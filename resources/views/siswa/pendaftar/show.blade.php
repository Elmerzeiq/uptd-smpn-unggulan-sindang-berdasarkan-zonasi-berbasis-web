@extends('layouts.siswa.app')
@section('title', 'Detail Pendaftar: ' . $user->nama_lengkap)

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header mb-4">
            <h3 class="page-title fw-bold text-primary">
                <i class="fas fa-id-card me-2"></i> Detail Pendaftar SPMB
                @if(Auth::id() == $user->id)
                <span class="badge bg-warning text-dark ms-2">Data Saya</span>
                @endif
            </h3>

        </div>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($user->catatan_verifikasi)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Catatan dari Admin:</strong><br>
            {!! nl2br(e($user->catatan_verifikasi)) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-4">
            <div class="col-md-8">
                <!-- Header Card with Photo -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">
                            <i class="bi bi-person-badge me-2"></i>{{ $user->nama_lengkap }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary">Identitas Pendaftar</h6>
                                        <p class="mb-1"><strong>No. Pendaftaran:</strong>
                                            <span class="badge bg-dark fs-6">{{ $user->no_pendaftaran }}</span>
                                        </p>
                                        <p class="mb-1"><strong>NISN:</strong> {{ $user->nisn }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                                        <p class="mb-1"><strong>Jalur Pilihan:</strong>
                                            <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ',
                                                $user->jalur_pendaftaran)) }}</span>
                                            @if($user->jalur_pendaftaran == 'domisili' && $user->kecamatan_domisili)
                                            <br><small class="text-muted">Kec. {{ ucfirst($user->kecamatan_domisili)
                                                }}</small>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold text-primary">Status & Waktu</h6>
                                        <p class="mb-1"><strong>Status Pendaftaran:</strong><br>
                                            <span class="badge
                                                @if($user->status_pendaftaran == 'lulus_seleksi') bg-success
                                                @elseif(in_array($user->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap'])) bg-danger
                                                @elseif(in_array($user->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi'])) bg-info
                                                @else bg-warning @endif fs-6 px-3 py-2">
                                                {{ ucwords(str_replace('_', ' ', $user->status_pendaftaran ?? 'N/A')) }}
                                            </span>
                                        </p>
                                        <p class="mb-1"><strong>Tgl Pendaftaran:</strong> {{
                                            $user->created_at->format('d M Y, H:i') }}</p>
                                        @if($user->updated_at != $user->created_at)
                                        <p class="mb-1"><strong>Update Terakhir:</strong> {{
                                            $user->updated_at->format('d M Y, H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                @if($user->berkas && $user->berkas->file_pas_foto)
                                <img src="{{ Storage::url($user->berkas->file_pas_foto) }}" alt="Foto Siswa"
                                    class="img-thumbnail shadow-sm border-3 border-primary"
                                    style="max-height: 200px; max-width: 150px; border-radius: 15px;">
                                @else
                                <div class="text-muted border border-3 border-dashed p-4 rounded-3 text-center bg-light"
                                    style="width: 150px; height: 200px; display: flex; align-items: center; justify-content: center; flex-direction: column; margin: 0 auto;">
                                    <i class="bi bi-person fs-1"></i>
                                    <small>Foto Belum Ada</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biodata -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-lines-fill me-2"></i>Biodata Pribadi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->biodata)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tempat, Tgl Lahir:</strong>
                                    {{ $user->biodata->tempat_lahir ?? '-' }}{{ $user->biodata->tgl_lahir ? ', ' .
                                    $user->biodata->tgl_lahir->format('d M Y') : '' }}
                                </p>
                                <p class="mb-2"><strong>Jenis Kelamin:</strong>
                                    {{ $user->biodata->jns_kelamin == 'L' ? 'Laki-laki' : ($user->biodata->jns_kelamin
                                    == 'P' ? 'Perempuan' : '-') }}
                                </p>
                                <p class="mb-2"><strong>Agama:</strong> {{ $user->biodata->agama ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Asal Sekolah:</strong> {{ $user->biodata->asal_sekolah ?? '-' }}
                                </p>
                                <p class="mb-2"><strong>Anak Ke:</strong> {{ $user->biodata->anak_ke ?? '-' }}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="mb-2"><strong>Alamat Rumah:</strong> {{ $user->biodata->alamat_rumah ?? '-' }}
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Biodata belum dilengkapi.
                            @if(Auth::id() == $user->id)
                            <a href="{{ route('siswa.pendaftar.edit', $user->id) }}">Lengkapi sekarang</a>.
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Data Keluarga -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-house me-2"></i>Data Keluarga
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->orangTua)
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary">Data Ayah</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nama:</strong></td>
                                        <td>{{ $user->orangTua->nama_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP:</strong></td>
                                        <td>{{ $user->orangTua->no_hp_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pekerjaan:</strong></td>
                                        <td>{{ $user->orangTua->pekerjaan_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pendidikan:</strong></td>
                                        <td>{{ $user->orangTua->pendidikan_ayah ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary">Data Ibu</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nama:</strong></td>
                                        <td>{{ $user->orangTua->nama_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP:</strong></td>
                                        <td>{{ $user->orangTua->no_hp_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pekerjaan:</strong></td>
                                        <td>{{ $user->orangTua->pekerjaan_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pendidikan:</strong></td>
                                        <td>{{ $user->orangTua->pendidikan_ibu ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Data orang tua belum diisi.
                            @if(Auth::id() == $user->id)
                            <a href="{{ route('siswa.pendaftar.edit', $user->id) }}">Lengkapi sekarang</a>.
                            @endif
                        </div>
                        @endif

                        @if($user->wali)
                        <hr>
                        <h6 class="fw-bold text-primary">Data Wali</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nama Wali:</strong> {{ $user->wali->nama_wali ?? '-' }}</p>
                                <p class="mb-1"><strong>Hubungan:</strong> {{
                                    $user->wali->hubungan_wali_dgn_calon_peserta ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>No. HP:</strong> {{ $user->wali->no_hp_wali ?? '-' }}</p>
                                <p class="mb-1"><strong>Alamat:</strong> {{ $user->wali->alamat_wali ?? '-' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Berkas Persyaratan -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Berkas Persyaratan
                            <span class="badge bg-secondary ms-2">Jalur {{ ucwords(str_replace('_', ' ',
                                $user->jalur_pendaftaran)) }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->berkas)
                        <div class="row g-3">
                            @foreach($berkasList as $field => $details)
                            @if(!isset($details['required']) || $details['required'] === false ||
                            ($details['required'] && ($field !== 'file_ijazah_mda_pernyataan' && $field !==
                            'file_suket_baca_quran_mda' || $user->biodata && $user->biodata->agama ===
                            'Islam')))
                            <div class="col-md-6">
                                <div
                                    class="card h-100 {{ $user->berkas && $user->berkas->$field ? 'border-success' : 'border-warning' }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1">{{ $details['label'] }}</h6>
                                                @if(!isset($details['required']) || !$details['required'])
                                                @if($field === 'file_ijazah_mda_pernyataan' || $field === 'file_suket_baca_quran_mda')
                                                <small class="text-muted">(Opsional)</small>
                                                @else
                                                <small class="text-danger">(WAJIB DIISI)</small>
                                                @endif
                                                @else
                                                <small class="text-muted">(Opsional)</small>
                                                @endif
                                            </div>
                                            <div>
                                                @if($user->berkas && $user->berkas->$field)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Ada
                                                </span>
                                                @else
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-x-circle me-1"></i>Belum
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($user->berkas && $user->berkas->$field)
                                        @if(isset($details['multiple']) && $details['multiple'])
                                        <?php $sertifikatPaths = json_decode($user->berkas->$field, true) ?: [$user->berkas->$field]; ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($sertifikatPaths as $index => $path)
                                            <a href="{{ Storage::url($path) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> File {{ $index + 1 }}
                                            </a>
                                            @endforeach
                                        </div>
                                        @else
                                        <a href="{{ Storage::url($user->berkas->$field) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> Lihat File
                                        </a>
                                        @endif
                                        @else
                                        <span class="text-muted small"><i>Belum diupload</i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-folder-x fs-1"></i>
                            <h5 class="mt-2">Belum Ada Berkas</h5>
                            <p>Berkas persyaratan belum diupload.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-md-4">
                @if(Auth::id() == $user->id)
                <!-- Panel untuk data sendiri -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-person-check me-2"></i>Panel Saya
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('siswa.pendaftar.edit', $user->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-fill me-2"></i>Edit Data Saya
                            </a>
                            <a href="{{ route('siswa.profile.show') }}" class="btn btn-info">
                                <i class="bi bi-person-circle me-2"></i>Lihat Profil Lengkap
                            </a>
                            {{-- <button class="btn btn-secondary" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Cetak Data
                            </button> --}}
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-2">Status Terkini:</h6>
                        <span
                            class="badge bg-{{ $user->status_pendaftaran == 'lulus_seleksi' ? 'success' :
                            (in_array($user->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap']) ? 'danger' :
                            (in_array($user->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi']) ? 'info' : 'warning')) }} fs-6 w-100 p-2">
                            {{ ucwords(str_replace('_', ' ', $user->status_pendaftaran ?? 'N/A')) }}
                        </span>
                    </div>
                </div>
                @else
                <!-- Panel untuk data siswa lain -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-info-circle me-2"></i>Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light small mb-0">
                            <i class="bi bi-eye me-1"></i>
                            Anda sedang melihat data siswa lain. Untuk melihat atau edit data Anda sendiri,
                            <a href="{{ route('siswa.profile.show') }}">klik di sini</a>.
                        </div>
                    </div>
                </div>
                @endif

                <!-- Progress & Statistics -->
                <div class="card border-0 shadow">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-bar-chart me-2"></i>Statistik
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $berkasUploaded = 0;
                        $totalBerkas = count($berkasList);
                        if ($user->berkas) {
                            foreach ($berkasList as $field => $details) {
                                if ($user->berkas->$field) {
                                    $berkasUploaded++;
                                }
                            }
                        }
                        $berkasPercentage = $totalBerkas > 0 ? round(($berkasUploaded / $totalBerkas) * 100) : 0;
                        ?>

                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h5 class="text-primary mb-0">{{ $berkasUploaded }}/{{ $totalBerkas }}</h5>
                                    <small>Berkas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <h5 class="text-success mb-0">{{ $berkasPercentage }}%</h5>
                                    <small>Kelengkapan</small>
                                </div>
                            </div>
                        </div>

                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $berkasPercentage }}%" aria-valuenow="{{ $berkasPercentage }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">Kelengkapan berkas persyaratan</small>

                        <hr>
                        <h6 class="fw-bold">Riwayat Akun</h6>
                        <small class="d-block">Dibuat: {{ $user->created_at->format('d M Y, H:i') }}</small>
                        @if($user->updated_at != $user->created_at)
                        <small class="d-block text-info">Diperbarui: {{ $user->updated_at->diffForHumans() }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Footer -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    @if(Auth::id() == $user->id)
                    <div>
                        <a href="{{ route('siswa.pendaftar.edit', $user->id) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-fill me-1"></i> Edit Data
                        </a>
                        {{-- <button class="btn btn-info" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> Cetak
                        </button> --}}
                    </div>
                    @endif
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
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .table td {
        padding: 0.25rem 0.5rem;
        border: none;
    }

    .table td:first-child {
        width: 40%;
        font-weight: 600;
    }

    @media print {

        .page-action,
        .btn,
        .card-footer {
            display: none !important;
        }

        .alert {
            print-color-adjust: exact;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
