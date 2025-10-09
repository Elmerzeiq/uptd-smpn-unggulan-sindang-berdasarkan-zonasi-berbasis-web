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
                    <span>Edit Jadwal</span>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fa fa-edit me-2"></i>
                            Edit Jadwal Pendaftaran
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.jadwal-pendaftaran.update', $jadwalPendaftaran->id) }}"
                            method="POST" id="jadwalEditForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahap" class="form-label">
                                            <i class="fa fa-tag me-1"></i>
                                            Tahap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="tahap" id="tahap"
                                            class="form-control @error('tahap') is-invalid @enderror"
                                            placeholder="Contoh: Tahap 1, Tahap 2, dll"
                                            value="{{ old('tahap', $jadwalPendaftaran->tahap) }}" required>
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
                                            value="{{ old('kegiatan', $jadwalPendaftaran->kegiatan) }}" required>
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
                                            value="{{ old('tanggal_mulai', $jadwalPendaftaran->tanggal_mulai) }}"
                                            required>
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
                                            value="{{ old('tanggal_selesai', $jadwalPendaftaran->tanggal_selesai) }}"
                                            required>
                                        @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Perubahan jadwal dapat mempengaruhi proses pendaftaran yang sedang
                                            berlangsung</li>
                                        <li>Pastikan tanggal selesai tidak lebih awal dari tanggal mulai</li>
                                        <li>Koordinasikan perubahan jadwal dengan pihak terkait</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fa fa-info-circle me-1"></i>
                                            Dibuat: {{ $jadwalPendaftaran->created_at ?
                                            $jadwalPendaftaran->created_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fa fa-edit me-1"></i>
                                            Diperbarui: {{ $jadwalPendaftaran->updated_at ?
                                            $jadwalPendaftaran->updated_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-action">
                        <button type="submit" form="jadwalEditForm" class="btn btn-success btn-round">
                            <i class="fa fa-save me-1"></i>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.jadwal-pendaftaran.index') }}" class="btn btn-secondary btn-round">
                            <i class="fa fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <button type="button" class="btn btn-info btn-round" onclick="resetForm()">
                            <i class="fa fa-refresh me-1"></i>
                            Reset
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

    // SweetAlert konfirmasi sebelum submit
    document.getElementById('jadwalEditForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const tahap = document.getElementById('tahap').value;
        const kegiatan = document.getElementById('kegiatan').value;
        const tanggalMulai = document.getElementById('tanggal_mulai').value;
        const tanggalSelesai = document.getElementById('tanggal_selesai').value;

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            html: `Apakah Anda yakin ingin menyimpan perubahan jadwal ini?<br><br>
                   <strong>Tahap:</strong> ${tahap}<br>
                   <strong>Kegiatan:</strong> ${kegiatan}<br>
                   <strong>Periode:</strong> ${new Date(tanggalMulai).toLocaleDateString('id-ID')} - ${new Date(tanggalSelesai).toLocaleDateString('id-ID')}<br><br>
                   <small class="text-warning">⚠️ Perubahan ini dapat mempengaruhi proses pendaftaran yang sedang berlangsung</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-save"></i> Ya, Simpan Perubahan!',
            cancelButtonText: '<i class="fa fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan Perubahan...',
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

function resetForm() {
    Swal.fire({
        title: 'Konfirmasi Reset',
        text: 'Yakin ingin mereset form ke data asli? Semua perubahan akan hilang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa fa-refresh"></i> Ya, Reset!',
        cancelButtonText: '<i class="fa fa-times"></i> Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('jadwalEditForm').reset();
            // Restore original values
            document.getElementById('tahap').value = "{{ $jadwalPendaftaran->tahap }}";
            document.getElementById('kegiatan').value = "{{ $jadwalPendaftaran->kegiatan }}";
            document.getElementById('tanggal_mulai').value = "{{ $jadwalPendaftaran->tanggal_mulai }}";
            document.getElementById('tanggal_selesai').value = "{{ $jadwalPendaftaran->tanggal_selesai }}";

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
