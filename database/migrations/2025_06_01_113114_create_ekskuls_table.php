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
        Schema::create('ekskuls', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Nama Ekskul
            $table->string('image')->nullable(); // Logo/Foto Ekskul
            $table->text('deskripsi');
            $table->text('isi')->nullable(); // Detail Kegiatan
            $table->date('tanggal')->nullable(); // Tanggal posting info, bukan jadwal ekskul
            $table->string('kategori')->nullable(); // Kategori Ekskul jika ada (Olahraga, Seni, dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekskuls');
    }
};
