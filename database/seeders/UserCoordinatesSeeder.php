<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserCoordinatesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🗺️ Starting to seed coordinates for existing users...');

        // Ambil semua user jalur domisili yang belum punya koordinat
        $usersWithoutCoordinates = User::where('jalur_pendaftaran', 'domisili')
            ->where(function ($query) {
                $query->whereNull('koordinat_domisili_siswa')
                    ->orWhere('koordinat_domisili_siswa', '')
                    ->orWhere('koordinat_domisili_siswa', '0,0');
            })
            ->get();

        $this->command->info("Found {$usersWithoutCoordinates->count()} users without coordinates");

        if ($usersWithoutCoordinates->isEmpty()) {
            $this->command->info('✅ No users need coordinate updates');
            return;
        }

        // Koordinat sekolah untuk perhitungan jarak
        $schoolLat = config('ppdb.koordinat_sekolah.lat', -6.3390);
        $schoolLng = config('ppdb.koordinat_sekolah.lng', 108.3225);

        // Koordinat kecamatan dan desa dari config
        $kecamatanKoordinat = config('ppdb.kecamatan_koordinat', []);
        $desaKoordinat = config('ppdb.desa_koordinat', []);

        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($usersWithoutCoordinates as $user) {
            $koordinat = $this->getKoordinatForUser($user, $kecamatanKoordinat, $desaKoordinat);

            if ($koordinat) {
                // Hitung jarak ke sekolah
                $jarak = $this->calculateDistance(
                    $koordinat['lat'],
                    $koordinat['lng'],
                    $schoolLat,
                    $schoolLng
                );

                // Update user
                $user->update([
                    'koordinat_domisili_siswa' => $koordinat['lat'] . ',' . $koordinat['lng'],
                    'jarak_ke_sekolah' => round($jarak, 2),
                    'desa_domisili' => $user->desa_kelurahan_domisili ?? $user->desa_domisili, // Sync desa_domisili
                ]);

                $this->command->info("✅ Updated user #{$user->id} ({$user->nama_lengkap}) - {$koordinat['source']}");
                $updatedCount++;
            } else {
                $this->command->warn("⚠️ Skipped user #{$user->id} ({$user->nama_lengkap}) - No coordinates found");
                $skippedCount++;
            }
        }

        $this->command->info("🎉 Seeding completed!");
        $this->command->info("✅ Updated: {$updatedCount} users");
        $this->command->info("⚠️ Skipped: {$skippedCount} users");

        // Log hasil untuk debugging
        Log::info('UserCoordinatesSeeder completed', [
            'total_users_processed' => $usersWithoutCoordinates->count(),
            'updated_count' => $updatedCount,
            'skipped_count' => $skippedCount,
        ]);
    }

    /**
     * Dapatkan koordinat untuk user berdasarkan kecamatan/desa
     */
    private function getKoordinatForUser(User $user, array $kecamatanKoordinat, array $desaKoordinat): ?array
    {
        // Priority 1: Coba dari desa/kelurahan
        if ($user->desa_kelurahan_domisili || $user->desa_domisili) {
            $desa = $user->desa_kelurahan_domisili ?? $user->desa_domisili;
            $desaKey = strtolower(str_replace([' ', '_', '-'], '', trim($desa)));

            if (isset($desaKoordinat[$desaKey])) {
                return [
                    'lat' => $desaKoordinat[$desaKey]['lat'],
                    'lng' => $desaKoordinat[$desaKey]['lng'],
                    'source' => "Desa: {$desa}"
                ];
            }
        }

        // Priority 2: Coba dari kecamatan
        if ($user->kecamatan_domisili) {
            $kecamatanKey = strtolower(trim($user->kecamatan_domisili));

            if (isset($kecamatanKoordinat[$kecamatanKey])) {
                return [
                    'lat' => $kecamatanKoordinat[$kecamatanKey]['lat'],
                    'lng' => $kecamatanKoordinat[$kecamatanKey]['lng'],
                    'source' => "Kecamatan: {$user->kecamatan_domisili}"
                ];
            }
        }

        // Priority 3: Default fallback berdasarkan kecamatan (hardcoded)
        $defaultKoordinat = $this->getDefaultKoordinatByKecamatan($user->kecamatan_domisili);
        if ($defaultKoordinat) {
            return [
                'lat' => $defaultKoordinat['lat'],
                'lng' => $defaultKoordinat['lng'],
                'source' => "Default kecamatan: {$user->kecamatan_domisili}"
            ];
        }

        return null;
    }

    /**
     * Default koordinat berdasarkan kecamatan (fallback)
     */
    private function getDefaultKoordinatByKecamatan(?string $kecamatan): ?array
    {
        if (!$kecamatan) return null;

        $defaults = [
            'sindang' => ['lat' => -6.3400, 'lng' => 108.3200],
            'indramayu' => ['lat' => -6.3276, 'lng' => 108.3249],
            'lohbener' => ['lat' => -6.3905, 'lng' => 108.2616],
            'arahan' => ['lat' => -6.3880, 'lng' => 108.1845],
        ];

        $key = strtolower(trim($kecamatan));
        return $defaults[$key] ?? null;
    }

    /**
     * Hitung jarak menggunakan Haversine formula
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLng / 2) * sin($deltaLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
