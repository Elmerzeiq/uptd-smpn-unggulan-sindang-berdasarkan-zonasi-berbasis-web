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
        Schema::create('komponen_biaya_daftar_ulang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->decimal('biaya', 10, 2); // Diubah dari 'nominal' menjadi 'biaya'
            $table->boolean('is_wajib')->default(true); // Ditambahkan
            $table->boolean('is_active')->default(true); // Ditambahkan
            $table->string('keterangan', 500)->nullable(); // Ditambahkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_biaya_daftar_ulang');
    }
};
