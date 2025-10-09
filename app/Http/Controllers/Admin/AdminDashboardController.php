<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Berita;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
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

        // Aktivitas terbaru (contoh - sesuaikan dengan model Anda)
        $recentActivities = User::where('role', 'siswa')
            ->latest()
            ->limit(10)
            ->get(['nama_lengkap', 'status_pendaftaran', 'created_at', 'updated_at']);

        // Progress PPDB (contoh perhitungan)
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

        $data = [
            'title' => 'Dashboard Admin',
            'description' => 'Halaman dashboard untuk admin Cerulean School',
            'menuDashboard' => 'active',

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

            // Informasi tambahan
            'progressPercentage' => round($progressPercentage, 1),
            'growthPercentage' => round($growthPercentage, 1),
            'currentDate' => Carbon::now()->translatedFormat('d F Y'),
            'currentUser' => Auth::user(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Get dashboard statistics via AJAX
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
     * Get monthly registration data for charts
     */
    public function getMonthlyData($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = User::where('role', 'siswa')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $monthlyData[] = [
                'month' => Carbon::create()->month($month)->translatedFormat('M'),
                'count' => $count
            ];
        }

        return response()->json($monthlyData);
    }

    /**
     * Get status distribution data
     */
    public function getStatusData()
    {
        $statusData = [
            [
                'status' => 'Menunggu Verifikasi',
                'count' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'menunggu_verifikasi')
                    ->count(),
                'color' => '#ffad46'
            ],
            [
                'status' => 'Berkas Diverifikasi',
                'count' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'berkas_diverifikasi')
                    ->count(),
                'color' => '#1f8ef1'
            ],
            [
                'status' => 'Lulus Seleksi',
                'count' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'lulus_seleksi')
                    ->count(),
                'color' => '#18d26e'
            ],
            [
                'status' => 'Tidak Lulus',
                'count' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'tidak_lulus')
                    ->count(),
                'color' => '#f25961'
            ]
        ];

        return response()->json($statusData);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities()
    {
        $activities = User::where('role', 'siswa')
            ->select(['nama_lengkap', 'status_pendaftaran', 'created_at', 'updated_at'])
            ->latest('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'nama' => $user->nama_lengkap,
                    'status' => $user->status_pendaftaran,
                    'waktu' => $user->updated_at->diffForHumans(),
                    'action' => $this->getActionText($user->status_pendaftaran),
                    'color' => $this->getStatusColor($user->status_pendaftaran)
                ];
            });

        return response()->json($activities);
    }

    /**
     * Get action text based on status
     */
    private function getActionText($status)
    {
        $actions = [
            'menunggu_verifikasi' => 'mendaftar',
            'berkas_diverifikasi' => 'diverifikasi',
            'lulus_seleksi' => 'lulus seleksi',
            'tidak_lulus' => 'tidak lulus',
        ];

        return $actions[$status] ?? 'melakukan aktivitas';
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            'menunggu_verifikasi' => 'warning',
            'berkas_diverifikasi' => 'info',
            'lulus_seleksi' => 'success',
            'tidak_lulus' => 'danger',
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Export dashboard data to Excel/PDF
     */
    public function exportData(Request $request)
    {
        $format = $request->input('format', 'excel');

        $data = [
            'total_pendaftar' => User::where('role', 'siswa')->count(),
            'berkas_diverifikasi' => User::where('role', 'siswa')
                ->where('status_pendaftaran', 'berkas_diverifikasi')
                ->count(),
            'siswa_lulus' => User::where('role', 'siswa')
                ->where('status_pendaftaran', 'lulus_seleksi')
                ->count(),
            'total_berita' => Berita::count(),
            'tanggal_export' => Carbon::now()->translatedFormat('d F Y H:i:s'),
        ];

        if ($format === 'pdf') {
            // Logic untuk export PDF
            return response()->json(['message' => 'Export PDF akan segera tersedia']);
        } else {
            // Logic untuk export Excel
            return response()->json(['message' => 'Export Excel akan segera tersedia']);
        }
    }

    /**
     * Get dashboard summary for mobile API
     */
    public function getSummary()
    {
        $summary = [
            'statistik_utama' => [
                'total_pendaftar' => User::where('role', 'siswa')->count(),
                'berkas_diverifikasi' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'berkas_diverifikasi')
                    ->count(),
                'siswa_lulus' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'lulus_seleksi')
                    ->count(),
                'total_berita' => Berita::count(),
            ],
            'aktivitas_hari_ini' => [
                'pendaftar_baru' => User::where('role', 'siswa')
                    ->whereDate('created_at', Carbon::today())
                    ->count(),
                'berkas_masuk' => User::where('role', 'siswa')
                    ->whereDate('updated_at', Carbon::today())
                    ->count(),
                'verifikasi_selesai' => User::where('role', 'siswa')
                    ->where('status_pendaftaran', 'berkas_diverifikasi')
                    ->whereDate('updated_at', Carbon::today())
                    ->count(),
            ],
            'waktu_update' => Carbon::now()->toISOString()
        ];

        return response()->json($summary);
    }
}
