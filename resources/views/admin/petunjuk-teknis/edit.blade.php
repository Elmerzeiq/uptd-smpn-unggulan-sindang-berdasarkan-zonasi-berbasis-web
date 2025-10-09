@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-edit me-2"></i>
                Edit Petunjuk Teknis SPMB
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
                    <span>Edit Petunjuk</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa fa-edit me-2"></i>
                            Edit Petunjuk Teknis
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.petunjuk-teknis.update', $petunjukTeknis->id) }}" method="POST"
                            enctype="multipart/form-data" id="petunjukForm">
                            @csrf
                            @method('PUT')
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
                                            value="{{ old('judul', $petunjukTeknis->judul) }}" required>
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
                                            required>{{ old('isi', $petunjukTeknis->isi) }}</textarea>
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

                            <!-- Current PDF Display -->
                            @if($petunjukTeknis->path_pdf)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-file-pdf me-1"></i>
                                            File PDF Saat Ini
                                        </label>
                                        <div class="alert alert-info">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                                    <strong>{{ basename($petunjukTeknis->path_pdf) }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Diupload: {{ $petunjukTeknis->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                                <div>
                                                    <a href="{{ asset('storage/' . $petunjukTeknis->path_pdf) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-eye me-1"></i>
                                                        Lihat PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="path_pdf" class="form-label">
                                            <i class="fas fa-file-pdf me-1"></i>
                                            Upload File PDF Baru
                                            @if(!$petunjukTeknis->path_pdf)
                                            <span class="text-danger">*</span>
                                            @else
                                            <span class="text-muted">(Opsional)</span>
                                            @endif
                                        </label>
                                        <input type="file" name="path_pdf" id="path_pdf"
                                            class="form-control @error('path_pdf') is-invalid @enderror" accept=".pdf"
                                            @if(!$petunjukTeknis->path_pdf) required @endif>
                                        @error('path_pdf')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format file: PDF, Maksimal 10MB
                                            @if($petunjukTeknis->path_pdf)
                                            <br><strong>Catatan:</strong> File baru akan menggantikan file lama
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Pastikan data yang diubah sudah benar sebelum menyimpan</li>
                                        <li>Jika mengupload PDF baru, file lama akan digantikan</li>
                                        <li>Perubahan akan langsung terlihat di halaman publik</li>
                                        <li>Backup data penting sebelum melakukan perubahan</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div id="filePreview" class="form-group" style="display: none;">
                                <div class="alert alert-success">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    <strong>File Baru Dipilih:</strong>
                                    <span id="fileName"></span>
                                    <span id="fileSize" class="text-muted"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-action">
                        <button type="submit" form="petunjukForm" class="btn btn-success btn-round">
                            <i class="fa fa-save me-1"></i>
                            Update Petunjuk
                        </button>
                        <a href="{{ route('admin.petunjuk-teknis.index') }}" class="btn btn-secondary btn-round">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <button type="button" class="btn btn-warning btn-round" onclick="resetForm()">
                            <i class="fa fa-refresh me-1"></i>
                            Reset Form
                        </button>
                        @if($petunjukTeknis->path_pdf)
                        <a href="{{ asset('storage/' . $petunjukTeknis->path_pdf) }}" target="_blank"
                            class="btn btn-info btn-round">
                            <i class="fas fa-download me-1"></i>
                            Download PDF
                        </a>
                        @endif
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
    // Store original values for reset
    const originalValues = {
        judul: document.getElementById('judul').value,
        isi: document.getElementById('isi').value
    };

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
        const hasExistingPdf = {{ $petunjukTeknis->path_pdf ? 'true' : 'false' }};

        // Check if PDF is required but not provided
        if (!hasExistingPdf && !file) {
            Swal.fire({
                icon: 'warning',
                title: 'File PDF Belum Dipilih',
                text: 'Silakan pilih file PDF terlebih dahulu',
                confirmButtonColor: '#ffc107'
            });
            return;
        }

        let fileInfo = '';
        if (file) {
            fileInfo = `<br><strong>File PDF Baru:</strong> ${file.name}<br>
                       <strong>Ukuran:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB<br>
                       <span class="text-warning">⚠️ File lama akan digantikan</span>`;
        } else if (hasExistingPdf) {
            fileInfo = '<br><strong>PDF:</strong> Tetap menggunakan file yang ada';
        }

        Swal.fire({
            title: 'Konfirmasi Update',
            html: `Apakah Anda yakin ingin mengupdate petunjuk teknis ini?<br><br>
                   <strong>Judul:</strong> ${judul}${fileInfo}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-save"></i> Ya, Update!',
            cancelButtonText: '<i class="fa fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Mengupdate...',
                    text: 'Sedang menyimpan perubahan data',
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

    // Check for changes before leaving page
    window.addEventListener('beforeunload', function (e) {
        const currentJudul = document.getElementById('judul').value;
        const currentIsi = document.getElementById('isi').value;
        const hasFileChange = document.getElementById('path_pdf').files.length > 0;

        if (currentJudul !== originalValues.judul || 
            currentIsi !== originalValues.isi || 
            hasFileChange) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
});

function resetForm() {
    Swal.fire({
        title: 'Konfirmasi Reset',
        text: 'Yakin ingin mengembalikan form ke nilai awal? Perubahan yang belum disimpan akan hilang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa fa-refresh"></i> Ya, Reset!',
        cancelButtonText: '<i class="fa fa-times"></i> Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Reset to original values
            document.getElementById('judul').value = '{{ old('judul', $petunjukTeknis->judul) }}';
            document.getElementById('isi').value = '{{ old('isi', $petunjukTeknis->isi) }}';
            document.getElementById('path_pdf').value = '';
            document.getElementById('filePreview').style.display = 'none';

            // Remove validation classes
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });

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