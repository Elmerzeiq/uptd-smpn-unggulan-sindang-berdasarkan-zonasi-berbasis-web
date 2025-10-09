{{-- resources/views/admin/jadwal_SPMB/index.blade.php --}}
@extends('layouts.admin.app')
@section('title', 'Manajemen Jadwal SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Manajemen Jadwal SPMB</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Jadwal SPMB</h4>
                            <a href="{{ route('admin.jadwal-ppdb.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Jadwal
                            </a>
                        </div>
                        <form method="GET" action="{{ route('admin.jadwal-ppdb.index') }}" class="mt-3">
                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <input type="text" name="search_jadwal" class="form-control"
                                        placeholder="Cari tahun ajaran..." value="{{ request('search_jadwal') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-primary btn-round w-100"><i
                                            class="fas fa-search"></i> Cari</button>
                                    {{-- <a href="{{ route('admin.jadwal-ppdb.index') }}"
                                        class="btn btn-secondary btn-round"><i class="fas fa-sync-alt"></i> Reset</a>
                                    --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="jadwalPpdbTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Pendaftaran</th>
                                        <th>Pengumuman</th>
                                        <th>Daftar Ulang</th>
                                        <th>Kuota</th>
                                        <th style="width:10%">Status</th>
                                        <th style="width:15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $index => $item)
                                    <tr class="{{ $item->is_active ? 'table-success' : '' }}" data-bs-toggle="tooltip"
                                        title="{{ $item->is_active ? 'Jadwal Sedang Aktif' : '' }}">
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td><strong>{{ $item->tahun_ajaran }}</strong></td>
                                        <td>{{ $item->pembukaan_pendaftaran->format('d M Y H:i') }} <br>s/d<br> {{
                                            $item->penutupan_pendaftaran->format('d M Y H:i') }}</td>
                                        <td>{{ $item->pengumuman_hasil->format('d M Y H:i') }}</td>
                                        <td>{{ $item->mulai_daftar_ulang->format('d M Y H:i') }} <br>s/d<br> {{
                                            $item->selesai_daftar_ulang->format('d M Y H:i') }}</td>
                                        <td>{{ $item->kuota_total_keseluruhan }}</td>
                                        <td>
                                            @if ($item->is_active)
                                            <span class="badge bg-success text-white">Aktif</span>
                                            @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.jadwal-ppdb.edit', $item->id) }}"
                                                    data-bs-toggle="tooltip" title="Edit"
                                                    class="btn btn-link btn-primary btn-lg p-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-link btn-danger delete-button p-1"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    data-form-id="delete-form-jadwal-{{ $item->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <form id="delete-form-jadwal-{{ $item->id }}"
                                                    action="{{ route('admin.jadwal-ppdb.destroy', $item->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center p-3">Belum ada data jadwal SPMB.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $('#jadwalPpdbTable .delete-button').on('click', function(e) {
            e.preventDefault();
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            var itemName = $(this).closest('tr').find('td:nth-child(2)').text().trim(); // Ambil Tahun Ajaran

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Anda akan menghapus Jadwal SPMB untuk Tahun Ajaran: <br><strong>" + itemName + "</strong><br>Tindakan ini tidak dapat dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
