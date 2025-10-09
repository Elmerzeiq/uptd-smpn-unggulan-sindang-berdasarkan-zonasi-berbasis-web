@extends('layouts.admin.app')

@section('title', 'Tambah Guru/Staff Baru')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-users"> Buat Data Guru & Staff</i> </h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Tambah Guru/Staff</h4>
                    </div>
                    <div class="card-body">
                        {{-- Notifikasi sudah dihandle di app.blade.php --}}
                        <form action="{{ route('admin.guru-staff.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('admin.guru_staff._form', ['item' => new \App\Models\GuruDanStaff()])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
