@extends('home.layouts.app')
{{--
<link rel="shortcut icon" type="image/x-icon" href="img/favicon.png" /> --}}
@section('home')



<div class="breadcrumb-banner-area teachers">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Kontak</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Kontak</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!--End of Google Map Area-->
<!-- Contact Area Start -->
<div class="contact-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-area-container">
                    <div class="single-title">
                        <h3>Kontak Info</h3>
                    </div>
                    <p>
                        Untuk informasi lebih lanjut mengenai PPD INFO atau jika Anda memiliki pertanyaan tentang
                        sekolah kami, silakan hubungi kontak yang tertera di bawah ini.
                    <div class="contact-address-container">
                        <div class="contact-address-info">
                            <div class="contact-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Address</h4>
                                <span>{!! nl2br(e($profil ? $profil->alamat : '-')) !!}</span>
                            </div>
                        </div>
                        <div class="contact-address-info">
                            <div class="contact-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Contact 1</h4>
                                <span>
                                    <a href="https://wa.me/{{ $profil ? $profil->kontak1 : '-'  }}" target="_blank">{{
                                        $profil ? $profil->kontak1 : '-' }}</a> </span>
                            </div>
                        </div>
                        <div class="contact-address-info">
                            <div class="contact-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Contact 2</h4>
                                <span>
                                    <a href="https://wa.me/{{ $profil ? $profil->kontak2 : '-'  }}" target="_blank">{{
                                        $profil ? $profil->kontak2 : '-' }}</a>
                                </span>
                            </div>
                        </div>
                        <div class="contact-address-info">
                            <div class="contact-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <span>{{ $profil ? $profil->email : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-image">
                    <img src="{{ $profil ? url('uploads/images/' . $profil->image) : '' }}" alt="" style="width:100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<br>
<!--Google Map Area Start -->
<div class="google-map-area">
    <div id="contacts" class="map-area">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3115872601697!2d108.3084999!3d-6.3536945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6eb915fa5c4fc3%3A0xb04c08d0c5267487!2sSMPN%20Unggulan%20Sindang!5e0!3m2!1sen!2sid!4v1754004739114!5m2!1sen!2sid"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>
<!--End of Google Map Area-->
@endsection
