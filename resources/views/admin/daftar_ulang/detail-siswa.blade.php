@extends('layouts.admin.app')

@section('title', 'Detail Daftar Ulang Siswa')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Detail Pendaftar Ulang: {{
                        optional($daftarUlang->user->biodata)->nama_lengkap_siswa ?? $daftarUlang->user->name }}</h4>
                    <a href="{{ route('admin.daftar-ulang.daftar-siswa') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body">

                    {{-- Informasi Siswa & Status --}}
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5><i class="fas fa-user-circle"></i> Informasi Siswa</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td width="40%"><strong>NISN</strong></td>
                                                    <td>: {{ $daftarUlang->user->nisn ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama Lengkap</strong></td>
                                                    <td>: {{ optional($daftarUlang->user->biodata)->nama_lengkap_siswa
                                                        ?? $daftarUlang->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tempat, Tgl Lahir</strong></td>
                                                    <td>: {{ optional($daftarUlang->user->biodata)->tempat_lahir ?? '-' }}, {{
                                                        optional($daftarUlang->user->biodata)->tgl_lahir ?
                                                        \Carbon\Carbon::parse($daftarUlang->user->biodata->tgl_lahir)->format('d M Y') : '-'
                                                    }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jenis Kelamin</strong></td>
                                                    <td>:
                                                        @if(optional($daftarUlang->user->biodata)->jns_kelamin)
                                                        {{ $daftarUlang->user->biodata->jns_kelamin === 'L' ?
                                                        'Laki-laki' : 'Perempuan' }}
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm">
                                                <tr>
                                                    <td width="40%"><strong>Jalur Pendaftaran</strong></td>
                                                    <td>: {{ $daftarUlang->user->jalur_pendaftaran ?
                                                        strtoupper(str_replace('_', ' ',
                                                        $daftarUlang->user->jalur_pendaftaran)) : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Asal Sekolah</strong></td>
                                                    <td>: {{ optional($daftarUlang->user->biodata)->asal_sekolah ?? '-'
                                                        }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama Ibu</strong></td>
                                                    <td>: {{ optional($daftarUlang->user->orangTua)->nama_ibu ?? '-' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white">
                                    <h5><i class="fas fa-info-circle"></i> Status Proses</h5>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-1"><strong>Status Daftar Ulang:</strong></p>
                                    {!! $daftarUlang->status_badge !!}
                                    <hr class="my-3">
                                    <p class="mb-1"><strong>Status Pembayaran:</strong></p>
                                    {!! $daftarUlang->pembayaran_badge !!}
                                    @if($daftarUlang->verifier)
                                    <hr class="my-3">
                                    <p class="mb-0"><strong>Diverifikasi oleh:</strong><br>{{
                                        $daftarUlang->verifier->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Jadwal dan Biaya --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white">
                                    <h5><i class="fas fa-calendar-alt"></i> Jadwal Daftar Ulang</h5>
                                </div>
                                <div class="card-body">
                                    @if($daftarUlang->jadwal)
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="35%"><strong>Sesi</strong></td>
                                            <td>: {{ $daftarUlang->jadwal->nama_sesi }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal</strong></td>
                                            <td>: {{ \Carbon\Carbon::parse($daftarUlang->jadwal->tanggal)->format('d M
                                                Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Waktu</strong></td>
                                            <td>: {{
                                                \Carbon\Carbon::parse($daftarUlang->jadwal->waktu_mulai)->format('H:i')
                                                }} - {{
                                                \Carbon\Carbon::parse($daftarUlang->jadwal->waktu_selesai)->format('H:i')
                                                }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Keterangan</strong></td>
                                            <td>: {{ $daftarUlang->jadwal->keterangan ?? '-' }}</td>
                                        </tr>
                                    </table>
                                    @else
                                    <div class="text-center text-muted py-4"><i
                                            class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p>Siswa belum memilih jadwal.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark">
                                    <h5><i class="fas fa-money-bill-wave"></i> Detail Biaya</h5>
                                </div>
                                <div class="card-body p-0">
                                    @if($daftarUlang->detailBiaya->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Komponen</th>
                                                    <th class="text-end">Biaya</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($daftarUlang->detailBiaya as $detail)
                                                <tr>
                                                    <td>{{ optional($detail->komponenBiaya)->nama_komponen ?? 'Komponen
                                                        Dihapus' }}</td>
                                                    <td class="text-end">Rp {{ number_format($detail->biaya, 0, ',',
                                                        '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-primary">
                                                <tr>
                                                    <th>Total Pembayaran</th>
                                                    <th class="text-end">Rp {{ number_format($daftarUlang->total_biaya,
                                                        0, ',', '.') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center text-muted py-4"><i
                                            class="fas fa-file-invoice-dollar fa-2x mb-2"></i>
                                        <p>Belum ada detail biaya.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Berkas Upload --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-id-card"></i> Kartu Lolos Seleksi</h5>
                                </div>
                                <div class="card-body">
                                    @if($daftarUlang->kartu_lolos_seleksi)
                                    <div class="d-flex justify-content-between align-items-center"><span><i
                                                class="fas fa-file-alt text-primary me-2"></i>File tersedia</span>
                                        <div><a href="{{ Storage::url($daftarUlang->kartu_lolos_seleksi) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary"><i
                                                    class="fas fa-eye"></i> Lihat</a><a
                                                href="{{ Storage::url($daftarUlang->kartu_lolos_seleksi) }}" download
                                                class="btn btn-sm btn-outline-success"><i class="fas fa-download"></i>
                                                Unduh</a></div>
                                    </div>
                                    @else
                                    <div class="text-center text-muted py-3"><i
                                            class="fas fa-file-times fa-2x mb-2"></i>
                                        <p>Siswa belum mengunggah berkas.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-receipt"></i> Bukti Pembayaran</h5>
                                </div>
                                <div class="card-body">
                                    @if($daftarUlang->bukti_pembayaran)
                                    <div class="mb-2"><strong>Tanggal Bayar:</strong> {{
                                        \Carbon\Carbon::parse($daftarUlang->tanggal_pembayaran)->format('d M Y') }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center"><span><i
                                                class="fas fa-file-alt text-primary me-2"></i>File tersedia</span>
                                        <div><a href="{{ Storage::url($daftarUlang->bukti_pembayaran) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary"><i
                                                    class="fas fa-eye"></i> Lihat</a><a
                                                href="{{ Storage::url($daftarUlang->bukti_pembayaran) }}" download
                                                class="btn btn-sm btn-outline-success"><i class="fas fa-download"></i>
                                                Unduh</a></div>
                                    </div>
                                    @else
                                    <div class="text-center text-muted py-3"><i
                                            class="fas fa-file-times fa-2x mb-2"></i>
                                        <p>Siswa belum mengunggah bukti bayar.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- === BAGIAN AKSI VERIFIKASI === --}}

                    {{-- Form Verifikasi Berkas (Hanya Tampil Jika Diperlukan) --}}
                    @if($daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_berkas' &&
                    $daftarUlang->kartu_lolos_seleksi)
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="fas fa-check-circle"></i> Aksi: Verifikasi Berkas</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.daftar-ulang.verifikasi-berkas', $daftarUlang->id) }}"
                                method="POST">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check mb-2"><input class="form-check-input" type="radio"
                                                name="status" id="verifikasi_setuju" value="diverifikasi"
                                                required><label class="form-check-label text-success fw-bold"
                                                for="verifikasi_setuju"><i class="fas fa-check"></i> Berkas Lengkap &
                                                Diverifikasi</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="status" id="verifikasi_tolak" value="ditolak" required><label
                                                class="form-check-label text-danger fw-bold" for="verifikasi_tolak"><i
                                                    class="fas fa-times"></i> Berkas Ditolak</label></div>
                                    </div>
                                    <div class="col-md-5"><textarea class="form-control" name="catatan"
                                            placeholder="Catatan verifikasi (wajib diisi jika ditolak)"
                                            rows="2"></textarea></div>
                                    <div class="col-md-3 text-end"><button type="submit" class="btn btn-primary"><i
                                                class="fas fa-save"></i> Simpan Verifikasi</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- Form Verifikasi Pembayaran (Hanya Tampil Jika Diperlukan) --}}
                    @if($daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_pembayaran' &&
                    $daftarUlang->bukti_pembayaran)
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h5><i class="fas fa-money-check-alt"></i> Aksi: Verifikasi Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.daftar-ulang.verifikasi-pembayaran', $daftarUlang->id) }}"
                                method="POST">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check mb-2"><input class="form-check-input" type="radio"
                                                name="status" id="pembayaran_lunas" value="lunas" required><label
                                                class="form-check-label text-success fw-bold" for="pembayaran_lunas"><i
                                                    class="fas fa-check"></i> Pembayaran Diterima (Lunas)</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio"
                                                name="status" id="pembayaran_ditolak" value="ditolak" required><label
                                                class="form-check-label text-danger fw-bold" for="pembayaran_ditolak"><i
                                                    class="fas fa-times"></i> Pembayaran Ditolak</label></div>
                                    </div>
                                    <div class="col-md-5"><textarea class="form-control" name="catatan"
                                            placeholder="Catatan verifikasi (wajib diisi jika ditolak)"
                                            rows="2"></textarea></div>
                                    <div class="col-md-3 text-end"><button type="submit" class="btn btn-success"><i
                                                class="fas fa-save"></i> Simpan Verifikasi</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- Catatan Verifikasi --}}
                    @if($daftarUlang->catatan_verifikasi)
                    <div class="card border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h5><i class="fas fa-sticky-note"></i> Riwayat & Catatan Verifikasi Terakhir</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light p-3">{{ $daftarUlang->catatan_verifikasi }}</div>
                            <small class="text-muted">
                                @if($daftarUlang->tanggal_verifikasi_pembayaran)
                                Pembayaran diverifikasi pada: {{ $daftarUlang->tanggal_verifikasi_pembayaran->format('d
                                M Y H:i') }}
                                @elseif($daftarUlang->tanggal_verifikasi_berkas)
                                Berkas diverifikasi pada: {{ $daftarUlang->tanggal_verifikasi_berkas->format('d M Y
                                H:i') }}
                                @endif
                                @if($daftarUlang->verifier)<br>Oleh: {{ $daftarUlang->verifier->name }}@endif
                            </small>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
