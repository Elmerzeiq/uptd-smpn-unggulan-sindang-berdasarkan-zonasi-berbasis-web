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
        Schema::create('profil_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->default('Cerulean School');
            $table->text('visi')->default('');
            $table->text('misi')->default('');
            $table->text('sejarah')->default('');
            $table->integer('jml_siswa')->default(0);
            $table->integer('jml_guru')->default(0);
            $table->integer('jml_staff')->default(0);
            $table->string('logo_sekolah')->nullable();
            $table->string('image')->nullable();
            $table->text('alamat')->default('');
            $table->string('kontak1')->default('');
            $table->string('kontak2')->nullable();
            $table->string('email')->default('');
            $table->text('prestasi_sekolah')->nullable();
            $table->text('metode_pengajaran')->nullable();
            $table->text('kurikulum')->nullable();
            $table->text('budaya_sekolah')->nullable();
            $table->text('fasilitas_sekolah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_sekolahs');
    }
};
