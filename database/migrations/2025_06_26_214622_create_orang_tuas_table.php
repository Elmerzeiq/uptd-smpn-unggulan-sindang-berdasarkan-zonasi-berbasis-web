<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
        public function up(): void {
        Schema::create('orang_tuas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // Data Ayah
        $table->string('nama_ayah')->nullable();
        $table->string('nik_ayah')->nullable()->unique(); // Tambahan NIK
        $table->string('tempat_lahir_ayah')->nullable();
        $table->date('tgl_lahir_ayah')->nullable();
        $table->enum('pendidikan_ayah', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'])->nullable();// Tambahan
        $table->string('pekerjaan_ayah')->nullable();
        $table->enum('penghasilan_ayah', ['<2juta', '2-5juta', '5-10juta', '>10juta'])->nullable();
        $table->string('no_hp_ayah')->nullable();
        // Data Ibu
        $table->string('nama_ibu')->nullable();
        $table->string('nik_ibu')->nullable()->unique(); // Tambahan NIK
        $table->string('tempat_lahir_ibu')->nullable();
        $table->date('tgl_lahir_ibu')->nullable();
        $table->enum('pendidikan_ibu', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'])->nullable();
        $table->string('pekerjaan_ibu')->nullable();
        $table->enum('penghasilan_ibu', ['<2juta', '2-5juta', '5-10juta', '>10juta'])->nullable();
        $table->string('no_hp_ibu')->nullable();
        $table->timestamps();
        });
        }

        public function down(): void { Schema::dropIfExists('orang_tuas'); }
        };
