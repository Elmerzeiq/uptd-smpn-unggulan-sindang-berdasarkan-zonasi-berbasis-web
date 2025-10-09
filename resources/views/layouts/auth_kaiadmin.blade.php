@props([
'title' => config('app.name', 'Laravel').' - Autentikasi',
'subtitle' => 'Silakan login atau registrasi untuk melanjutkan.',
'backgroundImageUrl' => null, // URL gambar background
'formCardClass' => 'col-xl-5 col-lg-6 col-md-8 col-sm-10' // Lebar card form
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $title }}</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
          urls: ["{{ asset('kaiadmin/assets/css/fonts.min.css') }}"],
        },
        active: function () { sessionStorage.fonts = true; },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/kaiadmin.min.css') }}" />

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
        }

        body.auth-page-kaiadmin::before {
            /* Overlay gelap */
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.35);
            /* Sesuaikan opasitas */
            z-index: 1;
        }

        .auth-wrapper-kaiadmin {
            position: relative;
            /* Agar di atas overlay */
            z-index: 2;
            width: 100%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 10px 35px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            /* Untuk menjaga shadow jika ada konten yang keluar */
        }

        .auth-content-kaiadmin {
            padding: 30px 35px;
            /* Padding untuk konten form */
        }

        .auth-header-kaiadmin {
            text-align: center;
            padding: 20px 20px 15px 20px;
            /* background-color: #f8f9fa; */
            /* Opsional background header card */
            /* border-bottom: 1px solid #dee2e6; */
        }

        .auth-header-kaiadmin img {
            max-height: 55px;
            margin-bottom: 10px;
        }

        .auth-header-kaiadmin h3 {
            color: #333740;
        }

        .auth-header-kaiadmin p {
            font-size: 0.95rem;
            color: #6c757d;
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

        .auth-wrapper-kaiadmin .form-control:focus,
        .auth-wrapper-kaiadmin .form-select:focus {
            border-color: #1D62F0;
            box-shadow: 0 0 0 0.2rem rgba(29, 98, 240, 0.25);
        }

        .auth-wrapper-kaiadmin .btn-primary {
            background-color: #1D62F0;
            border-color: #1D62F0;
            padding: 0.7rem 1rem;
            font-weight: 600;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(29, 98, 240, 0.3);
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .auth-wrapper-kaiadmin .btn-primary:hover {
            background-color: #1152d0;
            border-color: #104ab8;
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

        .auth-wrapper-kaiadmin .form-check-input:checked {
            background-color: #1D62F0;
            border-color: #1D62F0;
        }

        .auth-wrapper-kaiadmin .form-check-label {
            font-size: 0.9rem;
            color: #495057;
        }

        .auth-footer-kaiadmin {
            font-size: 0.85rem;
            margin-top: 20px;
            text-align: center;
        }
    </style>
    {{ $styles ?? '' }}
</head>

<body class="auth-page-kaiadmin"
    style="{{ $backgroundImageUrl ? 'background-image: url(\'' . $backgroundImageUrl . '\');' : 'background-color: #f0f3f8;' }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="{{ $formCardClass }}"> {{-- Lebar card bisa diatur via props --}}
                <div class="auth-wrapper-kaiadmin">
                    <div class="auth-header-kaiadmin">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/logo-sekolah-dark.png') }}" alt="Logo {{ config('app.name') }}">
                        </a>
                        <h3 class="mt-2 mb-1 fw-bold">{{ config('app.name') }}</h3>
                        <p class="text-muted">{{ $subtitle }}</p>
                    </div>
                    <div class="auth-content-kaiadmin">
                        {{ $slot }}
                    </div>
                </div>
                <div class="auth-footer-kaiadmin text-white mt-3"> {{-- Ubah text-muted menjadi text-white jika
                    background gelap --}}
                    © {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    {{ $scripts ?? '' }}
    @include('layouts.partials.sweetalert-script') {{-- Memanggil script SweetAlert terpisah --}}
</body>

</html>
