<?php

use Illuminate\Support\Facades\DB;
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
        if (!Schema::hasTable('berkas_pendaftars')) {
            Schema::create('berkas_pendaftars', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

                // Berkas umum
                $table->text('file_ijazah_skl')->nullable();
                $table->text('file_nisn_screenshot')->nullable();
                $table->text('file_kk')->nullable();
                $table->text('file_akta_kia')->nullable();
                $table->text('file_ktp_ortu')->nullable();
                $table->text('file_pas_foto')->nullable();
                $table->text('file_surat_pernyataan_ortu')->nullable();
                $table->text('file_skkb_sd_desa')->nullable();

                // Berkas agama
                $table->text('file_ijazah_mda_pernyataan')->nullable();
                $table->text('file_suket_baca_quran_mda')->nullable();

                // Berkas domisili
                $table->text('file_suket_domisili')->nullable();

                // Berkas prestasi
                $table->json('file_sertifikat_prestasi_lomba')->nullable();
                $table->text('file_surat_pertanggungjawaban_kepsek_lomba')->nullable();
                $table->text('file_rapor_5_semester')->nullable();
                $table->text('file_suket_nilai_rapor_peringkat_kepsek')->nullable();

                // Berkas afirmasi
                $table->text('file_kartu_bantuan_sosial')->nullable();
                $table->text('file_sktm_dtks_dinsos')->nullable();
                $table->text('file_suket_disabilitas_dokter_psikolog')->nullable();

                // Berkas mutasi
                $table->text('file_surat_penugasan_ortu_instansi')->nullable();
                $table->text('file_sk_penugasan_guru_tendik')->nullable();
                $table->text('file_surat_rekomendasi_dirjen_luarnegeri')->nullable();

                // Metadata verifikasi
                $table->timestamp('verified_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('users');
                $table->text('verification_notes')->nullable();

                $table->json('checklist_status')->nullable();

                $table->timestamps();

                // Index tambahan
                $table->index(['verified_at']);
            });
        } else {
            Schema::table('berkas_pendaftars', function (Blueprint $table) {
                if (!Schema::hasColumn('berkas_pendaftars', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable()->after('file_surat_rekomendasi_dirjen_luarnegeri');
                }

                if (!Schema::hasColumn('berkas_pendaftars', 'verified_by')) {
                    $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
                }

                if (!Schema::hasColumn('berkas_pendaftars', 'verification_notes')) {
                    $table->text('verification_notes')->nullable()->after('verified_by');
                }

                $indexes = collect(DB::select("SHOW INDEX FROM berkas_pendaftars"))->pluck('Key_name');

                if (!$indexes->contains('berkas_pendaftars_verified_at_index')) {
                    $table->index(['verified_at']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas_pendaftars', function (Blueprint $table) {
            // Jangan hapus index yang dibuat otomatis oleh foreign key (user_id)

            // Hapus foreign key dan kolom tambahan
            if (Schema::hasColumn('berkas_pendaftars', 'verified_by')) {
                $table->dropForeign(['verified_by']);
            }

            if (Schema::hasColumn('berkas_pendaftars', 'verified_at')) {
                $table->dropIndex(['verified_at']);
                $table->dropColumn('verified_at');
            }

            if (Schema::hasColumn('berkas_pendaftars', 'verification_notes')) {
                $table->dropColumn('verification_notes');
            }

            if (Schema::hasColumn('berkas_pendaftars', 'verified_by')) {
                $table->dropColumn('verified_by');
            }
        });

        // Jika kamu ingin drop tabel seluruhnya:
        // Schema::dropIfExists('berkas_pendaftars');
    }
};
