<!--Footer Area Start-->
<div class="footer-area">
    <div class="container">
        <div class="footer-widget-container section-padding">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="single-footer-widget">
                        <div class="footer-logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('techedu/img/logo/white-logo.png') }}" alt="Cerulean School" />
                            </a>
                        </div>
                        <p>Platform pendidikan terdepan yang berdedikasi untuk mencetak generasi unggul, berkarakter,
                            dan siap menghadapi tantangan global.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook"></i>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6">
                    <div class="single-footer-widget">
                        <h4>Profil Sekolah</h4>
                        <ul class="footer-widget-list">
                            <li><a href="{{ route('sejarah') }}">Sejarah</a></li>
                            <li><a href="{{ route('profil.visi_misi') }}">Visi & Misi</a></li>
                            <li><a href="{{ route('kurikulum') }}">Kurikulum</a></li>
                            <li><a href="{{ route('fasilitas') }}">Fasilitas</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6">
                    <div class="single-footer-widget">
                        <h4>Tautan Cepat</h4>
                        <ul class="footer-widget-list">
                            <li><a href="{{ route('prestasi') }}">Prestasi</a></li>
                            <li><a href="{{ route('galeri') }}">Galeri</a></li>
                            <li><a href="{{ route('berita') }}">Berita</a></li>
                            <li><a href="{{ route('kontak') }}">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="single-footer-widget">
                        <h4>Kontak Kami</h4>
                        <p><i class="fa fa-map-marker"></i> Jl. kenangan</p>
                        <p><i class="fa fa-envelope"></i> infoceruleanscool01@gmail.com</p>
                        <p><i class="fa fa-phone"></i> (123) 4567-8910</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-container">
                    <div class="row">
                        <div class="col-lg-6">
                            <span>© {{ date('Y') }} <a href="#">Cerulean School</a>. All rights
                                reserved</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Footer Area-->
