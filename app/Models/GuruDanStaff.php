<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruDanStaff extends Model
{
    use HasFactory;

    protected $table = 'guru_dan_staff';

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'kategori',
        'sambutan',
        'image',
        'is_active'
    ];

    protected $casts = [
        'kategori' => 'string',
        'is_active' => 'boolean',
    ];

    // Scopes untuk kueri mudah
    public function scopeKepalaSekolah($query)
    {
        return $query->where('kategori', 'kepala_sekolah');
    }

    public function scopeGuru($query)
    {
        return $query->where('kategori', 'guru');
    }

    public function scopeStaff($query)
    {
        return $query->where('kategori', 'staff');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('uploads/images/' . $this->image);
        }

        return match ($this->kategori) {
            'kepala_sekolah' => asset('img/principal/default-principal.jpg'),
            'guru' => asset('img/teacher/default-teacher.jpg'),
            'staff' => asset('img/staff/default-staff.jpg'),
            default => asset('img/default-person.jpg'),
        };
    }

    public function getIsKepalaSekolahAttribute()
    {
        return $this->kategori === 'kepala_sekolah';
    }

    public function getIsGuruAttribute()
    {
        return $this->kategori === 'guru';
    }

    public function getIsStaffAttribute()
    {
        return $this->kategori === 'staff';
    }

    // Methods
    public function hasSambutan()
    {
        return !empty($this->sambutan) && $this->is_kepala_sekolah;
    }

    public static function getKepalaSekolah()
    {
        return static::kepalaSekolah()->active()->first();
    }

    public static function getAllGuru()
    {
        return static::guru()->active()->orderBy('nama')->get();
    }

    public static function getAllStaff()
    {
        return static::staff()->active()->orderBy('nama')->get();
    }
}
