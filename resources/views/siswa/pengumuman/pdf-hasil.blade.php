<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Seleksi SPMB - {{ $user->nama_lengkap }}</title>
    <style>
        /* CSS untuk PDF Anda, pastikan font yang dipakai tersedia */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .container {
            border: 2px solid #ddd;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header h2 {
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }

        .status-banner {
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .status-lulus {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-gagal {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        td:first-child {
            font-weight: bold;
            width: 35%;
        }

        .qr-code {
            text-align: center;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>PENGUMUMAN HASIL SELEKSI SPMB</h1>
            <h2>{{ config('app.school_name', 'SMPN UNGGULAN SINDANG') }}</h2>
            <p>TAHUN AJARAN {{ date('Y') }}/{{ date('Y')+1 }}</p>
        </div>

        @if($user->status_pendaftaran === 'lulus_seleksi')
        <div class="status-banner status-lulus">SELAMAT! ANDA DINYATAKAN DITERIMA</div>
        @else
        <div class="status-banner status-gagal">MOHON MAAF, ANDA TIDAK DITERIMA</div>
        @endif

        <h3>Data Calon Siswa:</h3>
        <table>
            <tr>
                <td>No. Pendaftaran</td>
                {{-- PERBAIKAN: Menggunakan optional() untuk menghindari error jika relasi null --}}
                <td>: {{ optional($user->kartuPendaftaran)->nomor_kartu ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>: {{ $user->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>NISN</td>
                {{-- PERBAIKAN: Menggunakan optional() --}}
                <td>: {{ $user->nisn   }}</td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                {{-- PERBAIKAN: Menggunakan optional() --}}
                <td>: {{ $user->biodata->asal_sekolah  }}</td>
            </tr>
        </table>

        <div class="qr-code">
            <p><strong>Scan untuk Verifikasi Keaslian Dokumen</strong></p>
            {{-- Variabel $qrCode dari controller berisi data base64 --}}
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Verifikasi">
        </div>

        @if($user->status_pendaftaran === 'lulus_seleksi')
        <div class="instruction">
            <h4>Langkah Selanjutnya:</h4>
            <ol>
                <li>Segera lakukan daftar ulang sesuai jadwal yang akan diinformasikan.</li>
                <li>Simpan bukti kelulusan ini dan bawa saat melakukan daftar ulang.</li>
                <li>Pantau terus informasi resmi dari sekolah.</li>
            </ol>
        </div>
        @else
        <div class="instruction">
            <h4>Pesan Semangat:</h4>
            <p>Jangan berkecil hati. Kegagalan adalah bagian dari perjalanan menuju kesuksesan. Teruslah berusaha dan
                tetap semangat untuk meraih cita-cita Anda di tempat lain. Kami mendoakan yang terbaik untuk masa depan
                Anda.</p>
        </div>
        @endif

        <div class="footer">
            Dokumen ini dicetak oleh sistem pada {{ now()->format('d F Y H:i:s') }}.
        </div>
    </div>
</body>

</html>
