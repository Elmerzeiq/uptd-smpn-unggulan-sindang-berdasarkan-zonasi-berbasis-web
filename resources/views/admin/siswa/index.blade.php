@extends('layouts.admin.app')

@section('title', 'Data Siswa SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
            {{-- Page Header --}}
            <div class="page-header">
                <h4 class="page-title">
                    <i class="fas fa-users me-2"></i>Data Siswa SPMB
                </h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>

                </ul>
            </div>
    <!-- Page Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        {{-- <h1 class="mb-0 text-gray-800 h3">Data Siswa PPDB</h1> --}}
        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="mb-4 row">
        <div class="col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-primary h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">Total Siswa</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-success h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">Lulus Seleksi</div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $stats['lulus'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-info h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-info text-uppercase">Menunggu Verifikasi
                            </div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $stats['menunggu_verifikasi'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-hourglass-half fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="py-2 shadow card border-left-warning h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="mr-2 col">
                            <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">Data Belum Lengkap
                            </div>
                            <div class="mb-0 text-gray-800 h5 font-weight-bold">{{ $stats['belum_lengkap'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="text-gray-300 fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 card">
        <div class="card-header">
            <i class="fas fa-filter"></i> Filter & Pencarian
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.siswa.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, NISN, email..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="jalur" class="form-control">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurOptions as $key => $label)
                            <option value="{{ $key }}" {{ request('jalur')==$key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ request('status')==$key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jalur</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $student)
                        <tr>
                            <td>{{ $student->nisn }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($student->biodata && $student->biodata->foto_siswa)
                                    <img src="{{ asset('storage/' . $student->biodata->foto_siswa) }}"
                                        class="rounded-circle me-2" width="32" height="32">
                                    @endif
                                    <div>
                                        <strong>{{ $student->nama_lengkap }}</strong>
                                        <br><small class="text-muted">{{ $student->username }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <span class="badge badge-info">{{ $student->jalur_pendaftaran_label }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ \App\Helpers\PpdbHelper::getStatusBadgeClass($student->status_pendaftaran) }}">
                                    {{ $student->status_pendaftaran_label }}
                                </span>
                            </td>
                            <td>{{ $student->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.siswa.show', $student) }}" class="btn btn-sm btn-info"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.siswa.edit', $student) }}" class="btn btn-sm btn-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.siswa.destroy', $student) }}" method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data siswa ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }}
                    dari {{ $siswa->total() }} siswa
                </div>
                <div>
                    {{ $siswa->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
