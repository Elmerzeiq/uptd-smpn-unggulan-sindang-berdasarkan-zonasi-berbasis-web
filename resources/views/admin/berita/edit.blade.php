@extends('layouts.admin.app')
@section('title', 'Edit Berita: ' . Str::limit($item->judul, 30))

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-edit"> Edit Berita Sekolah</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Edit Berita: {{ $item->judul }}</div>
                    </div>
                    {{-- Penting: Pastikan $item->id ada dan benar --}}
                    <form action="{{ route('admin.berita.update', ['beritum' => $item->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @include('admin.berita._form', ['item' => $item])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
