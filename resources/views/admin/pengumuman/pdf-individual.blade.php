<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil SPMB - {{ $siswa->nama_lengkap }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;

            border: 3px solid {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#10b981': '#ef4444'
                }
            }

            ;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .header {
            background: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi'
                    ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)'
                    : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'
                }
            }

            ;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '{{ $siswa->status_pendaftaran === ' lulus_seleksi' ? ' 🎉' : ' 💪' }}';
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 24px;
        }

        .header::after {
            content: '{{ $siswa->status_pendaftaran === ' lulus_seleksi' ? ' 🎉' : ' 💪' }}';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header .subtitle {
            font-size: 12px;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 15px;
            display: inline-block;
        }

        .content {
            padding: 25px;
        }

        .result-banner {
            background: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi'
                    ? 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)'
                    : 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)'
                }
            }

            ;

            color: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#065f46': '#991b1b'
                }
            }

            ;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;

            border: 2px solid {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#10b981': '#ef4444'
                }
            }

            ;
        }

        .result-banner h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .result-banner .status {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
            letter-spacing: 2px;
        }

        .main-content {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .left-content {
            display: table-cell;
            width: 70%;
            padding-right: 20px;
            vertical-align: top;
        }

        .right-content {
            display: table-cell;
            width: 30%;
            text-align: center;
            vertical-align: top;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #374151;
            padding: 8px 10px 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-value {
            display: table-cell;
            width: 60%;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
        }

        .qr-section {
            background: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#f0fdf4': '#fef2f2'
                }
            }

            ;
            padding: 15px;
            border-radius: 8px;

            border: 2px solid {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#10b981': '#ef4444'
                }
            }

            ;
            margin-bottom: 15px;
        }

        .qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto 10px;

            border: 2px solid {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#059669': '#dc2626'
                }
            }

            ;
            border-radius: 4px;
            background: white;
            padding: 8px;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .instruction-box {
            background: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#ecfdf5': '#fef7f7'
                }
            }

            ;

            border: 2px solid {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#10b981': '#ef4444'
                }
            }

            ;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .instruction-box h4 {
            color: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#047857': '#991b1b'
                }
            }

            ;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        .instruction-item {
            background: white;

            border: 1px solid {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#6ee7b7': '#fca5a5'
                }
            }

            ;
            border-radius: 6px;
            padding: 12px;
            margin: 10px 0;
        }

        .instruction-item h5 {
            color: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#065f46': '#7f1d1d'
                }
            }

            ;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .instruction-item p {
            color: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi' ? '#047857': '#991b1b'
                }
            }

            ;
            font-size: 10px;
            line-height: 1.4;
        }

        .contact-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            text-align: center;
        }

        .contact-info h5 {
            color: #374151;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .contact-info p {
            color: #6b7280;
            font-size: 10px;
            line-height: 1.4;
        }

        .footer {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 20px;
            text-align: center;
        }

        .footer p {
            color: #6b7280;
            font-size: 10px;
            line-height: 1.4;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;

            color: {
                    {
                    $siswa->status_pendaftaran ==='lulus_seleksi'
                    ? 'rgba(16, 185, 129, 0.05)'
                    : 'rgba(239, 68, 68, 0.05)'
                }
            }

            ;
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }

        .print-date {
            font-size: 10px;
            color: #6b7280;
            text-align: center;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="watermark">{{ $siswa->status_pendaftaran === 'lulus_seleksi' ? 'DITERIMA' : 'TETAP SEMANGAT' }}</div>

    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>SMPN UNGGULAN SINDANG</h1>
            <h2>PENGUMUMAN HASIL SELEKSI SPMB</h2>
            <div class="subtitle">TAHUN PELAJARAN {{ date('Y') }}/{{ date('Y')+1 }}</div>
        </div>

        {{-- Content --}}
        <div class="content">
            {{-- Result Banner --}}
            <div class="result-banner">
                @if($siswa->status_pendaftaran === 'lulus_seleksi')
                <h3>🎉 Selamat! Anda dinyatakan: 🎉</h3>
                <div class="status">DITERIMA</div>
                <p>Sebagai Siswa Baru SMPN Unggulan Sindang</p>
                @else
                <h3>💪 Maaf Anda dinyatakan: 💪</h3>
                <div class="status">TIDAK DITERIMA</div>
                <p><strong>Tetap semangat jangan menyerah!</strong><br>Masih ada kesempatan di tempat lain</p>
                @endif
            </div>

            <div class="main-content">
                <div class="left-content">
                    {{-- Data Siswa --}}
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">No. Pendaftaran</div>
                            <div class="info-value">{{ $siswa->kartuPendaftaran->nomor_kartu ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Nama Lengkap</div>
                            <div class="info-value">{{ strtoupper($siswa->nama_lengkap) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">NISN</div>
                            <div class="info-value">{{ $siswa->biodata->nisn ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Asal Sekolah</div>
                            <div class="info-value">{{ $siswa->biodata->asal_sekolah ?? '-' }}</div>
                        </div>
                        @if($siswa->kartuPendaftaran)
                        <div class="info-row">
                            <div class="info-label">Jalur Pendaftaran</div>
                            <div class="info-value">{{ strtoupper($siswa->kartuPendaftaran->jalur_pendaftaran) }}</div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Tanggal Pengumuman</div>
                            <div class="info-value">{{ now()->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="right-content">
                    {{-- QR Code Section --}}
                    <div class="qr-section">
                        <div class="qr-code">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Hasil">
                        </div>
                        <div class="print-date">
                            <strong>Tanggal Cetak</strong><br>
                            {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="instruction-box">
                @if($siswa->status_pendaftaran === 'lulus_seleksi')
                <h4>📋 INSTRUKSI UNTUK SISWA YANG DITERIMA</h4>

                <div class="instruction-item">
                    <h5>1. Daftar Ulang</h5>
                    <p>Bagi siswa yang telah dinyatakan diterima bisa langsung datang kembali ke sekolah dengan
                        menyerahkan bukti ini kepada pihak sekolah sesuai dengan jadwal yang telah ditentukan.</p>
                </div>

                <div class="instruction-item">
                    <h5>2. Dokumen yang Harus Dibawa</h5>
                    <p>• Bukti hasil ini (wajib dibawa)<br>• Ijazah/SKHUN asli dan fotokopi<br>• Kartu Keluarga asli dan
                        fotokopi<br>• Akta Kelahiran asli dan fotokopi<br>• Pas foto terbaru 3x4 (6 lembar)<br>• Kartu
                        pendaftaran (jika ada)</p>
                </div>

                <div class="instruction-item">
                    <h5>3. Jadwal Daftar Ulang</h5>
                    <p>Silakan pantau pengumuman lebih lanjut melalui website sekolah atau Instagram
                        @smpnunggulansindang.official untuk jadwal daftar ulang yang tepat.</p>
                </div>
                @else
                <h4>💪 PESAN UNTUK SISWA</h4>

                <div class="instruction-item">
                    <h5>Jangan Patah Semangat!</h5>
                    <p>Hasil ini bukan menentukan masa depan Anda. Masih banyak sekolah bagus lainnya yang dapat menjadi
                        pilihan. Tetap semangat dan jangan menyerah dalam mengejar cita-cita!</p>
                </div>

                <div class="instruction-item">
                    <h5>Alternatif Pilihan</h5>
                    <p>• Cari informasi sekolah lain yang masih membuka pendaftaran<br>• Persiapkan diri lebih baik
                        untuk kesempatan berikutnya<br>• Konsultasikan dengan orang tua dan guru untuk pilihan terbaik
                    </p>
                </div>

                <div class="instruction-item">
                    <h5>Motivasi</h5>
                    <p>"Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas." Tetap belajar dan berkarya
                        untuk masa depan yang cerah!</p>
                </div>
                @endif
            </div>

            {{-- Contact Info --}}
            <div class="contact-info">
                <h5>📞 INFORMASI LEBIH LANJUT</h5>
                <p>Untuk mendapatkan informasi lebih lanjut silahkan follow Instagram
                    <strong>@smpnunggulansindang.official</strong> atau hubungi panitia SPMB atau kunjungi langsung ke
                    SMPN Unggulan Sindang.</p>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p><strong>Panitia SPMB SMPN Unggulan Sindang</strong><br>
                    Tahun Pelajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
                <p style="margin-top: 10px; font-size: 9px; color: #ef4444;">
                    ⚠️ Dokumen ini adalah bukti resmi hasil seleksi SPMB. Harap disimpan dengan baik dan jangan
                    disebarkan kepada pihak yang tidak berkepentingan.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
