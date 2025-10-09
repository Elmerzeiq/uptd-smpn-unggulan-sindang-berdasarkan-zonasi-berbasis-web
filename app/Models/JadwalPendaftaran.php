<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPendaftaran extends Model
{
    protected $table = 'jadwal_pendaftaran';
    protected $fillable = ['tahap', 'kegiatan', 'tanggal_mulai', 'tanggal_selesai'];
}
