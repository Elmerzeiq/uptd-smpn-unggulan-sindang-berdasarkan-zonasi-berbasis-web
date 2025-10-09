@extends('layouts.siswa.app')
@section('title', 'Lengkapi Biodata & Data Keluarga')
@section('title_header_siswa', 'Form Biodata') {{-- Untuk judul di header --}}

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-id-card"></i> Lengkapi Biodata & Data Keluarga</h4>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if(isset($allowEdit) && !$allowEdit)
                <div class="shadow alert alert-info" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi</h4>
                    @if (in_array($user->status_pendaftaran, ['berkas_diverifikasi', 'lulus_seleksi',
                    'tidak_lulus_seleksi', 'daftar_ulang_selesai']))
                    Data pendaftaran Anda sudah selesai diverifikasi atau statusnya sudah final. Anda tidak dapat
                    mengubah data lagi.
                    @else
                    Periode untuk mengisi atau mengubah biodata dan data keluarga saat ini tidak aktif. Anda hanya dapat
                    melihat data yang sudah tersimpan.
                    @endif
                </div>
                @elseif($user->status_pendaftaran === 'berkas_tidak_lengkap')
                <div class="shadow alert alert-warning" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h4>
                    Data Anda perlu diperbaiki sesuai catatan verifikasi. Silakan lengkapi atau perbaiki data yang
                    diperlukan.
                    @if($user->catatan_verifikasi)
                    <hr>
                    <strong>Catatan Verifikasi:</strong><br>
                    {!! nl2br(e($user->catatan_verifikasi)) !!}
                    @endif
                </div>
                @endif

                <div class="shadow-lg card">
                    <div class="card-header">
                        <div class="card-title">Lengkapi Data Diri dan Keluarga</div>
                        <div class="card-category">Pastikan semua data diisi dengan benar dan sesuai dengan dokumen
                            resmi (Akta, KK, KTP).</div>
                    </div>
                    {{-- Form akan di-submit ke route storeOrUpdate --}}
                    <form action="{{ route('siswa.biodata.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            {{-- Notifikasi error validasi sudah dihandle di app.blade.php --}}

                            {{-- Bagian Data Calon Peserta Didik --}}
                            <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-user me-2"></i>Data Calon Peserta
                                Didik</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label fw-semibold">Nama Lengkap (Sesuai Akta)</label>
                                            <input type="text" class="form-control" value="{{ $user->nama_lengkap }}"
                                                readonly>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label fw-semibold">NISN</label>
                                            <input type="text" class="form-control" value="{{ $user->nisn }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label for="tempat_lahir" class="form-label fw-semibold">Tempat Lahir <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                id="tempat_lahir" name="tempat_lahir"
                                                value="{{ old('tempat_lahir', $biodata->tempat_lahir) }}" required {{
                                                isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                            @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="tgl_lahir" class="form-label fw-semibold">Tanggal Lahir <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('tgl_lahir') is-invalid @enderror"
                                                id="tgl_lahir" name="tgl_lahir"
                                                value="{{ old('tgl_lahir', $biodata->tgl_lahir ? $biodata->tgl_lahir->format('Y-m-d') : '') }}"
                                                required max="{{ now()->subYears(10)->format('Y-m-d') }}" {{
                                                isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                            @error('tgl_lahir') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="jns_kelamin" class="form-label fw-semibold">Jenis Kelamin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('jns_kelamin') is-invalid @enderror"
                                                id="jns_kelamin" name="jns_kelamin" required {{ isset($allowEdit) &&
                                                !$allowEdit ? 'disabled' : '' }}>
                                                <option value="">-- Pilih --</option>
                                                <option value="L" {{ old('jns_kelamin', $biodata->jns_kelamin) == 'L' ?
                                                    'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ old('jns_kelamin', $biodata->jns_kelamin) == 'P' ?
                                                    'selected' : '' }}>Perempuan</option>
                                            </select>
                                            @error('jns_kelamin') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label for="agama" class="form-label fw-semibold">Agama <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select @error('agama') is-invalid @enderror" id="agama"
                                                name="agama" required {{ isset($allowEdit) && !$allowEdit ? 'disabled'
                                                : '' }}>
                                                <option value="" {{ old('agama', $biodata->agama) == '' ? 'selected' :
                                                    '' }}>Pilih Agama</option>
                                                <option value="Islam" {{ old('agama', $biodata->agama) == 'Islam' ?
                                                    'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama', $biodata->agama) == 'Kristen' ?
                                                    'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama', $biodata->agama) == 'Katolik' ?
                                                    'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama', $biodata->agama) == 'Hindu' ?
                                                    'selected' : '' }}>Hindu</option>
                                                <option value="Budha" {{ old('agama', $biodata->agama) == 'Budha' ?
                                                    'selected' : '' }}>Budha</option>
                                                <option value="Konghucu" {{ old('agama', $biodata->agama) == 'Konghucu'
                                                    ? 'selected' : '' }}>Konghucu</option>
                                            </select>
                                            @error('agama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            @if(isset($allowEdit) && $allowEdit)
                                            <small class="text-muted">Agama akan mempengaruhi berkas yang diperlukan
                                                saat upload.</small>
                                            @endif
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="anak_ke" class="form-label fw-semibold">Anak Ke- <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('anak_ke') is-invalid @enderror" id="anak_ke"
                                                name="anak_ke" value="{{ old('anak_ke', $biodata->anak_ke) }}" required
                                                min="1" {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                            @error('anak_ke') <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="jml_saudara_kandung" class="form-label fw-semibold">Jumlah
                                                Saudara Kandung <span class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('jml_saudara_kandung') is-invalid @enderror"
                                                id="jml_saudara_kandung" name="jml_saudara_kandung"
                                                value="{{ old('jml_saudara_kandung', $biodata->jml_saudara_kandung) }}"
                                                required min="0" {{ isset($allowEdit) && !$allowEdit ? 'readonly' : ''
                                                }}>
                                            @error('jml_saudara_kandung') <div class="invalid-feedback">{{ $message }}
                                            </div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-map-pin me-2"></i>Informasi Alamat &
                                Sekolah Asal</h5>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="asal_sekolah" class="form-label fw-semibold">Nama Sekolah Asal (SD/MI)
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror"
                                        id="asal_sekolah" name="asal_sekolah"
                                        value="{{ old('asal_sekolah', $biodata->asal_sekolah) }}" required {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="npsn_asal_sekolah" class="form-label fw-semibold">NPSN Sekolah Asal
                                        (Opsional)</label>
                                    <input type="text"
                                        class="form-control @error('npsn_asal_sekolah') is-invalid @enderror"
                                        id="npsn_asal_sekolah" name="npsn_asal_sekolah"
                                        value="{{ old('npsn_asal_sekolah', $biodata->npsn_asal_sekolah) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('npsn_asal_sekolah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="alamat_asal_sekolah" class="form-label fw-semibold">Alamat Sekolah Asal
                                    <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('alamat_asal_sekolah') is-invalid @enderror"
                                    id="alamat_asal_sekolah" name="alamat_asal_sekolah"
                                    value="{{ old('alamat_asal_sekolah', $biodata->alamat_asal_sekolah) }}" required {{
                                    isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                @error('alamat_asal_sekolah') <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-group">
                                <label for="alamat_rumah" class="form-label fw-semibold">Alamat Rumah (Sesuai KK) <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat_rumah') is-invalid @enderror"
                                    id="alamat_rumah" name="alamat_rumah" rows="3" required {{ isset($allowEdit) &&
                                    !$allowEdit ? 'readonly' : ''
                                    }}>{{ old('alamat_rumah', $biodata->alamat_rumah) }}</textarea>
                                @error('alamat_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="kelurahan_desa" class="form-label fw-semibold">Kelurahan/Desa <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('kelurahan_desa') is-invalid @enderror"
                                        id="kelurahan_desa" name="kelurahan_desa"
                                        value="{{ old('kelurahan_desa', $biodata->kelurahan_desa) }}" required {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('kelurahan_desa') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="rt" class="form-label fw-semibold">RT <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('rt') is-invalid @enderror" id="rt"
                                        name="rt" value="{{ old('rt', $biodata->rt) }}" required {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('rt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="rw" class="form-label fw-semibold">RW <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('rw') is-invalid @enderror" id="rw"
                                        name="rw" value="{{ old('rw', $biodata->rw) }}" required {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('rw') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="kode_pos" class="form-label fw-semibold">Kode Pos <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_pos') is-invalid @enderror"
                                        id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $biodata->kode_pos) }}"
                                        required {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('kode_pos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Tambahkan field opsional lain jika perlu --}}
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="tinggi_badan" class="form-label fw-semibold">Tinggi Badan (cm)</label>
                                    <input type="number"
                                        class="form-control @error('tinggi_badan') is-invalid @enderror"
                                        id="tinggi_badan" name="tinggi_badan"
                                        value="{{ old('tinggi_badan', $biodata->tinggi_badan) }}" {{ isset($allowEdit)
                                        && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tinggi_badan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="berat_badan" class="form-label fw-semibold">Berat Badan (kg)</label>
                                    <input type="number" class="form-control @error('berat_badan') is-invalid @enderror"
                                        id="berat_badan" name="berat_badan"
                                        value="{{ old('berat_badan', $biodata->berat_badan) }}" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('berat_badan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="lingkar_kepala" class="form-label fw-semibold">Lingkar Kepala
                                        (cm)</label>
                                    <input type="number"
                                        class="form-control @error('lingkar_kepala') is-invalid @enderror"
                                        id="lingkar_kepala" name="lingkar_kepala"
                                        value="{{ old('lingkar_kepala', $biodata->lingkar_kepala) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('lingkar_kepala') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <hr class="my-4">
                            {{-- Bagian Data Orang Tua --}}
                            <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-users me-2"></i>Data Orang Tua</h5>
                            <h6 class="mb-3 text-muted">Data Ayah Kandung</h6>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="nama_ayah" class="form-label fw-semibold">Nama Ayah</label>
                                    <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                        id="nama_ayah" name="nama_ayah"
                                        value="{{ old('nama_ayah', $orangTua->nama_ayah) }}" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('nama_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nik_ayah" class="form-label fw-semibold">NIK Ayah</label>
                                    <input type="text" class="form-control @error('nik_ayah') is-invalid @enderror"
                                        id="nik_ayah" name="nik_ayah" value="{{ old('nik_ayah', $orangTua->nik_ayah) }}"
                                        {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('nik_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tempat_lahir_ayah" class="form-label fw-semibold">Tempat Lahir
                                        Ayah</label>
                                    <input type="text"
                                        class="form-control @error('tempat_lahir_ayah') is-invalid @enderror"
                                        id="tempat_lahir_ayah" name="tempat_lahir_ayah"
                                        value="{{ old('tempat_lahir_ayah', $orangTua->tempat_lahir_ayah) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tempat_lahir_ayah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tgl_lahir_ayah" class="form-label fw-semibold">Tanggal Lahir
                                        Ayah</label>
                                    <input type="date"
                                        class="form-control @error('tgl_lahir_ayah') is-invalid @enderror"
                                        id="tgl_lahir_ayah" name="tgl_lahir_ayah"
                                        value="{{ old('tgl_lahir_ayah', $orangTua->tgl_lahir_ayah ? $orangTua->tgl_lahir_ayah->format('Y-m-d') : '') }}"
                                        {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tgl_lahir_ayah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="pendidikan_ayah" class="form-label fw-semibold">Pendidikan Ayah</label>
                                    <select class="form-select @error('pendidikan_ayah') is-invalid @enderror"
                                        id="pendidikan_ayah" name="pendidikan_ayah" {{ isset($allowEdit) && !$allowEdit
                                        ? 'disabled' : '' }}>
                                        <option value="" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) == '' ?
                                            'selected' : '' }}>Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) == 'SD'
                                            ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) ==
                                            'SMP' ? 'selected' : '' }}>SMP
                                        </option>
                                        <option value="SMA" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) ==
                                            'SMA' ? 'selected' : '' }}>SMA
                                        </option>
                                        <option value="D3" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) == 'D3'
                                            ? 'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) == 'S1'
                                            ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) == 'S2'
                                            ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_ayah', $orangTua->pendidikan_ayah) == 'S3'
                                            ? 'selected' : '' }}>S3</option>
                                    </select>
                                    @error('pendidikan_ayah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="pekerjaan_ayah" class="form-label fw-semibold">Pekerjaan Ayah</label>
                                    <input type="text"
                                        class="form-control @error('pekerjaan_ayah') is-invalid @enderror"
                                        id="pekerjaan_ayah" name="pekerjaan_ayah"
                                        value="{{ old('pekerjaan_ayah', $orangTua->pekerjaan_ayah) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('pekerjaan_ayah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="penghasilan_ayah" class="form-label fw-semibold">Penghasilan Bulanan
                                        Ayah</label>
                                    <select class="form-select @error('penghasilan_ayah') is-invalid @enderror"
                                        id="penghasilan_ayah" name="penghasilan_ayah" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'disabled' : '' }}>
                                        <option value="">Pilih Penghasilan</option>
                                        <option value="<2juta" {{ old('penghasilan_ayah', $orangTua->penghasilan_ayah)
                                            == '<2juta' ? 'selected' : '' }}>
                                                < 2 juta</option>
                                        <option value="2-5juta" {{ old('penghasilan_ayah', $orangTua->penghasilan_ayah)
                                            == '2-5juta'? 'selected' : ''
                                            }}>2 - 5 juta</option>
                                        <option value="5-10juta" {{ old('penghasilan_ayah', $orangTua->penghasilan_ayah)
                                            == '5-10juta'? 'selected' : ''
                                            }}>5 - 10 juta</option>
                                        <option value=">10juta" {{ old('penghasilan_ayah', $orangTua->penghasilan_ayah)
                                            == '>10juta' ? 'selected' : ''
                                            }}> > 10 juta</option>
                                    </select>
                                    @error('penghasilan_ayah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp_ayah" class="form-label fw-semibold">No. HP Ayah</label>
                                    <input type="text" class="form-control @error('no_hp_ayah') is-invalid @enderror"
                                        id="no_hp_ayah" name="no_hp_ayah"
                                        value="{{ old('no_hp_ayah', $orangTua->no_hp_ayah) }}" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('no_hp_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <h6 class="mt-4 mb-3 text-muted">Data Ibu Kandung</h6>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="nama_ibu" class="form-label fw-semibold">Nama Ibu <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                        id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu', $orangTua->nama_ibu) }}"
                                        required {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('nama_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nik_ibu" class="form-label fw-semibold">NIK Ibu</label>
                                    <input type="text" class="form-control @error('nik_ibu') is-invalid @enderror"
                                        id="nik_ibu" name="nik_ibu" value="{{ old('nik_ibu', $orangTua->nik_ibu) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('nik_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tempat_lahir_ibu" class="form-label fw-semibold">Tempat Lahir
                                        Ibu</label>
                                    <input type="text"
                                        class="form-control @error('tempat_lahir_ibu') is-invalid @enderror"
                                        id="tempat_lahir_ibu" name="tempat_lahir_ibu"
                                        value="{{ old('tempat_lahir_ibu', $orangTua->tempat_lahir_ibu) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tempat_lahir_ibu') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tgl_lahir_ibu" class="form-label fw-semibold">Tanggal Lahir Ibu</label>
                                    <input type="date" class="form-control @error('tgl_lahir_ibu') is-invalid @enderror"
                                        id="tgl_lahir_ibu" name="tgl_lahir_ibu"
                                        value="{{ old('tgl_lahir_ibu', $orangTua->tgl_lahir_ibu ? $orangTua->tgl_lahir_ibu->format('Y-m-d') : '') }}"
                                        {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tgl_lahir_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="pendidikan_ibu" class="form-label fw-semibold">Pendidikan Ibu</label>
                                    <select class="form-select @error('pendidikan_ibu') is-invalid @enderror"
                                        id="pendidikan_ibu" name="pendidikan_ibu" {{ isset($allowEdit) && !$allowEdit
                                        ? 'disabled' : '' }}>
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'SD' ?
                                            'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'SMP'
                                            ? 'selected' : '' }}>SMP
                                        </option>
                                        <option value="SMA" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'SMA'
                                            ? 'selected' : '' }}>SMA
                                        </option>
                                        <option value="D3" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'D3' ?
                                            'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'S1' ?
                                            'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'S2' ?
                                            'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_ibu', $orangTua->pendidikan_ibu) == 'S3' ?
                                            'selected' : '' }}>S3</option>
                                    </select>
                                    @error('pendidikan_ibu') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="pekerjaan_ibu" class="form-label fw-semibold">Pekerjaan Ibu</label>
                                    <input type="text" class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                        id="pekerjaan_ibu" name="pekerjaan_ibu"
                                        value="{{ old('pekerjaan_ibu', $orangTua->pekerjaan_ibu) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('pekerjaan_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="penghasilan_ibu" class="form-label fw-semibold">Penghasilan Bulanan
                                        Ibu</label>
                                    <select class="form-select @error('penghasilan_ibu') is-invalid @enderror"
                                        id="penghasilan_ibu" name="penghasilan_ibu" {{ isset($allowEdit) && !$allowEdit
                                        ? 'disabled' : '' }}>
                                        <option value="">Pilih Penghasilan</option>
                                        <option value="<2juta" {{ old('penghasilan_ibu', $orangTua->penghasilan_ibu) ==
                                            '<2juta' ? 'selected' : '' }}>
                                                < 2 juta</option>
                                        <option value="2-5juta" {{ old('penghasilan_ibu', $orangTua->penghasilan_ibu) ==
                                            '2-5juta'? 'selected' : '' }}>2
                                            - 5 juta</option>
                                        <option value="5-10juta" {{ old('penghasilan_ibu', $orangTua->penghasilan_ibu)
                                            == '5-10juta'? 'selected' : ''
                                            }}>5 - 10 juta</option>
                                        <option value=">10juta" {{ old('penghasilan_ibu', $orangTua->penghasilan_ibu) ==
                                            '>10juta' ? 'selected' : '' }}>
                                            > 10 juta</option>
                                    </select>
                                    @error('penghasilan_ibu') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp_ibu" class="form-label fw-semibold">No. HP Ibu</label>
                                    <input type="text" class="form-control @error('no_hp_ibu') is-invalid @enderror"
                                        id="no_hp_ibu" name="no_hp_ibu"
                                        value="{{ old('no_hp_ibu', $orangTua->no_hp_ibu) }}" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('no_hp_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>


                            <hr class="my-4">
                            {{-- Bagian Data Wali --}}
                            <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-user-tie me-2"></i>Data Wali (Diisi
                                Jika Tinggal Bersama Wali, Bukan Orang Tua Kandung)</h5>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="nama_wali" class="form-label fw-semibold">Nama Wali</label>
                                    <input type="text" class="form-control @error('nama_wali') is-invalid @enderror"
                                        id="nama_wali" name="nama_wali" value="{{ old('nama_wali', $wali->nama_wali) }}"
                                        {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('nama_wali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nik_wali" class="form-label fw-semibold">NIK Wali</label>
                                    <input type="text" class="form-control @error('nik_wali') is-invalid @enderror"
                                        id="nik_wali" name="nik_wali" value="{{ old('nik_wali', $wali->nik_wali) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('nik_wali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tempat_lahir_wali" class="form-label fw-semibold">Tempat Lahir
                                        Wali</label>
                                    <input type="text"
                                        class="form-control @error('tempat_lahir_wali') is-invalid @enderror"
                                        id="tempat_lahir_wali" name="tempat_lahir_wali"
                                        value="{{ old('tempat_lahir_wali', $wali->tempat_lahir_wali) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tempat_lahir_wali') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="tgl_lahir_wali" class="form-label fw-semibold">Tanggal Lahir
                                        Wali</label>
                                    <input type="date"
                                        class="form-control @error('tgl_lahir_wali') is-invalid @enderror"
                                        id="tgl_lahir_wali" name="tgl_lahir_wali"
                                        value="{{ old('tgl_lahir_wali', $wali->tgl_lahir_wali ? $wali->tgl_lahir_wali->format('Y-m-d') : '') }}"
                                        {{ isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('tgl_lahir_wali') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="pendidikan_wali" class="form-label fw-semibold">Pendidikan Wali</label>
                                    <select class="form-select @error('pendidikan_wali') is-invalid @enderror"
                                        id="pendidikan_wali" name="pendidikan_wali" {{ isset($allowEdit) && !$allowEdit
                                        ? 'disabled' : '' }}>
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'SD' ?
                                            'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'SMP' ?
                                            'selected' : '' }}>SMP
                                        </option>
                                        <option value="SMA" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'SMA' ?
                                            'selected' : '' }}>SMA
                                        </option>
                                        <option value="D3" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'D3' ?
                                            'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'S1' ?
                                            'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'S2' ?
                                            'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_wali', $wali->pendidikan_wali) == 'S3' ?
                                            'selected' : '' }}>S3</option>
                                    </select>
                                    @error('pendidikan_wali') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="pekerjaan_wali" class="form-label fw-semibold">Pekerjaan Wali</label>
                                    <input type="text"
                                        class="form-control @error('pekerjaan_wali') is-invalid @enderror"
                                        id="pekerjaan_wali" name="pekerjaan_wali"
                                        value="{{ old('pekerjaan_wali', $wali->pekerjaan_wali) }}" {{ isset($allowEdit)
                                        && !$allowEdit ? 'readonly' : '' }}>
                                    @error('pekerjaan_wali') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="penghasilan_wali" class="form-label fw-semibold">Penghasilan Bulanan
                                        Wali</label>
                                    <select class="form-select @error('penghasilan_wali ') is-invalid @enderror"
                                        id="penghasilan_wali" name="penghasilan_wali" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'disabled' : '' }}>
                                        <option value="">Pilih Penghasilan</option>
                                        <option value="<2juta" {{ old('penghasilan_wali', $wali->penghasilan_wali) == '
                                            <2juta' ? 'selected' : '' }}>
                                                < 2 juta</option>
                                        <option value="2-5juta" {{ old('penghasilan_wali', $wali->penghasilan_wali) ==
                                            '2-5juta'? 'selected' : ''
                                            }}>2 - 5 juta</option>
                                        <option value="5-10juta" {{ old('penghasilan_wali', $wali->penghasilan_wali) ==
                                            '5-10juta'? 'selected' : ''
                                            }}>5 - 10 juta</option>
                                        <option value=">10juta" {{ old('penghasilan_wali', $wali->penghasilan_wali) ==
                                            '>10juta' ? 'selected' : ''
                                            }}> > 10 juta</option>
                                    </select>
                                    @error('penghasilan_wali') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp_wali" class="form-label fw-semibold">No. HP Wali</label>
                                    <input type="text" class="form-control @error('no_hp_wali') is-invalid @enderror"
                                        id="no_hp_wali" name="no_hp_wali"
                                        value="{{ old('no_hp_wali', $wali->no_hp_wali) }}" {{ isset($allowEdit) &&
                                        !$allowEdit ? 'readonly' : '' }}>
                                    @error('no_hp_wali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="hubungan_wali_dgn_siswa" class="form-label fw-semibold">Hubungan dengan
                                        Siswa</label>
                                    <input type="text"
                                        class="form-control @error('hubungan_wali_dgn_siswa') is-invalid @enderror"
                                        id="hubungan_wali_dgn_siswa" name="hubungan_wali_dgn_siswa"
                                        value="{{ old('hubungan_wali_dgn_siswa', $wali->hubungan_wali_dgn_siswa) }}" {{
                                        isset($allowEdit) && !$allowEdit ? 'readonly' : '' }}>
                                    @error('hubungan_wali_dgn_siswa') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="alamat_wali" class="form-label fw-semibold">Alamat Wali</label>
                                    <textarea class="form-control @error('alamat_wali') is-invalid @enderror"
                                        id="alamat_wali" name="alamat_wali" rows="2" {{ isset($allowEdit) && !$allowEdit
                                        ? 'readonly' : '' }}>{{ old('alamat_wali', $wali->alamat_wali) }}</textarea>
                                    @error('alamat_wali') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>
                        <div class="card-action">
                            {{-- Tampilkan tombol simpan hanya jika periode pengisian data aktif --}}
                            @if(isset($allowEdit) && $allowEdit)
                            <button type="submit" class="btn btn-success btn-round"><i
                                    class="fas fa-save me-2"></i>Simpan Data</button>
                            @endif
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-round"><i
                                    class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
