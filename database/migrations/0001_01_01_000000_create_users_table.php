<?php

use App\Services\QRCodeService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nisn')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'siswa','kepala_sekolah'])->default('siswa');
            // $table->string('foto_profil')->nullable();

            // Kolom PPDB
            $table->enum('jalur_pendaftaran', [
                'domisili',
                'prestasi_akademik_lomba',
                'prestasi_non_akademik_lomba',
                'prestasi_rapor',
                'afirmasi_ketm',
                'afirmasi_disabilitas',
                'mutasi_pindah_tugas',
                'mutasi_anak_guru'
            ])->nullable();

            $table->string('kecamatan_domisili')->nullable();
            $table->string('desa_kelurahan_domisili')->nullable();
            $table->string('desa_domisili')->nullable()->comment('Alias untuk desa/kelurahan domisili');
            $table->string('no_pendaftaran')->unique()->nullable();

            $table->enum('status_pendaftaran', [
                'belum_diverifikasi',
                'menunggu_kelengkapan_data',
                'menunggu_verifikasi_berkas',
                'berkas_tidak_lengkap',
                'berkas_diverifikasi',
                'lulus_seleksi',
                'tidak_lulus_seleksi',
                'mengundurkan_diri',
                'daftar_ulang_selesai'
            ])->default('belum_diverifikasi')->nullable();

            $table->text('catatan_verifikasi')->nullable();

            // Koordinat dan jarak - KONSISTEN SEMUA MENGGUNAKAN FLOAT
            $table->string('koordinat_domisili_siswa', 100)->nullable()->comment('Koordinat rumah dalam format lat,lng');
            $table->float('jarak_ke_sekolah')->nullable()->comment('Jarak ke sekolah dalam KM');
            $table->float('jarak_ke_kecamatan')->nullable()->comment('Jarak ke pusat kecamatan dalam KM');
            $table->float('jarak_ke_desa')->nullable()->comment('Jarak ke pusat desa dalam KM');
            $table->integer('peringkat_zonasi')->nullable()->comment('Peringkat dalam zonasi');

            $table->enum('status_daftar_ulang', ['belum', 'proses_verifikasi', 'selesai', 'batal'])
                ->nullable()->comment('Status proses daftar ulang siswa');
            $table->timestamp('tanggal_daftar_ulang_selesai')->nullable()->comment('Waktu selesai daftar ulang');
            $table->text('catatan_daftar_ulang')->nullable()->comment('Catatan dari admin terkait daftar ulang');

            // Index untuk performa query
            $table->index(['jalur_pendaftaran'], 'idx_users_jalur_pendaftaran');
            $table->index(['kecamatan_domisili'], 'idx_users_kecamatan_domisili');
            $table->index(['status_pendaftaran'], 'idx_users_status_pendaftaran');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
