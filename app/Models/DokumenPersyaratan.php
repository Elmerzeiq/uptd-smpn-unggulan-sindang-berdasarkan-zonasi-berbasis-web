<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPersyaratan extends Model
{
    protected $table = 'dokumen_persyaratan';
    protected $fillable = ['kategori', 'keterangan'];
}
