@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fa fa-calendar me-2"></i>
                Jadwal Pendaftaran SPMB
            </h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>Jadwal Pendaftaran</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title mb-0">Daftar Jadwal Pendaftaran</h4>
                            <a href="{{ route('admin.jadwal-pendaftaran.create') }}"
                                class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Jadwal
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="jadwalTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahap</th>
                                        <th>Kegiatan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwals as $index => $jadwal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $jadwal->tahap }}</span>
                                        </td>
                                        <td>{{ $jadwal->kegiatan }}</td>
                                        <td>
                                            <i class="fa fa-calendar-o me-1"></i>
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar-check-o me-1"></i>
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            @php
                                            $now = \Carbon\Carbon::now();
                                            $mulai = \Carbon\Carbon::parse($jadwal->tanggal_mulai);
                                            $selesai = \Carbon\Carbon::parse($jadwal->tanggal_selesai);
                                            @endphp
                                            @if($now < $mulai) <span class="badge badge-warning">Belum Dimulai</span>
                                                @elseif($now >= $mulai && $now <= $selesai) <span
                                                    class="badge badge-success">Sedang Berlangsung</span>
                                                    @else
                                                    <span class="badge badge-secondary">Selesai</span>
                                                    @endif
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.jadwal-pendaftaran.edit', $jadwal->id) }}"
                                                    class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip"
                                                    title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-link btn-danger delete-btn"
                                                    data-bs-toggle="tooltip" title="Hapus" data-id="{{ $jadwal->id }}"
                                                    data-tahap="{{ $jadwal->tahap }}"
                                                    data-kegiatan="{{ $jadwal->kegiatan }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <form id="delete-form-{{ $jadwal->id }}"
                                                    action="{{ route('admin.jadwal-pendaftaran.destroy', $jadwal->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
    $('#jadwalTable').DataTable({
        "pageLength": 10,
        "searching": true,
        "paging": true,
        "info": true,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });

    // SweetAlert untuk success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    // SweetAlert untuk error message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    @endif

    // SweetAlert untuk konfirmasi hapus
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const tahap = $(this).data('tahap');
        const kegiatan = $(this).data('kegiatan');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus jadwal ini?<br><br>
                   <strong>Tahap:</strong> ${tahap}<br>
                   <strong>Kegiatan:</strong> ${kegiatan}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fa fa-times"></i> Batal',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses permintaan Anda',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush
@endsection
