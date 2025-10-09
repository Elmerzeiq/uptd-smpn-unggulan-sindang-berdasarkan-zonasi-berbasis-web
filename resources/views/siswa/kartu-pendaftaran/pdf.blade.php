<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $isFinalCard ? 'Kartu Final' : 'Kartu Pendaftaran' }} - {{ $kartu->nomor_kartu }}</title>
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            border: 2px solid {
                    {
                    $isFinalCard ? '#157347': '#0a58ca'
                }
            }

            ;
            border-radius: 10px;
            padding: 0;
            width: 100%;
        }

        .header {
            background-color: {
                    {
                    $isFinalCard ? '#198754': '#0d6efd'
                }
            }

            ;
            color: white;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .header h1 {
            font-size: 22px;
            margin: 0;
        }

        .header h2 {
            font-size: 16px;
            margin: 5px 0 0;
            font-weight: normal;
        }

        .content {
            padding: 25px;
        }

        .footer {
            padding: 20px;
            background-color: #f8f9fa;
            text-align: center;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
        }

        .table-data td {
            padding: 8px;
            vertical-align: top;
        }

        .table-data .label {
            font-weight: bold;
            color: #555;
            width: 150px;
        }

        .table-data .value {
            font-weight: normal;
        }

        .qr-section {
            text-align: center;
        }

        .qr-code {
            width: 130px;
            height: 130px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }

        .photo {
            width: 100px;
            height: 130px;
            border: 1px solid #ddd;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .data-grid {
            width: 100%;
        }

        .data-col-left {
            width: 70%;
            padding-right: 20px;
        }

        .data-col-right {
            width: 30%;
        }

        .info-box {
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        .info-success {
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
            color: #0f5132;
        }

        .info-warning {
            background-color: #fff3cd;
            border: 1px solid #ffecb5;
            color: #664d03;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-lulus {
            background-color: #198754;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
        }

        .status-error {
            background-color: #dc3545;
            color: white;
        }

        .status-info {
            background-color: #0dcaf0;
            color: #333;
        }

        h3 {
            margin-top: 0;
            font-size: 14px;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>SMPN UNGGULAN SINDANG</h1>
            <h2>{{ $isFinalCard ? 'KARTU FINAL PENERIMAAN SISWA BARU' : 'KARTU BUKTI PENDAFTARAN' }}</h2>
            <p style="margin-top: 5px; font-size: 11px;">Tahun Pelajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
        </div>

        <div class="content">
            @if($isFinalCard)
            <div class="info-box info-success">
                <h3 style="font-size: 16px;">SELAMAT! ANDA DINYATAKAN DITERIMA</h3>
            </div>
            @endif

            <table class="data-grid">
                <tr>
                    <td class="data-col-left">
                        <table class="table-data">
                            <tr>
                                <td class="label">No. Pendaftaran</td>
                                <td class="value">: <strong>{{ $kartu->nomor_kartu }}</strong></td>
                            </tr>
                            <tr>
                                <td class="label">Nama Lengkap</td>
                                <td class="value">: {{ strtoupper($user->nama_lengkap) }}</td>
                            </tr>
                            <tr>
                                <td class="label">NISN</td>
                                <td class="value">: {{ $user->nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Tempat, Tgl Lahir</td>
                                <td class="value">: {{ $user->biodata->tempat_lahir ?? '-' }}, {{
                                    $user->biodata->tgl_lahir ?
                                    \Carbon\Carbon::parse($user->biodata->tgl_lahir)->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Asal Sekolah</td>
                                <td class="value">: {{ $user->biodata->asal_sekolah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Alamat</td>
                                <td class="value">: {{ $user->biodata->alamat_rumah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Jalur Pendaftaran</td>
                                <td class="value">: {{ strtoupper($kartu->jalur_pendaftaran) }}</td>
                            </tr>
                            <tr>
                                <td class="label">Status</td>
                                <td class="value">:
                                    @php
                                    $statusConfig = [
                                    'belum_diverifikasi' => ['class' => 'pending', 'text' => 'BELUM DIVERIFIKASI'],
                                    'menunggu_kelengkapan_data' => ['class' => 'pending', 'text' => 'MENUNGGU
                                    KELENGKAPAN DATA'],
                                    'menunggu_verifikasi_berkas' => ['class' => 'pending', 'text' => 'MENUNGGU
                                    VERIFIKASI BERKAS'],
                                    'berkas_tidak_lengkap' => ['class' => 'error', 'text' => 'BERKAS TIDAK LENGKAP'],
                                    'berkas_diverifikasi' => ['class' => 'info', 'text' => 'BERKAS DIVERIFIKASI'],
                                    'lulus_seleksi' => ['class' => 'lulus', 'text' => 'DITERIMA'],
                                    'tidak_lulus_seleksi' => ['class' => 'error', 'text' => 'TIDAK LULUS'],
                                    'mengundurkan_diri' => ['class' => 'error', 'text' => 'MENGUNDURKAN DIRI'],
                                    'daftar_ulang_selesai' => ['class' => 'lulus', 'text' => 'DAFTAR ULANG SELESAI'],
                                    ];
                                    $status = $statusConfig[$user->status_pendaftaran] ?? ['class' => 'pending', 'text'
                                    => strtoupper(str_replace('_', ' ', $user->status_pendaftaran))];
                                    @endphp
                                    <span class="status-badge status-{{ $status['class'] }}">{{ $status['text']
                                        }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="data-col-right qr-section">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code" class="qr-code">
                        <p style="font-size: 10px; margin-top: 5px; color: #777;">Dicetak pada: {{ now()->format('d M Y
                            H:i') }}</p>
                    </td>
                </tr>
            </table>

            @if($isFinalCard)
            <hr>
            <div>
                <h3>INSTRUKSI DAFTAR ULANG</h3>
                <ol style="font-size: 11px; padding-left: 20px;">
                    <li>Segera lakukan daftar ulang sesuai dengan jadwal yang telah diumumkan oleh panitia SPMB.</li>
                    <li>Cetak dan bawa Kartu Final ini saat melakukan daftar ulang.</li>
                    <li>Siapkan dokumen persyaratan lainnya seperti Ijazah/SKL, Kartu Keluarga, dan Akta Kelahiran.</li>
                    <li>Informasi lebih lanjut dapat dilihat di website resmi sekolah atau hubungi panitia SPMB.</li>
                </ol>
            </div>
            @endif
        </div>

        <div class="footer">
            <div class="info-box info-warning" style="margin-top:0;">
                <p style="margin:0; font-size:11px; font-weight: bold;">
                    PERHATIAN: Kartu ini adalah dokumen rahasia. Dilarang keras menyebarluaskan kartu ini dan QR Code di
                    dalamnya.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
