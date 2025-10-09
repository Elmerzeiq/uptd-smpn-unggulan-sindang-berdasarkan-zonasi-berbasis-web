<?php

return [
    'kecamatan_zonasi' => [
        'Sindang',
        'Indramayu',
        'Lohbener',
        'Arahan',
    ],

    'desa_kelurahan_zonasi' => [
        'Terusan',
        'Kenanga',
        'Sindang',
        'Rambatan Wetan',
        'Wanantara',
        'Penganjang',
        'Dermayu',
        'Babadan',
        'Panyindangan Wetan',
        'Panyindangan Kulon',
        'Margadadi',
        'Lemah Mekar',
        'Lemah Abang',
        'Karanganyar',
        'Paoman',
        'Bojongsari',
        'Karangmalang',
        'Pabean Udik',
        'Kepandaian',
        'Dukuh',
        'Karangsong',
        'Pekandangan',
        'Pekandangan Jaya',
        'Plumbon',
        'Singajaya',
        'Tambak',
        'Telukagung',
        'Singaraja',
        'Rambatan Kulon',
        'Sindangkerta',
        'Pamayahan',
        'Lohbener',
        'Sukasari',
    ],

    // Koordinat sekolah - WAJIB diisi dengan koordinat yang benar
    'koordinat_sekolah' => [
        'lat' => -6.3390, // Latitude sekolah Anda
        'lng' => 108.3225, // Longitude sekolah Anda
    ],

    // Koordinat pusat kecamatan - untuk fallback jika koordinat siswa tidak ada
    'kecamatan_koordinat' => [
        'sindang'    => ['lat' => -6.3400, 'lng' => 108.3200],
        'indramayu'  => ['lat' => -6.3276, 'lng' => 108.3249],
        'lohbener'   => ['lat' => -6.3905, 'lng' => 108.2616],
        'arahan'     => ['lat' => -6.3880, 'lng' => 108.1845],
    ],

    // Koordinat desa/kelurahan - untuk fallback yang lebih spesifik
    'desa_koordinat' => [
        'terusan'               => ['lat' => -6.3410, 'lng' => 108.3150],
        'kenanga'               => ['lat' => -6.3300, 'lng' => 108.3250],
        'sindang'               => ['lat' => -6.3400, 'lng' => 108.3200],
        'rambatwetan'          => ['lat' => -6.3450, 'lng' => 108.3180], // hilangkan spasi
        'rambatanwetan'        => ['lat' => -6.3450, 'lng' => 108.3180], // alternatif tanpa spasi
        'wanantara'            => ['lat' => -6.3360, 'lng' => 108.3220],
        'penganjang'           => ['lat' => -6.3350, 'lng' => 108.3280],
        'dermayu'              => ['lat' => -6.3330, 'lng' => 108.3290],
        'babadan'              => ['lat' => -6.3420, 'lng' => 108.3305],
        'panyindanganwetan'    => ['lat' => -6.3240, 'lng' => 108.3370], // hilangkan spasi
        'panyindangankulon'    => ['lat' => -6.3250, 'lng' => 108.3340], // hilangkan spasi
        'margadadi'            => ['lat' => -6.3385, 'lng' => 108.3215],
        'lemahmekar'           => ['lat' => -6.3470, 'lng' => 108.3110], // hilangkan spasi
        'lemahabang'           => ['lat' => -6.3500, 'lng' => 108.3100], // hilangkan spasi
        'karanganyar'          => ['lat' => -6.3440, 'lng' => 108.3160],
        'paoman'               => ['lat' => -6.3395, 'lng' => 108.3190],
        'bojongsari'           => ['lat' => -6.3380, 'lng' => 108.3235],
        'karangmalang'         => ['lat' => -6.3375, 'lng' => 108.3270],
        'pabeaudik'            => ['lat' => -6.3320, 'lng' => 108.3345], // hilangkan spasi
        'kepandaian'           => ['lat' => -6.3310, 'lng' => 108.3360],
        'dukuh'                => ['lat' => -6.3415, 'lng' => 108.3195],
        'karangsong'           => ['lat' => -6.3390, 'lng' => 108.3310],
        'pekandangan'          => ['lat' => -6.3260, 'lng' => 108.3380],
        'pekandanganjaya'      => ['lat' => -6.3270, 'lng' => 108.3365], // hilangkan spasi
        'plumbon'              => ['lat' => -6.3365, 'lng' => 108.3155],
        'singajaya'            => ['lat' => -6.3430, 'lng' => 108.3175],
        'tambak'               => ['lat' => -6.3315, 'lng' => 108.3295],
        'telukagung'           => ['lat' => -6.3335, 'lng' => 108.3210],
        'singaraja'            => ['lat' => -6.3345, 'lng' => 108.3205],
        'rambatankulon'        => ['lat' => -6.3460, 'lng' => 108.3165], // hilangkan spasi
        'sindangkerta'         => ['lat' => -6.3398, 'lng' => 108.3222],
        'pamayahan'            => ['lat' => -6.3388, 'lng' => 108.3238],
        'lohbener'             => ['lat' => -6.3905, 'lng' => 108.2616],
        'sukasari'             => ['lat' => -6.3325, 'lng' => 108.3285],

        // Alternatif dengan spasi (untuk fallback)
        'rambatan wetan'       => ['lat' => -6.3450, 'lng' => 108.3180],
        'panyindangan wetan'   => ['lat' => -6.3240, 'lng' => 108.3370],
        'panyindangan kulon'   => ['lat' => -6.3250, 'lng' => 108.3340],
        'lemah mekar'          => ['lat' => -6.3470, 'lng' => 108.3110],
        'lemah abang'          => ['lat' => -6.3500, 'lng' => 108.3100],
        'pabean udik'          => ['lat' => -6.3320, 'lng' => 108.3345],
        'pekandangan jaya'     => ['lat' => -6.3270, 'lng' => 108.3365],
        'rambatan kulon'       => ['lat' => -6.3460, 'lng' => 108.3165],
    ],

    // Persentase kuota per jalur
    'kuota_persentase' => [
        'zonasi' => 0.40,
        'prestasi' => 0.37,
        'afirmasi' => 0.20,
        'mutasi' => 0.03,
    ],

    // Default fallback jika semua koordinat gagal
    'default_fallback_koordinat' => [
        'lat' => -6.3390,
        'lng' => 108.3225,
    ],



    // Informasi Sekolah
    'sekolah' => [
        'nama' => env('SEKOLAH_NAMA', 'SMPN UNGGULAN SINDANG'),
        'alamat' => env('SEKOLAH_ALAMAT', 'Jl. Pendidikan No. 123, Sindang'),
        'telepon' => env('SEKOLAH_TELEPON', '021-12345678'),
        'email' => env('SEKOLAH_EMAIL', 'info@smpnunggulan.sch.id'),
        'website' => env('SEKOLAH_WEBSITE', 'https://www.smpnunggulan.sch.id'),
        'instagram' => env('SEKOLAH_INSTAGRAM', '@smpnunggulansindang.official'),
    ],

    // Tahun Ajaran
    'tahun_ajaran' => [
        'aktif' => env('TAHUN_AJARAN_AKTIF', '2025/2026'),
        'mulai' => env('TAHUN_AJARAN_MULAI', '2025'),
        'selesai' => env('TAHUN_AJARAN_SELESAI', '2026'),
    ],

    // Jadwal PPDB
    'jadwal' => [
        'pendaftaran_mulai' => env('PPDB_PENDAFTARAN_MULAI', '2025-06-01'),
        'pendaftaran_selesai' => env('PPDB_PENDAFTARAN_SELESAI', '2025-06-30'),
        'pengumuman' => env('PPDB_PENGUMUMAN', '2025-07-15'),
        'daftar_ulang_mulai' => env('PPDB_DAFTAR_ULANG_MULAI', '2025-07-20'),
        'daftar_ulang_selesai' => env('PPDB_DAFTAR_ULANG_SELESAI', '2025-08-05'),
    ],

    // Jalur Pendaftaran
    'jalur_pendaftaran' => [
        'zonasi' => [
            'nama' => 'Zonasi',
            'kuota_persen' => 50,
            'deskripsi' => 'Jalur berdasarkan domisili terdekat dengan sekolah',
            'syarat' => [
                'Kartu Keluarga minimal 1 tahun',
                'Domisili dalam zona sekolah',
                'Koordinat rumah harus valid'
            ]
        ],
        'prestasi' => [
            'nama' => 'Prestasi',
            'kuota_persen' => 30,
            'deskripsi' => 'Jalur berdasarkan prestasi akademik dan non-akademik',
            'syarat' => [
                'Nilai rapor minimal 85',
                'Sertifikat prestasi (opsional)',
                'Rekomendasi dari sekolah asal'
            ]
        ],
        'afirmasi' => [
            'nama' => 'Afirmasi',
            'kuota_persen' => 15,
            'deskripsi' => 'Jalur untuk peserta didik dari keluarga kurang mampu',
            'syarat' => [
                'Kartu Indonesia Pintar (KIP)',
                'Surat keterangan tidak mampu',
                'Rekomendasi dari kelurahan/desa'
            ]
        ],
        'mutasi' => [
            'nama' => 'Mutasi',
            'kuota_persen' => 5,
            'deskripsi' => 'Jalur untuk perpindahan tugas orang tua',
            'syarat' => [
                'Surat perpindahan tugas',
                'Surat rekomendasi',
                'Domisili baru'
            ]
        ]
    ],

    // Kuota dan Kapasitas
    'kuota' => [
        'total_siswa' => env('PPDB_KUOTA_TOTAL', 360),
        'jumlah_kelas' => env('PPDB_JUMLAH_KELAS', 12),
        'siswa_per_kelas' => env('PPDB_SISWA_PER_KELAS', 30),
    ],

    // File Upload Settings
    'upload' => [
        'max_size' => env('PPDB_MAX_UPLOAD_SIZE', 2048), // KB
        'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
        'storage_disk' => env('PPDB_STORAGE_DISK', 'public'),
        'storage_path' => env('PPDB_STORAGE_PATH', 'ppdb-documents'),
    ],

    // Biaya Daftar Ulang
    'biaya' => [
        'daftar_ulang' => env('PPDB_BIAYA_DAFTAR_ULANG', 500000),
        'seragam' => env('PPDB_BIAYA_SERAGAM', 300000),
        'buku' => env('PPDB_BIAYA_BUKU', 200000),
        'kegiatan' => env('PPDB_BIAYA_KEGIATAN', 150000),
        'osis' => env('PPDB_BIAYA_OSIS', 50000),
        'spp_bulanan' => env('PPDB_SPP_BULANAN', 150000),
    ],

    // Bank untuk Pembayaran
    'bank' => [
        'utama' => [
            'nama' => env('BANK_NAMA', 'Bank BCA'),
            'nomor_rekening' => env('BANK_REKENING', '1234567890'),
            'atas_nama' => env('BANK_ATAS_NAMA', 'SMPN UNGGULAN SINDANG'),
        ],
        'alternatif' => [
            [
                'nama' => env('BANK_ALT1_NAMA', 'Bank Mandiri'),
                'nomor_rekening' => env('BANK_ALT1_REKENING', '9876543210'),
                'atas_nama' => env('BANK_ALT1_ATAS_NAMA', 'SMPN UNGGULAN SINDANG'),
            ],
        ]
    ],

    // Pengaturan Daftar Ulang
    'daftar_ulang' => [
        'max_file_size' => env('DAFTAR_ULANG_MAX_FILE_SIZE', 5120), // KB
        'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
        'jadwal_kuota_default' => env('DAFTAR_ULANG_KUOTA_DEFAULT', 50),
        'batas_waktu_pembayaran' => env('DAFTAR_ULANG_BATAS_PEMBAYARAN', 7), // hari
        'auto_reminder' => env('DAFTAR_ULANG_AUTO_REMINDER', true),
    ],

    // Notifikasi
    'notifications' => [
        'daftar_ulang' => [
            'admin_email' => env('ADMIN_NOTIFICATION_EMAIL', 'admin@smpunggulan.sch.id'),
            'send_sms' => env('DAFTAR_ULANG_SMS_NOTIFICATION', false),
            'send_email' => env('DAFTAR_ULANG_EMAIL_NOTIFICATION', true),
        ]
    ],

    // Status Mapping
    'status_daftar_ulang' => [
        'belum_daftar' => 'Belum Daftar',
        'menunggu_verifikasi_berkas' => 'Menunggu Verifikasi Berkas',
        'berkas_diverifikasi' => 'Berkas Diverifikasi',
        'menunggu_verifikasi_pembayaran' => 'Menunggu Verifikasi Pembayaran',
        'daftar_ulang_selesai' => 'Daftar Ulang Selesai',
        'ditolak' => 'Ditolak'
    ],

    'status_pembayaran' => [
        'belum_bayar' => 'Belum Bayar',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'sudah_lunas' => 'Sudah Lunas',
        'ditolak' => 'Ditolak'
    ],


    // Kontak & Support
    'kontak' => [
        'whatsapp' => env('PPDB_WHATSAPP', '081234567890'),
        'email_ppdb' => env('PPDB_EMAIL', 'ppdb@smpnunggulan.sch.id'),
        'jam_pelayanan' => 'Senin - Jumat: 08:00 - 15:00',
        'libur' => 'Sabtu, Minggu & Hari Libur Nasional',
    ],

    // QR Code Settings
    'qr_code' => [
        'size' => env('QR_CODE_SIZE', 200),
        'format' => env('QR_CODE_FORMAT', 'png'),
        'margin' => env('QR_CODE_MARGIN', 2),
        'error_correction' => env('QR_CODE_ERROR_CORRECTION', 'M'), // L, M, Q, H
    ],

    // PDF Settings
    'pdf' => [
        'paper' => env('PDF_PAPER', 'a4'),
        'orientation' => env('PDF_ORIENTATION', 'portrait'),
        'font_size' => env('PDF_FONT_SIZE', 12),
        'margin' => [
            'top' => env('PDF_MARGIN_TOP', 15),
            'right' => env('PDF_MARGIN_RIGHT', 15),
            'bottom' => env('PDF_MARGIN_BOTTOM', 15),
            'left' => env('PDF_MARGIN_LEFT', 15),
        ]
    ],

    // Notification Settings
    'notification' => [
        'email_enabled' => env('NOTIFICATION_EMAIL_ENABLED', true),
        'sms_enabled' => env('NOTIFICATION_SMS_ENABLED', false),
        'whatsapp_enabled' => env('NOTIFICATION_WHATSAPP_ENABLED', false),
    ],

    // Security & Validation
    'security' => [
        'max_login_attempts' => env('PPDB_MAX_LOGIN_ATTEMPTS', 5),
        'lockout_duration' => env('PPDB_LOCKOUT_DURATION', 15), // minutes
        'session_timeout' => env('PPDB_SESSION_TIMEOUT', 120), // minutes
        'password_min_length' => env('PPDB_PASSWORD_MIN_LENGTH', 8),
    ],

    // Rate Limiting
    'rate_limit' => [
        'login' => '5,1', // 5 attempts per minute
        'upload' => '10,1', // 10 uploads per minute
        'download' => '30,1', // 30 downloads per minute
        'api' => '60,1', // 60 API calls per minute
    ],

    // Features Toggle
    'features' => [
        'registration_enabled' => env('PPDB_REGISTRATION_ENABLED', true),
        'document_upload_enabled' => env('PPDB_DOCUMENT_UPLOAD_ENABLED', true),
        'daftar_ulang_enabled' => env('PPDB_DAFTAR_ULANG_ENABLED', true),
        'payment_verification_enabled' => env('PPDB_PAYMENT_VERIFICATION_ENABLED', true),
        'auto_backup_enabled' => env('PPDB_AUTO_BACKUP_ENABLED', true),
    ],

    // Cache Settings
    'cache' => [
        'statistics_ttl' => env('PPDB_CACHE_STATISTICS_TTL', 300), // 5 minutes
        'jadwal_ttl' => env('PPDB_CACHE_JADWAL_TTL', 600), // 10 minutes
        'user_session_ttl' => env('PPDB_CACHE_USER_SESSION_TTL', 3600), // 1 hour
    ],

    // Logging
    'logging' => [
        'log_user_activities' => env('PPDB_LOG_USER_ACTIVITIES', true),
        'log_admin_actions' => env('PPDB_LOG_ADMIN_ACTIONS', true),
        'log_file_uploads' => env('PPDB_LOG_FILE_UPLOADS', true),
        'log_level' => env('PPDB_LOG_LEVEL', 'info'),
    ],

    // Backup & Maintenance
    'backup' => [
        'enabled' => env('PPDB_BACKUP_ENABLED', true),
        'schedule' => env('PPDB_BACKUP_SCHEDULE', 'daily'),
        'retention_days' => env('PPDB_BACKUP_RETENTION_DAYS', 30),
        'include_files' => env('PPDB_BACKUP_INCLUDE_FILES', true),
    ],


];

