<?php

namespace App\Http\Controllers\Siswa;

use App\Models\User;
use App\Models\Wali;
use App\Models\OrangTua;
use App\Models\JadwalPpdb;
use Illuminate\Http\Request;
use App\Models\BiodataSiswa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    /**
     * Menampilkan form biodata.
     */
    public function index()
    {
        $user = User::with('biodata', 'orangTua', 'wali')->find(Auth::id());
        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }

        $biodata = $user->biodata ?? new BiodataSiswa(['user_id' => $user->id]);
        $orangTua = $user->orangTua ?? new OrangTua(['user_id' => $user->id]);
        $wali = $user->wali ?? new Wali(['user_id' => $user->id]);

        $jadwalSiswaAktif = JadwalPpdb::aktif()->first();

        // Logika untuk menentukan apakah form bisa diedit
        $allowEdit = false;
        $statusPendaftaran = $user->status_pendaftaran ?? 'akun_terdaftar';

        // Siswa bisa edit jika status 'berkas_tidak_lengkap' dan jadwal pendaftaran masih buka
        if ($statusPendaftaran === 'berkas_tidak_lengkap') {
            $allowEdit = $jadwalSiswaAktif && $jadwalSiswaAktif->isPendaftaranOpen();
        } else {
            // Status final yang mengunci pengeditan
            $finalStatuses = ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai'];
            if (!in_array($statusPendaftaran, $finalStatuses)) {
                $allowEdit = $jadwalSiswaAktif && $jadwalSiswaAktif->isPendaftaranOpen();
            }
        }

        return view('siswa.biodata.index', compact('user', 'biodata', 'orangTua', 'wali', 'allowEdit'));
    }

    /**
     * Menyimpan atau memperbarui data biodata, orang tua, dan wali.
     */
    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        // Validasi jadwal dan status pendaftaran
        $jadwalSiswaAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalSiswaAktif || !$jadwalSiswaAktif->isPendaftaranOpen()) {
            return redirect()->route('siswa.biodata.index')->with('error', 'Periode pengisian biodata sudah ditutup.');
        }

        $finalStatuses = ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai'];
        if (in_array($user->status_pendaftaran, $finalStatuses)) {
            return redirect()->route('siswa.biodata.index')->with('warning', 'Data Anda sudah diverifikasi dan tidak dapat diubah lagi.');
        }

        // Validasi data siswa
        $validatedSiswa = $request->validate([
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tgl_lahir' => ['required', 'date', 'before_or_equal:' . now()->subYears(10)->format('Y-m-d')],
            'jns_kelamin' => ['required', 'in:L,P'],
            'agama' => ['required', 'in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu'],
            'anak_ke' => ['required', 'integer', 'min:1'],
            'jml_saudara_kandung' => ['required', 'integer', 'min:0'],
            'asal_sekolah' => ['required', 'string', 'max:255'],
            'alamat_asal_sekolah' => ['required', 'string', 'max:500'],
            'alamat_rumah' => ['required', 'string', 'max:500'],
            'kelurahan_desa' => ['required', 'string', 'max:100'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'kode_pos' => ['required', 'string', 'max:5'],
            'tinggi_badan' => ['nullable', 'numeric', 'min:0'],
            'berat_badan' => ['nullable', 'numeric', 'min:0'],
            'lingkar_kepala' => ['nullable', 'numeric', 'min:0'],
            'npsn_asal_sekolah' => ['nullable', 'string', 'max:8'],
            'foto_siswa' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Validasi data orang tua
        $validatedOrtu = $request->validate([
            'nama_ayah' => 'nullable|string|max:100',
            'nik_ayah' => 'nullable|string|digits:16',
            'pendidikan_ayah' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'required|string|max:100',
            'nik_ibu' => 'nullable|string|digits:16',
            'pendidikan_ibu' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan_ibu' => 'nullable|string|max:100',
        ]);

        // Validasi data wali
        $validatedWali = $request->validate([
            'nama_wali' => 'nullable|string|max:100',
            'nik_wali' => 'nullable|string|digits:16',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'hubungan_wali_dgn_siswa' => 'nullable|string|max:50',
            'alamat_wali' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($user, $request, $validatedSiswa, $validatedOrtu, $validatedWali) {
                // Handle upload foto
                if ($request->hasFile('foto_siswa')) {
                    if ($user->biodata && $user->biodata->foto_siswa) {
                        Storage::disk('public')->delete($user->biodata->foto_siswa);
                    }
                    $nisn = $user->nisn ?? $user->id;
                    $validatedSiswa['foto_siswa'] = $request->file('foto_siswa')->store("foto_siswa_ppdb/{$nisn}", 'public');
                }

                // Update atau buat data
                $user->biodata()->updateOrCreate(['user_id' => $user->id], $validatedSiswa);
                $user->orangTua()->updateOrCreate(['user_id' => $user->id], $validatedOrtu);

                // Cek apakah data wali diisi
                if (collect($validatedWali)->filter()->isNotEmpty()) {
                    $user->wali()->updateOrCreate(['user_id' => $user->id], $validatedWali);
                } else {
                    $user->wali()->delete(); // Hapus data wali jika kosong
                }

                // Update status pendaftaran
                $this->updateUserStatusAfterDataCompletion($user);
            });

            return redirect()->route('siswa.dashboard')->with('success', 'Data biodata berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Helper untuk update status pendaftaran user setelah pengisian data.
     */
    private function updateUserStatusAfterDataCompletion(User $user)
    {
        $user->refresh(); // Ambil data terbaru dari database

        // Jangan ubah status jika sudah final
        $finalStatuses = ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai'];
        if (in_array($user->status_pendaftaran, $finalStatuses)) {
            return;
        }

        $biodataLengkap = $user->biodata && $user->biodata->tempat_lahir && $user->orangTua && $user->orangTua->nama_ibu;
        $berkasLengkap = $user->hasCompletedRequiredBerkas();

        if ($biodataLengkap && $berkasLengkap) {
            $user->status_pendaftaran = 'menunggu_verifikasi_berkas';
            $user->catatan_verifikasi = null; // Hapus catatan lama jika data sudah lengkap
        } else {
            $user->status_pendaftaran = 'menunggu_kelengkapan_data';
        }

        $user->save();
    }
}
