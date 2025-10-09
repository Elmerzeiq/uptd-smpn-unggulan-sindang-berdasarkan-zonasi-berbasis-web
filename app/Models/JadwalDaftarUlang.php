<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDaftarUlang extends Model
{
    use HasFactory;
    protected $table = 'jadwal_daftar_ulangs';
    protected $fillable = ['nama_sesi', 'tanggal', 'waktu_mulai', 'waktu_selesai', 'kuota', 'terisi', 'is_active', 'keterangan'];
    protected $casts = ['tanggal' => 'date', 'waktu_mulai' => 'datetime:H:i', 'waktu_selesai' => 'datetime:H:i', 'is_active' => 'boolean'];

    public function daftarUlangSiswa()
    {
        return $this->hasMany(DaftarUlang::class, 'jadwal_id');
    }
    public function isFull()
    {
        return $this->terisi >= $this->kuota;
    }
    public function getSlotTersisaAttribute()
    {
        return $this->kuota - $this->terisi;
    }
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->whereColumn('terisi', '<', 'kuota');
    }
}
