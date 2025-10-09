@extends('layouts.admin.app')
@section('title', 'Tambah Jadwal SPMB Baru')

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
                        <div class="card-title">Form Tambah Jadwal SPMB Baru</div>
                    </div>
                    <form action="{{ route('admin.jadwal-ppdb.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            {{-- Notifikasi error validasi sudah dihandle di app.blade.php --}}
                            @include('admin.jadwal_ppdb._form', ['jadwalPpdb' => new \App\Models\JadwalPpdb()]) {{--
                            Kirim instance model kosong --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
