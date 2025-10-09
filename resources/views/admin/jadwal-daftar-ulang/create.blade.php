@extends('layouts.admin.app')

@section('title', 'Tambah Jadwal Daftar Ulang Baru')
@section('title_header_admin', 'Tambah Jadwal Daftar Ulang')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-plus-circle me-2"></i>Tambah Jadwal Baru</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}">Kelola Jadwal</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Tambah Baru</a></li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Tambah Jadwal Sesi</h5>
            </div>
            <div class="card-body">
                {{-- Menampilkan error validasi jika ada --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong class="fw-bold">Gagal menyimpan data!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_sesi">Nama Sesi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_sesi" name="nama_sesi"
                                    value="{{ old('nama_sesi') }}" placeholder="Contoh: Sesi Pagi Kelompok A" required>
                                <small class="form-text text-muted">Nama unik untuk mengidentifikasi sesi
                                    jadwal.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="waktu_mulai">Waktu Mulai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai"
                                    value="{{ old('waktu_mulai') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="waktu_selesai">Waktu Selesai <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai"
                                    value="{{ old('waktu_selesai') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kuota">Kuota Peserta <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="kuota" name="kuota"
                                    value="{{ old('kuota', 25) }}" required min="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="aktif" name="aktif" value="1" {{
                            old('aktif', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktifkan Jadwal Ini</label>
                        <small class="form-text text-muted d-block">Jika tidak dicentang, jadwal tidak akan ditampilkan
                            kepada siswa.</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan
                            Jadwal</button>
                        <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
