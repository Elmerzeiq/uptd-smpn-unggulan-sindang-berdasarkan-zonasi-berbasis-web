@extends('layouts.admin.app') {{-- Sesuaikan dengan layout admin Anda --}}

@section('title', 'Detail Kartu Pendaftaran')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        {{-- Page Header --}}
        <div class="page-header">
            <h4 class="page-title h3 mb-4 text-gray-800"><i class="fas fa-id-card text-primary me-2"></i>Detail Kartu Pendaftaran</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
                </li>

            </ul>
        </div>


    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Nomor Kartu: {{ $kartu->nomor_kartu }}</h6>
            <div>
                <a href="{{ route('admin.kartu-pendaftaran.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
                <a href="{{ route('admin.kartu-pendaftaran.edit', $kartu->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit Data
                </a>

                {{-- Tombol Verifikasi/Batalkan Verifikasi --}}
                @if($kartu->verified_by_admin)
                <form action="{{ route('admin.kartu-pendaftaran.unverify', $kartu->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Anda yakin ingin membatalkan verifikasi kartu ini?')">
                        <i class="fas fa-times-circle"></i> Batalkan Verifikasi
                    </button>
                </form>
                @else
                <form action="{{ route('admin.kartu-pendaftaran.verify', $kartu->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm"
                        onclick="return confirm('Anda yakin ingin memverifikasi kartu ini?')">
                        <i class="fas fa-check-circle"></i> Verifikasi Sekarang
                    </button>
                </form>
                @endif
                {{-- Tombol Cetak (untuk admin, mungkin PDF atau print langsung) --}}
                {{-- Anda bisa menambahkan route untuk generate PDF khusus admin di sini, jika ada --}}
                {{-- Contoh: <a href="{{ route('admin.kartu-pendaftaran.print', $kartu->id) }}"
                    class="btn btn-info btn-sm" target="_blank">
                    <i class="fas fa-print"></i> Cetak Kartu
                </a> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri: Data Pendaftaran & Siswa -->
                <div class="col-md-8">
                    <h4>Status & Jalur Pendaftaran</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">Status Verifikasi Admin</th>
                            <td>
                                @if($kartu->verified_by_admin)
                                <span class="badge badge-success">Terverifikasi</span> pada {{
                                $kartu->verified_at->format('d M Y H:i') }} oleh {{ $kartu->verifiedBy->name ?? 'N/A' }}
                                @else
                                <span class="badge badge-warning">Belum Diverifikasi</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pendaftaran Siswa</th>
                            <td>
                                <span
                                    class="badge badge-{{ $kartu->user->status_pendaftaran == 'verified' ? 'info' : ($kartu->user->status_pendaftaran == 'lulus_seleksi' ? 'success' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $kartu->user->status_pendaftaran)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Jalur Pendaftaran</th>
                            <td>{{ $kartu->jalur_pendaftaran }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pendaftaran</th>
                            <td>{{ $kartu->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>

                    <h4>Data Calon Siswa</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">Nama Lengkap</th>
                            <td>{{ $kartu->user->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>NISN</th>
                            <td>{{ $kartu->user->nisn ?? '-' }} </td>
                        </tr>
                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>{{ $kartu->user->biodata->tempat_lahir ?? '-' }}, {{
                                $kartu->user->biodata->tgl_lahir ?
                                \Carbon\Carbon::parse($kartu->user->biodata->tgl_lahir)->format('d F Y') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $kartu->user->biodata->jns_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <th>Asal Sekolah</th>
                            <td>{{ $kartu->user->biodata->asal_sekolah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $kartu->user->biodata->alamat_rumah ?? '-' }}</td>
                        </tr>
                    </table>

                    <h4>Data Orang Tua/Wali</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="2" class="bg-light">Data Ayah</th>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Nama Ayah</th>
                            <td>{{ $kartu->user->orangTua->nama_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>NIK Ayah</th>
                            <td>{{ $kartu->user->orangTua->nik_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>{{ $kartu->user->orangTua->tempat_lahir_ayah ?? '-' }}, {{ isset($kartu->user->orangTua) && $kartu->user->orangTua->tgl_lahir_ayah ? \Carbon\Carbon::parse($kartu->user->orangTua->tgl_lahir_ayah)->format('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pendidikan</th>
                            <td>{{ $kartu->user->orangTua->pendidikan_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pekerjaan</th>
                            <td>{{ $kartu->user->orangTua->pekerjaan_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Penghasilan</th>
                            <td>{{ $kartu->user->orangTua->penghasilan_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>{{ $kartu->user->orangTua->no_hp_ayah ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="bg-light">Data Ibu</th>
                        </tr>
                        <tr>
                            <th>Nama Ibu</th>
                            <td>{{ $kartu->user->orangTua->nama_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>NIK Ibu</th>
                            <td>{{ $kartu->user->orangTua->nik_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>{{ $kartu->user->orangTua->tempat_lahir_ibu ?? '-' }}, {{ isset($kartu->user->orangTua) && $kartu->user->orangTua->tgl_lahir_ibu ? \Carbon\Carbon::parse($kartu->user->orangTua->tgl_lahir_ibu)->format('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pendidikan</th>
                            <td>{{ $kartu->user->orangTua->pendidikan_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Pekerjaan</th>
                            <td>{{ $kartu->user->orangTua->pekerjaan_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Penghasilan</th>
                            <td>{{ $kartu->user->orangTua->penghasilan_ibu ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>{{ $kartu->user->orangTua->no_hp_ibu ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Kolom Kanan: Foto dan QR Code -->
                <div class="col-md-4 text-center">
                    <div class="mb-4">
                        @if($kartu->user->berkas && $kartu->user->berkas->file_pas_foto)
                            <img src="{{ asset('storage/' . $kartu->user->berkas->file_pas_foto) }}"
                                alt="Foto Siswa" class="img-thumbnail" width="200"
                                style="height: 250px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}"
                                alt="Default Avatar" class="img-thumbnail" width="200"
                                style="height: 250px; object-fit: cover;">
                        @endif
                    </div>
                    <hr>
                    <div class="mt-4">
                        <h5>QR Code Kartu Pendaftaran</h5>
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code Pendaftaran" style="max-width: 200px;">
                        <p class="mt-2 small text-muted">QR Code ini digunakan siswa untuk verifikasi di sistem.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
