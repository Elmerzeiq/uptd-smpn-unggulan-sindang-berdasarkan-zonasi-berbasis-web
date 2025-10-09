@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-route"></i> Alur Pendaftaran</h4>
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
                    <span>Alur Pendaftaran</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Alur Pendaftaran</h4>
                            <a href="{{ route('admin.alur-pendaftaran.create') }}"
                                class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Alur Pendaftaran
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Visual Flow Preview -->
                        @if($alurs->count() > 0)
                        <div class="mb-4">
                            <h5 class="mb-3">Preview Alur Pendaftaran</h5>
                            <div class="row">
                                @foreach($alurs->sortBy('urutan') as $index => $alur)
                                <div class="col-md-2 mb-3">
                                    <div class="card bg-light border-primary">
                                        <div class="card-body text-center p-2">
                                            <div class="badge badge-primary badge-lg mb-2">{{ $alur->urutan }}</div>
                                            <h6 class="card-title text-primary mb-1" style="font-size: 12px;">{{
                                                $alur->nama }}</h6>
                                            <p class="card-text text-muted" style="font-size: 10px;">
                                                {{ Str::limit($alur->keterangan, 50) }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($index < $alurs->count() - 1)
                                        <div class="text-center">
                                            <i class="fas fa-arrow-down text-primary"></i>
                                        </div>
                                        @endif
                                </div>
                                @if(($index + 1) % 6 == 0)
                            </div>
                            <div class="row">
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        @endif

                        <div class="table-responsive">
                            <table id="alurTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Urutan</th>
                                        <th>Nama Langkah</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($alurs->sortBy('urutan') as $index => $alur)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge badge-primary badge-lg">{{ $alur->urutan }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $alur->nama }}</strong>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 300px;">
                                                {{ Str::limit($alur->keterangan, 100) }}
                                                @if(strlen($alur->keterangan) > 100)
                                                <br>
                                                <button type="button" class="btn btn-link btn-sm p-0"
                                                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $alur->id }}">
                                                    Lihat selengkapnya
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($alur->urutan == 1)
                                            <span class="badge badge-success">Langkah Awal</span>
                                            @elseif($alur->urutan == $alurs->max('urutan'))
                                            <span class="badge badge-warning">Langkah Akhir</span>
                                            @else
                                            <span class="badge badge-info">Langkah {{ $alur->urutan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <!-- Move Up Button -->
                                                @if($alur->urutan > 1)
                                                <button type="button"
                                                    class="btn btn-link btn-success btn-sm move-up-btn"
                                                    data-id="{{ $alur->id }}" data-name="{{ $alur->nama }}"
                                                    data-urutan="{{ $alur->urutan }}" data-bs-toggle="tooltip"
                                                    title="Pindah ke Atas">
                                                    <i class="fas fa-arrow-up"></i>
                                                </button>
                                                @endif

                                                <!-- Move Down Button -->
                                                @if($alur->urutan < $alurs->max('urutan'))
                                                    <button type="button"
                                                        class="btn btn-link btn-info btn-sm move-down-btn"
                                                        data-id="{{ $alur->id }}" data-name="{{ $alur->nama }}"
                                                        data-urutan="{{ $alur->urutan }}" data-bs-toggle="tooltip"
                                                        title="Pindah ke Bawah">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                    @endif

                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.alur-pendaftaran.edit', $alur->id) }}"
                                                        class="btn btn-link btn-warning" data-bs-toggle="tooltip"
                                                        title="Edit Alur">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger delete-btn"
                                                        data-id="{{ $alur->id }}" data-name="{{ $alur->nama }}"
                                                        data-urutan="{{ $alur->urutan }}" data-bs-toggle="tooltip"
                                                        title="Hapus Alur">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
                                    @if(strlen($alur->keterangan) > 100)
                                    <div class="modal fade" id="detailModal{{ $alur->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Detail Langkah {{ $alur->urutan }}: {{ $alur->nama }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <strong>Urutan:</strong>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <span class="badge badge-primary badge-lg">{{ $alur->urutan
                                                                }}</span>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <strong>Nama Langkah:</strong>
                                                        </div>
                                                        <div class="col-md-9">
                                                            {{ $alur->nama }}
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <strong>Keterangan:</strong>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <p>{{ $alur->keterangan }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ route('admin.alur-pendaftaran.edit', $alur->id) }}"
                                                        class="btn btn-warning">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-route fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada alur pendaftaran</h5>
                                                <p class="text-muted">Silakan tambah langkah alur pendaftaran baru</p>
                                            </div>
                                        </td>
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

<!-- Hidden Forms for POST requests -->
<form id="moveForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="direction" id="moveDirection">
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#alurTable').DataTable({
            "pageLength": 10,
            "responsive": true,
            "order": [[ 1, "asc" ]], // Sort by urutan column
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
        
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Success message from session
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

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if(session('moved'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Dipindah!',
                text: 'Urutan alur pendaftaran telah diperbarui',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            }).then(function() {
                setTimeout(function() {
                    location.reload();
                }, 1000);
            });
        @endif
        
        // Move Up Button Handler
        $('.move-up-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const urutan = $(this).data('urutan');
            
            Swal.fire({
                title: 'Pindah ke Atas?',
                html: `Apakah Anda yakin ingin memindahkan langkah <strong>"${name}"</strong> dari urutan <strong>${urutan}</strong> ke urutan <strong>${urutan - 1}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-arrow-up"></i> Ya, Pindah ke Atas',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $('#moveForm').attr('action', `/admin/alur-pendaftaran/${id}/move`);
                        $('#moveDirection').val('up');
                        $('#moveForm').submit();
                        resolve();
                    });
                }
            });
        });
        
        // Move Down Button Handler
        $('.move-down-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const urutan = $(this).data('urutan');
            
            Swal.fire({
                title: 'Pindah ke Bawah?',
                html: `Apakah Anda yakin ingin memindahkan langkah <strong>"${name}"</strong> dari urutan <strong>${urutan}</strong> ke urutan <strong>${urutan + 1}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-arrow-down"></i> Ya, Pindah ke Bawah',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $('#moveForm').attr('action', `/admin/alur-pendaftaran/${id}/move`);
                        $('#moveDirection').val('down');
                        $('#moveForm').submit();
                        resolve();
                    });
                }
            });
        });
        
        // Delete Button Handler
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const urutan = $(this).data('urutan');
            
            Swal.fire({
                title: 'Hapus Langkah Alur?',
                html: `
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <p>Apakah Anda yakin ingin menghapus langkah:</p>
                    <div class="alert alert-light">
                        <strong>Urutan ${urutan}: ${name}</strong>
                    </div>
                    <p class="text-muted small">Urutan langkah lainnya akan otomatis disesuaikan setelah penghapusan.</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        // Show loading state
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit form
                        $('#deleteForm').attr('action', `/admin/alur-pendaftaran/${id}`);
                        $('#deleteForm').submit();
                        resolve();
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection