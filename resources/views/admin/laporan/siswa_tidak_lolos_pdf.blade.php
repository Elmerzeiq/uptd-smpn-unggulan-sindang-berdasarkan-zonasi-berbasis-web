<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Siswa Tidak Lolos</title>
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

        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .status-tidak-lulus {
            background-color: #dc3545;
            color: white;
        }

        .status-berkas-tidak-lengkap {
            background-color: #ffc107;
            color: black;
        }

        .status-tidak-memenuhi {
            background-color: #6c757d;
            color: white;
        }

        .status-ditolak {
            background-color: #343a40;
            color: white;
        }

        .alasan-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 3px;
            font-size: 8px;
            border-radius: 2px;
            color: #721c24;
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
        <h2>LAPORAN SISWA TIDAK LOLOS</h2>
        <h3>SISTEM PENERIMAAN MURID BARU (SPMB)</h3>
        <p>SMP UNGGULAN SINDANG</p>
        <p>TAHUN AJARAN 2025/2026</p>
    </div>

    <div class="table-container">
        <p style="margin-bottom: 5px;">Tanggal Cetak: {{ $tanggalCetak }}</p>
        <p style="margin-bottom: 10px;"><strong>Total Siswa Tidak Lolos: {{ count($data) }} orang</strong></p>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 12%;">No Pendaftaran</th>
                    <th style="width: 15%;">Nama Lengkap</th>
                    <th style="width: 10%;">NISN</th>
                    <th style="width: 10%;">Jalur</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 12%;">Alasan Tidak Lolos</th>
                    <th style="width: 15%;">Asal Sekolah</th>
                    <th style="width: 10%;">Status Berkas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $user)
                @php
                $alasanTidakLolos = match($user->status_pendaftaran) {
                'tidak_lulus_seleksi' => 'Tidak Lulus Seleksi',
                'berkas_tidak_lengkap' => 'Berkas Tidak Lengkap',
                'tidak_memenuhi_syarat' => 'Tidak Memenuhi Syarat',
                'ditolak' => 'Ditolak',
                default => 'Tidak Diketahui'
                };

                $statusClass = match($user->status_pendaftaran) {
                'tidak_lulus_seleksi' => 'status-tidak-lulus',
                'berkas_tidak_lengkap' => 'status-berkas-tidak-lengkap',
                'tidak_memenuhi_syarat' => 'status-tidak-memenuhi',
                'ditolak' => 'status-ditolak',
                default => 'status-tidak-lulus'
                };
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user?->no_pendaftaran ?? '-' }}</td>
                    <td>{{ $user?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $user?->nisn ?? '-' }}</td>
                    <td>{{ ucfirst($user?->jalur_pendaftaran ?? '-') }}</td>
                    <td>
                        <span class="status-badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $user->status_pendaftaran)) }}
                        </span>
                    </td>
                    <td>
                        <div class="alasan-box">
                            {{ $alasanTidakLolos }}
                        </div>
                    </td>
                    <td>{{ $user?->biodata?->asal_sekolah ?? '-' }}</td>
                    <td>{{ $user?->berkas?->status_verifikasi ? ucwords(str_replace('_', ' ',
                        $user->berkas->status_verifikasi)) : 'Tidak Ada' }}</td>
                </tr>
                @if($user->keterangan_penolakan)
                <tr>
                    <td colspan="9"
                        style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 8px; font-style: italic;">
                        <strong>Keterangan Penolakan:</strong> {{ $user->keterangan_penolakan }}
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data siswa tidak lolos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if(count($data) > 0)
        <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
            <h4 style="margin: 0 0 10px 0; font-size: 11px;">Statistik Siswa Tidak Lolos:</h4>
            @php
            $statusCounts = collect($data)->groupBy('status_pendaftaran')->map->count();
            @endphp
            <ul style="margin: 0; padding-left: 20px; font-size: 9px;">
                @foreach($statusCounts as $status => $count)
                <li>{{ ucwords(str_replace('_', ' ', $status)) }}: {{ $count }} orang</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Laporan Siswa Tidak Lolos SPMB - Dicetak oleh sistem pada {{ now()->translatedFormat('d F Y H:i:s') }}</p>
    </div>
</body>

</html>
