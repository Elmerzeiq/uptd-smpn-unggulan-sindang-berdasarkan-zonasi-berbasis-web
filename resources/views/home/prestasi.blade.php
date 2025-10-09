@extends('home.layouts.app')

@section('home')
<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area prestasi">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Prestasi Sekolah</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Prestasi Sekolah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="prestasi-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-wrapper">
                    <!-- Header Section -->
                    <div class="mb-5 text-center section-header">
                        <h2 class="section-title">Prestasi {{ $profil->nama_sekolah }}</h2>
                        <div class="title-underline"></div>
                        <p class="section-subtitle">Pencapaian dan penghargaan yang membanggakan</p>
                    </div>

                    <!-- Prestasi Content -->
                    <div class="prestasi-content">
                        @if($profil->prestasi_sekolah && !empty(trim($profil->prestasi_sekolah)))
                        <div class="content-card">
                            <div class="card-header">
                                <i class="fas fa-trophy"></i>
                                <h3>Prestasi dan Penghargaan</h3>
                            </div>
                            <div class="card-body">
                                <div class="content-text">
                                   <strong>{!! $profil->prestasi_sekolah !!}</strong>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-trophy fa-4x"></i>
                            </div>
                            <h3>Prestasi Sekolah</h3>
                            <p>Informasi prestasi sekolah akan segera tersedia. Silakan kunjungi halaman ini kembali
                                nanti.</p>
                        </div>
                        @endif

                        <!-- Achievement Categories -->
                        <div class="mt-5 row">
                            <div class="mb-4 col-lg-6 col-md-6">
                                <div class="achievement-card">
                                    <div class="achievement-header">
                                        <div class="achievement-icon">
                                            <i class="fas fa-medal"></i>
                                        </div>
                                        <h4>Prestasi Akademik</h4>
                                    </div>
                                    <div class="achievement-list">
                                        <div class="achievement-item">
                                            <i class="fas fa-star"></i>
                                            <span>Juara Olimpiade Matematika Tingkat Kabupaten</span>
                                        </div>
                                        <div class="achievement-item">
                                            <i class="fas fa-star"></i>
                                            <span>Juara Lomba Karya Tulis Ilmiah Tingkat Provinsi</span>
                                        </div>
                                        <div class="achievement-item">
                                            <i class="fas fa-star"></i>
                                            <span>Peringkat Tertinggi UN Se-Kabupaten</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-lg-6 col-md-6">
                                <div class="achievement-card">
                                    <div class="achievement-header">
                                        <div class="achievement-icon">
                                            <i class="fas fa-running"></i>
                                        </div>
                                        <h4>Prestasi Non-Akademik</h4>
                                    </div>
                                    <div class="achievement-list">
                                        <div class="achievement-item">
                                            <i class="fas fa-star"></i>
                                            <span>Juara Futsal Antar SMP Se-Kabupaten</span>
                                        </div>
                                        <div class="achievement-item">
                                            <i class="fas fa-star"></i>
                                            <span>Juara Lomba Seni Tari Tradisional</span>
                                        </div>
                                        <div class="achievement-item">
                                            <i class="fas fa-star"></i>
                                            <span>Juara Band Competition Tingkat Provinsi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="mt-4 row">
                            <div class="mb-4 col-lg-3 col-md-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>50+</h3>
                                        <p>Total Prestasi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-lg-3 col-md-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>15</h3>
                                        <p>Juara 1</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-lg-3 col-md-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>20</h3>
                                        <p>Juara 2</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-lg-3 col-md-6">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="fas fa-ribbon"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>15</h3>
                                        <p>Juara 3</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
