@extends('layouts.admin.app') {{-- Menggunakan layout utama Kaiadmin --}}

@section('title', 'Admin Dashboard - Cerulean School') {{-- Judul untuk tab browser --}}

@section('admin_content') {{-- Memulai section konten utama --}}
<div class="container"> {{-- Wrapper konten utama dari Kaiadmin --}}
    <div class="page-inner">
        {{-- Page Header (Judul Halaman dan Breadcrumbs) --}}
        <div class="page-header">
            <h4 class="page-title">Dashboard</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                {{-- Tidak perlu separator dan breadcrumb lanjutan untuk halaman dashboard itu sendiri --}}
            </ul>
        </div>

        {{-- Konten Dashboard Sebenarnya --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h4>
                    </div>
                    <div class="card-body">
                        <p>Anda telah berhasil login ke Admin Panel Cerulean School.</p>
                        <p>Dari sini Anda dapat mengelola berbagai aspek website sekolah dan proses Sistem Penerimaan Murid Baru (SPMB).</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contoh Statistik Ringkas (bisa Anda kembangkan) --}}
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-primary bubble-shadow-small">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pendaftar</p>
                                    {{-- Ganti dengan data dinamis --}}
                                    <h4 class="card-title">{{ \App\Models\User::where('role', 'siswa')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-info bubble-shadow-small">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Berkas Diverifikasi</p>
                                    <h4 class="card-title">{{ \App\Models\User::where('role',
                                        'siswa')->where('status_pendaftaran', 'berkas_diverifikasi')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-success bubble-shadow-small">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Siswa Lulus</p>
                                    <h4 class="card-title">{{ \App\Models\User::where('role',
                                        'siswa')->where('status_pendaftaran', 'lulus_seleksi')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-secondary bubble-shadow-small">
                                    <i class="far fa-newspaper"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Berita</p>
                                    <h4 class="card-title">{{ \App\Models\Berita::count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Pendaftar</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-pendaftar"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Pendaftar Berdasarkan Jenis Kelamin</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-kelamin"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <script>
            var ctx1 = document.getElementById('chart-pendaftar').getContext('2d');
            var chart1 = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                    datasets: [{
                        label: 'Pendaftar',
                        backgroundColor: 'rgba(23, 125, 255, 0.2)',
                        borderColor: 'rgba(23, 125, 255, 1)',
                        pointBackgroundColor: 'rgba(23, 125, 255, 1)',
                        pointBorderColor: 'rgba(23, 125, 255, 1)',
                        pointBorderWidth: 2,
                        data: [
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '01')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '02')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '03')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '04')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '05')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '06')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '07')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '08')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '09')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '10')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '11')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->whereMonth('created_at', '12')->count() }}
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            var ctx2 = document.getElementById('jns_kelamin').getContext('2d');
            var chart2 = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ["Laki-laki", "Perempuan"],
                    datasets: [{
                        backgroundColor: ["#3498db", "#e74c3c"],
                        data: [
                            {{ \App\Models\User::where('role', 'siswa')->where('jns_kelamin', 'Laki-laki')->count() }},
                            {{ \App\Models\User::where('role', 'siswa')->where('jns_kelamin', 'Perempuan')->count() }}
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            });
        </script> --}}

    </div>
</div>
@endsection
