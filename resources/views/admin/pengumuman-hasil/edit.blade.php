{{-- resources/views/admin/pengumuman-hasil/edit.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Edit Pengumuman Hasil SPMB')
@section('title_header_admin', 'Edit Pengumuman Hasil SPMB')

@section('admin_content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Edit Pengumuman Hasil</h4>
        <ul class="breadcrumbs">
            <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="{{ route('admin.pengumuman-hasil.index') }}">Hasil SPMB</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a>Edit</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fas fa-trophy me-2"></i>Form Edit Pengumuman Hasil</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pengumuman-hasil.update', $pengumumanHasil->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3"><label for="judul" class="form-label fw-semibold">Judul <span
                                    class="text-danger">*</span></label><input id="judul" type="text"
                                class="form-control @error('judul') is-invalid @enderror" name="judul"
                                value="{{ old('judul', $pengumumanHasil->judul) }}" required>@error('judul')<div
                                class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3"><label for="target_penerima"
                                        class="form-label fw-semibold">Target <span
                                            class="text-danger">*</span></label><select id="target_penerima"
                                        name="target_penerima"
                                        class="form-select @error('target_penerima') is-invalid @enderror" required>
                                        <option value="semua" {{ old('target_penerima', $pengumumanHasil->
                                            target_penerima) == 'semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="calon_siswa" {{ old('target_penerima', $pengumumanHasil->
                                            target_penerima) == 'calon_siswa' ? 'selected' : '' }}>Calon Siswa</option>
                                        <option value="siswa_diterima" {{ old('target_penerima', $pengumumanHasil->
                                            target_penerima) == 'siswa_diterima' ? 'selected' : '' }}>Siswa Diterima
                                        </option>
                                        <option value="siswa_ditolak" {{ old('target_penerima', $pengumumanHasil->
                                            target_penerima) == 'siswa_ditolak' ? 'selected' : '' }}>Siswa Ditolak
                                        </option>
                                    </select>@error('target_penerima')<div class="invalid-feedback">{{ $message }}</div>
                                    @enderror</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3"><label for="tanggal" class="form-label fw-semibold">Tanggal
                                        Publikasi</label><input type="datetime-local" id="tanggal" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', $pengumumanHasil->tanggal_input) }}">@error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            </div>
                        </div>
                        <div class="form-group mb-3"><label for="isi" class="form-label fw-semibold">Isi Pengumuman
                                <span class="text-danger">*</span></label><textarea id="isi" name="isi"
                                class="form-control @error('isi') is-invalid @enderror" rows="15"
                                required>{{ old('isi', $pengumumanHasil->isi) }}</textarea>@error('isi')<div
                                class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        <div class="form-group mb-3">
                            <div class="form-check form-switch"><input class="form-check-input" type="checkbox"
                                    id="aktif" name="aktif" value="1" {{ old('aktif', $pengumumanHasil->aktif) ?
                                'checked' : '' }}><label class="form-check-label fw-semibold" for="aktif">Aktifkan
                                    Pengumuman</label></div>
                        </div>
                        <div class="card-action"><button type="submit" class="btn btn-success btn-round"><i
                                    class="fas fa-save me-2"></i>Simpan</button><a
                                href="{{ route('admin.pengumuman-hasil.index') }}"
                                class="btn btn-secondary btn-round">Kembali</a></div>
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
    $(document).ready(function() { $('#isi').summernote({ placeholder: 'Isi pengumuman hasil...', tabsize: 2, height: 300 }); });
</script>
@endpush
