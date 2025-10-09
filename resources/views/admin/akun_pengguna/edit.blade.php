@extends('layouts.admin.app')
@section('title', 'Edit Akun: ' . $user->nama_lengkap)

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
                        <div class="card-title">Form Edit Akun: {{ $user->nama_lengkap }} (@if($user->role == 'siswa')
                            {{ $user->nisn }} @else {{ $user->email }} @endif)</div>
                    </div>
                <form action="{{ route('admin.akun-pengguna.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @include('admin.akun_pengguna._form', ['user' => $user])
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
