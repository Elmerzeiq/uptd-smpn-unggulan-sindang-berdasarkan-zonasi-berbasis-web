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
        Schema::create('guru_dan_staff', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->unique()->nullable();
            $table->string('jabatan'); // Guru Mapel X, Wali Kelas IX A, Staff TU, Kepala Sekolah
            $table->enum('kategori', ['kepala_sekolah', 'guru', 'staff']);
            $table->text('sambutan')->nullable(); // Hanya untuk Kepala Sekolah
            $table->string('image')->nullable(); // Path foto
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_dan_staff');
    }
};
