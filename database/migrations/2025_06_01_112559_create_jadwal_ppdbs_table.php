<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_ppdb', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran'); // e.g., "2024/2025"
            $table->dateTime('pembukaan_pendaftaran');
            $table->dateTime('penutupan_pendaftaran');
            $table->dateTime('pengumuman_hasil');
            $table->dateTime('mulai_daftar_ulang');
            $table->dateTime('selesai_daftar_ulang');
            $table->integer('kuota_total_keseluruhan'); // Kuota total gabungan
            // Kuota per jalur bisa dihitung dari persentase di config saat proses atau disimpan juga
            // $table->integer('kuota_domisili');
            // $table->integer('kuota_prestasi');
            // $table->integer('kuota_afirmasi');
            // $table->integer('kuota_mutasi');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_ppdbs');
    }
};
