@extends('layouts.admin.app')
@section('title', 'Laporan Status Berkas Pendaftar')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4><i class="fa fa-file-alt"></i> Laporan Status Berkas</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('admin.laporan.berkas-pdf') }}" class="btn btn-primary btn-round" target="_blank"
                        style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> PDF</a>
                    <a href="{{ route('admin.laporan.berkas-excel') }}" class="btn btn-success btn-round"
                        target="_blank"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Status Berkas Pendaftar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Nama</th>
                                        <th>Jalur</th>
                                        <th>Status Pendaftaran</th>
                                        <th>Status Berkas</th>
                                        <th>Catatan Admin</th>
                                        <th>Diverifikasi Oleh</th>
                                        <th>Tgl Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendaftar as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->no_pendaftaran ?? 'N/A' }}</td>
                                        <td>{{ $user->nama_lengkap }}</td>
                                        <td>{{ $user->jalur_pendaftaran }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $user->status_pendaftaran)) }}</td>
                                        <td>{{ optional($user->berkas)->status_verifikasi ?? 'Belum Upload' }}</td>
                                        <td>{{ optional($user->berkas)->catatan_admin ?? '-' }}</td>
                                        <td>{{ optional(optional($user->berkas)->verifier)->nama_lengkap ?? '-' }}</td>
                                        <td>{{ optional($user->berkas)->verified_at ?
                                            optional($user->berkas)->verified_at->format('d M Y H:i') : '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
