<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" alt="navbar brand"
                    class="navbar-brand" height="25" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            @if(Auth::user()->foto_profil && Storage::disk('public')->exists('profil/' .
                            Auth::user()->foto_profil))
                            <img src="{{ Storage::url('profil/' . Auth::user()->foto_profil) }}"
                                alt="Foto Profil {{ Auth::user()->nama_lengkap }}" class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle border border-white bg-primary">{{
                                strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}</span>
                            @endif
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{ Auth::user()->nama_lengkap ?? Str::before(Auth::user()->email, '@')
                                }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if(Auth::user()->foto_profil && Storage::disk('public')->exists('profil/' .
                                        Auth::user()->foto_profil))
                                        <img src="{{ Storage::url('profil/' . Auth::user()->foto_profil) }}"
                                            alt="image profile" class="avatar-img rounded">
                                        @else
                                        <span class="avatar-title rounded-circle border border-white bg-primary"
                                            style="font-size: 2rem; width: 60px; height: 60px; line-height: 60px;">{{
                                            strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ Auth::user()->nama_lengkap ?? 'Admin User' }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <span class="badge badge-primary text-capitalize">{{ Auth::user()->role
                                            }}</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('admin-logout-form-header').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout Admin
                                </a>
                                <form id="admin-logout-form-header" action="{{ route('admin.logout') }}" method="POST"
                                    class="d-none">
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
