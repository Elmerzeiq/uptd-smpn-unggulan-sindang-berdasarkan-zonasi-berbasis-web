@php
$isEdit = isset($user) && $user->exists;
$currentRole = old('role', $user->role ?? 'admin');
@endphp

<div class="form-group">
    <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap"
        name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" required>
    @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="role">Role Pengguna <span class="text-danger">*</span></label>
    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
        <option value="admin" {{ $currentRole=='admin' ? 'selected' : '' }}>Admin</option>
        <option value="siswa" {{ $currentRole=='siswa' ? 'selected' : '' }}>Siswa</option>
    </select>
    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- <div class="form-group">
    <label for="foto_profil">Foto Profil <span class="text-muted">(Opsional, JPG/JPEG/PNG, maks 2MB)</span></label>
    <input type="file" class="form-control @error('foto_profil') is-invalid @enderror" id="foto_profil"
        name="foto_profil" accept="image/jpeg,image/png">
    @if($isEdit && $user->foto_profil)
    <div class="mt-2">
        <img src="{{ Storage::url('profil/' . $user->foto_profil) }}" alt="Foto Profil"
            style="max-width: 150px; max-height: 150px;">
        <div class="form-check mt-2">
            <input type="checkbox" class="form-check-input" id="remove_foto_profil" name="remove_foto_profil" value="1">
            <label class="form-check-label" for="remove_foto_profil">Hapus foto profil</label>
        </div>
        <p class="text-muted">Unggah file baru untuk mengganti foto existing, atau centang untuk menghapus.</p>
    </div>
    @endif
    @error('foto_profil') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div> --}}

<div id="admin_fields" style="{{ $currentRole == 'admin' ? '' : 'display:none;' }}">
    <div class="form-group">
        <label for="email">Email (Untuk Admin) <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $user->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div id="siswa_fields" style="{{ $currentRole == 'siswa' ? '' : 'display:none;' }}">
    <div class="form-group">
        <label for="nisn">NISN (Untuk Siswa) <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn"
            value="{{ old('nisn', $user->nisn ?? '') }}" maxlength="10" pattern="\d{10}"
            title="NISN harus 10 digit angka">
        @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @if($isEdit && $currentRole == 'siswa')
    <div class="form-group mb-3">
        <label for="jalur_pendaftaran" class="form-label fw-semibold">
            Pilih Jalur Pendaftaran <span class="text-danger">*</span>
        </label>
        <select id="jalur_pendaftaran" name="jalur_pendaftaran"
            class="form-select @error('jalur_pendaftaran') is-invalid @enderror" required>
            <option value="">-- Pilih Jalur --</option>
            @if(isset($jalurPendaftaranOptions) && is_array($jalurPendaftaranOptions))
            @foreach($jalurPendaftaranOptions as $value => $label)
            <option value="{{ $value }}" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == $value ? 'selected'
                : '' }}>
                {{ $label }}
            </option>
            @endforeach
            @else
            {{-- Fallback jika variabel tidak dikirim --}}
            <option value="domisili" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'domisili' ? 'selected'
                : '' }}>
                Domisili / Zonasi</option>
            <option value="prestasi_akademik_lomba" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') ==
                'prestasi_akademik_lomba' ? 'selected' : '' }}>
                Prestasi Akademik (Lomba)</option>
            <option value="prestasi_rapor" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'prestasi_rapor' ?
                'selected' : '' }}>
                Prestasi Nilai Rapor</option>
            <option value="afirmasi_ketm" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'afirmasi_ketm' ?
                'selected' : '' }}>
                Afirmasi - KETM</option>
            <option value="mutasi_pindah_tugas" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') ==
                'mutasi_pindah_tugas' ? 'selected' : '' }}>
                Mutasi - Perpindahan Tugas</option>
            @endif
        </select>
        @error('jalur_pendaftaran')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kecamatan Domisili (jika Domisili dipilih) --}}
    <div class="form-group mb-3" id="kecamatan_domisili_group"
        style="{{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'domisili' ? '' : 'display: none;' }}">
        <label for="kecamatan_domisili" class="form-label">Kecamatan Domisili</label>
        <input type="text" id="kecamatan_domisili" name="kecamatan_domisili"
            class="form-control @error('kecamatan_domisili') is-invalid @enderror"
            value="{{ old('kecamatan_domisili', $user->kecamatan_domisili ?? '') }}">
        @error('kecamatan_domisili')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    {{-- <div class="form-group">
        <label for="jalur_pendaftaran">Jalur Pendaftaran Siswa</label>
        <select name="jalur_pendaftaran" id="jalur_pendaftaran_akun"
            class="form-select @error('jalur_pendaftaran') is-invalid @enderror">
            <option value="">-- Pilih Jalur --</option>
            <option value="domisili" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'domisili' ?
                'selected' : '' }}>Domisili</option>
            <option value="prestasi" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'prestasi' ?
                'selected' : '' }}>Prestasi</option>
            <option value="afirmasi" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'afirmasi' ?
                'selected' : '' }}>Afirmasi</option>
            <option value="mutasi" {{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'mutasi' ? 'selected'
                : '' }}>Mutasi</option>
        </select>
        @error('jalur_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="form-group" id="kecamatan_domisili_akun_group"
        style="{{ old('jalur_pendaftaran', $user->jalur_pendaftaran ?? '') == 'domisili' ? '' : 'display:none;' }}">
        <label for="kecamatan_domisili_akun">Kecamatan Domisili (Jika Jalur Domisili)</label>
        <input type="text" class="form-control @error('kecamatan_domisili') is-invalid @enderror"
            id="kecamatan_domisili_akun" name="kecamatan_domisili"
            value="{{ old('kecamatan_domisili', $user->kecamatan_domisili ?? '') }}">
        @error('kecamatan_domisili') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div> --}}
    <div class="form-group">
        <label for="status_pendaftaran">Status Pendaftaran Siswa</label>
        <select name="status_pendaftaran" id="status_pendaftaran_akun"
            class="form-select @error('status_pendaftaran') is-invalid @enderror">
            <option value="">-- Pilih Status --</option>
            @foreach(['belum_diverifikasi', 'menunggu_kelengkapan_data', 'menunggu_verifikasi_berkas',
            'berkas_tidak_lengkap', 'berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi',
            'mengundurkan_diri', 'daftar_ulang_selesai'] as $status)
            <option value="{{ $status }}" {{ old('status_pendaftaran', $user->status_pendaftaran ?? '') == $status ?
                'selected' : '' }}>
                {{ ucwords(str_replace('_', ' ', $status)) }}
            </option>
            @endforeach
        </select>
        @error('status_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @endif
</div>

<div class="form-group">
    <label for="password">Password @if(!$isEdit) <span class="text-danger">*</span> @else (Kosongkan jika tidak ingin
        mengubah) @endif</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{
        !$isEdit ? 'required' : '' }} autocomplete="new-password">
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="password_confirmation">Konfirmasi Password @if(!$isEdit || old('password')) <span
            class="text-danger">*</span> @endif</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ !$isEdit ||
        old('password') ? 'required' : '' }}>
