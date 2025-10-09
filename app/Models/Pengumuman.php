<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Pengumuman extends Model
{
    use HasFactory;

    /**
     * PERBAIKAN: Nama tabel disesuaikan dengan yang ada di database.
     * Konvensi Laravel menggunakan bentuk jamak dengan 's' -> 'pengumumans'.
     * Error sebelumnya mencari 'pengumumen'.
     *
     * @var string
     */
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'isi',
        'tipe',
        'tanggal',
        'user_id',
        'target_penerima',
        'aktif',
        'priority',
        'views_count',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'aktif' => 'boolean',
    ];

    /**
     * Relasi ke model User (admin).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk pengumuman umum.
     */
    public function scopeUmum(Builder $query): Builder
    {
        return $query->where('tipe', '!=', 'pengumuman_hasil');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (Atribut Tambahan untuk View)
    |--------------------------------------------------------------------------
    */

    public function getTanggalInputAttribute(): ?string
    {
        return $this->tanggal ? $this->tanggal->format('Y-m-d\TH:i') : null;
    }

    public function getFormattedTanggalAttribute(): string
    {
        if ($this->tanggal) {
            return $this->tanggal->isFuture()
                ? 'Akan tayang pada ' . $this->tanggal->format('d M Y H:i')
                : 'Ditayangkan pada ' . $this->tanggal->format('d M Y H:i');
        }
        return 'Ditayangkan segera';
    }

    public function getTipeBadgeClassAttribute(): string
    {
        return [
            'info' => 'bg-info',
            'success' => 'bg-success',
            'warning' => 'bg-warning text-dark',
            'danger' => 'bg-danger',
        ][$this->tipe] ?? 'bg-secondary';
    }

    public function getTipeIconAttribute(): string
    {
        return [
            'info' => 'fas fa-info-circle',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'danger' => 'fas fa-times-circle',
        ][$this->tipe] ?? 'fas fa-bullhorn';
    }

    public function getTargetTextAttribute(): string
    {
        return [
            'semua' => 'Semua Pengguna',
            'calon_siswa' => 'Calon Siswa',
            'siswa_diterima' => 'Siswa Diterima',
            'siswa_ditolak' => 'Siswa Ditolak',
        ][$this->target_penerima] ?? 'Tidak Diketahui';
    }

    public function getTargetBadgeClassAttribute(): string
    {
        return [
            'semua' => 'bg-primary',
            'calon_siswa' => 'bg-info',
            'siswa_diterima' => 'bg-success',
            'siswa_ditolak' => 'bg-danger',
        ][$this->target_penerima] ?? 'bg-secondary';
    }

    public function getStatusTextAttribute(): string
    {
        if (!$this->aktif) {
            return 'tidak_aktif';
        }
        if ($this->tanggal && $this->tanggal->isFuture()) {
            return 'terjadwal';
        }
        return 'aktif';
    }
}
