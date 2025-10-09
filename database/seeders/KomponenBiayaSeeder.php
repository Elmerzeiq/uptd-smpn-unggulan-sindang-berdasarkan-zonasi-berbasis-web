<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KomponenBiayaDaftarUlang;

class KomponenBiayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $komponenBiaya = [
            [
                'nama_komponen' => 'Biaya Daftar Ulang',
                'biaya' => env('PPDB_BIAYA_DAFTAR_ULANG', 500000),
                'is_wajib' => true,
                'is_active' => true,
                'keterangan' => 'Biaya wajib untuk proses daftar ulang siswa baru'
            ],
            [
                'nama_komponen' => 'Biaya Seragam',
                'biaya' => env('PPDB_BIAYA_SERAGAM', 300000),
                'is_wajib' => false,
                'is_active' => true,
                'keterangan' => 'Paket seragam sekolah (opsional, bisa dibeli terpisah)'
            ],
            [
                'nama_komponen' => 'Biaya Buku',
                'biaya' => env('PPDB_BIAYA_BUKU', 200000),
                'is_wajib' => false,
                'is_active' => true,
                'keterangan' => 'Paket buku pelajaran (opsional, bisa dibeli terpisah)'
            ],
            [
                'nama_komponen' => 'Biaya Kegiatan',
                'biaya' => 150000,
                'is_wajib' => true,
                'is_active' => true,
                'keterangan' => 'Biaya kegiatan ekstrakurikuler dan pengembangan karakter'
            ],
            [
                'nama_komponen' => 'Biaya OSIS',
                'biaya' => 50000,
                'is_wajib' => true,
                'is_active' => true,
                'keterangan' => 'Biaya keanggotaan OSIS dan kegiatan siswa'
            ]
        ];

        foreach ($komponenBiaya as $komponen) {
            KomponenBiayaDaftarUlang::create($komponen);
        }
    }
}
