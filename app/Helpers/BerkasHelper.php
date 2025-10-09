<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BerkasHelper
{
    public static function getBerkasListForJalur(string $jalurPendaftaranSiswa = null, string $agama = 'Islam'): array
    {
        // Jika agama null, berikan nilai default di dalam fungsi
        $agama = $agama ?? 'Islam';
        if (!$jalurPendaftaranSiswa) {
            return [];
        }

        $berkas = [];
        $common = [
            'file_ijazah_skl' => ['label' => 'Ijazah SD/MI / SKL', 'required' => true, 'multiple' => false, 'keterangan' => 'File ijazah atau surat keterangan lulus dalam format PDF/JPG.'],
            'file_nisn_screenshot' => ['label' => 'Screenshot NISN', 'required' => true, 'multiple' => false, 'keterangan' => 'Screenshot NISN dari laman resmi.'],
            'file_kk' => ['label' => 'Kartu Keluarga', 'required' => true, 'multiple' => false, 'keterangan' => 'Scan kartu keluarga terbaru.'],
        ];

        $religious = [];
        if (strtolower($agama) === 'islam') {
            $religious = [
                'file_ijazah_mda_pernyataan' => ['label' => 'Ijazah MDA / Surat Pernyataan', 'required' => true, 'multiple' => false, 'keterangan' => 'Ijazah MDA atau surat pernyataan dalam format PDF/JPG.'],
                'file_suket_baca_quran_mda' => ['label' => "Surat Keterangan Mampu Baca Al-Qur'an", 'required' => true, 'multiple' => false, 'keterangan' => 'Surat keterangan dari lembaga terkait.'],
            ];
        } else {
            $religious = [
                'file_ijazah_mda_pernyataan' => ['label' => 'Ijazah MDA / Surat Pernyataan', 'required' => false, 'multiple' => false, 'keterangan' => 'Opsional untuk siswa non-Muslim.'],
                'file_suket_baca_quran_mda' => ['label' => "Surat Keterangan Mampu Baca Al-Qur'an", 'required' => false, 'multiple' => false, 'keterangan' => 'Opsional untuk siswa non-Muslim.'],
            ];
        }

        $berkas = array_merge($common, $religious);

        switch ($jalurPendaftaranSiswa) {
            case 'domisili':
                $berkas['file_suket_domisili'] = ['label' => 'Surat Keterangan Domisili', 'required' => false, 'multiple' => false, 'keterangan' => 'Surat keterangan domisili dari kelurahan.'];
                break;
            case 'prestasi':
                $berkas['file_sertifikat_prestasi_lomba'] = ['label' => 'Sertifikat Prestasi', 'required' => true, 'multiple' => true, 'keterangan' => 'Sertifikat lomba dalam format PDF/JPG.'];
                break;
            case 'afirmasi':
                $berkas['file_surat_keterangan_afirmasi'] = ['label' => 'Surat Keterangan Afirmasi', 'required' => true, 'multiple' => false, 'keterangan' => 'Surat keterangan dari lembaga terkait.'];
                break;
            case 'mutasi':
                $berkas['file_surat_mutasi'] = ['label' => 'Surat Mutasi', 'required' => true, 'multiple' => false, 'keterangan' => 'Surat mutasi dari sekolah asal.'];
                break;
            default:
                // Jalur umum tidak memerlukan berkas tambahan
                break;
        }

        // Ensure all berkas have 'required' key
        foreach ($berkas as $field => &$details) {
            if (!isset($details['required'])) {
                $details['required'] = false;
            }
        }

        return $berkas;
    }

    public static function getStatistikBerkas($user, array $definisiBerkas): array
    {
        $totalBerkas = count($definisiBerkas);
        $berkasWajib = 0;
        $berkasOpsional = 0;
        $berkasWajibTerupload = 0;
        $berkasOpsionalTerupload = 0;

        $agama = $user->biodata && $user->biodata->agama ? strtolower((string) $user->biodata->agama) : 'islam';

        foreach ($definisiBerkas as $field => $details) {
            $isRequired = $details['required'];
            if ($field === 'file_ijazah_mda_pernyataan' || $field === 'file_suket_baca_quran_mda') {
                $isRequired = $agama === 'islam';
            }

            if ($isRequired) {
                $berkasWajib++;
                if ($user->berkas && !empty($user->berkas->$field)) {
                    $berkasWajibTerupload++;
                }
            } else {
                $berkasOpsional++;
                if ($user->berkas && !empty($user->berkas->$field)) {
                    $berkasOpsionalTerupload++;
                }
            }
        }

        $persentaseWajib = $berkasWajib > 0 ? round(($berkasWajibTerupload / $berkasWajib) * 100) : 100;
        $persentaseTotal = $totalBerkas > 0 ? round((($berkasWajibTerupload + $berkasOpsionalTerupload) / $totalBerkas) * 100) : 100;

        return [
            'total_berkas' => $totalBerkas,
            'berkas_wajib' => $berkasWajib,
            'berkas_opsional' => $berkasOpsional,
            'berkas_wajib_terupload' => $berkasWajibTerupload,
            'berkas_opsional_terupload' => $berkasOpsionalTerupload,
            'persentase_wajib' => $persentaseWajib,
            'persentase_total' => $persentaseTotal,
            'is_wajib_lengkap' => $persentaseWajib === 100,
        ];
    }

    public static function getBerkasStatistics(Request $request): array
    {
        $query = User::where('role', 'siswa')
            ->whereNotNull('jalur_pendaftaran')
            ->with(['berkas', 'biodata']);

        if ($request->filled('jalur_pendaftaran')) {
            $query->where('jalur_pendaftaran', $request->jalur_pendaftaran);
        }

        if ($request->filled('status_pendaftaran')) {
            $query->where('status_pendaftaran', $request->status_pendaftaran);
        }

        if ($request->filled('status_berkas')) {
            if ($request->status_berkas === 'ada_berkas') {
                $query->whereHas('berkas');
            } elseif ($request->status_berkas === 'belum_upload') {
                $query->whereDoesntHave('berkas');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $totalSiswa = $query->count();
        $adaBerkas = $query->clone()->whereHas('berkas')->count();
        $menungguVerifikasi = $query->clone()->where('status_pendaftaran', 'menunggu_verifikasi_berkas')->count();
        $berkasTidakLengkap = $query->clone()->where('status_pendaftaran', 'berkas_tidak_lengkap')->count();

        return [
            'total_siswa' => $totalSiswa,
            'ada_berkas' => $adaBerkas,
            'menunggu_verifikasi' => $menungguVerifikasi,
            'berkas_tidak_lengkap' => $berkasTidakLengkap,
        ];
    }

    public static function calculateBerkasProgress($user): array
    {
        // Ensure agama is always a string
        $agama = $user->biodata && $user->biodata->agama ? (string) $user->biodata->agama : 'Islam';

        // Log if biodata or agama is missing
        if (!$user->biodata) {
            Log::warning('Biodata missing for user in calculateBerkasProgress', ['user_id' => $user->id, 'nama_lengkap' => $user->nama_lengkap ?? 'Unknown']);
        } elseif (!$user->biodata->agama) {
            Log::warning('Agama missing in biodata for user in calculateBerkasProgress', ['user_id' => $user->id, 'nama_lengkap' => $user->nama_lengkap ?? 'Unknown']);
        }

        $definisiBerkas = self::getBerkasListForJalur($user->jalur_pendaftaran, $agama);
        return self::getStatistikBerkas($user, $definisiBerkas);
    }

    public static function logBerkasActivity(string $action, ?int $siswaId, int $adminId, array $details = []): void
    {
        Log::info("Berkas Activity: {$action}", [
            'siswa_id' => $siswaId,
            'admin_id' => $adminId,
            'details' => $details,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    public static function getTemplateFiles(): array
    {
        return [
            'ijazah_mda_pernyataan' => [
                'nama_lengkap' => 'Template Surat Pernyataan MDA.pdf',
                'file' => 'templates/ijazah_mda_pernyataan.pdf',
            ],
            'suket_baca_quran' => [
                'nama_lengkap' => 'Template Surat Keterangan Baca Al-Qur\'an.pdf',
                'file' => 'templates/suket_baca_quran.pdf',
            ],
        ];
    }

    public static function getFileIcon(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf';
            case 'jpg':
            case 'jpeg':
            case 'png':
                return 'fas fa-file-image';
            default:
                return 'fas fa-file';
        }
    }

    public static function getDisplayFileName(string $filePath): string
    {
        $filename = basename($filePath);
        return strlen($filename) > 30 ? substr($filename, 0, 27) . '...' : $filename;
    }
}
