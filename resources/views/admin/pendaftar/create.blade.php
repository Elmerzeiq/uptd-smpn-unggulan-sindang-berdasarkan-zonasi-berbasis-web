@extends('layouts.admin.app')
@section('title', 'Tambah Pendaftar Baru')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header mb-4">
            <h3 class="page-title fw-bold text-success">
                <i class="bi bi-person-plus me-2"></i> Tambah Pendaftar Baru
            </h3>
            <div class="page-action">
                <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Terjadi Kesalahan!</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('admin.pendaftar.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <!-- Main Form -->
                <div class="col-md-8">
                    <!-- Data Akun -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-key me-2"></i>Data Akun
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama_lengkap" class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                        required>
                                    @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label fw-semibold">
                                        NISN <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                        id="nisn" name="nisn" value="{{ old('nisn') }}" required>
                                    @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="jalur_pendaftaran" class="form-label fw-semibold">
                                        Jalur Pendaftaran <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('jalur_pendaftaran') is-invalid @enderror"
                                        id="jalur_pendaftaran" name="jalur_pendaftaran" required>
                                        <option value="">Pilih Jalur Pendaftaran</option>
                                        @foreach($jalurOptions as $jalur)
                                        <option value="{{ $jalur }}" {{ old('jalur_pendaftaran')==$jalur ? 'selected'
                                            : '' }}>
                                            {{ ucfirst($jalur) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('jalur_pendaftaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="status_pendaftaran" class="form-label fw-semibold">
                                        Status Awal
                                    </label>
                                    <select class="form-select @error('status_pendaftaran') is-invalid @enderror"
                                        id="status_pendaftaran" name="status_pendaftaran" required>
                                        @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('status_pendaftaran', 'akun_terdaftar'
                                            )==$value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status_pendaftaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Biodata -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-lines-fill me-2"></i>Data Biodata <small
                                    class="text-muted">(Opsional)</small>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label fw-semibold">Tempat Lahir</label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                                    @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_lahir" class="form-label fw-semibold">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                        id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                                    @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="jns_kelamin" class="form-label fw-semibold">Jenis Kelamin</label>
                                    <select class="form-select @error('jns_kelamin') is-invalid @enderror"
                                        id="jns_kelamin" name="jns_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jns_kelamin')=='L' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="P" {{ old('jns_kelamin')=='P' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                    @error('jns_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="agama" class="form-label fw-semibold">Agama</label>
                                    <select class="form-select @error('agama') is-invalid @enderror" id="agama"
                                        name="agama">
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ old('agama')=='Islam' ? 'selected' : '' }}>Islam
                                        </option>
                                        <option value="Kristen" {{ old('agama')=='Kristen' ? 'selected' : '' }}>Kristen
                                        </option>
                                        <option value="Katholik" {{ old('agama')=='Katholik' ? 'selected' : '' }}>
                                            Katholik</option>
                                        <option value="Hindu" {{ old('agama')=='Hindu' ? 'selected' : '' }}>Hindu
                                        </option>
                                        <option value="Buddha" {{ old('agama')=='Buddha' ? 'selected' : '' }}>Buddha
                                        </option>
                                        <option value="Konghucu" {{ old('agama')=='Konghucu' ? 'selected' : '' }}>
                                            Konghucu</option>
                                    </select>
                                    @error('agama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="asal_sekolah" class="form-label fw-semibold">Asal Sekolah</label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                        id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                        placeholder="Contoh: SDN 1 Jakarta">
                                    @error('asal_sekolah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="alamat_rumah" class="form-label fw-semibold">Alamat Rumah</label>
                                    <textarea class="form-control @error('alamat_rumah') is-invalid @enderror"
                                        id="alamat_rumah" name="alamat_rumah" rows="3"
                                        placeholder="Masukkan alamat lengkap">{{ old('alamat_rumah') }}</textarea>
                                    @error('alamat_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="anak_ke" class="form-label fw-semibold">Anak Ke</label>
                                    <input type="number" class="form-control @error('anak_ke') is-invalid @enderror"
                                        id="anak_ke" name="anak_ke" value="{{ old('anak_ke') }}" min="1" max="20">
                                    @error('anak_ke')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-house me-2"></i>Data Orang Tua <small
                                    class="text-muted">(Opsional)</small>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary">Data Ayah</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="nama_ayah" class="form-label fw-semibold">Nama Ayah</label>
                                            <input type="text"
                                                class="form-control @error('nama_ayah') is-invalid @enderror"
                                                id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}">
                                            @error('nama_ayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="no_hp_ayah" class="form-label fw-semibold">No. HP Ayah</label>
                                            <input type="tel"
                                                class="form-control @error('no_hp_ayah') is-invalid @enderror"
                                                id="no_hp_ayah" name="no_hp_ayah" value="{{ old('no_hp_ayah') }}"
                                                placeholder="08xxxxxxxxxx">
                                            @error('no_hp_ayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="pekerjaan_ayah" class="form-label fw-semibold">Pekerjaan
                                                Ayah</label>
                                            <input type="text"
                                                class="form-control @error('pekerjaan_ayah') is-invalid @enderror"
                                                id="pekerjaan_ayah" name="pekerjaan_ayah"
                                                value="{{ old('pekerjaan_ayah') }}">
                                            @error('pekerjaan_ayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="pendidikan_ayah" class="form-label fw-semibold">Pendidikan
                                                Ayah</label>
                                            <select class="form-select @error('pendidikan_ayah') is-invalid @enderror"
                                                id="pendidikan_ayah" name="pendidikan_ayah">
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="SD" {{ old('pendidikan_ayah')=='SD' ? 'selected' : '' }}>
                                                    SD</option>
                                                <option value="SMP" {{ old('pendidikan_ayah')=='SMP' ? 'selected' : ''
                                                    }}>SMP</option>
                                                <option value="SMA" {{ old('pendidikan_ayah')=='SMA' ? 'selected' : ''
                                                    }}>SMA/SMK</option>
                                                <option value="D3" {{ old('pendidikan_ayah')=='D3' ? 'selected' : '' }}>
                                                    D3</option>
                                                <option value="S1" {{ old('pendidikan_ayah')=='S1' ? 'selected' : '' }}>
                                                    S1</option>
                                                <option value="S2" {{ old('pendidikan_ayah')=='S2' ? 'selected' : '' }}>
                                                    S2</option>
                                                <option value="S3" {{ old('pendidikan_ayah')=='S3' ? 'selected' : '' }}>
                                                    S3</option>
                                            </select>
                                            @error('pendidikan_ayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary">Data Ibu</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="nama_ibu" class="form-label fw-semibold">Nama Ibu</label>
                                            <input type="text"
                                                class="form-control @error('nama_ibu') is-invalid @enderror"
                                                id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}">
                                            @error('nama_ibu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="no_hp_ibu" class="form-label fw-semibold">No. HP Ibu</label>
                                            <input type="tel"
                                                class="form-control @error('no_hp_ibu') is-invalid @enderror"
                                                id="no_hp_ibu" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}"
                                                placeholder="08xxxxxxxxxx">
                                            @error('no_hp_ibu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="pekerjaan_ibu" class="form-label fw-semibold">Pekerjaan
                                                Ibu</label>
                                            <input type="text"
                                                class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                                id="pekerjaan_ibu" name="pekerjaan_ibu"
                                                value="{{ old('pekerjaan_ibu') }}">
                                            @error('pekerjaan_ibu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="pendidikan_ibu" class="form-label fw-semibold">Pendidikan
                                                Ibu</label>
                                            <select class="form-select @error('pendidikan_ibu') is-invalid @enderror"
                                                id="pendidikan_ibu" name="pendidikan_ibu">
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="SD" {{ old('pendidikan_ibu')=='SD' ? 'selected' : '' }}>
                                                    SD</option>
                                                <option value="SMP" {{ old('pendidikan_ibu')=='SMP' ? 'selected' : ''
                                                    }}>SMP</option>
                                                <option value="SMA" {{ old('pendidikan_ibu')=='SMA' ? 'selected' : ''
                                                    }}>SMA/SMK</option>
                                                <option value="D3" {{ old('pendidikan_ibu')=='D3' ? 'selected' : '' }}>
                                                    D3</option>
                                                <option value="S1" {{ old('pendidikan_ibu')=='S1' ? 'selected' : '' }}>
                                                    S1</option>
                                                <option value="S2" {{ old('pendidikan_ibu')=='S2' ? 'selected' : '' }}>
                                                    S2</option>
                                                <option value="S3" {{ old('pendidikan_ibu')=='S3' ? 'selected' : '' }}>
                                                    S3</option>
                                            </select>
                                            @error('pendidikan_ibu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Wali -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-plus me-2"></i>Data Wali <small
                                    class="text-muted">(Opsional)</small>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama_wali" class="form-label fw-semibold">Nama Wali</label>
                                    <input type="text" class="form-control @error('nama_wali') is-invalid @enderror"
                                        id="nama_wali" name="nama_wali" value="{{ old('nama_wali') }}">
                                    @error('nama_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="hubungan_wali_dgn_calon_peserta" class="form-label fw-semibold">Hubungan
                                        dengan Siswa</label>
                                    <select
                                        class="form-select @error('hubungan_wali_dgn_calon_peserta') is-invalid @enderror"
                                        id="hubungan_wali_dgn_calon_peserta" name="hubungan_wali_dgn_calon_peserta">
                                        <option value="">Pilih Hubungan</option>
                                        <option value="Kakek" {{ old('hubungan_wali_dgn_calon_peserta')=='Kakek'
                                            ? 'selected' : '' }}>Kakek</option>
                                        <option value="Nenek" {{ old('hubungan_wali_dgn_calon_peserta')=='Nenek'
                                            ? 'selected' : '' }}>Nenek</option>
                                        <option value="Paman" {{ old('hubungan_wali_dgn_calon_peserta')=='Paman'
                                            ? 'selected' : '' }}>Paman</option>
                                        <option value="Bibi" {{ old('hubungan_wali_dgn_calon_peserta')=='Bibi'
                                            ? 'selected' : '' }}>Bibi</option>
                                        <option value="Kakak" {{ old('hubungan_wali_dgn_calon_peserta')=='Kakak'
                                            ? 'selected' : '' }}>Kakak</option>
                                        <option value="Lainnya" {{ old('hubungan_wali_dgn_calon_peserta')=='Lainnya'
                                            ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('hubungan_wali_dgn_calon_peserta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp_wali" class="form-label fw-semibold">No. HP Wali</label>
                                    <input type="tel" class="form-control @error('no_hp_wali') is-invalid @enderror"
                                        id="no_hp_wali" name="no_hp_wali" value="{{ old('no_hp_wali') }}"
                                        placeholder="08xxxxxxxxxx">
                                    @error('no_hp_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="alamat_wali" class="form-label fw-semibold">Alamat Wali</label>
                                    <textarea class="form-control @error('alamat_wali') is-invalid @enderror"
                                        id="alamat_wali" name="alamat_wali" rows="3">{{ old('alamat_wali') }}</textarea>
                                    @error('alamat_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Info Panel -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-info-circle me-2"></i>Panduan Pengisian
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info small">
                                <i class="bi bi-lightbulb me-1"></i>
                                <strong>Tips untuk Admin:</strong><br>
                                • Hanya data akun yang wajib diisi<br>
                                • Data lain dapat dilengkapi kemudian<br>
                                • Password default bisa diubah siswa<br>
                                • Nomor pendaftaran akan otomatis dibuat
                            </div>

                            <h6 class="fw-bold">Field Wajib:</h6>
                            <ul class="small">
                                <li>Nama Lengkap</li>
                                <li>NISN (harus unik)</li>
                                <li>Email (harus unik)</li>
                                <li>Password & Konfirmasi</li>
                                <li>Jalur Pendaftaran</li>
                            </ul>

                            <h6 class="fw-bold mt-3">Field Opsional:</h6>
                            <ul class="small">
                                <li>Semua data biodata</li>
                                <li>Data orang tua</li>
                                <li>Data wali</li>
                                <li>Catatan admin</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Password Generator -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-key me-2"></i>Generator Password
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="generatePassword()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Generate Password Kuat
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="useSimplePassword()">
                                    <i class="bi bi-123 me-1"></i>Gunakan Password Sederhana
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                Password dapat diubah oleh siswa setelah login pertama.
                            </small>
                        </div>
                    </div>

                    <!-- Admin Note -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-chat-text me-2"></i>Catatan Admin
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="catatan_verifikasi" class="form-label fw-semibold">Catatan</label>
                                <textarea class="form-control @error('catatan_verifikasi') is-invalid @enderror"
                                    id="catatan_verifikasi" name="catatan_verifikasi" rows="4"
                                    placeholder="Tambahkan catatan untuk siswa jika diperlukan">{{ old('catatan_verifikasi') }}</textarea>
                                @error('catatan_verifikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                                    </a>
                                </div>
                                <div>
                                    <button type="reset" class="btn btn-outline-warning me-2"
                                        onclick="return confirm('Apakah Anda yakin ingin mereset form?')">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-person-plus me-2"></i>Tambah Pendaftar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .page-action {
        display: flex;
        gap: 10px;
    }

    .form-label.fw-semibold {
        font-weight: 600;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .input-group .btn {
        border-color: #ced4da;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto format phone numbers
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

        // Highlight required fields
        const requiredInputs = document.querySelectorAll('input[required], select[required]');
        requiredInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.classList.add('border-warning');
                } else {
                    this.classList.remove('border-warning');
                }
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }

            if (!confirm('Apakah Anda yakin data yang dimasukkan sudah benar?')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Generate strong password
    function generatePassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }

        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;

        // Show generated password briefly
        document.getElementById('password').type = 'text';
        document.getElementById('password_confirmation').type = 'text';

        setTimeout(() => {
            document.getElementById('password').type = 'password';
            document.getElementById('password_confirmation').type = 'password';
        }, 3000);
    }

    // Use simple password
    function useSimplePassword() {
        const nisn = document.getElementById('nisn').value;
        const simple = nisn ? `siswa${nisn.slice(-4)}` : 'siswa2024';

        document.getElementById('password').value = simple;
        document.getElementById('password_confirmation').value = simple;

        alert(`Password sederhana telah diset: ${simple}`);
    }
</script>
@endpush
@endsection
