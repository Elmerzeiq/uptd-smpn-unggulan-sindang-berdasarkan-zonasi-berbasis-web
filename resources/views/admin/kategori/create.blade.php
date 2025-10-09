@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-plus"></i> Tambah Kategori</h4>
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
                    <a href="{{ route('admin.kategori.index') }}">Kategori</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>Tambah</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Tambah Kategori</div>
                    </div>

                    <form action="{{ route('admin.kategori.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" id="kategori"
                                    class="form-control @error('kategori') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="zonasi" {{ old('kategori')=='zonasi' ? 'selected' : '' }}>
                                        Zonasi
                                    </option>
                                    <option value="afirmasi" {{ old('kategori')=='afirmasi' ? 'selected' : '' }}>
                                        Afirmasi
                                    </option>
                                    <option value="mutasi" {{ old('kategori')=='mutasi' ? 'selected' : '' }}>
                                        Mutasi
                                    </option>
                                    <option value="prestasi" {{ old('kategori')=='prestasi' ? 'selected' : '' }}>
                                        Prestasi
                                    </option>
                                </select>
                                @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Pilih jenis kategori pendaftaran
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" id="deskripsi"
                                    class="form-control @error('deskripsi') is-invalid @enderror" rows="4"
                                    placeholder="Masukkan deskripsi kategori..."
                                    required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan secara singkat tentang kategori ini
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="ketentuan">Ketentuan <span class="text-danger">*</span></label>
                                <textarea name="ketentuan" id="ketentuan"
                                    class="form-control @error('ketentuan') is-invalid @enderror" rows="6"
                                    placeholder="Masukkan ketentuan dan syarat-syarat..."
                                    required>{{ old('ketentuan') }}</textarea>
                                @error('ketentuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan ketentuan dan persyaratan untuk kategori ini
                                </small>
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Simpan
                            </button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Informasi</div>
                    </div>
                    <div class="card-body">
                        <h6><i class="fa fa-info-circle"></i> Panduan Pengisian</h6>
                        <ul class="list-unstyled">
                            <li><strong>Kategori:</strong> Pilih jenis kategori sesuai dengan sistem PPDB</li>
                            <li><strong>Deskripsi:</strong> Berikan penjelasan singkat dan jelas</li>
                            <li><strong>Ketentuan:</strong> Uraikan syarat dan ketentuan yang berlaku</li>
                        </ul>

                        <div class="mt-4">
                            <h6><i class="fa fa-list"></i> Jenis Kategori</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge badge-primary">Zonasi</span>
                                <span class="badge badge-success">Afirmasi</span>
                                <span class="badge badge-warning">Mutasi</span>
                                <span class="badge badge-info">Prestasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection