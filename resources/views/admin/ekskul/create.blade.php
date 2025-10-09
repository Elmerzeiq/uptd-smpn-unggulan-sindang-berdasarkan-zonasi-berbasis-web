@extends('layouts.admin.app')
@section('title', 'Tambah Ekstrakurikuler Baru')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-basketball-ball"> Tambah Data Ekstrakurikuler</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Tambah Data Ekstrakurikuler</div>
                    </div>
                    <form action="{{ route('admin.ekskul.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @include('admin.ekskul._form', ['item' => new \App\Models\Ekskul()])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
