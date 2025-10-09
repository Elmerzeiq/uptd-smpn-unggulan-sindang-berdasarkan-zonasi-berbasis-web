<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
                <img src="{{ asset('kaiadmin/assets/img/kaiadmin/favicon.png') }}" alt="Logo Sekolah"
                    class="navbar-brand" height="35" style="object-fit: contain;" />
                <span class="text-white ms-2 fw-bold" style="font-size: 0.85rem; line-height: 1.2;">
                    Cerulean <br>School
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
            <ul class="nav nav-secondary">

                {{-- Dashboard --}}
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- SECTION: MANAJEMEN WEBSITE --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Manajemen Website</h4>
                </li>

                {{-- Konten Sekolah --}}
                @php
                $isKontenSekolahActive = request()->routeIs('admin.profil-sekolah.*') ||
                request()->routeIs('admin.guru-staff.*') ||
                request()->routeIs('admin.galeri.*');
                @endphp
                <li class="nav-item {{ $isKontenSekolahActive ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#kontenSekolahCollapse"
                        class="{{ $isKontenSekolahActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isKontenSekolahActive ? 'true' : 'false' }}">
                        <i class="fas fa-school"></i>
                        <p>Konten Sekolah</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isKontenSekolahActive ? 'show' : '' }}" id="kontenSekolahCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('admin.profil-sekolah.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.profil-sekolah.index') }}">
                                    <span class="sub-item">Profil Sekolah</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.guru-staff.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.guru-staff.index') }}">
                                    <span class="sub-item">Guru & Staff</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.galeri.index') }}">
                                    <span class="sub-item">Galeri</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Informasi & Kegiatan --}}
                @php
                $isInformasiKegiatanActive = request()->routeIs('admin.berita.*') ||
                request()->routeIs('admin.pengumuman.*') ||
                request()->routeIs('admin.ekskul.*');
                @endphp
                <li class="nav-item {{ $isInformasiKegiatanActive ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#informasiKegiatanCollapse"
                        class="{{ $isInformasiKegiatanActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isInformasiKegiatanActive ? 'true' : 'false' }}">
                        <i class="fas fa-bullhorn"></i>
                        <p>Informasi & Kegiatan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isInformasiKegiatanActive ? 'show' : '' }}" id="informasiKegiatanCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.berita.index') }}">
                                    <span class="sub-item">Berita Sekolah</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.pengumuman.index') }}">
                                    <span class="sub-item">Pengumuman Umum</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.ekskul.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.ekskul.index') }}">
                                    <span class="sub-item">Ekstrakurikuler</span>
                                </a>
                            </li>
                            {{-- <li class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.siswa.index') }}">
                                    <span class="sub-item">Profil Siswa</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>


                {{-- SECTION: MANAJEMEN PPDB --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">SPMB INFO</h4>
                </li>

                {{-- Jadwal PPDB --}}
                <li class="nav-item {{ request()->routeIs('admin.jadwal-pendaftaran.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jadwal-pendaftaran.index') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Jadwal SPMB Info</p>
                    </a>
                </li>


                <li class="nav-item {{ request()->routeIs('admin.petunjuk-teknis.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.petunjuk-teknis.index') }}">
                        <i class="fas fa-book"></i>
                        <p>Petunjuk Teknis</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kategori.index') }}">
                        <i class="fas fa-list"></i>
                        <p>Kategori</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.dokumen-persyaratan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dokumen-persyaratan.index') }}">
                        <i class="fas fa-file"></i>
                        <p>Dokumen Persyaratan</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.alur-pendaftaran.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.alur-pendaftaran.index') }}">
                        <i class="fas fa-route"></i>
                        <p>Alur Pendaftaran</p>
                    </a>
                </li>

                {{-- SECTION: MANAJEMEN PPDB --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Manajemen SPMB</h4>
                </li>

                {{-- Jadwal PPDB --}}
                <li class="nav-item {{ request()->routeIs('admin.jadwal-ppdb.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jadwal-ppdb.index') }}">
                        <i class="far fa-calendar-alt"></i>
                        <p>Jadwal SPMB</p>
                    </a>
                </li>

                {{-- Data Pendaftaran --}}
                @php
                $isPendaftaranActive = request()->routeIs('admin.pendaftar.*') ||
                                       request()->routeIs('admin.berkas.*') ||
                                       request()->routeIs('admin.kartu-pendaftaran.*') ||
                                       request()->routeIs('admin.pengumuman-hasil.*');
                @endphp
                <li class="nav-item {{ $isPendaftaranActive ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#pendaftaranCollapse"
                        class="{{ $isPendaftaranActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isPendaftaranActive ? 'true' : 'false' }}">
                        <i class="fas fa-users"></i>
                        <p>Data Pendaftaran</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isPendaftaranActive ? 'show' : '' }}" id="pendaftaranCollapse">
                        <ul class="nav nav-collapse">
                            <li
                                class="{{ request()->routeIs('admin.pendaftar.index') && !request()->has('status') ? 'active' : '' }}">
                                <a href="{{ route('admin.pendaftar.index') }}">
                                    <span class="sub-item">Semua Data Pendaftar</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.berkas.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.berkas.index') }}">
                                    <span class="sub-item">Berkas Pendaftar</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.pendaftar.index') && request('status') === 'berkas_diverifikasi' ? 'active' : '' }}">
                                <a href="{{ route('admin.pendaftar.index', ['status' => 'berkas_diverifikasi']) }}">
                                    <span class="sub-item">Berkas Terverifikasi</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.pendaftar.index') && request('status') === 'lulus_seleksi' ? 'active' : '' }}">
                                <a href="{{ route('admin.pendaftar.index', ['status' => 'lulus_seleksi']) }}">
                                    <span class="sub-item">Siswa Diterima</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.pendaftar.index') && request('status') === 'tidak_lulus_seleksi' ? 'active' : '' }}">
                                <a href="{{ route('admin.pendaftar.index', ['status' => 'tidak_lulus_seleksi']) }}">
                                    <span class="sub-item">Siswa Tidak Diterima</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.kartu-pendaftaran.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.kartu-pendaftaran.index') }}">
                                    <span class="sub-item">Kartu Pendaftaran Siswa</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.pengumuman-hasil.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.pengumuman-hasil.index') }}">
                                    <span class="sub-item">Pengumuman Hasil</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Proses Seleksi --}}
                @php
                $isSeleksiActive = request()->routeIs('admin.seleksi.*');
                @endphp
                <li class="nav-item {{ $isSeleksiActive ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#prosesSeleksiCollapse"
                        class="{{ $isSeleksiActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isSeleksiActive ? 'true' : 'false' }}">
                        <i class="fas fa-tasks"></i>
                        <p>Proses Seleksi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isSeleksiActive ? 'show' : '' }}" id="prosesSeleksiCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('admin.seleksi.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.seleksi.index') }}">
                                    <span class="sub-item">Dashboard Seleksi</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.seleksi.hasil_jalur') && request()->route('jalur') === 'domisili' ? 'active' : '' }}">
                                <a href="{{ route('admin.seleksi.hasil_jalur', ['jalur' => 'domisili']) }}">
                                    <span class="sub-item">Seleksi Zonasi/Domisili</span>
                                </a>
                            </li>
                            {{-- <li
                                class="{{ request()->routeIs('admin.seleksi.hasil_jalur') && request()->route('jalur') === 'prestasi' ? 'active' : '' }}">
                                <a href="{{ route('admin.seleksi.hasil_jalur', ['jalur' => 'prestasi']) }}">
                                    <span class="sub-item">Seleksi Prestasi</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.seleksi.hasil_jalur') && request()->route('jalur') === 'afirmasi' ? 'active' : '' }}">
                                <a href="{{ route('admin.seleksi.hasil_jalur', ['jalur' => 'afirmasi']) }}">
                                    <span class="sub-item">Seleksi Afirmasi</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.seleksi.hasil_jalur') && request()->route('jalur') === 'mutasi' ? 'active' : '' }}">
                                <a href="{{ route('admin.seleksi.hasil_jalur', ['jalur' => 'mutasi']) }}">
                                    <span class="sub-item">Seleksi Mutasi</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>

                {{-- Daftar Ulang --}}
                <li class="nav-item {{ request()->routeIs('admin.daftar-ulang.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.daftar-ulang.index') }}">
                        <i class="fas fa-user-check"></i>
                        <p>Daftar Ulang Siswa</p>
                    </a>
                </li>

                {{-- Daftar Ulang
                <li class="nav-item {{ request()->routeIs('admin.daftar-ulang.jadwal-daftar-ulang.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Jadwal Daftar Ulang Siswa</p>
                    </a>
                </li> --}}

                {{-- Laporan PPDB --}}
                @php
                $isLaporanActive = request()->routeIs('admin.laporan.*');
                @endphp
                <li class="nav-item {{ $isLaporanActive ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#laporanPpdbCollapse"
                        class="{{ $isLaporanActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isLaporanActive ? 'true' : 'false' }}">
                        <i class="fas fa-chart-line"></i>
                        <p>Laporan SPMB</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isLaporanActive ? 'show' : '' }}" id="laporanPpdbCollapse">
                        <ul class="nav nav-collapse">
                            <li
                                class="{{ request()->routeIs('admin.laporan.index') && !request()->has('status') ? 'active' : '' }}">
                                <a href="{{ route('admin.laporan.index') }}">
                                    <span class="sub-item">Laporan Semua Pendaftar</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.laporan.siswa_diterima') || (request()->routeIs('admin.laporan.*') && request('status') === 'lulus_seleksi') ? 'active' : '' }}">
                                <a href="{{ route('admin.laporan.siswa_diterima', ['status' => 'lulus_seleksi']) }}">
                                    <span class="sub-item">Laporan Siswa Diterima</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.laporan.siswa-tidak-lolos') ? 'active' : '' }}">
                                <a href="{{ route('admin.laporan.siswa-tidak-lolos') }}">
                                    <span class="sub-item">Laporan Siswa Tidak Lolos</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.laporan.berkas') || request()->routeIs('admin.laporan.berkas') ? 'active' : '' }}">
                                <a href="{{ route('admin.laporan.berkas') }}">
                                    <span class="sub-item">Laporan Berkas</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->routeIs('admin.laporan.daftar-ulang') || request()->routeIs('admin.laporan.daftar-ulang') ? 'active' : '' }}">
                                <a href="{{ route('admin.laporan.daftar-ulang') }}">
                                    <span class="sub-item">Laporan Daftar Ulang</span>
                                </a>
                            </li>
                            {{-- <li class="{{ request()->routeIs('admin.laporan.statistik') ? 'active' : '' }}">
                                <a href="{{ route('admin.laporan.statistik') }}">
                                    <span class="sub-item">Statistik PPDB</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>

                {{-- SECTION: PENGATURAN SISTEM --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengaturan Sistem</h4>
                </li>

                {{-- Manajemen Akun --}}
                <li class="nav-item {{ request()->routeIs('admin.akun-pengguna.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.akun-pengguna.index') }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Manajemen Akun</p>
                    </a>
                </li>

                {{-- Pengaturan PPDB --}}
                {{-- @php
                $isPengaturanActive = request()->routeIs('admin.pengaturan.*') ||
                request()->routeIs('admin.koordinat.*') ||
                request()->routeIs('admin.backup.*');
                @endphp
                <li class="nav-item {{ $isPengaturanActive ? 'active submenu' : '' }}">
                    <a data-bs-toggle="collapse" href="#pengaturanCollapse"
                        class="{{ $isPengaturanActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isPengaturanActive ? 'true' : 'false' }}">
                        <i class="fas fa-cogs"></i>
                        <p>Pengaturan PPDB</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isPengaturanActive ? 'show' : '' }}" id="pengaturanCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('admin.pengaturan.kuota') ? 'active' : '' }}">
                                <a href="{{ route('admin.pengaturan.kuota') }}">
                                    <span class="sub-item">Pengaturan Kuota</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.koordinat.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.koordinat.index') }}">
                                    <span class="sub-item">Koordinat Zonasi</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.backup.index') }}">
                                    <span class="sub-item">Backup & Restore</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                {{-- Logout --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
