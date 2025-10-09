{{-- resources/views/admin/pengumuman-hasil/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Pengumuman Hasil SPMB')
@section('title_header_admin', 'Pengumuman Hasil SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-trophy me-2"></i>Pengumuman Hasil SPMB</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Hasil SPMB</a></li>
            </ul>
        </div>

        <div class="row mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small"><i
                                        class="fas fa-users"></i></div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pendaftar</p>
                                    <h4 class="card-title">{{ $stats['total_pendaftar'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small"><i
                                        class="fas fa-check-circle"></i></div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Diterima</p>
                                    <h4 class="card-title">{{ $stats['diterima'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small"><i
                                        class="fas fa-times-circle"></i></div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Ditolak</p>
                                    <h4 class="card-title">{{ $stats['ditolak'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small"><i
                                        class="fas fa-clock"></i></div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Dalam Proses</p>
                                    <h4 class="card-title">{{ $stats['dalam_proses'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Daftar Pengumuman Hasil</h4><a
                                href="{{ route('admin.pengumuman-hasil.create') }}"
                                class="btn btn-primary btn-sm btn-round"><i class="fas fa-plus me-2"></i>Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))<div class="alert alert-success alert-dismissible fade show"><i
                                class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button"
                                class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show"><i
                                class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button"
                                class="btn-close" data-bs-dismiss="alert"></button></div>@endif

                        @if($pengumumans->isEmpty())
                        <div class="text-center py-4"><i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Pengumuman Hasil</h5><a
                                href="{{ route('admin.pengumuman-hasil.create') }}" class="btn btn-primary"><i
                                    class="fas fa-plus me-1"></i>Buat Pengumuman</a>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Target</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Admin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengumumans as $index => $pengumuman)
                                    <tr>
                                        <td>{{ $pengumumans->firstItem() + $index }}</td>
                                        <td>{{ Str::limit($pengumuman->judul, 40) }}</td>
                                        {{-- Menggunakan accessor `target_text` dan `target_badge_class` --}}
                                        <td><span class="badge {{ $pengumuman->target_badge_class }}">{{
                                                $pengumuman->target_text }}</span></td>
                                        <td>{{ $pengumuman->tanggal ? $pengumuman->tanggal->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td>@if($pengumuman->aktif)<span class="badge bg-success">Aktif</span>@else<span
                                                class="badge bg-warning text-dark">Tidak Aktif</span>@endif</td>
                                        <td>{{ optional($pengumuman->admin)->nama_lengkap ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.pengumuman-hasil.show', $pengumuman->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.pengumuman-hasil.edit', $pengumuman->id) }}"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deletePengumuman({{ $pengumuman->id }})"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $pengumumans->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($pengumumans as $pengumuman)
<form id="delete-form-{{ $pengumuman->id }}" action="{{ route('admin.pengumuman-hasil.destroy', $pengumuman->id) }}"
    method="POST" style="display: none;">@csrf @method('DELETE')</form>
@endforeach
@endsection

@push('scripts')
<script>
    function deletePengumuman(id) {
    swal({
        title: 'Yakin hapus?', text: "Data tidak bisa dikembalikan!", type: 'warning',
        buttons: { cancel: { visible: true, text: 'Batal' }, confirm: { text: 'Ya, Hapus' } }
    }).then(willDelete => { if (willDelete) { document.getElementById('delete-form-' + id).submit(); } });
}
</script>
@endpush
