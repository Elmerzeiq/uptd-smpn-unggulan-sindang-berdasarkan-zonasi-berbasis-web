<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\JadwalPpdb;
use Illuminate\Support\Str;
use App\Models\BiodataSiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        $pendaftaranAkunDibuka = $jadwalAktif ? $jadwalAktif->isPendaftaranOpen() : false;
        $kecamatanZonasiConfig = config('ppdb.kecamatan_zonasi', []);
        $desaKelurahanZonasiConfig = config('ppdb.desa_kelurahan_zonasi', []);

        Log::debug('RegisteredUserController::create - Variabel untuk view', [
            'kecamatan_zonasi_config' => $kecamatanZonasiConfig,
            'desa_kelurahan_zonasi_config' => $desaKelurahanZonasiConfig,
            'pendaftaran_akun_dibuka' => $pendaftaranAkunDibuka,
            'current_time' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        // Fallback jika konfigurasi kosong, untuk mencegah error di view
        if (empty($kecamatanZonasiConfig)) {
            Log::warning('RegisteredUserController::create - Konfigurasi kecamatan_zonasi kosong, menggunakan fallback.');
            $kecamatanZonasiConfig = ['Sindang', 'Indramayu', 'Lohbener', 'Arahan'];
        }
        if (empty($desaKelurahanZonasiConfig)) {
            Log::warning('RegisteredUserController::create - Konfigurasi desa_kelurahan_zonasi kosong, menggunakan fallback.');
            $desaKelurahanZonasiConfig = ['Terusan', 'Kenanga', 'Sindang', 'Rambatan Wetan', 'Wanantara'];
        }

        $jalurPendaftaranOptions = [
            'domisili' => 'Domisili',
            // 'prestasi_akademik_lomba' => 'Prestasi Akademik (Lomba)',
            // 'prestasi_non_akademik_lomba' => 'Prestasi Non-Akademik (Lomba)',
            // 'prestasi_rapor' => 'Prestasi Nilai Rapor',
            // 'afirmasi_ketm' => 'Afirmasi KETM',
            // 'afirmasi_disabilitas' => 'Afirmasi Disabilitas',
            // 'mutasi_pindah_tugas' => 'Mutasi Pindah Tugas Orang Tua/Wali',
            // 'mutasi_anak_guru' => 'Mutasi Anak Guru',
        ];

        return view('auth.register', compact('pendaftaranAkunDibuka', 'kecamatanZonasiConfig', 'desaKelurahanZonasiConfig', 'jalurPendaftaranOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $jadwalAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalAktif || !$jadwalAktif->isPendaftaranOpen()) {
            return back()->withInput()->with('error', 'Pendaftaran akun SPMB saat ini tidak dibuka atau sudah ditutup.');
        }

        $kecamatanZonasiConfig = config('ppdb.kecamatan_zonasi', []);
        $desaKelurahanZonasiConfig = config('ppdb.desa_kelurahan_zonasi', []);
        $daftarKecamatanZonasiLower = array_map('strtolower', $kecamatanZonasiConfig);
        $daftarDesaKelurahanZonasiLower = array_map('strtolower', $desaKelurahanZonasiConfig);
        $jalurPendaftaranOptions = [
            'domisili' => 'Domisili',
            'prestasi_akademik_lomba' => 'Prestasi Akademik (Lomba)',
            'prestasi_non_akademik_lomba' => 'Prestasi Non-Akademik (Lomba)',
            'prestasi_rapor' => 'Prestasi Nilai Rapor',
            'afirmasi_ketm' => 'Afirmasi KETM',
            'afirmasi_disabilitas' => 'Afirmasi Disabilitas',
            'mutasi_pindah_tugas' => 'Mutasi Pindah Tugas Orang Tua/Wali',
            'mutasi_anak_guru' => 'Mutasi Anak Guru',
        ];
        $jalurPendaftaranKeys = array_keys($jalurPendaftaranOptions);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'digits:10', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jalur_pendaftaran' => ['required', Rule::in($jalurPendaftaranKeys)],
            'kecamatan_domisili_input' => [
                Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'domisili'),
                'nullable',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($daftarKecamatanZonasiLower, $request, $kecamatanZonasiConfig) {
                    if ($request->jalur_pendaftaran === 'domisili' && !empty($value) && !in_array(strtolower(trim($value)), $daftarKecamatanZonasiLower)) {
                        $fail('Kecamatan domisili harus termasuk dalam daftar zonasi: ' . implode(', ', $kecamatanZonasiConfig));
                    }
                },
            ],
            'desa_kelurahan_domisili_input' => [
                Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'domisili'),
                'nullable',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($daftarDesaKelurahanZonasiLower, $request, $desaKelurahanZonasiConfig) {
                    if ($request->jalur_pendaftaran === 'domisili' && !empty($value) && !in_array(strtolower(trim($value)), $daftarDesaKelurahanZonasiLower)) {
                        $fail('Desa/Kelurahan domisili harus termasuk dalam daftar zonasi: ' . implode(', ', $desaKelurahanZonasiConfig));
                    }
                },
            ],
            'koordinat_lat' => ['nullable', 'numeric', 'between:-90,90', Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'domisili')],
            'koordinat_lng' => ['nullable', 'numeric', 'between:-180,180', Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'domisili')],
        ]);

        $jalurPilihanAwal = $request->jalur_pendaftaran;
        $kecamatanInputRaw = $request->kecamatan_domisili_input;
        $desaKelurahanInputRaw = $request->desa_kelurahan_domisili_input;

        $jalurPendaftaranFinal = $jalurPilihanAwal;
        $kecamatanDomisiliFinal = null;
        $desaKelurahanDomisiliFinal = null;
        $koordinatDomisiliSiswaFinal = null;
        $distance = null;

        if ($jalurPilihanAwal === 'domisili') {
            if (empty($kecamatanInputRaw) || empty($desaKelurahanInputRaw) || empty($request->koordinat_lat) || empty($request->koordinat_lng)) {
                return back()->withInput()->withErrors([
                    'kecamatan_domisili_input' => 'Kecamatan, desa/kelurahan, dan titik koordinat wajib diisi untuk jalur domisili.',
                ]);
            }

            $kecamatanInputNormalized = strtolower(trim($kecamatanInputRaw));
            $desaKelurahanInputNormalized = strtolower(trim($desaKelurahanInputRaw));

            if (!in_array($kecamatanInputNormalized, $daftarKecamatanZonasiLower)) {
                return back()->withInput()->withErrors([
                    'kecamatan_domisili_input' => 'Kecamatan tidak termasuk dalam daftar zonasi yang diizinkan: ' . implode(', ', $kecamatanZonasiConfig),
                ]);
            }

            if (!in_array($desaKelurahanInputNormalized, $daftarDesaKelurahanZonasiLower)) {
                return back()->withInput()->withErrors([
                    'desa_kelurahan_domisili_input' => 'Desa/Kelurahan tidak termasuk dalam daftar zonasi yang diizinkan: ' . implode(', ', $desaKelurahanZonasiConfig),
                ]);
            }

            $schoolLat = config('ppdb.koordinat_sekolah.lat', -6.3390);
            $schoolLon = config('ppdb.koordinat_sekolah.lng', 108.3225);
            $studentLat = (float) $request->koordinat_lat;
            $studentLon = (float) $request->koordinat_lng;

            // Calculate distance using Haversine formula
            $earthRadius = 6371; // Kilometers
            $lat1Rad = deg2rad($studentLat);
            $lon1Rad = deg2rad($studentLon);
            $lat2Rad = deg2rad($schoolLat);
            $lon2Rad = deg2rad($schoolLon);
            $deltaLat = $lat2Rad - $lat1Rad;
            $deltaLon = $lon2Rad - $lon1Rad;
            $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
                cos($lat1Rad) * cos($lat2Rad) *
                sin($deltaLon / 2) * sin($deltaLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            $maxDistance = config('ppdb.maksimum_jarak_zonasi_km', 7); // Menggunakan konfigurasi, default 7 km

            if ($distance > $maxDistance) {
                return back()->withInput()->withErrors([
                    'koordinat_lat' => 'Jarak ke sekolah melebihi batas maksimum (' . $maxDistance . ' km). Jarak Anda: ' . round($distance, 2) . ' km.',
                ]);
            }

            $kecamatanDomisiliFinal = $kecamatanInputRaw;
            $desaKelurahanDomisiliFinal = $desaKelurahanInputRaw;
            $koordinatDomisiliSiswaFinal = $request->koordinat_lat . ',' . $request->koordinat_lng;
        }

        $prefixJalurSingkat = strtoupper(substr(explode('_', $jalurPendaftaranFinal)[0], 0, 1));
        if ($jalurPendaftaranFinal === 'domisili') $prefixJalurSingkat = 'Z';
        $tahunBulan = date('ym');
        $latestPendaftar = User::where('no_pendaftaran', 'LIKE', $prefixJalurSingkat . $tahunBulan . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->first();
        $nextUrutan = $latestPendaftar ? ((int) substr($latestPendaftar->no_pendaftaran, -4)) + 1 : 1;
        $noPendaftaran = $prefixJalurSingkat . $tahunBulan . str_pad($nextUrutan, 4, '0', STR_PAD_LEFT);

        // Data user yang akan disimpan - DIPERBAIKI
        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'nisn' => $request->nisn,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'jalur_pendaftaran' => $jalurPendaftaranFinal,
            'kecamatan_domisili' => $kecamatanDomisiliFinal,
            'desa_kelurahan_domisili' => $desaKelurahanDomisiliFinal, // Kolom utama
            'desa_domisili' => $desaKelurahanDomisiliFinal,           // Alias untuk kompatibilitas
            'no_pendaftaran' => $noPendaftaran,
            'status_pendaftaran' => 'akun_terdaftar',
        ];

        // Tambahkan data koordinat dan jarak jika jalur domisili
        if ($jalurPendaftaranFinal === 'domisili' && $koordinatDomisiliSiswaFinal && $distance !== null) {
            $userData['koordinat_domisili_siswa'] = $koordinatDomisiliSiswaFinal;
            $userData['jarak_ke_sekolah'] = round($distance, 2); // Simpan dengan 2 desimal
        }

        // Log data sebelum disimpan untuk debugging
        Log::debug('RegisteredUserController::store - Data yang akan disimpan:', [
            'userData' => $userData,
            'distance_calculated' => $distance,
            'koordinat_final' => $koordinatDomisiliSiswaFinal,
        ]);

        try {
            // Buat user baru
            $user = User::create($userData);

            // Langsung buat entri BiodataSiswa dan OrangTua kosong agar relasi ada
            if (!$user->biodata) {
                $user->biodata()->create([]);
            }
            if (!$user->orangTua) {
                $user->orangTua()->create([]);
            }

            // Log hasil penyimpanan
            Log::info('RegisteredUserController::store - User berhasil dibuat:', [
                'user_id' => $user->id,
                'nama' => $user->nama_lengkap,
                'no_pendaftaran' => $user->no_pendaftaran,
                'jalur' => $user->jalur_pendaftaran,
                'koordinat_tersimpan' => $user->koordinat_domisili_siswa,
                'jarak_tersimpan' => $user->jarak_ke_sekolah,
            ]);

            // Fire registered event tapi JANGAN login otomatis
            event(new Registered($user));

            // Redirect ke halaman login dengan pesan sukses
            return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login untuk melanjutkan pendaftaran.');
        } catch (\Exception $e) {
            Log::error('RegisteredUserController::store - Error saat menyimpan user:', [
                'error' => $e->getMessage(),
                'userData' => $userData,
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}
