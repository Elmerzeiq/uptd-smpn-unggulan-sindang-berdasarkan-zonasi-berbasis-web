<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;
    protected $table = 'ekskuls'; // Pastikan nama tabel sesuai dengan yang ada di database
    protected $fillable = ['judul', 'image', 'deskripsi', 'isi', 'tanggal', 'kategori'];
    protected $casts = ['tanggal' => 'date'];
}
