@extends('layouts.siswa.app')
@section('title', 'Profil Saya - ' . Auth::user()->nama_lengkap)

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="mb-4 page-header">
            <h3 class="page-title fw-bold text-success">
                <i class="bi bi-person-circle me-2"></i> Profil Saya
            </h3>
        {{-- <div class="page-action d-flex justify-content-end">
            <button class="btn btn-info me-2" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Profil
            </button>
            <a href="{{ route('siswa.profile.edit') }}" class="btn btn-warning">
                <i class="bi bi-pencil-fill me-1"></i> Edit Profil
            </a>
        </div>
        <br> --}}
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(Auth::user()->catatan_verifikasi)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Pesan dari Admin</h5>
            <p class="mb-0">{!! nl2br(e(Auth::user()->catatan_verifikasi)) !!}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-4">
            <!-- Main Profile Card -->
            <div class="col-md-8">
                <div class="border-0 shadow-sm card">
                    <div class="card-header bg-gradient"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="text-white d-flex align-items-center">
                            <div class="me-3">
                                @if(Auth::user()->berkas && Auth::user()->berkas->file_pas_foto)
                                <img src="{{ Storage::url(Auth::user()->berkas->file_pas_foto) }}" alt="Foto Profil"
                                    class="border-white rounded-circle border-3"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 80px; height: 80px;">
                                    <i class="text-white bi bi-person fs-1"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-1 text-white">{{ Auth::user()->nama_lengkap }}</h4>
                                <p class="mb-1 text-white-50">{{ Auth::user()->email }}</p>
                                <span class="badge bg-light text-dark">{{ Auth::user()->no_pendaftaran }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge fs-6 px-3 py-2
                                    @if(Auth::user()->status_pendaftaran == 'lulus_seleksi') bg-success
                                    @elseif(in_array(Auth::user()->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap'])) bg-danger
                                    @elseif(in_array(Auth::user()->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi'])) bg-info
                                    @else bg-warning @endif">
                                    {{ ucwords(str_replace('_', ' ', Auth::user()->status_pendaftaran ?? 'N/A')) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Quick Info -->
                        <div class="mb-4 row g-3">
                            <div class="col-md-3">
                                <div class="p-3 text-center rounded bg-light">
                                    <i class="bi bi-calendar-event fs-2 text-primary"></i>
                                    <h6 class="mt-2 mb-1">Tgl Daftar</h6>
                                    <small>{{ Auth::user()->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 text-center rounded bg-light">
                                    <i class="bi bi-signpost fs-2 text-info"></i>
                                    <h6 class="mt-2 mb-1">Jalur</h6>
                                    <small>{{ ucfirst(Auth::user()->jalur_pendaftaran) }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 text-center rounded bg-light">
                                    <i class="bi bi-mortarboard fs-2 text-warning"></i>
                                    <h6 class="mt-2 mb-1">NISN</h6>
                                    <small>{{ Auth::user()->nisn }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 text-center rounded bg-light">
                                    <i class="bi bi-clock-history fs-2 text-secondary"></i>
                                    <h6 class="mt-2 mb-1">Update</h6>
                                    <small>{{ Auth::user()->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="mb-3 fw-bold text-primary">
                                    <i class="bi bi-person-badge me-2"></i>Data Pribadi
                                </h5>
                                @if(Auth::user()->biodata)
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-semibold">Tempat Lahir</td>
                                        <td>: {{ Auth::user()->biodata->tempat_lahir ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Tanggal Lahir</td>
                                        <td>: {{ Auth::user()->biodata->tgl_lahir ?
                                            Auth::user()->biodata->tgl_lahir->format('d M Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Jenis Kelamin</td>
                                        <td>: {{ Auth::user()->biodata->jns_kelamin == 'L' ? 'Laki-laki' :
                                            (Auth::user()->biodata->jns_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Agama</td>
                                        <td>: {{ Auth::user()->biodata->agama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Anak Ke</td>
                                        <td>: {{ Auth::user()->biodata->anak_ke ?? '-' }}</td>
                                    </tr>
                                </table>
                                @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Data pribadi belum dilengkapi. <a href="{{ route('siswa.profile.edit') }}">Lengkapi
                                        sekarang</a>.
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3 fw-bold text-primary">
                                    <i class="bi bi-geo-alt me-2"></i>Pendidikan & Alamat
                                </h5>
                                @if(Auth::user()->biodata)
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="fw-semibold">Asal Sekolah</td>
                                        <td>: {{ Auth::user()->biodata->asal_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Alamat</td>
                                        <td>: {{ Auth::user()->biodata->alamat_rumah ?? '-' }}</td>
                                    </tr>
                                    @if(Auth::user()->jalur_pendaftaran == 'domisili' &&
                                    Auth::user()->kecamatan_domisili)
                                    <tr>
                                        <td class="fw-semibold">Kecamatan</td>
                                        <td>: {{ ucfirst(Auth::user()->kecamatan_domisili) }}</td>
                                    </tr>
                                    @endif
                                </table>
                                @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Data pendidikan belum dilengkapi. <a
                                        href="{{ route('siswa.profile.edit') }}">Lengkapi sekarang</a>.
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Family Information -->
                        <h5 class="mb-3 fw-bold text-primary">
                            <i class="bi bi-house me-2"></i>Data Keluarga
                        </h5>
                        @if(Auth::user()->orangTua)
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-secondary">Data Ayah</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="35%" class="fw-semibold">Nama</td>
                                        <td>: {{ Auth::user()->orangTua->nama_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">No. HP</td>
                                        <td>: {{ Auth::user()->orangTua->no_hp_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Pekerjaan</td>
                                        <td>: {{ Auth::user()->orangTua->pekerjaan_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Pendidikan</td>
                                        <td>: {{ Auth::user()->orangTua->pendidikan_ayah ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-secondary">Data Ibu</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="35%" class="fw-semibold">Nama</td>
                                        <td>: {{ Auth::user()->orangTua->nama_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">No. HP</td>
                                        <td>: {{ Auth::user()->orangTua->no_hp_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Pekerjaan</td>
                                        <td>: {{ Auth::user()->orangTua->pekerjaan_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Pendidikan</td>
                                        <td>: {{ Auth::user()->orangTua->pendidikan_ibu ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Data orang tua belum dilengkapi. <a href="{{ route('siswa.profile.edit') }}">Lengkapi
                                sekarang</a>.
                        </div>
                        @endif

                        @if(Auth::user()->wali)
                        <hr>
                        <h6 class="mb-3 fw-bold text-secondary">Data Wali</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="35%" class="fw-semibold">Nama Wali</td>
                                        <td>: {{ Auth::user()->wali->nama_wali ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Hubungan</td>
                                        <td>: {{ Auth::user()->wali->hubungan_wali_dgn_calon_peserta ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="35%" class="fw-semibold">No. HP</td>
                                        <td>: {{ Auth::user()->wali->no_hp_wali ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Alamat</td>
                                        <td>: {{ Auth::user()->wali->alamat_wali ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>



                <!-- Quick Actions Card -->
                <div class="mt-4 border-0 shadow-sm card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 card-title">
                            <i class="bi bi-lightning me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-grid">
                                    <a href="{{ route('siswa.profile.edit') }}" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil-square me-2"></i>Edit Profil
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-grid">
                                    <a href="{{ route('siswa.pendaftar.show', Auth::id()) }}"
                                        class="btn btn-outline-info">
                                        <i class="bi bi-eye me-2"></i>Lihat Data Lengkap
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-grid">
                                    <a href="{{ route('siswa.berkas.index') }}" class="btn btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Berkas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Progress Card -->
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="text-white card-header bg-primary">
                        <h5 class="mb-0 text-white card-title">
                            <i class="bi bi-graph-up me-2"></i>Progress Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <div class="position-relative d-inline-flex">
                                <svg width="120" height="120" class="progress-ring">
                                    <circle cx="60" cy="60" r="50" stroke="#e9ecef" stroke-width="8" fill="transparent">
                                    </circle>
                                    <circle id="progressCircle" cx="60" cy="60" r="50" stroke="#28a745" stroke-width="8"
                                        fill="transparent" stroke-linecap="round" stroke-dasharray="314.16"
                                        stroke-dashoffset="314.16"
                                        style="transition: stroke-dashoffset 0.5s ease-in-out;"></circle>
                                </svg>
                                <div class="text-center position-absolute top-50 start-50 translate-middle">
                                    <h4 id="progressPercent" class="mb-0 fw-bold text-primary">0%</h4>
                                    <small class="text-muted">Lengkap</small>
                                </div>
                            </div>
                        </div>

                        <div id="progressDetails">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 d-grid">
                            <button class="btn btn-outline-primary btn-sm" id="refreshProgress">
                                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="text-white card-header bg-info">
                        <h5 class="mb-0 text-white card-title">
                            <i class="bi bi-clock-history me-2"></i>Timeline Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item completed">
                                <div class="timeline-marker bg-success">
                                    <i class="text-white bi bi-check-circle-fill"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1 fw-bold">Pendaftaran</h6>
                                    <small class="text-muted">{{ Auth::user()->created_at->format('d M Y, H:i')
                                        }}</small>
                                </div>
                            </div>

                            @if(Auth::user()->status_pendaftaran != 'draft')
                            <div
                                class="timeline-item {{ in_array(Auth::user()->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi', 'lulus_seleksi']) ? 'completed' : 'pending' }}">
                                <div
                                    class="timeline-marker {{ in_array(Auth::user()->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi', 'lulus_seleksi']) ? 'bg-info' : 'bg-warning' }}">
                                    <i class="text-white bi bi-file-earmark-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1 fw-bold">Verifikasi Berkas</h6>
                                    <small class="text-muted">
                                        @if(in_array(Auth::user()->status_pendaftaran, ['menunggu_verifikasi_berkas',
                                        'berkas_diverifikasi', 'lulus_seleksi']))
                                        Berkas dalam proses verifikasi
                                        @else
                                        Menunggu kelengkapan berkas
                                        @endif
                                    </small>
                                </div>
                            </div>
                            @endif

                            @if(Auth::user()->status_pendaftaran == 'lulus_seleksi')
                            <div class="timeline-item completed">
                                <div class="timeline-marker bg-success">
                                    <i class="text-white bi bi-trophy-fill"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1 fw-bold">Lulus Seleksi</h6>
                                    <small class="text-muted">Selamat! Anda dinyatakan lulus</small>
                                </div>
                            </div>
                            @elseif(Auth::user()->status_pendaftaran == 'tidak_lulus_seleksi')
                            <div class="timeline-item failed">
                                <div class="timeline-marker bg-danger">
                                    <i class="text-white bi bi-x-circle-fill"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1 fw-bold">Tidak Lulus</h6>
                                    <small class="text-muted">Belum berhasil pada seleksi ini</small>
                                </div>
                            </div>
                            @else
                            <div class="timeline-item pending">
                                <div class="timeline-marker bg-secondary">
                                    <i class="text-white bi bi-hourglass-split"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1 fw-bold">Hasil Seleksi</h6>
                                    <small class="text-muted">Menunggu pengumuman</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="text-white card-header bg-secondary">
                        <h5 class="mb-0 text-white card-title">
                            <i class="bi bi-person-gear me-2"></i>Info Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center row g-3">
                            <div class="col-6">
                                <div class="p-2 border rounded">
                                    <h6 class="mb-1 text-primary">{{ Auth::user()->no_pendaftaran }}</h6>
                                    <small class="text-muted">No. Pendaftaran</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded">
                                    <h6 class="mb-1 text-success">{{ ucfirst(Auth::user()->jalur_pendaftaran) }}</h6>
                                    <small class="text-muted">Jalur Dipilih</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-2 border rounded">
                                    <h6 class="mb-1
                                        @if(Auth::user()->status_pendaftaran == 'lulus_seleksi') text-success
                                        @elseif(in_array(Auth::user()->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap'])) text-danger
                                        @elseif(in_array(Auth::user()->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi'])) text-info
                                        @else text-warning @endif">
                                        {{ ucwords(str_replace('_', ' ', Auth::user()->status_pendaftaran ?? 'N/A')) }}
                                    </h6>
                                    <small class="text-muted">Status Saat Ini</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="small">
                            <p class="mb-2"><strong>Akun dibuat:</strong> {{ Auth::user()->created_at->format('d M Y,
                                H:i') }}</p>
                            <p class="mb-2"><strong>Terakhir update:</strong> {{
                                Auth::user()->updated_at->diffForHumans() }}</p>
                            @if(Auth::user()->email_verified_at)
                            <p class="mb-0 text-success">
                                <i class="bi bi-shield-check me-1"></i> Email terverifikasi
                            </p>
                            @else
                            <p class="mb-0 text-warning">
                                <i class="bi bi-shield-exclamation me-1"></i> Email belum terverifikasi
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Help & Support -->
                <div class="border-0 shadow-sm card">
                    <div class="text-white card-header bg-dark">
                        <h5 class="mb-0 text-white card-title">
                            <i class="bi bi-question-circle me-2"></i>Bantuan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="gap-2 d-grid">
                            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faqCollapse" aria-expanded="false">
                                <i class="bi bi-question-square me-2"></i>FAQ
                            </button>
                            <a href="mailto:ppdb@sekolah.sch.id" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-envelope me-2"></i>Email Support
                            </a>
                            <a href="tel:+62211234567" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-telephone me-2"></i>Telepon
                            </a>
                        </div>

                        <div class="mt-3 collapse" id="faqCollapse">
                            <div class="card card-body bg-light small">
                                <p class="mb-2"><strong>Q: Bagaimana cara mengubah data?</strong><br>
                                    A: Klik tombol "Edit Profil" di atas.</p>
                                <p class="mb-2"><strong>Q: Kapan hasil pengumuman?</strong><br>
                                    A: Pengumuman akan diinformasikan via email.</p>
                                <p class="mb-0"><strong>Q: Berkas apa saja yang diperlukan?</strong><br>
                                    A: Sesuai jalur pendaftaran yang dipilih.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .page-action {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .table td {
        padding: 0.5rem 0.25rem;
        border: none;
        vertical-align: middle;
    }

    .table td:first-child {
        font-weight: 600;
        color: #6c757d;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #e9ecef;
    }

    .timeline-item.completed .timeline-marker {
        box-shadow: 0 0 0 3px #28a745;
    }

    .timeline-item.failed .timeline-marker {
        box-shadow: 0 0 0 3px #dc3545;
    }

    .timeline-item.pending .timeline-marker {
        box-shadow: 0 0 0 3px #ffc107;
    }

    .timeline-content {
        padding-left: 1rem;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    @media (max-width: 768px) {
        .page-action {
            flex-direction: column;
        }

        .timeline {
            padding-left: 1.5rem;
        }

        .timeline-marker {
            left: -1.5rem;
            width: 1.5rem;
            height: 1.5rem;
        }
    }

    @media print {

        .page-action,
        .btn,
        .card-footer,
        #refreshProgress,
        .collapse {
            display: none !important;
        }

        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
            break-inside: avoid;
        }

        .timeline::before {
            background: #000 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Load progress on page load
    loadProgress();

    // Refresh progress button
    document.getElementById('refreshProgress').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Loading...';

        setTimeout(() => {
            loadProgress();
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Refresh';
        }, 1000);
    });

    // Load progress data
    function loadProgress() {
        fetch('{{ route("siswa.profile.completion") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            updateProgressCircle(data.overall);
            updateProgressDetails(data.completion);
        })
        .catch(error => {
            console.error('Error loading progress:', error);
            document.getElementById('progressDetails').innerHTML =
                '<div class="alert alert-danger small">Gagal memuat data progress</div>';
        });
    }

    // Update circular progress
    function updateProgressCircle(percentage) {
        const circle = document.getElementById('progressCircle');
        const percentText = document.getElementById('progressPercent');
        const circumference = 2 * Math.PI * 50; // radius = 50
        const offset = circumference - (percentage / 100) * circumference;

        circle.style.strokeDashoffset = offset;
        percentText.textContent = percentage + '%';

        // Change color based on completion
        if (percentage < 30) {
            circle.style.stroke = '#dc3545'; // red
        } else if (percentage < 70) {
            circle.style.stroke = '#ffc107'; // yellow
        } else {
            circle.style.stroke = '#28a745'; // green
        }
    }

    // Update progress details
    function updateProgressDetails(completion) {
        const detailsDiv = document.getElementById('progressDetails');

        const items = [
            { key: 'biodata', label: 'Biodata', icon: 'person-lines-fill' },
            { key: 'orang_tua', label: 'Data Keluarga', icon: 'house' },
            { key: 'berkas', label: 'Upload Berkas', icon: 'file-earmark-text' }
        ];

        let html = '';
        items.forEach(item => {
            const percentage = completion[item.key] || 0;
            const isCompleted = percentage >= 100;
            const iconClass = isCompleted ? 'check-circle-fill text-success' : 'circle text-muted';

            html += `
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-${iconClass} me-2"></i>
                        <span class="small">${item.label}</span>
                    </div>
                    <span class="badge ${isCompleted ? 'bg-success' : 'bg-warning'} small">${percentage}%</span>
                </div>
            `;
        });

        detailsDiv.innerHTML = html;
    }

    // Smooth scroll to edit profile
    const editButtons = document.querySelectorAll('a[href*="profile.edit"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Optional: Add loading state
            this.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Loading...';
        });
    });

    // Auto-refresh progress every 5 minutes
    setInterval(loadProgress, 300000);

    // Add floating action button for mobile
    if (window.innerWidth <= 768) {
        const fab = document.createElement('div');
        fab.className = 'position-fixed';
        fab.style.cssText = 'bottom: 20px; right: 20px; z-index: 1050;';
        fab.innerHTML = `
            <div class="dropdown">
                <button class="btn btn-primary rounded-circle" type="button" data-bs-toggle="dropdown"
                        style="width: 56px; height: 56px;">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('siswa.profile.edit') }}">
                        <i class="bi bi-pencil me-2"></i>Edit Profil
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('siswa.berkas.index') }}">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Berkas
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Cetak Profil
                    </a></li>
                </ul>
            </div>
        `;
        document.body.appendChild(fab);
    }
});
</script>
@endpush
@endsection
