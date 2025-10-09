<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    use HasFactory;
    protected $table = 'walis';
    protected $guarded = ['id'];
    protected $casts = ['tgl_lahir_wali' => 'date'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
