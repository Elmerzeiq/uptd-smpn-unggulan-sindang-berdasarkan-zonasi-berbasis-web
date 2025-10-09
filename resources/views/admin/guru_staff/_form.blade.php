{{-- resources/views/admin/guru_staff/_form.blade.php --}}
<div class="form-group">
    <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
        value="{{ old('nama', $item->nama ?? '') }}" required>
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="nip">NIP (Kosongkan jika tidak ada)</label>
    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip"
        value="{{ old('nip', $item->nip ?? '') }}">
    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="jabatan">Jabatan (Contoh: Guru Matematika, Staff TU, Kepala Sekolah) <span
            class="text-danger">*</span></label>
    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan"
        value="{{ old('jabatan', $item->jabatan ?? '') }}" required>
    @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="kategori">Kategori <span class="text-danger">*</span></label>
    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required> {{--
        Gunakan form-select untuk Bootstrap 5 --}}
        <option value="">-- Pilih Kategori --</option>
        <option value="guru" {{ old('kategori', $item->kategori ?? '') == 'guru' ? 'selected' : '' }}>Guru</option>
        <option value="staff" {{ old('kategori', $item->kategori ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
        <option value="kepala_sekolah" {{ old('kategori', $item->kategori ?? '') == 'kepala_sekolah' ? 'selected' : ''
            }}>Kepala Sekolah</option>
    </select>
    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group" id="sambutan_group"
    style="{{ old('kategori', $item->kategori ?? 'guru') == 'kepala_sekolah' ? '' : 'display:none;' }}">
    <label for="sambutan">Sambutan (Khusus Kepala Sekolah)</label>
    <textarea class="form-control @error('sambutan') is-invalid @enderror" id="sambutan" name="sambutan"
        rows="5">{{ old('sambutan', $item->sambutan ?? '') }}</textarea>
    @error('sambutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group" style="{{ old('kategori', $item->kategori ?? '') !== 'kepala_sekolah' ? 'display:none;' : '' }}">
    <label for="image">Foto (Max 1MB, JPG/PNG)</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
        accept="image/png, image/jpeg, image/jpg" {{ old('kategori', $item->kategori ?? '') === 'kepala_sekolah' ? 'required' : '' }}>
    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    @if(isset($item) && $item->image)
    <div class="mt-2">
        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama }}" class="img-thumbnail"
            style="max-height: 150px; max-width:150px;">
    </div>
    @endif
</div>
<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('admin.guru-staff.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i> Batal</a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriSelect = document.getElementById('kategori');
        const sambutanGroup = document.getElementById('sambutan_group');
        const sambutanTextarea = document.getElementById('sambutan');

        function toggleSambutan() {
            if (kategoriSelect && sambutanGroup) { // Pastikan elemen ada
                if (kategoriSelect.value === 'kepala_sekolah') {
                    sambutanGroup.style.display = 'block';
                } else {
                    sambutanGroup.style.display = 'none';
                    // sambutanTextarea.value = ''; // Dihandle di controller saat save jika perlu
                }
            }
        }
        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', toggleSambutan);
            toggleSambutan(); // Initial check on page load
        }
    });
</script>
@endpush
