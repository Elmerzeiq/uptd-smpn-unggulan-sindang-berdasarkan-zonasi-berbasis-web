<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_biaya_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daftar_ulang_id')->constrained('daftar_ulangs')->cascadeOnDelete();
            $table->foreignId('komponen_biaya_id')->constrained('komponen_biaya_daftar_ulang')->cascadeOnDelete();
            $table->decimal('biaya', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('detail_biaya_siswa');
        Schema::enableForeignKeyConstraints();
    }
};
