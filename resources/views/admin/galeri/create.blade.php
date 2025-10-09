@extends('layouts.admin.app')
@section('title', 'Tambah Foto Galeri')
@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-image"> Tambah Foto Galeri</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Tambah Foto ke Galeri</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('admin.galeri._form', ['item' => new \App\Models\Gallery()])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
