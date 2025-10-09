<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #000;
        }

        .content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        .footer {
            text-align: center;
            padding: 10px;
            border-top: 2px solid #000;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>KARTU PENDAFTARAN SPMB 2025</h2>
            <p>Nomor Kartu: {{ $kartu->nomor_kartu }}</p>
            <p>Tanggal Pembuatan: {{ $kartu->tanggal_pembuatan->format('d M Y') }}</p>
        </div>
        <div class="content">
            <table>
                <tr>
                    <td><strong>Nama Lengkap</strong></td>
                    <td>{{ $kartu->user->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td><strong>NISN</strong></td>
                    <td>{{ $kartu->user->nisn ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <td><strong>Jalur Pendaftaran</strong></td>
                    <td>{{ $kartu->jalur_pendaftaran }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Catatan: Harap simpan kartu ini untuk proses verifikasi.</p>
            <p>Status Verifikasi: {{ $kartu->verified_by_admin ? 'Terverifikasi' : 'Belum Terverifikasi' }}</p>
        </div>
    </div>
</body>

</html>
