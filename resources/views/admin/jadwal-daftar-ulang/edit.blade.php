@extends('layouts.admin.app')

@section('title', 'Edit Jadwal Daftar Ulang')
@section('title_header_admin', 'Edit Jadwal Daftar Ulang')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-edit me-2"></i>Edit Jadwal</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}">Kelola Jadwal</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Edit</a></li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Jadwal: {{ $jadwalDaftarUlang->nama_sesi }}</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.update', $jadwalDaftarUlang->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_sesi">Nama Sesi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_sesi" name="nama_sesi"
                                    value="{{ old('nama_sesi', $jadwalDaftarUlang->nama_sesi) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', $jadwalDaftarUlang->tanggal->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="waktu_mulai">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai"
                                    value="{{ old('waktu_mulai', $jadwalDaftarUlang->waktu_mulai) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="waktu_selesai">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai"
                                    value="{{ old('waktu_selesai', $jadwalDaftarUlang->waktu_selesai) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kuota">Kuota Peserta <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="kuota" name="kuota"
                                    value="{{ old('kuota', $jadwalDaftarUlang->kuota) }}" required min="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="aktif" name="aktif" value="1" {{
                            old('aktif', $jadwalDaftarUlang->aktif) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktifkan Jadwal Ini</label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan
                            Perubahan</button>
                        <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
