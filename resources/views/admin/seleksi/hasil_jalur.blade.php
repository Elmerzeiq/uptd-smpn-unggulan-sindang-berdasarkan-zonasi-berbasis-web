@extends('layouts.admin.app')
@section('title', 'Hasil Seleksi Jalur ' . ucfirst($jalur))

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Hasil Seleksi Jalur: <span class="fw-bold">{{ ucfirst($jalur) }}</span></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="{{ route('admin.seleksi.index') }}">Seleksi</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item active">{{ ucfirst($jalur) }}</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                {{-- Info Ranking Card --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-sort-amount-down me-2"></i> Kriteria Perankingan
                            Jalur {{ ucfirst($jalur) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="alert alert-light border">
                                    <h6><i class="fas fa-info-circle me-2"></i><strong>Urutan Prioritas
                                            Seleksi:</strong></h6>
                                    @if($jalur == 'domisili')
                                    <ol class="mb-0">
                                        <li><strong>Jarak Terdekat</strong> ke sekolah</li>
                                        <li><strong>Usia Tertua</strong> (jika jarak sama)</li>
                                        <li><strong>Waktu Pendaftaran Tercepat</strong> (jika usia sama)</li>
                                    </ol>
                                    @elseif($jalur == 'prestasi')
                                    <ol class="mb-0">
                                        <li><strong>Skor Prestasi Tertinggi</strong></li>
                                        <li><strong>Usia Tertua</strong> (jika skor sama)</li>
                                        <li><strong>Waktu Pendaftaran Tercepat</strong> (jika usia sama)</li>
                                    </ol>
                                    @elseif($jalur == 'afirmasi')
                                    <ol class="mb-0">
                                        <li>Prioritas <strong>Pemegang KIP/PKH</strong> (KETM)</li>
                                        <li>Prioritas <strong>Penyandang Disabilitas</strong></li>
                                        <li><strong>Jarak Terdekat</strong> dari sekolah</li>
                                        <li><strong>Usia Tertua</strong></li>
                                        <li><strong>Waktu Pendaftaran Tercepat</strong></li>
                                    </ol>
                                    @elseif($jalur == 'mutasi')
                                    <ol class="mb-0">
                                        <li>Prioritas <strong>Pindah Tugas Orang Tua</strong></li>
                                        <li>Prioritas <strong>Anak Guru/Tenaga Pendidik</strong></li>
                                        <li><strong>Jarak Terdekat</strong> dari sekolah</li>
                                        <li><strong>Usia Tertua</strong></li>
                                        <li><strong>Waktu Pendaftaran Tercepat</strong></li>
                                    </ol>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Status Kuota Jalur Ini</h6>
                                        <div class="row">
                                            <div class="col-6 border-end">
                                                <h4 class="text-primary mb-0">{{ $kuotaJalurIni }}</h4>
                                                <small>Kuota</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-success mb-0">{{ $pendaftarLulusJalurIni }}</h4>
                                                <small>Lulus</small>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <h5
                                            class="mb-0 text-{{ $sisaKuotaJalurIni > 0 ? 'info' : ($sisaKuotaJalurIni == 0 ? 'warning' : 'danger') }}">
                                            {{ $sisaKuotaJalurIni }}
                                        </h5>
                                        <small class="fw-bold">Sisa Kuota Tersedia</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Peringkat dan Hasil Seleksi</h4>
                            <a href="{{ route('admin.seleksi.index') }}" class="btn btn-secondary btn-round ms-auto">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($sisaKuotaJalurIni < 0) <div class="alert alert-danger" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Peringatan!</strong> Jumlah siswa
                            yang lulus ({{ $pendaftarLulusJalurIni }}) melebihi kuota jalur ini ({{ $kuotaJalurIni }}).
                            Harap periksa kembali atau jalankan ulang seleksi otomatis.
                    </div>
                    @endif

                    @if($calonSiswa->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        Tidak ada pendaftar di jalur ini.
                    </div>
                    @else
                    <div class="alert alert-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Tabel diurutkan berdasarkan kriteria ranking.
                        Status: <span class="badge bg-success">LULUS</span> |
                        <span class="badge bg-danger">TIDAK LULUS</span> |
                        <span class="badge bg-warning text-dark">SIAP SELEKSI</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>No. Pendaftaran</th>
                                    <th>Nama Lengkap</th>

                                    {{-- Kolom Kriteria Ranking --}}
                                    @if($jalur == 'domisili')
                                    <th>Jarak (km)</th>
                                    @elseif($jalur == 'prestasi')
                                    <th>Skor Prestasi</th>
                                    @elseif($jalur == 'afirmasi')
                                    <th>Prioritas</th>
                                    <th>Jarak (km)</th>
                                    @elseif($jalur == 'mutasi')
                                    <th>Prioritas</th>
                                    <th>Jarak (km)</th>
                                    @endif

                                    <th>Usia</th>
                                    <th>Waktu Daftar</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calonSiswa as $index => $calon)
                                @php
                                $globalIndex = ($calonSiswa->currentPage() - 1) * $calonSiswa->perPage() + $index + 1;

                                // Tentukan class row berdasarkan status
                                $rowClass = '';
                                if ($calon->status_pendaftaran === 'lulus_seleksi') {
                                $rowClass = 'table-success';
                                } elseif ($calon->status_pendaftaran === 'tidak_lulus_seleksi') {
                                $rowClass = 'table-danger';
                                }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td><span class="badge bg-dark fs-6">#{{ $globalIndex }}</span></td>
                                    <td><strong>{{ $calon->no_pendaftaran }}</strong></td>
                                    <td>{{ $calon->nama_lengkap }}</td>

                                    {{-- Data Kriteria Ranking --}}
                                    @if($jalur == 'domisili')
                                    <td><span class="badge bg-primary">{{ number_format($calon->jarak_ke_sekolah, 2)
                                            }}</span></td>
                                    @elseif($jalur == 'prestasi')
                                    <td><span class="badge bg-info text-dark">{{ $calon->skor_prestasi ?? 0 }}</span>
                                    </td>
                                    @elseif($jalur == 'afirmasi')
                                    <td>
                                        @if($calon->prioritas_ketm)
                                        <span class="badge bg-success">KETM</span>
                                        @elseif($calon->prioritas_disabilitas)
                                        <span class="badge bg-warning">Disabilitas</span>
                                        @else
                                        <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-primary">{{ number_format($calon->jarak_ke_sekolah, 2)
                                            }}</span></td>
                                    @elseif($jalur == 'mutasi')
                                    <td>
                                        @if($calon->prioritas_pindah_tugas)
                                        <span class="badge bg-info">Pindah Tugas</span>
                                        @elseif($calon->prioritas_anak_guru)
                                        <span class="badge bg-warning">Anak Guru</span>
                                        @else
                                        <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-primary">{{ number_format($calon->jarak_ke_sekolah, 2)
                                            }}</span></td>
                                    @endif

                                    <td><span class="badge bg-dark">{{ $calon->usia }} Thn</span></td>
                                    <td><small>{{ $calon->created_at->format('d/m/y H:i') }}</small></td>
                                    <td class="text-center">
                                        @if($calon->status_pendaftaran === 'lulus_seleksi')
                                        <span class="badge bg-success fs-6"><i
                                                class="fas fa-check-circle me-1"></i>LULUS</span>
                                        @elseif($calon->status_pendaftaran === 'tidak_lulus_seleksi')
                                        <span class="badge bg-danger fs-6"><i class="fas fa-times-circle me-1"></i>TIDAK
                                            LULUS</span>
                                        @else
                                        <span class="badge bg-warning text-dark fs-6"><i
                                                class="fas fa-clock me-1"></i>SIAP SELEKSI</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 d-flex justify-content-center">{{ $calonSiswa->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
