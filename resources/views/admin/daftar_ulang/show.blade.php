@extends('layouts.admin.app')
@section('title', 'Detail Daftar Ulang - ' . $daftarUlang->user->nama_lengkap)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-lg-7">
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $daftarUlang->user->nama_lengkap }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-{{ $daftarUlang->status_badge['class'] }}">{{
                                $daftarUlang->status_text }}</span></p>
                    </div>
                </div>
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title">Berkas Pendaftaran (Referensi)</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            @if($daftarUlang->user->berkas)
                            @foreach($daftarUlang->user->berkas->getAttributes() as $field => $path)
                            @if(str_starts_with($field, 'file_') && !empty($path))
                            <li>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', str_replace('file_', '',
                                $field))) }}: <a href="{{ Storage::url($path) }}" target="_blank">Lihat</a></li>
                            @endif
                            @endforeach
                            @else
                            <li>Tidak ada berkas pendaftaran.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="card-title">1. Verifikasi Kartu Lolos Seleksi</h5>
                    </div>
                    <div class="card-body">
                        @if($daftarUlang->kartu_lolos_seleksi_file)
                        <a href="{{ Storage::url($daftarUlang->kartu_lolos_seleksi_file) }}" target="_blank"
                            class="btn btn-primary mb-3"><i class="fas fa-eye me-1"></i>Lihat Kartu Lolos</a>
                        @if($daftarUlang->status === 'pending_document_verification')
                        <form action="{{ route('admin.daftar-ulang.verifikasi', $daftarUlang->id) }}" method="POST">
                            @csrf @method('PUT') <input type="hidden" name="aksi" value="verifikasi_dokumen">
                            <button type="submit" class="btn btn-success">Setujui Dokumen</button>
                        </form>
                        <form action="{{ route('admin.daftar-ulang.verifikasi', $daftarUlang->id) }}" method="POST"
                            class="mt-2">
                            @csrf @method('PUT') <input type="hidden" name="aksi" value="tolak_dokumen">
                            <textarea name="catatan_admin" class="form-control mb-2" placeholder="Alasan penolakan..."
                                required></textarea>
                            <button type="submit" class="btn btn-danger">Tolak Dokumen</button>
                        </form>
                        @else
                        <div class="alert alert-info">Status dokumen: <strong>{{ $daftarUlang->status_text }}</strong>
                        </div>
                        @endif
                        @else
                        <p class="text-muted">Siswa belum mengunggah Kartu Lolos Seleksi.</p>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">2. Verifikasi Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if($daftarUlang->bukti_pembayaran_file)
                        <a href="{{ Storage::url($daftarUlang->bukti_pembayaran_file) }}" target="_blank"
                            class="btn btn-primary mb-3"><i class="fas fa-receipt me-1"></i>Lihat Bukti Pembayaran</a>
                        @if($daftarUlang->status === 'pending_payment_verification')
                        <form action="{{ route('admin.daftar-ulang.verifikasi', $daftarUlang->id) }}" method="POST">
                            @csrf @method('PUT') <input type="hidden" name="aksi" value="verifikasi_pembayaran">
                            <button type="submit" class="btn btn-success">Pembayaran Lunas</button>
                        </form>
                        <form action="{{ route('admin.daftar-ulang.verifikasi', $daftarUlang->id) }}" method="POST"
                            class="mt-2">
                            @csrf @method('PUT') <input type="hidden" name="aksi" value="tolak_pembayaran">
                            <textarea name="catatan_admin" class="form-control mb-2" placeholder="Alasan penolakan..."
                                required></textarea>
                            <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                        </form>
                        @else
                        <div class="alert alert-info">Status pembayaran: <strong>{{ $daftarUlang->status_pembayaran
                                }}</strong></div>
                        @endif
                        @else
                        <p class="text-muted">Siswa belum mengunggah bukti pembayaran.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
