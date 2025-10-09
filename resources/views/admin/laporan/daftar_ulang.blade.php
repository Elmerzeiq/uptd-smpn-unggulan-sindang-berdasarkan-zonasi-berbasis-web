@extends('layouts.admin.app')
@section('title', 'Laporan Daftar Ulang Siswa')
@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4><i class="fa fa-clipboard-check"></i> Laporan Daftar Ulang</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-4 d-flex justify-content-end">
                    <a href="{{ route('admin.laporan.daftar-ulang-pdf') }}" class="btn btn-primary btn-round"
                        target="_blank" style="margin-right: 10px;"><i class="fas fa-file-pdf"></i> PDF</a>
                    <a href="{{ route('admin.laporan.daftar-ulang-excel') }}" class="btn btn-success btn-round"
                        target="_blank"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Proses Daftar Ulang</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. DU</th>
                                        <th>Nama</th>
                                        <th>Status Dokumen</th>
                                        <th>Status Bayar</th>
                                        <th>Jadwal</th>
                                        <th>Kehadiran</th>
                                        <th>Tgl Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($daftarUlangs as $index => $du)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $du->nomor_daftar_ulang }}</td>
                                        <td>{{ $du->user->nama_lengkap }}</td>
                                        <td>{{ $du->status_text }}</td>
                                        <td>{{ $du->status_pembayaran_text }}</td>
                                        <td>{{ optional($du->jadwalDaftarUlang)->nama_sesi ?? 'Belum Pilih' }}</td>
                                        <td>{!! $du->hadir_daftar_ulang ? '<span class="badge bg-success">Hadir</span>'
                                            : '<span class="badge bg-secondary">Belum</span>' !!}</td>
                                        <td>{{ $du->status == 'selesai' ? optional($du->waktu_kehadiran)->format('d M
                                            Y') : '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data.</td>
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