</div>

<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> {{ $isEdit ? 'Update Akun' :
        'Simpan Akun Baru' }}</button>
    <a href="{{ route('admin.akun-pengguna.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i>
        Batal</a>
</div>

@pushOnce('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const adminFields = document.getElementById('admin_fields');
        const siswaFields = document.getElementById('siswa_fields');
        const emailInput = document.getElementById('email');
        const nisnInput = document.getElementById('nisn');
        const jalurPendaftaranSelect = document.getElementById('jalur_pendaftaran'); // Perbaikan ID
        const kecamatanDomisiliGroup = document.getElementById('kecamatan_domisili_group'); // Perbaikan ID
        const kecamatanDomisiliInput = document.getElementById('kecamatan_domisili');
        // const fotoProfilInput = document.getElementById('foto_profil');
        // const removeFotoProfilCheckbox = document.getElementById('remove_foto_profil');

        function toggleRoleSpecificFields() {
            if (!roleSelect) return;
            if (roleSelect.value === 'admin') {
                if (adminFields) adminFields.style.display = 'block';
                if (emailInput) emailInput.required = true;
                if (siswaFields) siswaFields.style.display = 'none';
                if (nisnInput) {
                    nisnInput.required = false;
                    nisnInput.value = '';
                }
            } else if (roleSelect.value === 'siswa') {
                if (siswaFields) siswaFields.style.display = 'block';
                if (nisnInput) nisnInput.required = true;
                if (adminFields) adminFields.style.display = 'none';
                if (emailInput) emailInput.required = false;
            } else {
                if (adminFields) adminFields.style.display = 'none';
                if (siswaFields) siswaFields.style.display = 'none';
                if (emailInput) emailInput.required = false;
                if (nisnInput) nisnInput.required = false;
            }
        }

        function toggleKecamatanField() {
            if (jalurPendaftaranSelect && kecamatanDomisiliGroup && kecamatanDomisiliInput) {
                if (jalurPendaftaranSelect.value === 'domisili') {
                    kecamatanDomisiliGroup.style.display = 'block';
                } else {
                    kecamatanDomisiliGroup.style.display = 'none';
                    kecamatanDomisiliInput.value = '';
                }
            }
        }

        function toggleFotoProfilInput() {
            if (removeFotoProfilCheckbox && fotoProfilInput) {
                if (removeFotoProfilCheckbox.checked) {
                    fotoProfilInput.disabled = true;
                    fotoProfilInput.value = '';
                } else {
                    fotoProfilInput.disabled = false;
                }
            }
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', toggleRoleSpecificFields);
            toggleRoleSpecificFields();
        }
        if (jalurPendaftaranSelect) {
            jalurPendaftaranSelect.addEventListener('change', toggleKecamatanField);
            toggleKecamatanField();
        }
        if (removeFotoProfilCheckbox) {
            removeFotoProfilCheckbox.addEventListener('change', toggleFotoProfilInput);
            toggleFotoProfilInput();
        }
    });
</script>
@endPushOnce
