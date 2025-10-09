@extends('layouts.admin.app')

@section('title', 'Daftar Siswa Daftar Ulang')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Siswa Daftar Ulang</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.daftar-ulang.daftar-siswa') }}" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status Daftar Ulang</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu_verifikasi_berkas"
                                        @selected(request('status')=='menunggu_verifikasi_berkas' )>Menunggu Verifikasi
                                        Berkas</option>
                                    <option value="berkas_diverifikasi"
                                        @selected(request('status')=='berkas_diverifikasi' )>Berkas Diverifikasi
                                    </option>
                                    <option value="menunggu_verifikasi_pembayaran"
                                        @selected(request('status')=='menunggu_verifikasi_pembayaran' )>Menunggu
                                        Verifikasi Pembayaran</option>
                                    <option value="daftar_ulang_selesai"
                                        @selected(request('status')=='daftar_ulang_selesai' )>Selesai</option>
                                    <option value="ditolak" @selected(request('status')=='ditolak' )>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pembayaran" class="form-label">Status Pembayaran</label>
                                <select name="pembayaran" id="pembayaran" class="form-select">
                                    <option value="">Semua Pembayaran</option>
                                    <option value="belum_bayar" @selected(request('pembayaran')=='belum_bayar' )>Belum
                                        Bayar</option>
                                    <option value="menunggu_verifikasi"
                                        @selected(request('pembayaran')=='menunggu_verifikasi' )>Menunggu Verifikasi
                                    </option>
                                    <option value="sudah_lunas" @selected(request('pembayaran')=='sudah_lunas' )>Lunas
                                    </option>
                                    <option value="ditolak" @selected(request('pembayaran')=='ditolak' )>Ditolak
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="jadwal" class="form-label">Jadwal</label>
                                <select name="jadwal" id="jadwal" class="form-select">
                                    <option value="">Semua Jadwal</option>
                                    @foreach($jadwalList as $jadwal)
                                    <option value="{{ $jadwal->id }}" @selected(request('jadwal')==$jadwal->id)>{{
                                        $jadwal->nama_sesi }} ({{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M
                                        Y') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i>
                                    Filter</button>
                                <a href="{{ route('admin.daftar-ulang.daftar-siswa') }}" class="btn btn-secondary"><i
                                        class="fas fa-redo"></i> Reset</a>
                            </div>
                        </div>
                    </form>

                    @if($daftarUlang->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No. DU</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Jadwal</th>
                                    <th>Status DU</th>
                                    <th>Status Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarUlang as $registration)
                                <tr>
                                    <td>{{ $registration->nomor_daftar_ulang }}</td>
                                    <td>{{ $registration->user->nisn }}</td>
                                    <td>{{ $registration->user->biodata->nama_lengkap_siswa ?? $registration->user->name
                                        }}</td>
                                    <td>{{ $registration->jadwal->nama_sesi ?? 'N/A' }}</td>
                                    <td>{!! $registration->status_badge !!}</td>
                                    <td>{!! $registration->pembayaran_badge !!}</td>
                                    <td>
                                        <a href="{{ route('admin.daftar-ulang.detail-siswa', $registration->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $daftarUlang->withQueryString()->links() }}
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <p>Data tidak ditemukan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
