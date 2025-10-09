@extends('layouts.kepala_sekolah.app')

@section('title', 'Dashboard Kepala Sekolah - Cerulean School')

@section('kepala_sekolah_content')
<div class="container">
    <div class="page-inner">
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="page-title">Dashboard Monitoring PPDB</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('kepala-sekolah.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Welcome Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Selamat Datang, {{ Auth::user()->nama_lengkap }}!</h4>
                    </div>
                    <div class="card-body">
                        <p>Dashboard monitoring sistem PPDB untuk memantau perkembangan penerimaan murid baru secara
                            real-time.</p>
                        <p><strong>Tanggal:</strong> {{ $currentDate }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pendaftar</p>
                                    <h4 class="card-title">{{ $totalPendaftar }}</h4>
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
                                    <i class="fas fa-file-check"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Berkas Diverifikasi</p>
                                    <h4 class="card-title">{{ $berkasDisverifikasi }}</h4>
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
                                    <p class="card-category">Siswa Diterima</p>
                                    <h4 class="card-title">{{ $siswaLulus }}</h4>
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
                                <div class="text-center icon-big icon-warning bubble-shadow-small">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Progress PPDB</p>
                                    <h4 class="card-title">{{ $progressPercentage }}%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Statistics -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Statistik Hari Ini</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-user-plus fa-2x text-primary"></i>
                                    <h4 class="mt-2">{{ $pendaftarHariIni }}</h4>
                                    <p class="text-muted">Pendaftar Baru</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-file-upload fa-2x text-info"></i>
                                    <h4 class="mt-2">{{ $berkasHariIni }}</h4>
                                    <p class="text-muted">Berkas Masuk</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                    <h4 class="mt-2">{{ $verifikasiHariIni }}</h4>
                                    <p class="text-muted">Verifikasi Selesai</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-newspaper fa-2x text-warning"></i>
                                    <h4 class="mt-2">{{ $beritaHariIni }}</h4>
                                    <p class="text-muted">Berita Hari Ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Grafik Pendaftar per Bulan</div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Status Pendaftaran</div>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Akses Cepat Laporan</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('kepala-sekolah.laporan.semua_pendaftar') }}"
                                    class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-users"></i><br>
                                    Semua Pendaftar
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('kepala-sekolah.laporan.siswa_diterima') }}"
                                    class="btn btn-success btn-lg btn-block">
                                    <i class="fas fa-check-circle"></i><br>
                                    Siswa Diterima
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('kepala-sekolah.laporan.siswa_tidak_lolos') }}"
                                    class="btn btn-danger btn-lg btn-block">
                                    <i class="fas fa-times-circle"></i><br>
                                    Siswa Tidak Lolos
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('kepala-sekolah.komentar.index') }}"
                                    class="btn btn-warning btn-lg btn-block">
                                    <i class="fas fa-comments"></i><br>
                                    Komunikasi Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Aktivitas Terbaru</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->nama_lengkap }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucwords(str_replace('_', ' ', $activity->status_pendaftaran)) }}
                                            </span>
                                        </td>
                                        <td>{{ $activity->updated_at->diffForHumans() }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada aktivitas terbaru</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Comments -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Komentar Terbaru</div>
                        <div class="card-tools">
                            <a href="{{ route('kepala-sekolah.komentar.index') }}" class="btn btn-info btn-sm">Lihat
                                Semua</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($recentComments as $comment)
                        <div class="mb-3 p-3 border rounded">
                            <h6 class="mb-1">{{ $comment->subject }}</h6>
                            <p class="small text-muted mb-1">{{ Str::limit($comment->message, 80) }}</p>
                            <small class="text-muted">{{ $comment->time_ago }}</small>
                            <span class="badge {{ $comment->status_badge }} float-right">{{ $comment->formatted_status
                                }}</span>
                        </div>
                        @empty
                        <p class="text-muted text-center">Belum ada komentar</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Scripts for Charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Monthly Chart
    var ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendaftar per Bulan',
                data: @json($monthlyStats),
                backgroundColor: 'rgba(23, 125, 255, 0.2)',
                borderColor: 'rgba(23, 125, 255, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Status Chart
    var ctxStatus = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Diverifikasi', 'Lulus', 'Tidak Lulus'],
            datasets: [{
                data: [
                    {{ $statusStats['menunggu_verifikasi'] }},
                    {{ $statusStats['berkas_diverifikasi'] }},
                    {{ $statusStats['lulus_seleksi'] }},
                    {{ $statusStats['tidak_lulus'] }}
                ],
                backgroundColor: ['#ffad46', '#1f8ef1', '#18d26e', '#f25961']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Auto refresh statistics every 5 minutes
    setInterval(function() {
        fetch('{{ route("kepala-sekolah.get_statistics") }}')
            .then(response => response.json())
            .then(data => {
                // Update card values (you can add this functionality)
                console.log('Statistics updated:', data);
            })
            .catch(error => console.error('Error updating statistics:', error));
    }, 300000); // 5 minutes
</script>
@endsection
