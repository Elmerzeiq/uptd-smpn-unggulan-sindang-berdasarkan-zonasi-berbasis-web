<?php

namespace App\Http\Controllers\Siswa;

use App\Models\User;
use App\Models\DaftarUlang;
use Illuminate\Http\Request;
// Hapus atau ganti dengan implementasi Anda
// use App\Services\BerkasService;
use App\Models\DetailBiayaSiswa;
use App\Models\JadwalDaftarUlang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\KomponenBiayaDaftarUlang;
use Illuminate\Support\Facades\Validator;

class DaftarUlangSiswaController extends Controller
{
    // Hapus atau ganti dengan implementasi Anda
    // protected $berkasService;
    // public function __construct(BerkasService $berkasService)
    // {
    //     $this->berkasService = $berkasService;
    // }

    public function index()
    {
        $user = Auth::user();
        if ($user->status_pendaftaran !== 'lulus_seleksi' && $user->status_pendaftaran !== 'daftar_ulang_selesai') {
            return redirect()->route('siswa.dashboard')->with('warning', 'Anda belum lulus seleksi.');
        }

        $user->load(['biodata', 'orangTua', 'berkas']);
        $daftarUlang = $user->daftarUlang()->with(['jadwal', 'detailBiaya.komponenBiaya'])->firstOrNew(['user_id' => $user->id]);

        // Ganti dengan logika Anda untuk mendapatkan daftar berkas
        $daftarBerkas = [];
        // if ($user->biodata) {
        //     $daftarBerkas = $this->berkasService->getBerkasListByJalur($user->jalur_pendaftaran, $user->biodata->agama);
        // }

        $jadwalTersedia = JadwalDaftarUlang::available()->orderBy('tanggal')->orderBy('waktu_mulai')->get();
        $komponenBiaya = KomponenBiayaDaftarUlang::aktif()->orderBy('is_wajib', 'desc')->get();
        $totalBiayaDefault = $komponenBiaya->where('is_wajib', true)->sum('biaya');
        $bankInfo = config('ppdb.bank');

        return view('siswa.daftar-ulang.index', compact(
            'user',
            'daftarUlang',
            'daftarBerkas',
            'jadwalTersedia',
            'komponenBiaya',
            'totalBiayaDefault',
            'bankInfo'
        ));
    }

