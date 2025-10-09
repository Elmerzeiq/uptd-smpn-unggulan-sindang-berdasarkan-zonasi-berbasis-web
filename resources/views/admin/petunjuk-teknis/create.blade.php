@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-book me-2"></i>
                Petunjuk Teknis SPMB
            </h4>
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
                    <a href="{{ route('admin.petunjuk-teknis.index') }}">Petunjuk Teknis</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>Tambah Petunjuk</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa fa-plus me-2"></i>
                            Tambah Petunjuk Teknis
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.petunjuk-teknis.store') }}" method="POST"
                            enctype="multipart/form-data" id="petunjukForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="judul" class="form-label">
                                            <i class="fas fa-heading me-1"></i>
                                            Judul Petunjuk <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="judul" id="judul"
                                            class="form-control @error('judul') is-invalid @enderror"
                                            placeholder="Contoh: Petunjuk Pendaftaran Online SPMB 2024"
                                            value="{{ old('judul') }}" required>
                                        @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="isi" class="form-label">
                                            <i class="fas fa-align-left me-1"></i>
                                            Isi Petunjuk <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="isi" id="isi"
                                            class="form-control @error('isi') is-invalid @enderror" rows="8"
                                            placeholder="Masukkan isi petunjuk teknis secara detail..."
                                            required>{{ old('isi') }}</textarea>
                                        @error('isi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Jelaskan petunjuk secara detail dan mudah dipahami
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="path_pdf" class="form-label">
                                            <i class="fas fa-file-pdf me-1"></i>
                                            Upload File PDF <span class="text-danger">*</span>
                                        </label>
                                        <input type="file" name="path_pdf" id="path_pdf"
                                            class="form-control @error('path_pdf') is-invalid @enderror" accept=".pdf"
                                            required>
                                        @error('path_pdf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format file: PDF, Maksimal 10MB
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="alert alert-info">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Tips Membuat Petunjuk Teknis:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Gunakan judul yang jelas dan informatif</li>
                                        <li>Tulis isi dengan bahasa yang mudah dipahami</li>
                                        <li>Upload file PDF sebagai dokumentasi lengkap</li>
                                        <li>Pastikan informasi yang diberikan akurat dan terbaru</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div id="filePreview" class="form-group" style="display: none;">
                                <div class="alert alert-success">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    <strong>File Dipilih:</strong>
                                    <span id="fileName"></span>
                                    <span id="fileSize" class="text-muted"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-action">
                        <button type="submit" form="petunjukForm" class="btn btn-success btn-round">
                            <i class="fa fa-save me-1"></i>
                            Simpan Petunjuk
                        </button>
                        <a href="{{ route('admin.petunjuk-teknis.index') }}" class="btn btn-secondary btn-round">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <button type="button" class="btn btn-warning btn-round" onclick="resetForm()">
                            <i class="fa fa-refresh me-1"></i>
                            Reset Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert untuk success message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    // SweetAlert untuk error message
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    @endif

    // SweetAlert untuk validation errors
    @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: errorMessages,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    @endif

    // File validation and preview
    const fileInput = document.getElementById('path_pdf');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            // Validate file type
            if (file.type !== 'application/pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Valid',
                    text: 'Hanya file PDF yang diperbolehkan!',
                    confirmButtonColor: '#d33'
                });
                this.value = '';
                filePreview.style.display = 'none';
                return;
            }

            // Validate file size (10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 10MB!',
                    confirmButtonColor: '#d33'
                });
                this.value = '';
                filePreview.style.display = 'none';
                return;
            }

            // Show file preview
            fileName.textContent = file.name;
            fileSize.textContent = ` (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            filePreview.style.display = 'block';

            // Show success toast
            Swal.fire({
                icon: 'success',
                title: 'File berhasil dipilih!',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            filePreview.style.display = 'none';
        }
    });

    // Form submission with SweetAlert
    document.getElementById('petunjukForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const judul = document.getElementById('judul').value;
        const isi = document.getElementById('isi').value;
        const file = document.getElementById('path_pdf').files[0];

        if (!file) {
            Swal.fire({
                icon: 'warning',
                title: 'File PDF Belum Dipilih',
                text: 'Silakan pilih file PDF terlebih dahulu',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Simpan',
            html: `Apakah Anda yakin ingin menyimpan petunjuk teknis ini?<br><br>
                   <strong>Judul:</strong> ${judul}<br>
                   <strong>File PDF:</strong> ${file.name}<br>
                   <strong>Ukuran:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-save"></i> Ya, Simpan!',
            cancelButtonText: '<i class="fa fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang mengupload file dan menyimpan data',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form
                this.submit();
            }
        });
    });
});

function resetForm() {
    Swal.fire({
        title: 'Konfirmasi Reset',
        text: 'Yakin ingin mengosongkan semua field? Data yang sudah diisi akan hilang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa fa-refresh"></i> Ya, Reset!',
        cancelButtonText: '<i class="fa fa-times"></i> Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('petunjukForm').reset();
            document.getElementById('filePreview').style.display = 'none';

            // Show success toast
            Swal.fire({
                icon: 'success',
                title: 'Form telah direset!',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}
</script>
@endpush
@endsection
