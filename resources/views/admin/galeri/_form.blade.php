{{-- resources/views/admin/galeri/_form.blade.php --}}
<div class="form-group">
    <label for="judul">Judul Foto (Opsional)</label>
    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
        value="{{ old('judul', $item->judul ?? '') }}">
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="deskripsi">Deskripsi Singkat (Opsional)</label>
    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi"
        rows="3">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="kategori">Kategori <span class="text-danger">*</span></label>
    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        @if(isset($kategoriList))
        @foreach($kategoriList as $kat)
        <option value="{{ $kat }}" {{ old('kategori', $item->kategori ?? '') == $kat ? 'selected' : '' }}>
            {{ ucwords(str_replace('_', ' ', $kat)) }}
        </option>
        @endforeach
        @else
        {{-- Fallback if kategoriList is not passed --}}
        <option value="kegiatan" {{ old('kategori', $item->kategori ?? '') == 'kegiatan' ? 'selected' : '' }}>Kegiatan
        </option>
        <option value="ekstrakurikuler" {{ old('kategori', $item->kategori ?? '') == 'ekstrakurikuler' ? 'selected' : ''
            }}>Ekstrakurikuler</option>
        <option value="prestasi" {{ old('kategori', $item->kategori ?? '') == 'prestasi' ? 'selected' : '' }}>Prestasi
        </option>
        {{-- <option value="fasilitas" {{ old('kategori', $item->kategori ?? '') == 'fasilitas' ? 'selected' : ''
            }}>Fasilitas</option> --}}
        @endif
    </select>
    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="image">Upload Gambar (Max 5MB, JPG/PNG/GIF/WEBP) <span class="text-danger">{{ isset($item) &&
            $item->image ? '' : '*' }}</span></label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" {{ isset($item)
        && $item->image ? '' : 'required' }} accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    @if(isset($item) && $item->image)
    <div class="mt-2">
        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}" class="img-thumbnail"
            style="max-height: 150px; max-width: 200px;">
    </div>
    @endif
</div>
<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> Simpan</button>
    <a href="{{ route('admin.galeri.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i> Batal</a>
</div>
