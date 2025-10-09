<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfilSekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'visi',
        'misi',
        'sejarah',
        'jml_siswa',
        'jml_guru',
        'jml_staff',
        'logo_sekolah',
        'image',
        'alamat',
        'kontak1',
        'kontak2',
        'email',
        'prestasi_sekolah',
        'metode_pengajaran',
        'kurikulum',
        'budaya_sekolah',
        'fasilitas_sekolah'
    ];

    protected $casts = [
        'jml_siswa' => 'integer',
        'jml_guru' => 'integer',
        'jml_staff' => 'integer',
    ];

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_sekolah) {
            return Storage::url($this->logo_sekolah);
        }
        return null;
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('uploads/images/' . $this->image);
        }
        return null;
    }

    /**
     * Get formatted prestasi as array
     */
    public function getPrestasiArrayAttribute()
    {
        if (!$this->prestasi_sekolah) {
            return [];
        }

        // Remove HTML tags and split by lines
        $prestasi = strip_tags($this->prestasi_sekolah);
        return array_filter(explode("\n", $prestasi));
    }

    /**
     * Get formatted fasilitas as array
     */
    public function getFasilitasArrayAttribute()
    {
        if (!$this->fasilitas_sekolah) {
            return [];
        }

        // Remove HTML tags and split by lines
        $fasilitas = strip_tags($this->fasilitas_sekolah);
        return array_filter(explode("\n", $fasilitas));
    }
}
