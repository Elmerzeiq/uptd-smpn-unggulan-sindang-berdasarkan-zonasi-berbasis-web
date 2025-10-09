@extends('home.layouts.app')

@section('home')

{{-- CSS untuk menambahkan lapisan hitam transparan pada slider dan typewriter effect --}}
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

    /* Typewriter Effect */
    .typewriter-text {
        border-right: 3px solid #fff;
        animation: blink-caret 0.75s step-end infinite;
        display: inline-block;
        color: #fff;
    }

    @keyframes blink-caret {

        from,
        to {
            border-color: transparent;
        }

        50% {
            border-color: #fff;
        }
    }

    /* Enhanced Hover Effects */
    .single-small-service {
        transition: all 0.3s ease;
    }

    .single-small-service:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .single-fun-factor {
        transition: all 0.3s ease;
    }

    .single-fun-factor:hover {
        transform: scale(1.05);
    }

    .single-items {
        transition: all 0.3s ease;
    }

    .single-items:hover {
        transform: scale(1.02);
    }

    /* Counter Animation */
    .counter {
        display: inline-block;
    }
</style>

<!--Slider Area Start-->
<div class="slider-area slider-four-area">
    <div class="preview-2">
        <div id="nivoslider" class="slides">
            <img src="{{ asset('techedu/img/slider/01.jpg') }}" alt="Pendidikan Unggul di SMPN Unggulan Sindang"
                title="#slider-1-caption1" />
            <img src="{{ asset('techedu/img/slider/02.jpg') }}" alt="Membentuk Karakter dan Prestasi Siswa"
                title="#slider-1-caption2" />
        </div>
        <div id="slider-1-caption1" class="nivo-html-caption nivo-caption">
            <div class="banner-content slider-1">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-content hidden-xs">
                                <p class="sub-title" data-aos="fade-up" data-aos-delay="100">Cerulean School</p>
                                <h1 class="title1" data-aos="fade-up" data-aos-delay="300">
                                    <span class="typewriter-text" id="typewriter-1"></span>
                                </h1>
                                <div class="banner-readmore" data-aos="fade-up" data-aos-delay="500">
                                    <a title="Lihat Profil Sekolah" href="{{ route('profil.visi_misi') }}">Profil
                                        Sekolah</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="slider-1-caption2" class="nivo-html-caption nivo-caption">
            <div class="banner-content slider-2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-content hidden-xs">
                                <p class="sub-title" data-aos="fade-up" data-aos-delay="100">Sistem Penerimaan Murid
                                    Baru (SPMB)</p>
                                <h1 class="title1" data-aos="fade-up" data-aos-delay="300">
                                    <span class="typewriter-text" id="typewriter-2"></span>
                                </h1>
                                <div class="banner-readmore" data-aos="fade-up" data-aos-delay="500">
                                    <a title="Daftar Sekarang" href="{{ url('/spmb') }}">Info Pendaftaran</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Slider Area-->
<br>

