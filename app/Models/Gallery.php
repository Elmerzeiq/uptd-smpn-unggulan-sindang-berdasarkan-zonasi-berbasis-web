<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $table = 'galleries'; // Pastikan nama tabel sesuai dengan yang ada di database
    protected $fillable = ['image', 'kategori', 'judul', 'deskripsi'];
}
