<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlurPendaftaran extends Model
{
    protected $table = 'alur_pendaftaran';
    protected $fillable = ['urutan', 'nama', 'keterangan'];
}
