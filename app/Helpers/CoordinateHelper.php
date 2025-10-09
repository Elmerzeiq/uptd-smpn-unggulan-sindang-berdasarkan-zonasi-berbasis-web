<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class CoordinateHelper
{
    /**
     * Hitung jarak antara dua titik menggunakan Haversine formula
     *
     * @param float $lat1 Latitude titik pertama
     * @param float $lng1 Longitude titik pertama
     * @param float $lat2 Latitude titik kedua
     * @param float $lng2 Longitude titik kedua
     * @return float Jarak dalam kilometer
     */
    public static function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        // Konversi derajat ke radian
        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        // Hitung selisih
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        // Rumus Haversine
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLng / 2) * sin($deltaLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Parse koordinat dari string format "lat,lng"
     *
     * @param string|null $coordinateString
     * @return array|null ['lat' => float, 'lng' => float] atau null jika invalid
     */
    public static function parseCoordinateString(?string $coordinateString): ?array
    {
        if (empty($coordinateString)) {
            return null;
        }

        $parts = explode(',', $coordinateString);

        if (count($parts) !== 2) {
            return null;
        }

        $lat = trim($parts[0]);
        $lng = trim($parts[1]);

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return null;
        }

        $latitude = (float) $lat;
        $longitude = (float) $lng;

        // Validasi range koordinat bumi
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return null;
        }

        return [
            'lat' => $latitude,
            'lng' => $longitude
        ];
    }

    /**
     * Format koordinat menjadi string "lat,lng"
     *
     * @param float $lat
     * @param float $lng
     * @param int $precision Jumlah desimal
     * @return string
     */
    public static function formatCoordinateString(float $lat, float $lng, int $precision = 6): string
    {
        return round($lat, $precision) . ',' . round($lng, $precision);
    }

    /**
     * Validasi apakah koordinat berada dalam Indonesia
     *
     * @param float $lat
     * @param float $lng
     * @return bool
     */
    public static function isInIndonesia(float $lat, float $lng): bool
    {
        // Batas koordinat Indonesia (perkiraan)
        return $lat >= -11 && $lat <= 6 && $lng >= 95 && $lng <= 141;
    }

    /**
     * Dapatkan koordinat dari konfigurasi berdasarkan kecamatan
     *
     * @param string|null $kecamatan
     * @return array|null
     */
    public static function getKecamatanCoordinates(?string $kecamatan): ?array
    {
        if (empty($kecamatan)) {
            return null;
        }

        $kecamatanKey = strtolower(trim($kecamatan));
        $kecamatanKoordinat = config('ppdb.kecamatan_koordinat', []);

        return $kecamatanKoordinat[$kecamatanKey] ?? null;
    }

    /**
     * Dapatkan koordinat dari konfigurasi berdasarkan desa
     *
     * @param string|null $desa
     * @return array|null
     */
    public static function getDesaCoordinates(?string $desa): ?array
    {
        if (empty($desa)) {
            return null;
        }

        // Bersihkan nama desa dari spasi dan karakter khusus
        $desaKey = strtolower(str_replace([' ', '_', '-'], '', trim($desa)));
        $desaKoordinat = config('ppdb.desa_koordinat', []);

        return $desaKoordinat[$desaKey] ?? null;
    }

    /**
     * Dapatkan koordinat fallback untuk user berdasarkan alamat
     *
     * @param string|null $kecamatan
     * @param string|null $desa
     * @return array|null ['lat' => float, 'lng' => float, 'source' => string]
     */
    public static function getFallbackCoordinates(?string $kecamatan, ?string $desa): ?array
    {
        // Priority 1: Koordinat desa
        if ($desa) {
            $desaCoords = self::getDesaCoordinates($desa);
            if ($desaCoords) {
                return [
                    'lat' => $desaCoords['lat'],
                    'lng' => $desaCoords['lng'],
                    'source' => "Desa: {$desa}"
                ];
            }
        }

        // Priority 2: Koordinat kecamatan
        if ($kecamatan) {
            $kecamatanCoords = self::getKecamatanCoordinates($kecamatan);
            if ($kecamatanCoords) {
                return [
                    'lat' => $kecamatanCoords['lat'],
                    'lng' => $kecamatanCoords['lng'],
                    'source' => "Kecamatan: {$kecamatan}"
                ];
            }
        }

        // Priority 3: Default fallback
        $defaultCoords = config('ppdb.default_fallback_koordinat');
        if ($defaultCoords && isset($defaultCoords['lat']) && isset($defaultCoords['lng'])) {
            return [
                'lat' => $defaultCoords['lat'],
                'lng' => $defaultCoords['lng'],
                'source' => 'Default fallback'
            ];
        }

        return null;
    }

    /**
     * Validasi jarak maksimum untuk zonasi
     *
     * @param float $distance Jarak dalam km
     * @return bool
     */
    public static function isWithinZonationDistance(float $distance): bool
    {
        $maxDistance = config('ppdb.maksimum_jarak_zonasi_km', 7.0);
        return $distance <= $maxDistance;
    }

    /**
     * Generate Google Maps URL dari koordinat
     *
     * @param float $lat
     * @param float $lng
     * @param int $zoom
     * @return string
     */
    public static function generateGoogleMapsUrl(float $lat, float $lng, int $zoom = 15): string
    {
        return "https://www.google.com/maps?q={$lat},{$lng}&z={$zoom}";
    }

    /**
     * Generate coordinate data untuk peta dashboard
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function generateMapData($user): array
    {
        $koordinatSekolah = config('ppdb.koordinat_sekolah', ['lat' => -6.3390, 'lng' => 108.3225]);

        // Parse koordinat siswa dari database
        $koordinatSiswa = null;
        if (!empty($user->koordinat_domisili_siswa)) {
            $koordinatSiswa = self::parseCoordinateString($user->koordinat_domisili_siswa);
        }

        // Fallback koordinat jika tidak ada
        if (!$koordinatSiswa) {
            $fallback = self::getFallbackCoordinates(
                $user->kecamatan_domisili,
                $user->desa_kelurahan_domisili ?? $user->desa_domisili
            );

            if ($fallback) {
                $koordinatSiswa = [
                    'lat' => $fallback['lat'],
                    'lng' => $fallback['lng']
                ];

                Log::debug('CoordinateHelper::generateMapData - Using fallback coordinates', [
                    'user_id' => $user->id,
                    'fallback_source' => $fallback['source'],
                    'coordinates' => $koordinatSiswa
                ]);
            }
        }

        // Tentukan apakah peta bisa ditampilkan
        $canShowMap = $user->jalur_pendaftaran === 'domisili' && (
            $koordinatSiswa ||
            (!empty($koordinatSekolah['lat']) && !empty($koordinatSekolah['lng']))
        );

        return [
            'koordinat_sekolah' => $koordinatSekolah,
            'koordinat_siswa' => $koordinatSiswa,
            'can_show_map' => $canShowMap,
            'jalur_pendaftaran' => $user->jalur_pendaftaran,
            'jarak_ke_sekolah' => $user->jarak_ke_sekolah,
            'kecamatan_domisili' => $user->kecamatan_domisili,
            'desa_domisili' => $user->desa_kelurahan_domisili ?? $user->desa_domisili,
        ];
    }

    /**
     * Validasi dan bersihkan data koordinat input
     *
     * @param mixed $lat
     * @param mixed $lng
     * @return array|null ['lat' => float, 'lng' => float] atau null jika invalid
     */
    public static function validateAndCleanCoordinates($lat, $lng): ?array
    {
        // Konversi ke float
        $latitude = is_numeric($lat) ? (float) $lat : null;
        $longitude = is_numeric($lng) ? (float) $lng : null;

        if ($latitude === null || $longitude === null) {
            return null;
        }

        // Validasi range
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return null;
        }

        // Validasi dalam wilayah Indonesia (opsional)
        if (!self::isInIndonesia($latitude, $longitude)) {
            Log::warning('CoordinateHelper::validateAndCleanCoordinates - Coordinates outside Indonesia', [
                'lat' => $latitude,
                'lng' => $longitude
            ]);
        }

        return [
            'lat' => round($latitude, 6),
            'lng' => round($longitude, 6)
        ];
    }

    /**
     * Hitung jarak dari user ke sekolah
     *
     * @param \App\Models\User $user
     * @return float|null Jarak dalam km atau null jika tidak bisa dihitung
     */
    public static function calculateDistanceToSchool($user): ?float
    {
        $schoolCoords = config('ppdb.koordinat_sekolah');
        if (!$schoolCoords || !isset($schoolCoords['lat']) || !isset($schoolCoords['lng'])) {
            return null;
        }

        $userCoords = self::parseCoordinateString($user->koordinat_domisili_siswa);
        if (!$userCoords) {
            return null;
        }

        return self::calculateDistance(
            $userCoords['lat'],
            $userCoords['lng'],
            $schoolCoords['lat'],
            $schoolCoords['lng']
        );
    }
}
