<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daftar_ulangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('jadwal_id')->nullable();
            $table->string('nomor_daftar_ulang')->unique();
            $table->dateTime('tanggal_daftar_ulang')->nullable();
            $table->string('kartu_lolos_seleksi')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->decimal('total_biaya', 10, 2)->default(0);
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'sudah_lunas', 'ditolak'])->default('belum_bayar');
            $table->enum('status_daftar_ulang', ['belum_daftar', 'menunggu_verifikasi_berkas', 'berkas_diverifikasi', 'menunggu_verifikasi_pembayaran', 'daftar_ulang_selesai', 'ditolak'])->default('belum_daftar');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamp('tanggal_verifikasi_berkas')->nullable();
            $table->timestamp('tanggal_verifikasi_pembayaran')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('hadir_daftar_ulang')->default(false);
            $table->timestamp('waktu_kehadiran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('daftar_ulangs');
        Schema::enableForeignKeyConstraints();
    }
};
