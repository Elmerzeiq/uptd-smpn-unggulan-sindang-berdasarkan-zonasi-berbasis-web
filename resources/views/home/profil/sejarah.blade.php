@extends('home.layouts.app')

@section('title', 'Sejarah - ' . ($profil->nama_sekolah ?? 'Nama Sekolah'))

@section('home')

<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Sejarah Sekolah</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Sejarah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br>

<!--Sejarah Area Start-->
<div class="about-area section-padding-bottom">
    <div class="container">
        <div class="row justify-content-between">
            <!-- Kolom Sejarah -->
            <div class="mb-4 col-lg-7">
                <div class="section-title-wrapper">
                    <div class="section-title">
                        <h3>Sejarah {{ $profil->nama_sekolah ?? 'Sekolah Kami' }}</h3>
                    </div>
                </div>
                <br>
                <div class="blog-post-details-img">
                    <img src="{{ asset('uploads/images/' . $profil->image) }}" class="card-img-top" alt="{{ $profil->judul }}">
                </div>
                @if($profil && $profil->sejarah)
                <div class="blog-post-details-text">
                   <h4>{!! $profil->sejarah !!}</h4> 
                </div>
                @else
                <div class="mt-3 alert alert-info">
                    <i class="fas fa-info-circle"></i> Sejarah sekolah belum tersedia.
                </div>
                @endif
            </div>
            
           
            {{-- <div class="blog-post-details-text">
                <h4>{!! $berita->deskripsi !!}</h4>
                <p>{!! $berita->isi !!}</p>
            </div> --}}

            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-content">
                    <!-- Gambar -->
                    {{-- <div class="mb-4 widget">
                        @if($profil && $profil->image)
                        <img src="{{ asset('uploads/images/' . $profil->image) }}" class="rounded shadow img-fluid"
                            alt="Gambar Sekolah">
                        @elseif($profil && $profil->logo_sekolah)
                        <img src="{{ Storage::url($profil->logo_sekolah) }}" class="rounded shadow img-fluid"
                            alt="Logo Sekolah">
                        @else
                        <div class="default-image-placeholder">
                            <i class="fas fa-school fa-3x text-muted"></i>
                            <p class="mt-2 text-muted">Gambar tidak tersedia</p>
                        </div>
                        @endif
                    </div> --}}

                    <!-- Statistik Sekolah -->
                    @if($profil)
                    <div class="mb-4 widget">
                        <h4 class="widget-title">Data Sekolah</h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{{ number_format($profil->jml_siswa) }}</div>
                                <div class="stat-label">Siswa</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ number_format($profil->jml_guru) }}</div>
                                <div class="stat-label">Guru</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ number_format($profil->jml_staff) }}</div>
                                <div class="stat-label">Staf</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Menu Navigasi -->
                    <div class="widget">
                        <h4 class="widget-title">Profil Sekolah</h4>
                        <ul class="sidebar-menu">
                            <li><a href="{{ route('profil.visi_misi') }}"><i class="fas fa-bullseye"></i> Visi &
                                    Misi</a></li>
                            <li><a href="{{ route('kurikulum') }}"><i class="fas fa-book"></i> Kurikulum</a></li>
                            <li><a href="{{ route('fasilitas') }}"><i class="fas fa-building"></i> Fasilitas</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<!--End of Sejarah Area-->

@endsection

@push('styles')
<style>
    .sejarah-content {
        font-size: 16px;
        line-height: 1.8;
    }

    .content-text {
        color: #34495e;
        text-align: justify;
    }

    .content-text h1,
    .content-text h2,
    .content-text h3 {
        color: #2c3e50;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .content-text p {
        margin-bottom: 1.5rem;
    }

    .content-text ul,
    .content-text ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }

    .content-text li {
        margin-bottom: 0.5rem;
    }

    .widget {
        background: #ffffff;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .widget-title {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-size: 18px;
        font-weight: 600;
        border-bottom: 2px solid #3498db;
        padding-bottom: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        text-align: center;
    }

    .stat-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #3498db;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        border-bottom: 1px solid #e9ecef;
    }

    .sidebar-menu li:last-child {
        border-bottom: none;
    }

    .sidebar-menu a {
        display: block;
        padding: 0.75rem 0;
        color: #495057;
        text-decoration: none;
        transition: color 0.3s;
    }

    .sidebar-menu a:hover,
    .sidebar-menu li.active a {
        color: #3498db;
        text-decoration: none;
    }

    .sidebar-menu i {
        margin-right: 0.5rem;
        width: 16px;
    }

    .default-image-placeholder {
        text-align: center;
        padding: 2rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
    }

    .no-content {
        text-align: center;
        padding: 3rem 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .sejarah-content {
            font-size: 15px;
        }

        .content-text h1,
        .content-text h2,
        .content-text h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
