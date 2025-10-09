<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('walis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_wali')->nullable();
            $table->string('nik_wali')->nullable()->unique(); // Tambahan
            $table->string('tempat_lahir_wali')->nullable();
            $table->date('tgl_lahir_wali')->nullable();
            $table->enum('pendidikan_wali', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'])->nullable(); // Tambahan
            $table->string('pekerjaan_wali')->nullable(); // Tambahan
            $table->enum('penghasilan_wali', ['<2juta', '2-5juta', '5-10juta', '>10juta'])->nullable(); // Tambahan
            $table->string('no_hp_wali')->nullable();
            $table->string('hubungan_wali_dgn_siswa')->nullable(); // Sebelumnya 'hubungan_wali_dgn_calon_peserta'
            $table->text('alamat_wali')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('walis');
    }
};
