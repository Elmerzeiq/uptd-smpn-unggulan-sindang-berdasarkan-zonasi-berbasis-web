@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-edit"></i> Edit Dokumen Persyaratan</h4>
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
                    <span>Edit</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Form Edit Dokumen Persyaratan:
                            @php
                            $badgeClass = 'badge-primary';
                            switch($dokumenPersyaratan->kategori) {
                            case 'zonasi':
                            $badgeClass = 'badge-primary';
                            break;
                            case str_contains($dokumenPersyaratan->kategori, 'afirmasi'):
                            $badgeClass = 'badge-success';
                            break;
                            case str_contains($dokumenPersyaratan->kategori, 'prestasi'):
                            $badgeClass = 'badge-info';
                            break;
                            case str_contains($dokumenPersyaratan->kategori, 'perpindahan'):
                            $badgeClass = 'badge-warning';
                            break;
                            case str_contains($dokumenPersyaratan->kategori, 'putra'):
                            $badgeClass = 'badge-secondary';
                            break;
                            }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucwords(str_replace('-', ' ', $dokumenPersyaratan->kategori)) }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('admin.dokumen-persyaratan.update', $dokumenPersyaratan->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
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
                                    <option value="zonasi" {{ old('kategori', $dokumenPersyaratan->kategori) == 'zonasi'
                                        ? 'selected' : '' }}>
                                        Zonasi
                                    </option>
                                    <option value="afirmasi-ketm" {{ old('kategori', $dokumenPersyaratan->kategori) ==
                                        'afirmasi-ketm' ? 'selected' : '' }}>
                                        Afirmasi (Ketua Masyarakat)
                                    </option>
                                    <option value="afirmasi-disabilitas" {{ old('kategori', $dokumenPersyaratan->
                                        kategori) == 'afirmasi-disabilitas' ? 'selected' : '' }}>
                                        Afirmasi (Disabilitas)
                                    </option>
                                    <option value="perpindahan tugas orang tua" {{ old('kategori', $dokumenPersyaratan->
                                        kategori) == 'perpindahan tugas orang tua' ? 'selected' : '' }}>
                                        Perpindahan Tugas Orang Tua
                                    </option>
                                    <option value="putra/putri guru/tenaga kependidikan" {{ old('kategori',
                                        $dokumenPersyaratan->kategori) == 'putra/putri guru/tenaga kependidikan' ?
                                        'selected' : '' }}>
                                        Putra/Putri Guru/Tenaga Kependidikan
                                    </option>
                                    <option value="prestasi-akademik" {{ old('kategori', $dokumenPersyaratan->kategori)
                                        == 'prestasi-akademik' ? 'selected' : '' }}>
                                        Prestasi Akademik
                                    </option>
                                    <option value="prestasi-non-akademik" {{ old('kategori', $dokumenPersyaratan->
                                        kategori) == 'prestasi-non-akademik' ? 'selected' : '' }}>
                                        Prestasi Non-Akademik
                                    </option>
                                    <option value="prestasi-akademik nilai raport" {{ old('kategori',
                                        $dokumenPersyaratan->kategori) == 'prestasi-akademik nilai raport' ? 'selected'
                                        : '' }}>
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
                                    required>{{ old('keterangan', $dokumenPersyaratan->keterangan) }}</textarea>
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
                                <i class="fa fa-check"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.dokumen-persyaratan.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="card-title">Detail Dokumen</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $dokumenPersyaratan->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucwords(str_replace('-', ' ', $dokumenPersyaratan->kategori)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $dokumenPersyaratan->created_at ? $dokumenPersyaratan->created_at->format('d/m/Y
                                    H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diperbarui:</strong></td>
                                <td>{{ $dokumenPersyaratan->updated_at ? $dokumenPersyaratan->updated_at->format('d/m/Y
                                    H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Preview Keterangan</div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light">
                            <h6>Keterangan Saat Ini:</h6>
                            <div style="max-height: 200px; overflow-y: auto;">
                                {{ $dokumenPersyaratan->keterangan }}
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6><i class="fa fa-info-circle"></i> Tips Pengeditan</h6>
                            <ul class="list-unstyled">
                                <li><strong>Format:</strong> Gunakan numbering untuk daftar dokumen</li>
                                <li><strong>Detail:</strong> Sertakan ukuran file atau format jika perlu</li>
                                <li><strong>Catatan:</strong> Tambahkan keterangan khusus jika ada</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card card-success">
                    <div class="card-header">
                        <div class="card-title">Statistik</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="text-success">{{ strlen($dokumenPersyaratan->keterangan) }}</h4>
                                    <small class="text-muted">Karakter</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <h4 class="text-info">{{ str_word_count($dokumenPersyaratan->keterangan) }}</h4>
                                    <small class="text-muted">Kata</small>
                                </div>
                            </div>
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
    function resizeTextarea() {
        $('#keterangan').each(function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
    
    // Initial resize
    resizeTextarea();
    
    // Resize on input
    $('#keterangan').on('input', function() {
        resizeTextarea();
        
        // Update character and word count
        var text = $(this).val();
        var charCount = text.length;
        var wordCount = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
        
        // You can add live counter here if needed
        console.log('Characters: ' + charCount + ', Words: ' + wordCount);
    });
    
    // Preview kategori badge change
    $('#kategori').on('change', function() {
        var selectedText = $(this).find('option:selected').text();
        if (selectedText && selectedText !== '-- Pilih Kategori --') {
            console.log('Kategori diubah ke: ' + selectedText);
        }
    });
});
</script>
@endpush
@endsection