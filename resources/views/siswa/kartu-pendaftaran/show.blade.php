@extends('layouts.siswa.app')

@section('title', $isFinalCard ? 'Kartu Final SPMB' : 'Kartu Pendaftaran')

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 h3 fw-bold">
                    @if($isFinalCard)
                    <i class="fas fa-award text-success me-2"></i>Kartu Final SPMB
                    @else
                    <i class="fas fa-id-card text-primary me-2"></i>Kartu Bukti Pendaftaran
                    @endif
                </h2>
                <p class="mb-0 text-muted">Ini adalah bukti sah bahwa Anda telah terdaftar dalam sistem.</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('siswa.kartu-pendaftaran.download-pdf', ['id' => $kartu->id]) }}{{ $isFinalCard ? '?type=final' : '' }}"
                    class="btn btn-primary fw-bold"><i class="fas fa-download me-1"></i>Unduh PDF</a>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary"><i
                        class="fas fa-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="border-0 shadow-lg card" id="kartu-pendaftaran">
            <div class="card-header text-white py-4 {{ $isFinalCard ? 'bg-success' : 'bg-primary' }}"
                style="background-image: linear-gradient(135deg, {{ $isFinalCard ? '#198754' : '#0d6efd' }} 0%, {{ $isFinalCard ? '#157347' : '#0a58ca' }} 100%);">
                <div class="text-center">
                    <h1 class="mb-2 h2 fw-bolder">SMPN UNGGULAN SINDANG</h1>
                    <h2 class="mb-3 h4 fw-semibold">
                        {{ $isFinalCard ? 'KARTU FINAL PENERIMAAN' : 'KARTU BUKTI PENDAFTARAN' }}
                    </h2>
                    <span
                        class="px-3 py-2 badge bg-light {{ $isFinalCard ? 'text-success' : 'text-primary' }} fs-6 rounded-pill">
                        Tahun Pelajaran {{ date('Y') }}/{{ date('Y')+1 }}
                    </span>
                </div>
            </div>

            <div class="p-4 p-md-5 card-body">
                @if($isFinalCard)
                <div class="mb-4 text-center alert alert-success" role="alert">
                    <h4 class="alert-heading fw-bold"><i class="fas fa-party-horn me-2"></i>SELAMAT! ANDA DITERIMA</h4>
                    <p class="mb-0">Anda telah resmi diterima sebagai siswa baru di SMPN Unggulan Sindang. Silakan
                        lanjutkan ke tahap daftar ulang.</p>
                </div>
                @endif

                <div class="row g-4">
                    <div class="col-lg-8">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-bold" style="width: 30%;">No. Pendaftaran</td>
                                    <td class="h5 fw-bolder {{ $isFinalCard ? 'text-success' : 'text-primary' }}">{{
                                        $kartu->nomor_kartu }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Nama Lengkap</td>
                                    <td class="fw-bold fs-5">{{ strtoupper($user->nama_lengkap) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">NISN</td>
                                    <td>{{ $user->nisn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Tempat, Tgl Lahir</td>
                                    <td>{{ $user->biodata->tempat_lahir ?? '-' }}, {{ $user->biodata->tgl_lahir ?
                                        \Carbon\Carbon::parse($user->biodata->tgl_lahir)->format('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Asal Sekolah</td>
                                    <td>{{ $user->biodata->asal_sekolah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Alamat</td>
                                    <td>{{ $user->biodata->alamat_rumah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Jalur Pendaftaran</td>
                                    <td class="fw-bold">{{ strtoupper($kartu->jalur_pendaftaran) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Status Pendaftaran</td>
                                    <td>
                                        @php
                                        $statusConfig = [
                                        'belum_diverifikasi' => ['class' => 'warning', 'icon' => 'clock', 'text' =>
                                        'Belum Diverifikasi'],
                                        'menunggu_kelengkapan_data' => ['class' => 'warning', 'icon' => 'clock', 'text'
                                        => 'Menunggu Kelengkapan Data'],
                                        'menunggu_verifikasi_berkas' => ['class' => 'warning', 'icon' => 'clock', 'text'
                                        => 'Menunggu Verifikasi Berkas'],
                                        'berkas_tidak_lengkap' => ['class' => 'danger', 'icon' => 'exclamation-circle',
                                        'text' => 'Berkas Tidak Lengkap'],
                                        'berkas_diverifikasi' => ['class' => 'info', 'icon' => 'check-circle', 'text' =>
                                        'Berkas Diverifikasi'],
                                        'lulus_seleksi' => ['class' => 'success', 'icon' => 'trophy', 'text' => 'Lulus
                                        Seleksi'],
                                        'tidak_lulus_seleksi' => ['class' => 'danger', 'icon' => 'times-circle', 'text'
                                        => 'Tidak Lulus'],
                                        'mengundurkan_diri' => ['class' => 'danger', 'icon' => 'times-circle', 'text' =>
                                        'Mengundurkan Diri'],
                                        'daftar_ulang_selesai' => ['class' => 'success', 'icon' => 'user-check', 'text'
                                        => 'Daftar Ulang Selesai'],
                                        ];
                                        $status_pendaftaran = $statusConfig[$user->status_pendaftaran] ?? ['class' =>
                                        'secondary', 'icon' => 'question-circle', 'text' => 'Unknown'];
                                        @endphp
                                        <span class="badge fs-6 bg-{{ $status_pendaftaran['class'] }}"><i
                                                class="fas fa-{{ $status_pendaftaran['icon'] }} me-1"></i>{{
                                            $status_pendaftaran['text'] }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-lg-4 text-center">
                        <img src="{{ $user->berkas && $user->berkas->file_pas_foto ? asset('storage/' . $user->berkas->file_pas_foto) : 'https://via.placeholder.com/150' }}"
                            alt="Pas Foto" class="img-thumbnail mb-3"
                            style="width: 120px; height: 150px; object-fit: cover;">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                        <small class="text-muted d-block mt-2">Pindai untuk verifikasi</small>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light p-4">
                @if(!$isFinalCard && in_array($user->status_pendaftaran, ['lulus_seleksi', 'daftar_ulang_selesai']))
                <div class="alert alert-success text-center">
                    <h5 class="alert-heading fw-bold">Selamat! Anda Lulus Seleksi!</h5>
                    <p>Silakan akses dan unduh <strong>Kartu Final</strong> Anda sebagai bukti kelulusan.</p>
                    <a href="{{ route('siswa.kartu-pendaftaran.show', ['type' => 'final']) }}"
                        class="btn btn-success fw-bold"><i class="fas fa-award me-2"></i>Lihat Kartu Final</a>
                </div>
                @endif
                <div class="mt-3 text-center alert alert-warning border-warning border-2">
                    <p class="mb-0 fw-bold small"><i class="fas fa-exclamation-triangle me-2"></i>PERHATIAN: Kartu ini
                        bersifat rahasia. Jangan menyebarkan informasi dan QR Code yang tertera kepada pihak yang tidak
                        berkepentingan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
