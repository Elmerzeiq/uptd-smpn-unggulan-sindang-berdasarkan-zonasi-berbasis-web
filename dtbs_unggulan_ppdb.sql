-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jul 2025 pada 21.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dtbs_unggulan_ppdb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `beritas`
--

CREATE TABLE `beritas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `isi` longtext NOT NULL,
  `tanggal` date NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `berkas_pendaftars`
--

CREATE TABLE `berkas_pendaftars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_ijazah_skl` varchar(255) DEFAULT NULL COMMENT 'Scan Ijazah SD/MI (legalisir) / Surat Keterangan Lulus',
  `file_nisn_screenshot` varchar(255) DEFAULT NULL COMMENT 'Scan/File NISN (misal dari web vervalpd atau kartu NISN)',
  `file_kk` varchar(255) DEFAULT NULL COMMENT 'Scan Kartu Keluarga',
  `file_akta_kia` varchar(255) DEFAULT NULL COMMENT 'Scan Akta Kelahiran / KIA / Surat Keterangan Lahir',
  `file_ktp_ortu` varchar(255) DEFAULT NULL COMMENT 'Scan KTP Orang Tua (Ayah/Ibu)',
  `file_pas_foto` varchar(255) DEFAULT NULL COMMENT 'Pas Foto (jika di-submit sebagai berkas)',
  `file_surat_pernyataan_ortu` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Pernyataan Keaslian Dokumen dari Ortu/Wali',
  `file_skkb_sd_desa` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Keterangan Kelakuan Baik dari SD/MI atau Pemerintah Desa',
  `file_ijazah_mda_pernyataan` varchar(255) DEFAULT NULL COMMENT 'Scan Ijazah MDA / Surat Pernyataan sedang/pernah mengikuti MDA',
  `file_suket_baca_quran_mda` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Keterangan Mampu Membaca Al-Quran dari Kepala MDA',
  `file_suket_domisili` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Keterangan Domisili (jika KK < 1 tahun atau kasus khusus lainnya)',
  `file_sertifikat_prestasi_lomba` text DEFAULT NULL COMMENT 'JSON Array path Scan Sertifikat Kejuaraan/Prestasi Lomba',
  `file_surat_pertanggungjawaban_kepsek_lomba` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Pertanggungjawaban Mutlak Kepala Sekolah Asal (untuk Prestasi Lomba)',
  `file_rapor_5_semester` varchar(255) DEFAULT NULL COMMENT 'Scan Rapor 5 Semester Terakhir (Kelas 4 smt 1&2, Kelas 5 smt 1&2, Kelas 6 smt 1)',
  `file_suket_nilai_rapor_peringkat_kepsek` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Keterangan Nilai Rapor dan Peringkat dari Kepala Sekolah',
  `file_kartu_bantuan_sosial` varchar(255) DEFAULT NULL COMMENT 'Scan KIP / PKH / KKS',
  `file_sktm_dtks_dinsos` varchar(255) DEFAULT NULL COMMENT 'Scan SKTM atau Surat Keterangan Terdaftar DTKS dari Dinsos (jika tidak memiliki kartu bantuan)',
  `file_suket_disabilitas_dokter_psikolog` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Keterangan Disabilitas dari Dokter/Psikolog',
  `file_surat_penugasan_ortu_instansi` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Penugasan Pindah Tugas dari Instansi/Lembaga/Perusahaan Orang Tua/Wali',
  `file_sk_penugasan_guru_tendik` varchar(255) DEFAULT NULL COMMENT 'Scan SK Penugasan sebagai Guru/Tenaga Kependidikan di sekolah tujuan (SMPN Unggulan Sindang)',
  `file_surat_rekomendasi_dirjen_luarnegeri` varchar(255) DEFAULT NULL COMMENT 'Scan Surat Rekomendasi Izin Belajar dari Direktur Jenderal (untuk siswa dari sekolah luar negeri)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `biodata_siswas`
--

CREATE TABLE `biodata_siswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jns_kelamin` enum('L','P') DEFAULT NULL,
  `tinggi_badan` int(10) UNSIGNED DEFAULT NULL COMMENT 'Dalam cm, minimal 0',
  `berat_badan` int(10) UNSIGNED DEFAULT NULL COMMENT 'Dalam kg, minimal 0',
  `lingkar_kepala` int(10) UNSIGNED DEFAULT NULL COMMENT 'Dalam cm, minimal 0',
  `agama` enum('Islam','Kristen','Katolik','Hindu','Budha','Konghucu') DEFAULT NULL,
  `anak_ke` int(10) UNSIGNED DEFAULT NULL COMMENT 'Minimal 0',
  `jml_saudara_kandung` int(10) UNSIGNED DEFAULT NULL COMMENT 'Minimal 0',
  `asal_sekolah` varchar(255) DEFAULT NULL,
  `npsn_asal_sekolah` varchar(255) DEFAULT NULL,
  `alamat_asal_sekolah` varchar(255) DEFAULT NULL,
  `alamat_rumah` text DEFAULT NULL,
  `kelurahan_desa` varchar(255) DEFAULT NULL,
  `rt` varchar(255) DEFAULT NULL,
  `rw` varchar(255) DEFAULT NULL,
  `kode_pos` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ekskuls`
