@extends('layouts.admin.app')
@section('title', 'Tambah Akun Pengguna Baru')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Manajemen Akun Pengguna</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Tambah Akun Pengguna</div>
                        <small class="form-text text-muted">Gunakan form ini untuk menambah akun Administrator. Akun
                            siswa dibuat melalui halaman registrasi publik.</small>
                    </div>
                    <form action="{{ route('admin.akun-pengguna.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @include('admin.akun_pengguna._form', ['user' => new \App\Models\User()])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
