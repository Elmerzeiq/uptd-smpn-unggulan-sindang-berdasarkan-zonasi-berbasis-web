{{-- resources/views/siswa/daftar-ulang/pembayaran.blade.php --}}
@extends('layouts.siswa.app')

@section('title', 'Informasi Pembayaran')

@section('siswa_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Pembayaran Daftar Ulang</h4>
                    <a href="{{ route('siswa.daftar-ulang.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">

                    {{-- Informasi Jadwal --}}
                    <div class="card border-info mb-4">
                        <div class="card-header bg-info text-white">
                            <h5><i class="fas fa-calendar"></i> Jadwal Daftar Ulang</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Sesi:</strong> {{ $daftarUlang->jadwal->nama_sesi }}<br>
                                    <strong>Tanggal:</strong> {{
                                    \Carbon\Carbon::parse($daftarUlang->jadwal->tanggal)->format('d M Y') }}<br>
                                    <strong>Waktu:</strong> {{
                                    \Carbon\Carbon::parse($daftarUlang->jadwal->waktu_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($daftarUlang->jadwal->waktu_selesai)->format('H:i') }}
                                </div>
                                <div class="col-md-6">
                                    @if($daftarUlang->jadwal->keterangan)
                                    <strong>Keterangan:</strong><br>
                                    {{ $daftarUlang->jadwal->keterangan }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Biaya --}}
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="fas fa-money-bill"></i> Detail Biaya</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Komponen Biaya</th>
                                            <th>Keterangan</th>
                                            <th class="text-end">Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($daftarUlang->detailBiaya as $detail)
                                        <tr>
                                            <td>{{ $detail->komponenBiaya->nama_komponen }}</td>
                                            <td>{{ $detail->komponenBiaya->keterangan ?? '-' }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->biaya, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-primary">
                                        <tr>
                                            <th colspan="2">Total Pembayaran</th>
                                            <th class="text-end">Rp {{ number_format($daftarUlang->total_biaya, 0, ',',
                                                '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Bank --}}
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h5><i class="fas fa-university"></i> Informasi Rekening Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Bank Utama --}}
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-header">
                                            <h6 class="mb-0">Bank Utama</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td><strong>Bank:</strong></td>
                                                    <td>{{ $bankInfo['utama']['nama'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>No. Rekening:</strong></td>
                                                    <td>
                                                        <span class="badge bg-primary fs-6">{{
                                                            $bankInfo['utama']['nomor_rekening'] }}</span>
                                                        <button class="btn btn-sm btn-outline-secondary ml-2"
                                                            onclick="copyToClipboard('{{ $bankInfo['utama']['nomor_rekening'] }}')">
                                                            <i class="fas fa-copy"></i> Copy
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Atas Nama:</strong></td>
                                                    <td>{{ $bankInfo['utama']['atas_nama'] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bank Alternatif --}}
                                @if(!empty($bankInfo['alternatif']))
                                @foreach($bankInfo['alternatif'] as $bank)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-header">
                                            <h6 class="mb-0">Bank Alternatif {{ $loop->iteration }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td><strong>Bank:</strong></td>
                                                    <td>{{ $bank['nama'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>No. Rekening:</strong></td>
                                                    <td>
                                                        <span class="badge bg-info fs-6">{{ $bank['nomor_rekening']
                                                            }}</span>
                                                        <button class="btn btn-sm btn-outline-secondary ml-2"
                                                            onclick="copyToClipboard('{{ $bank['nomor_rekening'] }}')">
                                                            <i class="fas fa-copy"></i> Copy
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Atas Nama:</strong></td>
                                                    <td>{{ $bank['atas_nama'] }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Petunjuk Pembayaran --}}
                    <div class="card border-warning">
                        <div class="card-header bg-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Petunjuk Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Lakukan pembayaran sesuai dengan total biaya yang tertera</li>
                                <li>Pastikan nama pengirim sesuai dengan nama siswa atau orang tua</li>
                                <li>Simpan bukti pembayaran (struk/screenshot)</li>
                                <li>Upload bukti pembayaran melalui sistem ini</li>
                                <li>Tunggu verifikasi dari admin (maksimal 2x24 jam)</li>
                            </ol>

                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-circle"></i>
                                <strong>Penting:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pembayaran harus dilakukan sebelum batas waktu yang ditentukan</li>
                                    <li>Jika pembayaran tidak sesuai atau terlambat, tempat bisa dialihkan ke siswa lain
                                    </li>
                                    <li>Hubungi panitia jika mengalami kesulitan dalam pembayaran</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    Nomor rekening berhasil dicopy!
                </div>
            </div>
        `;

        // Add to page and show
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove after shown
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    });
}
</script>
@endsection
