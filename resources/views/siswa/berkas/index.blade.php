@extends('layouts.siswa.app')

@section('title', 'Upload Berkas Persyaratan SPMB')
@section('title_header_siswa', 'Upload Berkas')

@section('siswa_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Upload Berkas Persyaratan</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('siswa.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>

        {{-- Catatan Verifikasi dari Admin --}}
        @if($user->status_pendaftaran === 'berkas_tidak_lengkap' && $user->catatan_verifikasi)
        <div class="alert alert-warning shadow-sm mt-3" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian! Pendaftaran Anda Perlu
                Diperbaiki</h4>
            <p>Admin telah melakukan verifikasi dan menemukan beberapa hal yang perlu Anda perbaiki. Silakan periksa
                kembali berkas Anda.</p>
            <hr>
            <p class="mb-0"><strong>Catatan dari Admin:</strong> {{ $user->catatan_verifikasi }}</p>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                {{-- Peringatan Jika Upload Tidak Diizinkan --}}
                @if (!$allowUpload)
                <div class="alert alert-info shadow-sm" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi</h4>
                    @if (in_array($user->status_pendaftaran, ['berkas_diverifikasi', 'lulus_seleksi',
                    'tidak_lulus_seleksi', 'daftar_ulang_selesai']))
                    Data pendaftaran Anda sudah selesai diproses atau statusnya sudah final. Anda tidak dapat mengubah
                    berkas lagi.
                    @else
                    Periode untuk mengupload atau mengubah berkas saat ini tidak aktif. Silakan periksa jadwal SPMB.
                    @endif
                </div>
                @endif

                {{-- Card Utama --}}
                <div class="card shadow-lg">
                    <div class="card-header">
                        <div class="card-title">Formulir Upload Berkas - Jalur: <strong>{{ ucwords(str_replace('_', ' ',
                                $jalur)) }}</strong></div>
                        <div class="card-category">
                            Pastikan file yang diupload jelas, tidak buram, dan sesuai dengan persyaratan.
                        </div>
                    </div>

                    {{-- Form hanya ditampilkan jika upload diizinkan --}}
                    @if ($allowUpload)
                    <form action="{{ route('siswa.berkas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            {{-- Progress Bar --}}
                            <div class="mb-4">
                                <p class="mb-1">Kelengkapan Berkas Wajib: <strong>{{
                                        $progressBerkas['berkas_wajib_terupload'] }} dari {{
                                        $progressBerkas['berkas_wajib'] }}</strong></p>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar progress-bar-striped {{ $progressBerkas['percentage_wajib'] == 100 ? 'bg-success' : 'bg-primary' }}"
                                        role="progressbar" style="width: {{ $progressBerkas['percentage_wajib'] }}%"
                                        aria-valuenow="{{ $progressBerkas['percentage_wajib'] }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $progressBerkas['percentage_wajib'] }}%
                                    </div>
                                </div>
                                @if($progressBerkas['is_complete'])
                                <small class="form-text text-success mt-1"><i class="fas fa-check-circle"></i> Semua
                                    berkas wajib telah terupload!</small>
                                @endif
                            </div>

                            {{-- Daftar Berkas Umum --}}
                            <h5 class="mb-3 fw-bold">Berkas Umum</h5>
                            @php $nomor = 1; @endphp
                            @foreach ($daftarBerkas as $field => $details)
                            @if($details['category'] === 'common')
                            @php
                            $isUploaded = $berkasPendaftar && !empty($berkasPendaftar->$field);
                            if ($isUploaded && isset($details['multiple'])) {
                            $isUploaded = !empty(json_decode($berkasPendaftar->$field, true));
                            }
                            $borderColor = $details['required'] ? ($isUploaded ? 'border-success' : 'border-danger') :
                            'border-light';
                            @endphp
                            <div class="form-group mb-4 p-3 border rounded {{ $borderColor }}">
                                <label for="{{ $field }}" class="form-label fw-bold">
                                    {{ $nomor++ }}. {{ $details['label'] }}
                                    @if($details['required']) <span class="text-danger">*</span> @else <span
                                        class="text-info">(Opsional)</span> @endif
                                </label>
                                <p class="text-muted small mb-2">{{ $details['keterangan'] }}</p>

                                {{-- Tampilan File yang Sudah Diupload --}}
                                @if($isUploaded)
                                <div class="mb-2">
                                    <p class="mb-1 small fw-medium text-success"><i
                                            class="fas fa-check-circle me-1"></i>File Terupload:</p>
                                    @if(isset($details['multiple']) && $details['multiple'])
                                    <ul class="list-group list-group-sm">
                                        @foreach(json_decode($berkasPendaftar->$field, true) as $index => $filePath)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="{{ Storage::url($filePath) }}" target="_blank"
                                                class="text-primary">{{ basename($filePath) }}</a>
                                            <form action="{{ route('siswa.berkas.deleteFile', $field) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                                @csrf
                                                <input type="hidden" name="file_index" value="{{ $index }}">
                                                <button type="submit" class="btn btn-danger btn-xs btn-link p-0 ms-2"
                                                    title="Hapus file ini"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <a href="{{ Storage::url($berkasPendaftar->$field) }}" target="_blank"
                                        class="text-primary">{{ basename($berkasPendaftar->$field) }}</a>
                                    <form action="{{ route('siswa.berkas.deleteFile', $field) }}" method="POST"
                                        class="d-inline ms-2"
                                        onsubmit="return confirm('Yakin ingin menghapus dan mengganti file ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs btn-link p-0"
                                            title="Hapus/Ganti File"><i class="fas fa-times-circle"></i></button>
                                    </form>
                                    @endif
                                </div>
                                @endif

                                {{-- Input File --}}
                                <input type="file"
                                    class="form-control @error($field) is-invalid @enderror @error($field.'.*') is-invalid @enderror"
                                    id="{{ $field }}"
                                    name="{{ $field }}{{ (isset($details['multiple']) && $details['multiple']) ? '[]' : '' }}"
                                    {{ (isset($details['multiple']) && $details['multiple']) ? 'multiple' : '' }}
                                    accept=".pdf,.jpg,.jpeg,.png">

                                @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @error($field.'.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif
                            @endforeach

                            {{-- Daftar Berkas Agama (jika Islam) --}}
                            @if($user->biodata && in_array(strtolower($user->biodata->agama), ['islam', 'muslim']))
                            <h5 class="mb-3 fw-bold">Berkas Agama</h5>
                            @foreach ($daftarBerkas as $field => $details)
                            @if($details['category'] === 'religious')
                            @php
                            $isUploaded = $berkasPendaftar && !empty($berkasPendaftar->$field);
                            if ($isUploaded && isset($details['multiple'])) {
                            $isUploaded = !empty(json_decode($berkasPendaftar->$field, true));
                            }
                            $borderColor = $details['required'] ? ($isUploaded ? 'border-success' : 'border-danger') :
                            'border-light';
                            @endphp
                            <div class="form-group mb-4 p-3 border rounded {{ $borderColor }}">
                                <label for="{{ $field }}" class="form-label fw-bold">
                                    {{ $nomor++ }}. {{ $details['label'] }}
                                    @if($details['required']) <span class="text-danger">*</span> @else <span
                                        class="text-info">(Opsional)</span> @endif
                                </label>
                                <p class="text-muted small mb-2">{{ $details['keterangan'] }}</p>

                                {{-- Tampilan File yang Sudah Diupload --}}
                                @if($isUploaded)
                                <div class="mb-2">
                                    <p class="mb-1 small fw-medium text-success"><i
                                            class="fas fa-check-circle me-1"></i>File Terupload:</p>
                                    <a href="{{ Storage::url($berkasPendaftar->$field) }}" target="_blank"
                                        class="text-primary">{{ basename($berkasPendaftar->$field) }}</a>
                                    <form action="{{ route('siswa.berkas.deleteFile', $field) }}" method="POST"
                                        class="d-inline ms-2"
                                        onsubmit="return confirm('Yakin ingin menghapus dan mengganti file ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs btn-link p-0"
                                            title="Hapus/Ganti File"><i class="fas fa-times-circle"></i></button>
                                    </form>
                                </div>
                                @endif

                                {{-- Input File --}}
                                <input type="file" class="form-control @error($field) is-invalid @enderror"
                                    id="{{ $field }}" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png">

                                @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif
                            @endforeach
                            @endif

                            {{-- Daftar Berkas Jalur --}}
                            <h5 class="mb-3 fw-bold">Berkas Jalur {{ ucwords(str_replace('_', ' ', $jalur)) }}</h5>
                            @foreach ($daftarBerkas as $field => $details)
                            @if($details['category'] === 'jalur')
                            @php
                            $isRequired = $details['required'];
                            if ($field === 'file_suket_domisili' && $jalur === 'domisili') {
                            $isRequired = true; // Override untuk jalur domisili
                            }
                            $isUploaded = $berkasPendaftar && !empty($berkasPendaftar->$field);
                            if ($isUploaded && isset($details['multiple'])) {
                            $isUploaded = !empty(json_decode($berkasPendaftar->$field, true));
                            }
                            $borderColor = $isRequired ? ($isUploaded ? 'border-success' : 'border-danger') :
                            'border-light';
                            @endphp
                            <div class="form-group mb-4 p-3 border rounded {{ $borderColor }}">
                                <label for="{{ $field }}" class="form-label fw-bold">
                                    {{ $nomor++ }}. {{ $details['label'] }}
                                    @if($isRequired) <span class="text-danger">*</span> @else <span
                                        class="text-info">(Opsional)</span> @endif
                                </label>
                                <p class="text-muted small mb-2">{{ $details['keterangan'] }}</p>

                                {{-- Tampilan File yang Sudah Diupload --}}
                                @if($isUploaded)
                                <div class="mb-2">
                                    <p class="mb-1 small fw-medium text-success"><i
                                            class="fas fa-check-circle me-1"></i>File Terupload:</p>
                                    @if(isset($details['multiple']) && $details['multiple'])
                                    <ul class="list-group list-group-sm">
                                        @foreach(json_decode($berkasPendaftar->$field, true) as $index => $filePath)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="{{ Storage::url($filePath) }}" target="_blank"
                                                class="text-primary">{{ basename($filePath) }}</a>
                                            <form action="{{ route('siswa.berkas.deleteFile', $field) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                                @csrf
                                                <input type="hidden" name="file_index" value="{{ $index }}">
                                                <button type="submit" class="btn btn-danger btn-xs btn-link p-0 ms-2"
                                                    title="Hapus file ini"><i class="fas fa-times-circle"></i></button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <a href="{{ Storage::url($berkasPendaftar->$field) }}" target="_blank"
                                        class="text-primary">{{ basename($berkasPendaftar->$field) }}</a>
                                    <form action="{{ route('siswa.berkas.deleteFile', $field) }}" method="POST"
                                        class="d-inline ms-2"
                                        onsubmit="return confirm('Yakin ingin menghapus dan mengganti file ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs btn-link p-0"
                                            title="Hapus/Ganti File"><i class="fas fa-times-circle"></i></button>
                                    </form>
                                    @endif
                                </div>
                                @endif

                                {{-- Input File --}}
                                <input type="file"
                                    class="form-control @error($field) is-invalid @enderror @error($field.'.*') is-invalid @enderror"
                                    id="{{ $field }}"
                                    name="{{ $field }}{{ (isset($details['multiple']) && $details['multiple']) ? '[]' : '' }}"
                                    {{ (isset($details['multiple']) && $details['multiple']) ? 'multiple' : '' }}
                                    accept=".pdf,.jpg,.jpeg,.png">

                                @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @error($field.'.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn btn-primary btn-round">
                                <i class="fas fa-upload me-2"></i>Upload / Perbarui Berkas
                            </button>
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-round">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                    @else
                    {{-- Tampilan Read-only jika upload tidak diizinkan --}}
                    <div class="card-body">
                        <p>Anda hanya dapat melihat berkas yang sudah terupload.</p>
                        @foreach ($daftarBerkas as $field => $details)
                        @if($berkasPendaftar && !empty($berkasPendaftar->$field))
                        @php
                        $isRequired = $details['required'];
                        if ($field === 'file_suket_domisili' && $jalur === 'domisili') {
                        $isRequired = true; // Override untuk jalur domisili
                        }
                        @endphp
                        <div class="form-group">
                            <label class="form-label fw-bold">{{ $details['label'] }}
                                @if($isRequired) <span class="text-danger">*</span> @else <span
                                    class="text-info">(Opsional)</span> @endif
                            </label>
                            <div>
                                @if(isset($details['multiple']) && $details['multiple'])
                                @foreach(json_decode($berkasPendaftar->$field, true) as $filePath)
                                <a href="{{ Storage::url($filePath) }}" target="_blank"
                                    class="btn btn-info btn-sm mb-1">{{ basename($filePath) }}</a>
                                @endforeach
                                @else
                                <a href="{{ Storage::url($berkasPendaftar->$field) }}" target="_blank"
                                    class="btn btn-info btn-sm">{{ basename($berkasPendaftar->$field) }}</a>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts_siswa')
<style>
    .form-group.border-success {
        border-left: 4px solid #28a745 !important;
        background-color: #f8f9fa;
    }

    .form-group.border-danger {
        border-left: 4px solid #dc3545 !important;
    }

    .btn-xs {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }
</style>
@endpush
