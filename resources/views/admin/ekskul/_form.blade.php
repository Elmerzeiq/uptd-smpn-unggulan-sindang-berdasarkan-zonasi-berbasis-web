{{-- resources/views/admin/ekskul/_form.blade.php --}}
@php $isEdit = isset($item) && $item->exists; @endphp

<div class="form-group">
    <label for="judul">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
        value="{{ old('judul', $item->judul ?? '') }}" required>
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="kategori">Kategori Ekskul (Contoh: Olahraga, Seni, Akademik, Keagamaan)</label>
    <input type="text" class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori"
        value="{{ old('kategori', $item->kategori ?? '') }}">
    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="deskripsi">Deskripsi Singkat <span class="text-danger">*</span></label>
    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
        required>{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="isi">Isi / Detail Kegiatan / Program Kerja (Opsional)</label>
    <textarea class="form-control @error('isi') is-invalid @enderror tinymce-editor" id="isi" name="isi"
        rows="10">{{ old('isi', $item->isi ?? '') }}</textarea>
    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="image">Logo/Gambar Ekskul (Max 1MB, JPG/PNG)</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
        accept="image/jpeg, image/png, image/jpg">
    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    @if($isEdit && $item->image)
    <div class="mt-2">
        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}" class="img-thumbnail"
            style="max-height: 150px; max-width: 200px; border:1px solid #ddd; padding:5px;">
        <p class="text-muted small">Gambar saat ini. Upload baru untuk mengganti.</p>
    </div>
    @endif
</div>

<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> {{ $isEdit ? 'Update Data' :
        'Simpan Data' }}</button>
    <a href="{{ route('admin.ekskul.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i> Batal</a>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: 'textarea.tinymce-editor',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak media table paste code help wordcount autoresize',
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough backcolor | \
                          alignleft aligncenter alignright alignjustify | \
                          bullist numlist outdent indent | removeformat | link image media table | code | help',
                height: 300,
                // Tambahkan konfigurasi lain jika perlu, misalnya untuk upload gambar via TinyMCE
            });
        } else {
            console.error("TinyMCE is not loaded. Pastikan script TinyMCE ada di layout utama atau di push di sini.");
        }
    });
</script>
@endpush
