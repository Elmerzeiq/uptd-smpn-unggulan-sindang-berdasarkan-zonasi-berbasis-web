<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk slug
use App\Models\User; // Untuk relasi author

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas'; // Eksplisit jika perlu, Laravel akan otomatis menebak 'beritas'

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi', // Kutipan singkat
        'isi',       // Isi berita lengkap
        'image',     // Path ke gambar thumbnail
        'tanggal',   // Tanggal publikasi
        'status',    // 'draft' atau 'published'
        'user_id',   // ID admin yang membuat/mengupdate
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Boot a new Eloquent model instance.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = static::createUniqueSlug($berita->judul);
            }
        });

        static::updating(function ($berita) {
            // Jika judul diubah, buat ulang slug
            if ($berita->isDirty('judul')) {
                $newSlug = Str::slug($berita->judul);
                // Hanya update slug jika slug baru berbeda dari slug lama atau jika slug kosong
                if ($newSlug !== $berita->getOriginal('slug') || empty($berita->slug)) {
                    $berita->slug = static::createUniqueSlug($berita->judul, $berita->id);
                }
            }
        });
    }

    /**
     * Create a unique slug for the model.
     */
    private static function createUniqueSlug(string $title, int $id = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Loop hingga slug unik ditemukan
        // Jika ada ID, pastikan slug unik KECUALI untuk record itu sendiri
        while (static::whereSlug($slug)->when($id, fn($query) => $query->where('id', '!=', $id))->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        return $slug;
    }

    /**
     * Get the user (author) that created the berita.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
