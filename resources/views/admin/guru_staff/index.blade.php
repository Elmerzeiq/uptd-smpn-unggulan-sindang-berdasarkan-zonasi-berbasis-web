@extends('layouts.admin.app') {{-- Menggunakan layout utama Kaiadmin --}}

@section('title', 'Manajemen Guru & Staff') {{-- Judul halaman akan tampil di tab browser --}}

@section('admin_content') {{-- Memulai section konten utama --}}
<div class="container"> {{-- Wrapper konten utama dari Kaiadmin --}}
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-users"> Manajemen Guru & Staff</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Guru & Staff</h4>
                            <a href="{{ route('admin.guru-staff.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Notifikasi success/error sudah dihandle di app.blade.php --}}
                        <div class="table-responsive">
                            <table id="guruStaffTable" class="table display table-striped table-hover">
                                {{-- Isi tabel seperti yang sudah dibuat sebelumnya --}}
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Jabatan</th>
                                        <th>Kategori</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $index => $item)
                                    <tr>
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>
                                            @if($item->image)
                                            <div class="avatar avatar-lg">
                                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama }}"
                                                    class="avatar-img rounded-circle">
                                            </div>
                                            @else
                                            <div class="avatar avatar-lg">
                                                <span class="avatar-title rounded-circle bg-grey1">{{
                                                    strtoupper(substr($item->nama, 0, 1)) }}</span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nip ?? '-' }}</td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ ucfirst(str_replace('_',' ',$item->kategori)) }}</td>
                                        <td class="text-center"> {{-- Sesuaikan class jika perlu --}}
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.guru-staff.edit', $item->id) }}" data-bs-toggle="tooltip" title="Edit"
                                                    class="p-1 btn btn-link btn-primary btn-lg">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                {{-- Tombol Hapus dengan SweetAlert --}}
                                                <button type="button" class="p-1 btn btn-link btn-danger delete-button" data-bs-toggle="tooltip" title="Hapus"
                                                    data-form-id="delete-form-guru-{{ $item->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                {{-- Form Hapus Tersembunyi --}}
                                                <form id="delete-form-guru-{{ $item->id }}" action="{{ route('admin.guru-staff.destroy', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" data-bs-toggle="tooltip" title="Hapus"
                                                        class="btn btn-link btn-danger">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="p-3 text-center">Belum ada data guru atau staff.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="mt-3 d-flex justify-content-center">
                            {{ $items->links('vendor.pagination.bootstrap-5') }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable jika Anda ingin menggunakan fitur-fiturnya
        // $('#guruStaffTable').DataTable({
        //     "pageLength": 10, // Jumlah entri per halaman
        //     // Opsi DataTable lainnya
        // });

        // Inisialisasi Tooltip Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush --}}


@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi Tooltip Bootstrap 5 (jika belum global)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // SweetAlert untuk konfirmasi hapus
        $('#guruStaffTable .delete-button').on('click', function(e) { // Targetkan tombol di dalam tabel spesifik
            e.preventDefault();
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            // Ambil nama dari kolom ke-3 (Nama)
            var itemName = $(this).closest('tr').find('td:nth-child(3)').text().trim();
            if (!itemName) { itemName = "data terpilih"; }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Anda akan menghapus data Guru/Staff: <br><strong>" + itemName + "</strong><br>Tindakan ini tidak dapat dibatalkan.",
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
