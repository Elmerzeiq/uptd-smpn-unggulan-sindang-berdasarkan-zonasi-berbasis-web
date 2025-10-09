<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BerkasPendaftar extends Model
{
    use HasFactory;

    protected $table = 'berkas_pendaftars';

    protected $fillable = [
        'user_id',

        // Berkas umum
        'file_ijazah_skl',
        'file_nisn_screenshot',
        'file_kk',
        'file_akta_kia',
        'file_ktp_ortu',
        'file_pas_foto',
        'file_surat_pernyataan_ortu',
        'file_skkb_sd_desa',

        // Berkas khusus agama
        'file_ijazah_mda_pernyataan',
        'file_suket_baca_quran_mda',

        // Berkas jalur domisili
        'file_suket_domisili',

        // Berkas jalur prestasi
        'file_sertifikat_prestasi_lomba',
        'file_surat_pertanggungjawaban_kepsek_lomba',
        'file_rapor_5_semester',
        'file_suket_nilai_rapor_peringkat_kepsek',

        // Berkas jalur afirmasi
        'file_kartu_bantuan_sosial',
        'file_sktm_dtks_dinsos',
        'file_suket_disabilitas_dokter_psikolog',

        // Berkas jalur mutasi
        'file_surat_penugasan_ortu_instansi',
        'file_sk_penugasan_guru_tendik',
        'file_surat_rekomendasi_dirjen_luarnegeri',

        // Metadata verifikasi
        'verified_at',
        'verified_by',
        'verification_notes',
    ];

    protected $casts = [
        'file_sertifikat_prestasi_lomba' => 'array',
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Siswa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User yang memverifikasi (Admin)
     * Fixed: Removed duplicate method
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get all file fields that can contain files
     */
    public function getFileFields(): array
    {
        return [
            'file_ijazah_skl',
            'file_nisn_screenshot',
            'file_kk',
            'file_akta_kia',
            'file_ktp_ortu',
            'file_pas_foto',
            'file_surat_pernyataan_ortu',
            'file_skkb_sd_desa',
            'file_ijazah_mda_pernyataan',
            'file_suket_baca_quran_mda',
            'file_suket_domisili',
            'file_sertifikat_prestasi_lomba',
            'file_surat_pertanggungjawaban_kepsek_lomba',
            'file_rapor_5_semester',
            'file_suket_nilai_rapor_peringkat_kepsek',
            'file_kartu_bantuan_sosial',
            'file_sktm_dtks_dinsos',
            'file_suket_disabilitas_dokter_psikolog',
            'file_surat_penugasan_ortu_instansi',
            'file_sk_penugasan_guru_tendik',
            'file_surat_rekomendasi_dirjen_luarnegeri',
        ];
    }

    /**
     * Get fields that support multiple files
     */
    public function getMultipleFileFields(): array
    {
        return [
            'file_sertifikat_prestasi_lomba'
        ];
    }

    /**
     * Cek apakah ada file yang terupload
     */
    public function hasAnyFile(): bool
    {
        foreach ($this->getFileFields() as $field) {
            if (!empty($this->$field)) {
                if (in_array($field, $this->getMultipleFileFields())) {
                    $files = is_array($this->$field) ? $this->$field : json_decode($this->$field, true);
                    if (!empty($files)) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Hitung jumlah berkas yang terupload
     */
    public function getUploadedFilesCount(): int
    {
        $count = 0;
        foreach ($this->getFileFields() as $field) {
            if (!empty($this->$field)) {
                if (in_array($field, $this->getMultipleFileFields())) {
                    $files = is_array($this->$field) ? $this->$field : json_decode($this->$field, true);
                    if (!empty($files)) {
                        $count++;
                    }
                } else {
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * Dapatkan URL file
     */
    public function getFileUrl($field)
    {
        if (empty($this->$field)) {
            return null;
        }

        if (in_array($field, $this->getMultipleFileFields())) {
            $files = is_array($this->$field) ? $this->$field : json_decode($this->$field, true);
            if (!empty($files)) {
                return array_map(function ($file) {
                    return Storage::url($file);
                }, $files);
            }
            return null;
        }

        return Storage::url($this->$field);
    }

    /**
     * Cek apakah file exist di storage
     */
    public function fileExists($field, $index = null): bool
    {
        if (empty($this->$field)) {
            return false;
        }

        if (in_array($field, $this->getMultipleFileFields())) {
            $files = is_array($this->$field) ? $this->$field : json_decode($this->$field, true);
            if (empty($files)) {
                return false;
            }

            if ($index !== null) {
                return isset($files[$index]) && Storage::disk('public')->exists($files[$index]);
            }

            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file)) {
                    return true;
                }
            }
            return false;
        }

        return Storage::disk('public')->exists($this->$field);
    }

    /**
     * Dapatkan ukuran file dalam KB
     */
    public function getFileSize($field, $index = null): ?float
    {
        if (!$this->fileExists($field, $index)) {
            return null;
        }

        if (in_array($field, $this->getMultipleFileFields())) {
            $files = is_array($this->$field) ? $this->$field : json_decode($this->$field, true);
            if ($index !== null && isset($files[$index])) {
                return Storage::disk('public')->size($files[$index]) / 1024;
            }
            return null;
        }

        return Storage::disk('public')->size($this->$field) / 1024;
    }

    /**
     * Get human readable file size
     */
    public function getHumanFileSize($field, $index = null): ?string
    {
        $sizeKB = $this->getFileSize($field, $index);
        if ($sizeKB === null) {
            return null;
        }

        if ($sizeKB < 1024) {
            return round($sizeKB, 1) . ' KB';
        }

        return round($sizeKB / 1024, 1) . ' MB';
    }

    /**
     * Scope untuk berkas yang sudah diverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope untuk berkas yang belum diverifikasi
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    /**
     * Scope untuk berkas milik jalur tertentu
     */
    public function scopeByJalur($query, $jalur)
    {
        return $query->whereHas('user', function ($q) use ($jalur) {
            $q->where('jalur_pendaftaran', $jalur);
        });
    }

    /**
     * Mark berkas sebagai verified
     */
    public function markAsVerified($verifiedBy = null, $notes = null)
    {
        $this->update([
            'verified_at' => now(),
            'verified_by' => $verifiedBy ?? auth()->id(),
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Mark berkas sebagai unverified
     */
    public function markAsUnverified($notes = null)
    {
        $this->update([
            'verified_at' => null,
            'verified_by' => null,
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Check if berkas is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Hapus file dari storage
     */
    public function deleteFile($field, $index = null): bool
    {
        if (empty($this->$field)) {
            return false;
        }

        if (in_array($field, $this->getMultipleFileFields())) {
            $files = is_array($this->$field) ? $this->$field : json_decode($this->$field, true);

            if ($index !== null && isset($files[$index])) {
                $filePath = $files[$index];
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                unset($files[$index]);
                $this->update([
                    $field => !empty($files) ? json_encode(array_values($files)) : null
                ]);
                return true;
            }

            // Hapus semua files
            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
            $this->update([$field => null]);
            return true;
        }

        // Single file
        $filePath = $this->$field;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $this->update([$field => null]);
        return true;
    }

    /**
     * Hapus semua file saat model dihapus
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($berkas) {
            foreach ($berkas->getFileFields() as $field) {
                if (!empty($berkas->$field)) {
                    $berkas->deleteFile($field);
                }
            }
        });
    }
}
