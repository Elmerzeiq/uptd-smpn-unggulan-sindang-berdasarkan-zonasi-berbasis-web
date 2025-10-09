<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailBiayaSiswa extends Model
{
    use HasFactory;
    protected $table = 'detail_biaya_siswa';
    protected $fillable = ['daftar_ulang_id', 'komponen_biaya_id', 'biaya'];
    protected $casts = ['biaya' => 'decimal:2'];

    public function daftarUlang()
    {
        return $this->belongsTo(DaftarUlang::class, 'daftar_ulang_id');
    }
    public function komponenBiaya()
    {
        return $this->belongsTo(KomponenBiayaDaftarUlang::class, 'komponen_biaya_id');
    }
}
