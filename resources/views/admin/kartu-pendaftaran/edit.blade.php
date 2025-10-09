@extends('layouts.admin.app')

@section('title', 'Edit Data Pendaftaran')

@section('admin_content')
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Edit Data Pendaftaran: {{ $kartu->user->nama_lengkap }}</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.kartu-pendaftaran.update', $kartu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Akun & Pendaftaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="nama_lengkap">Nama Lengkap Siswa</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                            value="{{ old('nama_lengkap', $kartu->user->nama_lengkap) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $kartu->user->email) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="nisn">NISN</label>
                        <input type="text" name="nisn" id="nisn" class="form-control"
                            value="{{ old('nisn', $kartu->user->nisn) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="jalur_pendaftaran">Jalur Pendaftaran</label>
                        <select name="jalur_pendaftaran" id="jalur_pendaftaran" class="form-control" required>
                            <option value="domisili" {{ old('jalur_pendaftaran', $kartu->jalur_pendaftaran) ==
                                'domisili' ? 'selected' : '' }}>Domisili</option>
                            <option value="prestasi_akademik_lomba" {{ old('jalur_pendaftaran', $kartu->
                                jalur_pendaftaran) == 'prestasi_akademik_lomba' ? 'selected' : '' }}>Prestasi Akademik
                                Lomba</option>
                            <option value="prestasi_non_akademik_lomba" {{ old('jalur_pendaftaran', $kartu->
                                jalur_pendaftaran) == 'prestasi_non_akademik_lomba' ? 'selected' : '' }}>Prestasi
                                Non-Akademik Lomba</option>
                            <option value="prestasi_rapor" {{ old('jalur_pendaftaran', $kartu->jalur_pendaftaran) ==
                                'prestasi_rapor' ? 'selected' : '' }}>Prestasi Rapor</option>
                            <option value="afirmasi_ketm" {{ old('jalur_pendaftaran', $kartu->jalur_pendaftaran) ==
                                'afirmasi_ketm' ? 'selected' : '' }}>Afirmasi KETM</option>
                            <option value="afirmasi_disabilitas" {{ old('jalur_pendaftaran', $kartu->jalur_pendaftaran)
                                == 'afirmasi_disabilitas' ? 'selected' : '' }}>Afirmasi Disabilitas</option>
                            <option value="mutasi_pindah_tugas" {{ old('jalur_pendaftaran', $kartu->jalur_pendaftaran)
                                == 'mutasi_pindah_tugas' ? 'selected' : '' }}>Mutasi Pindah Tugas</option>
                            <option value="mutasi_anak_guru" {{ old('jalur_pendaftaran', $kartu->jalur_pendaftaran) ==
                                'mutasi_anak_guru' ? 'selected' : '' }}>Mutasi Anak Guru</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="status_pendaftaran">Status Pendaftaran Siswa</label>
                        <select name="status_pendaftaran" id="status_pendaftaran" class="form-control" required>
                            <option value="belum_diverifikasi" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'belum_diverifikasi' ? 'selected' : '' }}>Belum
                                Diverifikasi</option>
                            <option value="menunggu_kelengkapan_data" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'menunggu_kelengkapan_data' ? 'selected' : '' }}>Menunggu
                                Kelengkapan Data</option>
                            <option value="menunggu_verifikasi_berkas" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'menunggu_verifikasi_berkas' ? 'selected' : '' }}>Menunggu
                                Verifikasi Berkas</option>
                            <option value="berkas_tidak_lengkap" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'berkas_tidak_lengkap' ? 'selected' : '' }}>Berkas Tidak
                                Lengkap</option>
                            <option value="berkas_diverifikasi" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'berkas_diverifikasi' ? 'selected' : '' }}>Berkas
                                Diverifikasi</option>
                            <option value="lulus_seleksi" {{ old('status_pendaftaran', $kartu->user->status_pendaftaran)
                                == 'lulus_seleksi' ? 'selected' : '' }}>Lulus Seleksi</option>
                            <option value="tidak_lulus_seleksi" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'tidak_lulus_seleksi' ? 'selected' : '' }}>Tidak Lulus
                            </option>
                            <option value="mengundurkan_diri" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'mengundurkan_diri' ? 'selected' : '' }}>Mengundurkan Diri
                            </option>
                            <option value="daftar_ulang_selesai" {{ old('status_pendaftaran', $kartu->
                                user->status_pendaftaran) == 'daftar_ulang_selesai' ? 'selected' : '' }}>Daftar Ulang
                                Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Biodata Calon Siswa</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                            value="{{ old('tempat_lahir', $kartu->user->biodata->tempat_lahir) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                            value="{{ old('tgl_lahir', $kartu->user->biodata->tgl_lahir) }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="jns_kelamin">Jenis Kelamin</label>
                        <select name="jns_kelamin" id="jns_kelamin" class="form-control" required>
                            <option value="L" {{ old('jns_kelamin', $kartu->user->biodata->jns_kelamin) == 'L' ?
                                'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jns_kelamin', $kartu->user->biodata->jns_kelamin) == 'P' ?
                                'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="asal_sekolah">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control"
                            value="{{ old('asal_sekolah', $kartu->user->biodata->asal_sekolah) }}" required>
                    </div>
                    <div class="col-12 form-group">
                        <label for="alamat_rumah">Alamat Lengkap</label>
                        <textarea name="alamat_rumah" id="alamat_rumah" rows="3"
                            class="form-control">{{ old('alamat_rumah', $kartu->user->biodata->alamat_rumah) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Data Orang Tua</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="nama_ayah">Nama Ayah</label>
                        <input type="text" name="nama_ayah" class="form-control"
                            value="{{ old('nama_ayah', $kartu->user->orangTua->nama_ayah ?? '') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" class="form-control"
                            value="{{ old('pekerjaan_ayah', $kartu->user->orangTua->pekerjaan_ayah ?? '') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="no_hp_ayah">Nomor Telepon Ayah</label>
                        <input type="text" name="no_hp_ayah" class="form-control"
                            value="{{ old('no_hp_ayah', $kartu->user->orangTua->no_hp_ayah ?? '') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="nama_ibu">Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control"
                            value="{{ old('nama_ibu', $kartu->user->orangTua->nama_ibu ?? '') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" class="form-control"
                            value="{{ old('pekerjaan_ibu', $kartu->user->orangTua->pekerjaan_ibu ?? '') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="no_hp_ibu">Nomor Telepon Ibu</label>
                        <input type="text" name="no_hp_ibu" class="form-control"
                            value="{{ old('no_hp_ibu', $kartu->user->orangTua->no_hp_ibu ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <a href="{{ route('admin.kartu-pendaftaran.show', $kartu->id) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