<!--Fasilitas Area Start-->
<div class="small-service-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title-wrapper">
                    <div data-aos="fade-up" data-aos-delay="100" class="section-title">
                        <h3>Fasilitas Unggulan</h3>
                        <p data-aos="fade-up" data-aos-delay="200">Kami menyediakan sarana dan prasarana terbaik untuk
                            mendukung proses belajar.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div data-aos="fade-right" data-aos-delay="150" class="single-small-service">
                    <div class="small-service-icon"> <i class="fa fa-university"></i> </div>
                    <div class="small-services-text">
                        <h4>Ruang Kelas Nyaman</h4>
                        <p>Dirancang agar kondusif untuk kegiatan belajar mengajar yang efektif dan menyenangkan.</p>
                    </div>
                </div>
                <div data-aos="fade-right" data-aos-delay="350" class="single-small-service">
                    <div class="small-service-icon"> <i class="fa fa-flask"></i> </div>
                    <div class="small-services-text">
                        <h4>Laboratorium Lengkap</h4>
                        <p>Mencakup laboratorium Komputer, IPA, dan Bahasa untuk menunjang praktik dan penelitian.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div data-aos="fade-up" data-aos-delay="250" class="single-small-service">
                    <div class="small-service-icon"> <i class="fa fa-book"></i> </div>
                    <div class="small-services-text">
                        <h4>Perpustakaan & Taman Bacaan</h4>
                        <p>Koleksi buku yang kaya dan ruang baca yang tenang untuk meningkatkan literasi siswa.</p>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="450" class="single-small-service">
                    <div class="small-service-icon"> <i class="fa fa-futbol-o"></i> </div>
                    <div class="small-services-text">
                        <h4>Sarana Olah Raga</h4>
                        <p>GOR, lapangan sepak bola, voli, dan basket untuk menyalurkan minat dan bakat olahraga.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div data-aos="fade-left" data-aos-delay="350" class="single-small-service">
                    <div class="small-service-icon"> <i class="fa fa-moon-o"></i> </div>
                    <div class="small-services-text">
                        <h4>Masjid yang Memadai</h4>
                        <p>Sebagai pusat pembinaan karakter, kegiatan keagamaan, dan ibadah bagi warga sekolah.</p>
                    </div>
                </div>
                <div data-aos="fade-left" data-aos-delay="550" class="single-small-service">
                    <div class="small-service-icon"> <i class="fa fa-bed"></i> </div>
                    <div class="small-services-text">
                        <h4>Asrama Putra & Putri</h4>
                        <p>Menyediakan lingkungan yang aman dan nyaman untuk mendukung siswa dari luar daerah.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div data-aos="zoom-in" data-aos-delay="650" class="mt-4 text-center col-12">
                <a href="{{ route('fasilitas') }}" class="button-default">Lihat Semua Fasilitas</a>
            </div>
        </div>
    </div>
</div>
<!--End of Fasilitas Area-->
<br>
<br>

<!--About Area Start-->
<div class="about-area section-padding-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title-wrapper">
                    <div data-aos="fade-up" data-aos-delay="100" class="section-title">
                        <h3>Tentang Kami</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div data-aos="fade-right" data-aos-delay="200" class="about-text-container">
                    <h3><b>Visi</b></h3>
                    <strong><b>{!! $profil ? $profil->visi : '-' !!}</b></strong>
                    <h3><b>Misi</b></h3>
                    <strong><b>{!! $profil ? $profil->misi : '-' !!}</b></strong>
                </div>
            </div>
            <div class="col-lg-6">
                <div data-aos="fade-left" data-aos-delay="400" class="skill-image">
                    @if($profil && $profil->image)
                    <img src="{{ asset('uploads/images/' . $profil->image) }}" alt="Gambar Sekolah" class="img-fluid" />
                    @elseif($profil && $profil->logo_sekolah)
                    <img src="{{ Storage::url($profil->logo_sekolah) }}" alt="Logo Sekolah" class="img-fluid" />
                    @else
                    <img src="{{ asset('images/default-school.jpg') }}" alt="Default Image" class="img-fluid" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of About Area-->
<br>
<br>
<br>
<br>
<br>

<!--Fun Factor Area Start-->
<div class="fun-factor-area fun-factor-three">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                <div data-aos="zoom-in" data-aos-delay="150" class="single-fun-factor">
                    <div class="fun-factor-icon"> <i class="fa fa-users"></i> </div>
                    <h2><span class="counter" >{!! $profil ? $profil->jml_guru : '-' !!}</span></h2>
                    <span>Guru & Staf</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                <div data-aos="zoom-in" data-aos-delay="300" class="single-fun-factor">
                    <div class="fun-factor-icon">
                        <i class="fa fa-trophy"></i>
                    </div>
                    <h2>
                        <span class="counter" data-target="50">50</span>+
                    </h2>
                    <span>Prestasi</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-6">
                <div data-aos="zoom-in" data-aos-delay="450" class="single-fun-factor">
                    <div class="fun-factor-icon"> <i class="fa fa-graduation-cap"></i> </div>
                    <h2><span class="counter" >{!! $profil ? $profil->jml_siswa : '-' !!}</span></h2>
                    <span>Siswa/i</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Fun Factor Area-->
<br>
<br>
<br>
<br>

