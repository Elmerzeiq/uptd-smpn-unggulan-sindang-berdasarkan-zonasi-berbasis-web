@extends('layouts.admin.app')
@section('title', 'Detail Pendaftar - ' . $user->nama_lengkap)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Pendaftar</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="{{ route('admin.pendaftar.index') }}">Data Pendaftar</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item active">Detail</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($user->biodata && $user->biodata->foto_siswa &&
                            Storage::exists($user->biodata->foto_siswa))
                            <img src="{{ Storage::url($user->biodata->foto_siswa) }}" alt="Foto Siswa"
                                class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white"
                                style="width: 100px; height: 100px; font-size: 2rem; font-weight: bold;">
                                {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                            </div>
                            @endif
                        </div>

                        <table class="table table-borderless table-sm">
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>{{ $user->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>{{ $user->nisn }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Pendaftaran</strong></td>
                                <td><span class="badge bg-primary">{{ $user->no_pendaftaran }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Jalur Pendaftaran</strong></td>
                                <td><span class="badge bg-info">{{ ucwords(str_replace('_', ' ',
                                        $user->jalur_pendaftaran)) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP</strong></td>
                                <td>{{ $user->no_hp }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Berkas Persyaratan
                            <span class="badge bg-secondary ms-2">Jalur {{ ucwords(str_replace('_', ' ',
                                $user->jalur_pendaftaran)) }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->berkas)
                        <div class="row g-3">
                            @foreach($berkasList as $field => $details)
                            @php
                            $isRequired = isset($details['required']) ? $details['required'] : false;
                            if ($field === 'file_ijazah_mda_pernyataan' || $field === 'file_suket_baca_quran_mda') {
                            $isRequired = $user->biodata && strtolower($user->biodata->agama) === 'islam';
                            }
                            @endphp
                            <div class="col-md-6">
                                <div
                                    class="card h-100 {{ $user->berkas->$field ? 'border-success' : ($isRequired ? 'border-danger' : 'border-warning') }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-1">{{ $details['label'] }}</h6>
                                                <small class="{{ $isRequired ? 'text-danger' : 'text-muted' }}">
                                                    ({{ $isRequired ? 'WAJIB' : 'Opsional' }})
                                                </small>
                                            </div>
                                            <div>
                                                @if($user->berkas->$field)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Ada
                                                </span>
                                                @else
                                                <span class="badge {{ $isRequired ? 'bg-danger' : 'bg-warning' }}">
                                                    <i class="bi bi-x-circle me-1"></i>Belum
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($user->berkas->$field)
                                        @if(isset($details['multiple']) && $details['multiple'])
                                        <?php $sertifikatPaths = json_decode($user->berkas->$field, true) ?: [$user->berkas->$field]; ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($sertifikatPaths as $index => $path)
                                            <a href="{{ Storage::url($path) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> File {{ $index + 1 }}
                                            </a>
                                            @endforeach
                                        </div>
                                        @else
                                        <a href="{{ Storage::url($user->berkas->$field) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> Lihat File
                                        </a>
                                        @endif
                                        @else
                                        <span class="text-muted small"><i>Belum diupload</i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-folder-x fs-1"></i>
                            <h5 class="mt-2">Belum Ada Berkas</h5>
                            <p>Siswa belum mengupload berkas apapun.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
