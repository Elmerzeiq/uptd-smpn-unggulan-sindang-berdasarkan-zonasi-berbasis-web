@extends('layouts.admin.app')
@section('title', 'Detail Daftar Ulang - ' . ($daftarUlang->user->biodata->nama_lengkap_siswa ??
$daftarUlang->user->name))

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-user-check"></i> Detail Daftar Ulang
            </h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.daftar-ulang.daftar-siswa') }}">Daftar Siswa</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">Detail</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-8">
                {{-- Informasi Siswa --}}
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-user"></i> Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>NISN:</strong> {{ $daftarUlang->user->nisn ?? '-' }}</p>
                                <p><strong>Nama:</strong>
                                    @if($daftarUlang->user->biodata)
                                    {{ $daftarUlang->user->biodata->nama_lengkap_siswa }}
                                    @else
                                    {{ $daftarUlang->user->name }}
                                    @endif
                                </p>
                                <p><strong>Email:</strong> {{ $daftarUlang->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status Daftar Ulang:</strong>
                                    @php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch($daftarUlang->status_daftar_ulang) {
                                    case 'menunggu_verifikasi_berkas':
                                    $statusClass = 'warning';
                                    $statusText = 'Menunggu Verifikasi Berkas';
                                    break;
                                    case 'berkas_diverifikasi':
                                    $statusClass = 'info';
                                    $statusText = 'Berkas Diverifikasi';
                                    break;
                                    case 'berkas_ditolak':
                                    $statusClass = 'danger';
                                    $statusText = 'Berkas Ditolak';
                                    break;
                                    case 'menunggu_verifikasi_pembayaran':
                                    $statusClass = 'warning';
                                    $statusText = 'Menunggu Verifikasi Pembayaran';
                                    break;
                                    case 'pembayaran_ditolak':
                                    $statusClass = 'danger';
                                    $statusText = 'Pembayaran Ditolak';
                                    break;
                                    case 'daftar_ulang_selesai':
                                    $statusClass = 'success';
                                    $statusText = 'Daftar Ulang Selesai';
                                    break;
                                    default:
                                    $statusClass = 'secondary';
                                    $statusText = ucwords(str_replace('_', ' ', $daftarUlang->status_daftar_ulang));
                                    }
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                </p>
                                <p><strong>Status Pembayaran:</strong>
                                    @php
                                    switch($daftarUlang->status_pembayaran) {
                                    case 'belum_bayar':
                                    $paymentClass = 'secondary';
                                    $paymentText = 'Belum Bayar';
                                    break;
                                    case 'menunggu_verifikasi':
                                    $paymentClass = 'warning';
                                    $paymentText = 'Menunggu Verifikasi';
                                    break;
                                    case 'sudah_lunas':
                                    $paymentClass = 'success';
                                    $paymentText = 'Sudah Lunas';
                                    break;
                                    case 'pembayaran_ditolak':
                                    $paymentClass = 'danger';
                                    $paymentText = 'Pembayaran Ditolak';
                                    break;
                                    default:
                                    $paymentClass = 'secondary';
                                    $paymentText = ucwords(str_replace('_', ' ', $daftarUlang->status_pembayaran));
                                    }
                                    @endphp
                                    <span class="badge bg-{{ $paymentClass }}">{{ $paymentText }}</span>
                                </p>
                                <p><strong>Jadwal:</strong>
                                    @if($daftarUlang->jadwal)
                                    {{ $daftarUlang->jadwal->nama_sesi }} -
                                    {{ \Carbon\Carbon::parse($daftarUlang->jadwal->tanggal)->format('d M Y') }}
                                    ({{ $daftarUlang->jadwal->waktu_mulai }} - {{ $daftarUlang->jadwal->waktu_selesai
                                    }})
                                    @else
                                    <span class="text-muted">Belum memilih jadwal</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($daftarUlang->catatan_verifikasi)
                        <div class="alert alert-info mt-3">
                            <strong>Catatan Verifikasi:</strong><br>
                            {{ $daftarUlang->catatan_verifikasi }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Rincian Biaya --}}
                @if($daftarUlang->detailBiaya && $daftarUlang->detailBiaya->count() > 0)
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-money-bill"></i> Rincian Biaya</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Komponen</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($daftarUlang->detailBiaya as $detail)
                                    <tr>
                                        <td>{{ $detail->komponenBiaya->nama_komponen }}</td>
                                        <td>Rp {{ number_format($detail->jumlah_biaya, 0, ',', '.') }}</td>
                                        <td>
                                            @if($detail->komponenBiaya->is_wajib)
                                            <span class="badge bg-danger">Wajib</span>
                                            @else
                                            <span class="badge bg-info">Opsional</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th>Total</th>
                                        <th>Rp {{ number_format($daftarUlang->total_biaya, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Berkas Pendaftaran (Referensi) --}}
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-folder"></i> Berkas Pendaftaran (Referensi)</h5>
                    </div>
                    <div class="card-body">
                        @if($daftarUlang->user->berkas)
                        <div class="row">
                            @foreach($daftarUlang->user->berkas->getAttributes() as $field => $path)
                            @if(str_starts_with($field, 'file_') && !empty($path))
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt me-2"></i>
                                    {{ \Illuminate\Support\Str::title(str_replace('_', ' ', str_replace('file_', '',
                                    $field))) }}:
                                    <a href="{{ Storage::url($path) }}" target="_blank"
                                        class="ms-2 btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted">Tidak ada berkas pendaftaran tersimpan.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel Verifikasi --}}
            <div class="col-lg-4">
                {{-- 1. Verifikasi Berkas --}}
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-file-check"></i> 1. Verifikasi Berkas</h5>
                    </div>
                    <div class="card-body">
                        @if($daftarUlang->file_kartu_lolos_seleksi)
                        <a href="{{ Storage::url($daftarUlang->file_kartu_lolos_seleksi) }}" target="_blank"
                            class="btn btn-primary btn-sm mb-3 w-100">
                            <i class="fas fa-eye me-1"></i>Lihat Kartu Lolos Seleksi
                        </a>

                        @if($daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_berkas')
                        <form action="{{ route('admin.daftar-ulang.verifikasi-berkas', $daftarUlang->id) }}"
                            method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="status" value="diverifikasi">
                            <textarea name="catatan" class="form-control mb-2"
                                placeholder="Catatan verifikasi (opsional)" rows="2"></textarea>
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-check"></i> Setujui Berkas
                            </button>
                        </form>
                        <form action="{{ route('admin.daftar-ulang.verifikasi-berkas', $daftarUlang->id) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="status" value="ditolak">
                            <textarea name="catatan" class="form-control mb-2" placeholder="Alasan penolakan..."
                                required rows="2"></textarea>
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-times"></i> Tolak Berkas
                            </button>
                        </form>
                        @else
                        <div class="alert alert-info">
                            <strong>Status:</strong> {{ $statusText }}<br>
                            @if($daftarUlang->tanggal_verifikasi_berkas)
                            <small>Diverifikasi: {{ $daftarUlang->tanggal_verifikasi_berkas->format('d M Y H:i')
                                }}</small>
                            @endif
                            @if($daftarUlang->verifier)
                            <br><small>Oleh: {{ $daftarUlang->verifier->name }}</small>
                            @endif
                        </div>
                        @endif
                        @else
                        <p class="text-muted">Siswa belum mengunggah Kartu Lolos Seleksi.</p>
                        @endif
                    </div>
                </div>

                {{-- 2. Verifikasi Pembayaran --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-receipt"></i> 2. Verifikasi Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if($daftarUlang->file_bukti_pembayaran)
                        <a href="{{ Storage::url($daftarUlang->file_bukti_pembayaran) }}" target="_blank"
                            class="btn btn-primary btn-sm mb-3 w-100">
                            <i class="fas fa-receipt me-1"></i>Lihat Bukti Pembayaran
                        </a>

                        @if($daftarUlang->status_daftar_ulang === 'berkas_diverifikasi' ||
                        $daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_pembayaran')
                        <form action="{{ route('admin.daftar-ulang.verifikasi-pembayaran', $daftarUlang->id) }}"
                            method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="status" value="lunas">
                            <textarea name="catatan" class="form-control mb-2"
                                placeholder="Catatan verifikasi (opsional)" rows="2"></textarea>
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-check"></i> Pembayaran Lunas
                            </button>
                        </form>
                        <form action="{{ route('admin.daftar-ulang.verifikasi-pembayaran', $daftarUlang->id) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="status" value="ditolak">
                            <textarea name="catatan" class="form-control mb-2" placeholder="Alasan penolakan..."
                                required rows="2"></textarea>
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-times"></i> Tolak Pembayaran
                            </button>
                        </form>
                        @else
                        <div class="alert alert-info">
                            <strong>Status:</strong> {{ $paymentText }}<br>
                            @if($daftarUlang->tanggal_verifikasi_pembayaran)
                            <small>Diverifikasi: {{ $daftarUlang->tanggal_verifikasi_pembayaran->format('d M Y H:i')
                                }}</small>
                            @endif
                            @if($daftarUlang->verifier)
                            <br><small>Oleh: {{ $daftarUlang->verifier->name }}</small>
                            @endif
                        </div>
                        @endif
                        @else
                        <p class="text-muted">Siswa belum mengunggah bukti pembayaran.</p>
                        @endif
                    </div>
                </div>

                {{-- Reset Button --}}
                @if($daftarUlang->status_daftar_ulang !== 'menunggu_verifikasi_berkas')
                <div class="card mt-3">
                    <div class="card-body">
                        <form action="{{ route('admin.daftar-ulang.reset-status', $daftarUlang->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin mereset status daftar ulang ini?')">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                <i class="fas fa-undo"></i> Reset Status
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
