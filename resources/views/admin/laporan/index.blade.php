@extends('layouts.admin.app')

@section('title', 'Laporan Semua Pendaftar SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-file"></i> Laporan Semua Pendaftar SPMB</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('admin.laporan.all-pdf') }}" class="btn btn-primary btn-round mr-4 mb-2" target="_blank" style="margin-right: 10px;">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>

                    <a href="{{ route('admin.laporan.all-excel') }}" class="btn btn-success  btn-round mr-4 mb-2" target="_blank" style="margin-right: 10px;">
                        <i class="fas fa-file-excel mr-2"></i> Excel
                    </a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Pendaftar</h4>
                        @if($jadwalAktif)
                        <div class="card-category mt-1">
                            Periode Pendaftaran: <strong>{{ $jadwalAktif->mulai_pendaftaran ?
                                $jadwalAktif->mulai_pendaftaran->format('d M Y H:i') : 'N/A' }}</strong> s/d <strong>{{
                                $jadwalAktif->selesai_pendaftaran ? $jadwalAktif->selesai_pendaftaran->format('d M Y
                                H:i') : 'N/A' }}</strong>.
                            @if($jadwalAktif->mulai_pendaftaran && $jadwalAktif->selesai_pendaftaran)
                            @if(!$jadwalAktif->isPendaftaranOpen())
                            <span class="badge bg-warning ms-2">Periode Belum Dibuka/Sudah Ditutup</span>
                            @else
                            <span class="badge bg-success text-white ms-2">Periode Sedang Berlangsung</span>
                            @endif
                            @endif
                        </div>
                        @else
                        <div class="alert alert-warning mt-2">
                            Jadwal SPMB untuk pendaftaran belum diatur atau tidak ada yang aktif.
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($pendaftar->isEmpty())
                        <div class="alert alert-info">
                            Tidak ada pendaftar yang ditemukan. Silakan tambahkan data pendaftar terlebih dahulu.
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Nama Lengkap</th>
                                        <th>NISN</th>
                                        <th>Tempat Lahir</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Nama Sekolah Asal</th>
                                        <th>Jalur Pilihan</th>
                                        <th>Status Pendaftaran</th>
                                        <th>Tanggal Daftar Ulang Selesai</th>
                                        <th>Status Berkas</th>
                                        <th>Nama Ayah</th>
                                        <th>Pekerjaan Ayah</th>
                                        <th>Nama Ibu</th>
                                        <th>Pekerjaan Ibu</th>
                                        <th>Nama Wali</th>
                                        <th>Pekerjaan Wali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftar as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->no_pendaftaran }}</td>
                                        <td>{{ $user->nama_lengkap }}</td>
                                        <td>{{ $user->nisn ?? '-' }}</td>
                                        <td>{{ $user->biodata->tempat_lahir ?? '-' }}</td>
                                        <td>{{ $user->biodata->tgl_lahir ?? '-' }}</td>
                                        <td>{{ $user->biodata->jns_kelamin ?? '-' }}</td>
                                        <td>{{ $user->biodata->asal_sekolah ?? '-' }}</td>
                                        <td>{{ $user->jalur_pendaftaran ?? '-' }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $user->status_pendaftaran)) }}</td>
                                        <td>{{ $user->status_daftar_ulang ?? '-' }}</td>
                                        <td>{{ $user->tanggal_daftar_ulang_selesai ?
                                            $user->tanggal_daftar_ulang_selesai->format('d M Y') : '-' }}</td>
                                        <td>{{ $user->berkas ? $user->berkas->status_verifikasi ?? 'Belum Diverifikasi'
                                            : 'Tidak Ada' }}</td>
                                        <td>{{ $user->orangTua->nama_ayah ?? '-' }}</td>
                                        <td>{{ $user->orangTua->pekerjaan_ayah ?? '-' }}</td>
                                        <td>{{ $user->orangTua->nama_ibu ?? '-' }}</td>
                                        <td>{{ $user->orangTua->pekerjaan_ibu ?? '-' }}</td>
                                        <td>{{ $user->wali ? $user->wali->nama_wali ?? '-' : '-' }}</td>
                                        <td>{{ $user->wali ? $user->wali->pekerjaan_wali ?? '-' : '-' }}</td>


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

@section('styles')
<style>
    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    th:nth-child(1),
    td:nth-child(1) {
        width: 3%;
    }

    /* No */
    th:nth-child(2),
    td:nth-child(2) {
        width: 6%;
    }

    /* No. Pendaftaran */
    th:nth-child(3),
    td:nth-child(3) {
        width: 10%;
    }

    /* Nama Lengkap */
    th:nth-child(4),
    td:nth-child(4) {
        width: 6%;
    }

    /* NISN */
    th:nth-child(5),
    td:nth-child(5) {
        width: 7%;
    }

    /* Tempat Lahir */
    th:nth-child(6),
    td:nth-child(6) {
        width: 7%;
    }

    /* Tanggal Lahir */
    th:nth-child(7),
    td:nth-child(7) {
        width: 8%;
    }

    /* Nama Sekolah Asal */
    th:nth-child(8),
    td:nth-child(8) {
        width: 8%;
    }

    /* Jalur Pilihan */
    th:nth-child(9),
    td:nth-child(9) {
        width: 8%;
    }

    /* Status Pendaftaran */
    th:nth-child(10),
    td:nth-child(10) {
        width: 8%;
    }

    /* Tanggal Daftar Ulang Selesai */
    th:nth-child(11),
    td:nth-child(11) {
        width: 8%;
    }

    /* Status Berkas */
    th:nth-child(12),
    td:nth-child(12) {
        width: 8%;
    }

    /* Nama Ayah */
    th:nth-child(13),
    td:nth-child(13) {
        width: 8%;
    }

    /* Pekerjaan Ayah */
    th:nth-child(14),
    td:nth-child(14) {
        width: 8%;
    }

    /* Nama Ibu */
    th:nth-child(15),
    td:nth-child(15) {
        width: 8%;
    }

    /* Pekerjaan Ibu */
    th:nth-child(16),
    td:nth-child(16) {
        width: 8%;
    }

    /* Nama Wali */
    th:nth-child(17),
    td:nth-child(17) {
        width: 8%;
    }

    /* Pekerjaan Wali */
    @media (max-width: 768px) {

        th,
        td {
            font-size: 10px;
            padding: 6px;
        }
    }
</style>
@endsection
