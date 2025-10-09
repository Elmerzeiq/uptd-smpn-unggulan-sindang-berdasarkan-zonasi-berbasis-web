{{-- resources/views/admin/pengumuman/edit.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Edit Pengumuman')
@section('title_header_admin', 'Edit Pengumuman')

@section('admin_content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Edit Pengumuman</h4>
        <ul class="breadcrumbs">
            <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="{{ route('admin.pengumuman.index') }}">Pengumuman</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a>Edit Pengumuman</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-edit me-2"></i>Form Edit Pengumuman
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="judul" class="form-label fw-semibold">Judul Pengumuman <span
                                            class="text-danger">*</span></label>
                                    <input id="judul" type="text"
                                        class="form-control @error('judul') is-invalid @enderror" name="judul"
                                        value="{{ old('judul', $pengumuman->judul) }}" required>
                                    @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="isi" class="form-label fw-semibold">Isi Pengumuman <span
                                    class="text-danger">*</span></label>
                            <textarea id="isi" name="isi" class="form-control @error('isi') is-invalid @enderror"
                                rows="8" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                            @error('isi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="tipe" class="form-label fw-semibold">Tipe Pengumuman <span
                                            class="text-danger">*</span></label>
                                    <select id="tipe" name="tipe"
                                        class="form-select @error('tipe') is-invalid @enderror" required>
                                        <option value="info" {{ old('tipe', $pengumuman->tipe) == 'info' ? 'selected' :
                                            '' }}>Info</option>
                                        <option value="warning" {{ old('tipe', $pengumuman->tipe) == 'warning' ?
                                            'selected' : '' }}>Warning</option>
                                        <option value="danger" {{ old('tipe', $pengumuman->tipe) == 'danger' ?
                                            'selected' : '' }}>Danger</option>
                                        <option value="success" {{ old('tipe', $pengumuman->tipe) == 'success' ?
                                            'selected' : '' }}>Success</option>
                                    </select>
                                    @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="tanggal" class="form-label fw-semibold">Tanggal & Waktu Tayang</label>
                                    <input type="datetime-local" id="tanggal" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', $pengumuman->tanggal_input) }}">
                                    @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label for="target_penerima" class="form-label fw-semibold">Target Penerima <span
                                            class="text-danger">*</span></label>
                                    <select id="target_penerima" name="target_penerima"
                                        class="form-select @error('target_penerima') is-invalid @enderror" required>
                                        <option value="semua" {{ old('target_penerima', $pengumuman->target_penerima) ==
                                            'semua' ? 'selected' : '' }}>
                                            Semua Pengguna
                                        </option>
                                        <option value="calon_siswa" {{ old('target_penerima', $pengumuman->
                                            target_penerima) == 'calon_siswa' ? 'selected' : '' }}>
                                            Calon Siswa
                                        </option>
                                        <option value="siswa_diterima" {{ old('target_penerima', $pengumuman->
                                            target_penerima) == 'siswa_diterima' ? 'selected' : '' }}>
                                            Siswa Diterima
                                        </option>
                                        <option value="siswa_ditolak" {{ old('target_penerima', $pengumuman->
                                            target_penerima) == 'siswa_ditolak' ? 'selected' : '' }}>
                                            Siswa Ditolak
                                        </option>
                                    </select>
                                    @error('target_penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('aktif') is-invalid @enderror" type="checkbox"
                                    id="aktif" name="aktif" value="1" {{ old('aktif', $pengumuman->aktif) ? 'checked' :
                                '' }}>
                                <label class="form-check-label fw-semibold" for="aktif">Aktifkan Pengumuman</label>
                                @error('aktif')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary btn-round">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary btn-round">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#isi').summernote({
            placeholder: 'Isi pengumuman...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>
@endpush
