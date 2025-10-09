<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\JadwalPpdb;
use App\Models\DaftarUlang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use Tests\Unit\BerkasExport;
// use App\Exports\BerkasExport;
use App\Exports\BerkasExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PendaftarExport;
use App\Exports\DaftarUlangExport;
use App\Exports\PendaftarTidakLolosExport; // New export class
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendaftarDiterimaExport;

class LaporanController extends Controller
{
    /**
     * Metode privat terpusat untuk membuat instance PDF dengan konfigurasi yang diperbaiki.
     * Mengatasi masalah ImageMagick dan konfigurasi DomPDF
     *
     * @param string $view Nama file blade view
     * @param array $data Data yang akan dikirim ke view
     * @param string $paper Ukuran kertas, default 'a4'
     * @param string $orientation Orientasi kertas, default 'landscape'
     * @return \Barryvdh\DomPDF\PDF
     */
    private function createPdf($view, $data, $paper = 'a4', $orientation = 'landscape')
    {
        try {
            // Fungsi helper untuk mengkodekan gambar ke base64 dengan error handling yang lebih baik
            $encodeBase64 = function ($path) {
                try {
                    // Cek apakah file benar-benar ada dan dapat dibaca
                    if (File::exists($path) && is_readable($path)) {
                        $imageData = File::get($path);
                        if ($imageData !== false) {
                            return base64_encode($imageData);
                        }
                    }
                    Log::warning('File gambar tidak dapat dibaca atau tidak ada di path: ' . $path);
                    return null;
                } catch (\Exception $e) {
                    Log::error('Error saat membaca file gambar: ' . $e->getMessage());
                    return null;
                }
            };

            // Tambahkan data umum yang dibutuhkan oleh semua PDF
            $data['logoPemda'] = $encodeBase64(public_path('kaiadmin/assets/img/kaiadmin/logoindramayu.png'));
            $data['logoSekolah'] = $encodeBase64(public_path('kaiadmin/assets/img/kaiadmin/favicon.png'));
            $data['tanggalCetak'] = now()->translatedFormat('d F Y');

            // Konfigurasi DomPDF dengan pengaturan yang lebih aman
            $pdf = Pdf::loadView($view, $data);

            // Set paper dan orientasi
            $pdf->setPaper($paper, $orientation);

            // Set options dengan konfigurasi yang diperbaiki
            $pdf->setOptions([
                // Disable ImageMagick completely
                'enable_imagick' => false,

                // Enable GD untuk pemrosesan gambar
                'enable_gd' => true,

                // PHP dan HTML5 parser
                'isPhpEnabled' => true,
                'isHtml5ParserEnabled' => true,

                // Font configuration
                'default_font' => 'DejaVu Sans',
                'font_height_ratio' => 1.1,

                // Memory and performance
                'memory_limit' => '512M',
                'dpi' => 96,

                // Security and remote content
                'isRemoteEnabled' => false, // Disable untuk keamanan karena kita pakai base64
                'chroot' => realpath(base_path()),

                // Additional options
                'enable_css_float' => true,
                'enable_javascript' => false,
                'enable_font_subsetting' => false,

                // Logging for debugging
                'log_output_file' => storage_path('logs/dompdf.log'),
            ]);

            return $pdf;
        } catch (\Exception $e) {
            Log::error('Error dalam createPdf method: ' . $e->getMessage() . ' pada baris ' . $e->getLine());
            throw $e;
        }
    }

    // --- LAPORAN PENDAFTAR ---

