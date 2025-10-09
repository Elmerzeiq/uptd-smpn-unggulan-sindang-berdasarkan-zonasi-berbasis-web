<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Cerulean School</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('techedu/img/favicon.png') }}" />

    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,300,500,600,700" rel="stylesheet" type="text/css" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Style CSS
		============================================ -->
    <link rel="stylesheet" href="{{ asset('techedu/style.css') }}" />

    <!-- Modernizr JS
		============================================ -->
    <script src="{{ asset('techedu/js/vendor/modernizr-2.8.3.min.js') }}"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">


<style>
        /* Kustomisasi warna Techedu jika perlu agar serasi */
        /* .header-logo-menu.sticker,
        .header-four .header-top {
            background-color: #0c91d9;
            /* Biru utama */


        .mainmenu nav ul#nav>li>a,
        .header-top-info span,
        .header-top-info .social-links a {
            color: white;
        }

        .mainmenu nav ul#nav>li>a:hover {
            color: #f0f0f0;
        }

        .btn-primary.daftar-ppdb-btn {
            background-color: #0c91d9;
            border-color: #0c91d9;
            color: white !important;
        }

        /* Hijau untuk tombol daftar */
        .btn-primary.daftar-ppdb-btn:hover {
            background-color: #0c91d9;
            border-color: #0c91d9;
        }
        /* #217a33 */
        /* #526979; */
        .footer-area {
            background-color:#0c91d9;
            /* Abu-abu tua untuk footer */
            color: #f0f0f0;
        }

        .footer-area a {
            color: #e0e0e0;
        }

        .footer-area a:hover {
            color: #fff;
        }

        /* ... dan seterusnya ... */
    </style>
</head>

<body>

    @include('home.layouts.header')

    @yield('home')

    @include('home.layouts.footer')

    <!-- jquery
		============================================ -->
    <script src="{{ asset('techedu/js/vendor/jquery-1.12.3.min.js') }}"></script>

    <!-- Popper JS
		============================================ -->
    <script src="{{ asset('techedu/js/popper.js') }}"></script>

    <!-- bootstrap JS
		============================================ -->
    <script src="{{ asset('techedu/js/bootstrap.min.js') }}"></script>

    <!-- bootstrap Toggle JS
		============================================ -->
    <script src="{{ asset('techedu/js/bootstrap-toggle.min.js') }}"></script>

    <!-- nivo slider js
		============================================ -->
    <script src="{{ asset('techedu/lib/nivo-slider/js/jquery.nivo.slider.js') }}"></script>
    <script src="{{ asset('techedu/lib/nivo-slider/home.js') }}"></script>

    <!-- wow JS
		============================================ -->
    <script src="{{ asset('techedu/js/wow.min.js') }}"></script>

    <!-- meanmenu JS
		============================================ -->
    <script src="{{ asset('techedu/js/jquery.meanmenu.js') }}"></script>

    <!-- Owl carousel JS
		============================================ -->
    <script src="{{ asset('techedu/js/owl.carousel.min.js') }}"></script>

    <!-- Countdown JS
		============================================ -->
    <script src="{{ asset('techedu/js/jquery.countdown.min.js') }}"></script>

    <!-- scrollUp JS
		============================================ -->
    <script src="{{ asset('techedu/js/jquery.scrollUp.min.js') }}"></script>

    <!-- Waypoints JS
		============================================ -->
    <script src="{{ asset('techedu/js/waypoints.min.js') }}"></script>

    <!-- Counterup JS
		============================================ -->
    <script src="{{ asset('techedu/js/jquery.counterup.min.js') }}"></script>

    <!-- Slick JS
		============================================ -->
    <script src="{{ asset('techedu/js/slick.min.js') }}"></script>

    <!-- Mix It Up JS
		============================================ -->
    <script src="{{ asset('techedu/js/jquery.mixitup.js') }}"></script>

    <!-- Venubox JS
		============================================ -->
    <script src="{{ asset('techedu/js/venobox.min.js') }}"></script>

    <!-- plugins JS
		============================================ -->
    <script src="{{ asset('techedu/js/plugins.js') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- main JS
		============================================ -->
    <script src="{{ asset('techedu/js/main.js') }}"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <!-- Google Map js
    		============================================ -->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuU_0_uLMnFM-2oWod_fzC0atPZj7dHlU"></script>
    <script src="https://www.google.com/jsapi"></script>
    <script>
        function initialize() {
                var mapOptions = {
                  zoom: 15,
                  scrollwheel: false,
                  center: new google.maps.LatLng(23.763494, 90.432226),
                };

                var map = new google.maps.Map(
                  document.getElementById("googleMap"),
                  mapOptions
                );

                var marker = new google.maps.Marker({
                  position: map.getCenter(),
                  animation: google.maps.Animation.BOUNCE,
                  icon: "img/map-marker.png",
                  map: map,
                });
              }

              google.maps.event.addDomListener(window, "load", initialize);
            </script>
</body>

</html>
