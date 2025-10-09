@extends('layouts.admin.app')

@section('title', 'Profil Sekolah - Cerulean School')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-school"> Profil Sekolah - Cerulean School</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Profil Sekolah</h4>
                    </div>
                    <div class="card-body">
                        {{-- Notifikasi sudah dihandle di app.blade.php --}}
                        <form action="{{ route('admin.profil-sekolah.update', $profil->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Informasi Dasar Sekolah -->
                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>

                                <div class="form-group">
                                    <label for="nama_sekolah">Nama Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_sekolah') is-invalid @enderror"
                                        id="nama_sekolah" name="nama_sekolah"
                                        value="{{ old('nama_sekolah', $profil->nama_sekolah) }}" required
                                        placeholder="Masukkan nama lengkap sekolah">
                                    @error('nama_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="visi">Visi Sekolah <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce @error('visi') is-invalid @enderror" id="visi"
                                        name="visi" rows="4" required>{{ old('visi', $profil->visi) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lightbulb"></i> Visi biasanya berupa satu kalimat yang
                                        menggambarkan tujuan jangka panjang sekolah
                                    </small>
                                    @error('visi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="misi">Misi Sekolah <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce @error('misi') is-invalid @enderror" id="misi"
                                        name="misi" rows="8" required>{{ old('misi', $profil->misi) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-list-ol"></i> Gunakan toolbar untuk membuat daftar bernomor.
                                        Misi akan ditampilkan sebagai daftar di website.
                                    </small>
                                    @error('misi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="sejarah">Sejarah Sekolah</label>
                                    <textarea class="form-control tinymce @error('sejarah') is-invalid @enderror"
                                        id="sejarah" name="sejarah"
                                        rows="6">{{ old('sejarah', $profil->sejarah) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-history"></i> Ceritakan sejarah berdirinya sekolah,
                                        perkembangan, dan pencapaian penting
                                    </small>
                                    @error('sejarah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Data Statistik -->
                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-chart-bar"></i> Data Statistik</h5>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="jml_siswa">Jumlah Siswa <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('jml_siswa') is-invalid @enderror" id="jml_siswa"
                                            name="jml_siswa" value="{{ old('jml_siswa', $profil->jml_siswa) }}" required
                                            min="0" placeholder="0">
                                        @error('jml_siswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="jml_guru">Jumlah Guru <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('jml_guru') is-invalid @enderror" id="jml_guru"
                                            name="jml_guru" value="{{ old('jml_guru', $profil->jml_guru) }}" required
                                            min="0" placeholder="0">
                                        @error('jml_guru') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="jml_staff">Jumlah Staff <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('jml_staff') is-invalid @enderror" id="jml_staff"
                                            name="jml_staff" value="{{ old('jml_staff', $profil->jml_staff) }}" required
                                            min="0" placeholder="0">
                                        @error('jml_staff') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Gambar -->
                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-images"></i> Gambar & Logo</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo_sekolah">Logo Sekolah</label>
                                            <input type="file"
                                                class="form-control @error('logo_sekolah') is-invalid @enderror"
                                                id="logo_sekolah" name="logo_sekolah"
                                                accept="image/png, image/jpeg, image/jpg">
                                            <small class="form-text text-muted">Format: PNG, JPG, JPEG | Maksimal:
                                                1MB</small>
                                            @error('logo_sekolah') <div class="invalid-feedback d-block">{{ $message }}
                                            </div> @enderror

                                            @if($profil->logo_sekolah)
                                            <div class="mt-3">
                                                <div class="current-image-preview">
                                                    <img src="{{ Storage::url($profil->logo_sekolah) }}"
                                                        alt="Logo Sekolah Saat Ini" class="img-thumbnail"
                                                        style="max-height: 120px; max-width: 200px;">
                                                    <p class="text-muted small mt-2">
                                                        <i class="fas fa-info-circle"></i> Logo saat ini. Upload file
                                                        baru untuk mengganti.
                                                    </p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">Gambar Banner/Sekolah</label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                id="image" name="image" accept="image/png, image/jpeg, image/jpg">
                                            <small class="form-text text-muted">Format: PNG, JPG, JPEG | Maksimal:
                                                1MB</small>
                                            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror

                                            @if($profil->image)
                                            <div class="mt-3">
                                                <div class="current-image-preview">
                                                    <img src="{{ asset('uploads/images/'.$profil->image) }}"
                                                        alt="Gambar Sekolah Saat Ini" class="img-thumbnail"
                                                        style="max-height: 120px; max-width: 200px;">
                                                    <p class="text-muted small mt-2">
                                                        <i class="fas fa-info-circle"></i> Gambar saat ini. Upload file
                                                        baru untuk mengganti.
                                                    </p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Kontak -->
                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-address-book"></i> Informasi Kontak</h5>

                                <div class="form-group">
                                    <label for="alamat">Alamat Sekolah <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                        name="alamat" rows="3" required
                                        placeholder="Tuliskan alamat lengkap sekolah beserta kode pos">{{ old('alamat', $profil->alamat) }}</textarea>
                                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="kontak1">Kontak 1 (Telepon) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kontak1') is-invalid @enderror"
                                            id="kontak1" name="kontak1" value="{{ old('kontak1', $profil->kontak1) }}"
                                            required placeholder="Contoh: (0234) 123456">
                                        @error('kontak1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="kontak2">Kontak 2 (WA/HP)</label>
                                        <input type="text" class="form-control @error('kontak2') is-invalid @enderror"
                                            id="kontak2" name="kontak2" value="{{ old('kontak2', $profil->kontak2) }}"
                                            placeholder="Contoh: 08123456789">
                                        @error('kontak2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="email">Email Sekolah <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $profil->email) }}" required
                                            placeholder="sekolah@example.com">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Akademik -->
                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-graduation-cap"></i> Informasi Akademik</h5>

                                <div class="form-group">
                                    <label for="kurikulum">Kurikulum</label>
                                    <textarea class="form-control tinymce @error('kurikulum') is-invalid @enderror"
                                        id="kurikulum" name="kurikulum"
                                        rows="5">{{ old('kurikulum', $profil->kurikulum) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-book"></i> Jelaskan kurikulum yang digunakan, struktur
                                        pembelajaran, dan keunggulannya
                                    </small>
                                    @error('kurikulum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="metode_pengajaran">Metode Pengajaran</label>
                                    <textarea
                                        class="form-control tinymce @error('metode_pengajaran') is-invalid @enderror"
                                        id="metode_pengajaran" name="metode_pengajaran"
                                        rows="5">{{ old('metode_pengajaran', $profil->metode_pengajaran) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-chalkboard-teacher"></i> Jelaskan metode dan pendekatan
                                        pengajaran yang diterapkan
                                    </small>
                                    @error('metode_pengajaran') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="budaya_sekolah">Budaya Sekolah</label>
                                    <textarea class="form-control tinymce @error('budaya_sekolah') is-invalid @enderror"
                                        id="budaya_sekolah" name="budaya_sekolah"
                                        rows="5">{{ old('budaya_sekolah', $profil->budaya_sekolah) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-users"></i> Jelaskan budaya, nilai-nilai, dan tradisi yang
                                        dikembangkan di sekolah
                                    </small>
                                    @error('budaya_sekolah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Prestasi & Fasilitas -->
                            <div class="form-section">
                                <h5 class="section-title"><i class="fas fa-trophy"></i> Prestasi & Fasilitas</h5>

                                <div class="form-group">
                                    <label for="prestasi_sekolah">Prestasi Sekolah</label>
                                    <textarea
                                        class="form-control tinymce @error('prestasi_sekolah') is-invalid @enderror"
                                        id="prestasi_sekolah" name="prestasi_sekolah"
                                        rows="6">{{ old('prestasi_sekolah', $profil->prestasi_sekolah) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-medal"></i> Gunakan toolbar untuk membuat daftar prestasi.
                                        Kosongkan jika belum ada prestasi.
                                    </small>
                                    @error('prestasi_sekolah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="fasilitas_sekolah">Fasilitas Sekolah</label>
                                    <textarea
                                        class="form-control tinymce @error('fasilitas_sekolah') is-invalid @enderror"
                                        id="fasilitas_sekolah" name="fasilitas_sekolah"
                                        rows="6">{{ old('fasilitas_sekolah', $profil->fasilitas_sekolah) }}</textarea>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-building"></i> Gunakan toolbar untuk membuat daftar fasilitas.
                                        Kosongkan jika belum ada fasilitas khusus.
                                    </small>
                                    @error('fasilitas_sekolah') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success btn-round btn-lg">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <button type="button" class="btn btn-secondary btn-round btn-lg ml-2"
                                    onclick="window.location.reload()">
                                    <i class="fas fa-undo"></i> Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TinyMCE CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for rich text editing
    tinymce.init({
        selector: '#visi, #sejarah, #kurikulum, #metode_pengajaran, #budaya_sekolah, #prestasi_sekolah, #fasilitas_sekolah',
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.6; }',
        branding: false,
        promotion: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // Special configuration for Misi with better list handling
    tinymce.init({
        selector: '#misi',
        height: 350,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic | ' +
                'alignleft aligncenter alignright | ' +
                'numlist bullist | outdent indent | ' +
                'removeformat | code | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.6; }',
        branding: false,
        promotion: false,
        list_number_styles: 'decimal,lower-alpha,lower-roman,upper-alpha,upper-roman',
        list_bullet_styles: 'disc,circle,square',
        setup: function (editor) {
            editor.on('init', function() {
                // Auto-create numbered list for mission if content exists and isn't already formatted
                var content = editor.getContent();
                if (content && !content.includes('<ol>') && !content.includes('<ul>')) {
                    var lines = content.replace(/<p>/g, '').split('</p>').filter(line => line.trim());
                    if (lines.length > 1) {
                        var listItems = lines.map(line => '<li>' + line.trim() + '</li>').join('');
                        editor.setContent('<ol>' + listItems + '</ol>');
                    }
                }
            });

            editor.on('change', function () {
                editor.save();
            });
        }
    });
});
</script>

<style>
    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    .section-title {
        color: #495057;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
    }

    .section-title i {
        color: #007bff;
        margin-right: 8px;
    }

    .current-image-preview {
        padding: 15px;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        text-align: center;
    }

    .form-control {
        border-radius: 6px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .btn-round {
        border-radius: 20px;
    }

    .card-action {
        background-color: #ffffff;
        padding: 1.5rem;
        border-top: 1px solid #dee2e6;
        margin: 0 -1.5rem -1.5rem -1.5rem;
        border-radius: 0 0 8px 8px;
    }

    .form-text.text-muted {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .form-text i {
        color: #6c757d;
        margin-right: 4px;
    }

    /* TinyMCE Custom Styling */
    .tox .tox-editor-header {
        border-bottom: 1px solid #dee2e6;
    }

    .tox .tox-toolbar {
        background-color: #f8f9fa;
    }

    .tox .tox-edit-area {
        border: 1px solid #ced4da;
        border-radius: 0 0 6px 6px;
    }

    .tox-tinymce {
        border-radius: 6px;
        border: 1px solid #ced4da;
    }

    .tinymce-container {
        margin-bottom: 1rem;
    }

    /* Hide original textarea when TinyMCE is loaded */
    .mce-tinymce {
        border-radius: 6px;
    }
</style>

@endsection
