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
        Schema::create('dokumen_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', [
                'zonasi',
                'afirmasi-ketm',
                'afirmasi-disabilitas',
                'perpindahan tugas orang tua',
                'putra/putri guru/tenaga kependidikan',
                'prestasi-akademik',
                'prestasi-non-akademik',
                'prestasi-akademik nilai raport'
            ]);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_persyaratan');
    }
};
