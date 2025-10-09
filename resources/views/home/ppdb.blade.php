@extends('home.layouts.app')

@section('home')
<style>
    /* 1. Jadikan wadah utama slider sebagai acuan posisi */
    #nivoslider {
        position: relative;
    }

    /* 2. Buat lapisan overlay di dalam wadah slider */
    #nivoslider::before {
        content: "";
        background-color: rgba(0, 0, 0, 0.4);
        /* Hitam dengan transparansi 40% */
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 7;
        /* PENTING: Menempatkan overlay di atas gambar transisi (z-index: 6) tapi di bawah teks (z-index: 8) */
        pointer-events: none;
        /* Memastikan overlay tidak bisa di-klik */
    }

    /* 3. Pastikan teks caption selalu berada di lapisan paling atas */
    .nivo-html-caption {
        z-index: 9 !important;
    }

    /* 4. Pastikan tombol navigasi (panah kiri/kanan) juga di atas overlay */
    .nivo-directionNav a {
        z-index: 9 !important;
    }
</style>
<!--Slider Area Start-->
<div class="slider-area slider-four-area">
    <div class="preview-2">
        <div id="nivoslider" class="slides">
            <img src="{{ asset('techedu/img/slider/3.png') }}" alt="Slider Image 1" title="#slider-1-caption1" />
            {{-- <img src="{{ asset('techedu/img/slider/2.png') }}" alt="Slider Image 2" title="#slider-1-caption2" /> --}}
            <img src="{{ asset('techedu/img/slider/1.png') }}" alt="Slider Image 3" title="#slider-1-caption3" />
        </div>

        <!-- Slide 1: Welcome -->
        <div id="slider-1-caption1" class="nivo-html-caption nivo-caption">
            <div class="banner-content slider-1">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-content hidden-xs">
                                <div id="home" class="section">
                                    <div class="section-title">
                                        <p class="sub-title">
                                            Aplikasi SISTEM PENERIMAAN MURID BARU Pelajaran 2025/2026
                                            <br> UPTD SMPN UNGGULAN SINDANG
                                        </p>
                                        <h1 class="title1">
                                            Selamat Datang di SPMB <br>UPTD SMPN UNGGULAN SINDANG
                                        </h1>
                                        <p class="sub-title">
                                            Pendaftaran siswa dan siswi Tahun 2025/2026 ini telah dibuka.
                                            <br>Silahkan Segera Daftarkan Anak Anda dan Lengkapi Formulir Pendaftaran
                                        </p>
                                        <div class="banner-readmore">
                                            <a title="Lihat Alur Pendaftaran" href="#alur-pendaftaran"
                                                class="btn btn-primary">
                                                Lihat Alur Pendaftaran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Requirements -->
        {{-- <div id="slider-1-caption2" class="nivo-html-caption nivo-caption">
            <div class="banner-content slider-2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-content hidden-xs">
                                <h1 class="title1">
                                    Syarat Pendaftaran Peserta Didik Baru Tahun Pelajaran 2025/2026
                                </h1>
                                <ul class="requirements-list">
                                    <li>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Fotokopi Akta Kelahiran
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Fotokopi Kartu Keluarga
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Fotokopi KTP orang tua/wali
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Fotokopi Kartu Identitas Anak
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Fotokopi Ijazah TK/PAUD (Bagi yang memiliki)
                                    </li>
                                    <li>
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Fotokopi Kartu Indonesia Sehat (KIS)/Kartu Indonesia Pintar (KIP)/Kartu Keluarga
                                        Sejahtera (KKS) (Bagi yang memiliki)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Slide 3: Registration Flow -->
        <div id="slider-1-caption3" class="nivo-html-caption nivo-caption">
            <div class="banner-content slider-3">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-content hidden-xs">
                                <h1 class="title1">
                                    Alur Pendaftaran Peserta Didik Baru Tahun Pelajaran 2025/2026
                                </h1>
                                <ol class="registration-flow">
                                    <li>
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        Daftar Akun
                                    </li>
                                    <li>
                                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                                        Login
                                    </li>
                                    <li>
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        Lengkapi Formulir
                                    </li>
                                    <li>
                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                        Upload Berkas
                                    </li>
                                    <li>
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        Download Kartu Pendaftaran
                                    </li>
                                    <li>
                                        <i class="fa fa-bell" aria-hidden="true"></i>
                                        Melihat Pengumuman
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Slider Area-->

<!-- Jadwal Pendaftaran Section -->
<section id="jadwal-pendaftaran" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center section-title">
                    <h2>Jadwal Pendaftaran</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($jadwal as $j)
            <div class="mb-4 col-md-6 col-lg-4">
                <div class="schedule-card">
                    <h3>{{ $j->tahap ?? 'Tahap tidak tersedia' }}</h3>
                    <h4>{{ $j->kegiatan ?? 'Kegiatan tidak tersedia' }}</h4>
                    <div class="schedule-date">
                        <p><strong>Mulai:</strong> {{ $j->tanggal_mulai ?
                            \Carbon\Carbon::parse($j->tanggal_mulai)->format('d F Y') : 'Belum ditentukan' }}</p>
                        <p><strong>Selesai:</strong> {{ $j->tanggal_selesai ?
                            \Carbon\Carbon::parse($j->tanggal_selesai)->format('d F Y') : 'Belum ditentukan' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center alert alert-info">
                    <p>Jadwal pendaftaran akan segera diumumkan.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Petunjuk Teknis Section -->
<section id="petunjuk-teknis" class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center section-title">
                    <h2>Surat Petunjuk Teknis SPMB</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mx-auto col-lg-8">
                @if($petunjuk)
                <div class="petunjuk-card">
                    <h3>{{ $petunjuk->judul }}</h3>
                    <div class="petunjuk-content">
                        {!! $petunjuk->isi !!}
                    </div>
                    @if($petunjuk->path_pdf)
                    <div class="mt-3 text-center">
                        <a href="{{ asset('storage/' . $petunjuk->path_pdf) }}" download="Surat Petunjuk Teknis SPMB.pdf"
                            class="btn btn-primary btn-lg">
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Download PDF
                        </a>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center alert alert-info">
                    <p>Petunjuk teknis akan segera tersedia.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Kategori Section -->
<section id="kategori" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center section-title">
                    <h2>Kategori Pendaftaran</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($kategoris as $kategori)
            <div class="mb-4 col-md-6 col-lg-4">
                <div class="category-card">
                    <h3>{{ $kategori->kategori ?? 'Kategori tidak tersedia' }}</h3>
                    <p class="category-description">{{ $kategori->deskripsi ?? 'Deskripsi tidak tersedia' }}</p>
                    @if($kategori->ketentuan)
                    <div class="category-requirements">
                        <strong>Ketentuan:</strong>
                        <p>{{ $kategori->ketentuan }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center alert alert-info">
                    <p>Informasi kategori akan segera diumumkan.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Dokumen Persyaratan Section -->
<section id="dokumen-persyaratan" class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center section-title">
                    <h2>Dokumen Persyaratan</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($dokumen as $doc)
            <div class="mb-4 col-md-6">
                <div class="document-card">
                    <h3>{{ $doc->kategori ?? 'Kategori tidak tersedia' }}</h3>
                    <p>{{ $doc->keterangan ?? 'Keterangan tidak tersedia' }}</p>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center alert alert-info">
                    <p>Informasi dokumen persyaratan akan segera diumumkan.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Alur Pendaftaran Section -->
<section id="alur-pendaftaran" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center section-title">
                    <h2>Alur Pendaftaran</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($alur as $index => $step)
            <div class="mb-4 col-md-6 col-lg-4">
                <div class="step-card">
                    <div class="step-number">{{ $index + 1 }}</div>
                    <h3>{{ $step->nama ?? 'Langkah tidak tersedia' }}</h3>
                    <p>{{ $step->keterangan ?? 'Keterangan tidak tersedia' }}</p>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center alert alert-info">
                    <p>Informasi alur pendaftaran akan segera diumumkan.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center section-title">
                    <h2>Informasi Kontak</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mx-auto col-lg-8">
                <div class="contact-info">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <div class="text-center contact-item">
                                <i class="mb-2 fa fa-envelope fa-2x" aria-hidden="true"></i>
                                <h4>Email</h4>
                                <span>
                                    @if($profil && $profil->email)
                                    <a href="mailto:{{ $profil->email }}">{{ $profil->email }}</a>
                                    @else
                                    <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="text-center contact-item">
                                <i class="mb-2 fa fa-phone fa-2x" aria-hidden="true"></i>
                                <h4>Kontak 1</h4>
                                <span>
                                    @if($profil && $profil->kontak1)
                                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $profil->kontak1) }}"
                                        target="_blank" rel="noopener noreferrer">
                                        {{ $profil->kontak1 }}
                                    </a>
                                    @else
                                    <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="text-center contact-item">
                                <i class="mb-2 fa fa-phone fa-2x" aria-hidden="true"></i>
                                <h4>Kontak 2</h4>
                                <span>
                                    @if($profil && $profil->kontak2)
                                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $profil->kontak2) }}"
                                        target="_blank" rel="noopener noreferrer">
                                        {{ $profil->kontak2 }}
                                    </a>
                                    @else
                                    <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Map Area Start -->
