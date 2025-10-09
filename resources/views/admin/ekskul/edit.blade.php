@extends('layouts.admin.app')
@section('title', 'Edit Ekstrakurikuler: ' . Str::limit($item->judul, 30))

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-edit"> Edit Data Ekstrakurikuler</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Edit Ekstrakurikuler: {{ $item->judul }}</div>
                    </div>
                    <form action="{{ route('admin.ekskul.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @include('admin.ekskul._form', ['item' => $item])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
