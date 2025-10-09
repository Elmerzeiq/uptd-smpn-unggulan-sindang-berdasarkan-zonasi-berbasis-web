<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update users table untuk field jarak dan zonasi
        Schema::table('users', function (Blueprint $table) {
            // Field jarak jika belum ada
            if (!Schema::hasColumn('users', 'jarak_ke_sekolah')) {
                $table->decimal('jarak_ke_sekolah', 8, 2)->nullable()->after('alamat_domisili_siswa');
            }

            // Field koordinat jika belum ada
            if (!Schema::hasColumn('users', 'koordinat_domisili_siswa')) {
                $table->string('koordinat_domisili_siswa')->nullable()->after('jarak_ke_sekolah');
            }
        });

        // Tambah index untuk users table jika belum ada
        try {
            if (!$this->indexExists('users', 'users_jarak_ke_sekolah_index')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->index(['jarak_ke_sekolah'], 'users_jarak_ke_sekolah_index');
                });
            }
        } catch (\Exception $e) {
            // Index mungkin sudah ada, skip
        }

        try {
            if (!$this->indexExists('users', 'users_jalur_status_index')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->index(['jalur_pendaftaran', 'status_pendaftaran'], 'users_jalur_status_index');
                });
            }
        } catch (\Exception $e) {
            // Index mungkin sudah ada, skip
        }

        // Update biodata_siswas table untuk field prestasi
        Schema::table('biodata_siswas', function (Blueprint $table) {
            // Field skor prestasi jika belum ada
            if (!Schema::hasColumn('biodata_siswas', 'skor_prestasi')) {
                $table->integer('skor_prestasi')->default(0)->after('tgl_lahir');
            }

            // Field KETM jika belum ada
            if (!Schema::hasColumn('biodata_siswas', 'ketm')) {
                $table->boolean('ketm')->default(false)->after('skor_prestasi');
            }

            // Field disabilitas jika belum ada
            if (!Schema::hasColumn('biodata_siswas', 'disabilitas')) {
                $table->boolean('disabilitas')->default(false)->after('ketm');
            }
        });

        // Tambah index untuk biodata_siswas table jika belum ada
        try {
            if (!$this->indexExists('biodata_siswas', 'biodata_siswas_skor_prestasi_index')) {
                Schema::table('biodata_siswas', function (Blueprint $table) {
                    $table->index(['skor_prestasi'], 'biodata_siswas_skor_prestasi_index');
                });
            }
        } catch (\Exception $e) {
            // Index mungkin sudah ada, skip
        }

        try {
            if (!$this->indexExists('biodata_siswas', 'biodata_siswas_tgl_lahir_index')) {
                Schema::table('biodata_siswas', function (Blueprint $table) {
                    $table->index(['tgl_lahir'], 'biodata_siswas_tgl_lahir_index');
                });
            }
        } catch (\Exception $e) {
            // Index mungkin sudah ada, skip
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes jika ada
            try {
                $table->dropIndex('users_jarak_ke_sekolah_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada, skip
            }

            try {
                $table->dropIndex('users_jalur_status_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada, skip
            }

            // Uncomment jika ingin menghapus kolom (hati-hati!)
            // if (Schema::hasColumn('users', 'koordinat_domisili_siswa')) {
            //     $table->dropColumn('koordinat_domisili_siswa');
            // }
        });

        Schema::table('biodata_siswas', function (Blueprint $table) {
            // Drop indexes jika ada
            try {
                $table->dropIndex('biodata_siswas_skor_prestasi_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada, skip
            }

            try {
                $table->dropIndex('biodata_siswas_tgl_lahir_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada, skip
            }

            // Uncomment jika ingin menghapus kolom (hati-hati!)
            // if (Schema::hasColumn('biodata_siswas', 'skor_prestasi')) {
            //     $table->dropColumn('skor_prestasi');
            // }
            // if (Schema::hasColumn('biodata_siswas', 'ketm')) {
            //     $table->dropColumn('ketm');
            // }
            // if (Schema::hasColumn('biodata_siswas', 'disabilitas')) {
            //     $table->dropColumn('disabilitas');
            // }
        });
    }

    /**
     * Check if index exists using raw SQL
     */
    private function indexExists($table, $indexName)
    {
        try {
            $databaseName = DB::connection()->getDatabaseName();
            $result = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.statistics
                WHERE table_schema = ?
                AND table_name = ?
                AND index_name = ?
            ", [$databaseName, $table, $indexName]);

            return $result[0]->count > 0;
        } catch (\Exception $e) {
            // Jika error, anggap index tidak ada
            return false;
        }
    }
};
