@extends('home.layouts.app')

@section('home')

<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area kurikulum">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Visi dan Misi</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Visi dan Misi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="visi-misi-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-wrapper">
                    <!-- Header Section -->
                    <div class="section-header text-center mb-5">
                        <h2 class="section-title">Tentang {{ $profil->nama_sekolah ?? 'Sekolah Kami' }}</h2>
                        <div class="title-underline"></div>
                        <p class="section-subtitle">Mengenal lebih dekat visi, misi, dan budaya sekolah kami</p>
                    </div>

                    <!-- Main Content -->
                    <div class="visi-misi-content">
                        <!-- Visi Section -->
                        @if($profil->visi && !empty(trim($profil->visi)))
                        <div class="main-content-card visi-card" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-header-modern">
                                <div class="icon-container visi-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="header-text">
                                    <h3>Visi</h3>
                                    <span class="header-subtitle">Cita-cita dan Tujuan Masa Depan</span>
                                </div>
                            </div>
                            <div class="card-body-modern">
                                <div class="content-text">
                                    {!! $profil->visi !!}
                                </div>
                            </div>
                            <div class="card-decoration visi-decoration"></div>
                        </div>
                        @else
                        <div class="empty-state-modern" data-aos="fade-up">
                            <div class="empty-icon">
                                <i class="fas fa-eye fa-4x"></i>
                            </div>
                            <h3>Visi Sekolah</h3>
                            <p>Informasi visi sekolah akan segera tersedia. Silakan kunjungi halaman ini kembali nanti.
                            </p>
                        </div>
                        @endif

                        <!-- Misi Section -->
                        @if($profil->misi && !empty(trim($profil->misi)))
                        <div class="main-content-card misi-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header-modern">
                                <div class="icon-container misi-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div class="header-text">
                                    <h3>Misi</h3>
                                    <span class="header-subtitle">Langkah Konkret Menuju Visi</span>
                                </div>
                            </div>
                            <div class="card-body-modern">
                                <div class="content-text">
                                    {!! $profil->misi !!}
                                </div>
                            </div>
                            <div class="card-decoration misi-decoration"></div>
                        </div>
                        @else
                        <div class="empty-state-modern" data-aos="fade-up" data-aos-delay="200">
                            <div class="empty-icon">
                                <i class="fas fa-bullseye fa-4x"></i>
                            </div>
                            <h3>Misi Sekolah</h3>
                            <p>Informasi misi sekolah akan segera tersedia. Silakan kunjungi halaman ini kembali nanti.
                            </p>
                        </div>
                        @endif

                        <!-- Additional Info Cards -->
                        <div class="row mt-5">
                            @if($profil->budaya_sekolah && !empty(trim($profil->budaya_sekolah)))
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="info-card-modern budaya-card" data-aos="fade-up" data-aos-delay="300">
                                    <div class="card-icon-wrapper">
                                        <div class="card-icon budaya-bg">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">Budaya Sekolah</h4>
                                        <div class="card-text">{!! $profil->budaya_sekolah !!}</div>
                                    </div>
                                    <div class="card-hover-effect"></div>
                                </div>
                            </div>
                            @endif

                            @if($profil->fasilitas_sekolah && !empty(trim($profil->fasilitas_sekolah)))
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="info-card-modern fasilitas-card" data-aos="fade-up" data-aos-delay="400">
                                    <div class="card-icon-wrapper">
                                        <div class="card-icon fasilitas-bg">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">Fasilitas Sekolah</h4>
                                        <div class="card-text">{!! $profil->fasilitas_sekolah !!}</div>
                                    </div>
                                    <div class="card-hover-effect"></div>
                                </div>
                            </div>
                            @endif

                            @if($profil->metode_pengajaran && !empty(trim($profil->metode_pengajaran)))
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="info-card-modern metode-card" data-aos="fade-up" data-aos-delay="500">
                                    <div class="card-icon-wrapper">
                                        <div class="card-icon metode-bg">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">Metode Pengajaran</h4>
                                        <div class="card-text">{!! $profil->metode_pengajaran !!}</div>
                                    </div>
                                    <div class="card-hover-effect"></div>
                                </div>
                            </div>
                            @endif

                            @if($profil->ekstrakurikuler && !empty(trim($profil->ekstrakurikuler)))
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="info-card-modern ekskul-card" data-aos="fade-up" data-aos-delay="600">
                                    <div class="card-icon-wrapper">
                                        <div class="card-icon ekskul-bg">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <h4 class="card-title">Ekstrakurikuler</h4>
                                        <div class="card-text">{!! $profil->ekstrakurikuler !!}</div>
                                    </div>
                                    <div class="card-hover-effect"></div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- School Profile Image Section -->
                        @if($profil->image)
                        <div class="school-image-section mt-5" data-aos="fade-up" data-aos-delay="700">
                            <div class="image-container">
                                <img src="{{ url('uploads/images/' . $profil->image) }}"
                                    alt="{{ $profil->nama_sekolah ?? 'Sekolah' }}" class="school-image">
                                <div class="image-overlay">
                                    <div class="overlay-content">
                                        <h4>{{ $profil->nama_sekolah ?? 'Sekolah Kami' }}</h4>
                                        <p>Membangun Generasi Unggul dan Berkarakter</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --visi-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --misi-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --budaya-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --fasilitas-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        --metode-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        --ekskul-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        --shadow-light: 0 10px 40px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 15px 50px rgba(0, 0, 0, 0.15);
        --shadow-heavy: 0 25px 70px rgba(0, 0, 0, 0.2);
        --text-dark: #2d3748;
        --text-light: #4a5568;
        --text-muted: #718096;
    }

    /* Main Container */
    .visi-misi-area {
        padding: 80px 0;
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        min-height: 100vh;
    }

    .content-wrapper {
        position: relative;
    }

    /* Section Header */
    .section-header {
        margin-bottom: 4rem;
    }

    .section-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
    }

    .title-underline {
        width: 100px;
        height: 6px;
        background: var(--primary-gradient);
        margin: 0 auto 1.5rem;
        border-radius: 3px;
    }

    .section-subtitle {
        font-size: 1.2rem;
        color: var(--text-light);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Main Content Cards */
    .main-content-card {
        background: white;
        border-radius: 25px;
        box-shadow: var(--shadow-light);
        margin-bottom: 3rem;
        overflow: hidden;
        position: relative;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .main-content-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-heavy);
    }

    .card-header-modern {
        padding: 2.5rem 2.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .icon-container {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .visi-icon {
        background: var(--visi-gradient);
    }

    .misi-icon {
        background: var(--misi-gradient);
    }

    .icon-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .main-content-card:hover .icon-container::after {
        opacity: 1;
    }

    .header-text h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.025em;
    }

    .header-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .card-body-modern {
        padding: 1rem 2.5rem 2.5rem;
        position: relative;
        z-index: 2;
    }

    .content-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-light);
    }

    .content-text h1,
    .content-text h2,
    .content-text h3,
    .content-text h4 {
        color: var(--text-dark);
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .content-text p {
        margin-bottom: 1.2rem;
    }

    .content-text ul,
    .content-text ol {
        padding-left: 1.5rem;
        margin-bottom: 1.2rem;
    }

    .content-text li {
        margin-bottom: 0.5rem;
        line-height: 1.7;
    }

    /* Card Decorations */
    .card-decoration {
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 100%;
        opacity: 0.05;
        z-index: 1;
        pointer-events: none;
    }

    .visi-decoration {
        background: var(--visi-gradient);
        clip-path: polygon(50% 0%, 100% 0%, 100% 100%, 0% 100%);
    }

    .misi-decoration {
        background: var(--misi-gradient);
        clip-path: polygon(30% 0%, 100% 0%, 100% 100%, 0% 100%);
    }

    /* Info Cards */
    .info-card-modern {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-light);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .info-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }

    .card-icon-wrapper {
        margin-bottom: 1.5rem;
    }

    .card-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .budaya-bg {
        background: var(--budaya-gradient);
    }

    .fasilitas-bg {
        background: var(--fasilitas-gradient);
    }

    .metode-bg {
        background: var(--metode-gradient);
    }

    .ekskul-bg {
        background: var(--ekskul-gradient);
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
    }

    .card-text {
        font-size: 0.95rem;
        line-height: 1.7;
        color: var(--text-light);
    }

    .card-text h1,
    .card-text h2,
    .card-text h3,
    .card-text h4 {
        color: var(--text-dark);
        margin-top: 1.2rem;
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .card-text p {
        margin-bottom: 1rem;
    }

    .card-text ul,
    .card-text ol {
        padding-left: 1.2rem;
        margin-bottom: 1rem;
    }

    .card-text li {
        margin-bottom: 0.4rem;
    }

    .card-hover-effect {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .info-card-modern:hover .card-hover-effect {
        transform: scaleX(1);
    }

    /* School Image Section */
    .school-image-section {
        margin-top: 3rem;
    }

    .image-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: var(--shadow-medium);
    }

    .school-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .image-container:hover .image-overlay {
        opacity: 1;
    }

    .image-container:hover .school-image {
        transform: scale(1.05);
    }

    .overlay-content {
        text-align: center;
        color: white;
    }

    .overlay-content h4 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .overlay-content p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Empty State */
    .empty-state-modern {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-light);
        margin-bottom: 2rem;
    }

    .empty-icon {
        margin-bottom: 1.5rem;
        color: var(--text-muted);
    }

    .empty-state-modern h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .empty-state-modern p {
        color: var(--text-light);
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [data-aos="fade-up"] {
        animation: fadeInUp 0.8s ease forwards;
    }

    [data-aos="fade-up"][data-aos-delay="100"] {
        animation-delay: 0.1s;
    }

    [data-aos="fade-up"][data-aos-delay="200"] {
        animation-delay: 0.2s;
    }

    [data-aos="fade-up"][data-aos-delay="300"] {
        animation-delay: 0.3s;
    }

    [data-aos="fade-up"][data-aos-delay="400"] {
        animation-delay: 0.4s;
    }

    [data-aos="fade-up"][data-aos-delay="500"] {
        animation-delay: 0.5s;
    }

    [data-aos="fade-up"][data-aos-delay="600"] {
        animation-delay: 0.6s;
    }

    [data-aos="fade-up"][data-aos-delay="700"] {
        animation-delay: 0.7s;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .visi-misi-area {
            padding: 60px 0;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .card-header-modern {
            flex-direction: column;
            text-align: center;
            padding: 2rem 1.5rem 1rem;
            gap: 1rem;
        }

        .header-text h3 {
            font-size: 1.6rem;
        }

        .card-body-modern {
            padding: 1rem 1.5rem 2rem;
        }

        .info-card-modern {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .icon-container {
            width: 70px;
            height: 70px;
            font-size: 1.6rem;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .school-image {
            height: 250px;
        }
    }

    @media (max-width: 576px) {
        .section-title {
            font-size: 1.8rem;
        }

        .main-content-card {
            margin-bottom: 2rem;
        }

        .content-text {
            font-size: 1rem;
        }

        .card-title {
            font-size: 1.2rem;
        }

        .card-text {
            font-size: 0.9rem;
        }
    }

    /* Print Styles */
    @media print {
        .visi-misi-area {
            background: white !important;
            padding: 0 !important;
        }

        .main-content-card,
        .info-card-modern {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid;
        }

        .card-decoration,
        .card-hover-effect,
        .image-overlay {
            display: none !important;
        }
    }
</style>
@endpush