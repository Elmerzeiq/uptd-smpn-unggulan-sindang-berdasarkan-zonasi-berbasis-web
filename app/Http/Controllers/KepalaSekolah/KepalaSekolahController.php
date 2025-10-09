<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Models\User;
use App\Models\Berita;
use App\Models\Comment;
use App\Models\JadwalPpdb;
use App\Models\DaftarUlang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KepalaSekolahController extends Controller
{
    /**
     * Dashboard untuk Kepala Sekolah
     */
    public function dashboard()
    {
        // Statistik dasar
        $totalPendaftar = User::where('role', 'siswa')->count();
        $berkasDisverifikasi = User::where('role', 'siswa')
            ->where('status_pendaftaran', 'berkas_diverifikasi')
            ->count();
        $siswaLulus = User::where('role', 'siswa')
            ->where('status_pendaftaran', 'lulus_seleksi')
            ->count();
        $totalBerita = Berita::count();

        // Statistik harian
        $pendaftarHariIni = User::where('role', 'siswa')
            ->whereDate('created_at', Carbon::today())
            ->count();
        $berkasHariIni = User::where('role', 'siswa')
            ->whereDate('updated_at', Carbon::today())
            ->count();
        $verifikasiHariIni = User::where('role', 'siswa')
            ->where('status_pendaftaran', 'berkas_diverifikasi')
            ->whereDate('updated_at', Carbon::today())
            ->count();
        $beritaHariIni = Berita::whereDate('created_at', Carbon::today())
            ->count();

        // Statistik bulanan untuk grafik
        $monthlyStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStats[] = User::where('role', 'siswa')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
        }

        // Statistik berdasarkan status
        $statusStats = [
            'menunggu_verifikasi' => User::where('role', 'siswa')
                ->where('status_pendaftaran', 'menunggu_verifikasi')
                ->count(),
            'berkas_diverifikasi' => $berkasDisverifikasi,
            'lulus_seleksi' => $siswaLulus,
            'tidak_lulus' => User::where('role', 'siswa')
                ->where('status_pendaftaran', 'tidak_lulus')
                ->count(),
        ];

        // Statistik berdasarkan jenis kelamin
        $genderStats = [
            'laki_laki' => User::where('role', 'siswa')
                ->whereHas('biodata', function ($query) {
                    $query->where('jns_kelamin', 'Laki-laki');
                })
                ->count(),
            'perempuan' => User::where('role', 'siswa')
                ->whereHas('biodata', function ($query) {
                    $query->where('jns_kelamin', 'Perempuan');
                })
                ->count(),
        ];

        // Aktivitas terbaru
        $recentActivities = User::where('role', 'siswa')
            ->latest()
            ->limit(10)
            ->get(['nama_lengkap', 'status_pendaftaran', 'created_at', 'updated_at']);

        // Progress PPDB
        $totalTarget = 200; // Target penerimaan siswa
        $progressPercentage = $totalTarget > 0 ? min(($siswaLulus / $totalTarget) * 100, 100) : 0;

        // Perbandingan dengan periode sebelumnya
        $previousMonthRegistrants = User::where('role', 'siswa')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $currentMonthRegistrants = User::where('role', 'siswa')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $growthPercentage = $previousMonthRegistrants > 0
            ? (($currentMonthRegistrants - $previousMonthRegistrants) / $previousMonthRegistrants) * 100
            : 0;

        // Komentar terbaru untuk admin
        $recentComments = Comment::with(['user'])
            ->where('from_role', 'kepala_sekolah')
            ->latest()
            ->limit(5)
            ->get();

        $data = [
            'title' => 'Dashboard Kepala Sekolah',
            'description' => 'Dashboard Monitoring PPDB untuk Kepala Sekolah',

            // Statistik utama
            'totalPendaftar' => $totalPendaftar,
            'berkasDisverifikasi' => $berkasDisverifikasi,
            'siswaLulus' => $siswaLulus,
            'totalBerita' => $totalBerita,

            // Statistik harian
            'pendaftarHariIni' => $pendaftarHariIni,
            'berkasHariIni' => $berkasHariIni,
            'verifikasiHariIni' => $verifikasiHariIni,
            'beritaHariIni' => $beritaHariIni,

            // Data untuk grafik
            'monthlyStats' => $monthlyStats,
            'statusStats' => $statusStats,
            'genderStats' => $genderStats,

            // Aktivitas terbaru
            'recentActivities' => $recentActivities,
            'recentComments' => $recentComments,

            // Informasi tambahan
            'progressPercentage' => round($progressPercentage, 1),
            'growthPercentage' => round($growthPercentage, 1),
            'currentDate' => Carbon::now()->translatedFormat('d F Y'),
            'currentUser' => Auth::user(),
        ];

        return view('kepala_sekolah.dashboard', $data);
    }

    /**
     * Laporan Semua Pendaftar
     */
    public function laporanSemuaPendaftar()
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $pendaftar = User::where('role', 'siswa')
            ->with(['biodata', 'berkas', 'orangTua', 'wali'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('kepala_sekolah.laporan.semua_pendaftar', compact('pendaftar', 'jadwalAktif'));
    }

    /**
     * Laporan Siswa Diterima
     */
    public function laporanSiswaDiterima()
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $pendaftar = User::where('role', 'siswa')
            ->where('status_pendaftaran', 'lulus_seleksi')
            ->with(['biodata', 'berkas', 'orangTua', 'wali'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('kepala_sekolah.laporan.siswa_diterima', compact('pendaftar', 'jadwalAktif'));
    }

    /**
     * Laporan Siswa Tidak Lolos
     */
    public function laporanSiswaTidakLolos()
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

        return view('kepala_sekolah.laporan.siswa_tidak_lolos', compact('pendaftar', 'jadwalAktif'));
    }

    /**
     * Laporan Status Berkas
     */
    public function laporanBerkas()
    {
        $pendaftar = User::where('role', 'siswa')
            ->whereNotNull('jalur_pendaftaran')
            ->with(['berkas.verifier', 'biodata'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('kepala_sekolah.laporan.berkas', compact('pendaftar'));
    }

    /**
     * Laporan Daftar Ulang
     */
    public function laporanDaftarUlang()
    {
        $daftarUlangs = DaftarUlang::with(['user.biodata', 'jadwalDaftarUlang'])
            ->orderBy('nomor_daftar_ulang')
            ->get();

        return view('kepala_sekolah.laporan.daftar_ulang', compact('daftarUlangs'));
    }

    /**
     * Halaman Komentar untuk Admin
     */
    public function komentarIndex()
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->where(function ($query) {
                $query->where('from_role', 'kepala_sekolah')
                    ->orWhere('to_role', 'kepala_sekolah');
            })
            ->latest()
            ->paginate(10);

        return view('kepala_sekolah.komentar.index', compact('comments'));
    }

    /**
     * Kirim Komentar ke Admin
     */
    public function komentarStore(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'from_role' => 'kepala_sekolah',
            'to_role' => 'admin',
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return redirect()->route('kepala-sekolah.komentar.index')
            ->with('success', 'Komentar berhasil dikirim ke Admin.');
    }

    /**
     * Reply Komentar
     */
    public function komentarReply(Request $request, Comment $comment)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'from_role' => 'kepala_sekolah',
            'to_role' => 'admin',
            'subject' => 'Re: ' . $comment->subject,
            'message' => $request->message,
            'priority' => $comment->priority,
            'status' => 'pending',
        ]);

        return redirect()->route('kepala-sekolah.komentar.index')
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Tandai komentar sebagai selesai
     */
    public function komentarComplete(Comment $comment)
    {
        $comment->update(['status' => 'completed']);

        return redirect()->route('kepala-sekolah.komentar.index')
            ->with('success', 'Komentar ditandai sebagai selesai.');
    }

    /**
     * Get statistics for AJAX
     */
    public function getStatistics()
    {
        $stats = [
            'total_pendaftar' => User::where('role', 'siswa')->count(),
            'berkas_diverifikasi' => User::where('role', 'siswa')
                ->where('status_pendaftaran', 'berkas_diverifikasi')
                ->count(),
            'siswa_lulus' => User::where('role', 'siswa')
                ->where('status_pendaftaran', 'lulus_seleksi')
                ->count(),
            'total_berita' => Berita::count(),
            'pendaftar_hari_ini' => User::where('role', 'siswa')
                ->whereDate('created_at', Carbon::today())
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export laporan (delegasi ke admin controller jika diperlukan)
     */
    public function exportLaporan(Request $request)
    {
        $type = $request->input('type', 'all');
        $format = $request->input('format', 'pdf');

        // Redirect ke controller admin untuk export
        return redirect()->route("admin.laporan.{$type}-{$format}");
    }
}
