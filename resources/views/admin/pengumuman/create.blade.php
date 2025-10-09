{{-- resources/views/admin/pengumuman/create.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Tambah Pengumuman')
@section('title_header_admin', 'Tambah Pengumuman Umum')

@section('admin_content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Tambah Pengumuman Umum</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item">
                <a href="{{ route('admin.pengumuman.index') }}">Pengumuman</a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a>Tambah Pengumuman</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">
                            <i class="fas fa-bullhorn me-2"></i>Form Pengumuman Umum
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('admin.pengumuman.store') }}" method="POST" id="pengumuman-form">
                        @csrf

                        {{-- Judul Pengumuman --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 form-group">
                                    <label for="judul" class="form-label fw-semibold">
                                        Judul Pengumuman <span class="text-danger">*</span>
                                    </label>
                                    <input id="judul" type="text"
                                        class="form-control @error('judul') is-invalid @enderror" name="judul"
                                        value="{{ old('judul') }}" required
                                        placeholder="Contoh: Pengumuman Libur Semester Ganjil">
                                    @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Maksimal 255 karakter. Buatlah judul yang jelas dan informatif.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Tipe, Target, dan Tanggal --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3 form-group">
                                    <label for="tipe" class="form-label fw-semibold">
                                        Tipe Pengumuman <span class="text-danger">*</span>
                                    </label>
                                    <select id="tipe" name="tipe"
                                        class="form-select @error('tipe') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="info" {{ old('tipe')=='info' ? 'selected' : '' }}>
                                            Info (Biru)
                                        </option>
                                        <option value="success" {{ old('tipe')=='success' ? 'selected' : '' }}>
                                            Success (Hijau)
                                        </option>
                                        <option value="warning" {{ old('tipe')=='warning' ? 'selected' : '' }}>
                                            Warning (Kuning)
                                        </option>
                                        <option value="danger" {{ old('tipe')=='danger' ? 'selected' : '' }}>
                                            Danger (Merah)
                                        </option>
                                    </select>
                                    @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3 form-group">
                                    <label for="target_penerima" class="form-label fw-semibold">
                                        Target Penerima <span class="text-danger">*</span>
                                    </label>
                                    <select id="target_penerima" name="target_penerima"
                                        class="form-select @error('target_penerima') is-invalid @enderror" required>
                                        <option value="">Pilih Target</option>
                                        <option value="semua" {{ old('target_penerima')=='semua' ? 'selected' : '' }}>
                                            Semua Pengguna
                                        </option>
                                        <option value="calon_siswa" {{ old('target_penerima')=='calon_siswa'
                                            ? 'selected' : '' }}>
                                            Calon Siswa (Belum Ada Hasil)
                                        </option>
                                        <option value="siswa_diterima" {{ old('target_penerima')=='siswa_diterima'
                                            ? 'selected' : '' }}>
                                            Siswa Diterima
                                        </option>
                                        <option value="siswa_ditolak" {{ old('target_penerima')=='siswa_ditolak'
                                            ? 'selected' : '' }}>
                                            Siswa Ditolak
                                        </option>
                                    </select>
                                    @error('target_penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3 form-group">
                                    <label for="tanggal" class="form-label fw-semibold">
                                        Tanggal & Waktu Tayang
                                    </label>
                                    <input type="datetime-local" id="tanggal" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal') }}">
                                    @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Kosongkan untuk tayang sekarang.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Isi Pengumuman --}}
                        <div class="mb-3 form-group">
                            <label for="isi" class="form-label fw-semibold">
                                Isi Pengumuman <span class="text-danger">*</span>
                            </label>
                            <textarea id="isi" name="isi" class="form-control @error('isi') is-invalid @enderror"
                                rows="12" required
                                placeholder="Tulis isi pengumuman di sini...">{{ old('isi') }}</textarea>
                            @error('isi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Template Suggestions --}}
                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb me-2"></i>Template Cepat:</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="button" class="mb-2 btn btn-outline-info btn-sm w-100"
                                        onclick="useTemplate('libur')">
                                        Pengumuman Libur
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="mb-2 btn btn-outline-success btn-sm w-100"
                                        onclick="useTemplate('jadwal')">
                                        Informasi Jadwal
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="mb-2 btn btn-outline-warning btn-sm w-100"
                                        onclick="useTemplate('peringatan')">
                                        Peringatan
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="mb-2 btn btn-outline-secondary btn-sm w-100"
                                        onclick="clearContent()">
                                        Kosongkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Priority dan Status --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="priority" class="form-label fw-semibold">
                                        Prioritas
                                    </label>
                                    <select id="priority" name="priority" class="form-select">
                                        <option value="1" {{ old('priority')=='1' ? 'selected' : '' }}>
                                            Sangat Tinggi (1)
                                        </option>
                                        <option value="3" {{ old('priority')=='3' ? 'selected' : '' }}>
                                            Tinggi (3)
                                        </option>
                                        <option value="5" {{ old('priority', '5' )=='5' ? 'selected' : '' }}>
                                            Normal (5)
                                        </option>
                                        <option value="7" {{ old('priority')=='7' ? 'selected' : '' }}>
                                            Rendah (7)
                                        </option>
                                        <option value="10" {{ old('priority')=='10' ? 'selected' : '' }}>
                                            Sangat Rendah (10)
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Pengumuman dengan prioritas tinggi akan ditampilkan lebih dulu.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label fw-semibold">Status Pengumuman</label>
                                    <div class="mt-2 form-check form-switch">
                                        <input class="form-check-input @error('aktif') is-invalid @enderror"
                                            type="checkbox" id="aktif" name="aktif" value="1" {{ old('aktif', true)
                                            ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="aktif">
                                            Aktifkan Pengumuman
                                        </label>
                                    </div>
                                    @error('aktif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Pengumuman yang tidak aktif tidak akan ditampilkan kepada siswa.
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Preview Section --}}
                        <div class="mt-4 card bg-light" id="preview-section" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-eye me-2"></i>Preview Pengumuman
                                </h6>
                            </div>
                            <div class="card-body" id="preview-content">
                                <!-- Preview akan ditampilkan di sini -->
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4 card-action">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-info btn-round" id="preview-btn">
                                        <i class="fas fa-eye me-2"></i>Preview
                                    </button>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-round">
                                        <i class="fas fa-save me-2"></i>Simpan Pengumuman
                                    </button>
                                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary btn-round">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.css" rel="stylesheet">
<style>
    .note-editor {
        border-radius: 8px;
    }

    .form-label.fw-semibold {
        color: #2c3e50;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    .btn-round {
        border-radius: 20px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .preview-badge {
        display: inline-block;
        margin-right: 8px;
        margin-bottom: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
    // Initialize Summernote
    $('#isi').summernote({
        placeholder: 'Tulis isi pengumuman di sini...',
        tabsize: 2,
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'italic', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onChange: function(contents, $editable) {
                updatePreview();
            }
        }
    });

    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);

    // Form validation
    $('#pengumuman-form').on('submit', function(e) {
        if ($('#isi').summernote('isEmpty')) {
            e.preventDefault();
            alert('Isi pengumuman tidak boleh kosong!');
            return false;
        }
    });

    // Preview functionality
    $('#preview-btn').on('click', function() {
        const previewSection = $('#preview-section');
        if (previewSection.is(':visible')) {
            previewSection.hide();
            $(this).html('<i class="fas fa-eye me-2"></i>Preview');
        } else {
            updatePreview();
            previewSection.show();
            $(this).html('<i class="fas fa-eye-slash me-2"></i>Tutup Preview');
        }
    });

    function updatePreview() {
        const judul = $('#judul').val();
        const tipe = $('#tipe').val();
        const target = $('#target_penerima option:selected').text();
        const isi = $('#isi').summernote('code');
        const tanggal = $('#tanggal').val();

        if (!judul || !isi) return;

        const tipeBadgeClass = {
            'info': 'bg-info',
            'success': 'bg-success',
            'warning': 'bg-warning text-dark',
            'danger': 'bg-danger'
        };

        const tipeIcon = {
            'info': 'fas fa-info-circle',
            'success': 'fas fa-check-circle',
            'warning': 'fas fa-exclamation-triangle',
            'danger': 'fas fa-times-circle'
        };

        const previewHTML = `
            <div class="mb-0 card">
                <div class="card-header ${tipeBadgeClass[tipe] || 'bg-secondary'} text-white">
                    <h5 class="mb-0 card-title">
                        <i class="${tipeIcon[tipe] || 'fas fa-bullhorn'} me-2"></i>
                        ${judul}
                    </h5>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <small>Target: ${target}</small>
                        <small>${tanggal ? new Date(tanggal).toLocaleString('id-ID') : 'Tayang Sekarang'}</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="content-display">
                        ${isi}
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->nama_lengkap ?? Auth::user()->nama_lengkap }}
                        <span class="ms-3">
                            <i class="fas fa-clock me-1"></i>{{ now()->format('d M Y H:i') }}
                        </span>
                    </small>
                </div>
            </div>
        `;

        $('#preview-content').html(previewHTML);
    }
});

function useTemplate(type) {
    let content = '';
    let judul = '';

    switch(type) {
        case 'libur':
            judul = 'Pengumuman Libur Semester';
            content = `
                <p><strong>Kepada Yth. Seluruh Siswa SMPN Unggulan Sindang</strong></p>

                <p>Dengan ini kami sampaikan bahwa dalam rangka libur semester, kegiatan belajar mengajar akan diliburkan mulai:</p>

                <ul>
                    <li><strong>Tanggal:</strong> [Tanggal Mulai] s/d [Tanggal Selesai]</li>
                    <li><strong>Masuk kembali:</strong> [Tanggal Masuk]</li>
                </ul>

                <p>Selama libur, diharapkan siswa tetap menjaga kesehatan dan menggunakan waktu dengan baik.</p>

                <p>Demikian pengumuman ini kami sampaikan. Terima kasih.</p>

                <p><strong>Panitia SPMB SMPN Unggulan Sindang</strong></p>
            `;
            $('#tipe').val('info').trigger('change');
            break;

        case 'jadwal':
            judul = 'Informasi Jadwal Kegiatan';
            content = `
                <p><strong>Kepada Yth. Seluruh Siswa SMPN Unggulan Sindang</strong></p>

                <p>Berikut adalah jadwal kegiatan yang akan dilaksanakan:</p>

                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="border: 1px solid #ddd; padding: 8px;">Hari/Tanggal</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Waktu</th>
                            <th style="border: 1px solid #ddd; padding: 8px;">Kegiatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">[Hari, Tanggal]</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">[Waktu]</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">[Nama Kegiatan]</td>
                        </tr>
                    </tbody>
                </table>

                <p>Mohon perhatikan jadwal tersebut dan hadiri tepat waktu.</p>

                <p><strong>Panitia SPMB SMPN Unggulan Sindang</strong></p>
            `;
            $('#tipe').val('success').trigger('change');
            break;

        case 'peringatan':
            judul = 'Peringatan Penting';
            content = `
                <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <h4 style="color: #856404; margin-bottom: 10px;">⚠️ PERHATIAN!</h4>
                    <p style="color: #856404; margin-bottom: 0;">Ini adalah peringatan penting yang harus diperhatikan oleh seluruh siswa.</p>
                </div>

                <p><strong>Kepada Yth. Seluruh Siswa SMPN Unggulan Sindang</strong></p>

                <p>Dengan ini kami sampaikan peringatan mengenai:</p>

                <ol>
                    <li>[Poin peringatan pertama]</li>
                    <li>[Poin peringatan kedua]</li>
                    <li>[Poin peringatan ketiga]</li>
                </ol>

                <p><strong>Konsekuensi:</strong></p>
                <p>[Jelaskan konsekuensi jika tidak mengikuti peringatan]</p>

                <p>Mohon untuk mematuhi peraturan yang telah ditetapkan.</p>

                <p><strong>Panitia SPMB SMPN Unggulan Sindang</strong></p>
            `;
            $('#tipe').val('warning').trigger('change');
            break;
    }

    if (judul) $('#judul').val(judul);
    if (content) $('#isi').summernote('code', content);
}

function clearContent() {
    $('#judul').val('');
    $('#isi').summernote('code', '');
}
</script>
@endpush
