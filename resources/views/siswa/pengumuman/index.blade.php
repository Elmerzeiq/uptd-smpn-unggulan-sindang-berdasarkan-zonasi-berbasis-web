{{-- resources/views/siswa/pengumuman-hasil/index.blade.php --}}
@extends('layouts.siswa.app')

@section('title', 'Pengumuman Hasil SPMB')
@section('title_header_siswa', 'Pengumuman Hasil SPMB')

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-trophy me-2"></i>Pengumuman Hasil SPMB</h4>
        </div>

        {{-- Status Card Siswa --}}
        <div class="row mb-4">
            <div class="col-md-12">
                @php
                $statusClass = 'warning';
                $statusIcon = 'fas fa-clock';
                if ($user->status_pendaftaran === 'lulus_seleksi') { $statusClass = 'success'; $statusIcon = 'fas
                fa-trophy'; }
                if ($user->status_pendaftaran === 'tidak_lulus') { $statusClass = 'danger'; $statusIcon = 'fas
                fa-heart-broken'; }
                @endphp
                <div class="card border-{{ $statusClass }}">
                    <div class="card-header bg-{{ $statusClass }} text-white">
                        <h5 class="card-title mb-0"><i class="{{ $statusIcon }} me-2"></i>Status Pendaftaran: <strong>{{
                                strtoupper($statusText) }}</strong></h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                @if($user->status_pendaftaran === 'lulus_seleksi')
                                <div class="alert alert-success border-0 mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Selamat, Anda
                                        Diterima!</h6>
                                    <p class="mb-0">Silakan unduh bukti hasil PDF dan persiapkan untuk daftar ulang.</p>
                                </div>
                                @elseif($user->status_pendaftaran === 'tidak_lulus')
                                <div class="alert alert-info border-0 mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-heart me-2"></i>Tetap Semangat!</h6>
                                    <p class="mb-0">Maaf, Anda belum berhasil pada seleksi kali ini. Jangan menyerah!
                                    </p>
                                </div>
                                @else
                                <div class="alert alert-warning border-0 mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-clock me-2"></i>Menunggu Hasil</h6>
                                    <p class="mb-0">Status Anda saat ini: <strong>{{ $statusText }}</strong>. Pantau
                                        terus halaman ini.</p>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4 text-end mt-3 mt-md-0">
                                @if($canDownloadHasil)
                                <a href="{{ route('siswa.pengumuman.download-hasil-pdf') }}" class="btn btn-primary"><i
                                        class="fas fa-download me-2"></i>Download Hasil PDF</a>
                                @else
                                <button class="btn btn-secondary" disabled><i class="fas fa-clock me-2"></i>Hasil Belum
                                    Tersedia</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengumuman List --}}
        @if($pengumumans->isNotEmpty())
        <h4 class="mb-3">Pengumuman Terkait</h4>
        @foreach ($pengumumans as $pengumuman)
        <div class="card mb-3">
            <div class="card-header {{ $pengumuman->tipe_badge_class }} text-white">
                <h5 class="card-title mb-0"><i class="{{ $pengumuman->tipe_icon }} me-2"></i>{{ $pengumuman->judul }}
                </h5>
            </div>
            <div class="card-body">{!! $pengumuman->isi !!}</div>
            <div class="card-footer bg-light"><small class="text-muted">Dipublikasikan pada: {{
                    $pengumuman->tanggal->format('d M Y, H:i') }}</small></div>
        </div>
        @endforeach
        @else
        <div class="text-center py-5"><i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada pengumuman untuk Anda saat ini.</h5>
        </div>
        @endif
    </div>
</div>
@endsection
