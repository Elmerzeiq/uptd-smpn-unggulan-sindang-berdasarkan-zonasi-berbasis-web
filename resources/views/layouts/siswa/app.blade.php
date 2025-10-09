<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Admin Panel - Cerulean School')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('kaiadmin/assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files (Load all CSS here) -->
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/kaiadmin.min.css') }}" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" crossorigin="" /> --}}

    <style>
        /* Custom styles */
        :root {
            --primary-color: #0c91d9;
            /* Define a CSS variable for primary color */
        }

        .sidebar[data-background-color="dark2"] {
            background: #29903e;
            /* Example: Green background for sidebar */
        }

        .sidebar[data-background-color="dark2"] .nav>.nav-item>a,
        .sidebar[data-background-color="dark2"] .nav>.nav-item>a[data-bs-toggle=collapse][aria-expanded=true],
        .sidebar[data-background-color="dark2"] .nav>.nav-item>a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar[data-background-color="dark2"] .nav>.nav-item.active>a {
            background: var(--primary-color);
            /* Use primary color for active item */
            color: #fff;
        }

        .sidebar[data-background-color="dark2"] .logo-header {
            background: #526979;
        }

        .navbar-header[data-background-color="blue2"] {
            background: var(--primary-color);
            /* Use primary color for navbar header */
        }

        /* Ensure .btn-primary, .badge-primary, .nav-pills.active use the defined primary color */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0a7cb9;
            border-color: #0a7cb9;
        }

        .badge-primary {
            background-color: var(--primary-color) !important;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background-color: var(--primary-color) !important;
        }

        /* General content styling */
        .content {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .stats-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            color: white;
            margin-bottom: 1rem;
        }

        .stats-card.primary {
            background: linear-gradient(135deg, var(--primary-color), #0a7cb9);
        }

        .stats-card.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .stats-card.info {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }

        .stats-card i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stats-card h3 {
            margin: 0.5rem 0;
            font-size: 1.5rem;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.5rem;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary-color);
        }

        .timeline-item.completed::before {
            background: #28a745;
        }

        .timeline-item.current::before {
            background: #ffc107;
        }

        /* End Custom styles */
    </style>

    @stack('styles') {{-- Untuk CSS tambahan per halaman --}}
</head>

<body>
    <div class="wrapper">

        @include('layouts.siswa.sidebar')

        <div class="main-panel">
            @include('layouts.siswa.header')

            <div class="container"> {{-- Added container-fluid for better layout control --}}
                @yield('siswa_content')
            </div>

            @include('layouts.siswa.footer')
        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert (Kaiadmin's bundled version - assuming it's SweetAlert 1.x or an older version of SweetAlert2) -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- SweetAlert2 CDN (Modern version, load after Kaiadmin's if both are needed, but ideally only use one) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" crossorigin=""></script>

    <!-- Leaflet JS (Load after core JS, before page-specific scripts) -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('kaiadmin/assets/js/kaiadmin.min.js') }}"></script>

    {{-- Notifikasi menggunakan Bootstrap Notify (if still desired, but SweetAlert2 is better for validation errors)
    --}}
    <script>
        $(document).ready(function() {
            // This is for Bootstrap Notify messages, good for simple toasts
            @if(session('success_notify'))
                $.notify({
                    icon: 'fas fa-check-circle',
                    title: '<strong>Berhasil!</strong>',
                    message: "{{ session('success_notify') }}",
                },{
                    type: 'success',
                    placement: { from: "top", align: "right" },
                    time: 1000,
                    delay: 0,
                });
            @endif

            @if(session('error_notify'))
                $.notify({
                    icon: 'fas fa-exclamation-triangle',
                    title: '<strong>Gagal!</strong>',
                    message: "{{ session('error_notify') }}",
                },{
                    type: 'danger',
                    placement: { from: "top", align: "right" },
                    time: 3000,
                    delay: 0,
                });
            @endif
        });
    </script>

    {{-- Script untuk menampilkan session messages dengan SweetAlert2 --}}
    {{-- This block should be after SweetAlert2 library is loaded --}}
    <script>
        $(document).ready(function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    // timer: 3000, // Optional: for persistent error messages, remove timer
                    // showConfirmButton: true // Optional: allow user to close manually
                });
            @endif

            // This SweetAlert2 block is specifically for Laravel validation errors
            @if ($errors->any())
                let errorMessages = '<ul class="text-start ps-3">';
                @foreach ($errors->all() as $error)
                    errorMessages += '<li>{{ $error }}</li>';
                @endforeach
                errorMessages += '</ul>';
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal!',
                    html: errorMessages,
                    // timer: 5000 // Optional
                });
            @endif
        });
    </script>

    @stack('scripts') {{-- PENTING: Hanya satu @stack('scripts') di sini untuk JS tambahan per halaman --}}

</body>

</html>
