<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Dashboard Kepala Sekolah')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" type="image/x-icon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts and icons -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{ asset("kaiadmin/assets/css/fonts.min.css") }}']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kaiadmin/assets/css/kaiadmin.min.css') }}">

    @yield('styles')

    <style>
        .sidebar-brand {
            background: linear-gradient(45deg, #1f8ef1, #764ba2);
        }

        .sidebar-brand .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        .nav-item.active>.nav-link {
            background: #1f8ef1 !important;
            color: white !important;
        }

        .card-stats {
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2" data-background-color="dark">
            <div class="sidebar-logo">
                <div class="sidebar-brand">
                    <div class="logo-header">
                        <a href="{{ route('kepala-sekolah.dashboard') }}" class="navbar-brand">
                            <img src="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" alt="Logo"
                                style="width: 30px; height: 30px; margin-right: 10px;">
                            <span style="font-size: 14px;">Kepala Sekolah</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <!-- Dashboard -->
                        <li class="nav-item {{ request()->routeIs('kepala-sekolah.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.dashboard') }}" class="nav-link">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <!-- Laporan Section -->
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Laporan PPDB</h4>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs('kepala-sekolah.laporan.semua_pendaftar') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.laporan.semua_pendaftar') }}" class="nav-link">
                                <i class="fas fa-users"></i>
                                <p>Semua Pendaftar</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs('kepala-sekolah.laporan.siswa_diterima') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.laporan.siswa_diterima') }}" class="nav-link">
                                <i class="fas fa-user-check"></i>
                                <p>Siswa Diterima</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs('kepala-sekolah.laporan.siswa_tidak_lolos') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.laporan.siswa_tidak_lolos') }}" class="nav-link">
                                <i class="fas fa-user-times"></i>
                                <p>Siswa Tidak Lolos</p>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('kepala-sekolah.laporan.berkas') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.laporan.berkas') }}" class="nav-link">
                                <i class="fas fa-file-alt"></i>
                                <p>Status Berkas</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs('kepala-sekolah.laporan.daftar_ulang') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.laporan.daftar_ulang') }}" class="nav-link">
                                <i class="fas fa-clipboard-check"></i>
                                <p>Daftar Ulang</p>
                            </a>
                        </li>

                        <!-- Komunikasi Section -->
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Komunikasi</h4>
                        </li>

                        <li class="nav-item {{ request()->routeIs('kepala-sekolah.komentar.*') ? 'active' : '' }}">
                            <a href="{{ route('kepala-sekolah.komentar.index') }}" class="nav-link">
                                <i class="fas fa-comments"></i>
                                <p>Pesan ke Admin</p>
                                @if(Auth::user()->hasUnreadComments())
                                <span class="notification-badge">{{ Auth::user()->getUnreadCommentsCount() }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="{{ route('kepala-sekolah.dashboard') }}" class="logo">
                            <img src="{{ asset('kaiadmin/assets/img/kaiadmin/logo_light.png') }}" alt="navbar brand"
                                class="navbar-brand" height="20">
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>

                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Cari laporan..." class="form-control">
                            </div>
                        </nav>

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                    aria-expanded="false" aria-haspopup="true">
                                    <i class="fa fa-search"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input type="text" placeholder="Cari..." class="form-control">
                                        </div>
                                    </form>
                                </ul>
                            </li>

                            <!-- Messages -->
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link dropdown-toggle" href="{{ route('kepala-sekolah.komentar.index') }}"
                                    id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-envelope"></i>
                                    @if(Auth::user()->hasUnreadComments())
                                    <span class="notification-badge">{{ Auth::user()->getUnreadCommentsCount() }}</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu messages-notif-box animated fadeIn"
                                    aria-labelledby="messageDropdown">
                                    <li>
                                        <div class="dropdown-title d-flex justify-content-between align-items-center">
                                            Pesan Terbaru
                                            <a href="{{ route('kepala-sekolah.komentar.index') }}" class="small">Lihat
                                                Semua</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-notif-scroll scrollbar-outer">
                                            <div class="notif-center">
                                                @php
                                                $recentMessages = \App\Models\Comment::where('to_role',
                                                'kepala_sekolah')
                                                ->with('user')
                                                ->latest()
                                                ->limit(3)
                                                ->get();
                                                @endphp
                                                @forelse($recentMessages as $message)
                                                <a href="{{ route('kepala-sekolah.komentar.index') }}">
                                                    <div class="notif-img">
                                                        <img src="{{ asset('kaiadmin/assets/img/profile.jpg') }}"
                                                            alt="Admin">
                                                    </div>
                                                    <div class="notif-content">
                                                        <span class="subject">{{ Str::limit($message->subject, 30)
                                                            }}</span>
                                                        <span class="block">{{ Str::limit($message->message, 50)
                                                            }}</span>
                                                        <span class="time">{{ $message->time_ago }}</span>
                                                    </div>
                                                </a>
                                                @empty
                                                <div class="text-center p-3">
                                                    <small class="text-muted">Belum ada pesan</small>
                                                </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <!-- Profile -->
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('kaiadmin/assets/img/profile.jpg') }}" alt="Profile"
                                            class="avatar-img rounded-circle">
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hai,</span>
                                        <span class="fw-bold">{{ Auth::user()->nama_lengkap }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('kaiadmin/assets/img/profile.jpg') }}"
                                                        alt="Profile" class="avatar-img rounded">
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->nama_lengkap }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                                    <span class="badge badge-info">Kepala Sekolah</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('kepala-sekolah.dashboard') }}">
                                                <i class="fas fa-tachometer-alt"></i> Dashboard
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('kepala-sekolah.komentar.index') }}">
                                                <i class="fas fa-comments"></i> Pesan
                                                @if(Auth::user()->hasUnreadComments())
                                                <span class="badge badge-danger ms-2">{{
                                                    Auth::user()->getUnreadCommentsCount() }}</span>
                                                @endif
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt"></i> Logout
                                                </button>
                                            </form>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <!-- Main Content -->
            <div class="content">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Peringatan!</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('kepala_sekolah_content')
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kepala-sekolah.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kepala-sekolah.komentar.index') }}">Komunikasi</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright">
                        2024 © Cerulean School - Dashboard Kepala Sekolah
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JS Files -->
    <script src="{{ asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('kaiadmin/assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('kaiadmin/assets/js/kaiadmin.min.js') }}"></script>

    @yield('scripts')

    <script>
        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Real-time notification check every 30 seconds
            setInterval(function() {
                fetch('{{ route("kepala-sekolah.get_statistics") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update notification badges if needed
                        console.log('Statistics updated');
                    })
                    .catch(error => console.error('Error:', error));
            }, 30000);

            // Sidebar toggle
            $('.nav-toggle .btn').on('click', function() {
                $(this).toggleClass('toggled');
            });
        });
    </script>
</body>

</html>
