{{-- resources/views/components/auth-kaiadmin-layout.blade.php --}}
@props([
'title' => config('app.name', 'Laravel').' - Autentikasi',
'subtitle' => 'Silakan login atau registrasi untuk melanjutkan.',
'backgroundImageUrl' => null, // URL gambar background opsional
'formCardClass' => 'col-xl-5 col-lg-6 col-md-8 col-sm-10' // Default lebar card form
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title }}</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    {{-- Ganti dengan path favicon sekolah Anda --}}
    <link rel="icon" href="{{ asset('techedu/img/favicon.png') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] }, // Font Kaiadmin
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
    {{-- plugins.min.css mungkin tidak semua dibutuhkan untuk halaman auth, bisa di-skip jika memberatkan --}}
    {{--
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/plugins.min.css') }}" /> --}}
    
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/kaiadmin.min.css') }}" />

    {{-- Custom CSS untuk halaman autentikasi --}}
    <style>
        body.auth-page-kaiadmin {
            font-family: "Public Sans", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            /* Untuk overlay */
        }

        body.auth-page-kaiadmin::before {
            /* Overlay gelap agar teks lebih terbaca */
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.35);
            /* Sesuaikan opasitas overlay */
            z-index: 1;
        }

        .auth-container-kaiadmin {
            /* Menggantikan .container agar tidak konflik dengan Bootstrap container di dalam card */
            position: relative;
            z-index: 2;
            /* Agar card di atas overlay */
            width: 100%;
        }

        .auth-wrapper-kaiadmin {
            width: 100%;
            /* Card akan mengambil lebar dari .auth-form-card-wrapper */
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 10px 35px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .auth-header-kaiadmin {
            text-align: center;
            padding: 25px 20px 20px 20px;
        }

        .auth-header-kaiadmin img {
            max-height: 55px;
            /* Ukuran logo sekolah */
            margin-bottom: 10px;
        }

        .auth-header-kaiadmin h3 {
            color: #333740;
            font-weight: 700;
        }

        .auth-header-kaiadmin p {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .auth-content-kaiadmin {
            padding: 20px 35px 30px 35px;
            /* Padding untuk konten form */
        }

        .auth-wrapper-kaiadmin .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.3rem;
        }

        .auth-wrapper-kaiadmin .form-control,
        .auth-wrapper-kaiadmin .form-select {
            border-radius: 5px;
            border: 1px solid #dfe3e9;
            padding: 0.7rem 1rem;
            height: auto;
            box-shadow: none;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .auth-wrapper-kaiadmin .form-control-lg {
            padding: 0.8rem 1.1rem;
            font-size: 1rem;
        }

        /* Ukuran input lebih besar */
        .auth-wrapper-kaiadmin .form-select-lg {
            padding: 0.8rem 1.1rem;
            font-size: 1rem;
            height: calc(2.8rem + 2px);
        }

        .auth-wrapper-kaiadmin .form-control:focus,
        .auth-wrapper-kaiadmin .form-select:focus {
            border-color: #1D62F0;
            box-shadow: 0 0 0 0.2rem rgba(29, 98, 240, 0.25);
        }

        .auth-wrapper-kaiadmin .btn-primary {
            background-color: #1D62F0;
            border-color: #1D62F0;
            padding: 0.75rem 1rem;
            font-weight: 600;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(29, 98, 240, 0.3);
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .auth-wrapper-kaiadmin .btn-primary:hover {
            background-color: #1152d0;
            border-color: #104ab8;
        }

        .auth-wrapper-kaiadmin .btn-lg {
            padding: 0.8rem 1.2rem;
            font-size: 1.1rem;
        }

        .auth-wrapper-kaiadmin .invalid-feedback,
        .auth-wrapper-kaiadmin .text-danger {
            display: block;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .auth-wrapper-kaiadmin .text-primary {
            color: #1D62F0 !important;
        }

        .auth-wrapper-kaiadmin .text-primary:hover {
            color: #1152d0 !important;
        }

        .auth-wrapper-kaiadmin .form-check-input {
            border-color: #adb5bd;
        }

        .auth-wrapper-kaiadmin .form-check-input:checked {
            background-color: #1D62F0;
            border-color: #1D62F0;
        }

        .auth-wrapper-kaiadmin .form-check-label {
            font-size: 0.9rem;
            color: #495057;
            padding-left: 0.3em;
        }

        .auth-footer-kaiadmin {
            font-size: 0.85rem;
            margin-top: 20px;
            text-align: center;
        }
    </style>
    {{-- Slot untuk CSS tambahan dari view yang menggunakan layout ini --}}
    {{ $styles ?? '' }}
</head>

<body class="auth-page-kaiadmin"
    style="{{ $backgroundImageUrl ? 'background-image: url(\'' . $backgroundImageUrl . '\');' : 'background-color: #f0f3f8;' }}">
    <div class="auth-container-kaiadmin">
        <div class="row justify-content-center">
            <div class="{{ $formCardClass }}">
                <div class="auth-wrapper-kaiadmin">
                    <div class="auth-header-kaiadmin">
                        <a href="{{ url('/') }}"> {{-- Arahkan ke beranda publik --}}
                            <img src="{{ asset('techedu/img/favicon.png') }}" alt="Logo {{ config('app.name') }}">
                            {{-- Ganti dengan path logo sekolah Anda --}}
                        </a>
                        <h3 class="mt-2 mb-1 fw-bold">{{ config('app.name') }}</h3>
                        <p class="text-muted">{{ $subtitle }}</p>
                    </div>
                    <div class="auth-content-kaiadmin">
                        {{ $slot }} {{-- Di sinilah konten form login/register Anda akan dimasukkan --}}
                    </div>
                </div>
                <div class="auth-footer-kaiadmin {{ $backgroundImageUrl ? 'text-white' : 'text-muted' }} mt-3">
                    © {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>
    <!-- SweetAlert -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    {{-- Slot untuk script tambahan dari view yang menggunakan layout ini --}}
    {{ $scripts ?? '' }}



    {{-- Memanggil script SweetAlert yang sudah dipisah untuk notifikasi session --}}
    @include('layouts.partials.sweetalert-script')
</body>

</html>
