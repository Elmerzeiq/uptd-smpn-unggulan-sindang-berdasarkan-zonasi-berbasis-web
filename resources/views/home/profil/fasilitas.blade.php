@extends('home.layouts.app')

@section('home')
<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area fasilitas">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Fasilitas Sekolah</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Fasilitas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="modern-fasilitas-area">
    <div class="px-4 container-fluid">
        <!-- Hero Section -->
        <div class="mb-5 text-center hero-section">
            <div class="hero-content">
                <div class="animated-title">
                    <h1 class="main-title">Fasilitas {{ $profil->nama_sekolah ?? 'Cerulean School' }}
                    </h1>
                    <div class="title-decoration">
                        <div class="decoration-line"></div>
                        <div class="decoration-diamond"></div>
                        <div class="decoration-line"></div>
                    </div>
                </div>
                <p class="hero-subtitle">Sarana dan prasarana pendukung pembelajaran yang lengkap dan berteknologi
                    modern</p>
                <div class="floating-elements">
                    <div class="floating-circle circle-1"></div>
                    <div class="floating-circle circle-2"></div>
                    <div class="floating-circle circle-3"></div>
                </div>
            </div>
        </div>

        <!-- Facilities Grid -->
        <div class="facilities-grid">
            <!-- Academic Facilities -->
            <div class="category-section" data-category="academic">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h2>Fasilitas Akademik</h2>
                </div>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Ruang Kelas Nyaman</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-chalkboard-teacher"></i>
                                        <h4>Ruang Kelas Nyaman</h4>
                                        <p>Ruang kelas ber-AC dengan smart board, proyektor, dan fasilitas pembelajaran
                                            modern</p>
                                        <div class="features">
                                            <span class="feature-tag">AC</span>
                                            <span class="feature-tag">Smart Board</span>
                                            <span class="feature-tag">Proyektor</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-flask"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Lab IPA</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-flask"></i>
                                        <h4>Laboratorium IPA</h4>
                                        <p>Lab Fisika, Kimia, dan Biologi dengan peralatan praktikum lengkap dan modern
                                        </p>
                                        <div class="features">
                                            <span class="feature-tag">Fisika</span>
                                            <span class="feature-tag">Kimia</span>
                                            <span class="feature-tag">Biologi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-desktop"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Lab Komputer</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-desktop"></i>
                                        <h4>Lab Komputer</h4>
                                        <p>40 unit komputer terbaru dengan internet berkecepatan tinggi dan software
                                            terkini</p>
                                        <div class="features">
                                            <span class="feature-tag">40 Unit PC</span>
                                            <span class="feature-tag">High Speed Internet</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-calculator"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Lab Matematika</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-calculator"></i>
                                        <h4>Lab Matematika</h4>
                                        <p>Ruang pembelajaran matematika interaktif dengan alat peraga dan media digital
                                        </p>
                                        <div class="features">
                                            <span class="feature-tag">Interaktif</span>
                                            <span class="feature-tag">Alat Peraga</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-language"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Lab Bahasa</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-language"></i>
                                        <h4>Lab Bahasa</h4>
                                        <p>Studio bahasa dengan sound system canggih untuk pembelajaran bahasa asing</p>
                                        <div class="features">
                                            <span class="feature-tag">Audio System</span>
                                            <span class="feature-tag">Multilingual</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-bolt"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Lab Elektro</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-bolt"></i>
                                        <h4>Lab Elektro</h4>
                                        <p>Workshop teknik elektro dan elektronika dengan peralatan industri standar</p>
                                        <div class="features">
                                            <span class="feature-tag">Industrial Standard</span>
                                            <span class="feature-tag">Workshop</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card academic">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-book-open"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Perpustakaan</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-book-open"></i>
                                        <h4>Perpustakaan Digital</h4>
                                        <p>10,000+ koleksi buku fisik dan digital dengan ruang baca yang nyaman</p>
                                        <div class="features">
                                            <span class="feature-tag">10K+ Books</span>
                                            <span class="feature-tag">Digital Library</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sports & Recreation -->
            <div class="category-section" data-category="sports">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2>Fasilitas Olahraga & Rekreasi</h2>
                </div>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card sports">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-dumbbell"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>GOR Unggulan</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-dumbbell"></i>
                                        <h4>GOR Unggulan (Aula)</h4>
                                        <p>Gedung olahraga indoor dengan kapasitas 500 orang plus aula serbaguna</p>
                                        <div class="features">
                                            <span class="feature-tag">Indoor</span>
                                            <span class="feature-tag">500 Kapasitas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card sports">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-running"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Lapangan Olahraga</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-running"></i>
                                        <h4>Multi-Sport Courts</h4>
                                        <p>Sepak bola, volley, basket, futsal, bulu tangkis, dan tenis meja</p>
                                        <div class="features">
                                            <span class="feature-tag">Football</span>
                                            <span class="feature-tag">Basketball</span>
                                            <span class="feature-tag">Badminton</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Creative Arts -->
            <div class="category-section" data-category="arts">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h2>Fasilitas Seni & Kreativitas</h2>
                </div>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card arts">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-music"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Studio Musik</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-music"></i>
                                        <h4>Studio Musik</h4>
                                        <p>Studio recording dengan peralatan profesional dan soundproof room</p>
                                        <div class="features">
                                            <span class="feature-tag">Recording Studio</span>
                                            <span class="feature-tag">Soundproof</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card arts">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-drum"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Studio Angklung</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-drum"></i>
                                        <h4>Studio Angklung</h4>
                                        <p>Ruang khusus pelestarian budaya dengan koleksi angklung lengkap</p>
                                        <div class="features">
                                            <span class="feature-tag">Traditional</span>
                                            <span class="feature-tag">Cultural Heritage</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card arts">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-cut"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Workshop Tata Busana</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-cut"></i>
                                        <h4>Workshop Tata Busana</h4>
                                        <p>Studio desain fashion dengan mesin jahit industri dan ruang kreativitas</p>
                                        <div class="features">
                                            <span class="feature-tag">Fashion Design</span>
                                            <span class="feature-tag">Industrial Machines</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Living & Wellness -->
            <div class="category-section" data-category="wellness">
                <div class="category-header">
                    <div class="category-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h2>Fasilitas Kesehatan & Kehidupan</h2>
                </div>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card wellness">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-mosque"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Masjid</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-mosque"></i>
                                        <h4>Masjid Al-Barokah</h4>
                                        <p>Masjid berkapasitas 300 jamaah dengan fasilitas wudhu dan sound system</p>
                                        <div class="features">
                                            <span class="feature-tag">300 Jamaah</span>
                                            <span class="feature-tag">Sound System</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card wellness">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-bed"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Asrama</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-bed"></i>
                                        <h4>Asrama Putra & Putri</h4>
                                        <p>Asrama nyaman dengan pengawasan 24 jam, wifi gratis, dan fasilitas lengkap
                                        </p>
                                        <div class="features">
                                            <span class="feature-tag">24/7 Security</span>
                                            <span class="feature-tag">Free WiFi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card wellness">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-medkit"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>UKS</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-medkit"></i>
                                        <h4>Unit Kesehatan Sekolah</h4>
                                        <p>Klinik mini dengan dokter dan perawat, plus fasilitas emergency</p>
                                        <div class="features">
                                            <span class="feature-tag">Medical Staff</span>
                                            <span class="feature-tag">Emergency Care</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card wellness">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-utensils"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Kantin Sehat</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-utensils"></i>
                                        <h4>Kantin Sehat & Luas</h4>
                                        <p>Food court dengan 10+ tenant, menu bergizi seimbang dan halal</p>
                                        <div class="features">
                                            <span class="feature-tag">10+ Tenants</span>
                                            <span class="feature-tag">Halal & Healthy</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card wellness">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-store"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Koperasi Siswa</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-store"></i>
                                        <h4>Koperasi Siswa Unggul</h4>
                                        <p>Mini market dengan kebutuhan sekolah, alat tulis, dan produk siswa</p>
                                        <div class="features">
                                            <span class="feature-tag">Student Products</span>
                                            <span class="feature-tag">School Supplies</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="facility-card wellness">
                            <div class="card-inner">
                                <div class="card-front">
                                    <div class="icon-container">
                                        <i class="fas fa-seedling"></i>
                                        <div class="icon-bg"></div>
                                    </div>
                                    <h4>Eco Learning</h4>
                                    <div class="card-accent"></div>
                                </div>
                                <div class="card-back">
                                    <div class="back-content">
                                        <i class="mb-3 fas fa-seedling"></i>
                                        <h4>Kebun & Empang</h4>
                                        <p>Area eco-learning untuk pertanian organik dan budidaya ikan</p>
                                        <div class="features">
                                            <span class="feature-tag">Organic Farm</span>
                                            <span class="feature-tag">Fish Pond</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="container">
                <div class="text-center row">
                    <div class="col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-target="18">0</div>
                            <div class="stat-label">Total Fasilitas</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-target="7">0</div>
                            <div class="stat-label">Laboratorium</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-item">
                            <div class="stat-number" data-target="5">0</div>
                            <div class="stat-label">Lapangan Olahraga</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Variables */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --dark-bg: #1a1a2e;
        --card-bg: rgba(255, 255, 255, 0.95);
        --shadow-light: 0 10px 40px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 20px 60px rgba(0, 0, 0, 0.15);
        --shadow-heavy: 0 30px 80px rgba(0, 0, 0, 0.2);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .modern-fasilitas-area {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 2rem 0 4rem;
        position: relative;
        overflow-x: hidden;
    }

    .modern-fasilitas-area::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        pointer-events: none;
    }

    /* Hero Section */
    .hero-section {
        padding: 4rem 0;
        position: relative;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .animated-title .main-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 2rem;
        line-height: 1.2;
        animation: titleSlideUp 1s ease-out;
    }

    @keyframes titleSlideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .title-decoration {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2rem 0;
        animation: decorationFade 1.5s ease-out;
    }

    @keyframes decorationFade {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .decoration-line {
        width: 60px;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    .decoration-diamond {
        width: 20px;
        height: 20px;
        background: var(--secondary-gradient);
        transform: rotate(45deg);
        margin: 0 1rem;
        border-radius: 4px;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto 3rem;
        line-height: 1.6;
        animation: subtitleSlideUp 1s ease-out 0.3s both;
    }

    @keyframes subtitleSlideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }

    .floating-circle {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        animation: float 6s ease-in-out infinite;
    }

    .circle-1 {
        width: 100px;
        height: 100px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .circle-2 {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 15%;
        animation-delay: 2s;
    }

    .circle-3 {
        width: 80px;
        height: 80px;
        bottom: 20%;
        left: 70%;
        animation-delay: 4s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }

    /* Category Sections */
    .category-section {
        margin-bottom: 5rem;
        animation: sectionFadeIn 1s ease-out;
    }

    @keyframes sectionFadeIn {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .category-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 3rem;
        position: relative;
    }

    .category-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .category-section[data-category="academic"] .category-icon {
        background: var(--primary-gradient);
    }

    .category-section[data-category="sports"] .category-icon {
        background: var(--secondary-gradient);
    }

    .category-section[data-category="arts"] .category-icon {
        background: var(--success-gradient);
    }

    .category-section[data-category="wellness"] .category-icon {
        background: var(--warning-gradient);
    }

    .category-icon i {
        font-size: 2rem;
        color: white;
        z-index: 2;
    }

    .category-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {

        0%,
        100% {
            transform: rotate(0deg);
            opacity: 0;
        }

        50% {
            transform: rotate(180deg);
            opacity: 1;
        }
    }

    .category-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        position: relative;
    }

    .category-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    /* Facility Cards */
    .facility-card {
        perspective: 1000px;
        height: 280px;
        margin-bottom: 2rem;
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .facility-card:hover .card-inner {
        transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        box-shadow: var(--shadow-light);
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-back {
        transform: rotateY(180deg);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
    }

    .icon-container {
        position: relative;
        width: 80px;
        height: 80px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        opacity: 0.1;
        z-index: 1;
    }

    .facility-card.academic .icon-bg {
        background: var(--primary-gradient);
    }

    .facility-card.sports .icon-bg {
        background: var(--secondary-gradient);
    }

    .facility-card.arts .icon-bg {
        background: var(--success-gradient);
    }

    .facility-card.wellness .icon-bg {
        background: var(--warning-gradient);
    }

    .icon-container i {
        font-size: 2.5rem;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .facility-card.academic .icon-container i {
        color: #667eea;
    }

    .facility-card.sports .icon-container i {
        color: #f5576c;
    }

    .facility-card.arts .icon-container i {
        color: #00f2fe;
    }

    .facility-card.wellness .icon-container i {
        color: #38f9d7;
    }

    .card-front h4,
    .card-back h4 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .card-accent {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 5px;
        border-radius: 0 0 20px 20px;
    }

    .facility-card.academic .card-accent {
        background: var(--primary-gradient);
    }

    .facility-card.sports .card-accent {
        background: var(--secondary-gradient);
    }

    .facility-card.arts .card-accent {
        background: var(--success-gradient);
    }

    .facility-card.wellness .card-accent {
        background: var(--warning-gradient);
    }

    .back-content {
        text-align: center;
    }

    .back-content i {
        font-size: 3rem;
        opacity: 0.2;
    }

    .back-content p {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .features {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
    }

    .feature-tag {
        background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        color: #667eea;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    /* Statistics Section */
    .stats-section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
        backdrop-filter: blur(20px);
        border-radius: 30px;
        padding: 4rem 2rem;
        margin: 4rem auto;
        max-width: 1200px;
        box-shadow: var(--shadow-medium);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .stat-item {
        padding: 2rem 1rem;
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 900;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 1.1rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .main-title {
            font-size: 3rem;
        }

        .category-header h2 {
            font-size: 2rem;
        }
    }

    @media (max-width: 768px) {
        .modern-fasilitas-area {
            padding: 1rem 0 2rem;
        }

        .hero-section {
            padding: 2rem 0;
        }

        .main-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .category-header {
            flex-direction: column;
            text-align: center;
        }

        .category-icon {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .category-header h2 {
            font-size: 1.8rem;
        }

        .facility-card {
            height: 250px;
        }

        .card-front,
        .card-back {
            padding: 1.5rem;
        }

        .icon-container {
            width: 60px;
            height: 60px;
        }

        .icon-container i {
            font-size: 2rem;
        }

        .card-front h4,
        .card-back h4 {
            font-size: 1.2rem;
        }

        .stats-section {
            padding: 2rem 1rem;
            margin: 2rem auto;
        }

        .stat-number {
            font-size: 2.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .decoration-line {
            width: 40px;
        }

        .decoration-diamond {
            width: 15px;
            height: 15px;
        }

        .floating-circle {
            display: none;
        }

        .facility-card {
            height: 220px;
        }

        .features {
            gap: 0.3rem;
        }

        .feature-tag {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
        }
    }

    /* Animation delays for staggered effect */
    .facility-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .facility-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .facility-card:nth-child(3) {
        animation-delay: 0.3s;
    }

    .facility-card:nth-child(4) {
        animation-delay: 0.4s;
    }

    .facility-card:nth-child(5) {
        animation-delay: 0.5s;
    }

    .facility-card:nth-child(6) {
        animation-delay: 0.6s;
    }

    .facility-card:nth-child(7) {
        animation-delay: 0.7s;
    }

    .facility-card:nth-child(8) {
        animation-delay: 0.8s;
    }

    /* Entrance animation for cards */
    .facility-card {
        animation: cardSlideUp 0.8s ease-out both;
    }

    @keyframes cardSlideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Animated counter for statistics
    const counters = document.querySelectorAll('.stat-number');
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    };

    // Intersection Observer for triggering animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('stat-number')) {
                    animateCounter(entry.target);
                }
                entry.target.classList.add('animate');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observe elements
    counters.forEach(counter => observer.observe(counter));

    // Add hover sound effect (optional)
    const cards = document.querySelectorAll('.facility-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'scale(1.02)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'scale(1)';
        });
    });
});
</script>

@endsection
