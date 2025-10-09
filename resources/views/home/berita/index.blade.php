@extends('home.layouts.app')

@section('home')
<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area blog">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Berita</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Berita</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blog-fullwidth-area section-padding blog-two">
    <div class="container">
        <div class="row">
            @foreach($beritas as $berita)
            <div class="col-lg-4 col-md-6">
                <div class="single-blog-item overlay-hover">
                    <div class="single-blog-image">
                        <div class="overlay-effect">
                            <a href="{{ route('berita.show', $berita->id) }}">
                                @if($berita->image)
                                <img src="{{ Storage::url($berita->image) }}" class="card-img-top"
                                    alt="{{ $berita->judul }}" style="width: 100%; height: 380px; object-fit: cover;">
                                @else
                                <img src="{{ asset('images/default-news.jpg') }}" class="card-img-top"
                                    alt="{{ $berita->judul }}" style="width: 100%; height: 380px; object-fit: cover;">
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="single-blog-text">
                        <div class="blog-date">
                            <span><i class="fa fa-calendar"></i>{{ \Carbon\Carbon::parse($berita->tanggal)->format('d F,
                                Y') }}</span>
                        </div>
                        <h4><a href="{{ route('berita.show', $berita->id) }}">{{ $berita->judul }}</a></h4>
                        <p>{!! Str::limit($berita->deskripsi, 200) !!}</p>

                        <a href="{{ route('berita.show', $berita->id) }}" class="btn btn-primary btn-sm read-more-btn">
                            Baca Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="text-center col-md-12">
                {{ $beritas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
