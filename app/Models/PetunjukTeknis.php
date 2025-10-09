<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetunjukTeknis extends Model
{
    protected $table = 'petunjuk_teknis';
    protected $fillable = ['judul', 'isi', 'path_pdf'];
}
