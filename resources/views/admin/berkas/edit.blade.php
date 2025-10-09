@extends('layouts.admin.app')
@section('title', 'Tambah/Edit Berkas Pendaftar')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah/Edit Berkas {{ $user->nama_lengkap }}</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li><a href="{{ route('admin.pendaftar.index') }}">Data Pendaftar</a></li>
                <li><a href="{{ route('admin.pendaftar.show', $user->id) }}">Detail Pendaftar</a></li>
                <li><a href="{{ route('admin.berkas.index', $user->id) }}">Kelola Berkas</a></li>
                <li class="nav-item active">Tambah/Edit Berkas</li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Unggah Berkas</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.berkas.store-or-update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @foreach($berkasList as $field => $label)
                            <div class="mb-3">
                                <label for="{{ str_replace('file_', '', $field) }}" class="form-label">{{ $label }}</label>
                                @if($field === 'file_sertifikat_prestasi_lomba')
                                    <input type="file" name="{{ str_replace('file_', '', $field) }}[]" id="{{ str_replace('file_', '', $field) }}" class="form-control" multiple>
                                @else
                                    <input type="file" name="{{ str_replace('file_', '', $field) }}" id="{{ str_replace('file_', '', $field) }}" class="form-control">
                                @endif
                                @if($berkas->$field)
                                    @if($field === 'file_sertifikat_prestasi_lomba' && is_array($berkas->$field))
                                        @foreach($berkas->$field as $filePath)
                                        <p><a href="{{ asset('storage/' . $filePath) }}" target="_blank">Lihat Berkas {{ $loop->iteration }}</a></p>
                                        @endforeach
                                    @else
                                        <p><a href="{{ asset('storage/' . $berkas->$field) }}" target="_blank">Lihat Berkas Saat Ini</a></p>
                                    @endif
                                @endif
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-primary">Simpan Berkas</button>
                            <a href="{{ route('admin.berkas.index', $user->id) }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection