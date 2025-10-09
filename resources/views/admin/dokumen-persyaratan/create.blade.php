@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-plus"></i> Tambah Dokumen Persyaratan</h4>
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
                    <a href="{{ route('admin.dokumen-persyaratan.index') }}">Dokumen Persyaratan</a>
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
                        <div class="card-title">Form Tambah Dokumen Persyaratan</div>
                    </div>

                    <form action="{{ route('admin.dokumen-persyaratan.store') }}" method="POST">
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
                                    <option value="afirmasi-ketm" {{ old('kategori')=='afirmasi-ketm' ? 'selected' : ''
                                        }}>
                                        Afirmasi (Ketua Masyarakat)
                                    </option>
                                    <option value="afirmasi-disabilitas" {{ old('kategori')=='afirmasi-disabilitas'
                                        ? 'selected' : '' }}>
                                        Afirmasi (Disabilitas)
                                    </option>
                                    <option value="perpindahan tugas orang tua" {{
                                        old('kategori')=='perpindahan tugas orang tua' ? 'selected' : '' }}>
                                        Perpindahan Tugas Orang Tua
                                    </option>
                                    <option value="putra/putri guru/tenaga kependidikan" {{
                                        old('kategori')=='putra/putri guru/tenaga kependidikan' ? 'selected' : '' }}>
                                        Putra/Putri Guru/Tenaga Kependidikan
                                    </option>
                                    <option value="prestasi-akademik" {{ old('kategori')=='prestasi-akademik'
                                        ? 'selected' : '' }}>
                                        Prestasi Akademik
                                    </option>
                                    <option value="prestasi-non-akademik" {{ old('kategori')=='prestasi-non-akademik'
                                        ? 'selected' : '' }}>
                                        Prestasi Non-Akademik
                                    </option>
                                    <option value="prestasi-akademik nilai raport" {{
                                        old('kategori')=='prestasi-akademik nilai raport' ? 'selected' : '' }}>
                                        Prestasi Akademik (Nilai Raport)
                                    </option>
                                </select>
                                @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Pilih kategori sesuai dengan jenis persyaratan dokumen
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror" rows="8"
                                    placeholder="Masukkan keterangan dokumen persyaratan yang diperlukan..."
                                    required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan secara detail dokumen apa saja yang diperlukan untuk kategori ini
                                </small>
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Simpan
                            </button>
                            <a href="{{ route('admin.dokumen-persyaratan.index') }}" class="btn btn-secondary">
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
                            <li><strong>Kategori:</strong> Pilih sesuai jenis pendaftaran PPDB</li>
                            <li><strong>Keterangan:</strong> Uraikan dokumen-dokumen yang harus disiapkan</li>
                        </ul>

                        <div class="mt-4">
                            <h6><i class="fas fa-file-alt"></i> Contoh Format Keterangan</h6>
                            <div class="alert alert-light">
                                <small style="color: black;">
                                    1. Fotokopi KK<br>
                                    2. Fotokopi Akta Kelahiran<br>
                                    3. Surat Keterangan Domisili<br>
                                    4. Pas foto 3x4 (2 lembar)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-warning">
                    <div class="card-header">
                        <div class="card-title">Kategori Tersedia</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge badge-primary mb-1">Zonasi</span>
                            <span class="badge badge-success mb-1">Afirmasi (KETM)</span>
                            <span class="badge badge-success mb-1">Afirmasi (Disabilitas)</span>
                            <span class="badge badge-secondary mb-1">Perpindahan Tugas</span>
                            <span class="badge badge-secondary mb-1">Putra/Putri GTK</span>
                            <span class="badge badge-info mb-1">Prestasi Akademik</span>
                            <span class="badge badge-info mb-1">Prestasi Non-Akademik</span>
                            <span class="badge badge-info mb-1">Prestasi (Nilai Raport)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
    // Auto-resize textarea
    $('#keterangan').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Preview kategori badge
    $('#kategori').on('change', function() {
        var selectedText = $(this).find('option:selected').text();
        if (selectedText && selectedText !== '-- Pilih Kategori --') {
            console.log('Kategori dipilih: ' + selectedText);
        }
    });
});
</script>
@endpush
@endsection