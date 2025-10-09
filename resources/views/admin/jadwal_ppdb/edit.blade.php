@extends('layouts.admin.app')
@section('title', 'Edit Jadwal SPMB: ' . $jadwalPpdb->tahun_ajaran)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Manajemen Jadwal SPMB</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Edit Jadwal SPMB: {{ $jadwalPpdb->tahun_ajaran }}</div>
                    </div>
                    <form action="{{ route('admin.jadwal-ppdb.update', $jadwalPpdb->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            {{-- Notifikasi error validasi sudah dihandle di app.blade.php --}}
                            @include('admin.jadwal_ppdb._form', ['jadwalPpdb' => $jadwalPpdb]) {{-- Kirim data jadwal
                            yang diedit --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
