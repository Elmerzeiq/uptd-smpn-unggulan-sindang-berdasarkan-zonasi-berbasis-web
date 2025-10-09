@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-file-alt"></i> Dokumen Persyaratan</h4>
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
                    <span>Dokumen Persyaratan</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Dokumen Persyaratan</h4>
                            <a href="{{ route('admin.dokumen-persyaratan.create') }}"
                                class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Dokumen Persyaratan
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Filter Kategori -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select id="filterKategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <option value="zonasi">Zonasi</option>
                                    <option value="afirmasi-ketm">Afirmasi (Ketua Masyarakat)</option>
                                    <option value="afirmasi-disabilitas">Afirmasi (Disabilitas)</option>
                                    <option value="perpindahan tugas orang tua">Perpindahan Tugas Orang Tua</option>
                                    <option value="putra/putri guru/tenaga kependidikan">Putra/Putri Guru/Tenaga
                                        Kependidikan</option>
                                    <option value="prestasi-akademik">Prestasi Akademik</option>
                                    <option value="prestasi-non-akademik">Prestasi Non-Akademik</option>
                                    <option value="prestasi-akademik nilai raport">Prestasi Akademik (Nilai Raport)
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="dokumenTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dokumens as $index => $dokumen)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                            $badgeClass = 'badge-primary';
                                            switch($dokumen->kategori) {
                                            case 'zonasi':
                                            $badgeClass = 'badge-primary';
                                            break;
                                            case str_contains($dokumen->kategori, 'afirmasi'):
                                            $badgeClass = 'badge-success';
                                            break;
                                            case str_contains($dokumen->kategori, 'prestasi'):
                                            $badgeClass = 'badge-info';
                                            break;
                                            case str_contains($dokumen->kategori, 'perpindahan'):
                                            $badgeClass = 'badge-warning';
                                            break;
                                            case str_contains($dokumen->kategori, 'putra'):
                                            $badgeClass = 'badge-secondary';
                                            break;
                                            }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucwords(str_replace('-', ' ', $dokumen->kategori)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 400px;">
                                                {{ Str::limit($dokumen->keterangan, 150) }}
                                                @if(strlen($dokumen->keterangan) > 150)
                                                <br>
                                                <button type="button" class="btn btn-link btn-sm p-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $dokumen->id }}">
                                                    Lihat selengkapnya
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.dokumen-persyaratan.edit', $dokumen->id) }}"
                                                    class="btn btn-link btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit Dokumen">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form
                                                    action="{{ route('admin.dokumen-persyaratan.destroy', $dokumen->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-danger"
                                                        data-bs-toggle="tooltip" title="Hapus Dokumen"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen persyaratan ini?')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
                                    @if(strlen($dokumen->keterangan) > 150)
                                    <div class="modal fade" id="detailModal{{ $dokumen->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Detail Keterangan - {{ ucwords(str_replace('-', ' ',
                                                        $dokumen->kategori)) }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ $dokumen->keterangan }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data dokumen persyaratan</h5>
                                                <p class="text-muted">Silakan tambah dokumen persyaratan baru</p>
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

@push('scripts')
<script>
    $(document).ready(function() {
    var table = $('#dokumenTable').DataTable({
        "pageLength": 10,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });
    
    // Filter by kategori
    $('#filterKategori').on('change', function() {
        table.column(1).search(this.value).draw();
    });
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush
@endsection