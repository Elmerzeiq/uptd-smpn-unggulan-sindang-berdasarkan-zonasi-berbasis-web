<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DaftarUlangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('daftar_ulangs')->insert([
            [
                'user_id' => 1,
                'jadwal_id' => 1,
                'nomor_daftar_ulang' => 'DU' . date('Y') . '0001',
                'tanggal_daftar_ulang' => Carbon::now()->subDays(2),
                'kartu_lolos_seleksi' => 'daftar-ulang/1/kartu_lolos.pdf',
                'bukti_pembayaran' => 'daftar-ulang/1/bukti_bayar.jpg',
                'total_biaya' => 500000,
                'status_pembayaran' => 'sudah_lunas',
                'status_daftar_ulang' => 'daftar_ulang_selesai',
                'catatan_verifikasi' => 'Semua berkas lengkap.',
                'tanggal_pembayaran' => Carbon::now()->subDays(2),
                'tanggal_verifikasi_berkas' => Carbon::now()->subDay(),
                'tanggal_verifikasi_pembayaran' => Carbon::now()->subDay(),
                'verified_by' => 2, // misalnya admin id 2
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'jadwal_id' => 1,
                'nomor_daftar_ulang' => 'DU' . date('Y') . '0002',
                'tanggal_daftar_ulang' => Carbon::now()->subDay(),
                'kartu_lolos_seleksi' => 'daftar-ulang/2/kartu_lolos.pdf',
                'bukti_pembayaran' => null,
                'total_biaya' => 500000,
                'status_pembayaran' => 'belum_bayar',
                'status_daftar_ulang' => 'menunggu_verifikasi_berkas',
                'catatan_verifikasi' => null,
                'tanggal_pembayaran' => null,
                'tanggal_verifikasi_berkas' => null,
                'tanggal_verifikasi_pembayaran' => null,
                'verified_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
