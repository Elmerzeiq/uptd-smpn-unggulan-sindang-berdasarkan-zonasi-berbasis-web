@extends('layouts.admin.app')
@section('title', 'Dashboard Proses Seleksi SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Dashboard Proses Seleksi SPMB</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(!$jadwalAktif)
                <div class="shadow alert alert-warning">
                    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h4>
                    <p>Tidak ada jadwal SPMB yang aktif saat ini. Proses seleksi tidak dapat dilakukan.</p>
                    <hr>
                    <a href="{{ route('admin.jadwal-ppdb.index') }}" class="btn btn-warning btn-sm btn-round">Atur
                        Jadwal SPMB</a>
                </div>
                @else
                <div class="shadow-lg card">
                    <div class="card-header">
                        <div class="card-title">Ringkasan Seleksi (Tahun Ajaran: <strong>{{ $jadwalAktif->tahun_ajaran
                                }}</strong>)</div>
                        <div class="mt-1 card-category">
                            Kuota Total: <span class="fw-bold">{{ $jadwalAktif->kuota_total_keseluruhan }}</span> |
                            Total Lulus: <span class="fw-bold text-success">{{ $totalLulus }}</span> |
                            Total Tidak Lulus: <span class="fw-bold text-danger">{{ $totalTidakLulus }}</span> |
                            Sisa Kuota: <span class="fw-bold text-info">{{ $sisaTotalKuota }}</span> |
                            Siap Seleksi: <span class="fw-bold text-primary">{{ $totalSiapSeleksi }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3 text-primary"><i class="fas fa-chart-bar me-2"></i>Statistik per Jalur</h4>
                        <p>Berikut adalah ringkasan hasil seleksi untuk setiap jalur pendaftaran.</p>
                        <div class="row">
                            {{-- @foreach(['domisili', 'prestasi', 'afirmasi', 'mutasi'] as $jalurNama) --}}
                            @foreach(['domisili'] as $jalurNama)
                            @php
                            $stats = $statistikJalur[$jalurNama];
                            $sisaKuotaJalur = $stats['kuota'] - $stats['lulus'];
                            $iconClass = [
                            'domisili' => 'fas fa-map-marked-alt', 'prestasi' => 'fas fa-medal',
                            'afirmasi' => 'fas fa-hands-helping', 'mutasi' => 'fas fa-exchange-alt'
                            ][$jalurNama];
                            $colorClass = [
                            'domisili' => 'icon-primary', 'prestasi' => 'icon-info',
                            'afirmasi' => 'icon-success', 'mutasi' => 'icon-secondary'
                            ][$jalurNama];
                            @endphp
                            <div class="mb-4 col-sm-6 col-lg-3">
                                <div class="border shadow-sm card card-stats card-round h-100">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-icon">
                                                <div class="icon-big text-center {{ $colorClass }} bubble-shadow-small">
                                                    <i class="{{ $iconClass }}"></i>
                                                </div>
                                            </div>
                                            <div class="col col-stats ms-3 ms-sm-0">
                                                <div class="numbers">
                                                    <p class="card-category">Jalur {{ ucfirst($jalurNama) }}</p>
                                                    <h5 class="mb-0 card-title fw-bold">Kuota: {{ $stats['kuota'] }}
                                                    </h5>
                                                    <p class="mb-0"><small>Siap Seleksi: <span class="fw-bold">{{
                                                                $stats['siap_seleksi'] }}</span></small></p>
                                                    <p class="mb-0"><small class="text-success">Lulus: <span
                                                                class="fw-bold">{{ $stats['lulus'] }}</span></small></p>
                                                    <p class="mb-0"><small class="text-danger">Tidak Lulus: <span
                                                                class="fw-bold">{{ $stats['tidak_lulus']
                                                                }}</span></small></p>
                                                    <p class="mb-0"><small
                                                            class="{{ $sisaKuotaJalur >= 0 ? 'text-primary' : 'text-danger' }} fw-bold">Sisa:
                                                            {{ $sisaKuotaJalur }}</small></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 mt-auto text-center card-footer border-top">
                                        <a href="{{ route('admin.seleksi.hasil_jalur', ['jalur' => $jalurNama]) }}"
                                            class="btn btn-sm btn-info btn-round">
                                            <i class="fas fa-eye me-1"></i> Lihat Hasil
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-lg-8">
                                <h4 class="text-success"><i class="fas fa-robot me-2"></i>Seleksi Otomatis</h4>
                                @if($totalSiapSeleksi > 0)
                                <p>Gunakan tombol ini untuk meluluskan semua siswa terbaik di setiap jalur sesuai kuota
                                    secara otomatis. Semua siswa yang tidak masuk kuota akan ditandai "Tidak Lulus".</p>
                                <form action="{{ route('admin.seleksi.proses_otomatis') }}" method="POST"
                                    onsubmit="return confirm('PERINGATAN! Ini akan memproses semua siswa yang belum diseleksi. Siswa yang memenuhi kuota akan LULUS, sisanya TIDAK LULUS. Proses ini tidak bisa diurungkan dengan mudah. Lanjutkan?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg btn-round"><i
                                            class="fas fa-play-circle me-2"></i> Jalankan Seleksi Otomatis
                                        Sekarang</button>
                                </form>
                                <p class="mt-2 text-muted small">
                                    <strong>Penting:</strong> Proses ini akan me-reset status "Lulus" dan "Tidak Lulus"
                                    sebelumnya agar
                                    semua siswa dapat dievaluasi ulang dalam perankingan.
                                </p>
                                @else
                                <div class="alert alert-info"><i class="fas fa-info-circle me-1"></i>Tidak ada pendaftar
                                    yang
                                    siap untuk diproses secara otomatis.</div>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <h4 class="text-warning"><i class="fas fa-undo me-2"></i>Reset Hasil Seleksi</h4>
                                @if($totalLulus > 0 || $totalTidakLulus > 0)
                                <p>Reset semua hasil seleksi dan kembalikan semua siswa ke status "Berkas Diverifikasi".
                                </p>
                                <form action="{{ route('admin.seleksi.reset_hasil') }}" method="POST"
                                    onsubmit="return confirm('PERINGATAN! Ini akan menghapus SEMUA hasil seleksi yang sudah ada dan mengembalikan semua siswa ke status Berkas Diverifikasi. Yakin ingin melanjutkan?');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg btn-round"><i
                                            class="fas fa-undo me-2"></i> Reset Hasil Seleksi</button>
                                </form>
                                <p class="mt-2 text-muted small">
                                    <strong>Gunakan dengan hati-hati:</strong> Semua data hasil seleksi akan hilang.
                                </p>
                                @else
                                <div class="alert alert-info"><i class="fas fa-info-circle me-1"></i>Tidak ada hasil
                                    seleksi yang perlu direset.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
