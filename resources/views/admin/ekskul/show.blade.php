@extends('layouts.admin.app')
@section('title', 'Detail Ekstrakurikuler: ' . $item->judul)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-basketball-ball"> Detail Ekstrakurikuler</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">{{ $item->judul }}</h4>
                            <a href="{{ route('admin.ekskul.edit', $item->id) }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-edit"></i> Edit Data
                            </a>
                        </div>
                        @if($item->kategori)
                        <div class="card-category">Kategori: {{ $item->kategori }}</div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($item->image)
                        <div class="text-center mb-3">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}"
                                class="img-fluid rounded shadow-sm"
                                style="max-height: 300px; width:auto; display:block; margin-left:auto; margin-right:auto;">
                        </div>
                        @endif

                        <h5>Deskripsi Singkat:</h5>
                        <p class="lead">{{ $item->deskripsi }}</p>
                        <hr>

                        @if($item->isi)
                        <h5>Detail Kegiatan / Program Kerja:</h5>
                        <div>
                            {!! $item->isi !!} {{-- Jika isi adalah HTML dari TinyMCE --}}
                        </div>
                        @else
                        <p class="text-muted"><em>Detail kegiatan belum diisi.</em></p>
                        @endif
                    </div>
                    <div class="card-action">
                        <a href="{{ route('admin.ekskul.index') }}" class="btn btn-secondary btn-round"><i
                                class="fas fa-arrow-left"></i> Kembali ke Daftar Ekskul</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
