<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JadwalPpdb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeleksiController extends Controller
{
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // Jarak dalam kilometer
    }

    private function calculateStudentDistance($user)
    {
        $sekolahCoord = config('ppdb.koordinat_sekolah');
        $kecamatanCoords = config('ppdb.kecamatan_koordinat', []);
        $desaCoords = config('ppdb.desa_koordinat', []);

        $jarak = 9999; // Default jarak besar

        if (!empty($user->koordinat_domisili_siswa)) {
            $koordinatArray = explode(',', $user->koordinat_domisili_siswa);
            if (count($koordinatArray) === 2 && is_numeric(trim($koordinatArray[0])) && is_numeric(trim($koordinatArray[1]))) {
                $lat = (float) trim($koordinatArray[0]);
                $lng = (float) trim($koordinatArray[1]);
                $jarak = $this->haversineDistance($sekolahCoord['lat'], $sekolahCoord['lng'], $lat, $lng);
            }
        }

        if ($jarak == 9999) {
            $desa = strtolower(str_replace([' ', '_', '-'], '', trim($user->desa_kelurahan_domisili ?? $user->desa_domisili ?? '')));
            if ($desa && isset($desaCoords[$desa])) {
                $jarak = $this->haversineDistance($sekolahCoord['lat'], $sekolahCoord['lng'], $desaCoords[$desa]['lat'], $desaCoords[$desa]['lng']);
            }
        }

        if ($jarak == 9999) {
            $kecamatan = strtolower(trim($user->kecamatan_domisili ?? ''));
            if ($kecamatan && isset($kecamatanCoords[$kecamatan])) {
                $jarak = $this->haversineDistance($sekolahCoord['lat'], $sekolahCoord['lng'], $kecamatanCoords[$kecamatan]['lat'], $kecamatanCoords[$kecamatan]['lng']);
            }
        }

        // Update jarak ke database jika perlu
        if (abs(($user->jarak_ke_sekolah ?? 9999) - $jarak) > 0.1) {
            DB::table('users')->where('id', $user->id)->update(['jarak_ke_sekolah' => $jarak]);
        }

        return $jarak;
    }

    /**
     * Mengambil kuota per jalur dari jadwal aktif.
     */
    private function getKuotaPerJalur()
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalAktif) {
            return ['domisili' => 0, 'prestasi' => 0, 'afirmasi' => 0, 'mutasi' => 0];
        }

        $kuotaTotal = $jadwalAktif->kuota_total_keseluruhan;
        $persentaseKuota = config('ppdb.kuota_persentase', [
            'domisili' => 0.50,
            'prestasi' => 0.30,
            'afirmasi' => 0.15,
            'mutasi'   => 0.05,
        ]);

        $kuotaPerJalur = [
            'domisili' => floor($kuotaTotal * ($persentaseKuota['domisili'] ?? 0)),
            'prestasi' => floor($kuotaTotal * ($persentaseKuota['prestasi'] ?? 0)),
            'afirmasi' => floor($kuotaTotal * ($persentaseKuota['afirmasi'] ?? 0)),
            'mutasi'   => floor($kuotaTotal * ($persentaseKuota['mutasi'] ?? 0)),
        ];

        // Alokasikan sisa kuota ke jalur domisili
        $sisaKuota = $kuotaTotal - array_sum($kuotaPerJalur);
        if ($sisaKuota > 0) {
            $kuotaPerJalur['domisili'] += $sisaKuota;
        }

        return $kuotaPerJalur;
    }

    /**
     * Fungsi utama untuk menampilkan dashboard seleksi.
     */
    public function index()
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $kuotaPerJalur = $this->getKuotaPerJalur();

        $pendaftarQuery = User::where('role', 'siswa')
            ->whereIn('status_pendaftaran', ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi']);

        $statistikPendaftar = $pendaftarQuery
            ->select('jalur_pendaftaran', 'status_pendaftaran', DB::raw('count(*) as total'))
            ->groupBy('jalur_pendaftaran', 'status_pendaftaran')
            ->get();

        $pendaftarSiapSeleksi = $statistikPendaftar->where('status_pendaftaran', 'berkas_diverifikasi')->pluck('total', 'jalur_pendaftaran');
        $pendaftarLulus = $statistikPendaftar->where('status_pendaftaran', 'lulus_seleksi')->pluck('total', 'jalur_pendaftaran');
        $pendaftarTidakLulus = $statistikPendaftar->where('status_pendaftaran', 'tidak_lulus_seleksi')->pluck('total', 'jalur_pendaftaran');

        $totalSiapSeleksi = $pendaftarSiapSeleksi->sum();
        $totalLulus = $pendaftarLulus->sum();
        $totalTidakLulus = $pendaftarTidakLulus->sum();
        $sisaTotalKuota = $jadwalAktif ? ($jadwalAktif->kuota_total_keseluruhan - $totalLulus) : 0;

        $statistikJalur = [];
        foreach (['domisili', 'prestasi', 'afirmasi', 'mutasi'] as $jalur) {
            $statistikJalur[$jalur] = [
                'kuota'         => $kuotaPerJalur[$jalur] ?? 0,
                'siap_seleksi'  => $pendaftarSiapSeleksi[$jalur] ?? 0,
                'lulus'         => $pendaftarLulus[$jalur] ?? 0,
                'tidak_lulus'   => $pendaftarTidakLulus[$jalur] ?? 0,
            ];
        }

        return view('admin.seleksi.index', compact(
            'jadwalAktif',
            'statistikJalur',
            'totalSiapSeleksi',
            'totalLulus',
            'totalTidakLulus',
            'sisaTotalKuota'
        ));
    }

    /**
     * Menampilkan hasil seleksi untuk satu jalur.
     */
    public function hasilSeleksiJalur(Request $request, $jalur)
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalAktif) {
            return redirect()->route('admin.seleksi.index')->with('error', 'Jadwal SPMB tidak aktif.');
        }

        $validJalur = ['domisili', 'prestasi', 'afirmasi', 'mutasi'];
        if (!in_array($jalur, $validJalur)) {
            abort(404, 'Jalur pendaftaran tidak valid.');
        }

        $kuotaPerJalur = $this->getKuotaPerJalur();
        $kuotaJalurIni = $kuotaPerJalur[$jalur] ?? 0;

        $pendaftarLulusJalurIni = User::where('role', 'siswa')
            ->where('jalur_pendaftaran', $jalur)
            ->where('status_pendaftaran', 'lulus_seleksi')
            ->count();
        $sisaKuotaJalurIni = $kuotaJalurIni - $pendaftarLulusJalurIni;

        // Ambil semua calon siswa di jalur ini (lulus, tidak lulus, dan siap seleksi)
        $query = User::where('role', 'siswa')
            ->where('jalur_pendaftaran', $jalur)
            ->whereIn('status_pendaftaran', ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi'])
            ->leftJoin('biodata_siswas', 'users.id', '=', 'biodata_siswas.user_id')
            ->select('users.*', 'biodata_siswas.tgl_lahir as tanggal_lahir_siswa', 'biodata_siswas.skor_prestasi', 'biodata_siswas.ketm', 'biodata_siswas.disabilitas');

        $calonSiswaCollection = $this->rankCalonSiswa($query->get(), $jalur);

        // Buat paginator manual
        $currentPage = request()->get('page', 1);
        $perPage = 50;
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $calonSiswaCollection->slice(($currentPage - 1) * $perPage, $perPage),
            $calonSiswaCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.seleksi.hasil_jalur', [
            'jalur'                   => $jalur,
            'calonSiswa'              => $paginatedItems,
            'jadwalAktif'             => $jadwalAktif,
            'kuotaJalurIni'           => $kuotaJalurIni,
            'pendaftarLulusJalurIni'  => $pendaftarLulusJalurIni,
            'sisaKuotaJalurIni'       => $sisaKuotaJalurIni
        ]);
    }

    /**
     * Memproses seleksi otomatis untuk semua jalur.
     */
    public function prosesSeleksiOtomatis(Request $request)
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalAktif) {
            return back()->with('error', 'Tidak ada jadwal SPMB aktif untuk menjalankan seleksi otomatis.');
        }

        $kuotaPerJalur = $this->getKuotaPerJalur();

        DB::transaction(function () use ($kuotaPerJalur) {
            // Langkah 1: Reset semua status seleksi agar bisa dievaluasi ulang
            User::where('role', 'siswa')
                ->whereIn('status_pendaftaran', ['lulus_seleksi', 'tidak_lulus_seleksi'])
                ->update([
                    'status_pendaftaran' => 'berkas_diverifikasi',
                    'catatan_verifikasi' => 'Status direset untuk proses seleksi otomatis.'
                ]);

            // Langkah 2: Loop per jalur untuk meluluskan siswa sesuai kuota
            foreach (['domisili', 'prestasi', 'afirmasi', 'mutasi'] as $jalur) {
                $kuota = $kuotaPerJalur[$jalur] ?? 0;
                if ($kuota <= 0) continue;

                // Ambil semua calon yang siap seleksi
                $queryCalon = User::where('role', 'siswa')
                    ->where('jalur_pendaftaran', $jalur)
                    ->where('status_pendaftaran', 'berkas_diverifikasi')
                    ->leftJoin('biodata_siswas', 'users.id', '=', 'biodata_siswas.user_id')
                    ->select('users.*', 'biodata_siswas.tgl_lahir as tanggal_lahir_siswa', 'biodata_siswas.skor_prestasi', 'biodata_siswas.ketm', 'biodata_siswas.disabilitas');

                // Peringkatkan menggunakan fungsi terpusat dan ambil sesuai kuota
                $calonLulusIds = $this->rankCalonSiswa($queryCalon->get(), $jalur)
                    ->take($kuota)
                    ->pluck('id');

                // Update status mereka menjadi LULUS
                if ($calonLulusIds->isNotEmpty()) {
                    User::whereIn('id', $calonLulusIds)->update([
                        'status_pendaftaran' => 'lulus_seleksi',
                        'catatan_verifikasi' => 'Lulus seleksi otomatis pada jalur ' . ucfirst($jalur) . '.'
                    ]);
                }
            }

            // Langkah 3: Siswa yang tidak masuk kuota di jalur manapun, statusnya menjadi TIDAK LULUS
            User::where('role', 'siswa')
                ->where('status_pendaftaran', 'berkas_diverifikasi')
                ->update([
                    'status_pendaftaran' => 'tidak_lulus_seleksi',
                    'catatan_verifikasi' => 'Tidak memenuhi kuota pada proses seleksi otomatis.'
                ]);
        });

        return redirect()->route('admin.seleksi.index')->with('success', 'Proses seleksi otomatis telah selesai dijalankan.');
    }

    /**
     * Reset hasil seleksi (mengembalikan semua ke status berkas_diverifikasi)
     */
    public function resetHasilSeleksi(Request $request)
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalAktif) {
            return back()->with('error', 'Tidak ada jadwal SPMB aktif.');
        }

        DB::transaction(function () {
            User::where('role', 'siswa')
                ->whereIn('status_pendaftaran', ['lulus_seleksi', 'tidak_lulus_seleksi'])
                ->update([
                    'status_pendaftaran' => 'berkas_diverifikasi',
                    'catatan_verifikasi' => 'Status seleksi direset.'
                ]);
        });

        return redirect()->route('admin.seleksi.index')->with('success', 'Hasil seleksi berhasil direset.');
    }

    /**
     * ===================================================================
     * FUNGSI PERANKINGAN TERPUSAT
     * ===================================================================
     * Fungsi ini menerima koleksi (collection) pendaftar dan nama jalur,
     * kemudian mengembalikan koleksi yang sudah diurutkan.
     */
    private function rankCalonSiswa($calonSiswa, $jalur)
    {
        // Transformasi data dasar (usia, jarak, dll.)
        $calonSiswa->transform(function ($user) use ($jalur) {
            $user->usia = $user->tanggal_lahir_siswa ? Carbon::parse($user->tanggal_lahir_siswa)->age : 0;
            if ($jalur == 'domisili' || $jalur == 'afirmasi' || $jalur == 'mutasi') {
                $user->jarak_ke_sekolah = $this->calculateStudentDistance($user);
            }
            return $user;
        });

        // Tentukan kriteria sorting berdasarkan jalur
        $sortCriteria = [];
        switch ($jalur) {
            case 'domisili':
                $sortCriteria = [['jarak_ke_sekolah', 'asc'], ['usia', 'desc'], ['created_at', 'asc']];
                break;
            case 'prestasi':
                $calonSiswa->transform(function ($user) {
                    $user->skor_prestasi = $user->skor_prestasi ?? 0;
                    return $user;
                });
                $sortCriteria = [['skor_prestasi', 'desc'], ['usia', 'desc'], ['created_at', 'asc']];
                break;
            case 'afirmasi':
                $calonSiswa->transform(function ($user) {
                    $user->prioritas_ketm = $user->ketm ? 1 : 0;
                    $user->prioritas_disabilitas = $user->disabilitas ? 1 : 0;
                    return $user;
                });
                $sortCriteria = [
                    ['prioritas_ketm', 'desc'],
                    ['prioritas_disabilitas', 'desc'],
                    ['jarak_ke_sekolah', 'asc'],
                    ['usia', 'desc'],
                    ['created_at', 'asc']
                ];
                break;
            case 'mutasi':
                $calonSiswa->transform(function ($user) {
                    // Cek berdasarkan bukti upload atau pilihan sub-jalur jika ada
                    $user->prioritas_pindah_tugas = str_contains(strtolower($user->jenis_mutasi ?? ''), 'pindah tugas') ? 1 : 0;
                    $user->prioritas_anak_guru = str_contains(strtolower($user->jenis_mutasi ?? ''), 'anak guru') ? 1 : 0;
                    return $user;
                });
                $sortCriteria = [
                    ['prioritas_pindah_tugas', 'desc'],
                    ['prioritas_anak_guru', 'desc'],
                    ['jarak_ke_sekolah', 'asc'],
                    ['usia', 'desc'],
                    ['created_at', 'asc']
                ];
                break;
        }

        // Lakukan sorting dan kembalikan collection yang sudah terurut
        return $calonSiswa->sortBy($sortCriteria)->values();
    }
}
