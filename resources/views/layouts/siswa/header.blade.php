{{-- resources/views/layouts/siswa/header.blade.php --}}
<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="blue2"> {{-- Warna header logo --}}
            <a href="{{ route('siswa.dashboard') }}" class="logo text-decoration-none">
                <img src="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" alt="Logo" height="40" class="navbar-brand">
            </a>
            {{-- Tombol toggle sidebar --}}
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" data-background-color="blue2"> {{-- Warna navbar --}}
        <div class="container-fluid">
            {{-- Tombol ini untuk tampilan mobile --}}
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Judul Halaman (Opsional) --}}
            {{-- <h4 class="text-white page-title d-none d-md-block op-7 ms-3">@yield('title_header_siswa', 'Dashboard')</h4> --}}

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                {{-- Notifikasi untuk siswa bisa ditambahkan di sini --}}
                {{-- ... (kode notifikasi jika ada) ... --}}

                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            {{-- Foto Profil Siswa jika ada, atau Inisial --}}
                            @if(Auth::user()->berkas && Auth::user()->berkas->file_pas_foto)
                                <img src="{{ Storage::url(Auth::user()->berkas->file_pas_foto) }}" alt="Foto Profil {{ Auth::user()->nama_lengkap }}" class="avatar-img rounded-circle">
                            @else
                                <span class="border border-white avatar-title rounded-circle bg-primary">{{ strtoupper(substr(Auth::user()->nama_lengkap,0,1)) }}</span>
                            @endif
                        </div>
                        <span class="profile-username ms-2">
                            <span class="text-white op-7">Halo,</span>
                            <span class="text-white fw-bold">{{ Str::words(Auth::user()->nama_lengkap, 2, '') }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if(Auth::user()->berkas && Auth::user()->berkas->file_pas_foto)
                                            {{-- Jika ada foto profil, tampilkan --}}
                                            <img src="{{ Storage::url(Auth::user()->berkas->file_pas_foto) }}" alt="image profile" class="rounded avatar-img">
                                        @else
                                            <span class="border border-white avatar-title rounded-circle bg-primary" style="font-size: 2rem; width: 60px; height: 60px; line-height: 60px;">{{ strtoupper(substr(Auth::user()->nama_lengkap,0,1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="u-text ms-2">
                                        <h4>{{ Auth::user()->nama_lengkap }}</h4>
                                        <p class="mb-0 text-muted">{{ Auth::user()->nisn }}</p>
                                        <p class="text-muted small">{{ Auth::user()->no_pendaftaran ?? '' }}</p>
                                        <span class="text-white badge bg-info">Calon Siswa</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('siswa.dashboard') }}">
                                    <i class="fas fa-home me-2"></i> Dashboard Saya
                                </a>
                                {{-- Link profil/pengaturan akun siswa jika ada --}}
                                <a class="dropdown-item" href="{{ route('siswa.profile.show') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profil Siswa
                                </a>
                                
                                <div class="dropdown-divider"></div>
                                {{-- Tombol Logout --}}
                                <a class="dropdown-item" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                {{-- Form logout untuk JS trigger --}}
                                <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
