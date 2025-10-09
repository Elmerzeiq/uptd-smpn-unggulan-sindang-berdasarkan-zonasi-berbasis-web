@extends('layouts.admin')

@section('title', 'Peserta Jadwal Daftar Ulang')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        Peserta: {{ $jadwal->nama_sesi }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Info Jadwal -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Tanggal:</strong><br>
                                        <i class="fas fa-calendar"></i> {{
                                        \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Waktu:</strong><br>
                                        <i class="fas fa-clock"></i> {{
                                        \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Kuota:</strong><br>
                                        <i class="fas fa-users"></i> {{ $peserta->total() }}/{{ $jadwal->kuota }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong><br>
                                        @if($jadwal->aktif)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Aktif
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times"></i> Non-Aktif
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter dan Export -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2"
                                    placeholder="Cari NIM/Nama..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            @if($peserta->count() > 0)
                            <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.export-peserta', $jadwal) }}"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                            <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.print-peserta', $jadwal) }}"
                                class="btn btn-info btn-sm" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($peserta->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">NIM</th>
                                    <th width="25%">Nama</th>
                                    <th width="20%">Program Studi</th>
                                    <th width="15%">Waktu Daftar</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $index => $item)
                                <tr>
                                    <td>{{ $peserta->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $item->mahasiswa->nim ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $item->mahasiswa->nama ?? 'N/A' }}</td>
                                    <td>{{ $item->mahasiswa->program_studi ?? 'N/A' }}</td>
                                    <td>
                                        <small>
                                            {{ $item->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @switch($item->status ?? 'pending')
                                        @case('confirmed')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                        @break
                                        @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                        @case('completed')
                                        <span class="badge bg-primary">Selesai</span>
                                        @break
                                        @default
                                        <span class="badge bg-warning">Menunggu</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $item->id }}" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if(($item->status ?? 'pending') !== 'completed')
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.daftar-ulang.update-status', [$item->id, 'confirmed']) }}">
                                                            <i class="fas fa-check text-success"></i> Konfirmasi
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.daftar-ulang.update-status', [$item->id, 'cancelled']) }}"
                                                            onclick="return confirm('Yakin batalkan pendaftaran ini?')">
                                                            <i class="fas fa-times text-danger"></i> Batalkan
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Pendaftar</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>NIM:</strong></td>
                                                                <td>{{ $item->mahasiswa->nim ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Nama:</strong></td>
                                                                <td>{{ $item->mahasiswa->nama ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Program Studi:</strong></td>
                                                                <td>{{ $item->mahasiswa->program_studi ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Email:</strong></td>
                                                                <td>{{ $item->mahasiswa->email ?? 'N/A' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>No. HP:</strong></td>
                                                                <td>{{ $item->mahasiswa->no_hp ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Status:</strong></td>
                                                                <td>
                                                                    @switch($item->status ?? 'pending')
                                                                    @case('confirmed')
                                                                    <span class="badge bg-success">Dikonfirmasi</span>
                                                                    @break
                                                                    @case('cancelled')
                                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                                    @break
                                                                    @case('completed')
                                                                    <span class="badge bg-primary">Selesai</span>
                                                                    @break
                                                                    @default
                                                                    <span class="badge bg-warning">Menunggu</span>
                                                                    @endswitch
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Waktu Daftar:</strong></td>
                                                                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                                            </tr>
                                                            @if($item->updated_at != $item->created_at)
                                                            <tr>
                                                                <td><strong>Terakhir Update:</strong></td>
                                                                <td>{{ $item->updated_at->format('d/m/Y H:i:s') }}</td>
                                                            </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                </div>

                                                @if($item->catatan)
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="alert alert-info">
                                                            <strong>Catatan:</strong><br>
                                                            {{ $item->catatan }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $peserta->appends(request()->query())->links() }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">
                            @if(request('search'))
                            Tidak ada peserta yang sesuai dengan pencarian "{{ request('search') }}".
                            @else
                            Belum ada peserta yang mendaftar pada jadwal ini.
                            @endif
                        </p>
                        @if(request('search'))
                        <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.peserta', $jadwal) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hapus Filter
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
