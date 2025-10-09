<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
    public function run(): void
    {
        // User::factory(10)->create(); // Hapus atau komentari factory default jika ada
        $this->call([
            AdminUserSeeder::class,
            DaftarUlangSeeder::class,
            KomponenBiayaSeeder::class,
            SiswaSeeder::class,
            KartuPendaftaranSeeder::class,
        ]);


    }
}
