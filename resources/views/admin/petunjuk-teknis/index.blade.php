@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-book me-2"></i>
                Petunjuk Teknis SPMB
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
                    <span>Petunjuk Teknis</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title mb-0">Daftar Petunjuk Teknis</h4>
                            <a href="{{ route('admin.petunjuk-teknis.create') }}"
                                class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Petunjuk
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="petunjukTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Isi</th>
                                        <th>File PDF</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($petunjuk as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt text-primary me-2"></i>
                                                <span class="fw-bold">{{ $item->judul }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;"
                                                title="{{ $item->isi }}">
                                                {{ Str::limit($item->isi, 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->path_pdf)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <a href="{{ asset('storage/' . $item->path_pdf) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Lihat PDF
                                                </a>
                                            </div>
                                            @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                No PDF
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar-o me-1"></i>
                                            {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            @if($item->path_pdf)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Lengkap
                                            </span>
                                            @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Perlu PDF
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" class="btn btn-link btn-info btn-lg view-btn"
                                                    data-bs-toggle="tooltip" title="Lihat Detail"
                                                    data-judul="{{ $item->judul }}" data-isi="{{ $item->isi }}"
                                                    data-pdf="{{ $item->path_pdf ? asset('storage/' . $item->path_pdf) : '' }}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.petunjuk-teknis.edit', $item->id) }}"
                                                    class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip"
                                                    title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-link btn-danger delete-btn"
                                                    data-bs-toggle="tooltip" title="Hapus" data-id="{{ $item->id }}"
                                                    data-judul="{{ $item->judul }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('admin.petunjuk-teknis.destroy', $item->id) }}"
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
    $('#petunjukTable').DataTable({
        "pageLength": 10,
        "searching": true,
        "paging": true,
        "info": true,
        "responsive": true,
        "order": [[4, "desc"]], // Sort by created date
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

    // SweetAlert untuk lihat detail
    $(document).on('click', '.view-btn', function() {
        const judul = $(this).data('judul');
        const isi = $(this).data('isi');
        const pdf = $(this).data('pdf');

        let pdfButton = '';
        if (pdf) {
            pdfButton = `<br><br><a href="${pdf}" target="_blank" class="btn btn-outline-danger btn-sm">
                           <i class="fas fa-file-pdf me-1"></i> Buka PDF
                         </a>`;
        }

        Swal.fire({
            title: judul,
            html: `<div class="text-start">
                     <p><strong>Isi Petunjuk:</strong></p>
                     <div class="border p-3 rounded bg-light">
                       ${isi.replace(/\n/g, '<br>')}
                     </div>
                     ${pdfButton}
                   </div>`,
            width: '600px',
            showCloseButton: true,
            confirmButtonText: '<i class="fas fa-check"></i> Tutup',
            confirmButtonColor: '#6c757d'
        });
    });

    // SweetAlert untuk konfirmasi hapus
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const judul = $(this).data('judul');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus petunjuk teknis ini?<br><br>
                   <strong>Judul:</strong> ${judul}<br><br>
                   <small class="text-danger">⚠️ File PDF yang terkait juga akan dihapus!</small>`,
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