    public function allPdf()
    {
        try {
            // Set memory limit untuk proses yang berat
            ini_set('memory_limit', '512M');
            set_time_limit(120); // 2 menit timeout

            $jadwalAktif = JadwalPpdb::aktif()->first();
            $pendaftar = User::where('role', 'siswa')
                ->with(['biodata'])
                ->orderBy('nama_lengkap')
                ->get();

            // Validasi data sebelum membuat PDF
            if ($pendaftar->isEmpty()) {
                Log::warning('Tidak ada data pendaftar untuk PDF');
                return redirect()->back()->with('warning', 'Tidak ada data pendaftar untuk dicetak.');
            }

            $pdf = $this->createPdf('admin.laporan.all_pdf', compact('pendaftar', 'jadwalAktif'));

            $filename = 'laporan_semua_pendaftar_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF semua pendaftar: ' . $e->getMessage() . ' di baris ' . $e->getLine() . ' file: ' . $e->getFile());
            return redirect()->back()->with('error', 'Gagal membuat PDF. Error: ' . $e->getMessage());
        }
    }

    // Method utama dengan nama camelCase untuk konsistensi
    public function siswaDiterimaPdf()
    {
        try {
            // Set memory limit
            ini_set('memory_limit', '512M');
            set_time_limit(120);

            $data = User::where('status_pendaftaran', 'lulus_seleksi')
                ->with('biodata')
                ->orderBy('nama_lengkap')
                ->get();

            // Validasi data
            if ($data->isEmpty()) {
                Log::warning('Tidak ada data siswa diterima untuk PDF');
                return redirect()->back()->with('warning', 'Tidak ada data siswa diterima untuk dicetak.');
            }

            $pdf = $this->createPdf('admin.laporan.siswa_diterima_pdf', ['data' => $data]);

            $filename = 'laporan_siswa_diterima_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF siswa diterima: ' . $e->getMessage() . ' di baris ' . $e->getLine() . ' file: ' . $e->getFile());
            return redirect()->back()->with('error', 'Gagal membuat PDF. Error: ' . $e->getMessage());
        }
    }

    // NEW: Method untuk siswa tidak lolos PDF
    public function siswaTidakLolosPdf()
    {
        try {
            // Set memory limit
            ini_set('memory_limit', '512M');
            set_time_limit(120);

            $data = User::whereIn('status_pendaftaran', [
                'tidak_lulus_seleksi',
                'berkas_tidak_lengkap',
                'tidak_memenuhi_syarat',
                'ditolak'
            ])
                ->with('biodata')
                ->orderBy('nama_lengkap')
                ->get();

            // Validasi data
            if ($data->isEmpty()) {
                Log::warning('Tidak ada data siswa tidak lolos untuk PDF');
                return redirect()->back()->with('warning', 'Tidak ada data siswa tidak lolos untuk dicetak.');
            }

            $pdf = $this->createPdf('admin.laporan.siswa_tidak_lolos_pdf', ['data' => $data]);

            $filename = 'laporan_siswa_tidak_lolos_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF siswa tidak lolos: ' . $e->getMessage() . ' di baris ' . $e->getLine() . ' file: ' . $e->getFile());
            return redirect()->back()->with('error', 'Gagal membuat PDF. Error: ' . $e->getMessage());
        }
    }

    public function allExcel()
    {
        try {
            $filename = 'laporan_pendaftar_semua_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new PendaftarExport('all'), $filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate Excel semua pendaftar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat Excel. Error: ' . $e->getMessage());
        }
    }

    public function siswaDiterimaExcel()
    {
        try {
            $filename = 'laporan_siswa_diterima_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new PendaftarDiterimaExport, $filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate Excel semua pendaftar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat Excel. Error: ' . $e->getMessage());
        }
    }

    // NEW: Method untuk siswa tidak lolos Excel
    public function siswaTidakLolosExcel()
    {
        try {
            $filename = 'laporan_siswa_tidak_lolos_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new PendaftarTidakLolosExport, $filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate Excel siswa tidak lolos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat Excel. Error: ' . $e->getMessage());
        }
    }

    // --- LAPORAN BERKAS ---

    public function berkasPdf()
    {
        try {
            // Set memory limit
            ini_set('memory_limit', '512M');
            set_time_limit(120);

            $pendaftar = User::where('role', 'siswa')
                ->whereNotNull('jalur_pendaftaran')
                ->with(['berkas', 'biodata'])
                ->orderBy('nama_lengkap')
                ->get();

            // Validasi data
            if ($pendaftar->isEmpty()) {
                Log::warning('Tidak ada data berkas untuk PDF');
                return redirect()->back()->with('warning', 'Tidak ada data berkas untuk dicetak.');
            }

            $pdf = $this->createPdf('admin.laporan.berkas_pdf', compact('pendaftar'));

            $filename = 'laporan_status_berkas_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF status berkas: ' . $e->getMessage() . ' di baris ' . $e->getLine() . ' file: ' . $e->getFile());
            return redirect()->back()->with('error', 'Gagal membuat PDF. Error: ' . $e->getMessage());
        }
    }

    public function berkasExcel(Request $request)
    {
        try {
            // Verify BerkasExport class exists
            if (!class_exists(\App\Exports\BerkasExport::class)) {
                throw new \Exception('BerkasExport class not found in App\Exports namespace');
            }

            $filename = 'laporan_status_berkas_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new BerkasExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Failed to generate Excel status berkas: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Gagal membuat Excel. Error: ' . $e->getMessage());
        }
    }

    public function daftarUlangExcel()
    {
        try {
            $filename = 'laporan_siswa_daftar_ulang_' . now()->format('Ymd_His') . '.xlsx';
            return Excel::download(new DaftarUlangExport, $filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate Excel Daftar Ulang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat Excel. Error: ' . $e->getMessage());
        }
    }
    // --- LAPORAN DAFTAR ULANG ---

    public function daftarUlangPdf()
    {
        try {
            // Set memory limit
            ini_set('memory_limit', '512M');
            set_time_limit(120);

            $daftarUlangs = DaftarUlang::with(['user.biodata', 'jadwalDaftarUlang'])
                ->orderBy('nomor_daftar_ulang')
                ->get();

            // Validasi data
            if ($daftarUlangs->isEmpty()) {
                Log::warning('Tidak ada data daftar ulang untuk PDF');
                return redirect()->back()->with('warning', 'Tidak ada data daftar ulang untuk dicetak.');
            }

            $pdf = $this->createPdf('admin.laporan.daftar_ulang_pdf', compact('daftarUlangs'));

            $filename = 'laporan_daftar_ulang_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF daftar ulang: ' . $e->getMessage() . ' di baris ' . $e->getLine() . ' file: ' . $e->getFile());
            return redirect()->back()->with('error', 'Gagal membuat PDF. Error: ' . $e->getMessage());
        }
    }

    // --- METHOD UNTUK MENAMPILKAN HALAMAN ---

    public function index()
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $pendaftar = User::where('role', 'siswa')->with(['biodata', 'berkas', 'orangTua', 'wali'])->orderBy('nama_lengkap')->get();
        return view('admin.laporan.index', compact('pendaftar', 'jadwalAktif'));
    }

    public function siswaDiterima(Request $request)
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $pendaftar = User::where('role', 'siswa')
            ->where('status_pendaftaran', 'lulus_seleksi')
            ->with(['biodata', 'berkas', 'orangTua', 'wali'])
            ->orderBy('nama_lengkap')
            ->get();
        return view('admin.laporan.siswa_diterima', compact('pendaftar', 'jadwalAktif'));
    }

    // NEW: Method untuk menampilkan halaman siswa tidak lolos
    public function siswaTidakLolos(Request $request)
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $pendaftar = User::where('role', 'siswa')
            ->whereIn('status_pendaftaran', [
                'tidak_lulus_seleksi',
                'berkas_tidak_lengkap',
                'tidak_memenuhi_syarat',
                'ditolak'
            ])
            ->with(['biodata', 'berkas', 'orangTua', 'wali'])
            ->orderBy('nama_lengkap')
            ->get();
        return view('admin.laporan.siswa_tidak_lolos', compact('pendaftar', 'jadwalAktif'));
    }

    public function berkas()
    {
        $pendaftar = User::where('role', 'siswa')
            ->whereNotNull('jalur_pendaftaran')
            ->with(['berkas.verifier', 'biodata'])
            ->orderBy('nama_lengkap')->get();
        return view('admin.laporan.berkas', compact('pendaftar'));
    }

    public function daftarUlang()
    {
        $daftarUlangs = DaftarUlang::with(['user.biodata', 'jadwalDaftarUlang'])
            ->orderBy('nomor_daftar_ulang')->get();
        return view('admin.laporan.daftar_ulang', compact('daftarUlangs'));
    }
}
