@extends('layouts.admin.app')
@section('title', 'Manajemen Ekstrakurikuler')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-basketball-ball"></i> Manajemen Ekstrakurikuler</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>

        {{-- Kalimat pembuka --}}
        <div class="alert alert-info shadow-sm">
            <strong>Informasi:</strong> Ekstrakurikuler di sekolah ini telah meraih berbagai prestasi di tingkat daerah,
            provinsi, hingga nasional.
            Manajemen data yang baik akan membantu pembinaan dan pengembangan potensi siswa, serta menjaga konsistensi
            rekam jejak prestasi yang membanggakan.
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Ekstrakurikuler</h4>
                            <a href="{{ route('admin.ekskul.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Ekskul
                            </a>
                        </div>
                        <form method="GET" action="{{ route('admin.ekskul.index') }}" class="mt-3">
                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <input type="text" name="search_ekskul" class="form-control"
                                        placeholder="Cari nama atau kategori ekskul..."
                                        value="{{ request('search_ekskul') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-primary btn-round w-100">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ekskulTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 10%">Logo</th>
                                        <th>Nama Ekstrakurikuler</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi Singkat</th>
                                        <th style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ekskuls as $index => $ekskul)
                                    <tr>
                                        <td>{{ $ekskuls->firstItem() + $index }}</td>
                                        <td>
                                            @if($ekskul->image)
                                            <img src="{{ Storage::url($ekskul->image) }}"
                                                alt="{{ Str::limit($ekskul->judul, 20) }}" class="img-thumbnail"
                                                style="max-width: 80px; max-height: 50px; object-fit: contain;">
                                            @else
                                            <span class="text-muted">Tidak ada logo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ekskul.show', $ekskul->id) }}"
                                                data-bs-toggle="tooltip" title="Lihat Detail Ekskul">
                                                {{ $ekskul->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $ekskul->kategori ?? '-' }}</td>
                                        <td>{{ Str::limit(strip_tags($ekskul->deskripsi), 70) }}</td>
                                        <td class="text-center">
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.ekskul.show', $ekskul->id) }}"
                                                    data-bs-toggle="tooltip" title="Lihat"
                                                    class="btn btn-link btn-info btn-lg p-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.ekskul.edit', $ekskul->id) }}"
                                                    data-bs-toggle="tooltip" title="Edit"
                                                    class="btn btn-link btn-primary btn-lg p-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-link btn-danger delete-button p-1"
                                                    data-bs-toggle="tooltip" title="Hapus"
                                                    data-form-id="delete-form-ekskul-{{ $ekskul->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <form id="delete-form-ekskul-{{ $ekskul->id }}"
                                                    action="{{ route('admin.ekskul.destroy', $ekskul->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-3">Belum ada data ekstrakurikuler.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $ekskuls->links() }}
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
        // Aktifkan tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Konfirmasi hapus data
        $('#ekskulTable .delete-button').on('click', function(e) {
            e.preventDefault();
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            var itemName = $(this).closest('tr').find('td:nth-child(3) a').text().trim();
            if (!itemName) { itemName = "data ekskul terpilih"; }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Anda akan menghapus ekstrakurikuler: <br><strong>" + itemName + "</strong><br>Tindakan ini tidak dapat dibatalkan.",
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
