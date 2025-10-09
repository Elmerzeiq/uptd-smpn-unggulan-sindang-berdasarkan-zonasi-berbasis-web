<!--Header Area Start-->
<header class="header-four">
    <div class="header-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-7 col-md-8">
                    <div class="header-top-info">
                        <span><i class="fa fa-map"></i>Jl. kenangan 05</span>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook"></i>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-4">
                    <div class="header-login-register">
                        @php
                            $jadwal = App\Models\JadwalPpdb::aktif();
                        @endphp
                        @if($jadwal && $jadwal->isPendaftaranOpen())
                            <a href="{{ route('login') }}" class="btn btn-primary daftar-ppdb-btn">
                                Daftar SPMB
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--Logo Mainmenu Start-->
    <div class="header-logo-menu sticker">
        <div class="container">
            <div class="logo-menu-bg">
                <div class="row">
                    <div class="col-lg-3 col-md-12">
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('techedu/img/logo/white-logo.png') }}" alt="Cerulean School" />
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9 d-none d-lg-block">
                        <div class="mainmenu-area">
                            <div class="mainmenu">
                                <nav>
                                    <ul id="nav">
                                        <li>
                                            <a href="{{ route('home') }}">Beranda</a>
                                        </li>
                                        <li>
                                            <a >Profil <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ route('sejarah') }}">Sejarah</a></li>
                                                <li><a href="{{ route('profil.visi_misi') }}">Visi & Misi</a></li>
                                                <li><a href="{{ route('sambutan') }}">Sambutan Kepala Sekolah</a></li>
                                                <li>
                                                    <a href="{{ route('guru_staff') }}">Guru & Staff</a>
                                                </li>
                                                <li><a href="{{ route('kurikulum') }}">Kurikulum</a></li>
                                                <li><a href="{{ route('fasilitas') }}">Fasilitas</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('spmb') }}">SPMB</a></li>
                                        <li><a href="{{ route('prestasi') }}">Prestasi</a></li>
                                        <li><a href="{{ route('ekskul.index') }}">Ekstrakurikuler</a></li>
                                        <li><a href="{{ route('berita') }}">Berita</a></li>
                                        <li><a href="{{ route('galeri') }}">Galeri</a></li>
                                        <li><a href="{{ route('kontak') }}">Kontak</a></li>

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Logo Mainmenu-->

    <!-- Mobile Menu Area start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul>
                               <li>
                                <a href="{{ route('home') }}">Beranda</a>
                            </li>
                            <li>
                                <a>Profil <i class="fa fa-angle-down"></i></a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('sejarah') }}">Sejarah</a></li>
                                    <li><a href="{{ route('profil.visi_misi') }}">Visi & Misi</a></li>
                                    <li><a href="{{ route('sambutan') }}">Sambutan Kepala Sekolah</a></li>
                                    <li>
                                        <a href="{{ route('guru_staff') }}">Guru & Staff</a>
                                    </li>
                                    <li><a href="{{ route('kurikulum') }}">Kurikulum</a></li>
                                    <li><a href="{{ route('fasilitas') }}">Fasilitas</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('spmb') }}">SPMB</a></li>
                            <li><a href="{{ route('prestasi') }}">Prestasi</a></li>
                            <li><a href="{{ route('ekskul.index') }}">Ekstrakurikuler</a></li>
                            <li><a href="{{ route('berita') }}">Berita</a></li>
                            <li><a href="{{ route('galeri') }}">Galeri</a></li>
                            <li><a href="{{ route('kontak') }}">Kontak</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area end -->
</header>
