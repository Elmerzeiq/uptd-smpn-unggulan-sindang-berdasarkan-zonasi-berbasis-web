<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class BerkasService
{
    /**
     * Get berkas list based on jalur and agama
     */
    public function getBerkasListByJalur($jalur, $agama = 'Islam')
    {
        $berkas = [];

        // Common berkas for all jalur
        $common = [
            'file_ijazah_skl' => [
                'label' => 'Ijazah SD/MI / SKL',
                'keterangan' => 'Scan Ijazah (legalisir) atau Surat Keterangan Lulus. Format: PDF/JPG/PNG, Max: 5MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_nisn_screenshot' => [
                'label' => 'Screenshot NISN (dari web vervalpd)',
                'keterangan' => 'File hasil screenshot dari website vervalpd. Format: PDF/JPG/PNG, Max: 5MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_kk' => [
                'label' => 'Kartu Keluarga (KK)',
                'keterangan' => 'Scan Kartu Keluarga terbaru. Format: PDF/JPG/PNG, Max: 1MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_akta_kia' => [
                'label' => 'Akta Kelahiran / KIA',
                'keterangan' => 'Scan salah satu dokumen identitas anak. Format: PDF/JPG/PNG, Max: 5MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_ktp_ortu' => [
                'label' => 'KTP Orang Tua (Ayah/Ibu)',
                'keterangan' => 'Scan KTP salah satu orang tua. Format: PDF/JPG/PNG, Max: 5MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_pas_foto' => [
                'label' => 'Pas Foto 3x4 (Background Merah)',
                'keterangan' => 'Format: JPG/PNG, Max: 500KB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_surat_pernyataan_ortu' => [
                'label' => 'Surat Pernyataan Keabsahan Dokumen',
                'keterangan' => 'Unduh template di website sekolah, cetak, tanda tangani Ortu/Wali, lalu scan/foto. Format: PDF/JPG/PNG, Max: 5MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
            'file_skkb_sd_desa' => [
                'label' => 'SKKB dari SD/MI atau Desa',
                'keterangan' => 'Scan Surat Keterangan Kelakuan Baik dari sekolah asal atau Pemerintah Desa/Kelurahan. Format: PDF/JPG/PNG, Max: 5MB.',
                'required' => true,
                'multiple' => false,
                'category' => 'common'
            ],
        ];

        // Religious berkas
        $agamaNormalized = strtolower(trim($agama));
        $religious = [];
        if ($agamaNormalized === 'islam' || $agamaNormalized === 'muslim') {
            $religious = [
                'file_ijazah_mda_pernyataan' => [
                    'label' => 'Ijazah MDA / Surat Pernyataan (Opsional)',
                    'keterangan' => 'Scan Ijazah MDA/Surat Pernyataan ikut MDA. Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'religious'
                ],
                'file_suket_baca_quran_mda' => [
                    'label' => "Surat Ket. Mampu Baca Al-Qur'an (Opsional)",
                    'keterangan' => "Scan Surat Ket. dari Kepala MDA/Ustaz. Format: PDF/JPG/PNG, Max: 5MB.",
                    'required' => false,
                    'multiple' => false,
                    'category' => 'religious'
                ],
            ];
        }

        $berkas = array_merge($common, $religious);

        // Jalur specific berkas
        $validJalurs = [
            'domisili',
            'prestasi_akademik',
            'prestasi_non_akademik',
            'prestasi_rapor',
            'afirmasi_ketm',
            'afirmasi_disabilitas',
            'mutasi_ortu',
            'mutasi_guru'
        ];

        if (!in_array($jalur, $validJalurs)) {
            Log::warning('Invalid jalur provided, fallback to common berkas only: ' . $jalur);
            return $berkas;
        }

        switch ($jalur) {
            case 'domisili':
                $berkas['file_suket_domisili'] = [
                    'label' => 'Surat Keterangan Domisili (Jika KK < 1 thn)',
                    'keterangan' => 'Format: PDF/JPG/PNG, Max: 5MB. Diperlukan jika domisili di KK kurang dari 1 tahun.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;

            case 'prestasi_akademik':
            case 'prestasi_non_akademik':
                $berkas['file_sertifikat_prestasi_lomba'] = [
                    'label' => 'Scan Sertifikat Kejuaraan/Prestasi Lomba',
                    'keterangan' => 'Min. Juara 3 Kab/Kota. Format: PDF/JPG/PNG, Max: 5MB/file. Max 5 file.',
                    'required' => true,
                    'multiple' => true,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_pertanggungjawaban_kepsek_lomba'] = [
                    'label' => 'Surat Pertanggungjawaban Kepsek Asal (Lomba)',
                    'keterangan' => 'Format: PDF, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_rapor_5_semester'] = [
                    'label' => 'Scan Rapor 5 Semester Terakhir',
                    'keterangan' => 'Scan rapor Kelas 4, 5 (Smst 1&2), Kls 6 (Smst 1) dalam 1 file PDF gabungan. Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_suket_nilai_rapor_peringkat_kepsek'] = [
                    'label' => 'Surat Ket. Nilai Rapor & Peringkat dari Kepsek',
                    'keterangan' => 'Surat Keterangan nilai rapor dan peringkat kelas dari Kepala Sekolah Asal. Format: PDF, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;

            case 'prestasi_rapor':
                $berkas['file_rapor_5_semester'] = [
                    'label' => 'Scan Rapor 5 Semester Terakhir',
                    'keterangan' => 'Scan rapor Kelas 4, 5 (Smst 1&2), Kls 6 (Smst 1) dalam 1 file PDF gabungan. Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_suket_nilai_rapor_peringkat_kepsek'] = [
                    'label' => 'Surat Ket. Nilai Rapor & Peringkat dari Kepsek',
                    'keterangan' => 'Surat Keterangan nilai rapor dan peringkat kelas dari Kepala Sekolah Asal. Format: PDF, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;

            case 'afirmasi_ketm':
                $berkas['file_kartu_bantuan_sosial'] = [
                    'label' => 'KIP/PKH/KKS (Pilih salah satu)',
                    'keterangan' => 'Scan salah satu kartu bantuan sosial (KIP/PKH/KKS). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_sktm_dtks_dinsos'] = [
                    'label' => 'SKTM / Ket. DTKS Dinsos (Jika tidak ada Kartu Bantuan)',
                    'keterangan' => 'Scan SKTM dari Desa/Kelurahan atau Surat Keterangan Terdaftar DTKS dari Dinsos. Format: PDF/JPG/PNG, Max: 5MB. Opsional jika sudah ada kartu bantuan.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;

            case 'afirmasi_disabilitas':
                $berkas['file_suket_disabilitas_dokter_psikolog'] = [
                    'label' => 'Surat Ket. Disabilitas dari Dokter/Psikolog',
                    'keterangan' => 'Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;

            case 'mutasi_ortu':
                $berkas['file_surat_penugasan_ortu_instansi'] = [
                    'label' => 'Surat Penugasan Pindah Tugas Ortu/Wali',
                    'keterangan' => 'Scan Surat Penugasan dari Instansi/Lembaga/Perusahaan tempat Ortu/Wali bekerja. Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;

            case 'mutasi_guru':
                $berkas['file_sk_penugasan_guru_tendik'] = [
                    'label' => 'SK Penugasan Guru/Tendik (Sekolah Tujuan)',
                    'keterangan' => 'Scan SK Penugasan sebagai Guru/Tenaga Kependidikan di SMPN Unggulan Sindang. Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => false,
                    'multiple' => false,
                    'category' => 'jalur'
                ];
                break;
        }

        return $berkas;
    }

    /**
     * Calculate berkas progress for a user
     */
    public function calculateBerkasProgress(User $user): array
    {
        if (!$user->jalur_pendaftaran) {
            return [
                'berkas_wajib' => 0,
                'berkas_wajib_terupload' => 0,
                'percentage_wajib' => 0,
                'is_complete' => false,
            ];
        }

        $agama = $user->biodata ? $user->biodata->agama : 'Islam';
        $definisi = $this->getBerkasListByJalur($user->jalur_pendaftaran, $agama);

        $wajib = 0;
        $wajibUploaded = 0;

        foreach ($definisi as $field => $details) {
            if ($details['required']) {
                $wajib++;
                if ($user->berkas && !empty($user->berkas->$field)) {
                    if (isset($details['multiple']) && $details['multiple']) {
                        if (!empty(json_decode($user->berkas->$field, true))) {
                            $wajibUploaded++;
                        }
                    } else {
                        $wajibUploaded++;
                    }
                }
            }
        }

        return [
            'berkas_wajib' => $wajib,
            'berkas_wajib_terupload' => $wajibUploaded,
            'percentage_wajib' => $wajib > 0 ? round(($wajibUploaded / $wajib) * 100) : 100,
            'is_complete' => $wajibUploaded === $wajib,
        ];
    }

    /**
     * Check if all required berkas are uploaded
     */
    public function isBerkasWajibLengkap(User $user, array $definisiBerkas): bool
    {
        if (!$user->berkas) {
            return false;
        }

        foreach ($definisiBerkas as $field => $details) {
            if ($details['required'] === true) {
                if (empty($user->berkas->$field)) {
                    return false;
                }
                if (isset($details['multiple']) && $details['multiple']) {
                    if (empty(json_decode($user->berkas->$field, true))) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Get berkas statistics for admin dashboard
     */
    public function getBerkasStatistics($jalur = null): array
    {
        $query = \App\Models\User::where('role', 'siswa')
            ->whereNotNull('jalur_pendaftaran')
            ->with(['berkas', 'biodata']);

        if ($jalur) {
            $query->where('jalur_pendaftaran', $jalur);
        }

        $totalSiswa = $query->count();
        $adaBerkas = $query->clone()->whereHas('berkas')->count();
        $menungguVerifikasi = $query->clone()->where('status_pendaftaran', 'menunggu_verifikasi_berkas')->count();
        $berkasTidakLengkap = $query->clone()->where('status_pendaftaran', 'berkas_tidak_lengkap')->count();
        $berkasVerified = $query->clone()->whereHas('berkas', function ($q) {
            $q->whereNotNull('verified_at');
        })->count();

        return [
            'total_siswa' => $totalSiswa,
            'ada_berkas' => $adaBerkas,
            'menunggu_verifikasi' => $menungguVerifikasi,
            'berkas_tidak_lengkap' => $berkasTidakLengkap,
            'berkas_verified' => $berkasVerified,
        ];
    }

    /**
     * Get file icon based on extension
     */
    public function getFileIcon(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf text-danger';
            case 'jpg':
            case 'jpeg':
            case 'png':
                return 'fas fa-file-image text-primary';
            default:
                return 'fas fa-file text-secondary';
        }
    }

    /**
     * Get display filename (truncated if too long)
     */
    public function getDisplayFileName(string $filePath): string
    {
        $filename = basename($filePath);
        return strlen($filename) > 30 ? substr($filename, 0, 27) . '...' : $filename;
    }

    /**
     * Validate file upload requirements
     */
    public function validateBerkasUpload(array $files, array $definisiBerkas): array
    {
        $errors = [];
        $fileTypes = 'pdf,jpg,jpeg,png';

        foreach ($files as $field => $file) {
            if (!isset($definisiBerkas[$field])) {
                $errors[$field] = 'Field berkas tidak valid.';
                continue;
            }

            $details = $definisiBerkas[$field];

            // Extract max size from keterangan
            preg_match('/Max:\s*(\d+)\s*(MB|KB)/i', $details['keterangan'], $matches);
            $maxSizeKB = 5 * 1024; // Default 5MB
            if (count($matches) === 3) {
                $size = (int)$matches[1];
                $unit = strtolower($matches[2]);
                $maxSizeKB = ($unit === 'mb') ? $size * 1024 : $size;
            }

            if (is_array($file)) {
                // Multiple files
                if (count($file) > 5) {
                    $errors[$field] = 'Maksimal 5 file yang dapat diupload.';
                    continue;
                }

                foreach ($file as $index => $singleFile) {
                    if ($singleFile->getSize() > ($maxSizeKB * 1024)) {
                        $errors[$field . '.' . $index] = 'Ukuran file terlalu besar. Maksimal ' . ($maxSizeKB > 1024 ? round($maxSizeKB / 1024) . 'MB' : $maxSizeKB . 'KB');
                    }
                }
            } else {
                // Single file
                if ($file->getSize() > ($maxSizeKB * 1024)) {
                    $errors[$field] = 'Ukuran file terlalu besar. Maksimal ' . ($maxSizeKB > 1024 ? round($maxSizeKB / 1024) . 'MB' : $maxSizeKB . 'KB');
                }
            }
        }

        return $errors;
    }

    /**
     * Log berkas activity
     */
    public function logBerkasActivity(string $action, ?int $siswaId, int $adminId, array $details = []): void
    {
        Log::info("Berkas Activity: {$action}", [
            'siswa_id' => $siswaId,
            'admin_id' => $adminId,
            'details' => $details,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
