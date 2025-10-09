<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KepalaSekolahSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama_lengkap' => 'Dr. Ahmad Susanto, S.Pd., M.Pd',
            'email' => 'kepala.sekolah@smpunggulansindang.sch.id',
            'password' => Hash::make('kepala123'),
            'role' => 'kepala_sekolah',
            'email_verified_at' => now(),
        ]);
    }
}
