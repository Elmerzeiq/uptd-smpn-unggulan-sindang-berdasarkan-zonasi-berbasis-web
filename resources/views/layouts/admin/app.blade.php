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

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    <style>
        .sidebar[data-background-color="dark2"] {
            background: #29903e;
        }

        .sidebar[data-background-color="dark2"] .nav>.nav-item>a,
        .sidebar[data-background-color="dark2"] .nav>.nav-item>a[data-bs-toggle=collapse][aria-expanded=true],
        .sidebar[data-background-color="dark2"] .nav>.nav-item>a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar[data-background-color="dark2"] .nav>.nav-item.active>a {
            background: #0c91d9;
            color: #fff;
        }

        .sidebar[data-background-color="dark2"] .logo-header {
            background: #526979;
        }

        .navbar-header[data-background-color="blue2"] {
            background: #0c91d9;
        }

        .btn-primary {
            background-color: #0c91d9;
            border-color: #0c91d9;
        }

        .btn-primary:hover {
            background-color: #0a7cb9;
            border-color: #0a7cb9;
        }

        .badge-primary {
            background-color: #0c91d9 !important;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background-color: #0c91d9 !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="wrapper">
        @include('layouts.admin.sidebar')
        <div class="main-panel">
            @include('layouts.admin.header')
            @yield('admin_content')
            @include('layouts.admin.footer')
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/kaiadmin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'OK',
                });
            @endif

            @if($errors->any())
                let errorMessages = '<ul class="text-start">';
                @foreach($errors->all() as $error)
                    errorMessages += '<li>{{ $error }}</li>';
                @endforeach
                errorMessages += '</ul>';
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal!',
                    html: errorMessages,
                    confirmButtonText: 'OK',
                });
            @endif
        });
    </script>
</body>

</html>
