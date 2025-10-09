<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPpdb extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ppdb';

    protected $fillable = [
        'tahun_ajaran',
        'pembukaan_pendaftaran',
        'penutupan_pendaftaran',
        'pengumuman_hasil',
        'mulai_daftar_ulang',
        'selesai_daftar_ulang',
        'kuota_total_keseluruhan',
        'is_active',
    ];

    protected $casts = [
        'pembukaan_pendaftaran' => 'datetime',
        'penutupan_pendaftaran' => 'datetime',
        'pengumuman_hasil' => 'datetime',
        'mulai_daftar_ulang' => 'datetime',
        'selesai_daftar_ulang' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk mendapatkan jadwal yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)->latest('created_at');
    }

    /**
     * Mendapatkan jadwal aktif pertama.
     */
    public static function aktif()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Memeriksa apakah periode pendaftaran aktif.
     */
    public function isPendaftaranOpen(): bool
    {
        $now = Carbon::now();
        return $this->is_active
            && $this->pembukaan_pendaftaran
            && $this->penutupan_pendaftaran
            && $now->between($this->pembukaan_pendaftaran, $this->penutupan_pendaftaran);
    }

    /**
     * Memeriksa apakah periode pengumuman aktif.
     */
    public function isPengumumanOpen(): bool
    {
        $now = Carbon::now();
        return $this->is_active && $this->pengumuman_hasil && $now->gte($this->pengumuman_hasil);
    }

    /**
     * Memeriksa apakah periode daftar ulang aktif.
     */
    public function isDaftarUlangOpen(): bool
    {
        $now = Carbon::now();
        return $this->is_active
            && $this->mulai_daftar_ulang
            && $this->selesai_daftar_ulang
            && $now->between($this->mulai_daftar_ulang, $this->selesai_daftar_ulang);
    }

    /**
     * Memeriksa apakah periode pengisian data aktif (sama dengan pendaftaran).
     */
    public function isPengisianDataOpen(): bool
    {
        return $this->isPendaftaranOpen();
    }
    
}
