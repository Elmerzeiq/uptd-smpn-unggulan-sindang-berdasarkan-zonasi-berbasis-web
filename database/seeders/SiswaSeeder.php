<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BiodataSiswa; // Pastikan model ini ada
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Siswa 1
        $siswa1 = User::updateOrCreate(
            ['email' => 'marklee@gmail.com'],
            [
                'nama_lengkap' => 'Mark Lee',
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'nisn' => '0045123456',
                'jalur_pendaftaran' => 'domisili', // Nilai 'domisili' ini sudah valid
                'status_pendaftaran' => 'lulus_seleksi',
            ]
        );

        // Buat biodata untuk Siswa 1
        BiodataSiswa::updateOrCreate(
            ['user_id' => $siswa1->id],
            [
                'asal_sekolah' => 'SMP Swasta Ceria',
                'tempat_lahir' => 'Vancouver',
                'tgl_lahir' => '1999-08-02',
                'jns_kelamin' => 'L'
            ]
        );

        // Siswa 2
        $siswa2 = User::updateOrCreate(
            ['email' => 'jaemin@gmail.com'],
            [
                'nama_lengkap' => 'Na Jaemin',
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'email_verified_at' => now(),
                'nisn' => '0056123789',
                // PERBAIKAN: Mengganti 'prestasi' menjadi nilai yang valid sesuai ENUM di migrasi
                'jalur_pendaftaran' => 'domisili',
                'status_pendaftaran' => 'lulus_seleksi',
            ]
        );

        // Buat biodata untuk Siswa 2
        BiodataSiswa::updateOrCreate(
            ['user_id' => $siswa2->id],
            [
                'asal_sekolah' => 'SMP Negeri Impian',
                'tempat_lahir' => 'Seoul',
                'tgl_lahir' => '2000-08-13',
                'jns_kelamin' => 'L'
            ]
        );
    }
}
