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
        Schema::create('kepala_sekolahs', function (Blueprint $table) {
            $table->id();

            // Data Pribadi
            $table->string('nip', 18)->unique();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_telepon', 15);
            $table->string('no_whatsapp', 15)->nullable();

            // Data Pendidikan
            $table->string('pendidikan_terakhir', 50);
            $table->string('jurusan', 100);
            $table->string('universitas', 100);
            $table->year('tahun_lulus');

            // Data Kepegawaian
            $table->date('tanggal_mulai_tugas');
            $table->string('sk_pengangkatan', 100)->nullable();

            // System Fields
            $table->string('role')->default('kepala_sekolah');
            $table->boolean('status_aktif')->default(true);
            $table->string('foto_profil')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index(['email', 'role']);
            $table->index(['nip']);
            $table->index(['status_aktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepala_sekolahs');
    }
};
