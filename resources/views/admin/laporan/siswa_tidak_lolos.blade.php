@extends('layouts.admin.app')

@section('title', 'Laporan Siswa Tidak Lolos SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-user-times"></i> Laporan Siswa Tidak Lolos SPMB</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4 d-flex justify-content-end">
                    <a href="{{ route('admin.laporan.siswatidaklolos-pdf') }}"
                        class="mb-2 mr-4 btn btn-primary btn-round" target="_blank" style="margin-right: 10px;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>

                    <a href="{{ route('admin.laporan.siswatidaklolos-excel') }}"
                        class="mb-2 mr-4 btn btn-success btn-round" target="_blank" style="margin-right: 10px;">
                        <i class="mr-2 fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fas fa-times-circle"></i> Data Siswa Tidak Lolos</h4>
                        @if($jadwalAktif)
                        <div class="mt-1 card-category">
                            Periode Pendaftaran: <strong>{{ $jadwalAktif->mulai_pendaftaran ?
                                $jadwalAktif->mulai_pendaftaran->format('d M Y H:i') : 'N/A' }}</strong> s/d <strong>{{
                                $jadwalAktif->selesai_pendaftaran ? $jadwalAktif->selesai_pendaftaran->format('d M Y
                                H:i') : 'N/A' }}</strong>.
                            @if($jadwalAktif->mulai_pendaftaran && $jadwalAktif->selesai_pendaftaran)
                            @if(!$jadwalAktif->isPendaftaranOpen())
                            <span class="badge bg-warning ms-2">Periode Belum Dibuka/Sudah Ditutup</span>
                            @else
                            <span class="text-white badge bg-success ms-2">Periode Sedang Berlangsung</span>
                            @endif
                            @endif
                        </div>
                        @else
                        <div class="mt-2 alert alert-warning">
                            Jadwal SPMB untuk pendaftaran belum diatur atau tidak ada yang aktif.
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($pendaftar->isEmpty())
                        <div class="alert alert-info">
                            Tidak ada pendaftar yang tidak lolos ditemukan.
                        </div>
                        @else
                        <div class="mb-3 alert alert-info">
                            <i class="fas fa-info-circle"></i> Total siswa tidak lolos: <strong>{{ $pendaftar->count()
                                }}</strong>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No. Pendaftaran</th>
                                        <th>Nama Lengkap</th>
                                        <th>NISN</th>
                                        <th>Jalur Pendaftaran</th>
                                        <th>Status Pendaftaran</th>
                                        <th>Alasan Tidak Lolos</th>
                                        <th>Asal Sekolah</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Status Berkas</th>
                                        <th>Keterangan Penolakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftar as $user)
                                    @php
                                    $alasanTidakLolos = match($user->status_pendaftaran) {
                                    'tidak_lulus_seleksi' => 'Tidak Lulus Seleksi',
                                    'berkas_tidak_lengkap' => 'Berkas Tidak Lengkap',
                                    'tidak_memenuhi_syarat' => 'Tidak Memenuhi Syarat',
                                    'ditolak' => 'Ditolak',
                                    default => 'Tidak Diketahui'
                                    };

                                    $badgeClass = match($user->status_pendaftaran) {
                                    'tidak_lulus_seleksi' => 'badge-danger',
                                    'berkas_tidak_lengkap' => 'badge-warning',
                                    'tidak_memenuhi_syarat' => 'badge-secondary',
                                    'ditolak' => 'badge-dark',
                                    default => 'badge-light'
                                    };
                                    @endphp
                                    <tr>
                                        <td>{{ $user->no_pendaftaran }}</td>
                                        <td>{{ $user->nama_lengkap }}</td>
                                        <td>{{ $user->nisn ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst($user->jalur_pendaftaran) ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucwords(str_replace('_', ' ', $user->status_pendaftaran)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline-danger">
                                                {{ $alasanTidakLolos }}
                                            </span>
                                        </td>
                                        <td>{{ $user->biodata->asal_sekolah ?? '-' }}</td>
                                        <td>{{ $user->biodata->tempat_lahir ?? '-' }}</td>
                                        <td>{{ $user->biodata->tanggal_lahir ? $user->biodata->tanggal_lahir->format('d
                                            M Y') : '-' }}</td>
                                        <td>
                                            @if($user->berkas)
                                            @php
                                            $statusBerkas = $user->berkas->status_verifikasi ?? 'Belum Diverifikasi';
                                            $berkasBadge = match($statusBerkas) {
                                            'terverifikasi' => 'badge-success',
                                            'ditolak' => 'badge-danger',
                                            'menunggu_verifikasi' => 'badge-warning',
                                            default => 'badge-secondary'
                                            };
                                            @endphp
                                            <span class="badge {{ $berkasBadge }}">
                                                {{ ucwords(str_replace('_', ' ', $statusBerkas)) }}
                                            </span>
                                            @else
                                            <span class="badge badge-light">Tidak Ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->keterangan_penolakan)
                                            <span class="text-danger">{{ $user->keterangan_penolakan }}</span>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
