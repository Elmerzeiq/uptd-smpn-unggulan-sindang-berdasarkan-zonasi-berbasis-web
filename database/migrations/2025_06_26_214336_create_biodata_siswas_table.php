<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biodata_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->index();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('jns_kelamin', ['L', 'P'])->nullable();
            $table->unsignedInteger('tinggi_badan')->nullable()->comment('Dalam cm, minimal 0');
            $table->unsignedInteger('berat_badan')->nullable()->comment('Dalam kg, minimal 0');
            $table->unsignedInteger('lingkar_kepala')->nullable()->comment('Dalam cm, minimal 0');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'])->nullable();
            $table->unsignedInteger('anak_ke')->nullable()->comment('Minimal 0');
            $table->unsignedInteger('jml_saudara_kandung')->nullable()->comment('Minimal 0');
            $table->string('asal_sekolah')->nullable();
            $table->string('npsn_asal_sekolah')->nullable();
            $table->string('alamat_asal_sekolah')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('desa_kelurahan_domisili')->nullable();
            $table->string('rt')->nullable(); // Ubah ke non-nullable jika wajib
            $table->string('rw')->nullable(); // Ubah ke non-nullable jika wajib
            $table->string('kode_pos')->nullable(); // Ubah ke non-nullable jika wajib
            $table->string('koordinat_rumah')->nullable(); // Ubah ke non-nullable jika wajib
            // $table->string('foto_siswa')->nullable();
            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('biodata_siswas');
    }
};
