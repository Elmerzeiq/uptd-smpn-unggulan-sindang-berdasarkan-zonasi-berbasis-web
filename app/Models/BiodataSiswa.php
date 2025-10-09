<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataSiswa extends Model
{
    use HasFactory;
    protected $table = 'biodata_siswas';
    protected $guarded = ['id']; // Atau definisikan $fillable dengan semua kolom
    protected $casts = ['tgl_lahir' => 'date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
