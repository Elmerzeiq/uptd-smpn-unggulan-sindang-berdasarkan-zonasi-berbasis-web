<div class="sidebar sidebar-style-2" data-background-color="blue2">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="blue2">
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" alt="Logo Sekolah"
                    class="navbar-brand" height="35" style="object-fit: contain;" />
                <span class="text-white ms-2 fw-bold" style="font-size: 0.85rem; line-height: 1.2;">
                    UPTD SMPN<br>Unggulan Sindang
                </span>
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            @php
            // Mengambil data user dan jadwal ppdb yang aktif
            $user = Auth::user();
            $jadwalSiswa = \App\Models\JadwalPpdb::aktif()->first();

            // KONDISI UNTUK PENGUMUMAN: Jadwal pengumuman sudah dibuka ATAU siswa sudah punya status lulus/tidak lulus/diterima daftar ulang.
            $isPengumumanUnlocked = $jadwalSiswa && ($jadwalSiswa->isPengumumanOpen() ||
            in_array($user->status_pendaftaran, ['lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai']));


            @endphp

            <ul class="nav nav-primary">
                {{-- Dashboard --}}
                <li class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('siswa.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- KELENGKAPAN DATA --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">KELENGKAPAN DATA</h4>
                </li>

                <li class="nav-item {{ request()->routeIs('siswa.biodata*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.biodata.index') }}">
                        <i class="fas fa-user-edit"></i>
                        <p>Biodata Siswa</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('siswa.berkas*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.berkas.index') }}">
                        <i class="fas fa-folder-open"></i>
                        <p>Upload Berkas</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('siswa.kartu-pendaftaran*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.kartu-pendaftaran.show') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Kartu Pendaftaran</p>
                    </a>
                </li>

                {{-- HASIL SELEKSI (Selalu tampil) --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">HASIL SELEKSI</h4>
                </li>

                {{-- PENGUMUMAN HASIL --}}
                <li class="nav-item {{ request()->routeIs('siswa.pengumuman*') ? 'active' : '' }}">
                    {{-- Link menjadi aktif/terkunci berdasarkan kondisi $isPengumumanUnlocked --}}
                    <a href="{{ $isPengumumanUnlocked ? route('siswa.pengumuman.index') : '#' }}"
                        class="{{ !$isPengumumanUnlocked ? 'disabled-link' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Pengumuman Hasil</p>
                        {{-- Logika untuk menampilkan badge status --}}
                        @if($user->status_pendaftaran === 'lulus_seleksi')
                        <span class="badge badge-success badge-sm float-end"><i class="fas fa-check-circle"></i>
                            Lulus</span>
                        @elseif($user->status_pendaftaran === 'tidak_lulus_seleksi')
                        <span class="badge badge-danger badge-sm float-end"><i class="fas fa-times-circle"></i> Tidak
                            Lulus</span>
                        @elseif($isPengumumanUnlocked)
                        <span class="badge badge-info badge-sm float-end"><i class="fas fa-clock"></i> Tersedia</span>
                        @else
                        <span class="badge badge-secondary badge-sm float-end"><i class="fas fa-lock"></i>
                            Terkunci</span>
                        @endif
                    </a>
                </li>

                {{-- DAFTAR ULANG --}}
                <li class="nav-item {{ request()->routeIs('siswa.daftar-ulang*') ? 'active' : '' }}">
                    {{-- Link menjadi aktif/terkunci berdasarkan kondisi $isDaftarUlangUnlocked --}}
                    <a href="{{  route('siswa.daftar-ulang.index') }}">

                        <i class="fas fa-user-check"></i>
                        <p>Daftar Ulang</p>
                        {{-- Logika untuk menampilkan badge status --}}
                        @if(optional($user)->status_daftar_ulang === 'selesai')
                        <span class="badge badge-success badge-sm float-end"><i class="fas fa-check"></i> Selesai</span>
                        @elseif($jadwalSiswa && $jadwalSiswa->isDaftarUlangOpen())
                        <span class="badge badge-info badge-sm float-end"><i class="fas fa-clock"></i> Aktif</span>
                        @else
                        <span class="badge badge-secondary badge-sm float-end"><i class="fas fa-lock"></i>
                            Terkunci</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .disabled-link {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }

    .nav-item .badge {
        margin-top: 5px;
    }
</style>
