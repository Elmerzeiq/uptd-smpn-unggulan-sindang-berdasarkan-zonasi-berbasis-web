<?php
// database/migrations/xxxx_create_kartu_pendaftarans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kartu_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_kartu')->unique();
            $table->timestamp('tanggal_pembuatan');
            $table->string('jalur_pendaftaran'); // zonasi, prestasi, afirmasi, dll
            $table->boolean('is_active')->default(true); // Standarisasi nama kolom
            $table->boolean('verified_by_admin')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('nomor_kartu');
            $table->index('jalur_pendaftaran');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kartu_pendaftarans');
    }
};
