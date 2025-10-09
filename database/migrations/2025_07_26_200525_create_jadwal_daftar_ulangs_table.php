<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_daftar_ulangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi', 100);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('kuota')->default(50);
            $table->integer('terisi')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            // Kolom 'jumlah_peserta' dihapus karena duplikat dengan 'terisi'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_daftar_ulangs');
    }
};
