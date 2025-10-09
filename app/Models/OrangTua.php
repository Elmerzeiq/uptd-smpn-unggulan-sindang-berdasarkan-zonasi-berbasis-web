<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;
    protected $table = 'orang_tuas';
    protected $guarded = ['id'];
    protected $casts = ['tgl_lahir_ayah' => 'date', 'tgl_lahir_ibu' => 'date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
