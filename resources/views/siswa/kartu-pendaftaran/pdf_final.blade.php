<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFinalCard ? 'Kartu Final' : 'Kartu Pendaftaran' }} - {{ $kartu->nomor_kartu }}</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;

            border: {
                    {
                    $isFinalCard ? '3px solid #059669': '2px solid #2563eb'
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
                    $isFinalCard ? 'linear-gradient(135deg, #059669 0%, #047857 100%)': 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'
                }
            }

            ;
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 12px;
            @if($isFinalCard) background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 15px;
            display: inline-block;
            margin-top: 5px;
            @else opacity: 0.9;
            @endif
        }

        .content {
            padding: 20px;
        }

        @if($isFinalCard) .accepted-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
        }

        .accepted-banner h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .accepted-banner p {
            font-size: 11px;
        }

        @endif .main-content {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .left-content {
            display: table-cell;
            width: 65%;
            padding-right: 15px;
            vertical-align: top;
        }

        .right-content {
            display: table-cell;
            width: 35%;
            text-align: center;
            vertical-align: top;
        }

        .field-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .field-col {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
            vertical-align: top;
        }

        .field-col:last-child {
            padding-right: 0;
        }

        .field-group {
            margin-bottom: 12px;
        }

        .field-label {
            font-size: 9px;
            color: #666;
            font-weight: 600;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .field-value {
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
            word-wrap: break-word;
            line-height: 1.3;
        }

        .field-value.large {
            font-size: 14px;

            color: {
                    {
                    $isFinalCard ? '#059669': '#2563eb'
                }
            }

            ;
            font-weight: bold;
        }

        .qr-section {
            background: {
                    {
                    $isFinalCard ? '#f0fdf4': '#f8fafc'
                }
            }

            ;
            padding: 12px;
            border-radius: 6px;

            border: {
                    {
                    $isFinalCard ? '2px solid #10b981': '1px solid #e2e8f0'
                }
            }

            ;
            margin-bottom: 12px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto 8px;

            border: 2px solid {
                    {
                    $isFinalCard ? '#059669': '#cbd5e1'
                }
            }

            ;
            border-radius: 4px;
            background: white;
            padding: 5px;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .print-date {
            font-size: 9px;

            color: {
                    {
                    $isFinalCard ? '#047857': '#64748b'
                }
            }

            ;
            text-align: center;
        }

        .print-date .label {
            font-weight: 600;
            display: block;
            margin-bottom: 1px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .status-verified {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .status-lulus {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .koordinat-info {
            background: #f0fdf4;
            border: 1px solid #22c55e;
            border-radius: 4px;
            padding: 8px;
            margin-top: 6px;
        }

        .koordinat-info .label {
            font-size: 8px;
            color: #15803d;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .koordinat-info .value {
            font-size: 10px;
            color: #166534;
            font-weight: 600;
        }

        .footer-info {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .info-box h3 {
            color: #1e40af;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-box p {
            color: #1e40af;
            font-size: 9px;
            line-height: 1.4;
        }

        @if($isFinalCard) .final-info {
            background: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
        }

        .final-info h3 {
            color: #047857;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            text-align: center;
        }

        .instruction {
            background: white;
            border: 1px solid #6ee7b7;
            border-radius: 4px;
            padding: 8px;
            margin: 6px 0;
        }

        .instruction h4 {
            color: #065f46;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .instruction p {
            color: #047857;
            font-size: 9px;
            line-height: 1.3;
        }

        @endif .warning-box {
            background: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }

        .warning-box p {
            color: #dc2626;
            font-size: 9px;
            font-weight: bold;
            line-height: 1.3;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);

            font-size: {
                    {
                    $isFinalCard ? '40px': '50px'
                }
            }

            ;

            color: {
                    {
                    $isFinalCard ? 'rgba(16, 185, 129, 0.03)': 'rgba(37, 99, 235, 0.03)'
                }
            }

            ;
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="watermark">{{ $isFinalCard ? 'DITERIMA' : 'SMPN UNGGULAN SINDANG' }}</div>

    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>SMPN UNGGULAN SINDANG</h1>
            <h2>{{ $isFinalCard ? 'KARTU FINAL PENDAFTARAN' : 'KARTU PENDAFTARAN SISWA BARU' }}</h2>
            <div class="subtitle">
                {{ $isFinalCard ? 'BUKTI KELULUSAN SELEKSI' : 'Tahun Pelajaran ' . date('Y') . '/' . (date('Y')+1) }}
            </div>
        </div>

        {{-- Content --}}
        <div class="content">
            @if($isFinalCard)
            {{-- Banner Diterima --}}
            <div class="accepted-banner">
                <h3>SELAMAT! ANDA DITERIMA</h3>
                <p>Sebagai Siswa Baru SMPN Unggulan Sindang Tahun Pelajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
            </div>
            @endif

            <div class="main-content">
                <div class="left-content">
                    {{-- No Pendaftaran & NISN --}}
                    <div class="field-row">
                        <div class="field-col">
                            <div class="field-label">No. Pendaftaran</div>
                            <div class="field-value large">{{ $kartu->nomor_kartu }}</div>
                        </div>
                        <div class="field-col">
                            <div class="field-label">NISN</div>
                            <div class="field-value">{{ $user->nisn  }}</div>
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="field-group">
                        <div class="field-label">Nama Lengkap</div>
                        <div class="field-value large">{{ strtoupper($user->nama_lengkap) }}</div>
                    </div>

                    {{-- Tempat & Tanggal Lahir --}}
                    <div class="field-row">
                        <div class="field-col">
                            <div class="field-label">Tempat Lahir</div>
                            <div class="field-value">{{ $user->biodata->tempat_lahir ?? '-' }}</div>
                        </div>
                        <div class="field-col">
                            <div class="field-label">Tanggal Lahir</div>
                            <div class="field-value">
                                {{ $user->biodata->tanggal_lahir ?
                                \Carbon\Carbon::parse($user->biodata->tgl_lahir)->format('d/m/Y') : '-' }}
                            </div>
                        </div>
                    </div>

                    @if($isFinalCard)
                    {{-- Jalur Pendaftaran --}}
                    <div class="field-group">
                        <div class="field-label">Jalur Pendaftaran</div>
                        <div class="field-value">{{ strtoupper($kartu->jalur_pendaftaran) }}</div>
                    </div>
                    @endif

                    {{-- Asal Sekolah --}}
                    <div class="field-group">
                        <div class="field-label">Asal Sekolah</div>
                        <div class="field-value">{{ $user->biodata->asal_sekolah ?? '-' }}</div>
                    </div>

                    @if(!$isFinalCard)
                    {{-- Alamat Rumah --}}
                    <div class="field-group">
                        <div class="field-label">Alamat Rumah</div>
                        <div class="field-value">{{ $user->biodata->alamat ?? '-' }}</div>
                    </div>

                    {{-- Koordinat Jalur Zonasi --}}
                    @if($kartu->jalur_pendaftaran === 'zonasi' && $user->biodata->koordinat_rumah)
                    <div class="koordinat-info">
                        <div class="label">Koordinat Rumah (Jalur Zonasi)</div>
                        <div class="value">{{ $user->biodata->koordinat_rumah }}</div>
                    </div>
                    @endif

                    {{-- Status Berkas --}}
                    <div class="field-group">
                        <div class="field-label">Status Berkas</div>
                        <div class="field-value">
                            @php
                            $statusClass = 'status-pending';
                            $statusText = 'Menunggu Verifikasi';

                            switch($user->status_pendaftaran) {
                            case 'verified':
                            $statusClass = 'status-verified';
                            $statusText = 'Berkas Lengkap';
                            break;
                            case 'lulus_seleksi':
                            $statusClass = 'status-lulus';
                            $statusText = 'Diterima';
                            break;
                            case 'tidak_lulus':
                            $statusClass = 'status-ditolak';
                            $statusText = 'Ditolak';
                            break;
                            }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="right-content">
                    {{-- QR Code Section --}}
                    <div class="qr-section">
                        <div class="qr-code">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                        </div>
                        <div class="print-date">
                            <span class="label">Tanggal Cetak</span>
                            {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Information --}}
            <div class="footer-info">
                @if($isFinalCard)
                {{-- Instruksi Daftar Ulang --}}
                <div class="final-info">
                    <h3>INSTRUKSI DAFTAR ULANG</h3>

                    <div class="instruction">
                        <h4>1. Jadwal Daftar Ulang</h4>
                        <p>Silahkan melakukan daftar ulang pada tanggal yang telah ditentukan sesuai pengumuman sekolah.
                        </p>
                    </div>

                    <div class="instruction">
                        <h4>2. Dokumen yang Harus Dibawa</h4>
                        <p>• Kartu ini (cetak dan bawa saat daftar ulang)<br>• Ijazah/SKHUN asli dan fotokopi<br>• Kartu
                            Keluarga asli dan fotokopi<br>• Akta Kelahiran asli dan fotokopi<br>• Pas foto terbaru 3x4
                            (6 lembar)</p>
                    </div>

                    <div class="instruction">
                        <h4>3. Informasi Lebih Lanjut</h4>
                        <p>Follow Instagram @smpnunggulansindang.official atau hubungi panitia SPMB untuk informasi
                            jadwal dan persyaratan daftar ulang.</p>
                    </div>
                </div>
                @else
                <div class="info-box">
                    <h3>Informasi Lebih Lanjut:</h3>
                    <p>Untuk mendapatkan informasi lebih lanjut silahkan follow Instagram
                        <strong>@smpnunggulansindang.official</strong> atau hubungi panitia SPMB atau kunjungi website
                        resmi SPMB SMPN Unggulan Sindang.</p>
                </div>
                @endif

                <div class="warning-box">
                    <p>HARAP UNTUK TIDAK MENYEBARKAN BUKTI PENDAFTARAN SERTA QR CODE INI KEPADA PIHAK LAIN SELAIN
                        PENDAFTAR & SEKOLAH</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
