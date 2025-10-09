<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomponenBiayaDaftarUlang extends Model
{
    use HasFactory;
    protected $table = 'komponen_biaya_daftar_ulang';
    protected $fillable = ['nama_komponen', 'biaya', 'is_wajib', 'is_active', 'keterangan'];
    protected $casts = ['biaya' => 'decimal:2', 'is_wajib' => 'boolean', 'is_active' => 'boolean'];

    public function detailBiayaSiswa()
    {
        return $this->hasMany(DetailBiayaSiswa::class, 'komponen_biaya_id');
    }
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeWajib($query)
    {
        return $query->where('is_wajib', true);
    }
}
