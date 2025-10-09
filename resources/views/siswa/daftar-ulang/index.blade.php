{{-- resources/views/siswa/daftar-ulang/index.blade.php --}}
@extends('layouts.siswa.app')

@section('title', 'Daftar Ulang')

@section('siswa_content')
<br>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Ulang Siswa Baru</h4>
                    <p class="card-text">Selamat! Anda telah lulus seleksi PPDB. Silakan lakukan proses daftar ulang di
                        bawah ini.</p>
                </div>
                <div class="card-body">

                    {{-- Progress Steps --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="progress-steps">
                                <div
                                    class="step {{ $daftarUlang->kartu_lolos_seleksi ? 'completed' : ($daftarUlang->status_daftar_ulang === 'belum_daftar' ? 'active' : '') }}">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Upload Kartu Lolos</div>
                                </div>
                                <div
                                    class="step {{ $daftarUlang->jadwal_id ? 'completed' : ($daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_berkas' ? 'active' : '') }}">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Pilih Jadwal</div>
                                </div>
                                <div
                                    class="step {{ $daftarUlang->status_pembayaran === 'sudah_lunas' ? 'completed' : ($daftarUlang->jadwal_id && !$daftarUlang->bukti_pembayaran ? 'active' : '') }}">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Pembayaran</div>
                                </div>
                                <div
                                    class="step {{ $daftarUlang->bukti_pembayaran ? 'completed' : ($daftarUlang->status_daftar_ulang === 'menunggu_verifikasi_pembayaran' ? 'active' : '') }}">
                                    <div class="step-number">4</div>
                                    <div class="step-title">Upload Bukti</div>
                                </div>
                                <div
                                    class="step {{ $daftarUlang->status_daftar_ulang === 'daftar_ulang_selesai' ? 'completed active' : '' }}">
                                    <div class="step-number">5</div>
                                    <div class="step-title">Selesai</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status Alert --}}
                    @if($daftarUlang->status_daftar_ulang === 'daftar_ulang_selesai')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Selamat!</strong> Proses daftar ulang Anda telah selesai. Silakan menunggu jadwal
                        MPLS/orientasi siswa baru.
                    </div>
                    @elseif($daftarUlang->status_daftar_ulang === 'ditolak')
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        <strong>Ditolak!</strong> {{ $daftarUlang->catatan_verifikasi }}
                    </div>
                    @endif

                    {{-- Step 1: Upload Kartu Lolos Seleksi --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>1. Upload Kartu Lolos Seleksi</h5>
                        </div>
                        <div class="card-body">
                            @if($daftarUlang->kartu_lolos_seleksi)
                            <div class="alert alert-success">
                                <i class="fas fa-check"></i> Kartu lolos seleksi sudah diupload
                                <a href="{{ Storage::url($daftarUlang->kartu_lolos_seleksi) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary ml-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                                @if($daftarUlang->status_daftar_ulang !== 'daftar_ulang_selesai')
                                <form action="{{ route('siswa.daftar-ulang.delete-file', 'kartu_lolos_seleksi') }}"
                                    method="POST" class="d-inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin ingin menghapus file ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                            @else
                            <form action="{{ route('siswa.daftar-ulang.upload-kartu') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="kartu_lolos_seleksi" class="form-label">Kartu Lolos Seleksi</label>
                                    <input type="file"
                                        class="form-control @error('kartu_lolos_seleksi') is-invalid @enderror"
                                        id="kartu_lolos_seleksi" name="kartu_lolos_seleksi"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <div class="form-text">Format: PDF, JPG, PNG. Maksimal 5MB</div>
                                    @error('kartu_lolos_seleksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload Kartu
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Berkas Pendaftaran (View Only) --}}
                    @if($daftarUlang->kartu_lolos_seleksi)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Berkas Pendaftaran Anda</h5>
                            <small class="text-muted">Data ini diambil dari proses pendaftaran sebelumnya</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($daftarBerkas as $field => $details)
                                @if($user->berkas && $user->berkas->$field)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">{{ $details['label'] }}</h6>
                                            @if(isset($details['multiple']) && $details['multiple'])
                                            @php $files = json_decode($user->berkas->$field, true) @endphp
                                            @if($files)
                                            @foreach($files as $file)
                                            <a href="{{ Storage::url($file) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary mb-1">
                                                <i class="fas fa-file"></i> File {{ $loop->iteration }}
                                            </a>
                                            @endforeach
                                            @endif
                                            @else
                                            <a href="{{ Storage::url($user->berkas->$field) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file"></i> Lihat File
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Step 2: Pilih Jadwal --}}
                    @if($daftarUlang->kartu_lolos_seleksi && $daftarUlang->status_daftar_ulang !== 'ditolak')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>2. Pilih Jadwal Daftar Ulang</h5>
                        </div>
                        <div class="card-body">
                            @if($daftarUlang->jadwal_id)
                            <div class="alert alert-info">
                                <i class="fas fa-calendar"></i>
                                <strong>Jadwal Terpilih:</strong><br>
                                {{ $daftarUlang->jadwal->nama_sesi }}<br>
                                {{ \Carbon\Carbon::parse($daftarUlang->jadwal->tanggal)->format('d M Y') }}
                                ({{ \Carbon\Carbon::parse($daftarUlang->jadwal->waktu_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($daftarUlang->jadwal->waktu_selesai)->format('H:i') }})
                            </div>

                            {{-- Komponen Biaya --}}
                            @if($daftarUlang->detailBiaya->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Komponen Biaya</th>
                                            <th class="text-end">Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($daftarUlang->detailBiaya as $detail)
                                        <tr>
                                            <td>{{ $detail->komponenBiaya->nama_komponen }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->biaya, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-primary">
                                            <th>Total Biaya</th>
                                            <th class="text-end">Rp {{ number_format($daftarUlang->total_biaya, 0, ',',
                                                '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @endif
                            @else
                            <form action="{{ route('siswa.daftar-ulang.pilih-jadwal') }}" method="POST">
                                @csrf

                                {{-- Pilihan Jadwal --}}
                                <div class="mb-4">
                                    <label class="form-label"><strong>Pilih Jadwal Daftar Ulang</strong></label>
                                    @if($jadwalTersedia->count() > 0)
                                    <div class="row">
                                        @foreach($jadwalTersedia as $jadwal)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="jadwal_id"
                                                            id="jadwal_{{ $jadwal->id }}" value="{{ $jadwal->id }}"
                                                            required>
                                                        <label class="form-check-label" for="jadwal_{{ $jadwal->id }}">
                                                            <strong>{{ $jadwal->nama_sesi }}</strong><br>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M
                                                                Y') }}<br>
                                                                {{
                                                                \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')
                                                                }} -
                                                                {{
                                                                \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')
                                                                }}<br>
                                                                Sisa slot: {{ $jadwal->slot_tersisa }}/{{ $jadwal->kuota
                                                                }}
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="alert alert-warning">Belum ada jadwal daftar ulang yang tersedia.</div>
                                    @endif
                                </div>

                                {{-- Pilihan Komponen Biaya --}}
                                <div class="mb-4">
                                    <label class="form-label"><strong>Komponen Biaya</strong></label>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Komponen</th>
                                                    <th>Biaya</th>
                                                    <th>Status</th>
                                                    <th>Pilih</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($komponenBiaya as $komponen)
                                                <tr>
                                                    <td>
                                                        {{ $komponen->nama_komponen }}
                                                        @if($komponen->keterangan)
                                                        <br><small class="text-muted">{{ $komponen->keterangan
                                                            }}</small>
                                                        @endif
                                                    </td>
                                                    <td>Rp {{ number_format($komponen->biaya, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($komponen->is_wajib)
                                                        <span class="badge bg-danger">Wajib</span>
                                                        @else
                                                        <span class="badge bg-info">Opsional</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($komponen->is_wajib)
                                                        <input type="checkbox" checked disabled> Otomatis
                                                        @else
                                                        <input type="checkbox" name="komponen_biaya[]"
                                                            value="{{ $komponen->id }}">
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-primary">
                                                    <td colspan="3"><strong>Total Minimal (Biaya Wajib)</strong></td>
                                                    <td><strong>Rp {{ number_format($totalBiayaDefault, 0, ',', '.')
                                                            }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                @if($jadwalTersedia->count() > 0)
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Pilih Jadwal & Lanjut ke Pembayaran
                                </button>
                                @endif
                            </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Step 3: Pembayaran --}}
                    @if($daftarUlang->jadwal_id && $daftarUlang->status_daftar_ulang !== 'ditolak')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>3. Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            @if($daftarUlang->status_pembayaran === 'sudah_lunas')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Pembayaran sudah terverifikasi lunas
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Total Pembayaran</h6>
                                    <h3 class="text-primary">Rp {{ number_format($daftarUlang->total_biaya, 0, ',', '.')
                                        }}</h3>

                                    <a href="{{ route('siswa.daftar-ulang.info-pembayaran') }}" class="btn btn-info">
                                        <i class="fas fa-info-circle"></i> Info Pembayaran Lengkap
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <h6>Informasi Bank</h6>
                                    <div class="card border-info">
                                        <div class="card-body p-3">
                                            <strong>{{ $bankInfo['utama']['nama'] }}</strong><br>
                                            <strong>{{ $bankInfo['utama']['nomor_rekening'] }}</strong><br>
                                            a.n {{ $bankInfo['utama']['atas_nama'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Step 4: Upload Bukti Pembayaran --}}
                    @if($daftarUlang->jadwal_id && $daftarUlang->status_pembayaran !== 'sudah_lunas' &&
                    $daftarUlang->status_daftar_ulang !== 'ditolak')
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>4. Upload Bukti Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            @if($daftarUlang->bukti_pembayaran)
                            <div class="alert alert-info">
                                <i class="fas fa-clock"></i>
                                Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                                <br>
                                <small>Tanggal Pembayaran: {{
                                    \Carbon\Carbon::parse($daftarUlang->tanggal_pembayaran)->format('d M Y') }}</small>
                                <br>
                                <a href="{{ Storage::url($daftarUlang->bukti_pembayaran) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-eye"></i> Lihat Bukti
                                </a>
                                @if($daftarUlang->status_pembayaran === 'ditolak')
                                <form action="{{ route('siswa.daftar-ulang.delete-file', 'bukti_pembayaran') }}"
                                    method="POST" class="d-inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-2"
                                        onclick="return confirm('Yakin ingin menghapus file ini?')">
                                        <i class="fas fa-trash"></i> Hapus & Upload Ulang
                                    </button>
                                </form>
                                @endif
                            </div>

                            @if($daftarUlang->status_pembayaran === 'ditolak')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Bukti Pembayaran Ditolak</strong><br>
                                {{ $daftarUlang->catatan_verifikasi }}
                            </div>
                            @endif
                            @else
                            <form action="{{ route('siswa.daftar-ulang.upload-bukti') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                                        <input type="date"
                                            class="form-control @error('tanggal_pembayaran') is-invalid @enderror"
                                            id="tanggal_pembayaran" name="tanggal_pembayaran" max="{{ date('Y-m-d') }}"
                                            value="{{ old('tanggal_pembayaran') }}" required>
                                        @error('tanggal_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                                        <input type="file"
                                            class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                            id="bukti_pembayaran" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png"
                                            required>
                                        <div class="form-text">Format: PDF, JPG, PNG. Maksimal 5MB</div>
                                        @error('bukti_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Informasi Tambahan --}}
                    @if($daftarUlang->status_daftar_ulang === 'daftar_ulang_selesai')
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h5><i class="fas fa-graduation-cap"></i> Selamat! Daftar Ulang Selesai</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Status:</strong> Daftar ulang berhasil diselesaikan</p>
                            <p><strong>Jadwal:</strong> {{ $daftarUlang->jadwal->nama_sesi }} - {{
                                \Carbon\Carbon::parse($daftarUlang->jadwal->tanggal)->format('d M Y') }}</p>
                            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($daftarUlang->total_biaya, 0, ',',
                                '.') }}</p>

                            <hr>
                            <h6>Langkah Selanjutnya:</h6>
                            <ul>
                                <li>Datang sesuai jadwal yang telah dipilih untuk daftar ulang</li>
                                <li>Bawa bukti daftar ulang ini untuk verifikasi Pembayaran</li>
                                <li>Menunggu informasi jadwal MPLS/orientasi siswa baru</li>
                                <li>Pantau terus pengumuman dari sekolah</li>
                            </ul>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Penting:</strong> Simpan bukti daftar ulang ini dan bawa saat datang ke sekolah.
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .progress-steps {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .step-title {
        font-size: 12px;
        text-align: center;
        color: #6c757d;
        max-width: 80px;
    }

    .step.active .step-number {
        background: #007bff;
        color: white;
    }

    .step.active .step-title {
        color: #007bff;
        font-weight: bold;
    }

    .step.completed .step-number {
        background: #28a745;
        color: white;
    }

    .step.completed .step-title {
        color: #28a745;
    }
</style>
@endsection

