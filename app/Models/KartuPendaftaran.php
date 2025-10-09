<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuPendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_kartu',
        'tanggal_pembuatan',
        'jalur_pendaftaran',
        'is_active',
        'verified_by_admin',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'tanggal_pembuatan' => 'datetime',
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
        'verified_by_admin' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified_by_admin', true);
    }

    public function scopeByJalur($query, $jalur)
    {
        return $query->where('jalur_pendaftaran', $jalur);
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        if ($this->verified_by_admin) {
            return 'Terverifikasi';
        }
        return 'Menunggu Verifikasi';
    }

    public function getFormattedTanggalPembuatanAttribute()
    {
        return $this->tanggal_pembuatan->format('d/m/Y H:i');
    }


// Tambahkan ke app/Models/User.php
public function kartuPendaftaran()
{
    return $this->hasOne(KartuPendaftaran::class);
}

public function biodata()
{
    return $this->hasOne(BiodataSiswa::class); // Sesuaikan dengan nama model biodata Anda
}

public function orangTua()
{
    return $this->hasOne(OrangTua::class); // Sesuaikan dengan nama model orang tua Anda
}

}
