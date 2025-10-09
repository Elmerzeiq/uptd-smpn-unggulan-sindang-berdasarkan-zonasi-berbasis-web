<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Siswa Diterima</title>
    <link rel="icon" href="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" type="image/x-icon" />
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.4;
            position: relative;
            width: 100%;
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

        .header h2,
        .header h3,
        .header p {
            margin: 0;
            padding: 0;
        }

        .table-container {
            clear: both;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        @if($logoPemda)
        <img src="data:image/png;base64,{{ $logoPemda }}" class="logo" alt="Logo Pemda">
        @endif
        @if($logoSekolah)
        <img src="data:image/png;base64,{{ $logoSekolah }}" class="logo logo-right" alt="Logo Sekolah">
        @endif
        <h2>LAPORAN SISWA DITERIMA</h2>
        <h3>SISTEM PENERIMAAN MURID BARU (SPMB)</h3>
    </div>

    <div class="table-container">
        <p style="margin-bottom: 5px;">Tanggal Cetak: {{ $tanggalCetak }}</p>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">No Pendaftaran</th>
                    <th>Nama Lengkap</th>
                    <th style="width: 15%;">NISN</th>
                    <th style="width: 15%;">Jalur Pendaftaran</th>
                    <th>Asal Sekolah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user?->no_pendaftaran ?? '-' }}</td>
                    <td>{{ $user?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $user?->biodata?->nisn ?? '-' }}</td>
                    <td>{{ $user?->jalur_pendaftaran ?? '-' }}</td>
                    <td>{{ $user?->biodata?->asal_sekolah ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data siswa diterima.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan Siswa Diterima SPMB - Dicetak oleh sistem pada {{ now()->translatedFormat('d F Y H:i:s') }}</p>
    </div>
</body>

</html>