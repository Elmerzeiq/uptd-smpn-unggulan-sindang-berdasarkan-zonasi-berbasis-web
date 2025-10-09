@extends('home.layouts.app')

@section('home')
<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area kurikulum">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Kurikulum</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Kurikulum</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="kurikulum-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-wrapper">
                    <!-- Header Section -->
                    <div class="mb-5 text-center section-header">
                        <h2 class="section-title">Kurikulum {{ $profil->nama_sekolah }}</h2>
                        <div class="title-underline"></div>
                        <p class="section-subtitle">Sistem pendidikan yang komprehensif dan berkualitas</p>
                    </div>

                    <!-- Kurikulum Content -->
                    <div class="kurikulum-content">
                        @if($profil->kurikulum && !empty(trim($profil->kurikulum)))
                        <div class="content-card">
                            <div class="card-header">
                                <i class="fas fa-graduation-cap"></i>
                                <h3>Struktur Kurikulum</h3>
                            </div>
                            <div class="card-body">
                                <div class="content-text">
                                    <p>{!! $profil ? $profil->kurikulum : '-' !!}</p>
                                    
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-book-open fa-4x"></i>
                            </div>
                            <h3>Informasi Kurikulum</h3>
                            <p>Informasi kurikulum akan segera tersedia. Silakan kunjungi halaman ini kembali nanti.</p>
                        </div>
                        @endif

                        <!-- Additional Info Cards -->
                        <div class="mt-5 row">
                            <div class="mb-4 col-lg-4 col-md-6">
                                <div class="info-card">
                                    <div class="card-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="card-content">
                                        <h4>Jumlah Siswa</h4>
                                        <p class="card-number">{{ number_format($profil->jml_siswa) }}</p>
                                        <span class="card-label">Siswa Aktif</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-lg-4 col-md-6">
                                <div class="info-card">
                                    <div class="card-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="card-content">
                                        <h4>Tenaga Pengajar</h4>
                                        <p class="card-number">{{ number_format($profil->jml_guru) }}</p>
                                        <span class="card-label">Guru Berkualitas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 col-lg-4 col-md-6">
                                <div class="info-card">
                                    <div class="card-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="card-content">
                                        <h4>Staff Pendukung</h4>
                                        <p class="card-number">{{ number_format($profil->jml_staff) }}</p>
                                        <span class="card-label">Staff Profesional</span>
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
