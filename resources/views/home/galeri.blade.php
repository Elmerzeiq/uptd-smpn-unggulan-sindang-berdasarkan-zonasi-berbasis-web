@extends('home.layouts.app')

@section('home')

<div class="breadcrumb-banner-area gallery">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Gallery</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Gallery</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->
<!--Gallery Area Start-->
<div class="gallery-area section-padding gallery-full-width">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="filter-menu">
                    <ul>
                        <li class="filter" data-filter="all">Semua</li>
                        <li class="filter" data-filter=".kegiatan">Kegiatan</li>
                        <li class="filter" data-filter=".ekstrakurikuler">Ekstrakurikuler</li>
                        <li class="filter" data-filter=".prestasi">Prestasi</li>
                        {{-- <li class="filter" data-filter=".fasilitas">Fasilitas</li> --}}
                    </ul>
                </div>
            </div>
        </div>

        <div class="gallery-wrapper filter-items">
            <div class="row">
                @if($gallery->count() > 0)
                @foreach($gallery as $item)
                <div class="col-lg-4 col-md-4 col-sm-6 col-12 mix single-items {{ strtolower($item->kategori) }} overlay-hover"
                    style="padding: 15px;">
                    <div class="overlay-effect sea-green-overlay">
                        <a href="#">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->kategori }}" class="gallery-img"
                                style="width: 100%; height: 250px; object-fit: cover;" />
                        </a>
                        <div class="gallery-hover-effect">
                            <a class="gallery-icon venobox" href="{{ Storage::url($item->image) }}">
                                <i class="fa fa-search-plus"></i>
                            </a>
                            <span class="gallery-text">{{ ucfirst($item->kategori) }}</span>
                            @if($item->judul)
                            <div class="gallery-title" style="color: white; margin-top: 5px; font-size: 12px;">
                                {{ $item->judul }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-12">
                    <div class="text-center" style="padding: 50px;">
                        <h3>Belum ada foto di galeri</h3>
                        <p>Foto akan ditampilkan setelah ditambahkan oleh admin.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--End of Gallery Area-->

<!-- JavaScript untuk filter kategori -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter');
    const galleryItems = document.querySelectorAll('.single-items');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if (filterValue === 'all') {
                    item.style.display = 'block';
                } else {
                    const category = filterValue.replace('.', '');
                    if (item.classList.contains(category)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });

    // Set default active filter
    document.querySelector('.filter[data-filter="all"]').classList.add('active');
});
</script>

<style>
    .filter-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: center;
        margin-bottom: 30px;
    }

    .filter-menu ul li {
        display: inline-block;
        margin: 0 10px;
        padding: 10px 20px;
        background: #f8f9fa;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-menu ul li:hover,
    .filter-menu ul li.active {
        background: #007bff;
        color: white;
    }

    .gallery-img {
        transition: transform 0.3s ease;
    }

    .overlay-hover:hover .gallery-img {
        transform: scale(1.1);
    }

    .gallery-hover-effect {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .overlay-hover:hover .gallery-hover-effect {
        opacity: 1;
    }

    .overlay-effect {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .sea-green-overlay::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 123, 255, 0.8);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
    }

    .overlay-hover:hover .sea-green-overlay::before {
        opacity: 1;
    }

    .gallery-hover-effect {
        z-index: 2;
    }

    .gallery-icon {
        display: inline-block;
        width: 50px;
        height: 50px;
        line-height: 50px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 50%;
        text-decoration: none;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .gallery-icon:hover {
        background: rgba(255, 255, 255, 0.4);
        color: white;
        text-decoration: none;
    }

    .gallery-text {
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

@endsection