<!-- Gallery Section Update -->
<div class="gallery-area gallery-fullwidth section-gray section-padding">
    <div class="section-title-wrapper">
        <div data-aos="fade-up" data-aos-delay="100" class="section-title">
            <h3>Galeri Sekolah</h3>
            <p data-aos="fade-up" data-aos-delay="200">Momen dan kegiatan di lingkungan UPTD SMP Negeri Unggulan
                Sindang.</p>
        </div>
    </div>
    <div class="gallery-wrapper">
     <div class="row no-gutters">
        @foreach($gallery->take(4) as $index => $galeri)
        <div data-aos="flip-left" data-aos-delay="{{ 300 + ($index * 100) }}"
            class="single-items col-lg-3 col-md-3 col-sm-6 col-12 overlay-hover">
            <div class="overlay-effect sea-green-overlay">
                <a href="#">
                    @if($galeri->image)
                    <img src="{{ Storage::url($galeri->image) }}" alt="{{ $galeri->kategori }}" class="gallery-img" />
                    @else
                    <img src="{{ asset('images/default-gallery.jpg') }}" alt="{{ $galeri->kategori }}"
                        class="gallery-img" />
                    @endif
                </a>
                <div class="gallery-hover-effect">
                    @if($galeri->image)
                    <a class="gallery-icon venobox" href="{{ Storage::url($galeri->image) }}">
                        <i class="fa fa-search-plus"></i>
                    </a>
                    @endif
                    <span class="gallery-text">{{ ucfirst($galeri->kategori) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    </div>
    <div data-aos="zoom-in" data-aos-delay="800" class="text-center view-gallery">
        <h4>Lihat Seluruh <span>Dokumentasi Kami</span></h4>
        <a href="{{ route('galeri') }}" class="button-default">Buka Galeri</a>
    </div>
</div>
<br>
<br>
<br>
<br>

<!--Register Area Start (Info PPDB)-->
<div class="register-area register-style-two">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center register-info">
                    <h4 data-aos="fade-up" data-aos-delay="100">SISTEM PENERIMAAN MURID BARU <span>(SPMB)</span></h4>
                    <h1 data-aos="zoom-in" data-aos-delay="300">SEGERA DIBUKA</h1>
                    <div data-aos="fade-up" data-aos-delay="500" class="timer">
                        <div data-countdown="2025/08/28" class="timer-grid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Register Area-->
<br>
<br>
<br>
<br>

<!-- Blog/Berita Section Update -->
<div class="blog-area section-padding-bottom blog-style-three">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title-wrapper">
                    <div data-aos="fade-up" data-aos-delay="100" class="section-title">
                        <h3>Berita</h3>
                        <p data-aos="fade-up" data-aos-delay="200">Berita &amp; acara terbaru dari sekolah kami</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="blog-carousel carousel-style-one owl-carousel">
            @foreach ($beritas as $index => $item)
            <div data-aos="fade-up" data-aos-delay="{{ 300 + ($index * 150) }}"
                class="text-center border single-blog-item overlay-hover">
                <div class="single-blog-image">
                    <div class="overlay-effect">
                        <a href="{{ route('berita.show', $item->id) }}">
                            @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}"
                                style="width: 100%; height: 380px; object-fit: cover;" />
                            @else
                            <img src="{{ asset('images/default-news.jpg') }}" alt="{{ $item->judul }}"
                                style="width: 100%; height: 380px; object-fit: cover;" />
                            @endif
                            <span class="date">{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</span>
                        </a>
                    </div>
                </div>
                <div class="single-blog-text">
                    <h4>
                        <a href="{{ route('berita.show', $item->id) }}">{{ $item->judul }}</a>
                    </h4>
                    <p>{!! Str::limit($item->deskripsi, 200) !!}</p>
                    <a href="{{ route('berita.show', $item->id) }}">Read more.</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>

<!--Google Map Area Start -->
<div class="google-map-area">
    <div data-aos="fade-up" data-aos-delay="200" id="contacts" class="map-area">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3115872601697!2d108.3084999!3d-6.3536945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb915fa5c4fc3%3A0xb04c08d0c5267487!2sSMPN%20Unggulan%20Sindang!5e0!3m2!1sen!2sid!4v1754004739114!5m2!1sen!2sid"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>
<!--End of Google Map Area-->

{{-- JavaScript untuk AOS dan Typewriter Effect --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS dengan konfigurasi optimal
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 80,
            delay: 0,
            mirror: false,
            anchorPlacement: 'top-bottom',
            disable: function() {
                // Disable AOS pada perangkat dengan performance rendah
                return window.innerWidth < 768 && 'ontouchstart' in window;
            }
        });

        // Typewriter Effect - Fixed untuk slider transitions
        class TypeWriter {
            constructor(elementId, texts) {
                this.elementId = elementId;
                this.element = null;
                this.texts = texts;
                this.textIndex = 0;
                this.charIndex = 0;
                this.isDeleting = false;
                this.isRunning = false;
                this.timeoutId = null;
            }

            init() {
                this.element = document.getElementById(this.elementId);
                return this.element !== null;
            }

            start(delay = 0) {
                if (this.isRunning) return;

                setTimeout(() => {
                    if (this.init()) {
                        this.isRunning = true;
                        this.type();
                    }
                }, delay);
            }

            type() {
                if (!this.init() || !this.isRunning) return;

                const currentText = this.texts[this.textIndex];

                if (this.isDeleting) {
                    this.element.textContent = currentText.substring(0, this.charIndex - 1);
                    this.charIndex--;
                } else {
                    this.element.textContent = currentText.substring(0, this.charIndex + 1);
                    this.charIndex++;
                }

                let typeSpeed = this.isDeleting ? 150 : 200; // Lebih lambat lagi untuk readability

                if (!this.isDeleting && this.charIndex === currentText.length) {
                    typeSpeed = 4000; // Pause 4 detik untuk baca - lebih lama
                    this.isDeleting = true;
                } else if (this.isDeleting && this.charIndex === 0) {
                    this.isDeleting = false;
                    this.textIndex = (this.textIndex + 1) % this.texts.length;
                    typeSpeed = 1500; // Pause 1.5 detik sebelum teks baru
                }

                this.timeoutId = setTimeout(() => this.type(), typeSpeed);
            }

            stop() {
                this.isRunning = false;
                if (this.timeoutId) {
                    clearTimeout(this.timeoutId);
                    this.timeoutId = null;
                }
            }

            restart() {
                this.stop();
                setTimeout(() => this.start(800), 1000); // Delay restart lebih lama untuk readability
            }
        }

        // Initialize TypeWriter instances dengan error handling
        let typewriter1, typewriter2;

        try {
            typewriter1 = new TypeWriter('typewriter-1', [
                'Pendidikan Unggul',
                'Karakter Mulia',
                'Prestasi Gemilang',
                'Masa Depan Cerah'
            ]);

            typewriter2 = new TypeWriter('typewriter-2', [
                'Bergabunglah Bersama Kami',
                'Menjadi Siswa Unggulan',
                'Raih Prestasi Terbaik',
                'Wujudkan Cita-Cita'
            ]);

            // Verify instances created successfully
            console.log('TypeWriter instances created:', {
                typewriter1: !!typewriter1,
                typewriter2: !!typewriter2
            });

        } catch (error) {
            console.error('Error creating TypeWriter instances:', error);
        }

        // Start both typewriters dengan delay yang lebih lambat dan readable
        function startTypewriters() {
            try {
                if (typewriter1 && typeof typewriter1.start === 'function') {
                    typewriter1.start(1200); // Delay start 1.2 detik
                    console.log('TypeWriter 1 started');
                }
                if (typewriter2 && typeof typewriter2.start === 'function') {
                    typewriter2.start(1800); // Delay start 1.8 detik
                    console.log('TypeWriter 2 started');
                }
            } catch (error) {
                console.error('Error starting typewriters:', error);
            }
        }

        // Restart typewriters when slider changes
        function restartTypewriters() {
            try {
                if (typewriter1 && typeof typewriter1.restart === 'function') {
                    typewriter1.restart();
                }
                if (typewriter2 && typeof typewriter2.restart === 'function') {
                    typewriter2.restart();
                }
                console.log('TypeWriters restarted');
            } catch (error) {
                console.error('Error restarting typewriters:', error);
            }
        }

        // Initialize typewriters setelah DOM ready dengan delay yang comfortable
        setTimeout(startTypewriters, 2000);

        // Slider change detection - Multiple methods untuk compatibility
        let sliderChangeTimeout;

        // Method 1: MutationObserver untuk detect slider changes
        if (typeof MutationObserver !== 'undefined') {
            const sliderObserver = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        clearTimeout(sliderChangeTimeout);
                        sliderChangeTimeout = setTimeout(restartTypewriters, 100);
                    }
                });
            });

            const sliderElement = document.getElementById('nivoslider');
            if (sliderElement) {
                sliderObserver.observe(sliderElement, {
                    attributes: true,
                    childList: true,
                    subtree: true
                });
            }
        }

        // Method 2: Event listeners untuk Nivo Slider
        $(document).ready(function() {
            // Nivo slider callback events
            $('#nivoslider').nivoSlider({
                afterChange: function() {
                    setTimeout(restartTypewriters, 800); // Delay 800ms untuk smooth transition
                },
                beforeChange: function() {
                    typewriter1.stop();
                    typewriter2.stop();
                }
            });
        });

        // Method 3: Interval check sebagai fallback
        let lastActiveSlide = '';
        setInterval(() => {
            const activeSlide = document.querySelector('.nivo-slice.nivo-slice-active, .nivo-box.nivo-box-active');
            const currentSlide = activeSlide ? activeSlide.style.backgroundImage : '';

            if (currentSlide !== lastActiveSlide && currentSlide !== '') {
                lastActiveSlide = currentSlide;
                setTimeout(restartTypewriters, 300);
            }
        }, 1000);

        // Method 4: Visibility change detection
        let visibilityTimeout;
        function handleVisibilityChange() {
            const typewriter1Element = document.getElementById('typewriter-1');
            const typewriter2Element = document.getElementById('typewriter-2');

            if (typewriter1Element && isElementVisible(typewriter1Element)) {
                if (!typewriter1.isRunning) {
                    clearTimeout(visibilityTimeout);
                    visibilityTimeout = setTimeout(() => typewriter1.start(500), 800);
                }
            }

            if (typewriter2Element && isElementVisible(typewriter2Element)) {
                if (!typewriter2.isRunning) {
                    clearTimeout(visibilityTimeout);
                    visibilityTimeout = setTimeout(() => typewriter2.start(700), 1000);
                }
            }
        }

        function isElementVisible(element) {
            const rect = element.getBoundingClientRect();
            return rect.top >= 0 && rect.left >= 0 &&
                   rect.bottom <= window.innerHeight &&
                   rect.right <= window.innerWidth;
        }

        // Check visibility periodically
        setInterval(handleVisibilityChange, 2000);

        // Counter Animation dengan easing yang lebih smooth
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-count')) || 0;
            const duration = 2000; // 2 detik
            const increment = target / (duration / 16); // 60fps
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                element.textContent = Math.floor(current);

                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                }
            }, 16);
        }

        // Intersection Observer untuk counters dengan threshold yang lebih baik
        const counterElements = document.querySelectorAll('.counter');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersectionRatio > 0.3) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: [0.3],
            rootMargin: '0px 0px -50px 0px'
        });

        counterElements.forEach(counter => {
            counterObserver.observe(counter);
        });

        // Smooth scroll dan performance optimization
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Throttled resize handler untuk performance
        let resizeTimer;
        const handleResize = () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            }, 150);
        };

        window.addEventListener('resize', handleResize, { passive: true });

        // Cleanup pada page unload dengan null checks
        window.addEventListener('beforeunload', () => {
            try {
                if (typewriter1 && typeof typewriter1.stop === 'function') {
                    typewriter1.stop();
                }
                if (typewriter2 && typeof typewriter2.stop === 'function') {
                    typewriter2.stop();
                }
            } catch (error) {
                console.error('Error during cleanup:', error);
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".counter");
  counters.forEach(counter => {
    const target = +counter.getAttribute("data-target");
    let count = 0;
    const speed = 20; // kecepatan

    const updateCount = () => {
      if (count < target) {
        count++;
        counter.innerText = count;
        setTimeout(updateCount, speed);
      } else {
        counter.innerText = target;
      }
    };
    updateCount();
  });
});
</script>

<style>
    .gallery-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-img:hover {
        transform: scale(1.05);
    }
</style>
@endsection