<div class="google-map-area">
    <div id="contacts" class="map-area">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3115872601697!2d108.3084999!3d-6.3536945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb915fa5c4fc3%3A0xb04c08d0c5267487!2sSMPN%20Unggulan%20Sindang!5e0!3m2!1sen!2sid!4v1754004739114!5m2!1sen!2sid"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" title="Lokasi SMPN Unggulan Sindang">
        </iframe>
    </div>
</div>
<!--End of Google Map Area-->

<style>
    /* Custom Styles for SPMB */
    .requirements-list {
        list-style: none;
        padding: 0;
        font-size: 1.2rem;
    }

    .requirements-list li {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .requirements-list li i {
        color: #28a745;
        margin-right: 10px;
    }

    .registration-flow {
        list-style: none;
        padding: 0;
        font-size: 1.2rem;
    }

    .registration-flow li {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        counter-increment: step-counter;
    }

    .registration-flow li i {
        color: #007bff;
        margin-right: 10px;
    }

    .section-padding {
        padding: 60px 0;
    }

    .schedule-card,
    .category-card,
    .document-card,
    .step-card,
    .petunjuk-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
        transition: transform 0.3s ease;
    }

    .schedule-card:hover,
    .category-card:hover,
    .document-card:hover,
    .step-card:hover {
        transform: translateY(-5px);
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-weight: bold;
        font-size: 18px;
    }

    .contact-item {
        padding: 20px;
    }

    .contact-item i {
        color: #007bff;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .section-title {
        margin-bottom: 50px;
    }

    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 600;
        color: #333;
    }

    @media (max-width: 768px) {
        .section-title h2 {
            font-size: 2rem;
        }

        .requirements-list,
        .registration-flow {
            font-size: 1rem;
        }
    }
</style>

@endsection

