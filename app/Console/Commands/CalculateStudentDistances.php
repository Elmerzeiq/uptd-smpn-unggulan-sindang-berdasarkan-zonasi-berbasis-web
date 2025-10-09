<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalculateStudentDistances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:calculate-distances
                            {--force : Force recalculate all distances}
                            {--jalur= : Only calculate for specific jalur (domisili, prestasi, afirmasi, mutasi)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate distance from school for all students based on their coordinates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting distance calculation for all students...');

        $force = $this->option('force');
        $jalur = $this->option('jalur');

        // Validasi jalur jika disediakan
        if ($jalur && !in_array($jalur, ['domisili', 'prestasi', 'afirmasi', 'mutasi'])) {
            $this->error('Invalid jalur. Must be one of: domisili, prestasi, afirmasi, mutasi');
            return self::FAILURE;
        }

        // Query siswa
        $query = User::where('role', 'siswa');

        if ($jalur) {
            $query->where('jalur_pendaftaran', $jalur);
            $this->info("📌 Filtering for jalur: {$jalur}");
        }

        if (!$force) {
            $query->where(function ($q) {
                $q->whereNull('jarak_ke_sekolah')
                    ->orWhere('jarak_ke_sekolah', 9999)
                    ->orWhere('jarak_ke_sekolah', 0);
            });
            $this->info("🔄 Only calculating for students without distance data");
        } else {
            $this->info("⚡ Force mode: Recalculating ALL distances");
        }

        $students = $query->get();
        $totalStudents = $students->count();

        if ($totalStudents === 0) {
            $this->info('✅ No students found to process.');
            return self::SUCCESS;
        }

        $this->info("📊 Found {$totalStudents} students to process");

        // Konfigurasi koordinat
        $sekolahCoord = config('ppdb.koordinat_sekolah');
        $kecamatanCoords = config('ppdb.kecamatan_koordinat', []);
        $desaCoords = config('ppdb.desa_koordinat', []);

        if (!$sekolahCoord || !isset($sekolahCoord['lat']) || !isset($sekolahCoord['lng'])) {
            $this->error('❌ School coordinates not configured in config/ppdb.php');
            return self::FAILURE;
        }

        $this->info("🏫 School coordinates: {$sekolahCoord['lat']}, {$sekolahCoord['lng']}");

        // Progress bar
        $progressBar = $this->output->createProgressBar($totalStudents);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% - %message%');
        $progressBar->setMessage('Starting...');

        $successCount = 0;
        $failCount = 0;
        $processedData = [];

        foreach ($students as $student) {
            $progressBar->setMessage("Processing: {$student->nama_lengkap}");

            try {
                $distance = $this->calculateStudentDistance($student, $sekolahCoord, $kecamatanCoords, $desaCoords);

                // Update ke database
                $student->update(['jarak_ke_sekolah' => $distance]);

                $successCount++;
                $processedData[] = [
                    'nama' => $student->nama_lengkap,
                    'jalur' => $student->jalur_pendaftaran,
                    'jarak' => $distance,
                    'koordinat_sumber' => $this->getCoordinateSource($student, $desaCoords, $kecamatanCoords)
                ];
            } catch (\Exception $e) {
                $failCount++;
                Log::error("Error calculating distance for student {$student->id}: " . $e->getMessage());
                $progressBar->setMessage("Error: {$student->nama_lengkap}");
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Report hasil
        $this->info('📋 CALCULATION RESULTS:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $totalStudents],
                ['Successfully Calculated', $successCount],
                ['Failed', $failCount],
                ['Success Rate', round(($successCount / $totalStudents) * 100, 2) . '%']
            ]
        );

        // Detail per jalur
        $jalurStats = collect($processedData)->groupBy('jalur')->map(function ($group, $jalur) {
            return [
                'jalur' => $jalur,
                'count' => $group->count(),
                'avg_distance' => round($group->avg('jarak'), 2),
                'min_distance' => round($group->min('jarak'), 2),
                'max_distance' => round($group->max('jarak'), 2)
            ];
        })->values()->toArray();

        if (!empty($jalurStats)) {
            $this->newLine();
            $this->info('📊 STATISTICS BY JALUR:');
            $this->table(
                ['Jalur', 'Count', 'Avg Distance (km)', 'Min Distance (km)', 'Max Distance (km)'],
                $jalurStats
            );
        }

        // Koordinat source statistics
        $sourceStats = collect($processedData)->groupBy('koordinat_sumber')->map(function ($group, $source) use ($processedData) {
            return [
                'source' => $source,
                'count' => $group->count(),
                'percentage' => round(($group->count() / count($processedData)) * 100, 2) . '%'
            ];
        })->values()->toArray();

        if (!empty($sourceStats)) {
            $this->newLine();
            $this->info('📍 COORDINATE SOURCES:');
            $this->table(
                ['Source', 'Count', 'Percentage'],
                $sourceStats
            );
        }

        if ($failCount > 0) {
            $this->warn("⚠️  {$failCount} students failed to process. Check logs for details.");
        }

        $this->info('✅ Distance calculation completed!');

        return self::SUCCESS;
    }

    /**
     * Calculate distance for a student
     */
    private function calculateStudentDistance($student, $sekolahCoord, $kecamatanCoords, $desaCoords)
    {
        $jarak = 9999; // Default jarak besar jika koordinat tidak ada

        // Prioritas 1: Koordinat langsung dari siswa
        if (!empty($student->koordinat_domisili_siswa)) {
            $koordinatArray = explode(',', $student->koordinat_domisili_siswa);
            if (count($koordinatArray) === 2 && is_numeric(trim($koordinatArray[0])) && is_numeric(trim($koordinatArray[1]))) {
                $lat = (float) trim($koordinatArray[0]);
                $lng = (float) trim($koordinatArray[1]);
                $jarak = $this->haversineDistance(
                    $sekolahCoord['lat'],
                    $sekolahCoord['lng'],
                    $lat,
                    $lng
                );
            }
        }

        // Prioritas 2: Koordinat desa/kelurahan
        if ($jarak == 9999) {
            $desa = strtolower(str_replace([' ', '_', '-'], '', trim($student->desa_kelurahan_domisili ?? $student->desa_domisili ?? '')));
            if ($desa && isset($desaCoords[$desa])) {
                $jarak = $this->haversineDistance(
                    $sekolahCoord['lat'],
                    $sekolahCoord['lng'],
                    $desaCoords[$desa]['lat'],
                    $desaCoords[$desa]['lng']
                );
            }
        }

        // Prioritas 3: Koordinat kecamatan
        if ($jarak == 9999) {
            $kecamatan = strtolower(trim($student->kecamatan_domisili ?? ''));
            if ($kecamatan && isset($kecamatanCoords[$kecamatan])) {
                $jarak = $this->haversineDistance(
                    $sekolahCoord['lat'],
                    $sekolahCoord['lng'],
                    $kecamatanCoords[$kecamatan]['lat'],
                    $kecamatanCoords[$kecamatan]['lng']
                );
            }
        }

        return round($jarak, 2);
    }

    /**
     * Get coordinate source for student
     */
    private function getCoordinateSource($student, $desaCoords, $kecamatanCoords)
    {
        // Check koordinat langsung
        if (!empty($student->koordinat_domisili_siswa)) {
            $koordinatArray = explode(',', $student->koordinat_domisili_siswa);
            if (count($koordinatArray) === 2 && is_numeric(trim($koordinatArray[0])) && is_numeric(trim($koordinatArray[1]))) {
                return 'Direct Coordinates';
            }
        }

        // Check desa
        $desa = strtolower(str_replace([' ', '_', '-'], '', trim($student->desa_kelurahan_domisili ?? $student->desa_domisili ?? '')));
        if ($desa && isset($desaCoords[$desa])) {
            return 'Desa/Kelurahan';
        }

        // Check kecamatan
        $kecamatan = strtolower(trim($student->kecamatan_domisili ?? ''));
        if ($kecamatan && isset($kecamatanCoords[$kecamatan])) {
            return 'Kecamatan';
        }

        return 'No Coordinates';
    }

    /**
     * Haversine distance calculation
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
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
}
