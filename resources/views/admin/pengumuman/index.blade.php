{{-- resources/views/admin/pengumuman/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Pengumuman Umum')
@section('title_header_admin', 'Pengumuman Umum')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        {{-- Page Header --}}
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-bullhorn me-2"></i>Pengumuman Umum
            </h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Pengumuman Umum</a></li>
            </ul>
        </div>

        {{-- Statistik Cards --}}
        <div class="mb-4 row">
            @php
            $stats = \App\Models\Pengumuman::umum()->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN aktif = 1 AND (tanggal IS NULL OR tanggal <= NOW()) THEN 1 ELSE 0 END) as aktif, SUM(CASE WHEN
                aktif=0 THEN 1 ELSE 0 END) as tidak_aktif, SUM(CASE WHEN aktif=1 AND tanggal> NOW() THEN 1 ELSE 0 END)
                as terjadwal
                ')->first();
                @endphp
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="text-center icon-big icon-primary bubble-shadow-small"><i
                                            class="fas fa-bullhorn"></i></div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Pengumuman</p>
                                        <h4 class="card-title">{{ $stats->total ?? 0 }}</h4>
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
                                    <div class="text-center icon-big icon-success bubble-shadow-small"><i
                                            class="fas fa-check-circle"></i></div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Aktif</p>
                                        <h4 class="card-title">{{ $stats->aktif ?? 0 }}</h4>
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
                                    <div class="text-center icon-big icon-warning bubble-shadow-small"><i
                                            class="fas fa-pause-circle"></i></div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Tidak Aktif</p>
                                        <h4 class="card-title">{{ $stats->tidak_aktif ?? 0 }}</h4>
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
                                    <div class="text-center icon-big icon-info bubble-shadow-small"><i
                                            class="fas fa-clock"></i></div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Terjadwal</p>
                                        <h4 class="card-title">{{ $stats->terjadwal ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        {{-- Main Content --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Daftar Pengumuman Umum</h4>
                            <div><a href="{{ route('admin.pengumuman.create') }}"
                                    class="btn btn-primary btn-sm btn-round"><i class="fas fa-plus me-2"></i>Tambah
                                    Pengumuman</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))<div class="alert alert-success alert-dismissible fade show"><i
                                class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button"
                                class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show"><i
                                class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button"
                                class="btn-close" data-bs-dismiss="alert"></button></div>@endif

                        {{-- Filter & Search --}}
                        <div class="mb-3 row">
                            <div class="col-md-4">
                                <div class="form-group"><label>Filter Status:</label><select class="form-select"
                                        id="filter-status">
                                        <option value="">Semua Status</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak_aktif">Tidak Aktif</option>
                                        <option value="terjadwal">Terjadwal</option>
                                    </select></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label>Filter Tipe:</label><select class="form-select"
                                        id="filter-tipe">
                                        <option value="">Semua Tipe</option>
                                        <option value="info">Info</option>
                                        <option value="warning">Warning</option>
                                        <option value="danger">Danger</option>
                                        <option value="success">Success</option>
                                    </select></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label>Cari Pengumuman:</label><input type="text"
                                        class="form-control" id="search-pengumuman"
                                        placeholder="Cari berdasarkan judul..."></div>
                            </div>
                        </div>

                        @if($pengumumans->isEmpty())
                        <div class="py-5 text-center">
                            <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                            <h5 class="text-muted">Belum ada pengumuman yang dibuat</h5>
                            <p class="mb-3 text-muted">Mulai dengan membuat pengumuman pertama untuk siswa.</p>
                            <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary"><i
                                    class="fas fa-plus me-1"></i>Buat Pengumuman Pertama</a>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="pengumuman-table">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Judul</th>
                                        <th width="10%">Tipe</th>
                                        <th width="15%">Target</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Admin</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengumumans as $index => $pengumuman)
                                    <tr data-status="{{ $pengumuman->status_text }}" data-tipe="{{ $pengumuman->tipe }}"
                                        data-judul="{{ strtolower($pengumuman->judul) }}">
                                        <td>{{ $pengumumans->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i
                                                    class="{{ $pengumuman->tipe_icon }} me-2 text-{{ str_replace(['bg-', ' text-dark'], '', $pengumuman->tipe_badge_class) }}"></i>
                                                <div><strong>{{ Str::limit($pengumuman->judul, 40) }}</strong><br><small
                                                        class="text-muted">{{ Str::limit(strip_tags($pengumuman->isi),
                                                        60) }}</small></div>
                                            </div>
                                        </td>
                                        <td><span class="badge {{ $pengumuman->tipe_badge_class }}">{{
                                                ucwords($pengumuman->tipe) }}</span></td>
                                        <td><span class="badge {{ $pengumuman->target_badge_class }}">{{
                                                $pengumuman->target_text }}</span></td>
                                        <td>
                                            @if($pengumuman->tanggal)
                                            {{ $pengumuman->tanggal->format('d M Y') }}<br><small class="text-muted">{{
                                                $pengumuman->tanggal->format('H:i') }}</small>
                                            @else
                                            <span class="text-muted">Segera</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pengumuman->status_text == 'aktif')<span class="badge bg-success"><i
                                                    class="fas fa-check-circle me-1"></i>Aktif</span>
                                            @elseif($pengumuman->status_text == 'terjadwal')<span
                                                class="badge bg-info"><i class="fas fa-clock me-1"></i>Terjadwal</span>
                                            @else<span class="badge bg-secondary"><i class="fas fa-pause me-1"></i>Tidak
                                                Aktif</span>
                                            @endif
                                        </td>
                                        <td><small>{{ optional($pengumuman->admin)->nama_lengkap ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.pengumuman.show', $pengumuman->id) }}"
                                                    class="btn btn-info btn-sm" data-bs-toggle="tooltip"
                                                    title="Lihat"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}"
                                                    class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                                    title="Edit"><i class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deletePengumuman({{ $pengumuman->id }})"
                                                    data-bs-toggle="tooltip" title="Hapus"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">{{
                            $pengumumans->links('pagination::bootstrap-4') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($pengumumans as $pengumuman)
<form id="delete-form-{{ $pengumuman->id }}" action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}"
    method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection

@push('styles')
<style>
    .card-stats:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    function filterTable() {
        const statusFilter = $('#filter-status').val(), tipeFilter = $('#filter-tipe').val(), searchText = $('#search-pengumuman').val().toLowerCase();
        $('#pengumuman-table tbody tr').each(function() {
            const row = $(this), status = row.data('status'), tipe = row.data('tipe'), judul = row.data('judul');
            let showRow = true;
            if (statusFilter && status !== statusFilter) showRow = false;
            if (tipeFilter && tipe !== tipeFilter) showRow = false;
            if (searchText && !judul.includes(searchText)) showRow = false;
            row.toggle(showRow);
        });
    }
    $('#filter-status, #filter-tipe').on('change', filterTable);
    $('#search-pengumuman').on('keyup', filterTable);
});

function deletePengumuman(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?', text: "Tindakan ini tidak dapat dibatalkan!", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then((result) => { if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); } });
}
</script>
@endpush
