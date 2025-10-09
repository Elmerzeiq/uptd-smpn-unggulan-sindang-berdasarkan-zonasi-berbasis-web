@extends('layouts.admin.app')
@section('title', 'Edit Data Pendaftar: ' . $user->nama_lengkap)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header mb-4">
            <h3 class="page-title fw-bold text-primary">
                <i class="bi bi-pencil-square me-2"></i> Edit Data Pendaftar
                <span class="badge bg-primary ms-2">{{ $user->nama_lengkap }}</span>
            </h3>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Terjadi Kesalahan!</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">Edit Data Pendaftar: {{ $user->nama_lengkap }}</h4>
                    </div>

                    <form action="{{ route('admin.pendaftar.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Data Utama -->
                                <div class="col-md-8">
                                    <!-- Data Utama Siswa -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Data Utama Siswa</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="nama_lengkap" class="form-label fw-semibold">Nama
                                                        Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                                                        id="nama_lengkap" name="nama_lengkap"
                                                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                                                    @error('nama_lengkap')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="nisn" class="form-label fw-semibold">NISN <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('nisn') is-invalid @enderror"
                                                        id="nisn" name="nisn" value="{{ old('nisn', $user->nisn) }}"
                                                        required>
                                                    @error('nisn')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label fw-semibold">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email" name="email" value="{{ old('email', $user->email) }}"
                                                        required>
                                                    @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="jalur_pendaftaran" class="form-label fw-semibold">Jalur
                                                        Pendaftaran <span class="text-danger">*</span></label>
                                                    <select
                                                        class="form-select @error('jalur_pendaftaran') is-invalid @enderror"
                                                        id="jalur_pendaftaran" name="jalur_pendaftaran" required>
                                                        <option value="">Pilih Jalur</option>
                                                        <option value="domisili" {{ old('jalur_pendaftaran', $user->
                                                            jalur_pendaftaran) == 'domisili' ? 'selected' : ''
                                                            }}>Domisili</option>
                                                        <option value="prestasi" {{ old('jalur_pendaftaran', $user->
                                                            jalur_pendaftaran) == 'prestasi' ? 'selected' : ''
                                                            }}>Prestasi</option>
                                                        <option value="afirmasi" {{ old('jalur_pendaftaran', $user->
                                                            jalur_pendaftaran) == 'afirmasi' ? 'selected' : ''
                                                            }}>Afirmasi</option>
                                                        <option value="mutasi" {{ old('jalur_pendaftaran', $user->
                                                            jalur_pendaftaran) == 'mutasi' ? 'selected' : '' }}>Mutasi
                                                        </option>
                                                    </select>
                                                    @error('jalur_pendaftaran')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">No. Pendaftaran</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $user->no_pendaftaran }}" readonly>
                                                    <small class="text-muted">Nomor ini tidak dapat diubah</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="status_pendaftaran"
                                                        class="form-label fw-semibold">Status Pendaftaran</label>
                                                    <select
                                                        class="form-select @error('status_pendaftaran') is-invalid @enderror"
                                                        id="status_pendaftaran" name="status_pendaftaran">
                                                        <option value="akun_terdaftar" {{ old('status_pendaftaran',
                                                            $user->status_pendaftaran) == 'akun_terdaftar' ? 'selected'
                                                            : '' }}>Akun Terdaftar</option>
                                                        <option value="menunggu_kelengkapan_data" {{
                                                            old('status_pendaftaran', $user->status_pendaftaran) ==
                                                            'menunggu_kelengkapan_data' ? 'selected' : '' }}>Menunggu
                                                            Kelengkapan Data</option>
                                                        <option value="menunggu_verifikasi_berkas" {{
                                                            old('status_pendaftaran', $user->status_pendaftaran) ==
                                                            'menunggu_verifikasi_berkas' ? 'selected' : '' }}>Menunggu
                                                            Verifikasi Berkas</option>
                                                        <option value="berkas_tidak_lengkap" {{
                                                            old('status_pendaftaran', $user->status_pendaftaran) ==
                                                            'berkas_tidak_lengkap' ? 'selected' : '' }}>Berkas Tidak
                                                            Lengkap</option>
                                                        <option value="berkas_diverifikasi" {{ old('status_pendaftaran',
                                                            $user->status_pendaftaran) == 'berkas_diverifikasi' ?
                                                            'selected' : '' }}>Berkas Diverifikasi</option>
                                                        <option value="lulus_seleksi" {{ old('status_pendaftaran',
                                                            $user->status_pendaftaran) == 'lulus_seleksi' ? 'selected' :
                                                            '' }}>Lulus Seleksi</option>
                                                        <option value="tidak_lulus_seleksi" {{ old('status_pendaftaran',
                                                            $user->status_pendaftaran) == 'tidak_lulus_seleksi' ?
                                                            'selected' : '' }}>Tidak Lulus Seleksi</option>
                                                        <option value="mengundurkan_diri" {{ old('status_pendaftaran',
                                                            $user->status_pendaftaran) == 'mengundurkan_diri' ?
                                                            'selected' : '' }}>Mengundurkan Diri</option>
                                                        <option value="daftar_ulang_selesai" {{
                                                            old('status_pendaftaran', $user->status_pendaftaran) ==
                                                            'daftar_ulang_selesai' ? 'selected' : '' }}>Daftar Ulang
                                                            Selesai</option>
                                                    </select>
                                                    @error('status_pendaftaran')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Biodata -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Data Biodata</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="tempat_lahir" class="form-label fw-semibold">Tempat
                                                        Lahir</label>
                                                    <input type="text"
                                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                        id="tempat_lahir" name="tempat_lahir"
                                                        value="{{ old('tempat_lahir', $user->biodata->tempat_lahir ?? '') }}">
                                                    @error('tempat_lahir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="tgl_lahir" class="form-label fw-semibold">Tanggal
                                                        Lahir</label>
                                                    <input type="date"
                                                        class="form-control @error('tgl_lahir') is-invalid @enderror"
                                                        id="tgl_lahir" name="tgl_lahir"
                                                        value="{{ old('tgl_lahir', $user->biodata?->tgl_lahir?->format('Y-m-d')) }}">
                                                    @error('tgl_lahir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="jns_kelamin" class="form-label fw-semibold">Jenis
                                                        Kelamin</label>
                                                    <select
                                                        class="form-select @error('jns_kelamin') is-invalid @enderror"
                                                        id="jns_kelamin" name="jns_kelamin">
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        <option value="L" {{ old('jns_kelamin', $user->
                                                            biodata->jns_kelamin ?? '') == 'L' ? 'selected' : ''
                                                            }}>Laki-laki</option>
                                                        <option value="P" {{ old('jns_kelamin', $user->
                                                            biodata->jns_kelamin ?? '') == 'P' ? 'selected' : ''
                                                            }}>Perempuan</option>
                                                    </select>
                                                    @error('jns_kelamin')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="agama" class="form-label fw-semibold">Agama</label>
                                                    <select class="form-select @error('agama') is-invalid @enderror"
                                                        id="agama" name="agama">
                                                        <option value="">Pilih Agama</option>
                                                        <option value="Islam" {{ old('agama', $user->biodata->agama ??
                                                            '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                        <option value="Kristen" {{ old('agama', $user->biodata->agama ??
                                                            '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                        <option value="Katholik" {{ old('agama', $user->biodata->agama
                                                            ?? '') == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                                                        <option value="Hindu" {{ old('agama', $user->biodata->agama ??
                                                            '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                        <option value="Buddha" {{ old('agama', $user->biodata->agama ??
                                                            '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                        <option value="Konghucu" {{ old('agama', $user->biodata->agama
                                                            ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                                    </select>
                                                    @error('agama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="asal_sekolah" class="form-label fw-semibold">Asal
                                                        Sekolah</label>
                                                    <input type="text"
                                                        class="form-control @error('asal_sekolah') is-invalid @enderror"
                                                        id="asal_sekolah" name="asal_sekolah"
                                                        value="{{ old('asal_sekolah', $user->biodata->asal_sekolah ?? '') }}">
                                                    @error('asal_sekolah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="alamat_rumah" class="form-label fw-semibold">Alamat
                                                        Rumah</label>
                                                    <textarea
                                                        class="form-control @error('alamat_rumah') is-invalid @enderror"
                                                        id="alamat_rumah" name="alamat_rumah"
                                                        rows="3">{{ old('alamat_rumah', $user->biodata->alamat_rumah ?? '') }}</textarea>
                                                    @error('alamat_rumah')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="anak_ke" class="form-label fw-semibold">Anak Ke</label>
                                                    <input type="number"
                                                        class="form-control @error('anak_ke') is-invalid @enderror"
                                                        id="anak_ke" name="anak_ke"
                                                        value="{{ old('anak_ke', $user->biodata->anak_ke ?? '') }}">
                                                    @error('anak_ke')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Catatan Admin -->
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Catatan & Verifikasi</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <label for="catatan_verifikasi"
                                                        class="form-label fw-semibold">Catatan Verifikasi</label>
                                                    <textarea
                                                        class="form-control @error('catatan_verifikasi') is-invalid @enderror"
                                                        id="catatan_verifikasi" name="catatan_verifikasi" rows="4"
                                                        placeholder="Masukkan catatan untuk siswa jika diperlukan">{{ old('catatan_verifikasi', $user->catatan_verifikasi) }}</textarea>
                                                    @error('catatan_verifikasi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Panel -->
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="card-title mb-0 text-white">
                                                <i class="bi bi-info-circle me-2"></i>Informasi Edit
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info small">
                                                <i class="bi bi-shield-check me-1"></i>
                                                <strong>Hak Admin:</strong><br>
                                                • Edit semua data siswa<br>
                                                • Ubah status pendaftaran<br>
                                                • Tambah catatan verifikasi<br>
                                                • Kelola berkas persyaratan
                                            </div>

                                            <h6 class="fw-bold">Status Saat Ini:</h6>
                                            <span
                                                class="badge bg-{{ $user->status_pendaftaran == 'lulus_seleksi' ? 'success' :
                                                (in_array($user->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap']) ? 'danger' :
                                                (in_array($user->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi']) ? 'info' : 'warning')) }} fs-6 w-100 p-2 mb-3">
                                                {{ ucwords(str_replace('_', ' ', $user->status_pendaftaran ?? 'N/A')) }}
                                            </span>

                                            <h6 class="fw-bold">Data Yang Dapat Diedit:</h6>
                                            <ul class="small">
                                                <li>Semua data pribadi siswa</li>
                                                <li>Status pendaftaran</li>
                                                <li>Jalur pendaftaran</li>
                                                <li>Catatan verifikasi</li>
                                                <li>Email akun siswa</li>
                                            </ul>

                                            <div class="mt-3">
                                                <h6 class="fw-bold">Riwayat:</h6>
                                                <p class="small mb-1"><strong>Tgl Daftar:</strong> {{
                                                    $user->created_at->format('d M Y, H:i') }}</p>
                                                @if($user->updated_at != $user->created_at)
                                                <p class="small mb-1"><strong>Update Terakhir:</strong> {{
                                                    $user->updated_at->format('d M Y, H:i') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-lightning me-2"></i>Aksi Cepat
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('admin.pendaftar.show', $user->id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-2"></i>Lihat Detail
                                                </a>
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="setStatus('berkas_diverifikasi')">
                                                    <i class="bi bi-check-circle me-2"></i>Verifikasi Berkas
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="setStatus('berkas_tidak_lengkap')">
                                                    <i class="bi bi-x-circle me-2"></i>Tolak Berkas
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="setStatus('lulus_seleksi')">
                                                    <i class="bi bi-trophy me-2"></i>Lulus Seleksi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('admin.pendaftar.show', $user->id) }}"
                                        class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </a>
                                    <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline-primary ms-2">
                                        <i class="bi bi-list me-2"></i>Daftar Pendaftar
                                    </a>
                                </div>
                                <div>
                                    <button type="reset" class="btn btn-outline-warning me-2">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
    .form-label.fw-semibold {
        font-weight: 600;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Konfirmasi sebelum reset form
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
                e.preventDefault();
            }
        });

        // Konfirmasi sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin data yang dimasukkan sudah benar?')) {
                e.preventDefault();
            }
        });
    });

    // Fungsi untuk set status cepat
    function setStatus(status) {
        document.getElementById('status_pendaftaran').value = status;

        // Auto-fill catatan berdasarkan status
        const catatanField = document.getElementById('catatan_verifikasi');
        switch(status) {
            case 'berkas_diverifikasi':
                if (!catatanField.value) {
                    catatanField.value = 'Berkas lengkap dan sesuai dengan persyaratan.';
                }
                break;
            case 'berkas_tidak_lengkap':
                if (!catatanField.value) {
                    catatanField.value = 'Berkas tidak lengkap atau tidak sesuai dengan persyaratan. Silakan lengkapi kembali.';
                }
                break;
            case 'lulus_seleksi':
                if (!catatanField.value) {
                    catatanField.value = 'Selamat! Anda lulus seleksi SPMB. Silakan melakukan daftar ulang.';
                }
                break;
        }
    }
</script>
@endpush
@endsection