--

CREATE TABLE `ekskuls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `isi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru_dan_staff`
--

CREATE TABLE `guru_dan_staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) NOT NULL,
  `kategori` enum('kepala_sekolah','guru','staff') NOT NULL,
  `sambutan` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_ppdb`
--

CREATE TABLE `jadwal_ppdb` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) NOT NULL,
  `pembukaan_pendaftaran` datetime NOT NULL,
  `penutupan_pendaftaran` datetime NOT NULL,
  `pengumuman_hasil` datetime NOT NULL,
  `mulai_daftar_ulang` datetime NOT NULL,
  `selesai_daftar_ulang` datetime NOT NULL,
  `kuota_total_keseluruhan` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_ppdb`
--

INSERT INTO `jadwal_ppdb` (`id`, `tahun_ajaran`, `pembukaan_pendaftaran`, `penutupan_pendaftaran`, `pengumuman_hasil`, `mulai_daftar_ulang`, `selesai_daftar_ulang`, `kuota_total_keseluruhan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2025/2026', '2025-07-23 00:45:00', '2025-07-24 00:45:00', '2025-07-25 00:45:00', '2025-07-25 00:45:00', '2025-07-25 00:45:00', 320, 1, '2025-07-22 17:45:40', '2025-07-22 17:45:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kartu_pendaftarans`
--

CREATE TABLE `kartu_pendaftarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_kartu` varchar(255) NOT NULL,
  `tanggal_pembuatan` datetime NOT NULL,
  `jalur_pendaftaran` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `verified_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_01_112559_create_jadwal_ppdbs_table', 1),
(5, '2025_06_01_112823_create_profil_sekolahs_table', 1),
(6, '2025_06_01_112955_create_guru_dan_staff_table', 1),
(7, '2025_06_01_113036_create_galleries_table', 1),
(8, '2025_06_01_113114_create_ekskuls_table', 1),
(9, '2025_06_01_113237_create_beritas_table', 1),
(10, '2025_06_09_212436_create_pengumumans_table', 1),
(11, '2025_06_12_024157_add_daftar_ulang_fields_to_users_table', 1),
(12, '2025_06_26_214336_create_biodata_siswas_table', 1),
(13, '2025_06_26_214622_create_orang_tuas_table', 1),
(14, '2025_06_26_214829_create_walis_table', 1),
(15, '2025_06_26_232241_create_berkas_pendaftars', 1),
(16, '2025_07_07_121630_alter_enum_status_pendaftaran_users_table', 1),
(17, '2025_07_10_133146_create_kartu_pendaftarans_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orang_tuas`
--

CREATE TABLE `orang_tuas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `nik_ayah` varchar(255) DEFAULT NULL,
  `tempat_lahir_ayah` varchar(255) DEFAULT NULL,
  `tgl_lahir_ayah` date DEFAULT NULL,
  `pendidikan_ayah` enum('SD','SMP','SMA','D3','S1','S2','S3') DEFAULT NULL,
  `pekerjaan_ayah` varchar(255) DEFAULT NULL,
  `penghasilan_ayah` enum('<2juta','2-5juta','5-10juta','>10juta') DEFAULT NULL,
  `no_hp_ayah` varchar(255) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `nik_ibu` varchar(255) DEFAULT NULL,
  `tempat_lahir_ibu` varchar(255) DEFAULT NULL,
  `tgl_lahir_ibu` date DEFAULT NULL,
  `pendidikan_ibu` enum('SD','SMP','SMA','D3','S1','S2','S3') DEFAULT NULL,
  `pekerjaan_ibu` varchar(255) DEFAULT NULL,
  `penghasilan_ibu` enum('<2juta','2-5juta','5-10juta','>10juta') DEFAULT NULL,
  `no_hp_ibu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumumans`
--

CREATE TABLE `pengumumans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tipe` enum('info','warning','success','danger','pengumuman_hasil') NOT NULL DEFAULT 'info' COMMENT 'Tipe pengumuman: berita umum atau pengumuman hasil seleksi',
  `tanggal` datetime DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_penerima` enum('semua','calon_siswa','siswa_diterima','siswa_ditolak') NOT NULL DEFAULT 'semua',
  `aktif` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Apakah pengumuman ini aktif dan terlihat',
  `tanggal_berakhir` datetime DEFAULT NULL COMMENT 'Kapan pengumuman tidak lagi terlihat',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_sekolahs`
--

CREATE TABLE `profil_sekolahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_sekolah` varchar(255) NOT NULL DEFAULT 'Cerulean School',
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `jml_siswa` int(11) NOT NULL DEFAULT 0,
  `jml_guru` int(11) NOT NULL DEFAULT 0,
  `jml_staff` int(11) NOT NULL DEFAULT 0,
  `logo_sekolah` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `kontak1` varchar(255) NOT NULL,
  `kontak2` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `prestasi_sekolah` text DEFAULT NULL,
  `fasilitas_sekolah` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa') NOT NULL DEFAULT 'siswa',
  `jalur_pendaftaran` enum('domisili','prestasi_akademik_lomba','prestasi_non_akademik_lomba','prestasi_rapor','afirmasi_ketm','afirmasi_disabilitas','mutasi_pindah_tugas','mutasi_anak_guru') DEFAULT NULL,
  `kecamatan_domisili` varchar(255) DEFAULT NULL,
  `desa_kelurahan_domisili` varchar(255) DEFAULT NULL,
  `no_pendaftaran` varchar(255) DEFAULT NULL,
  `status_pendaftaran` enum('akun_terdaftar','belum_diverifikasi','menunggu_kelengkapan_data','menunggu_verifikasi_berkas','berkas_tidak_lengkap','berkas_diverifikasi','lulus_seleksi','tidak_lulus_seleksi','mengundurkan_diri','daftar_ulang_selesai') DEFAULT 'belum_diverifikasi',
  `catatan_verifikasi` text DEFAULT NULL,
  `koordinat_domisili_siswa` varchar(255) DEFAULT NULL,
  `jarak_ke_sekolah` double DEFAULT NULL,
  `jarak_ke_kecamatan` double DEFAULT NULL,
  `jarak_ke_desa` double DEFAULT NULL,
  `peringkat_zonasi` int(11) DEFAULT NULL,
  `status_daftar_ulang` enum('belum','proses_verifikasi','selesai','batal') DEFAULT NULL COMMENT 'Status proses daftar ulang siswa',
  `tanggal_daftar_ulang_selesai` timestamp NULL DEFAULT NULL COMMENT 'Waktu selesai daftar ulang',
  `catatan_daftar_ulang` text DEFAULT NULL COMMENT 'Catatan dari admin terkait daftar ulang',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `nisn`, `email`, `email_verified_at`, `password`, `role`, `jalur_pendaftaran`, `kecamatan_domisili`, `desa_kelurahan_domisili`, `no_pendaftaran`, `status_pendaftaran`, `catatan_verifikasi`, `koordinat_domisili_siswa`, `jarak_ke_sekolah`, `jarak_ke_kecamatan`, `jarak_ke_desa`, `peringkat_zonasi`, `status_daftar_ulang`, `tanggal_daftar_ulang_selesai`, `catatan_daftar_ulang`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, 'admin2403@gmail.com', '2025-07-22 17:43:25', '$2y$12$KCeqtu5BodUbRCyQlPseMOw3YuWXj4hC.dZH.5or52c1fpAcHwU7u', 'admin', NULL, NULL, NULL, NULL, 'belum_diverifikasi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-22 17:43:25', '2025-07-22 17:43:25'),
(2, 'Admin Kedua', NULL, 'admin2@gmail.com', '2025-07-22 17:43:25', '$2y$12$m8aS1ipYXFTiV613yBdACezCuopq1fTH1kgLbIC/menErtPBluyhy', 'admin', NULL, NULL, NULL, NULL, 'belum_diverifikasi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-22 17:43:25', '2025-07-22 17:43:25'),
(3, 'Mark Lee', '0045123456', 'marklee@gmail.com', '2025-07-22 17:43:26', '$2y$12$BePXUOTeUO.2z8orcq9rfe7lD4bFUSmSSovPX7DQY0qsIiw.xus42', 'siswa', 'domisili', 'Kecamatan Sindang Ujung', NULL, 'Z20240001', 'belum_diverifikasi', NULL, '106.123456, -6.123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-22 17:43:26', '2025-07-22 17:43:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `walis`
--

CREATE TABLE `walis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `nik_wali` varchar(255) DEFAULT NULL,
  `tempat_lahir_wali` varchar(255) DEFAULT NULL,
  `tgl_lahir_wali` date DEFAULT NULL,
  `pendidikan_wali` enum('SD','SMP','SMA','D3','S1','S2','S3') DEFAULT NULL,
  `pekerjaan_wali` varchar(255) DEFAULT NULL,
  `penghasilan_wali` enum('<2juta','2-5juta','5-10juta','>10juta') DEFAULT NULL,
  `no_hp_wali` varchar(255) DEFAULT NULL,
  `hubungan_wali_dgn_siswa` varchar(255) DEFAULT NULL,
  `alamat_wali` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `beritas`
--
ALTER TABLE `beritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `beritas_slug_unique` (`slug`),
  ADD KEY `beritas_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `berkas_pendaftars`
--
ALTER TABLE `berkas_pendaftars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `berkas_pendaftars_user_id_unique` (`user_id`);

--
-- Indeks untuk tabel `biodata_siswas`
--
ALTER TABLE `biodata_siswas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `1` (`user_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `ekskuls`
--
ALTER TABLE `ekskuls`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `guru_dan_staff`
--
ALTER TABLE `guru_dan_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guru_dan_staff_nip_unique` (`nip`);

--
-- Indeks untuk tabel `jadwal_ppdb`
--
ALTER TABLE `jadwal_ppdb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kartu_pendaftarans`
--
ALTER TABLE `kartu_pendaftarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kartu_pendaftarans_nomor_kartu_unique` (`nomor_kartu`),
  ADD KEY `kartu_pendaftarans_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orang_tuas`
--
ALTER TABLE `orang_tuas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orang_tuas_nik_ayah_unique` (`nik_ayah`),
  ADD UNIQUE KEY `orang_tuas_nik_ibu_unique` (`nik_ibu`),
  ADD KEY `orang_tuas_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `pengumumans`
--
ALTER TABLE `pengumumans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengumumans_slug_unique` (`slug`),
  ADD KEY `pengumumans_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `profil_sekolahs`
--
ALTER TABLE `profil_sekolahs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nisn_unique` (`nisn`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_no_pendaftaran_unique` (`no_pendaftaran`);

--
-- Indeks untuk tabel `walis`
--
ALTER TABLE `walis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `walis_nik_wali_unique` (`nik_wali`),
  ADD KEY `walis_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `beritas`
--
ALTER TABLE `beritas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `berkas_pendaftars`
--
ALTER TABLE `berkas_pendaftars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `biodata_siswas`
--
ALTER TABLE `biodata_siswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ekskuls`
--
ALTER TABLE `ekskuls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `guru_dan_staff`
--
ALTER TABLE `guru_dan_staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwal_ppdb`
--
ALTER TABLE `jadwal_ppdb`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kartu_pendaftarans`
--
ALTER TABLE `kartu_pendaftarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `orang_tuas`
--
ALTER TABLE `orang_tuas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengumumans`
--
ALTER TABLE `pengumumans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `profil_sekolahs`
--
ALTER TABLE `profil_sekolahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `walis`
--
ALTER TABLE `walis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `beritas`
--
ALTER TABLE `beritas`
  ADD CONSTRAINT `beritas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `berkas_pendaftars`
--
ALTER TABLE `berkas_pendaftars`
  ADD CONSTRAINT `berkas_pendaftars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `biodata_siswas`
--
ALTER TABLE `biodata_siswas`
  ADD CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kartu_pendaftarans`
--
ALTER TABLE `kartu_pendaftarans`
  ADD CONSTRAINT `kartu_pendaftarans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orang_tuas`
--
ALTER TABLE `orang_tuas`
  ADD CONSTRAINT `orang_tuas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumumans`
--
ALTER TABLE `pengumumans`
  ADD CONSTRAINT `pengumumans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `walis`
--
ALTER TABLE `walis`
  ADD CONSTRAINT `walis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
