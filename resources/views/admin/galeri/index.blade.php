@extends('layouts.admin.app')
@section('title', 'Manajemen Galeri')
@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-image"> Manajemen Galeri</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Foto Galeri</h4>
                            <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary btn-round ms-auto"><i
                                    class="fa fa-plus"></i> Tambah Foto</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="galleryTable" class="table display table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $index => $item)
                                    <tr>
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ Storage::url($item->image) }}" target="_blank">
                                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}"
                                                    width="100" class="img-thumbnail">
                                            </a>
                                        </td>
                                        <td>{{ $item->judul ?? '-' }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $item->kategori)) }}</td>
                                        <td>{{ Str::limit($item->deskripsi, 50) ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.galeri.edit', $item->id) }}"
                                                    data-bs-toggle="tooltip" title="Edit"
                                                    class="p-1 btn btn-link btn-primary btn-lg"><i
                                                        class="fa fa-edit"></i></a>
                                                {{-- Tombol Hapus dengan SweetAlert --}}
                                                <button type="button" class="p-1 btn btn-link btn-danger delete-button"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    data-form-id="delete-form-galeri-{{ $item->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                {{-- Form Hapus Tersembunyi --}}
                                                <form id="delete-form-galeri-{{ $item->id }}"
                                                    action="{{ route('admin.galeri.destroy', $item->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" data-bs-toggle="tooltip" title="Hapus"
                                                        class="btn btn-link btn-danger"><i
                                                            class="fa fa-times"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="p-3 text-center">Belum ada foto di galeri.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Enable pagination
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $items->links('vendor.pagination.bootstrap-5') }}
                        </div> --}}
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

        $('#galleryTable .delete-button').on('click', function(e) { // Targetkan tabel spesifik
            e.preventDefault();
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            // Ambil judul dari kolom ke-3 (Judul)
            var itemName = $(this).closest('tr').find('td:nth-child(3)').text().trim();
            if (!itemName) { itemName = "foto terpilih"; }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Anda akan menghapus foto: <br><strong>" + itemName + "</strong><br>Tindakan ini tidak dapat dibatalkan.",
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
