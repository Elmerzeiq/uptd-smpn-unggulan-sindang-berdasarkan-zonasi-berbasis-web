@extends('layouts.admin.app')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fa fa-calendar me-2"></i>
                Jadwal Pendaftaran SPMB
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
                    <a href="{{ route('admin.jadwal-pendaftaran.index') }}">Jadwal Pendaftaran</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <span>Tambah Jadwal</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa fa-plus me-2"></i>
                            Tambah Jadwal Pendaftaran
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.jadwal-pendaftaran.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahap" class="form-label">
                                            <i class="fa fa-tag me-1"></i>
                                            Tahap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="tahap" id="tahap"
                                            class="form-control @error('tahap') is-invalid @enderror"
                                            placeholder="Contoh: Tahap 1, Tahap 2, dll" value="{{ old('tahap') }}"
                                            required>
                                        @error('tahap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kegiatan" class="form-label">
                                            <i class="fa fa-tasks me-1"></i>
                                            Kegiatan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="kegiatan" id="kegiatan"
                                            class="form-control @error('kegiatan') is-invalid @enderror"
                                            placeholder="Contoh: Pendaftaran Online, Tes Tertulis, dll"
                                            value="{{ old('kegiatan') }}" required>
                                        @error('kegiatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_mulai" class="form-label">
                                            <i class="fa fa-calendar-o me-1"></i>
                                            Tanggal Mulai <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                            value="{{ old('tanggal_mulai') }}" required>
                                        @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_selesai" class="form-label">
                                            <i class="fa fa-calendar-check-o me-1"></i>
                                            Tanggal Selesai <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                            value="{{ old('tanggal_selesai') }}" required>
                                        @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i>
                                    <strong>Petunjuk:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Pastikan tanggal selesai tidak lebih awal dari tanggal mulai</li>
                                        <li>Gunakan format tahap yang konsisten (contoh: Tahap 1, Tahap 2)</li>
                                        <li>Nama kegiatan harus jelas dan mudah dipahami</li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-action">
                        <button type="submit" form="jadwalForm" class="btn btn-success btn-round">
                            <i class="fa fa-save me-1"></i>
                            Simpan Jadwal
                        </button>
                        <a href="{{ route('admin.jadwal-pendaftaran.index') }}" class="btn btn-secondary btn-round">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali
                        </a>
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
    // Set form id untuk submit button
    document.querySelector('form').id = 'jadwalForm';

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

    // Validasi tanggal
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');

    function validateDates() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            if (new Date(tanggalSelesai.value) < new Date(tanggalMulai.value)) {
                tanggalSelesai.setCustomValidity('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');

                // Show SweetAlert warning
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                tanggalSelesai.setCustomValidity('');
            }
        }
    }

    tanggalMulai.addEventListener('change', validateDates);
    tanggalSelesai.addEventListener('change', validateDates);

    // SweetAlert konfirmasi submit
    document.getElementById('jadwalForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const tahap = document.getElementById('tahap').value;
        const kegiatan = document.getElementById('kegiatan').value;
        const tanggalMulai = document.getElementById('tanggal_mulai').value;
        const tanggalSelesai = document.getElementById('tanggal_selesai').value;

        Swal.fire({
            title: 'Konfirmasi Simpan',
            html: `Apakah Anda yakin ingin menyimpan jadwal ini?<br><br>
                   <strong>Tahap:</strong> ${tahap}<br>
                   <strong>Kegiatan:</strong> ${kegiatan}<br>
                   <strong>Periode:</strong> ${new Date(tanggalMulai).toLocaleDateString('id-ID')} - ${new Date(tanggalSelesai).toLocaleDateString('id-ID')}`,
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
                    text: 'Sedang memproses data jadwal',
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
</script>
@endpush
@endsection
