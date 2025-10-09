@extends('layouts.admin.app')

@section('title', 'Laporan Daftar Ulang')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Laporan Daftar Ulang</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.daftar-ulang.laporan') }}" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="periode" class="form-label">Periode</label>
                                <select name="periode" id="periode" class="form-select">
                                    <option value="">Pilih Periode</option>
                                    <option value="hari_ini" @selected(request('periode')=='hari_ini' )>Hari Ini
                                    </option>
                                    <option value="minggu_ini" @selected(request('periode')=='minggu_ini' )>Minggu Ini
                                    </option>
                                    <option value="bulan_ini" @selected(request('periode')=='bulan_ini' )>Bulan Ini
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                                    value="{{ request('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                                    value="{{ request('tanggal_selesai') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>
                                    Tampilkan</button>
                                <a href="{{ route('admin.daftar-ulang.export', request()->all()) }}"
                                    class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalPendaftar }}</h5>
                                    <p class="card-text">Total Pendaftar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalSelesai }}</h5>
                                    <p class="card-text">Daftar Ulang Selesai</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h5>
                                    <p class="card-text">Total Pembayaran Lunas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($daftarUlang->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No. DU</th>
                                    <th>Nama Siswa</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Jadwal</th>
                                    <th>Status</th>
                                    <th>Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarUlang as $data)
                                <tr>
                                    <td>{{ $data->nomor_daftar_ulang }}</td>
                                    <td>{{ $data->user->biodata->nama_lengkap_siswa ?? $data->user->name }}</td>
                                    <td>{{ $data->created_at->format('d M Y') }}</td>
                                    <td>{{ $data->jadwal->nama_sesi ?? 'N/A' }}</td>
                                    <td>{!! $data->status_badge !!}</td>
                                    <td class="text-end">Rp {{ number_format($data->total_biaya, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <p>Tidak ada data laporan untuk periode yang dipilih.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
