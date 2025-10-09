<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Daftar Ulang</title>
    <link rel="icon" href="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" type="image/x-icon" />
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 15px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.4;
            position: relative;
            width: 100%;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 15px;
        }

        .header .logo {
            width: 60px;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
        }

        .header .logo-right {
            left: auto;
            right: 0;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #0066cc;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 3px 0;
            color: #333;
        }

        .header h3 {
            font-size: 12px;
            font-weight: normal;
            margin: 0 0 8px 0;
            color: #666;
        }

        .header .school-info {
            font-size: 9px;
            color: #777;
            margin: 5px 0 0 0;
            line-height: 1.3;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #0066cc;
        }

        .report-title h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            color: #0066cc;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .info-value {
            color: #666;
        }

        .table-container {
            clear: both;
            margin-top: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .table th,
        .table td {
            border: 1px solid #333;
            padding: 8px 6px;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .table th {
            background-color: #0066cc;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        .table td {
            font-size: 9px;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #f0f8ff;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-terdaftar {
            background-color: #d4edda;
            color: #155724;
        }

        .status-belum {
            background-color: #fff3cd;
            color: #856404;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f4ff;
            border: 1px solid #0066cc;
            border-radius: 5px;
        }

        .summary-title {
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 10px;
            text-align: center;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            flex: 1;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }

        .divider {
            width: 1px;
            background-color: #ccc;
            margin: 0 10px;
        }

        /* Responsive adjustments for print */
        @media print {
            body {
                padding: 10px;
            }

            .table {
                font-size: 8px;
            }

            .table th,
            .table td {
                padding: 4px;
            }
        }
    </style>
</head>

<body>
    <!-- Header dengan Kop Surat -->
    <div class="header">
        @if(isset($logoPemda) && $logoPemda)
        <img src="data:image/png;base64,{{ $logoPemda }}" class="logo" alt="Logo Pemda">
        @endif
        @if(isset($logoSekolah) && $logoSekolah)
        <img src="data:image/png;base64,{{ $logoSekolah }}" class="logo logo-right" alt="Logo Sekolah">
        @endif

        <h1>{{ config('app.sekolah_nama', 'NAMA SEKOLAH') }}</h1>
        <h2>SISTEM PENERIMAAN MURID BARU (SPMB)</h2>
        <h3>Tahun Pelajaran {{ config('app.tahun_ajaran', date('Y').'/'.date('Y', strtotime('+1 year'))) }}</h3>

        <div class="school-info">
            <p>{{ config('app.sekolah_alamat', 'Alamat Sekolah') }}</p>
            <p>Telp: {{ config('app.sekolah_telp', '021-xxxxxxxx') }} | Email: {{ config('app.sekolah_email',
                'sekolah@email.com') }}</p>
            <p>Website: {{ config('app.sekolah_website', 'www.sekolah.sch.id') }}</p>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title">
        <h2>Laporan Daftar Ulang Siswa</h2>
        <p>Data siswa yang telah melakukan proses daftar ulang</p>
    </div>

    <!-- Informasi Laporan -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ isset($tanggalCetak) ? $tanggalCetak : now()->translatedFormat('d F Y')
                }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Data:</span>
            <span class="info-value">{{ isset($daftarUlangs) ? count($daftarUlangs) : 0 }} siswa</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span class="info-value">{{ isset($periode) ? $periode : 'Semua Periode' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ isset($statusFilter) ? ucfirst($statusFilter) : 'Semua Status' }}</span>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Nomor DU</th>
                    <th style="width: 25%;">Nama Lengkap</th>
                    <th style="width: 15%;">NISN</th>
                    <th style="width: 12%;">Jalur</th>
                    <th style="width: 18%;">Jadwal Daftar Ulang</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarUlangs as $index => $daftar)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        <strong>{{ $daftar->nomor_daftar_ulang ?? '-' }}</strong>
                    </td>
                    <td>{{ optional($daftar->user)->nama_lengkap ?? '-' }}</td>
                    <td class="text-center">{{ optional($daftar->user)->nisn ?? '-' }}</td>
                    <td class="text-center">
                        @if(optional($daftar->user)->jalur_pendaftaran)
                        <span style="font-size: 8px; padding: 1px 4px; background-color: #e3f2fd; border-radius: 2px;">
                            {{ ucfirst(optional($daftar->user)->jalur_pendaftaran) }}
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if($daftar->jadwal)
                        <strong>{{ $daftar->jadwal->nama_sesi ?? '-' }}</strong><br>
                        <small style="color: #666;">
                            {{ optional($daftar->jadwal->tanggal_mulai)->format('d/m/Y') ?? '' }}
                            @if($daftar->jadwal->jam_mulai && $daftar->jadwal->jam_selesai)
                            <br>{{ $daftar->jadwal->jam_mulai }} - {{ $daftar->jadwal->jam_selesai }}
                            @endif
                        </small>
                        @else
                        <span class="status-badge status-belum">Belum Pilih Jadwal</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($daftar->status_daftar_ulang === 'selesai')
                        <span class="status-badge status-terdaftar">Selesai</span>
                        @elseif($daftar->status_daftar_ulang === 'proses')
                        <span class="status-badge status-belum">Proses</span>
                        @else
                        <span class="status-badge status-belum">Belum</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #666; font-style: italic;">
                        <strong>Tidak ada data daftar ulang.</strong><br>
                        <small>Belum ada siswa yang melakukan proses daftar ulang.</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary Section -->
    @if(isset($daftarUlangs) && count($daftarUlangs) > 0)
    <div class="summary-section">
        <div class="summary-title">Ringkasan Statistik</div>
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ count($daftarUlangs) }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                <div class="stat-number">
                    {{ collect($daftarUlangs)->where('status_daftar_ulang', 'selesai')->count() }}
                </div>
                <div class="stat-label">Selesai Daftar Ulang</div>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                <div class="stat-number">
                    {{ collect($daftarUlangs)->where('jadwal_id', '!=', null)->count() }}
                </div>
                <div class="stat-label">Sudah Pilih Jadwal</div>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                <div class="stat-number">
                    {{ collect($daftarUlangs)->where('jadwal_id', null)->count() }}
                </div>
                <div class="stat-label">Belum Pilih Jadwal</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>{{ config('app.sekolah_nama', 'NAMA SEKOLAH') }}</strong> |
            Laporan Daftar Ulang SPMB |
            Dicetak pada {{ now()->translatedFormat('l, d F Y \p\u\k\u\l H:i:s') }}
        </p>
        <p style="margin-top: 5px;">
            <em>Dokumen ini dihasilkan secara otomatis oleh sistem dan tidak memerlukan tanda tangan basah</em>
        </p>
    </div>
</body>

</html>

