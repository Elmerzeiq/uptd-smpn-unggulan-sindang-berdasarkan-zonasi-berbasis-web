@extends('layouts.siswa.app')

@section('title', 'Dashboard Pendaftaran SPMB')
@section('title_header_siswa', 'Dashboard Utama')

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header mb-3">
            <h4 class="page-title fw-bold">
                <i class="fas fa-user me-2"></i> Dashboard Calon Peserta Didik
            </h4>
        </div>

        {{-- Flash Messages --}}
        @if(session('warning'))
        <div class="alert alert-warning shadow alert-dismissible fade show">
            <i class="flaticon-warning me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @elseif(session('success'))
        <div class="alert alert-success shadow alert-dismissible fade show">
            <i class="flaticon-success me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @elseif(session('error'))
        <div class="alert alert-danger shadow alert-dismissible fade show">
            <i class="flaticon-error me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @else
        <div class="card card-dark bg-primary-gradient shadow mb-4">
            <div class="card-body">
                <h3 class="text-white fw-bold mb-1">
                    Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'Calon Siswa' }}!
                </h3>
                <p class="text-white mb-0">
                    Ini adalah halaman dashboard pendaftaran Anda. Mohon lengkapi semua tahapan sesuai jadwal.
                </p>
            </div>
        </div>
        @endif

        {{-- Catatan Panitia --}}
        @if(optional($user)->catatan_verifikasi)
        <div class="alert alert-danger shadow p-3 mb-4">
            <h4 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Catatan dari Panitia:
            </h4>
            <p>{!! nl2br(e($user->catatan_verifikasi)) !!}</p>
            @if(optional($user)->status_pendaftaran === 'berkas_tidak_lengkap')
            <hr>
            <p class="mb-0">Silakan perbaiki data atau berkas Anda melalui menu yang sesuai.</p>
            @endif
        </div>
        @endif

        {{-- Info Agama dan Berkas MDA --}}
        @if(optional($user->biodata)->agama && in_array(optional($user)->status_pendaftaran, ['akun_terdaftar',
        'menunggu_kelengkapan_data', 'berkas_tidak_lengkap']))
        <div class="alert alert-info shadow mb-4">
            <h5 class="alert-heading">
                <i class="fas fa-info-circle me-2"></i>Informasi Berkas Berdasarkan Agama
            </h5>
            <p class="mb-2">Agama Anda tercatat: <strong>{{ $user->biodata->agama }}</strong></p>
            @if(strtolower($user->biodata->agama) === 'islam')
            <p class="mb-0">
                <i class="fas fa-mosque me-1 text-success"></i>
                Berkas MDA (Madrasah Diniyah Awaliyah) bersifat <strong>opsional</strong> untuk siswa muslim.
                Anda dapat menguploadnya untuk melengkapi berkas, namun tidak wajib.
            </p>
            @else
            <p class="mb-0">
                <i class="fas fa-pray me-1 text-info"></i>
                Berkas keagamaan bersifat <strong>opsional</strong> untuk siswa {{ $user->biodata->agama }}.
                Anda dapat menyertakan surat keterangan dari pemimpin agama jika tersedia.
            </p>
            @endif
        </div>
        @endif

        {{-- Progress Bar --}}
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Progress Pendaftaran Anda ({{ $progressPercentage ?? 0 }}%)</h5>
                <small class="card-subtitle text-muted">
                    Langkah Selanjutnya: <strong>{{ $currentStepLabel ?? 'Memulai' }}</strong>
                </small>
            </div>
            <div class="card-body">
                <div class="progress mb-4" style="height: 30px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated
                        @if(($progressPercentage ?? 0) >= 100) bg-success
                        @elseif(($progressPercentage ?? 0) >= 66) bg-info
                        @elseif(($progressPercentage ?? 0) >= 33) bg-primary
                        @else bg-warning @endif" style="width: {{ $progressPercentage ?? 0 }}%; font-size: 0.9rem;">
                        {{ $progressPercentage ?? 0 }}% Selesai
                    </div>
                </div>

                {{-- Flow Proses Pendaftaran --}}
                <div class="alert alert-light border-left-primary mb-4">
                    <h6 class="alert-heading"><i class="fas fa-route me-2"></i>Alur Proses Pendaftaran SPMB</h6>
                    <div class="row text-center">
                        <div class="col-md-2">
                            <div class="step-box {{ optional($user)->biodata ? 'completed' : 'current' }}">
                                <i class="fas fa-user-edit"></i>
                                <small>1. Isi Biodata</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div
                                class="step-box {{ optional($user)->berkas ? 'completed' : (optional($user)->biodata ? 'current' : 'pending') }}">
                                <i class="fas fa-upload"></i>
                                <small>2. Upload Berkas</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div
                                class="step-box {{ in_array(optional($user)->status_pendaftaran, ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi']) ? 'completed' : 'pending' }}">
                                <i class="fas fa-check-circle"></i>
                                <small>3. Verifikasi Admin</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div
                                class="step-box {{ optional($user)->status_pendaftaran === 'berkas_diverifikasi' ? 'completed' : 'pending' }}">
                                <i class="fas fa-download"></i>
                                <small>4. Download Kartu</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div
                                class="step-box {{ in_array(optional($user)->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus_seleksi']) ? 'completed' : 'pending' }}">
                                <i class="fas fa-bullhorn"></i>
                                <small>5. Pengumuman</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div
                                class="step-box {{ optional($user)->status_pendaftaran === 'lulus_seleksi' ? 'current' : 'pending' }}">
                                <i class="fas fa-graduation-cap"></i>
                                <small>6. Daftar Ulang</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <strong>Keterangan:</strong> Setelah mengisi biodata dan berkas, data akan diverifikasi admin.
                        Jika lolos verifikasi, Anda dapat download kartu pendaftaran. Saat pengumuman dibuka,
                        Anda dapat melihat hasil seleksi. Jika diterima, lakukan daftar ulang di website atau langsung
                        ke sekolah.
                    </small>
                </div>

                {{-- Langkah Pendaftaran --}}
                <div class="list-group list-group-flush">
                    @php
                    $canFillData = isset($jadwalAktif) && $jadwalAktif && $jadwalAktif->isPendaftaranOpen();
                    $canSeeAnnouncement = isset($jadwalAktif) && $jadwalAktif && $jadwalAktif->isPengumumanOpen();
                    $canViewInitialCard = !empty(optional($user)->jalur_pendaftaran) && optional($user)->biodata &&
                    optional($user)->orangTua;
                    $canDownloadFinalCard = optional($user)->status_pendaftaran === 'lulus_seleksi' &&
                    isset($jadwalAktif) && $jadwalAktif && ($jadwalAktif->isDaftarUlangOpen() ||
                    $jadwalAktif->isPengumumanOpen());
                    $userKartu = optional($user)->kartuPendaftaran()->first();

                    // PERBAIKAN: Status untuk allow edit yang konsisten dengan sidebar
                    $finalStatusesForBiodata = ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi',
                    'daftar_ulang_selesai'];
                    $allowEditBiodata = $canFillData && !in_array(optional($user)->status_pendaftaran,
                    $finalStatusesForBiodata);

                    // Berkas bisa diakses selama masa pendaftaran terbuka, kecuali sudah final
                    $allowUploadBerkas = $canFillData && !in_array(optional($user)->status_pendaftaran, [
                    'lulus_seleksi',
                    'tidak_lulus_seleksi',
                    'daftar_ulang_selesai'
                    ]);

                    // Jika status berkas_tidak_lengkap, masih bisa edit keduanya
                    if (optional($user)->status_pendaftaran === 'berkas_tidak_lengkap') {
                    $allowEditBiodata = $canFillData;
                    $allowUploadBerkas = $canFillData;
                    }

                    // Untuk compatibility dengan kode lama
                    $allowEdit = $allowEditBiodata;
                    @endphp

                    @foreach($progressSteps as $key => $step)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div
                                class="avatar avatar-sm me-3 {{ $step['is_complete'] ? 'avatar-online' : 'avatar-offline' }}">
                                <span
                                    class="avatar-title rounded-circle border border-white {{ $step['is_complete'] ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="{{ $step['icon'] ?? 'fas fa-question' }} text-white"></i>
                                </span>
                            </div>
                            <div>
                                <strong>{{ $step['label'] }}</strong>
                                @if($key === 'verifikasi_panitia' && optional($user)->status_pendaftaran ===
                                'berkas_tidak_lengkap')
                                <br><small class="text-danger">Ada data yang perlu diperbaiki.</small>
                                @elseif($key === 'verifikasi_panitia' && optional($user)->status_pendaftaran ===
                                'menunggu_verifikasi_berkas')
                                <br><small class="text-warning">Menunggu verifikasi panitia.</small>
                                @elseif($key === 'pengumuman_hasil' && in_array(optional($user)->status_pendaftaran,
                                ['lulus_seleksi', 'tidak_lulus_seleksi']))
                                <br><small class="text-info">Hasil seleksi telah tersedia.</small>
                                @elseif($key === 'daftar_ulang' && optional($user)->status_pendaftaran ===
                                'lulus_seleksi' && isset($jadwalAktif) && $jadwalAktif &&
                                $jadwalAktif->isDaftarUlangOpen())
                                <br><small class="text-success">Masa daftar ulang sedang berlangsung.</small>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($step['is_complete'])
                            <span class="badge bg-success rounded-pill">
                                <i class="fas fa-check-circle me-1"></i>Selesai
                            </span>
                            @if(($key === 'isi_biodata' || $key === 'upload_berkas') && (($key === 'isi_biodata' &&
                            $allowEditBiodata) || ($key === 'upload_berkas' && $allowUploadBerkas)) &&
                            isset($step['link']))
                            <a href="{{ $step['link'] }}" class="p-0 btn btn-link btn-sm text-primary ms-2"
                                data-bs-toggle="tooltip" title="Lihat/Edit Data">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            @endif
                            @else
                            @if($key === 'isi_biodata' && $allowEditBiodata && isset($step['link']))
                            <a href="{{ $step['link'] }}" class="btn btn-primary btn-sm btn-round">
                                @if(optional($user)->status_pendaftaran === 'berkas_tidak_lengkap')
                                <i class="fas fa-edit me-1"></i> Perbaiki
                                @else
                                <i class="fas fa-arrow-right me-1"></i> Kerjakan
                                @endif
                            </a>
                            @elseif($key === 'upload_berkas' && isset($step['condition']) && $step['condition'] &&
                            $allowUploadBerkas && isset($step['link']))
                            <a href="{{ $step['link'] }}" class="btn btn-primary btn-sm btn-round">
                                @if(optional($user)->status_pendaftaran === 'berkas_tidak_lengkap')
                                <i class="fas fa-edit me-1"></i> Perbaiki
                                @else
                                <i class="fas fa-arrow-right me-1"></i> Kerjakan
                                @endif
                            </a>
                            @elseif($key === 'verifikasi_panitia' && optional($user)->status_pendaftaran ===
                            'berkas_tidak_lengkap')
                            <span class="badge bg-danger rounded-pill">Perlu Perbaikan</span>
                            @elseif($key === 'verifikasi_panitia' && optional($user)->status_pendaftaran ===
                            'menunggu_verifikasi_berkas')
                            <span class="badge bg-warning text-dark rounded-pill">Menunggu Diverifikasi</span>
                            @elseif($key === 'pengumuman_hasil' && $canSeeAnnouncement && isset($step['link']))
                            <a href="{{ $step['link'] }}" class="btn btn-info btn-sm btn-round">Lihat Hasil</a>
                            @elseif($key === 'daftar_ulang' && $canDownloadFinalCard && isset($step['link']))
                            <a href="{{ route('siswa.kartu-pendaftaran.show', ['type' => 'final']) }}"
                                class="btn btn-success btn-sm btn-round">
                                <i class="fas fa-print me-1"></i> Unduh Kartu Final
                            </a>
                            @else
                            <span class="badge bg-secondary rounded-pill">Belum Tersedia</span>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            {{-- DIPERLEBAR: Rangkuman Pendaftaran --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Rangkuman Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">No. Pendaftaran</dt>
                                    <dd class="col-sm-7">: <strong>{{ optional($user)->no_pendaftaran ?? '-' }}</strong>
                                    </dd>

                                    <dt class="col-sm-5">Nama Lengkap</dt>
                                    <dd class="col-sm-7">: {{ optional($user)->nama_lengkap ?? '-' }}</dd>

                                    <dt class="col-sm-5">NISN</dt>
                                    <dd class="col-sm-7">: {{ optional($user)->nisn ?? '-' }}</dd>

                                    <dt class="col-sm-5">Jalur Pilihan</dt>
                                    <dd class="col-sm-7">: <strong>{{ ucwords(str_replace('_', ' ',
                                            optional($user)->jalur_pendaftaran ?? '-')) }}</strong>
                                        @if(optional($user)->jalur_pendaftaran === 'domisili' &&
                                        optional($user)->kecamatan_domisili)
                                        <br><small class="text-muted">(Kec. {{
                                            ucfirst(optional($user)->kecamatan_domisili) }})</small>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row mb-0">
                                    @if(optional($user->biodata)->agama)
                                    <dt class="col-sm-5">Agama</dt>
                                    <dd class="col-sm-7">: {{ optional($user->biodata)->agama }}
                                        @if(strtolower(optional($user->biodata)->agama) === 'islam')
                                        <br><small class="text-muted">(Berkas MDA opsional)</small>
                                        @endif
                                    </dd>
                                    @endif

                                    <dt class="col-sm-5">Status Pendaftaran</dt>
                                    <dd class="col-sm-7">:
                                        <span class="badge
                                            @if(optional($user)->status_pendaftaran === 'lulus_seleksi') bg-success
                                            @elseif(in_array(optional($user)->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap'])) bg-danger
                                            @elseif(in_array(optional($user)->status_pendaftaran, ['berkas_diverifikasi'])) bg-info
                                            @else bg-warning text-dark @endif">
                                            {{ ucwords(str_replace('_', ' ', optional($user)->status_pendaftaran ??
                                            'Belum Ada')) }}
                                        </span>
                                    </dd>

                                    @if(optional($user)->jalur_pendaftaran === 'domisili' &&
                                    optional($user)->jarak_ke_sekolah)
                                    <dt class="col-sm-5">Jarak ke Sekolah</dt>
                                    <dd class="col-sm-7">: <strong>{{ number_format(optional($user)->jarak_ke_sekolah,
                                            2) }} KM</strong></dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ENHANCED PETA ZONASI SECTION DIPERLEBAR --}}
                @if(optional($user)->jalur_pendaftaran === 'domisili')
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marked-alt me-2"></i>Peta Zonasi Domisili
                        </h5>
                        @if(isset($zonasiData['student_zone']))
                        <small class="text-muted">
                            Status Zona:
                            <span class="badge" style="background-color: {{ $zonasiData['student_zone']['color'] }}">
                                {{ $zonasiData['student_zone']['name'] }}
                            </span>
                            @if(isset($zonasiData['distance']))
                            | Jarak: <strong>{{ $zonasiData['distance'] }} km</strong>
                            @endif
                        </small>
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- Info Zonasi --}}
                        @if(isset($zonasiData['student_zone']))
                        <div class="alert alert-info mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>🎯 Zona Anda:</strong> {{ $zonasiData['student_zone']['name'] }}<br>
                                    <small>{{ $zonasiData['student_zone']['description'] }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>📊 Prioritas:</strong> Level {{ $zonasiData['priority_level'] ?? 'N/A'
                                    }}<br>
                                    <small>Kuota zona: ~{{ $zonasiData['student_zone']['quota_percentage'] ?? 0 }}% dari
                                        total</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- PETA DIPERLEBAR --}}
                        <div id="mapDomisiliSiswaDashboard"
                            style="height: 500px; width: 100%; border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa;">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Memuat peta zonasi...</p>
                                </div>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <div class="row mt-4">
                            <div class="col-md-8">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Peta menunjukkan zona berdasarkan jarak dari sekolah.
                                    <br>
                                    <span class="badge bg-primary" style="font-size: 10px;">🏫 Biru</span> = Sekolah,
                                    <span class="badge bg-danger" style="font-size: 10px;">🏠 Merah</span> = Domisili
                                    Anda
                                </small>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-wrap gap-2">
                                    @if(isset($zonasiData['zones']))
                                    @foreach($zonasiData['zones'] as $zona)
                                    <small class="badge" style="background-color: {{ $zona['color'] }}">
                                        {{ $zona['name'] }}
                                    </small>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Debug Info (hanya tampil di development) --}}
                        @if(config('app.debug'))
                        <div class="mt-3 p-2 bg-light rounded">
                            <small class="text-muted">
                                <strong>Debug Info:</strong><br>
                                Koordinat Sekolah: {{ json_encode($koordinatSekolah ?? []) }}<br>
                                Koordinat Siswa: {{ json_encode($koordinatSiswa ?? []) }}<br>
                                Zona Data: {{ json_encode($zonasiData ?? []) }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marked-alt me-2"></i>Peta Zonasi Domisili
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">Peta zonasi hanya tersedia untuk jalur pendaftaran
                                <strong>Domisili</strong>.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- DIPERKECIL: Jadwal PPDB Aktif --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="far fa-calendar-alt me-2"></i>Jadwal SPMB Aktif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($jadwalAktif) && $jadwalAktif)
                        <ul class="list-unstyled mb-0">
                            <li><strong>Tahun Ajaran:</strong> {{ $jadwalAktif->tahun_ajaran }}</li>
                            <li class="mt-2"><strong>Pendaftaran:</strong> <br>
                                <small>{{ $jadwalAktif->pembukaan_pendaftaran->format('d M Y H:i') }} -
                                    {{ $jadwalAktif->penutupan_pendaftaran->format('d M Y H:i') }}</small>
                            </li>
                            <li class="mt-2"><strong>Pengumuman Hasil:</strong> <br>
                                <small>{{ $jadwalAktif->pengumuman_hasil->format('d M Y H:i') }}</small>
                            </li>
                            <li class="mt-2"><strong>Daftar Ulang:</strong> <br>
                                <small>{{ $jadwalAktif->mulai_daftar_ulang->format('d M Y H:i') }} -
                                    {{ $jadwalAktif->selesai_daftar_ulang->format('d M Y H:i') }}</small>
                            </li>
                        </ul>
                        @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>Jadwal belum tersedia.
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                {{-- <div class="card shadow mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                        $canEditBiodata = isset($jadwalAktif) && $jadwalAktif && $jadwalAktif->isPendaftaranOpen() &&
                        !in_array(optional($user)->status_pendaftaran, ['berkas_diverifikasi', 'lulus_seleksi',
                        'tidak_lulus_seleksi', 'daftar_ulang_selesai']);
                        $canUploadBerkas = isset($jadwalAktif) && $jadwalAktif && $jadwalAktif->isPendaftaranOpen() &&
                        !in_array(optional($user)->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus_seleksi',
                        'daftar_ulang_selesai']);

                        if (optional($user)->status_pendaftaran === 'berkas_tidak_lengkap') {
                        $canEditBiodata = isset($jadwalAktif) && $jadwalAktif && $jadwalAktif->isPendaftaranOpen();
                        $canUploadBerkas = isset($jadwalAktif) && $jadwalAktif && $jadwalAktif->isPendaftaranOpen();
                        }
                        @endphp

                        @if(!optional($user)->biodata || !optional($user)->orangTua)
                        <a href="{{ route('siswa.biodata.index') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-user-edit me-2"></i>Lengkapi Biodata
                        </a>
                        @elseif($canEditBiodata)
                        <a href="{{ route('siswa.biodata.index') }}" class="btn btn-outline-primary btn-block mb-2">
                            <i class="fas fa-edit me-2"></i>Edit Biodata
                        </a>
                        @endif

                        @if(optional($user)->biodata && optional($user)->orangTua && $canUploadBerkas)
                        <a href="{{ route('siswa.berkas.index') }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-upload me-2"></i>Upload Berkas
                        </a>
                        @endif

                        @if(optional($user)->status_pendaftaran === 'berkas_diverifikasi')
                        <a href="{{ route('siswa.kartu-pendaftaran.show') }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-download me-2"></i>Download Kartu
                        </a>
                        @endif

                        @if(in_array(optional($user)->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus_seleksi']))
                        <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-bullhorn me-2"></i>Lihat Pengumuman
                        </a>
                        @endif

                        @if(optional($user)->status_pendaftaran === 'lulus_seleksi')
                        <a href="{{ route('siswa.daftar-ulang.index') }}" class="btn btn-gradient-success btn-block">
                            <i class="fas fa-graduation-cap me-2"></i>Daftar Ulang
                        </a>
                        @endif
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Step Flow Styles */
    .step-box {
        padding: 15px 10px;
        margin: 5px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }

    .step-box.completed {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-color: #28a745;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    .step-box.current {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
        color: white;
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        animation: pulse-glow 2s infinite;
    }

    .step-box.pending {
        background: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }

    .step-box i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 8px;
    }

    .step-box small {
        font-weight: 600;
        font-size: 0.8rem;
    }

    @keyframes pulse-glow {
        0% {
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        50% {
            box-shadow: 0 8px 16px rgba(0, 123, 255, 0.5);
        }

        100% {
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }
    }

    /* Border left for alert */
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }

    /* Custom Blue School Marker */
    .custom-school-marker {
        width: 35px;
        height: 45px;
        position: relative;
        transform: translate(-50%, -100%);
    }

    .school-pin {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
        border: 3px solid white;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        top: 0;
        left: 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transform: rotate(-45deg);
        animation: bounce 2s infinite;
    }

    .school-pin::after {
        content: '🏫';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        font-size: 16px;
        color: white;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    /* Custom Red Student Marker */
    .custom-student-marker {
        width: 30px;
        height: 40px;
        position: relative;
        transform: translate(-50%, -100%);
    }

    .student-pin {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        border: 3px solid white;
        border-radius: 50% 50% 50% 0;
        position: absolute;
        top: 0;
        left: 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transform: rotate(-45deg);
        animation: pulse 2s infinite;
    }

    .student-pin::after {
        content: '🏠';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        font-size: 14px;
        color: white;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0) rotate(-45deg);
        }

        40% {
            transform: translateY(-10px) rotate(-45deg);
        }

        60% {
            transform: translateY(-5px) rotate(-45deg);
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 0 0 0 rgba(220, 53, 69, 0.7);
        }

        70% {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 0 0 15px rgba(220, 53, 69, 0);
        }

        100% {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    /* Distance line animation */
    .animated-line {
        stroke-dasharray: 10, 5;
        animation: dash 2s linear infinite;
    }

    @keyframes dash {
        to {
            stroke-dashoffset: -15;
        }
    }

    /* Gradient button */
    .btn-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        font-weight: 600;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1dc97f 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }
</style>
@endpush

@push('scripts')
<script>
    // Enhanced Map Initialization with Custom Red & Blue Markers
let mapInstance = null;
let zonasiData = null;

document.addEventListener('DOMContentLoaded', function() {
    console.log('🗺️ Initializing Enhanced Zonasi Map with Custom Markers...');

    const mapElement = document.getElementById('mapDomisiliSiswaDashboard');
    if (!mapElement) {
        console.error('❌ Map element not found');
        return;
    }

    // Check Leaflet
    if (typeof L === 'undefined') {
        console.error('❌ Leaflet library not loaded');
        mapElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error loading map library</div></div>';
        return;
    }

    // Data dari PHP
    const mapData = {
        jalurPendaftaran: "{{ optional($user)->jalur_pendaftaran ?? '' }}",
        koordinatSekolah: @json($koordinatSekolah ?? ['lat' => -6.3390, 'lng' => 108.3225]),
        koordinatSiswa: @json($koordinatSiswa ?? null),
        canShowMap: {{ isset($canShowMap) && $canShowMap ? 'true' : 'false' }},
        zonasiData: @json($zonasiData ?? [])
    };

    console.log('📍 Map Data:', mapData);

    // Validasi data
    if (!mapData.canShowMap || mapData.jalurPendaftaran !== 'domisili') {
        console.log('Map not available for this registration path');
        return;
    }

    if (!mapData.koordinatSekolah || !mapData.koordinatSekolah.lat || !mapData.koordinatSekolah.lng) {
        console.error('Invalid school coordinates');
        mapElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="text-danger">Error: Invalid school coordinates</div></div>';
        return;
    }

    try {
        initializeEnhancedMapWithCustomMarkers(mapData);
    } catch (error) {
        console.error('❌ Error initializing map:', error);
        mapElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="text-danger">Error: ' + error.message + '</div></div>';
    }
});

function initializeEnhancedMapWithCustomMarkers(mapData) {
    const mapElement = document.getElementById('mapDomisiliSiswaDashboard');

    // Tentukan center dan zoom
    let centerLat, centerLng, zoom;

    if (mapData.koordinatSiswa && mapData.koordinatSiswa.lat && mapData.koordinatSiswa.lng) {
        centerLat = (mapData.koordinatSekolah.lat + mapData.koordinatSiswa.lat) / 2;
        centerLng = (mapData.koordinatSekolah.lng + mapData.koordinatSiswa.lng) / 2;
        zoom = 13;
    } else {
        centerLat = mapData.koordinatSekolah.lat;
        centerLng = mapData.koordinatSekolah.lng;
        zoom = 14;
    }

    // Inisialisasi peta
    mapInstance = L.map(mapElement, {
        center: [centerLat, centerLng],
        zoom: zoom,
        zoomControl: true,
        scrollWheelZoom: true
    });

    // Tambahkan tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapInstance);

    // Tambahkan zona circles jika data zonasi tersedia
    if (mapData.zonasiData && mapData.zonasiData.zones) {
        mapData.zonasiData.zones.forEach((zona, index) => {
            if (zona.max_distance !== null && zona.max_distance < 1000) { // Hanya zona dengan jarak wajar
                const circle = L.circle([mapData.koordinatSekolah.lat, mapData.koordinatSekolah.lng], {
                    radius: zona.max_distance * 1000, // Convert km to meters
                    fillColor: zona.color,
                    color: zona.color,
                    weight: 2,
                    opacity: 0.8,
                    fillOpacity: 0.15
                }).addTo(mapInstance);

                circle.bindPopup(`
                    <div class="text-center">
                        <strong style="color: ${zona.color}">${zona.name}</strong><br>
                        <small>${zona.description}</small><br>
                        <small><strong>Kuota:</strong> ~${zona.quota_percentage}% dari total</small>
                    </div>
                `);
            }
        });
    }

    // =========================================
    // CUSTOM BLUE SCHOOL MARKER (🏫)
    // =========================================
    const schoolIcon = L.divIcon({
        className: 'custom-school-marker',
        html: '<div class="school-pin"></div>',
        iconSize: [35, 45],
        iconAnchor: [17.5, 45],
        popupAnchor: [0, -45]
    });

    // Marker sekolah
    const schoolMarker = L.marker([mapData.koordinatSekolah.lat, mapData.koordinatSekolah.lng], {
        icon: schoolIcon
    }).addTo(mapInstance);

    schoolMarker.bindPopup(`
        <div class="text-center">
            <strong style="color: #0d6efd;">🏫 Cerulean School</strong><br>
            <small>Pusat Zonasi SPMB</small><br>
            <small class="text-muted">Kec. Sindang, Kab. Indramayu</small>
        </div>
    `);

    // =========================================
    // CUSTOM RED STUDENT MARKER (🏠)
    // =========================================
    if (mapData.koordinatSiswa && mapData.koordinatSiswa.lat && mapData.koordinatSiswa.lng) {
        const studentIcon = L.divIcon({
            className: 'custom-student-marker',
            html: '<div class="student-pin"></div>',
            iconSize: [30, 40],
            iconAnchor: [15, 40],
            popupAnchor: [0, -40]
        });

        const studentMarker = L.marker([mapData.koordinatSiswa.lat, mapData.koordinatSiswa.lng], {
            icon: studentIcon
        }).addTo(mapInstance);

        let popupContent = '<div class="text-center"><strong style="color: #dc3545;">🏠 Lokasi Domisili Anda</strong>';

        if (mapData.zonasiData && mapData.zonasiData.student_zone) {
            popupContent += `<br><small>Zona: <span style="color: ${mapData.zonasiData.student_zone.color}"><strong>${mapData.zonasiData.student_zone.name}</strong></span></small>`;
        }

        if (mapData.zonasiData && mapData.zonasiData.distance) {
            popupContent += `<br><small>Jarak: <strong>${mapData.zonasiData.distance} km</strong></small>`;

            // Tambahkan info prioritas
            if (mapData.zonasiData.student_zone) {
                let priorityText = '';
                switch(mapData.zonasiData.priority_level) {
                    case 1: priorityText = 'Prioritas Tinggi'; break;
                    case 2: priorityText = 'Prioritas Sedang'; break;
                    case 3: priorityText = 'Prioritas Rendah'; break;
                    case 4: priorityText = 'Tidak Diprioritaskan'; break;
                    default: priorityText = 'Prioritas Unknown';
                }
                popupContent += `<br><small><strong>Status:</strong> ${priorityText}</small>`;
            }
        }

        popupContent += '</div>';

        studentMarker.bindPopup(popupContent);

        // =========================================
        // ANIMATED DISTANCE LINE
        // =========================================
        const polyline = L.polyline([
            [mapData.koordinatSekolah.lat, mapData.koordinatSekolah.lng],
            [mapData.koordinatSiswa.lat, mapData.koordinatSiswa.lng]
        ], {
            color: '#007bff',
            weight: 4,
            opacity: 0.8,
            dashArray: '10, 5'
        }).addTo(mapInstance);

        // Add CSS class for animation
        polyline.getElement().classList.add('animated-line');

        polyline.bindPopup(`
            <div class="text-center">
                <strong>📏 Jarak Domisili ke Sekolah</strong><br>
                <span style="font-size: 1.2em; color: #007bff;"><strong>${mapData.zonasiData.distance || 'N/A'} km</strong></span>
                ${mapData.zonasiData && mapData.zonasiData.student_zone ?
                    `<br><small>Zona: ${mapData.zonasiData.student_zone.name}</small>` : ''
                }
            </div>
        `);

        // Fit bounds untuk semua markers
        const group = new L.featureGroup([schoolMarker, studentMarker]);
        mapInstance.fitBounds(group.getBounds().pad(0.1));
    } else {
        // Jika tidak ada koordinat siswa, buka popup sekolah
        setTimeout(() => {
            schoolMarker.openPopup();
        }, 1000);
    }

    // Refresh ukuran peta
    setTimeout(function() {
        if (mapInstance) {
            mapInstance.invalidateSize();
        }
    }, 500);

    console.log('✅ Enhanced Zonasi Map with Custom Markers initialized successfully');
}
</script>
@endpush
