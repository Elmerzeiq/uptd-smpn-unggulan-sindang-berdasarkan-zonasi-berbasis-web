<?php

namespace App\Models;

use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable // implements MustVerifyEmail (opsional)
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama_lengkap',
        'nisn',
        'email',
        'password',
        'role',
        // 'foto_profil',
        'email_verified_at',
        'jalur_pendaftaran',
        'kecamatan_domisili',
        'desa_kelurahan_domisili',        // Kolom utama untuk desa/kelurahan
        'desa_domisili',                  // Alias/fallback untuk kompatibilitas
        'no_pendaftaran',
        'status_pendaftaran',
        'catatan_verifikasi',
        'koordinat_domisili_siswa',       // Koordinat dalam format "lat,lng"
        'jarak_ke_sekolah',              // DITAMBAHKAN: Jarak dalam KM
        'jarak_ke_kecamatan',            // Jarak ke pusat kecamatan
        'jarak_ke_desa',                 // Jarak ke pusat desa
        'peringkat_zonasi',              // Peringkat dalam zonasi
        'status_daftar_ulang',
        'tanggal_daftar_ulang_selesai',
        'catatan_daftar_ulang',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_daftar_ulang_selesai' => 'datetime',
        'jarak_ke_sekolah' => 'decimal:2',  // Cast jarak sebagai decimal dengan 2 angka di belakang koma
        'jarak_ke_kecamatan' => 'decimal:2',
        'jarak_ke_desa' => 'decimal:2',
        'peringkat_zonasi' => 'integer',
    ];

    // ===== BASIC ROLE METHODS =====

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepalaSekolah()
    {
        return $this->role === 'kepala_sekolah';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // ===== SCOPE METHODS =====

    /**
     * Scope untuk filter user yang berperan sebagai siswa
     */
    public function scopeSiswa($query)
    {
        return $query->where('role', 'siswa');
    }

    /**
     * Scope untuk filter user yang berperan sebagai admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk filter berdasarkan jalur pendaftaran
     */
    public function scopeByJalur($query, $jalur)
    {
        return $query->where('jalur_pendaftaran', $jalur);
    }

    /**
     * Scope untuk filter berdasarkan status pendaftaran
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_pendaftaran', $status);
    }

    /**
     * Scope untuk filter berdasarkan kecamatan domisili
     */
    public function scopeByKecamatan($query, $kecamatan)
    {
        return $query->where('kecamatan_domisili', $kecamatan);
    }

    /**
     * Scope untuk filter berdasarkan jalur pendaftaran - domisili
     */
    public function scopeJalurDomisili($query)
    {
        return $query->where('jalur_pendaftaran', 'domisili');
    }

    /**
     * Scope untuk filter user yang memiliki koordinat
     */
    public function scopeWithKoordinat($query)
    {
        return $query->whereNotNull('koordinat_domisili_siswa')
            ->where('koordinat_domisili_siswa', '!=', '');
    }

    /**
     * Scope to filter users by role.
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ===== RELATIONSHIP METHODS =====

    public function biodata()
    {
        return $this->hasOne(BiodataSiswa::class, 'user_id');
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'user_id');
    }

    public function wali()
    {
        return $this->hasOne(Wali::class, 'user_id');
    }

    public function berkas()
    {
        return $this->hasOne(BerkasPendaftar::class, 'user_id');
    }

    public function kartuPendaftaran()
    {
        return $this->hasOne(KartuPendaftaran::class, 'user_id');
    }

    public function daftarUlang()
    {
        return $this->hasOne(DaftarUlang::class, 'user_id');
    }

    // ===== ACCESSOR METHODS =====

    /**
     * Accessor untuk mendapatkan desa domisili (prioritas desa_kelurahan_domisili)
     */
    public function getDesaDomisiliAttribute()
    {
        return $this->desa_kelurahan_domisili ?? $this->attributes['desa_domisili'] ?? null;
    }

    /**
     * Accessor untuk koordinat dalam format array
     */
    public function getKoordinatArrayAttribute()
    {
        if (!$this->koordinat_domisili_siswa) {
            return null;
        }

        $coords = explode(',', $this->koordinat_domisili_siswa);
        if (count($coords) === 2 && is_numeric(trim($coords[0])) && is_numeric(trim($coords[1]))) {
            return [
                'lat' => (float) trim($coords[0]),
                'lng' => (float) trim($coords[1])
            ];
        }

        return null;
    }

    /**
     * Accessor for name attribute to maintain compatibility
     */
    public function getNameAttribute()
    {
        return $this->nama_lengkap;
    }

    /**
     * Accessor for is_admin attribute to maintain compatibility
     */
    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }

    /**
     * Get jalur pendaftaran label
     */
    public function getJalurPendaftaranLabelAttribute()
    {
        $labels = [
            'domisili' => 'Zonasi Domisili',
            'prestasi_akademik_lomba' => 'Prestasi Akademik Lomba',
            'prestasi_non_akademik_lomba' => 'Prestasi Non-Akademik Lomba',
            'prestasi_rapor' => 'Prestasi Rapor',
            'afirmasi_ketm' => 'Afirmasi KIP/PKH/KETM',
            'afirmasi_disabilitas' => 'Afirmasi Disabilitas',
            'mutasi_pindah_tugas' => 'Mutasi Pindah Tugas',
            'mutasi_anak_guru' => 'Mutasi Anak Guru'
        ];

        return $labels[$this->jalur_pendaftaran] ?? $this->jalur_pendaftaran;
    }

    /**
     * Get status pendaftaran label
     */
    public function getStatusPendaftaranLabelAttribute()
    {
        $labels = [
            'belum_diverifikasi' => 'Belum Diverifikasi',
            'menunggu_kelengkapan_data' => 'Menunggu Kelengkapan Data',
            'menunggu_verifikasi_berkas' => 'Menunggu Verifikasi Berkas',
            'berkas_tidak_lengkap' => 'Berkas Tidak Lengkap',
            'berkas_diverifikasi' => 'Berkas Diverifikasi',
            'lulus_seleksi' => 'Lulus Seleksi',
            'tidak_lulus_seleksi' => 'Tidak Lulus Seleksi',
            'mengundurkan_diri' => 'Mengundurkan Diri',
            'daftar_ulang_selesai' => 'Daftar Ulang Selesai'
        ];

        return $labels[$this->status_pendaftaran] ?? $this->status_pendaftaran;
    }

    // ===== MUTATOR METHODS =====

    /**
     * Mutator for name attribute to maintain compatibility
     */
    public function setNameAttribute($value)
    {
        $this->attributes['nama_lengkap'] = $value;
    }

    /**
     * Mutator for is_admin attribute to maintain compatibility
     */
    public function setIsAdminAttribute($value)
    {
        $this->attributes['role'] = $value ? 'admin' : 'siswa';
    }

    /**
     * Mutator untuk menyimpan koordinat
     */
    public function setKoordinatDomisiliSiswaAttribute($value)
    {
        if (is_array($value) && isset($value['lat']) && isset($value['lng'])) {
            $this->attributes['koordinat_domisili_siswa'] = $value['lat'] . ',' . $value['lng'];
        } else {
            $this->attributes['koordinat_domisili_siswa'] = $value;
        }
    }

    // ===== HELPER METHODS =====

    /**
     * Method untuk mendapatkan status badge class
     */
    public function getStatusBadgeClass(): string
    {
        if (is_null($this->status_pendaftaran)) {
            return 'bg-secondary';
        }

        switch ($this->status_pendaftaran) {
            case 'lulus_seleksi':
                return 'bg-success text-white';
            case 'tidak_lulus_seleksi':
            case 'berkas_tidak_lengkap':
                return 'bg-danger text-white';
            case 'menunggu_verifikasi_berkas':
            case 'berkas_diverifikasi':
                return 'bg-info text-white';
            case 'belum_diverifikasi':
            case 'menunggu_kelengkapan_data':
            default:
                return 'bg-warning text-dark';
        }
    }

    /**
     * Check if user is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status_pendaftaran === 'lulus_seleksi' ||
            $this->status_pendaftaran === 'daftar_ulang_selesai';
    }

    /**
     * Check if user is rejected
     */
    public function isRejected(): bool
    {
        return $this->status_pendaftaran === 'tidak_lulus_seleksi' ||
            $this->status_pendaftaran === 'berkas_tidak_lengkap';
    }

    /**
     * Get pengumuman target based on status
     */
    public function getPengumumanTarget(): string
    {
        if ($this->isAccepted()) {
            return 'siswa_diterima';
        }

        if ($this->isRejected()) {
            return 'siswa_ditolak';
        }

        return 'calon_siswa';
    }

    /**
     * Generate nomor pendaftaran unik
     */
    public function generateNomorPendaftaran(): string
    {
        $year = date('Y');
        $jalurCode = [
            'domisili' => 'DOM',
            'prestasi_akademik_lomba' => 'PAL',
            'prestasi_non_akademik_lomba' => 'PNL',
            'prestasi_rapor' => 'PRA',
            'afirmasi_ketm' => 'AKT',
            'afirmasi_disabilitas' => 'ADI',
            'mutasi_pindah_tugas' => 'MPT',
            'mutasi_anak_guru' => 'MAG'
        ];

        $code = $jalurCode[$this->jalur_pendaftaran] ?? 'UMM';
        $sequence = str_pad(self::siswa()->count() + 1, 4, '0', STR_PAD_LEFT);

        return $year . $code . $sequence;
    }

    // ===== STATIC METHODS =====

    /**
     * Get user statistics for dashboard
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::siswa()->count(),
            'lulus' => self::siswa()->where('status_pendaftaran', 'lulus_seleksi')->count(),
            'menunggu_verifikasi' => self::siswa()->where('status_pendaftaran', 'menunggu_verifikasi_berkas')->count(),
            'belum_lengkap' => self::siswa()->where('status_pendaftaran', 'menunggu_kelengkapan_data')->count(),
            'berkas_diverifikasi' => self::siswa()->where('status_pendaftaran', 'berkas_diverifikasi')->count(),
            'tidak_lulus' => self::siswa()->where('status_pendaftaran', 'tidak_lulus_seleksi')->count(),
            'daftar_ulang_selesai' => self::siswa()->where('status_pendaftaran', 'daftar_ulang_selesai')->count(),
        ];
    }

    /**
     * Get available roles
     */
    public static function getAllRoles(): array
    {
        return [
            'admin' => 'Administrator',
            'siswa' => 'Siswa',
        ];
    }

    /**
     * Cek apakah siswa sudah melengkapi semua berkas wajib
     * Method ini harus ditambahkan ke model User
     */
    public function hasCompletedRequiredBerkas(): bool
    {
        // Pastikan ada jalur pendaftaran
        if (!$this->jalur_pendaftaran) {
            return false;
        }

        // Pastikan ada data berkas
        if (!$this->berkas) {
            return false;
        }

        // Load biodata jika belum di-load
        if (!$this->relationLoaded('biodata')) {
            $this->load('biodata');
        }

        // Ambil agama dari biodata, default Islam jika tidak ada
        $agama = $this->biodata && $this->biodata->agama ? $this->biodata->agama : 'Islam';

        // Dapatkan definisi berkas untuk jalur ini
        $definisiBerkas = $this->getBerkasListForJalur($this->jalur_pendaftaran, $agama);

        // Cek setiap berkas yang required
        foreach ($definisiBerkas as $field => $details) {
            if ($details['required'] === true) {
                if (empty($this->berkas->$field)) {
                    // Untuk berkas multiple, cek apakah array kosong
                    if (isset($details['multiple']) && $details['multiple']) {
                        $fileArray = json_decode($this->berkas->$field, true);
                        if (empty($fileArray)) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    // App/Models/KartuPendaftaran.php
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    /**
     * Mendapatkan daftar berkas yang diperlukan untuk jalur pendaftaran tertentu
     * Method ini juga harus ditambahkan ke model User
     */
    public function getBerkasListForJalur(string $jalurPendaftaranSiswa, string $agama = 'Islam'): array
    {
        $berkas = [];

        $common = [
            'file_ijazah_skl' => ['label' => 'Ijazah SD/MI / SKL', 'keterangan' => 'Scan Ijazah (legalisir) atau Surat Keterangan Lulus. Format: PDF/JPG/PNG, Max: 5MB.', 'required' => true, 'multiple' => false],
            'file_nisn_screenshot' => ['label' => 'Screenshot NISN (dari web vervalpd)', 'keterangan' => 'File hasil screenshot dari website vervalpd. Format: PDF/JPG/PNG, Max: 5MB.', 'required' => true, 'multiple' => false],
            'file_kk' => ['label' => 'Kartu Keluarga (KK)', 'keterangan' => 'Scan Kartu Keluarga terbaru. Format: PDF/JPG/PNG, Max: 1MB.', 'required' => true, 'multiple' => false],
            'file_akta_kia' => ['label' => 'Akta Kelahiran / KIA / Surat Lahir', 'keterangan' => 'Scan salah satu dokumen identitas anak. Format: PDF/JPG/PNG, Max: 5MB.', 'required' => true, 'multiple' => false],
            'file_ktp_ortu' => ['label' => 'KTP Orang Tua (Ayah/Ibu)', 'keterangan' => 'Scan KTP salah satu orang tua. Format: PDF/JPG/PNG, Max: 5MB.', 'required' => true, 'multiple' => false],
            'file_pas_foto' => ['label' => 'Pas Foto 3x4 (Background Merah)', 'keterangan' => 'Format: JPG/PNG, Max: 500KB.', 'required' => true, 'multiple' => false],
            'file_surat_pernyataan_ortu' => ['label' => 'Surat Pernyataan Keabsahan Dokumen', 'keterangan' => 'Unduh template di website sekolah, cetak, tanda tangani Ortu/Wali, lalu scan/foto. Format: PDF/JPG/PNG, Max: 5MB.', 'required' => true, 'multiple' => false],
            'file_skkb_sd_desa' => ['label' => 'SKKB dari SD/MI atau Desa', 'keterangan' => 'Scan Surat Keterangan Kelakuan Baik dari sekolah asal atau Pemerintah Desa/Kelurahan. Format: PDF/JPG/PNG, Max: 5MB.', 'required' => true, 'multiple' => false],
        ];

        // Berkas MDA berdasarkan agama
        $religious = [];
        $agamaNormalized = strtolower(trim($agama));

        if ($agamaNormalized === 'islam' || $agamaNormalized === 'muslim') {
            // KHUSUS untuk Muslim: berkas MDA OPSIONAL
            $religious = [
                'file_ijazah_mda_pernyataan' => [
                    'label' => 'Ijazah MDA / Surat Pernyataan Ikut MDA (Opsional)',
                    'keterangan' => 'Scan Ijazah MDA atau Surat Pernyataan masih/pernah mengikuti MDA. Format: PDF/JPG/PNG, Max: 5MB. OPSIONAL untuk siswa Muslim.',
                    'required' => false,
                    'multiple' => false
                ],
                'file_suket_baca_quran_mda' => [
                    'label' => "Surat Ket. Mampu Baca Al-Qur'an dari MDA (Opsional)",
                    'keterangan' => "Scan Surat Keterangan Mampu Membaca Al-Qur'an dari Kepala MDA/Ustaz. Format: PDF/JPG/PNG, Max: 5MB. OPSIONAL untuk siswa Muslim.",
                    'required' => false,
                    'multiple' => false
                ],
            ];
        } else {
            // Untuk non-Muslim: berkas keagamaan umum (opsional)
            $religious = [
                'file_surat_keagamaan' => [
                    'label' => 'Surat Keterangan Keagamaan (Opsional)',
                    'keterangan' => 'Scan Surat Keterangan dari Pemimpin Agama terkait. Format: PDF/JPG/PNG, Max: 5MB. OPSIONAL untuk siswa non-Muslim.',
                    'required' => false,
                    'multiple' => false
                ],
            ];
        }

        $berkas = array_merge($common, $religious);

        // Berkas khusus jalur pendaftaran
        switch ($jalurPendaftaranSiswa) {
            case 'domisili':
                $berkas['file_suket_domisili'] = [
                    'label' => 'Surat Keterangan Domisili (Jika KK < 1 thn)',
                    'keterangan' => 'Format: PDF/JPG/PNG, Max: 5MB. Diperlukan jika domisili di KK kurang dari 1 tahun.',
                    'required' => false,
                    'multiple' => false
                ];
                break;
            case 'prestasi_akademik':
            case 'prestasi_non_akademik':
                $berkas['file_sertifikat_prestasi_lomba'] = [
                    'label' => 'Scan Sertifikat Kejuaraan/Prestasi Lomba',
                    'keterangan' => 'Min. Juara 3 Kab/Kota. Format: PDF/JPG/PNG, Max: 5MB per file. Dapat mengupload multiple file (maks 5 file).',
                    'required' => true,
                    'multiple' => true
                ];
                $berkas['file_surat_pertanggungjawaban_kepsek_lomba'] = [
                    'label' => 'Surat Pertanggungjawaban Kepsek Asal (Lomba)',
                    'keterangan' => 'Format: PDF, Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                break;
            case 'prestasi_rapor':
                $berkas['file_rapor_5_semester'] = [
                    'label' => 'Scan Rapor 5 Semester Terakhir',
                    'keterangan' => 'Scan rapor Kelas 4, 5 (Smst 1&2), Kls 6 (Smst 1) dalam 1 file PDF gabungan. Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                $berkas['file_suket_nilai_rapor_peringkat_kepsek'] = [
                    'label' => 'Surat Ket. Nilai Rapor & Peringkat dari Kepsek',
                    'keterangan' => 'Surat Keterangan nilai rapor dan peringkat kelas dari Kepala Sekolah Asal. Format: PDF, Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                break;
            case 'afirmasi_ketm':
                $berkas['file_kartu_bantuan_sosial'] = [
                    'label' => 'KIP/PKH/KKS (Pilih salah satu)',
                    'keterangan' => 'Scan salah satu kartu bantuan sosial (KIP/PKH/KKS). Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                $berkas['file_sktm_dtks_dinsos'] = [
                    'label' => 'SKTM / Ket. DTKS Dinsos (Jika tidak ada Kartu Bantuan)',
                    'keterangan' => 'Scan SKTM dari Desa/Kelurahan atau Surat Keterangan Terdaftar DTKS dari Dinsos. Format: PDF/JPG/PNG, Max: 5MB. Opsional jika sudah ada kartu bantuan.',
                    'required' => false,
                    'multiple' => false
                ];
                break;
            case 'afirmasi_disabilitas':
                $berkas['file_suket_disabilitas_dokter_psikolog'] = [
                    'label' => 'Surat Ket. Disabilitas dari Dokter/Psikolog',
                    'keterangan' => 'Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                break;
            case 'mutasi_ortu':
                $berkas['file_surat_penugasan_ortu_instansi'] = [
                    'label' => 'Surat Penugasan Pindah Tugas Ortu/Wali',
                    'keterangan' => 'Scan Surat Penugasan dari Instansi/Lembaga/Perusahaan tempat Ortu/Wali bekerja. Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB. Opsional.',
                    'required' => false,
                    'multiple' => false
                ];
                break;
            case 'mutasi_guru':
                $berkas['file_sk_penugasan_guru_tendik'] = [
                    'label' => 'SK Penugasan Guru/Tendik (Sekolah Tujuan)',
                    'keterangan' => 'Scan SK Penugasan sebagai Guru/Tenaga Kependidikan di SMPN Unggulan Sindang. Format: PDF/JPG/PNG, Max: 5MB.',
                    'required' => true,
                    'multiple' => false
                ];
                $berkas['file_surat_rekomendasi_dirjen_luarnegeri'] = [
                    'label' => 'Surat Rekomendasi Dirjen (Jika dari LN)',
                    'keterangan' => 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri). Format: PDF/JPG/PNG, Max: 5MB. Opsional.',
                    'required' => false,
                    'multiple' => false
                ];
                break;
        }

        return $berkas;
    }


    public function isBiodataComplete()
    {
        if (!$this->biodata || !$this->orangTua) {
            return false;
        }

        // Required biodata fields
        $requiredBiodataFields = [
            'tempat_lahir',
            'tgl_lahir',
            'jns_kelamin',
            'agama',
            'anak_ke',
            'jml_saudara_kandung',
            'asal_sekolah',
            'alamat_asal_sekolah',
            'alamat_rumah',
            'kelurahan_desa',
            'rt',
            'rw',
            'kode_pos'
        ];

        foreach ($requiredBiodataFields as $field) {
            if (empty($this->biodata->$field)) {
                return false;
            }
        }

        // Required orang tua fields (minimal nama ibu)
        if (empty($this->orangTua->nama_ibu)) {
            return false;
        }

        return true;
    }

    /**
     * Check if berkas is complete
     */
    public function isBerkasComplete()
    {
        if (!$this->berkas) {
            return false;
        }

        $requiredBerkasFields = [
            'file_akta_kelahiran',
            'file_kartu_keluarga',
            'file_foto_siswa',
            'file_ijazah_sd'
        ];

        foreach ($requiredBerkasFields as $field) {
            if (empty($this->berkas->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user can edit their data
     */
    public function canEdit()
    {
        $finalStatuses = [
            'berkas_diverifikasi',
            'lulus_seleksi',
            'tidak_lulus_seleksi',
            'daftar_ulang_selesai'
        ];

        // If status is berkas_tidak_lengkap, still can edit
        if ($this->status_pendaftaran === 'berkas_tidak_lengkap') {
            return true;
        }

        try {
            $jadwalAktif = app('App\Services\JadwalService')->getJadwalAktif();
            $pendaftaranOpen = $jadwalAktif && $jadwalAktif->isPendaftaranOpen();
        } catch (Exception $e) {
            $pendaftaranOpen = false;
        }

        return $pendaftaranOpen && !in_array($this->status_pendaftaran, $finalStatuses);
    }


    // public function isBiodataLengkap(): bool
    // {
    //     // Pastikan relasi biodata dan orangTua ada,
    //     // dan field-field penting tidak kosong.
    //     return $this->biodata &&
    //         $this->orangTua &&
    //         !empty($this->biodata->tempat_lahir) &&
    //         !empty($this->biodata->tgl_lahir) &&
    //         !empty($this->orangTua->nama_ibu);
    // }

    /**
     * Memeriksa apakah semua berkas yang WAJIB sudah diupload.
     *
     * @return bool
     */
    // public function hasCompletedRequiredBerkas(): bool
    // {
    //     if (!$this->berkas || !$this->jalur_pendaftaran) {
    //         return false;
    //     }

    //     $agama = $this->biodata->agama ?? 'Islam';
    //     $definisiBerkas = \App\Helpers\BerkasHelper::getBerkasListForJalur($this->jalur_pendaftaran, $agama);

    //     foreach ($definisiBerkas as $field => $details) {
    //         if ($details['required']) {
    //             if (empty($this->berkas->$field)) {
    //                 return false;
    //             }
    //             // Khusus untuk multiple file, pastikan array-nya tidak kosong
    //             if (isset($details['multiple']) && $details['multiple']) {
    //                 $files = json_decode($this->berkas->$field, true);
    //                 if (empty($files)) {
    //                     return false;
    //                 }
    //             }
    //         }
    //     }
    //     return true;
    // }



// public function daftarUlang()
// {
//     return $this->hasOne(DaftarUlang::class);
// }

/**
 * Cek apakah siswa sudah menyelesaikan daftar ulang
 */
public function hasCompletedDaftarUlang()
{
    return $this->daftarUlang && $this->daftarUlang->status_daftar_ulang === 'daftar_ulang_selesai';
}

/**
 * Cek apakah siswa eligible untuk daftar ulang
 */
public function isEligibleForDaftarUlang()
{
    return $this->status_pendaftaran === 'lulus_seleksi';
}

/**
 * Get status daftar ulang untuk display
 */
public function getDaftarUlangStatusAttribute()
{
    if (!$this->daftarUlang) {
        return $this->status_pendaftaran === 'lulus_seleksi' ? 'Belum Daftar Ulang' : 'Tidak Eligible';
    }

    $statusMap = [
        'belum_daftar' => 'Belum Daftar',
        'menunggu_verifikasi_berkas' => 'Menunggu Verifikasi Berkas',
        'berkas_diverifikasi' => 'Berkas Diverifikasi',
        'menunggu_verifikasi_pembayaran' => 'Menunggu Verifikasi Pembayaran',
        'daftar_ulang_selesai' => 'Daftar Ulang Selesai',
        'ditolak' => 'Ditolak'
    ];

    return $statusMap[$this->daftarUlang->status_daftar_ulang] ?? 'Unknown';
}



    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sentComments()
    {
        return $this->hasMany(Comment::class)->where('from_role', $this->role);
    }

    public function receivedComments()
    {
        return $this->hasMany(Comment::class, 'to_role', 'role');
    }

    /**
     * Check if user has unread comments
     */
    public function hasUnreadComments()
    {
        return Comment::where('to_role', $this->role)
            ->whereNull('read_at')
            ->exists();
    }

    /**
     * Get unread comments count
     */
    public function getUnreadCommentsCount()
    {
        return Comment::where('to_role', $this->role)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Scope for role-based queries
     */



}

