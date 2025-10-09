<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KartuPendaftaran;
use App\Models\User;

class KartuPendaftaranSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Pastikan ada user
        if ($user) {
            KartuPendaftaran::create([
                'user_id' => $user->id,
                'nomor_kartu' => 'KARTU-TEST123',
                'tanggal_pembuatan' => now(), // Pastikan ini sesuai dengan tipe kolom
                'jalur_pendaftaran' => 'domisili',
                'is_active' => true,
                'verified_by_admin' => false,
            ]);
        }
    }
}
