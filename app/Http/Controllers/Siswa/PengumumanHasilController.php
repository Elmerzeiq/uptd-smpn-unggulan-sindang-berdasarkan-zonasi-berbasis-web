<?php

namespace App\Http\Controllers\Siswa;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Pengumuman;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class PengumumanHasilController extends Controller
{
    /**
     * Tampilkan SEMUA pengumuman (hasil dan umum) untuk siswa.
     */
    public function index(): View
    {
        $user = Auth::user()->load('biodata', 'kartuPendaftaran');
        $statusPendaftaran = $user->status_pendaftaran ?? 'akun_terdaftar';

        // Memanggil method helper yang sekarang sudah ada
        $targetPenerima = $this->determineTargetPenerima($statusPendaftaran);
        $statusText = $this->getStatusText($statusPendaftaran);

        $pengumumans = Pengumuman::where('aktif', true)
            ->where(function ($query) {
                $query->where('tanggal', '<=', now())->orWhereNull('tanggal');
            })
            ->where(function ($query) use ($targetPenerima) {
                $query->where('target_penerima', $targetPenerima)
                    ->orWhere('target_penerima', 'semua');
            })
            ->orderBy('priority', 'asc') // Gunakan priority jika ada
            ->latest('tanggal')
            ->paginate(10);

        $canDownloadHasil = in_array($statusPendaftaran, ['lulus_seleksi', 'tidak_lulus']);

        return view('siswa.pengumuman.index', compact(
            'pengumumans',
            'user',
            'statusPendaftaran',
            'canDownloadHasil',
            'statusText'
        ));
    }

    /**
     * Download PDF hasil individual siswa
     */
    public function downloadHasilPdf()
    {
        $user = Auth::guard('web')->user();
        try {
            if (!$user || $user->role !== 'siswa') {
                abort(403, 'Akses ditolak.');
            }
            $user->load('biodata', 'kartuPendaftaran');

            if (!in_array($user->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus'])) {
                return redirect()->route('siswa.pengumuman.index')->with('warning', 'Hasil Anda belum tersedia.');
            }

            $qrData = $this->generateQrDataHasil($user);

            $qrResult = Builder::create()->writer(new PngWriter())->data($qrData)
                ->encoding(new Encoding('UTF-8'))->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(150)->margin(2)->build();

            $qrCodeBase64 = $qrResult->getDataUri();
            $qrCode = substr($qrCodeBase64, strpos($qrCodeBase64, ',') + 1);

            $pdf = Pdf::loadView('siswa.pengumuman.pdf-hasil', compact('user', 'qrCode'));
            $pdf->setPaper('A4', 'portrait');
            $filename = "hasil_ppdb_" . (optional($user->kartuPendaftaran)->nomor_kartu ?? $user->id) . ".pdf";

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Error download PDF hasil for user " . ($user->id ?? 'unknown') . ": " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('siswa.pengumuman.index')->with('error', 'Terjadi kesalahan sistem saat mengunduh PDF.');
        }
    }

    // =================================================================
    // PERBAIKAN: Pastikan method verifyHasil ini ada di controller Anda
    // =================================================================
    public function verifyHasil($userId)
    {
        try {
            $user = User::with('biodata', 'kartuPendaftaran')->findOrFail($userId);

            if (!in_array($user->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus'])) {
                abort(404, 'Hasil tidak ditemukan atau belum tersedia');
            }

            // Buat view untuk halaman verifikasi
            // Contoh: resources/views/siswa/pengumuman/verify.blade.php
            return view('siswa.pengumuman.verify', compact('user'));
        } catch (\Exception $e) {
            Log::error("Error verifikasi hasil untuk user {$userId}: " . $e->getMessage());
            abort(404, 'Data verifikasi tidak ditemukan.');
        }
    }

    private function generateQrDataHasil($user): string
    {
        return json_encode([
            'type' => 'hasil_ppdb',
            'nomor_kartu' => optional($user->kartuPendaftaran)->nomor_kartu ?? '',
            'nama_lengkap' => $user->nama_lengkap,
            'nisn' => optional($user->biodata)->nisn ?? '',
            'status' => $user->status_pendaftaran,
            'hasil' => $user->status_pendaftaran === 'lulus_seleksi' ? 'DITERIMA' : 'TIDAK DITERIMA',
            'tanggal_pengumuman' => now()->format('Y-m-d'),
            'verify_url' => route('verify.hasil', $user->id), // Ini yang butuh route
            'sekolah' => config('app.school_name', 'SMPN Unggulan Sindang'),
            'timestamp' => now()->timestamp
        ]);
    }

    // private function generateQrDataHasil($user): string
    // {
    //     $kartu = $user->kartuPendaftaran;
    //     return json_encode([
    //         'type' => 'hasil_ppdb',
    //         'nomor_kartu' => $kartu->nomor_kartu ?? '',
    //         'nama_lengkap' => $user->nama_lengkap,
    //         'nisn' => $user->biodata->nisn ?? '',
    //         'status' => $user->status_pendaftaran,
    //         'hasil' => $user->status_pendaftaran === 'lulus_seleksi' ? 'DITERIMA' : 'TIDAK DITERIMA',
    //         'tanggal_pengumuman' => now()->format('Y-m-d'),
    //         'verify_url' => route('verify.hasil', $user->id),
    //         'sekolah' => config('app.school_name', 'SMPN Unggulan Sindang'),
    //         'timestamp' => now()->timestamp
    //     ]);
    // }

    // =================================================================
    // PERBAIKAN: Tambahkan dua method helper yang hilang di bawah ini
    // =================================================================

    /**
     * Tentukan target penerima pengumuman berdasarkan status pendaftaran siswa.
     */
    private function determineTargetPenerima($status): string
    {
        switch ($status) {
            case 'lulus_seleksi':
            case 'daftar_ulang_selesai':
                return 'siswa_diterima';
            case 'tidak_lulus':
                return 'siswa_ditolak';
            default:
                // Untuk status seperti 'pending', 'verified', dll.
                return 'calon_siswa';
        }
    }

    /**
     * Dapatkan teks status yang user-friendly.
     */
    private function getStatusText($status): string
    {
        $statusTexts = [
            'akun_terdaftar' => 'Akun Terdaftar',
            'biodata_lengkap' => 'Biodata Lengkap',
            'berkas_uploaded' => 'Berkas Sudah Diupload',
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Berkas Terverifikasi',
            'lulus_seleksi' => 'Diterima',
            'tidak_lulus' => 'Tidak Diterima',
            'daftar_ulang_selesai' => 'Daftar Ulang Selesai'
        ];
        return $statusTexts[$status] ?? 'Status Tidak Dikenal';
    }
}
