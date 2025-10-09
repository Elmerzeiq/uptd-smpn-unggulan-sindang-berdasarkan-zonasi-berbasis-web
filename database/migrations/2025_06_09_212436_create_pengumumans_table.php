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
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('judul');
            $table->longText('isi');

            // Type & Targeting
            $table->enum('tipe', [
                'info',
                'warning',
                'danger',
                'success',
                'pengumuman_hasil'
            ])->default('info');

            $table->enum('target_penerima', [
                'semua',
                'calon_siswa',
                'siswa_diterima',
                'siswa_ditolak'
            ])->default('semua');

            // Publishing & Status
            $table->timestamp('tanggal')->nullable();
            $table->boolean('aktif')->default(true);
            $table->integer('priority')->default(5); // 1 = highest, 10 = lowest

            // Statistics
            $table->unsignedBigInteger('views_count')->default(0);

            // Relations
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('Admin yang membuat pengumuman');

            // Timestamps & Soft Deletes
            $table->timestamps();
            $table->softDeletes(); // Ini akan membuat kolom deleted_at

            // Indexes for better performance
            $table->index(['tipe', 'aktif'], 'idx_tipe_aktif');
            $table->index(['target_penerima', 'aktif'], 'idx_target_aktif');
            $table->index(['tanggal', 'aktif'], 'idx_tanggal_aktif');
            $table->index(['priority', 'created_at'], 'idx_priority_created');
            $table->index(['aktif', 'tanggal', 'created_at'], 'idx_visibility');

            // Index untuk soft delete
            $table->index(['deleted_at'], 'idx_deleted_at');

            // Full text search index for title and content
            $table->fullText(['judul', 'isi'], 'idx_fulltext_search');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumans');
    }
};
