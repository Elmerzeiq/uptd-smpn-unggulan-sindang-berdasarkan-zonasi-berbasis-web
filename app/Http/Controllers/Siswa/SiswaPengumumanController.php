<?php

namespace App\Http\Controllers\Siswa;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Pengumuman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// PASTIKAN SEMUA USE STATEMENT INI ADA DI ATAS FILE ANDA
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

class SiswaPengumumanController extends Controller
{
    /**
     * Tampilkan pengumuman hasil untuk siswa
     */
    public function index(): View
    {
        $user = Auth::guard('web')->user();
        if (!$user || $user->role !== 'siswa') {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai siswa.');
        }

        $user->load('biodata', 'kartuPendaftaran');
        $statusPendaftaran = $user->status_pendaftaran ?? 'akun_terdaftar';
        $targetPenerima = $this->determineTargetPenerima($statusPendaftaran);
        $statusText = $this->getStatusText($statusPendaftaran);

        $pengumumans = Pengumuman::where('tipe', 'pengumuman_hasil')
            ->where(function ($query) use ($targetPenerima) {
                $query->where('target_penerima', $targetPenerima)->orWhere('target_penerima', 'semua');
            })
            ->where('aktif', true)
            ->where(function ($query) {
                $query->where('tanggal', '<=', now())->orWhereNull('tanggal');
            })->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        $canDownloadHasil = in_array($statusPendaftaran, ['lulus_seleksi', 'tidak_lulus']);

        // Pastikan path view ini benar
        return view('siswa.pengumuman.index', compact(
            'pengumumans',
            'user',
            'statusPendaftaran',
            'statusText',
            'canDownloadHasil'
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
                return redirect()->route('siswa.pengumuman.index')
                    ->with('warning', 'Hasil Anda belum tersedia.');
            }

            $qrData = $this->generateQrDataHasil($user);

            // BLOK KODE YANG SUDAH DIPERBAIKI
            $qrResult = Builder::create()
                ->writer(new PngWriter())
                ->data($qrData)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(200)
                ->margin(2)
                ->build();

            $qrCodeBase64 = $qrResult->getDataUri();
            $qrCode = substr($qrCodeBase64, strpos($qrCodeBase64, ',') + 1);

            // Pastikan path view ini benar
            $pdf = Pdf::loadView('siswa.pengumuman.pdf-hasil', compact('user', 'qrCode'));
            $pdf->setPaper('A4', 'portrait');
            $filename = "hasil_ppdb_" . ($user->kartuPendaftaran->nomor_kartu ?? $user->id) . ".pdf";

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Error download PDF hasil for user " . ($user->id ?? 'unknown') . ": " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('siswa.pengumuman.index')
                ->with('error', 'Terjadi kesalahan saat mengunduh PDF hasil.');
        }
    }

    private function generateQrDataHasil($user): string
    {
        $kartu = $user->kartuPendaftaran;
        return json_encode([
            'type' => 'hasil_ppdb',
            'nomor_kartu' => $kartu->nomor_kartu ?? '',
            'nama_lengkap' => $user->nama_lengkap,
            'nisn' => $user->biodata->nisn ?? '',
            'status' => $user->status_pendaftaran,
            'hasil' => $user->status_pendaftaran === 'lulus_seleksi' ? 'DITERIMA' : 'TIDAK DITERIMA',
            'tanggal_pengumuman' => now()->format('Y-m-d'),
            'verify_url' => route('verify.hasil', $user->id), // Pastikan route ini ada
            'sekolah' => config('app.school_name', 'SMPN Unggulan Sindang'),
            'timestamp' => now()->timestamp
        ]);
    }

    private function determineTargetPenerima($status): string
    {
        switch ($status) {
            case 'lulus_seleksi':
            case 'daftar_ulang_selesai':
                return 'siswa_diterima';
            case 'tidak_lulus':
                return 'siswa_ditolak';
            default:
                return 'calon_siswa';
        }
    }

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
