@extends('home.layouts.app')

@section('home')

<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">{{ $ekskul->judul }}</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('ekskul.index') }}">Ekstrakurikuler</a></li>
                            <li>{{ Str::limit($ekskul->judul, 30) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Main Content -->
            <div class="card shadow-sm border-0 mb-5">
                @if($ekskul->image)
                <img src="{{ Storage::url($ekskul->image) }}" class="card-img-top img-fluid" alt="{{ $ekskul->judul }}"
                    style="height: 400px; object-fit: cover;">
                @endif

                <div class="card-body p-4">
                    <div class="mb-3">
                        <h2 class="card-title mb-3">{{ $ekskul->judul }}</h2>

                        <div class="d-flex flex-wrap align-items-center mb-3 text-muted">
                            <i class="fa fa-calendar me-2"></i>
                            <span class="me-3">{{ \Carbon\Carbon::parse($ekskul->tanggal)->translatedFormat('d F Y')
                                }}</span>

                            @if($ekskul->kategori)
                            <span class="badge bg-primary">{{ $ekskul->kategori }}</span>
                            @endif
                        </div>
                    </div>

                    @if($ekskul->deskripsi)
                    <div class="alert alert-light border-start border-4 border-primary">
                        <h5 class="mb-0">{{ $ekskul->deskripsi }}</h5>
                    </div>
                    @endif

                    @if($ekskul->isi)
                    <div class="content mt-4">
                        {!! nl2br(e($ekskul->isi)) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Ekstrakurikuler -->
    @if($ekskul_detail->count() > 0)
    <hr class="my-5">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4 text-center">Ekstrakurikuler Lainnya</h4>
        </div>
    </div>
    <div class="row">
        @foreach($ekskul_detail as $item)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                @if($item->image)
                <img src="{{ Storage::url($item->image) }}" class="card-img-top" alt="{{ $item->judul }}"
                    style="height: 180px; object-fit: cover;">
                @else
                <img src="{{ asset('images/default-ekskul.jpg') }}" class="card-img-top" alt="{{ $item->judul }}"
                    style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ $item->judul }}</h6>
                    @if($item->kategori)
                    <span class="badge bg-secondary mb-2 align-self-start">{{ $item->kategori }}</span>
                    @endif
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($item->deskripsi, 80) }}
                    </p>
                    <a href="{{ route('ekskul.show', $item->id) }}" class="btn btn-sm btn-outline-primary mt-auto">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<style>
    .content {
        line-height: 1.8;
        color: #333;
    }

    .content p {
        margin-bottom: 1rem;
    }
</style>

@endsection
