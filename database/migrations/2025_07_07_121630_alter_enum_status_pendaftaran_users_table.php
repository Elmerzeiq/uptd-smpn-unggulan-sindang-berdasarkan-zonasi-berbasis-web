<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `users`
            MODIFY `status_pendaftaran` ENUM(
                'akun_terdaftar',
                'belum_diverifikasi',
                'menunggu_kelengkapan_data',
                'menunggu_verifikasi_berkas',
                'berkas_tidak_lengkap',
                'berkas_diverifikasi',
                'lulus_seleksi',
                'tidak_lulus_seleksi',
                'mengundurkan_diri',
                'daftar_ulang_selesai'
            ) DEFAULT 'belum_diverifikasi'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE `users`
            MODIFY `status_pendaftaran` ENUM(
                'belum_diverifikasi',
                'menunggu_kelengkapan_data',
                'menunggu_verifikasi_berkas',
                'berkas_tidak_lengkap',
                'berkas_diverifikasi',
                'lulus_seleksi',
                'tidak_lulus_seleksi',
                'mengundurkan_diri',
                'daftar_ulang_selesai'
            ) DEFAULT 'belum_diverifikasi'
        ");
    }
};
