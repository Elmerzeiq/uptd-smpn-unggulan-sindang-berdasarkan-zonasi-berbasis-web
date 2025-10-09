@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-edit"></i> Edit Alur Pendaftaran</h4>
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
                    <a href="{{ route('admin.alur-pendaftaran.index') }}">Alur Pendaftaran</a>
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
                        <div class="card-title">Form Edit Langkah Alur Pendaftaran</div>
                    </div>

                    <form action="{{ route('admin.alur-pendaftaran.update', $alurPendaftaran->id) }}" method="POST">
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
                                <label for="urutan">Urutan Langkah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </span>
                                    <input type="number" name="urutan" id="urutan"
                                        class="form-control @error('urutan') is-invalid @enderror" min="1" max="20"
                                        value="{{ old('urutan', $alurPendaftaran->urutan) }}" required>
                                </div>
                                @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan nomor urutan langkah (1-20)
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Langkah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <input type="text" name="nama" id="nama"
                                        class="form-control @error('nama') is-invalid @enderror" maxlength="100"
                                        placeholder="Contoh: Registrasi Akun"
                                        value="{{ old('nama', $alurPendaftaran->nama) }}" required>
                                </div>
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan nama singkat dan jelas untuk langkah ini (maksimal 100 karakter)
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror" rows="5"
                                    maxlength="500"
                                    placeholder="Jelaskan secara detail apa yang harus dilakukan pada langkah ini..."
                                    required>{{ old('keterangan', $alurPendaftaran->keterangan) }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="d-flex justify-content-between">
                                    <small class="form-text text-muted">
                                        Jelaskan secara detail langkah yang harus dilakukan
                                    </small>
                                    <small class="form-text text-muted" id="charCount">
                                        0/500 karakter
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Update Langkah
                            </button>
                            <a href="{{ route('admin.alur-pendaftaran.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Preview Card -->
                <div class="card card-info">
                    <div class="card-header">
                        <div class="card-title">Preview Langkah</div>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="badge badge-primary badge-lg mb-2" id="preview-urutan">{{
                                $alurPendaftaran->urutan }}</div>
                            <b>
                                <h6 class="text-white mb-2 font-weight-bold" id="preview-nama">{{ $alurPendaftaran->nama
                                    }}</h6>
                            </b>
                            <p class="text-white small" id="preview-keterangan">{{ $alurPendaftaran->keterangan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card card-warning">
                    <div class="card-header">
                        <div class="card-title">Informasi</div>
                    </div>
                    <div class="card-body">
                        <h6><i class="fa fa-info-circle"></i> Panduan Pengisian</h6>
                        <ul class="list-unstyled">
                            <li><strong>Urutan:</strong> Nomor urutan langkah secara berurutan</li>
                            <li><strong>Nama:</strong> Judul singkat untuk langkah ini</li>
                            <li><strong>Keterangan:</strong> Penjelasan detail yang akan ditampilkan</li>
                        </ul>

                        <div class="mt-4">
                            <h6><i class="fas fa-lightbulb"></i> Contoh Langkah Alur</h6>
                            <div class="alert alert-light">
                                <small style="color: #000">
                                    <strong>1. Registrasi Akun</strong><br>
                                    Mendaftar akun baru di sistem PPDB dengan mengisi data diri dan membuat password
                                    <br><br>
                                    <strong>2. Verifikasi Email</strong><br>
                                    Melakukan verifikasi email yang telah didaftarkan melalui link yang dikirim
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Data Info -->
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title">Data Saat Ini</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>ID:</strong> {{ $alurPendaftaran->id }}
                        </div>
                        <div class="mb-2">
                            <strong>Dibuat:</strong> {{ $alurPendaftaran->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong>Terakhir Update:</strong> {{ $alurPendaftaran->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Existing Steps -->
                @if(isset($existingSteps) && $existingSteps->count() > 0)
                <div class="card card-success">
                    <div class="card-header">
                        <div class="card-title">Langkah Lainnya</div>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        @foreach($existingSteps as $step)
                        <div
                            class="d-flex align-items-center mb-2 {{ $step->id == $alurPendaftaran->id ? 'bg-light p-2 rounded' : '' }}">
                            <span
                                class="badge {{ $step->id == $alurPendaftaran->id ? 'badge-warning' : 'badge-primary' }} me-2">{{
                                $step->urutan }}</span>
                            <small class="{{ $step->id == $alurPendaftaran->id ? 'font-weight-bold' : '' }}">
                                {{ $step->nama }}
                                {{ $step->id == $alurPendaftaran->id ? '(Sedang diedit)' : '' }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
    // Character counter
    $('#keterangan').on('input', function() {
        var length = $(this).val().length;
        $('#charCount').text(length + '/500 karakter');
        
        if (length > 450) {
            $('#charCount').addClass('text-warning');
        } else if (length > 480) {
            $('#charCount').addClass('text-danger').removeClass('text-warning');
        } else {
            $('#charCount').removeClass('text-warning text-danger');
        }
        
        // Update preview
        updatePreview();
    });
    
    // Update preview on input
    $('#urutan, #nama').on('input', function() {
        updatePreview();
    });
    
    function updatePreview() {
        var urutan = $('#urutan').val() || '1';
        var nama = $('#nama').val() || 'Nama Langkah';
        var keterangan = $('#keterangan').val() || 'Keterangan akan muncul di sini...';
        
        $('#preview-urutan').text(urutan);
        $('#preview-nama').text(nama);
        $('#preview-keterangan').text(keterangan.length > 100 ? 
            keterangan.substring(0, 100) + '...' : keterangan);
    }
    
    // Auto-resize textarea
    $('#keterangan').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Initialize character count and preview
    var initialLength = $('#keterangan').val().length;
    $('#charCount').text(initialLength + '/500 karakter');
    updatePreview();
});
</script>
@endpush
@endsection