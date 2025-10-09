<?php

namespace App\Http\Controllers\Siswa;

use App\Models\User;
use App\Models\JadwalPpdb;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BerkasHelper;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Validasi user
        if (!$user || !($user instanceof \Illuminate\Database\Eloquent\Model)) {
            Log::warning('SiswaDashboardController::index - Invalid user session', [
                'auth_check' => Auth::check(),
                'user_exists' => !!$user,
            ]);
            return redirect()->route('login')->with('error', 'Sesi pengguna tidak valid.');
        }

        // Load relasi yang dibutuhkan
        $user->load(['biodata', 'orangTua', 'wali', 'berkas', 'kartuPendaftaran']);

        // Ambil jadwal aktif
        $jadwalAktif = JadwalPpdb::aktif()->first();

        // Definisi berkas berdasarkan jalur
        $agama = optional($user->biodata)->agama ?? 'Islam';
        $definisiBerkas = \App\Helpers\BerkasHelper::getBerkasListForJalur(optional($user)->jalur_pendaftaran, $agama);

        // Progress steps logic - DIPERBAIKI UNTUK SINKRONISASI
        $progressStepsDefinition = $this->getProgressStepsDefinition($user, $jadwalAktif, $definisiBerkas);

        // Filter progress steps berdasarkan kondisi
        $progressSteps = [];
        $completedCount = 0;
        $activeStepKey = null;

        foreach ($progressStepsDefinition as $key => $step) {
            if (!isset($step['condition']) || $step['condition']) {
                $progressSteps[$key] = $step;
                if ($step['is_complete']) {
                    $completedCount++;
                } elseif (is_null($activeStepKey)) {
                    $activeStepKey = $key;
                }
            }
        }

        // Hitung persentase progress
        $totalVisibleSteps = count($progressSteps);
        $progressPercentage = ($totalVisibleSteps > 0) ? round(($completedCount / $totalVisibleSteps) * 100) : 0;

        // Tentukan label langkah saat ini
        $currentStepLabel = "Semua Tahap Selesai";
        if (!is_null($activeStepKey) && isset($progressSteps[$activeStepKey])) {
            $currentStepLabel = Str::after($progressSteps[$activeStepKey]['label'], '. ');
        } elseif ($progressPercentage < 100 && $totalVisibleSteps > 0) {
            foreach ($progressSteps as $step) {
                if (!$step['is_complete']) {
                    $currentStepLabel = Str::after($step['label'], '. ');
                    break;
                }
            }
        }

        // ========================================
        // ENHANCED ZONASI MAP DATA
        // ========================================

        // Koordinat sekolah dari config
        $koordinatSekolah = config('ppdb.koordinat_sekolah', ['lat' => -6.3390, 'lng' => 108.3225]);

        // Parsing koordinat siswa yang lebih robust
        $koordinatSiswa = null;
        if (!empty($user->koordinat_domisili_siswa)) {
            $koordinatArray = explode(',', $user->koordinat_domisili_siswa);
            if (count($koordinatArray) === 2 && is_numeric(trim($koordinatArray[0])) && is_numeric(trim($koordinatArray[1]))) {
                $koordinatSiswa = [
                    'lat' => (float) trim($koordinatArray[0]),
                    'lng' => (float) trim($koordinatArray[1])
                ];
            }
        }

        // Fallback ke koordinat kecamatan/desa jika koordinat siswa tidak ada
        if (!$koordinatSiswa) {
            $kecamatanDomisiliLower = strtolower(trim($user->kecamatan_domisili ?? ''));
            $desaDomisiliRaw = $user->desa_kelurahan_domisili ?? $user->desa_domisili ?? '';
            $desaDomisiliLower = strtolower(trim($desaDomisiliRaw));

            // Coba ambil dari konfigurasi desa terlebih dahulu
            if ($desaDomisiliLower) {
                $desaKey = str_replace([' ', '_', '-'], '', $desaDomisiliLower);
                $desaKoordinat = config("ppdb.desa_koordinat.{$desaKey}", null);

                if ($desaKoordinat && isset($desaKoordinat['lat']) && isset($desaKoordinat['lng'])) {
                    $koordinatSiswa = [
                        'lat' => (float) $desaKoordinat['lat'],
                        'lng' => (float) $desaKoordinat['lng']
                    ];
                }
            }

            // Fallback ke kecamatan
            if (!$koordinatSiswa && $kecamatanDomisiliLower) {
                $kecamatanKoordinat = config("ppdb.kecamatan_koordinat.{$kecamatanDomisiliLower}", null);
                if ($kecamatanKoordinat && isset($kecamatanKoordinat['lat']) && isset($kecamatanKoordinat['lng'])) {
                    $koordinatSiswa = [
                        'lat' => (float) $kecamatanKoordinat['lat'],
                        'lng' => (float) $kecamatanKoordinat['lng']
                    ];
                }
            }
        }

        // ========================================
        // ZONASI LOGIC - NEW ENHANCEMENT
        // ========================================

        $zonasiData = $this->calculateZonasiData($koordinatSekolah, $koordinatSiswa, $user);

        // Data untuk view
        $kecamatanDomisili = $user->kecamatan_domisili;
        $desaDomisili = $user->desa_kelurahan_domisili ?? $user->desa_domisili;

        // Tentukan apakah peta bisa ditampilkan
        $canShowMap = $user->jalur_pendaftaran === 'domisili' && (
            $koordinatSiswa ||
            (!empty($koordinatSekolah['lat']) && !empty($koordinatSekolah['lng']))
        );

        return view('siswa.dashboard', compact(
            'user',
            'jadwalAktif',
            'progressPercentage',
            'progressSteps',
            'currentStepLabel',
            'koordinatSekolah',
            'koordinatSiswa',
            'kecamatanDomisili',
            'desaDomisili',
            'canShowMap',
            'zonasiData'
        ));
    }

    /**
     * FUNGSI BARU - Definisi progress steps yang sama dengan sidebar
     */
    private function getProgressStepsDefinition($user, $jadwalAktif, $definisiBerkas)
    {
        // Status jadwal
        $canFillData = $jadwalAktif && $jadwalAktif->isPendaftaranOpen();
        $canSeeAnnouncement = $jadwalAktif && $jadwalAktif->isPengumumanOpen();

        // Status kelengkapan data - DIPERBAIKI
        $biodataLengkap = optional($user)->biodata &&
            optional($user)->orangTua &&
            !empty(optional($user->biodata)->nama_lengkap_siswa ?? optional($user->biodata)->tempat_lahir) &&
            !empty(optional($user->orangTua)->nama_ibu);

        $berkasLengkap = $this->isBerkasWajibLengkap($user, $definisiBerkas);

        return [
            'akun_terdaftar' => [
                'label' => '1. Registrasi Akun',
                'is_complete' => true,
                'icon' => 'fas fa-user-check'
            ],
            'isi_biodata' => [
                'label' => '2. Isi Biodata & Keluarga',
                'is_complete' => $biodataLengkap,
                'icon' => 'fas fa-id-card',
                'link' => route('siswa.biodata.index'),
            ],
            'upload_berkas' => [
                'label' => '3. Upload Berkas',
                'is_complete' => $berkasLengkap,
                'icon' => 'fas fa-folder-open',
                'condition' => $biodataLengkap,
                'link' => route('siswa.berkas.index'),
            ],
            'verifikasi_panitia' => [
                'label' => '4. Verifikasi Panitia',
                'is_complete' => in_array(optional($user)->status_pendaftaran, [
                    'berkas_diverifikasi',
                    'lulus_seleksi',
                    'tidak_lulus_seleksi',
                    'daftar_ulang_selesai'
                ]),
                'icon' => 'fas fa-user-shield',
            ],
            'pengumuman_hasil' => [
                'label' => '5. Pengumuman Hasil',
                'is_complete' => in_array(optional($user)->status_pendaftaran, [
                    'lulus_seleksi',
                    'tidak_lulus_seleksi'
                ]),
                'icon' => 'fas fa-bullhorn',
                'link' => route('siswa.pengumuman.index'),
            ],
            'daftar_ulang' => [
                'label' => '6. Daftar Ulang',
                'is_complete' => (optional($user)->status_daftar_ulang === 'selesai'),
                'icon' => 'fas fa-print',
                'condition' => (optional($user)->status_pendaftaran === 'lulus_seleksi'),
                'link' => route('siswa.daftar-ulang.index'),
            ],
        ];
    }

    /**
     * Hitung data zonasi berdasarkan koordinat
     */
    private function calculateZonasiData($koordinatSekolah, $koordinatSiswa, $user)
    {
        // Definisi zona sesuai dengan aturan PPDB
        $zonaDefinition = [
            [
                'name' => 'Zona Inti',
                'max_distance' => 1.5,
                'color' => '#28a745',
                'priority' => 1,
                'description' => '0 - 1.5 km dari sekolah',
                'quota_percentage' => 50
            ],
            [
                'name' => 'Zona Penyangga',
                'max_distance' => 3.0,
                'color' => '#ffc107',
                'priority' => 2,
                'description' => '1.5 - 3 km dari sekolah',
                'quota_percentage' => 30
            ],
            [
                'name' => 'Zona Luar',
                'max_distance' => 5.0,
                'color' => '#fd7e14',
                'priority' => 3,
                'description' => '3 - 5 km dari sekolah',
                'quota_percentage' => 15
            ],
            [
                'name' => 'Di Luar Zona',
                'max_distance' => PHP_FLOAT_MAX,
                'color' => '#dc3545',
                'priority' => 4,
                'description' => 'Lebih dari 5 km dari sekolah',
                'quota_percentage' => 5
            ]
        ];

        $zonasiData = [
            'zones' => $zonaDefinition,
            'student_zone' => null,
            'distance' => null,
            'priority_level' => null,
            'eligibility_status' => 'unknown'
        ];

        // Hitung jarak jika koordinat tersedia
        if (
            $koordinatSekolah && $koordinatSiswa &&
            isset($koordinatSekolah['lat']) && isset($koordinatSekolah['lng']) &&
            isset($koordinatSiswa['lat']) && isset($koordinatSiswa['lng'])
        ) {

            $distance = $this->calculateHaversineDistance(
                $koordinatSekolah['lat'],
                $koordinatSekolah['lng'],
                $koordinatSiswa['lat'],
                $koordinatSiswa['lng']
            );

            $zonasiData['distance'] = round($distance, 2);

            // Tentukan zona siswa
            foreach ($zonaDefinition as $zona) {
                if ($distance <= $zona['max_distance']) {
                    $zonasiData['student_zone'] = $zona;
                    $zonasiData['priority_level'] = $zona['priority'];
                    break;
                }
            }

            // Update jarak ke database jika belum ada
            if (empty($user->jarak_ke_sekolah) || abs($user->jarak_ke_sekolah - $distance) > 0.1) {
                $user->update(['jarak_ke_sekolah' => $distance]);
            }

            // Tentukan status kelayakan
            $zonasiData['eligibility_status'] = $distance <= 5.0 ? 'eligible' : 'not_prioritized';
        }

        return $zonasiData;
    }

    /**
     * Hitung jarak menggunakan formula Haversine
     */
    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Cek apakah berkas wajib sudah lengkap
     */
    private function isBerkasWajibLengkap(User $user, array $definisiBerkas): bool
    {
        if (!optional($user)->berkas) {
            return false;
        }

        foreach ($definisiBerkas as $field => $details) {
            if ($details['required']) {
                if (isset($details['multiple']) && $details['multiple']) {
                    $fileArray = json_decode($user->berkas->$field, true);
                    if (empty($fileArray)) {
                        return false;
                    }
                } else {
                    if (empty($user->berkas->$field)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
