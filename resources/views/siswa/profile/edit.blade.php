@extends('layouts.siswa.app')
@section('title', 'Edit Profil - ' . Auth::user()->nama_lengkap)

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header mb-4">
            <h3 class="page-title fw-bold text-warning">
                <i class="bi bi-pencil-square me-2"></i> Edit Profil Lengkap
            </h3>
            <div class="page-action">
                <a href="{{ route('siswa.profile.show') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <button type="button" class="btn btn-info" id="checkCompletionBtn">
                    <i class="bi bi-graph-up me-1"></i> Cek Kelengkapan
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Terjadi Kesalahan!</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(Auth::user()->catatan_verifikasi)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="bi bi-megaphone me-2"></i>Pesan dari Admin</h5>
            <div class="mb-0">{!! nl2br(e(Auth::user()->catatan_verifikasi)) !!}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Progress Bar -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">Kelengkapan Profil</h6>
                    <span id="progressText" class="fw-bold text-primary">0%</span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div id="progressBar" class="progress-bar bg-gradient" role="progressbar"
                        style="width: 0%; background: linear-gradient(90deg, #fd7e14, #20c997);" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">Lengkapi semua data untuk meningkatkan peluang diterima</small>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Tab Navigation -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-semibold" id="basic-tab" data-bs-toggle="tab"
                                    data-bs-target="#basic" type="button" role="tab">
                                    <i class="bi bi-person-badge me-1"></i>Data Utama
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="biodata-tab" data-bs-toggle="tab"
                                    data-bs-target="#biodata" type="button" role="tab">
                                    <i class="bi bi-person-lines-fill me-1"></i>Biodata
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="family-tab" data-bs-toggle="tab"
                                    data-bs-target="#family" type="button" role="tab">
                                    <i class="bi bi-house me-1"></i>Keluarga
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="security-tab" data-bs-toggle="tab"
                                    data-bs-target="#security" type="button" role="tab">
                                    <i class="bi bi-shield-lock me-1"></i>Keamanan
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Form Content -->
                <form id="profileForm" action="{{ route('siswa.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="tab-content" id="profileTabsContent">
                        <!-- Data Utama Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-person-badge me-2"></i>Data Utama Pendaftar
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="nama_lengkap" class="form-label fw-semibold">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control @error('nama_lengkap') is-invalid @enderror"
                                                id="nama_lengkap" name="nama_lengkap"
                                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                                            <div class="form-text">Sesuai dengan ijazah/akta kelahiran</div>
                                            @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nisn" class="form-label fw-semibold">
                                                NISN <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('nisn') is-invalid @enderror" id="nisn"
                                                    name="nisn" value="{{ old('nisn', $user->nisn) }}" required>
                                                <button type="button" class="btn btn-outline-info" id="checkNisnBtn">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">
                                                <a href="https://nisn.data.kemdikbud.go.id/" target="_blank">
                                                    <i class="bi bi-link-45deg"></i> Cek NISN disini
                                                </a>
                                            </div>
                                            @error('nisn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-semibold">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                name="email" value="{{ old('email', $user->email) }}" required>
                                            <div class="form-text">Email aktif untuk komunikasi penting</div>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="jalur_pendaftaran" class="form-label fw-semibold">
                                                Jalur Pendaftaran <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('jalur_pendaftaran') is-invalid @enderror"
                                                id="jalur_pendaftaran" name="jalur_pendaftaran" required>
                                                <option value="">Pilih Jalur Pendaftaran</option>
                                                <option value="domisili" {{ old('jalur_pendaftaran', $user->
                                                    jalur_pendaftaran) == 'domisili' ? 'selected' : '' }}>
                                                    Jalur Domisili
                                                </option>
                                                <option value="prestasi" {{ old('jalur_pendaftaran', $user->
                                                    jalur_pendaftaran) == 'prestasi' ? 'selected' : '' }}>
                                                    Jalur Prestasi
                                                </option>
                                                <option value="afirmasi" {{ old('jalur_pendaftaran', $user->
                                                    jalur_pendaftaran) == 'afirmasi' ? 'selected' : '' }}>
                                                    Jalur Afirmasi
                                                </option>
                                                <option value="mutasi" {{ old('jalur_pendaftaran', $user->
                                                    jalur_pendaftaran) == 'mutasi' ? 'selected' : '' }}>
                                                    Jalur Mutasi
                                                </option>
                                            </select>
                                            <div class="form-text">Pilih sesuai kriteria Anda</div>
                                            @error('jalur_pendaftaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-info small">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>No. Pendaftaran:</strong> {{ $user->no_pendaftaran }}
                                                <span class="text-muted">(otomatis, tidak dapat diubah)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Biodata Tab -->
                        <div class="tab-pane fade" id="biodata" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-person-lines-fill me-2"></i>Data Biodata Pribadi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="tempat_lahir" class="form-label fw-semibold">
                                                Tempat Lahir <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                id="tempat_lahir" name="tempat_lahir"
                                                value="{{ old('tempat_lahir', $user->biodata->tempat_lahir ?? '') }}"
                                                required>
                                            <div class="form-text">Sesuai akta kelahiran</div>
                                            @error('tempat_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tgl_lahir" class="form-label fw-semibold">
                                                Tanggal Lahir <span class="text-danger">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control @error('tgl_lahir') is-invalid @enderror"
                                                id="tgl_lahir" name="tgl_lahir"
                                                value="{{ old('tgl_lahir', $user->biodata?->tgl_lahir?->format('Y-m-d')) }}"
                                                required>
                                            <div class="form-text">Sesuai akta kelahiran</div>
                                            @error('tgl_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="jns_kelamin" class="form-label fw-semibold">
                                                Jenis Kelamin <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('jns_kelamin') is-invalid @enderror"
                                                id="jns_kelamin" name="jns_kelamin" required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" {{ old('jns_kelamin', $user->biodata->jns_kelamin ??
                                                    '') == 'L' ? 'selected' : '' }}>
                                                    Laki-laki
                                                </option>
                                                <option value="P" {{ old('jns_kelamin', $user->biodata->jns_kelamin ??
                                                    '') == 'P' ? 'selected' : '' }}>
                                                    Perempuan
                                                </option>
                                            </select>
                                            @error('jns_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="agama" class="form-label fw-semibold">
                                                Agama <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('agama') is-invalid @enderror" id="agama"
                                                name="agama" required>
                                                <option value="">Pilih Agama</option>
                                                <option value="Islam" {{ old('agama', $user->biodata->agama ?? '') ==
                                                    'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama', $user->biodata->agama ?? '') ==
                                                    'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katholik" {{ old('agama', $user->biodata->agama ?? '') ==
                                                    'Katholik' ? 'selected' : '' }}>Katholik</option>
                                                <option value="Hindu" {{ old('agama', $user->biodata->agama ?? '') ==
                                                    'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama', $user->biodata->agama ?? '') ==
                                                    'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Konghucu" {{ old('agama', $user->biodata->agama ?? '') ==
                                                    'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                            @error('agama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label for="asal_sekolah" class="form-label fw-semibold">
                                                Asal Sekolah <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control @error('asal_sekolah') is-invalid @enderror"
                                                id="asal_sekolah" name="asal_sekolah"
                                                value="{{ old('asal_sekolah', $user->biodata->asal_sekolah ?? '') }}"
                                                placeholder="Contoh: SDN 1 Jakarta Pusat" required>
                                            <div class="form-text">Nama lengkap sekolah dasar asal</div>
                                            @error('asal_sekolah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label for="alamat_rumah" class="form-label fw-semibold">
                                                Alamat Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('alamat_rumah') is-invalid @enderror"
                                                id="alamat_rumah" name="alamat_rumah" rows="3" required
                                                placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat_rumah', $user->biodata->alamat_rumah ?? '') }}</textarea>
                                            <div class="form-text">Alamat lengkap sesuai KK</div>
                                            @error('alamat_rumah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="anak_ke" class="form-label fw-semibold">
                                                Anak Ke <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                class="form-control @error('anak_ke') is-invalid @enderror" id="anak_ke"
                                                name="anak_ke"
                                                value="{{ old('anak_ke', $user->biodata->anak_ke ?? '') }}" min="1"
                                                max="20" required>
                                            <div class="form-text">Urutan kelahiran dalam keluarga</div>
                                            @error('anak_ke')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Tab -->
                        <div class="tab-pane fade" id="family" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0 text-white">
                                        <i class="bi bi-house me-2"></i>Data Keluarga
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Data Orang Tua -->
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="bi bi-person-fill me-2"></i>Data Ayah
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="nama_ayah" class="form-label fw-semibold">
                                                        Nama Ayah <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control @error('nama_ayah') is-invalid @enderror"
                                                        id="nama_ayah" name="nama_ayah"
                                                        value="{{ old('nama_ayah', $user->orangTua->nama_ayah ?? '') }}"
                                                        required>
                                                    @error('nama_ayah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="no_hp_ayah" class="form-label fw-semibold">
                                                        No. HP Ayah <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="tel"
                                                        class="form-control @error('no_hp_ayah') is-invalid @enderror"
                                                        id="no_hp_ayah" name="no_hp_ayah"
                                                        value="{{ old('no_hp_ayah', $user->orangTua->no_hp_ayah ?? '') }}"
                                                        placeholder="08xxxxxxxxxx" required>
                                                    <div class="form-text">Nomor yang bisa dihubungi</div>
                                                    @error('no_hp_ayah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="pekerjaan_ayah" class="form-label fw-semibold">
                                                        Pekerjaan Ayah <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control @error('pekerjaan_ayah') is-invalid @enderror"
                                                        id="pekerjaan_ayah" name="pekerjaan_ayah"
                                                        value="{{ old('pekerjaan_ayah', $user->orangTua->pekerjaan_ayah ?? '') }}"
                                                        placeholder="Contoh: Karyawan Swasta" required>
                                                    @error('pekerjaan_ayah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="pendidikan_ayah" class="form-label fw-semibold">
                                                        Pendidikan Ayah <span class="text-danger">*</span>
                                                    </label>
                                                    <select
                                                        class="form-select @error('pendidikan_ayah') is-invalid @enderror"
                                                        id="pendidikan_ayah" name="pendidikan_ayah" required>
                                                        <option value="">Pilih Pendidikan</option>
                                                        <option value="SD" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'SD' ? 'selected' : ''
                                                            }}>SD/Sederajat</option>
                                                        <option value="SMP" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : ''
                                                            }}>SMP/Sederajat</option>
                                                        <option value="SMA" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'SMA' ? 'selected' : ''
                                                            }}>SMA/SMK/Sederajat</option>
                                                        <option value="D3" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'D3' ? 'selected' : ''
                                                            }}>Diploma (D3)</option>
                                                        <option value="S1" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'S1' ? 'selected' : ''
                                                            }}>Sarjana (S1)</option>
                                                        <option value="S2" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'S2' ? 'selected' : ''
                                                            }}>Magister (S2)</option>
                                                        <option value="S3" {{ old('pendidikan_ayah', $user->
                                                            orangTua->pendidikan_ayah ?? '') == 'S3' ? 'selected' : ''
                                                            }}>Doktor (S3)</option>
                                                    </select>
                                                    @error('pendidikan_ayah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="bi bi-person-heart me-2"></i>Data Ibu
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="nama_ibu" class="form-label fw-semibold">
                                                        Nama Ibu <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control @error('nama_ibu') is-invalid @enderror"
                                                        id="nama_ibu" name="nama_ibu"
                                                        value="{{ old('nama_ibu', $user->orangTua->nama_ibu ?? '') }}"
                                                        required>
                                                    @error('nama_ibu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="no_hp_ibu" class="form-label fw-semibold">
                                                        No. HP Ibu <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="tel"
                                                        class="form-control @error('no_hp_ibu') is-invalid @enderror"
                                                        id="no_hp_ibu" name="no_hp_ibu"
                                                        value="{{ old('no_hp_ibu', $user->orangTua->no_hp_ibu ?? '') }}"
                                                        placeholder="08xxxxxxxxxx" required>
                                                    <div class="form-text">Nomor yang bisa dihubungi</div>
                                                    @error('no_hp_ibu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="pekerjaan_ibu" class="form-label fw-semibold">
                                                        Pekerjaan Ibu <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text"
                                                        class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                                        id="pekerjaan_ibu" name="pekerjaan_ibu"
                                                        value="{{ old('pekerjaan_ibu', $user->orangTua->pekerjaan_ibu ?? '') }}"
                                                        placeholder="Contoh: Ibu Rumah Tangga" required>
                                                    @error('pekerjaan_ibu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="pendidikan_ibu" class="form-label fw-semibold">
                                                        Pendidikan Ibu <span class="text-danger">*</span>
                                                    </label>
                                                    <select
                                                        class="form-select @error('pendidikan_ibu') is-invalid @enderror"
                                                        id="pendidikan_ibu" name="pendidikan_ibu" required>
                                                        <option value="">Pilih Pendidikan</option>
                                                        <option value="SD" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'SD' ? 'selected' : ''
                                                            }}>SD/Sederajat</option>
                                                        <option value="SMP" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : ''
                                                            }}>SMP/Sederajat</option>
                                                        <option value="SMA" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'SMA' ? 'selected' : ''
                                                            }}>SMA/SMK/Sederajat</option>
                                                        <option value="D3" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'D3' ? 'selected' : ''
                                                            }}>Diploma (D3)</option>
                                                        <option value="S1" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'S1' ? 'selected' : ''
                                                            }}>Sarjana (S1)</option>
                                                        <option value="S2" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'S2' ? 'selected' : ''
                                                            }}>Magister (S2)</option>
                                                        <option value="S3" {{ old('pendidikan_ibu', $user->
                                                            orangTua->pendidikan_ibu ?? '') == 'S3' ? 'selected' : ''
                                                            }}>Doktor (S3)</option>
                                                    </select>
                                                    @error('pendidikan_ibu')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <!-- Data Wali -->
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="bi bi-person-plus me-2"></i>Data Wali
                                        <small class="text-muted">(Opsional - jika ada wali resmi)</small>
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="nama_wali" class="form-label fw-semibold">Nama Wali</label>
                                            <input type="text"
                                                class="form-control @error('nama_wali') is-invalid @enderror"
                                                id="nama_wali" name="nama_wali"
                                                value="{{ old('nama_wali', $user->wali->nama_wali ?? '') }}">
                                            <div class="form-text">Kosongkan jika tidak ada wali</div>
                                            @error('nama_wali')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="hubungan_wali_dgn_calon_peserta" class="form-label fw-semibold">
                                                Hubungan dengan Siswa
                                            </label>
                                            <select
                                                class="form-select @error('hubungan_wali_dgn_calon_peserta') is-invalid @enderror"
                                                id="hubungan_wali_dgn_calon_peserta"
                                                name="hubungan_wali_dgn_calon_peserta">
                                                <option value="">Pilih Hubungan</option>
                                                <option value="Kakek" {{ old('hubungan_wali_dgn_calon_peserta', $user->
                                                    wali->hubungan_wali_dgn_calon_peserta ?? '') == 'Kakek' ? 'selected'
                                                    : '' }}>Kakek</option>
                                                <option value="Nenek" {{ old('hubungan_wali_dgn_calon_peserta', $user->
                                                    wali->hubungan_wali_dgn_calon_peserta ?? '') == 'Nenek' ? 'selected'
                                                    : '' }}>Nenek</option>
                                                <option value="Paman" {{ old('hubungan_wali_dgn_calon_peserta', $user->
                                                    wali->hubungan_wali_dgn_calon_peserta ?? '') == 'Paman' ? 'selected'
                                                    : '' }}>Paman</option>
                                                <option value="Bibi" {{ old('hubungan_wali_dgn_calon_peserta', $user->
                                                    wali->hubungan_wali_dgn_calon_peserta ?? '') == 'Bibi' ? 'selected'
                                                    : '' }}>Bibi</option>
                                                <option value="Kakak" {{ old('hubungan_wali_dgn_calon_peserta', $user->
                                                    wali->hubungan_wali_dgn_calon_peserta ?? '') == 'Kakak' ? 'selected'
                                                    : '' }}>Kakak</option>
                                                <option value="Lainnya" {{ old('hubungan_wali_dgn_calon_peserta',
                                                    $user->wali->hubungan_wali_dgn_calon_peserta ?? '') == 'Lainnya' ?
                                                    'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('hubungan_wali_dgn_calon_peserta')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="no_hp_wali" class="form-label fw-semibold">No. HP Wali</label>
                                            <input type="tel"
                                                class="form-control @error('no_hp_wali') is-invalid @enderror"
                                                id="no_hp_wali" name="no_hp_wali"
                                                value="{{ old('no_hp_wali', $user->wali->no_hp_wali ?? '') }}"
                                                placeholder="08xxxxxxxxxx">
                                            @error('no_hp_wali')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="alamat_wali" class="form-label fw-semibold">Alamat Wali</label>
                                            <textarea class="form-control @error('alamat_wali') is-invalid @enderror"
                                                id="alamat_wali" name="alamat_wali" rows="3"
                                                placeholder="Alamat lengkap wali">{{ old('alamat_wali', $user->wali->alamat_wali ?? '') }}</textarea>
                                            @error('alamat_wali')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-shield-lock me-2"></i>Keamanan Akun
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Perhatian:</strong> Perubahan password akan memerlukan login ulang.
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <h6 class="fw-bold text-secondary mb-3">Ubah Password</h6>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="current_password" class="form-label fw-semibold">
                                                Password Saat Ini <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                    id="current_password" name="current_password">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="toggleCurrentPassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Masukkan password yang sedang digunakan</div>
                                            @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-semibold">
                                                Password Baru <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" minlength="8">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="togglePassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Minimal 8 karakter</div>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label fw-semibold">
                                                Konfirmasi Password Baru <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" minlength="8">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="togglePasswordConfirmation">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Ulangi password baru</div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-info small">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <strong>Tips Keamanan:</strong>
                                                <ul class="mb-0 mt-2">
                                                    <li>Gunakan kombinasi huruf besar, kecil, angka, dan simbol</li>
                                                    <li>Jangan gunakan informasi pribadi yang mudah ditebak</li>
                                                    <li>Jangan bagikan password kepada siapapun</li>
                                                    <li>Ganti password secara berkala</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('siswa.profile.show') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Profil
                                    </a>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-warning me-2" id="resetFormBtn">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Semua
                                    </button>
                                    <button type="submit" class="btn btn-success" id="saveProfileBtn">
                                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Current Profile Status -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-person-check me-2"></i>Status Profil
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if($user->berkas && $user->berkas->file_pas_foto)
                        <img src="{{ Storage::url($user->berkas->file_pas_foto) }}" alt="Foto Profil"
                            class="rounded-circle mb-3 border-3 border-primary"
                            style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                        <div class="bg-light border border-3 border-dashed rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-person fs-1 text-muted"></i>
                        </div>
                        @endif

                        <h6 class="fw-bold">{{ $user->nama_lengkap }}</h6>
                        <p class="text-muted small mb-2">{{ $user->no_pendaftaran }}</p>
                        <span
                            class="badge bg-{{ $user->status_pendaftaran == 'lulus_seleksi' ? 'success' :
                            (in_array($user->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap']) ? 'danger' :
                            (in_array($user->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi']) ? 'info' : 'warning')) }} fs-6 px-3 py-2">
                            {{ ucwords(str_replace('_', ' ', $user->status_pendaftaran ?? 'N/A')) }}
                        </span>

                        <hr>

                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Bergabung</small>
                                    <strong>{{ $user->created_at->format('d M Y') }}</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">Update</small>
                                    <strong>{{ $user->updated_at->diffForHumans() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-lightning me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('siswa.pendaftar.show', $user->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-2"></i>Lihat Data Lengkap
                            </a>
                            <a href="{{ route('siswa.berkas.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-file-earmark-arrow-up me-2"></i>Kelola Berkas
                            </a>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Cetak Profil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Completion Checklist -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-list-check me-2"></i>Checklist Kelengkapan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="completionChecklist">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help & Tips -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-question-circle me-2"></i>Bantuan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="helpAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false">
                                        <i class="bi bi-info-circle me-2"></i> Cara Mengisi Data
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body small">
                                        <ul class="mb-0">
                                            <li>Isi semua data dengan lengkap dan benar</li>
                                            <li>Pastikan nama sesuai ijazah/akta</li>
                                            <li>Gunakan email aktif</li>
                                            <li>No. HP harus bisa dihubungi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseTwo" aria-expanded="false">
                                        <i class="bi bi-shield-check me-2"></i> Keamanan Data
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingTwo" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body small">
                                        <ul class="mb-0">
                                            <li>Data Anda tersimpan dengan aman</li>
                                            <li>Hanya admin yang bisa melihat data lengkap</li>
                                            <li>Jangan bagikan password</li>
                                            <li>Logout setelah selesai</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseThree" aria-expanded="false">
                                        <i class="bi bi-telephone me-2"></i> Kontak Bantuan
                                    </button>
                                </h2>
                                <div id="flush-collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingThree" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body small">
                                        <p class="mb-2"><strong>Jam Layanan:</strong><br>
                                            Senin - Jumat: 08:00 - 15:00</p>
                                        <p class="mb-0"><strong>Kontak:</strong><br>
                                            <i class="bi bi-telephone me-1"></i> (021) 123-4567<br>
                                            <i class="bi bi-envelope me-1"></i> spmb@sekolah.sch.id
                                        </p>
                                    </div>
                                </div>
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

    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white !important;
    }

    .form-label.fw-semibold {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }

    .checklist-item {
        padding: 0.5rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .checklist-item.completed {
        background-color: #d1e7dd;
        border-left: 4px solid #198754;
    }

    .checklist-item.incomplete {
        background-color: #f8d7da;
        border-left: 4px solid #dc3545;
    }

    @media (max-width: 768px) {
        .page-action {
            flex-direction: column;
        }

        .nav-tabs {
            flex-wrap: wrap;
        }

        .nav-tabs .nav-link {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
    }

    @media print {

        .page-action,
        .btn,
        .card-footer,
        .accordion,
        #helpAccordion {
            display: none !important;
        }

        .card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
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

    // Check completion on page load
    checkProfileCompletion();

    // Password toggle functionality
    function togglePasswordVisibility(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);
        const icon = toggleButton.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        togglePasswordVisibility('current_password', 'toggleCurrentPassword');
    });

    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', 'togglePassword');
    });

    document.getElementById('togglePasswordConfirmation').addEventListener('click', function() {
        togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation');
    });

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            }
            if (value.length > 13) {
                value = value.substring(0, 13);
            }
            e.target.value = value;
        });
    });

    // Form validation
    const form = document.getElementById('profileForm');
    const securityTab = document.getElementById('security-tab');

    // Check if security fields are filled
    function checkSecurityFields() {
        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (currentPassword || newPassword || confirmPassword) {
            // If any security field is filled, make them required
            document.getElementById('current_password').required = true;
            document.getElementById('password').required = true;
            document.getElementById('password_confirmation').required = true;
        } else {
            // If no security field is filled, remove required
            document.getElementById('current_password').required = false;
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
        }
    }

    // Listen for changes in security fields
    ['current_password', 'password', 'password_confirmation'].forEach(id => {
        document.getElementById(id).addEventListener('input', checkSecurityFields);
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        // Check password match
        const newPassword = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (newPassword && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak cocok!');
            return;
        }

        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan?')) {
            e.preventDefault();
        }
    });

    // Reset form
    document.getElementById('resetFormBtn').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
            form.reset();
            updateProgressBar(0);
        }
    });

    // Check completion button
    document.getElementById('checkCompletionBtn').addEventListener('click', function() {
        checkProfileCompletion();
    });

    // NISN check button (placeholder)
    document.getElementById('checkNisnBtn').addEventListener('click', function() {
        const nisn = document.getElementById('nisn').value;
        if (nisn) {
            alert('Fitur pengecekan NISN akan segera tersedia. Silakan verifikasi manual di https://nisn.data.kemdikbud.go.id/');
        } else {
            alert('Masukkan NISN terlebih dahulu');
        }
    });

    // Auto-save draft (optional - can be implemented later)
    let autoSaveTimer;
    const formInputs = form.querySelectorAll('input, select, textarea');

    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Auto-save functionality can be implemented here
                console.log('Auto-saving draft...');
            }, 30000); // Save after 30 seconds of inactivity
        });
    });

    // Update progress on form changes
    formInputs.forEach(input => {
        input.addEventListener('change', updateProgress);
    });

    // Calculate and update progress
    function updateProgress() {
        setTimeout(checkProfileCompletion, 100);
    }

    // Check profile completion
    function checkProfileCompletion() {
        fetch('{{ route("siswa.profile.completion") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            updateProgressBar(data.overall);
            updateCompletionChecklist(data.completion);
        })
        .catch(error => {
            console.error('Error checking completion:', error);
        });
    }

    // Update progress bar
    function updateProgressBar(percentage) {
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');

        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', percentage);
        progressText.textContent = percentage + '%';

        // Change color based on completion
        progressBar.className = 'progress-bar bg-gradient';
        if (percentage < 30) {
            progressBar.style.background = 'linear-gradient(90deg, #dc3545, #fd7e14)';
        } else if (percentage < 70) {
            progressBar.style.background = 'linear-gradient(90deg, #fd7e14, #ffc107)';
        } else {
            progressBar.style.background = 'linear-gradient(90deg, #ffc107, #20c997)';
        }
    }

    // Update completion checklist
    function updateCompletionChecklist(completion) {
        const checklistDiv = document.getElementById('completionChecklist');

        const checklist = [
            { key: 'biodata', label: 'Data Biodata', icon: 'person-lines-fill' },
            { key: 'orang_tua', label: 'Data Orang Tua', icon: 'house' },
            { key: 'wali', label: 'Data Wali', icon: 'person-plus' },
            { key: 'berkas', label: 'Upload Berkas', icon: 'file-earmark-text' }
        ];

        let html = '';
        checklist.forEach(item => {
            const percentage = completion[item.key] || 0;
            const isCompleted = percentage >= 100;
            const cssClass = isCompleted ? 'completed' : 'incomplete';
            const iconClass = isCompleted ? 'check-circle-fill text-success' : 'x-circle-fill text-danger';

            html += `
                <div class="checklist-item ${cssClass}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-${item.icon} me-2"></i>
                            <span class="fw-semibold">${item.label}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="me-2">${percentage}%</small>
                            <i class="bi bi-${iconClass}"></i>
                        </div>
                    </div>
                </div>
            `;
        });

        checklistDiv.innerHTML = html;
    }

    // Tab switching with auto-save
    const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            // Optional: Auto-save when switching tabs
            console.log('Switched to tab:', e.target.getAttribute('data-bs-target'));
        });
    });

    // Highlight required fields on blur
    const requiredInputs = document.querySelectorAll('input[required], select[required], textarea[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-warning');
            } else {
                this.classList.remove('border-warning');
            }
        });

        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-warning');
            }
        });
    });
});
</script>
@endpush
@endsection
