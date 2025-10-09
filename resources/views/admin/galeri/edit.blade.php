@extends('layouts.admin.app')
@section('title', 'Edit Foto Galeri')
@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-image"> Edit Foto Galeri</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Foto Galeri: {{ $item->judul ?? 'ID: '.$item->id }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.galeri.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.galeri._form', ['item' => $item])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
