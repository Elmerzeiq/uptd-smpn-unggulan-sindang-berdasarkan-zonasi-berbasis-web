{{-- resources/views/admin/daftar-ulang/index.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Dashboard Daftar Ulang')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-user-check"> Data Siswa Daftar Ulang</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Siswa Daftar Ulang</h4>
                    </div>
                    <div class="card-body">

                        {{-- Statistics Cards --}}
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Siswa Lulus
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSiswaLulus
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Total Daftar Ulang
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDaftarUlang
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Menunggu Verifikasi
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $menungguVerifikasiBerkas + $menungguVerifikasiPembayaran }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Daftar Ulang Selesai
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{
                                                    $daftarUlangSelesai
                                                    }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Menu Cepat</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('admin.daftar-ulang.jadwal') }}"
                                                    class="btn btn-primary btn-block">
                                                    <i class="fas fa-calendar-plus"></i><br>
                                                    Kelola Jadwal
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('admin.daftar-ulang.biaya') }}"
                                                    class="btn btn-success btn-block">
                                                    <i class="fas fa-money-bill"></i><br>
                                                    Kelola Biaya
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('admin.daftar-ulang.daftar-siswa') }}"
                                                    class="btn btn-info btn-block">
                                                    <i class="fas fa-users"></i><br>
                                                    Daftar Siswa
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('admin.daftar-ulang.laporan') }}"
                                                    class="btn btn-warning btn-block">
                                                    <i class="fas fa-chart-bar"></i><br>
                                                    Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Recent Registrations --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Pendaftaran Terbaru</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($recentRegistrations->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>NISN</th>
                                                        <th>Nama Siswa</th>
                                                        <th>Jadwal</th>
                                                        <th>Status</th>
                                                        <th>Tanggal Daftar</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentRegistrations as $registration)
                                                    <tr>
                                                        <td>{{ $registration->user->nisn ?? '-' }}</td>
                                                        <td>
                                                            @if($registration->user->biodata)
                                                            {{ $registration->user->nama_lengkap }}
                                                            @else
                                                            {{ $registration->user->name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($registration->jadwal)
                                                            {{ $registration->jadwal->nama_sesi }}<br>
                                                            <small class="text-muted">{{
                                                                \Carbon\Carbon::parse($registration->jadwal->tanggal)->format('d
                                                                M Y') }}</small>
                                                            @else
                                                            <span class="text-muted">Belum pilih</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                            $statusClass = '';
                                                            $statusText = '';
                                                            switch($registration->status_daftar_ulang) {
                                                            case 'menunggu_verifikasi_berkas':
                                                            $statusClass = 'warning';
                                                            $statusText = 'Menunggu Verifikasi Berkas';
                                                            break;
                                                            case 'berkas_diverifikasi':
                                                            $statusClass = 'info';
                                                            $statusText = 'Berkas Diverifikasi';
                                                            break;
                                                            case 'berkas_ditolak':
                                                            $statusClass = 'danger';
                                                            $statusText = 'Berkas Ditolak';
                                                            break;
                                                            case 'menunggu_verifikasi_pembayaran':
                                                            $statusClass = 'warning';
                                                            $statusText = 'Menunggu Verifikasi Pembayaran';
                                                            break;
                                                            case 'pembayaran_ditolak':
                                                            $statusClass = 'danger';
                                                            $statusText = 'Pembayaran Ditolak';
                                                            break;
                                                            case 'daftar_ulang_selesai':
                                                            $statusClass = 'success';
                                                            $statusText = 'Selesai';
                                                            break;
                                                            default:
                                                            $statusClass = 'secondary';
                                                            $statusText = ucwords(str_replace('_', ' ',
                                                            $registration->status_daftar_ulang));
                                                            }
                                                            @endphp
                                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText
                                                                }}</span>
                                                        </td>
                                                        <td>{{ $registration->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.daftar-ulang.detail-siswa', $registration->id) }}"
                                                                class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Belum ada pendaftaran daftar ulang</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
