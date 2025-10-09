@extends('layouts.admin.app')

@section('title', 'Detail Siswa - ' . $siswa->nama_lengkap)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        {{-- Page Header --}}
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-user"></i> Detail Siswa
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
        <div>
            {{-- <h1 class="mb-0 text-gray-800 h3">Detail Siswa</h1> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.siswa.index') }}">Data Siswa</a></li>
                    <li class="breadcrumb-item active">{{ $siswa->nama_lengkap }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Account Information -->
            <div class="mb-4 shadow card">
                <div class="text-white card-header bg-primary">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user"></i> Informasi Akun
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Nama Lengkap:</td>
                                    <td class="font-weight-bold">{{ $siswa->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">NISN:</td>
                                    <td>
                                        @if($siswa->nisn)
                                        <span class="badge badge-info">{{ $siswa->nisn }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Email:</td>
                                    <td>
                                        {{ $siswa->email }}
                                        @if($siswa->email_verified_at)
                                        <i class="ml-1 fas fa-check-circle text-success" title="Email verified"></i>
                                        @else
                                        <i class="ml-1 fas fa-exclamation-circle text-warning"
                                            title="Email not verified"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Username:</td>
                                    <td>{{ $siswa->username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Role:</td>
                                    <td><span class="badge badge-secondary">{{ ucfirst($siswa->role) }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Jalur Pendaftaran:</td>
                                    <td><span class="badge badge-info badge-lg">{{ $siswa->jalur_pendaftaran_label
                                            }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Status Pendaftaran:</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ \App\Helpers\PpdbHelper::getStatusBadgeClass($siswa->status_pendaftaran) }} badge-lg">
                                            {{ $siswa->status_pendaftaran_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tanggal Daftar:</td>
                                    <td>
                                        {{ $siswa->created_at->format('d F Y, H:i') }}
                                        <small class="text-muted d-block">{{ $siswa->created_at->diffForHumans()
                                            }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Terakhir Update:</td>
                                    <td>
                                        {{ $siswa->updated_at->format('d F Y, H:i') }}
                                        <small class="text-muted d-block">{{ $siswa->updated_at->diffForHumans()
                                            }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">No. Pendaftaran:</td>
                                    <td>
                                        @if($siswa->no_pendaftaran)
                                        <span class="badge badge-primary">{{ $siswa->no_pendaftaran }}</span>
                                        @else
                                        <span class="text-muted">Belum generate</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($siswa->catatan_verifikasi)
                    <hr>
                    <div class="alert alert-info">
                        <h6 class="mb-2 font-weight-bold">
                            <i class="fas fa-comment-alt"></i> Catatan Verifikasi:
                        </h6>
                        <p class="mb-0">{{ $siswa->catatan_verifikasi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Personal Data -->
            @if($siswa->biodata)
            <div class="mb-4 shadow card">
                <div class="text-white card-header bg-success">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-id-card"></i> Data Pribadi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 font-weight-bold text-secondary">Identitas Diri</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Tempat Lahir:</td>
                                    <td>{{ $siswa->biodata->tempat_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tanggal Lahir:</td>
                                    <td>
                                        @if($siswa->biodata->tgl_lahir)
                                        {{ \Carbon\Carbon::parse($siswa->biodata->tgl_lahir)->format('d F Y') }}
                                        <small class="text-muted d-block">
                                            Umur: {{ \Carbon\Carbon::parse($siswa->biodata->tgl_lahir)->age }} tahun
                                        </small>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Jenis Kelamin:</td>
                                    <td>
                                        @if($siswa->biodata->jns_kelamin)
                                        <span
                                            class="badge badge-{{ $siswa->biodata->jns_kelamin == 'L' ? 'primary' : 'pink' }}">
                                            {{ $siswa->biodata->jns_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Agama:</td>
                                    <td>{{ $siswa->biodata->agama ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3 font-weight-bold text-secondary">Keluarga & Fisik</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Anak Ke-:</td>
                                    <td>{{ $siswa->biodata->anak_ke ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Jumlah Saudara:</td>
                                    <td>{{ $siswa->biodata->jml_saudara_kandung ?? '-' }} orang</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tinggi Badan:</td>
                                    <td>{{ $siswa->biodata->tinggi_badan ? $siswa->biodata->tinggi_badan . ' cm' : '-'
                                        }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Berat Badan:</td>
                                    <td>{{ $siswa->biodata->berat_badan ? $siswa->biodata->berat_badan . ' kg' : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Lingkar Kepala:</td>
                                    <td>{{ $siswa->biodata->lingkar_kepala ? $siswa->biodata->lingkar_kepala . ' cm' :
                                        '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School & Address Data -->
            <div class="mb-4 shadow card">
                <div class="text-white card-header bg-info">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-school"></i> Data Sekolah & Alamat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-3 font-weight-bold text-secondary">Asal Sekolah</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 20%;">Nama Sekolah:</td>
                                    <td>{{ $siswa->biodata->asal_sekolah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">NPSN:</td>
                                    <td>{{ $siswa->biodata->npsn_asal_sekolah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Alamat Sekolah:</td>
                                    <td>{{ $siswa->biodata->alamat_asal_sekolah ?? '-' }}</td>
                                </tr>
                            </table>

                            <hr>

                            <h6 class="mb-3 font-weight-bold text-secondary">Alamat Domisili</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 20%;">Alamat Lengkap:</td>
                                    <td>{{ $siswa->biodata->alamat_rumah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Kelurahan/Desa:</td>
                                    <td>{{ $siswa->biodata->desa_kelurahan_domisili ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">RT/RW:</td>
                                    <td>
                                        RT {{ $siswa->biodata->rt ?? '-' }} / RW {{ $siswa->biodata->rw ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Kode Pos:</td>
                                    <td>{{ $siswa->biodata->kode_pos ?? '-' }}</td>
                                </tr>
                                @if($siswa->biodata->koordinat_rumah)
                                <tr>
                                    <td class="font-weight-bold text-muted">Koordinat:</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $siswa->biodata->koordinat_rumah
                                            }}</span>
                                        <a href="https://maps.google.com/maps?q={{ $siswa->biodata->koordinat_rumah }}"
                                            target="_blank" class="ml-2 btn btn-sm btn-outline-primary">
                                            <i class="fas fa-map-marker-alt"></i> Lihat di Maps
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            @if($siswa->kecamatan_domisili || $siswa->jarak_ke_sekolah)
                            <hr>
                            <h6 class="mb-3 font-weight-bold text-secondary">Zonasi & Jarak</h6>
                            <table class="table table-borderless table-sm">
                                @if($siswa->kecamatan_domisili)
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 20%;">Kecamatan:</td>
                                    <td>{{ $siswa->kecamatan_domisili }}</td>
                                </tr>
                                @endif
                                @if($siswa->jarak_ke_sekolah)
                                <tr>
                                    <td class="font-weight-bold text-muted">Jarak ke Sekolah:</td>
                                    <td>
                                        <span class="badge badge-info">{{ $siswa->jarak_ke_sekolah }} km</span>
                                    </td>
                                </tr>
                                @endif
                                @if($siswa->peringkat_zonasi)
                                <tr>
                                    <td class="font-weight-bold text-muted">Peringkat Zonasi:</td>
                                    <td>
                                        <span class="badge badge-warning">{{ $siswa->peringkat_zonasi }}</span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Parent Data -->
            @if($siswa->orangTua)
            <div class="mb-4 shadow card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-users"></i> Data Orang Tua
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Mother Data -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 font-weight-bold text-secondary">
                                <i class="fas fa-female"></i> Data Ibu
                            </h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Nama:</td>
                                    <td class="font-weight-bold">{{ $siswa->orangTua->nama_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">NIK:</td>
                                    <td>{{ $siswa->orangTua->nik_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tempat Lahir:</td>
                                    <td>{{ $siswa->orangTua->tempat_lahir_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tanggal Lahir:</td>
                                    <td>
                                        @if($siswa->orangTua->tgl_lahir_ibu)
                                        {{ \Carbon\Carbon::parse($siswa->orangTua->tgl_lahir_ibu)->format('d F Y') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Pendidikan:</td>
                                    <td>
                                        @if($siswa->orangTua->pendidikan_ibu)
                                        <span class="badge badge-light">{{ $siswa->orangTua->pendidikan_ibu }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Pekerjaan:</td>
                                    <td>{{ $siswa->orangTua->pekerjaan_ibu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Penghasilan:</td>
                                    <td>
                                        @if($siswa->orangTua->penghasilan_ibu)
                                        @php
                                        $penghasilanLabels = [
                                        '<2juta'=> 'Kurang dari Rp 2.000.000',
                                            '2-5juta' => 'Rp 2.000.000 - Rp 5.000.000',
                                            '5-10juta' => 'Rp 5.000.000 - Rp 10.000.000',
                                            '>10juta' => 'Lebih dari Rp 10.000.000'
                                            ];
                                            @endphp
                                            <span class="badge badge-success">
                                                {{ $penghasilanLabels[$siswa->orangTua->penghasilan_ibu] ??
                                                $siswa->orangTua->penghasilan_ibu }}
                                            </span>
                                            @else
                                            -
                                            @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">No. HP:</td>
                                    <td>
                                        @if($siswa->orangTua->no_hp_ibu)
                                        <a href="tel:{{ $siswa->orangTua->no_hp_ibu }}" class="text-decoration-none">
                                            <i class="fas fa-phone text-success"></i> {{ $siswa->orangTua->no_hp_ibu }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Father Data -->
                        <div class="col-md-6">
                            <h6 class="mb-3 font-weight-bold text-secondary">
                                <i class="fas fa-male"></i> Data Ayah
                            </h6>
                            @if($siswa->orangTua->nama_ayah)
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Nama:</td>
                                    <td class="font-weight-bold">{{ $siswa->orangTua->nama_ayah }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">NIK:</td>
                                    <td>{{ $siswa->orangTua->nik_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tempat Lahir:</td>
                                    <td>{{ $siswa->orangTua->tempat_lahir_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tanggal Lahir:</td>
                                    <td>
                                        @if($siswa->orangTua->tgl_lahir_ayah)
                                        {{ \Carbon\Carbon::parse($siswa->orangTua->tgl_lahir_ayah)->format('d F Y') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Pendidikan:</td>
                                    <td>
                                        @if($siswa->orangTua->pendidikan_ayah)
                                        <span class="badge badge-light">{{ $siswa->orangTua->pendidikan_ayah }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Pekerjaan:</td>
                                    <td>{{ $siswa->orangTua->pekerjaan_ayah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Penghasilan:</td>
                                    <td>
                                        @if($siswa->orangTua->penghasilan_ayah)
                                        @php
                                        $penghasilanLabels = [
                                        '<2juta'=> 'Kurang dari Rp 2.000.000',
                                            '2-5juta' => 'Rp 2.000.000 - Rp 5.000.000',
                                            '5-10juta' => 'Rp 5.000.000 - Rp 10.000.000',
                                            '>10juta' => 'Lebih dari Rp 10.000.000'
                                            ];
                                            @endphp
                                            <span class="badge badge-success">
                                                {{ $penghasilanLabels[$siswa->orangTua->penghasilan_ayah] ??
                                                $siswa->orangTua->penghasilan_ayah }}
                                            </span>
                                            @else
                                            -
                                            @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">No. HP:</td>
                                    <td>
                                        @if($siswa->orangTua->no_hp_ayah)
                                        <a href="tel:{{ $siswa->orangTua->no_hp_ayah }}" class="text-decoration-none">
                                            <i class="fas fa-phone text-success"></i> {{ $siswa->orangTua->no_hp_ayah }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @else
                            <div class="text-center alert alert-light">
                                <i class="fas fa-info-circle text-muted"></i>
                                <p class="mb-0 text-muted">Data ayah tidak diisi</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Wali Data -->
            @if($siswa->wali && $siswa->wali->nama_wali)
            <div class="mb-4 shadow card">
                <div class="text-white card-header bg-secondary">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-friends"></i> Data Wali
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">Nama Wali:</td>
                                    <td class="font-weight-bold">{{ $siswa->wali->nama_wali }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">NIK:</td>
                                    <td>{{ $siswa->wali->nik_wali ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Hubungan:</td>
                                    <td>
                                        <span class="badge badge-info">{{ $siswa->wali->hubungan_wali_dgn_siswa ?? '-'
                                            }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold text-muted" style="width: 40%;">No. HP:</td>
                                    <td>
                                        @if($siswa->wali->no_hp_wali)
                                        <a href="tel:{{ $siswa->wali->no_hp_wali }}" class="text-decoration-none">
                                            <i class="fas fa-phone text-success"></i> {{ $siswa->wali->no_hp_wali }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Alamat:</td>
                                    <td>{{ $siswa->wali->alamat_wali ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents Status -->
            @if($siswa->berkas)
            <div class="mb-4 shadow card">
                <div class="text-white card-header bg-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-folder-open"></i> Status Berkas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3 font-weight-bold text-secondary">Status Verifikasi</h6>
                            <p>
                                <span class="badge badge-{{ $siswa->berkas->getStatusBadgeClass() }} badge-lg">
                                    {{ ucfirst($siswa->berkas->status_verifikasi ?? 'pending') }}
                                </span>
                            </p>
                            @if($siswa->berkas->verified_at)
                            <p class="text-muted">
                                <i class="fas fa-check-circle text-success"></i>
                                Diverifikasi pada {{ $siswa->berkas->verified_at->format('d F Y, H:i') }}
                                @if($siswa->berkas->verifier)
                                oleh {{ $siswa->berkas->verifier->nama_lengkap }}
                                @endif
                            </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3 font-weight-bold text-secondary">Progress Upload</h6>
                            @php
                            $completionPercentage = $siswa->berkas->getCompletionPercentage();
                            @endphp
                            <div class="mb-2 progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped 
                                    {{ $completionPercentage == 100 ? 'bg-success' : ($completionPercentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                    role="progressbar" style="width: {{ $completionPercentage }}%">
                                    {{ $completionPercentage }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ count($siswa->berkas->getRequiredDocuments()) -
                                count($siswa->berkas->getMissingDocuments()) }}
                                dari {{ count($siswa->berkas->getRequiredDocuments()) }} dokumen terupload
                            </small>
                        </div>
                    </div>

                    @if($siswa->berkas->catatan_verifikasi)
                    <hr>
                    <div class="alert alert-info">
                        <h6 class="mb-2 font-weight-bold">
                            <i class="fas fa-comment"></i> Catatan Verifikasi Berkas:
                        </h6>
                        <p class="mb-0">{{ $siswa->berkas->catatan_verifikasi }}</p>
                    </div>
                    @endif

                    @if(!empty($siswa->berkas->getMissingDocuments()))
                    <hr>
                    <h6 class="mb-3 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Dokumen yang Belum Diupload:
                    </h6>
                    <ul class="list-unstyled">
                        @foreach($siswa->berkas->getMissingDocuments() as $field => $label)
                        <li class="mb-1">
                            <i class="fas fa-times text-danger"></i> {{ $label }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Photo -->
            <div class="mb-4 shadow card">
                <div class="text-white card-header bg-primary">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-camera"></i> Foto Siswa
                    </h6>
                </div>
                <div class="text-center card-body">
                    @if($siswa->biodata && $siswa->biodata->foto_siswa)
                    <img src="{{ asset('storage/' . $siswa->biodata->foto_siswa) }}" class="shadow img-thumbnail"
                        style="max-width: 250px;">
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            File: {{ basename($siswa->biodata->foto_siswa) }}
                        </small>
                    </div>
                    @else
                    <div class="py-5 text-muted">
                        <i class="fas fa-user fa-5x text-muted"></i>
                        <p class="mt-3 mb-0">Foto belum diupload</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-4 shadow card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> Edit Data Siswa
                    </a>

                    @if($siswa->status_pendaftaran === 'menunggu_verifikasi_berkas')
                    <button class="btn btn-success btn-block" onclick="updateStatus('berkas_diverifikasi')">
                        <i class="fas fa-check"></i> Verifikasi Berkas
                    </button>
                    <button class="btn btn-danger btn-block" onclick="updateStatus('berkas_tidak_lengkap')">
                        <i class="fas fa-times"></i> Tolak Berkas
                    </button>
                    @endif

                    @if($siswa->status_pendaftaran === 'berkas_diverifikasi')
                    <button class="btn btn-success btn-block" onclick="updateStatus('lulus_seleksi')">
                        <i class="fas fa-graduation-cap"></i> Lulus Seleksi
                    </button>
                    <button class="btn btn-danger btn-block" onclick="updateStatus('tidak_lulus_seleksi')">
                        <i class="fas fa-times-circle"></i> Tidak Lulus
                    </button>
                    @endif

                    @if(!$siswa->no_pendaftaran)
                    <button class="btn btn-info btn-block" onclick="generateNomorPendaftaran()">
                        <i class="fas fa-hashtag"></i> Generate No. Pendaftaran
                    </button>
                    @endif

                    <hr>

                    <!-- Communication Actions -->
                    @if($siswa->orangTua && ($siswa->orangTua->no_hp_ibu || $siswa->orangTua->no_hp_ayah))
                    <div class="dropdown">
                        <button class="btn btn-outline-success btn-block dropdown-toggle" type="button"
                            data-toggle="dropdown">
                            <i class="fas fa-phone"></i> Hubungi Orang Tua
                        </button>
                        <div class="dropdown-menu">
                            @if($siswa->orangTua->no_hp_ibu)
                            <a class="dropdown-item" href="tel:{{ $siswa->orangTua->no_hp_ibu }}">
                                <i class="fas fa-phone text-success"></i> Ibu: {{ $siswa->orangTua->no_hp_ibu }}
                            </a>
                            @endif
                            @if($siswa->orangTua->no_hp_ayah)
                            <a class="dropdown-item" href="tel:{{ $siswa->orangTua->no_hp_ayah }}">
                                <i class="fas fa-phone text-success"></i> Ayah: {{ $siswa->orangTua->no_hp_ayah }}
                            </a>
                            @endif
                            @if($siswa->wali && $siswa->wali->no_hp_wali)
                            <a class="dropdown-item" href="tel:{{ $siswa->wali->no_hp_wali }}">
                                <i class="fas fa-phone text-success"></i> Wali: {{ $siswa->wali->no_hp_wali }}
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <hr>

                    <button class="btn btn-outline-danger btn-block" onclick="deleteStudent()">
                        <i class="fas fa-trash"></i> Hapus Siswa
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="shadow card">
                <div class="text-white card-header bg-info">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar"></i> Statistik & Info
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Akun dibuat:</td>
                            <td>{{ $siswa->created_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terakhir update:</td>
                            <td>{{ $siswa->updated_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email verified:</td>
                            <td>
                                @if($siswa->email_verified_at)
                                <i class="fas fa-check text-success"></i>
                                <small class="text-muted">{{ $siswa->email_verified_at->diffForHumans() }}</small>
                                @else
                                <i class="fas fa-times text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status daftar ulang:</td>
                            <td>
                                @if($siswa->status_daftar_ulang)
                                <span
                                    class="badge badge-{{ \App\Helpers\PpdbHelper::getStatusDaftarUlangBadgeClass($siswa->status_daftar_ulang) }}">
                                    {{ ucfirst(str_replace('_', ' ', $siswa->status_daftar_ulang)) }}
                                </span>
                                @else
                                <span class="text-muted">Belum daftar ulang</span>
                                @endif
                            </td>
                        </tr>
                        @if($siswa->biodata && $siswa->biodata->koordinat_rumah)
                        <tr>
                            <td class="text-muted">Koordinat:</td>
                            <td>
                                <i class="fas fa-map-marker-alt text-info"></i>
                                <small>Tersedia</small>
                            </td>
                        </tr>
                        @endif
                    </table>

                    @if($siswa->biodata)
                    <hr>
                    <h6 class="font-weight-bold text-secondary">Data Completion</h6>
                    <div class="text-center row">
                        <div class="col-4">
                            <div class="text-primary">
                                <i class="fas fa-user fa-2x"></i>
                                <div class="mt-1">
                                    <small>Biodata</small>
                                    <div class="text-success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <i class="fas fa-users fa-2x"></i>
                                <div class="mt-1">
                                    <small>Ortu</small>
                                    <div class="{{ $siswa->orangTua ? 'text-success' : 'text-danger' }}">
                                        <i class="fas {{ $siswa->orangTua ? 'fa-check' : 'fa-times' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info">
                                <i class="fas fa-folder fa-2x"></i>
                                <div class="mt-1">
                                    <small>Berkas</small>
                                    <div
                                        class="{{ $siswa->berkas && $siswa->berkas->hasAnyFile() ? 'text-success' : 'text-danger' }}">
                                        <i
                                            class="fas {{ $siswa->berkas && $siswa->berkas->hasAnyFile() ? 'fa-check' : 'fa-times' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Siswa</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusForm" method="POST" action="{{ route('admin.siswa.update', $siswa) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="quick_update" value="1">
                    <input type="hidden" name="status_pendaftaran" id="new_status">

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Status akan diubah untuk siswa: <strong>{{ $siswa->nama_lengkap }}</strong>
                    </div>

                    <div class="form-group">
                        <label for="catatan_verifikasi">Catatan Verifikasi:</label>
                        <textarea class="form-control" name="catatan_verifikasi" rows="3"
                            placeholder="Tambahkan catatan terkait perubahan status...">{{ $siswa->catatan_verifikasi }}</textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan terlihat oleh siswa dan dapat membantu mereka memahami status pendaftaran.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateStatus(status) {
    $('#new_status').val(status);
    
    // Update modal title and content based on status
    let statusLabels = {
        'berkas_diverifikasi': 'Verifikasi Berkas',
        'berkas_tidak_lengkap': 'Tolak Berkas',
        'lulus_seleksi': 'Lulus Seleksi',
        'tidak_lulus_seleksi': 'Tidak Lulus Seleksi'
    };
    
    $('.modal-title').text('Update Status: ' + (statusLabels[status] || status));
    $('#statusModal').modal('show');
}

function deleteStudent() {
    if (confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus siswa "{{ $siswa->nama_lengkap }}"?\n\nSemua data termasuk biodata, berkas, dan histori akan terhapus permanen dan tidak dapat dikembalikan.\n\nKetik "HAPUS" untuk konfirmasi:')) {
        let confirmation = prompt('Ketik "HAPUS" (tanpa tanda kutip) untuk mengkonfirmasi penghapusan:');
        if (confirmation === 'HAPUS') {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.siswa.destroy", $siswa) }}';
            
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        } else {
            alert('Konfirmasi tidak sesuai. Penghapusan dibatalkan.');
        }
    }
}

function generateNomorPendaftaran() {
    if (confirm('Generate nomor pendaftaran untuk siswa ini?')) {
        // Implementation untuk generate nomor pendaftaran
        // Bisa menggunakan AJAX atau form submission
        alert('Fitur generate nomor pendaftaran akan diimplementasikan.');
    }
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut();
}, 5000);

// Tooltip initialization
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// Print functionality
function printStudentData() {
    window.print();
}

// Add print button
$(document).ready(function() {
    $('.btn-group').append(`
        <button type="button" class="btn btn-outline-secondary" onclick="printStudentData()" title="Print Data">
            <i class="fas fa-print"></i>
        </button>
    `);
});
</script>

<style>
    @media print {

        .btn,
        .card-header,
        .breadcrumb,
        .modal,
        .dropdown-menu {
            display: none !important;
        }

        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        .card-body {
            padding: 15px !important;
        }

        .badge {
            border: 1px solid #000;
            color: #000 !important;
            background: white !important;
        }
    }

    .badge-lg {
        font-size: 0.9em;
        padding: 0.5em 0.8em;
    }

    .table-borderless td {
        border: none !important;
        padding: 0.3rem 0.5rem;
    }

    .card-header {
        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
@endsection