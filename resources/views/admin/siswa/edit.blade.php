@extends('layouts.admin.app')

@section('title', 'Edit Data Siswa')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        {{-- Page Header --}}
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-users me-2"></i>Edit Data Siswa: {{ $siswa->nama_lengkap }}
            </h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
    
            </ul>
        </div>
    <!-- Page Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        {{-- <h1 class="mb-0 text-gray-800 h3">Edit Data Siswa: {{ $siswa->nama_lengkap }}</h1> --}}
        <div>
            <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Lihat Detail
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan:</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST" enctype="multipart/form-data"
        id="editSiswaForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Account Data -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Data Akun</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}"
                                        required>
                                    @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nisn">NISN <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nisn') is-invalid @enderror"
                                        name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required maxlength="10">
                                    @error('nisn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $siswa->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        name="username" value="{{ old('username', $siswa->username) }}" required>
                                    @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jalur_pendaftaran">Jalur Pendaftaran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('jalur_pendaftaran') is-invalid @enderror"
                                        name="jalur_pendaftaran" required>
                                        <option value="">Pilih Jalur Pendaftaran</option>
                                        @foreach($jalurOptions as $key => $label)
                                        <option value="{{ $key }}" {{ old('jalur_pendaftaran', $siswa->
                                            jalur_pendaftaran) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('jalur_pendaftaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_pendaftaran">Status Pendaftaran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('status_pendaftaran') is-invalid @enderror"
                                        name="status_pendaftaran" required>
                                        <option value="">Pilih Status</option>
                                        @foreach($statusOptions as $key => $label)
                                        <option value="{{ $key }}" {{ old('status_pendaftaran', $siswa->
                                            status_pendaftaran) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status_pendaftaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan_verifikasi">Catatan Verifikasi</label>
                                    <textarea class="form-control @error('catatan_verifikasi') is-invalid @enderror"
                                        name="catatan_verifikasi" rows="3"
                                        placeholder="Catatan dari admin terkait status pendaftaran...">{{ old('catatan_verifikasi', $siswa->catatan_verifikasi) }}</textarea>
                                    @error('catatan_verifikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Password Update Section -->
                        <h6 class="mb-3 font-weight-bold text-secondary">Update Password (Opsional)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" minlength="8">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah
                                        password</small>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" minlength="8">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Data -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pribadi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        name="tempat_lahir"
                                        value="{{ old('tempat_lahir', $siswa->biodata->tempat_lahir ?? '') }}" required>
                                    @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                        name="tgl_lahir"
                                        value="{{ old('tgl_lahir', $siswa->biodata->tgl_lahir ?? '') }}" required
                                        max="{{ now()->subYears(10)->format('Y-m-d') }}">
                                    @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jns_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control @error('jns_kelamin') is-invalid @enderror"
                                        name="jns_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jns_kelamin', $siswa->biodata->jns_kelamin ?? '') ==
                                            'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jns_kelamin', $siswa->biodata->jns_kelamin ?? '') ==
                                            'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jns_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agama">Agama <span class="text-danger">*</span></label>
                                    <select class="form-control @error('agama') is-invalid @enderror" name="agama"
                                        required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ old('agama', $siswa->biodata->agama ?? '') == 'Islam' ?
                                            'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama', $siswa->biodata->agama ?? '') ==
                                            'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('agama', $siswa->biodata->agama ?? '') ==
                                            'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('agama', $siswa->biodata->agama ?? '') == 'Hindu' ?
                                            'selected' : '' }}>Hindu</option>
                                        <option value="Budha" {{ old('agama', $siswa->biodata->agama ?? '') == 'Budha' ?
                                            'selected' : '' }}>Budha</option>
                                        <option value="Konghucu" {{ old('agama', $siswa->biodata->agama ?? '') ==
                                            'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                    @error('agama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="anak_ke">Anak Ke- <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('anak_ke') is-invalid @enderror"
                                        name="anak_ke" value="{{ old('anak_ke', $siswa->biodata->anak_ke ?? '') }}"
                                        required min="1">
                                    @error('anak_ke')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jml_saudara_kandung">Jumlah Saudara Kandung <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('jml_saudara_kandung') is-invalid @enderror"
                                        name="jml_saudara_kandung"
                                        value="{{ old('jml_saudara_kandung', $siswa->biodata->jml_saudara_kandung ?? '') }}"
                                        required min="0">
                                    @error('jml_saudara_kandung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Physical Data -->
                        <h6 class="mb-3 font-weight-bold text-secondary">Data Fisik (Opsional)</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tinggi_badan">Tinggi Badan (cm)</label>
                                    <input type="number"
                                        class="form-control @error('tinggi_badan') is-invalid @enderror"
                                        name="tinggi_badan"
                                        value="{{ old('tinggi_badan', $siswa->biodata->tinggi_badan ?? '') }}"
                                        step="0.1" min="0">
                                    @error('tinggi_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="berat_badan">Berat Badan (kg)</label>
                                    <input type="number" class="form-control @error('berat_badan') is-invalid @enderror"
                                        name="berat_badan"
                                        value="{{ old('berat_badan', $siswa->biodata->berat_badan ?? '') }}" step="0.1"
                                        min="0">
                                    @error('berat_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lingkar_kepala">Lingkar Kepala (cm)</label>
                                    <input type="number"
                                        class="form-control @error('lingkar_kepala') is-invalid @enderror"
                                        name="lingkar_kepala"
                                        value="{{ old('lingkar_kepala', $siswa->biodata->lingkar_kepala ?? '') }}"
                                        step="0.1" min="0">
                                    @error('lingkar_kepala')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School & Address Data -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Data Sekolah & Alamat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asal_sekolah">Asal Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                        name="asal_sekolah"
                                        value="{{ old('asal_sekolah', $siswa->biodata->asal_sekolah ?? '') }}" required>
                                    @error('asal_sekolah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alamat_asal_sekolah">Alamat Asal Sekolah <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_asal_sekolah') is-invalid @enderror"
                                        name="alamat_asal_sekolah" rows="2"
                                        required>{{ old('alamat_asal_sekolah', $siswa->biodata->alamat_asal_sekolah ?? '') }}</textarea>
                                    @error('alamat_asal_sekolah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat_rumah">Alamat Rumah <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_rumah') is-invalid @enderror"
                                        name="alamat_rumah" rows="2"
                                        required>{{ old('alamat_rumah', $siswa->biodata->alamat_rumah ?? '') }}</textarea>
                                    @error('alamat_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kelurahan_desa">Kelurahan/Desa <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('kelurahan_desa') is-invalid @enderror"
                                        name="kelurahan_desa"
                                        value="{{ old('kelurahan_desa', $siswa->biodata->desa_kelurahan_domisili ?? '') }}"
                                        required>
                                    @error('kelurahan_desa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rt">RT <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('rt') is-invalid @enderror" name="rt"
                                        value="{{ old('rt', $siswa->biodata->rt ?? '') }}" required maxlength="3">
                                    @error('rt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rw">RW <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('rw') is-invalid @enderror" name="rw"
                                        value="{{ old('rw', $siswa->biodata->rw ?? '') }}" required maxlength="3">
                                    @error('rw')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kode_pos">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_pos') is-invalid @enderror"
                                        name="kode_pos" value="{{ old('kode_pos', $siswa->biodata->kode_pos ?? '') }}"
                                        required maxlength="5">
                                    @error('kode_pos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Data -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Data Orang Tua</h6>
                    </div>
                    <div class="card-body">
                        <!-- Mother Data (Required) -->
                        <h6 class="mb-3 font-weight-bold text-secondary">Data Ibu (Wajib)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ibu">Nama Ibu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                        name="nama_ibu" value="{{ old('nama_ibu', $siswa->orangTua->nama_ibu ?? '') }}"
                                        required>
                                    @error('nama_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nik_ibu">NIK Ibu</label>
                                    <input type="text" class="form-control @error('nik_ibu') is-invalid @enderror"
                                        name="nik_ibu" value="{{ old('nik_ibu', $siswa->orangTua->nik_ibu ?? '') }}"
                                        maxlength="16">
                                    @error('nik_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tempat_lahir_ibu">Tempat Lahir Ibu</label>
                                    <input type="text"
                                        class="form-control @error('tempat_lahir_ibu') is-invalid @enderror"
                                        name="tempat_lahir_ibu"
                                        value="{{ old('tempat_lahir_ibu', $siswa->orangTua->tempat_lahir_ibu ?? '') }}">
                                    @error('tempat_lahir_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_lahir_ibu">Tanggal Lahir Ibu</label>
                                    <input type="date" class="form-control @error('tgl_lahir_ibu') is-invalid @enderror"
                                        name="tgl_lahir_ibu"
                                        value="{{ old('tgl_lahir_ibu', $siswa->orangTua->tgl_lahir_ibu ?? '') }}">
                                    @error('tgl_lahir_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendidikan_ibu">Pendidikan Ibu</label>
                                    <select class="form-control @error('pendidikan_ibu') is-invalid @enderror"
                                        name="pendidikan_ibu">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="D3" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_ibu', $siswa->orangTua->pendidikan_ibu ??
                                            '') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                    @error('pendidikan_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                                    <input type="text" class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                        name="pekerjaan_ibu"
                                        value="{{ old('pekerjaan_ibu', $siswa->orangTua->pekerjaan_ibu ?? '') }}">
                                    @error('pekerjaan_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="penghasilan_ibu">Penghasilan Ibu</label>
                                    <select class="form-control @error('penghasilan_ibu') is-invalid @enderror"
                                        name="penghasilan_ibu">
                                        <option value="">Pilih Penghasilan</option>
                                        <option value="<2juta" {{ old('penghasilan_ibu', $siswa->
                                            orangTua->penghasilan_ibu ?? '') == '<2juta' ? 'selected' : '' }}>Kurang
                                                dari Rp 2.000.000</option>
                                        <option value="2-5juta" {{ old('penghasilan_ibu', $siswa->
                                            orangTua->penghasilan_ibu ?? '') == '2-5juta' ? 'selected' : '' }}>Rp
                                            2.000.000 - Rp 5.000.000</option>
                                        <option value="5-10juta" {{ old('penghasilan_ibu', $siswa->
                                            orangTua->penghasilan_ibu ?? '') == '5-10juta' ? 'selected' : '' }}>Rp
                                            5.000.000 - Rp 10.000.000</option>
                                        <option value=">10juta" {{ old('penghasilan_ibu', $siswa->
                                            orangTua->penghasilan_ibu ?? '') == '>10juta' ? 'selected' : '' }}>Lebih
                                            dari Rp 10.000.000</option>
                                    </select>
                                    @error('penghasilan_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_hp_ibu">No. HP Ibu</label>
                                    <input type="text" class="form-control @error('no_hp_ibu') is-invalid @enderror"
                                        name="no_hp_ibu"
                                        value="{{ old('no_hp_ibu', $siswa->orangTua->no_hp_ibu ?? '') }}"
                                        maxlength="15">
                                    @error('no_hp_ibu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Father Data (Optional) -->
                        <h6 class="mb-3 font-weight-bold text-secondary">Data Ayah (Opsional)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_ayah">Nama Ayah</label>
                                    <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                        name="nama_ayah"
                                        value="{{ old('nama_ayah', $siswa->orangTua->nama_ayah ?? '') }}">
                                    @error('nama_ayah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nik_ayah">NIK Ayah</label>
                                    <input type="text" class="form-control @error('nik_ayah') is-invalid @enderror"
                                        name="nik_ayah" value="{{ old('nik_ayah', $siswa->orangTua->nik_ayah ?? '') }}"
                                        maxlength="16">
                                    @error('nik_ayah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Add similar fields for father like mother -->
                        </div>

                        <hr>

                        <!-- Wali Data (Optional) -->
                        <h6 class="mb-3 font-weight-bold text-secondary">Data Wali (Opsional)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_wali">Nama Wali</label>
                                    <input type="text" class="form-control @error('nama_wali') is-invalid @enderror"
                                        name="nama_wali" value="{{ old('nama_wali', $siswa->wali->nama_wali ?? '') }}">
                                    @error('nama_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nik_wali">NIK Wali</label>
                                    <input type="text" class="form-control @error('nik_wali') is-invalid @enderror"
                                        name="nik_wali" value="{{ old('nik_wali', $siswa->wali->nik_wali ?? '') }}"
                                        maxlength="16">
                                    @error('nik_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hubungan_wali_dgn_siswa">Hubungan dengan Siswa</label>
                                    <input type="text"
                                        class="form-control @error('hubungan_wali_dgn_siswa') is-invalid @enderror"
                                        name="hubungan_wali_dgn_siswa"
                                        value="{{ old('hubungan_wali_dgn_siswa', $siswa->wali->hubungan_wali_dgn_siswa ?? '') }}"
                                        placeholder="Contoh: Paman, Bibi, Kakek, dll">
                                    @error('hubungan_wali_dgn_siswa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="alamat_wali">Alamat Wali</label>
                                    <textarea class="form-control @error('alamat_wali') is-invalid @enderror"
                                        name="alamat_wali"
                                        rows="2">{{ old('alamat_wali', $siswa->wali->alamat_wali ?? '') }}</textarea>
                                    @error('alamat_wali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Current Photo -->
                @if($siswa->biodata && $siswa->biodata->foto_siswa)
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Foto Saat Ini</h6>
                    </div>
                    <div class="text-center card-body">
                        <img src="{{ asset('storage/' . $siswa->biodata->foto_siswa) }}" class="img-thumbnail"
                            style="max-width: 200px;">
                    </div>
                </div>
                @endif

                <!-- Upload New Photo -->
                <div class="mb-4 shadow card">
                    <div class="py-3 card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ $siswa->biodata && $siswa->biodata->foto_siswa ? 'Update Foto' : 'Upload Foto' }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="foto_siswa">Upload Foto (3x4, Background Merah)</label>
                            <input type="file" class="form-control-file @error('foto_siswa') is-invalid @enderror"
                                name="foto_siswa" accept="image/jpeg,image/png,image/jpg">
                            <small class="form-text text-muted">
                                Format: JPG/PNG, Max: 500KB
                                @if($siswa->biodata && $siswa->biodata->foto_siswa)
                                <br>Kosongkan jika tidak ingin mengubah foto.
                                @endif
                            </small>
                            @error('foto_siswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="foto-preview" style="display: none;">
                            <label>Preview:</label>
                            <img id="foto-preview-img" class="img-thumbnail d-block" style="max-width: 200px;">
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="shadow card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Data Siswa
                        </button>
                        <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn btn-info btn-block">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Batal
                        </a>

                        <hr>

                        <div class="alert alert-warning">
                            <small>
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Perhatian:</strong> Perubahan status dan catatan verifikasi akan mempengaruhi
                                akses siswa terhadap sistem.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
    // Photo preview
    $('input[name="foto_siswa"]').change(function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#foto-preview-img').attr('src', e.target.result);
                $('#foto-preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#foto-preview').hide();
        }
    });

    // Validate password confirmation
    $('input[name="password_confirmation"]').on('input', function() {
        var password = $('input[name="password"]').val();
        var confirmPassword = $(this).val();
        
        if (password && confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Password tidak sama</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Clear password confirmation when password is cleared
    $('input[name="password"]').on('input', function() {
        if (!$(this).val()) {
            $('input[name="password_confirmation"]').val('').removeClass('is-invalid');
            $('input[name="password_confirmation"]').next('.invalid-feedback').remove();
        }
    });

    // Status change warning
    $('select[name="status_pendaftaran"]').on('change', function() {
        var status = $(this).val();
        var restrictedStatuses = ['berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai'];
        
        if (restrictedStatuses.includes(status)) {
            if (!confirm('Status ini akan membatasi akses edit siswa. Yakin ingin mengubah?')) {
                $(this).val('{{ $siswa->status_pendaftaran }}');
            }
        }
    });

    // Remove invalid class on input change
    $('.form-control').on('input change', function() {
        if ($(this).attr('name') !== 'password_confirmation') {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush
@endsection