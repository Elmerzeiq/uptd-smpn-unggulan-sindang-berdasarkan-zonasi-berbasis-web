@extends('layouts.admin.app')
@section('title', 'Manajemen Berita Sekolah')

@section('admin_content')
<div class="container"> {{-- Pastikan ini adalah class wrapper konten utama dari layout Anda --}}
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-newspaper"> Manajemen Berita Sekolah</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Berita</h4>
                            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tulis Berita Baru
                            </a>
                        </div>
                        <form method="GET" action="{{ route('admin.berita.index') }}" class="mt-3">
                            <div class="row">
                                <div class="col-md-5 form-group">
                                    <input type="text" name="search_berita" class="form-control"
                                        placeholder="Cari judul berita..." value="{{ request('search_berita') }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <select name="status_berita" class="form-select"> {{-- Gunakan form-select untuk
                                        Bootstrap 5 --}}
                                        <option value="">Semua Status</option>
                                        @if(isset($statusOptions)) {{-- Pastikan $statusOptions dikirim dari controller
                                        --}}
                                        @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" {{ request('status_berita')==$status ? 'selected'
                                            : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-primary btn-round"><i
                                            class="fas fa-search"></i> Cari</button>
                                    <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary btn-round"><i
                                            class="fas fa-sync-alt"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="beritaTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 10%">Gambar</th>
                                        <th>Judul</th>
                                        <th style="width: 10%">Tanggal</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 15%">Author</th>
                                        <th style="width: 15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $index => $item)
                                    <tr>
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>
                                            @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}"
                                                alt="{{ Str::limit($item->judul, 20) }}" class="img-thumbnail"
                                                style="width: 80px; height: 50px; object-fit: cover;">
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.berita.show', $item->id) }}" data-bs-toggle="tooltip"
                                                title="Lihat Detail Berita">
                                                {{ Str::limit($item->judul, 60) }}
                                            </a>
                                        </td>
                                        <td>{{ $item->tanggal ? $item->tanggal->format('d M Y') : '-' }}</td>
                                        <td>
                                            @if($item->status == 'published')
                                            <span class="badge bg-success text-white">Published</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->author->nama_lengkap ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.berita.show', $item->id) }}" data-bs-toggle="tooltip"
                                                    title="Lihat" class="btn btn-link btn-info btn-lg p-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.berita.edit', $item->id) }}" data-bs-toggle="tooltip"
                                                    title="Edit" class="btn btn-link btn-primary btn-lg p-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                {{-- Tombol Hapus yang dimodifikasi --}}
                                                <button type="button" class="btn btn-link btn-danger delete-button p-1"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    data-form-id="delete-form-{{ $item->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                {{-- Form Hapus Tersembunyi --}}
                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('admin.berita.destroy', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center p-3">Belum ada berita.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $items->links() }} {{-- Pastikan pagination Bootstrap 5
                            --}}
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
        // Inisialisasi Tooltip Bootstrap 5
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // SweetAlert untuk konfirmasi hapus
        $('.delete-button').on('click', function(e) {
            e.preventDefault(); // Mencegah aksi default jika ada
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            // Mencoba mengambil judul berita dari kolom ke-3 (<td>Judul</td>)
            // Anda mungkin perlu menyesuaikan selector ini jika struktur tabel berubah
            var itemName = $(this).closest('tr').find('td:nth-child(3) a').text().trim();
            if (!itemName) { // Fallback jika tidak ada link atau judul sulit diambil
                itemName = "data terpilih";
            }


            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Anda akan menghapus berita: <br><strong>" + itemName + "</strong><br>Tindakan ini tidak dapat dibatalkan.", // Menggunakan html untuk bold
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // Biru
                cancelButtonColor: '#d33',   // Merah
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form tersembunyi jika dikonfirmasi
                }
            });
        });
    });
</script>
@endpush
