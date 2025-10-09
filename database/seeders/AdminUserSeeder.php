<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'nama_lengkap' => 'Admin',
            'email' => 'admin2403@gmail.com', // Ganti dengan email admin
            'password' => Hash::make('sindangunggulanppdb'), // GANTI DENGAN PASSWORD YANG KUAT
            'role' => 'admin',
            'email_verified_at' => now(),
            'nisn' => null,
        ]);

        User::updateOrCreate(
            ['email' => 'admin2@gmail.com'],
            [
                'nama_lengkap' => 'Admin Kedua',
                'password' => Hash::make('sindangunggulanppdb2'), // GANTI DENGAN PASSWORD YANG KUAT
                'role' => 'admin',
                'email_verified_at' => now(),
                'nisn' => null,
            ]
        );


        User::updateOrCreate(
            ['email' => 'marklee@gmail.com'],
            [
                'nama_lengkap' => 'Mark Lee',
                'password' => Hash::make('12345678'), // GANTI DENGAN PASSWORD YANG KUAT
                'role' => 'siswa',
                'email_verified_at' => now(),
                'nisn' => '0045123456',
                'jalur_pendaftaran' => 'domisili',
                'kecamatan_domisili' => 'Kecamatan Sindang Ujung',
                'no_pendaftaran' => 'Z20240001',
                'status_pendaftaran' => 'belum_diverifikasi',
                'catatan_verifikasi' => null,
                'koordinat_domisili_siswa' => '106.123456, -6.123456',

            ]
        );
    }
}