    public function uploadKartuLolos(Request $request)
    {
        $user = Auth::user();
        $request->validate(['kartu_lolos_seleksi' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']]);

        DB::transaction(function () use ($request, $user) {
            $daftarUlang = $user->daftarUlang()->firstOrNew(['user_id' => $user->id]);
            if (!$daftarUlang->exists) {
                $daftarUlang->nomor_daftar_ulang = \App\Models\DaftarUlang::generateNomorDaftarUlang();
            }
            if ($daftarUlang->kartu_lolos_seleksi) {
                Storage::disk('public')->delete($daftarUlang->kartu_lolos_seleksi);
            }
            $uploadPath = "daftar_ulang/{$user->id}_{$user->nisn}";
            $daftarUlang->kartu_lolos_seleksi = $request->file('kartu_lolos_seleksi')->store($uploadPath, 'public');
            if ($daftarUlang->status_daftar_ulang === 'belum_daftar' || is_null($daftarUlang->status_daftar_ulang)) {
                $daftarUlang->status_daftar_ulang = 'menunggu_verifikasi_berkas';
            }
            $daftarUlang->save();
        });

        return redirect()->route('siswa.daftar-ulang.index')->with('success', 'Kartu lolos seleksi berhasil diupload.');
    }

    public function pilihJadwal(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'jadwal_id' => ['required', 'exists:jadwal_daftar_ulangs,id'],
            'komponen_biaya' => ['nullable', 'array'],
            'komponen_biaya.*' => ['exists:komponen_biaya_daftar_ulang,id']
        ]);

        $jadwal = JadwalDaftarUlang::find($request->jadwal_id);
        if ($jadwal->isFull()) {
            return redirect()->back()->with('error', 'Jadwal yang dipilih sudah penuh.');
        }

        DB::transaction(function () use ($request, $user, $jadwal) {
            $daftarUlang = $user->daftarUlang()->firstOrFail();
            if ($daftarUlang->jadwal_id && $daftarUlang->jadwal_id !== $request->jadwal_id) {
                JadwalDaftarUlang::find($daftarUlang->jadwal_id)?->decrement('terisi');
            }

            $daftarUlang->jadwal_id = $request->jadwal_id;
            $jadwal->increment('terisi');
            $daftarUlang->detailBiaya()->delete();

            $komponenDipilih = collect($request->komponen_biaya ?? []);
            $komponenWajib = KomponenBiayaDaftarUlang::wajib()->aktif()->pluck('id');
            $semuaKomponenIds = $komponenDipilih->merge($komponenWajib)->unique();
            $semuaKomponen = KomponenBiayaDaftarUlang::find($semuaKomponenIds);

            $detailBiaya = $semuaKomponen->map(function ($komponen) use ($daftarUlang) {
                return [
                    'daftar_ulang_id' => $daftarUlang->id,
                    'komponen_biaya_id' => $komponen->id,
                    'biaya' => $komponen->biaya,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            });

            DetailBiayaSiswa::insert($detailBiaya->toArray());
            $daftarUlang->total_biaya = $semuaKomponen->sum('biaya');
            $daftarUlang->save();
        });

        return redirect()->route('siswa.daftar-ulang.index')->with('success', 'Jadwal berhasil dipilih.');
    }

    public function uploadBuktiPembayaran(Request $request)
    {
        $user = Auth::user();
        $daftarUlang = $user->daftarUlang;
        if (!$daftarUlang || !$daftarUlang->jadwal_id) {
            return redirect()->back()->with('error', 'Anda belum memilih jadwal.');
        }

        $request->validate([
            'bukti_pembayaran' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'tanggal_pembayaran' => ['required', 'date', 'before_or_equal:today']
        ]);

        DB::transaction(function () use ($request, $daftarUlang) {
            if ($daftarUlang->bukti_pembayaran) {
                Storage::disk('public')->delete($daftarUlang->bukti_pembayaran);
            }
            $uploadPath = "daftar_ulang/{$daftarUlang->user->id}_{$daftarUlang->user->nisn}";
            $daftarUlang->bukti_pembayaran = $request->file('bukti_pembayaran')->store($uploadPath, 'public');
            $daftarUlang->tanggal_pembayaran = $request->tanggal_pembayaran;
            $daftarUlang->status_pembayaran = 'menunggu_verifikasi';
            $daftarUlang->status_daftar_ulang = 'menunggu_verifikasi_pembayaran';
            $daftarUlang->save();
        });

        return redirect()->route('siswa.daftar-ulang.index')->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    public function deleteFile(Request $request, $type)
    {
        $user = Auth::user();
        $daftarUlang = $user->daftarUlang;
        if (!$daftarUlang || !in_array($type, ['kartu_lolos_seleksi', 'bukti_pembayaran'])) {
            return back()->with('error', 'Data atau tipe file tidak valid.');
        }
        if ($daftarUlang->status_daftar_ulang === 'daftar_ulang_selesai') {
            return back()->with('error', 'Tidak dapat menghapus file karena daftar ulang sudah selesai.');
        }

        DB::transaction(function () use ($daftarUlang, $type) {
            if ($daftarUlang->$type) {
                Storage::disk('public')->delete($daftarUlang->$type);
                $daftarUlang->$type = null;
                if ($type === 'bukti_pembayaran') {
                    $daftarUlang->status_pembayaran = 'belum_bayar';
                    $daftarUlang->tanggal_pembayaran = null;
                    if ($daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_pembayaran') {
                        $daftarUlang->status_daftar_ulang = 'berkas_diverifikasi';
                    }
                }
                $daftarUlang->save();
            }
        });

        return redirect()->route('siswa.daftar-ulang.index')->with('success', 'File berhasil dihapus.');
    }

    public function infoPembayaran()
    {
        $user = Auth::user();
        $daftarUlang = $user->daftarUlang;
        if (!$daftarUlang || !$daftarUlang->jadwal_id) {
            return redirect()->route('siswa.daftar-ulang.index')->with('error', 'Anda belum memilih jadwal.');
        }

        $daftarUlang->load(['detailBiaya.komponenBiaya', 'jadwal']);
        $bankInfo = config('ppdb.bank');

        return view('siswa.daftar-ulang.pembayaran', compact('daftarUlang', 'bankInfo'));
    }
}
