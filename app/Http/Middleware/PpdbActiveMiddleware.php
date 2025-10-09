<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ekskul;
use App\Models\JadwalPpdb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class PpdbActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $feature Fitur PPDB yang ingin dicek ('pendaftaran_registrasi', 'pendaftaran_upload', 'pengumuman', 'daftar_ulang')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $feature = 'pendaftaran_registrasi'): Response
    {
        // Ambil jadwal PPDB yang sedang aktif
        $jadwal = JadwalPpdb::aktif();// Gunakan metode getAktif() yang sudah diperbarui

        $user = Auth::user(); // Mendapatkan user yang sedang login (bisa null jika tamu)

        // 1. Jika tidak ada jadwal PPDB yang aktif sama sekali
        if (!$jadwal) {
            if ($user && $user->role === 'siswa') {
                return redirect()->route('siswa.dashboard')->with('warning', 'Tidak ada jadwal SPMB aktif.');
            }
            return redirect()->route('home')->with('warning', 'Tidak ada jadwal SPMB aktif.');
        }

        $isAllowed = false;
        $errorMessage = 'Fitur SPMB yang Anda coba akses saat ini di luar periode yang ditentukan.';

        // 2. Cek berdasarkan fitur yang diminta
        switch ($feature) {
            case 'pendaftaran_registrasi':
                $isAllowed = $jadwal->isPendaftaranOpen();
                break;
            case 'pendaftaran_upload': // Untuk siswa mengisi biodata & upload berkas (/siswa/biodata, /siswa/berkas)
                $isAllowed = $jadwal->isPengisianDataOpen(); // Gunakan isPengisianDataOpen() yang sesuai
                $errorMessage = 'Periode pengisian data dan upload berkas belum dibuka atau sudah ditutup.';
                break;
            case 'pengumuman': // Untuk siswa melihat hasil seleksi (/siswa/pengumuman-hasil)
                $isAllowed = $jadwal->isPengumumanOpen();
                $errorMessage = 'Pengumuman hasil seleksi SPMB belum tersedia atau sudah ditutup.';
                break;
            case 'daftar_ulang': // Untuk siswa mengunduh kartu dan info daftar ulang (/siswa/kartu-pendaftaran/download)
                $isAllowed = $jadwal->isDaftarUlangOpen();
                $errorMessage = 'Periode daftar ulang belum dibuka atau sudah ditutup.';
                break;
            default:
                $isAllowed = $jadwal->is_active;
                $errorMessage = 'Fitur SPMB umum tidak aktif saat ini.';
        }

        // 3. Pengecualian (Siswa yang sudah di status final mungkin masih bisa lihat hasil/unduh kartu)
        if (
            ($feature === 'pengumuman' || $feature === 'daftar_ulang') &&
            $user && $user->role === 'siswa' &&
            in_array($user->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai'])
        ) {
            $isAllowed = true;
        }

        // 4. Jika tidak diizinkan, redirect dengan pesan flash
        if (!$isAllowed) {
            if ($user && $user->role === 'siswa') {
                return redirect()->route('siswa.dashboard')->with('warning', $errorMessage);
            }
            return redirect()->route('login')->with('error', $errorMessage);
        }

        // 5. Jika diizinkan, share variabel $jadwal ke semua view
        view()->share('jadwalPpdbAktifGlobal', $jadwal);
        view()->share('ppdbFeatureActiveGlobal', $feature);

        return $next($request);
    }
}
