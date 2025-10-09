{{-- resources/views/admin/berita/_form.blade.php --}}
@php $isEdit = isset($item) && $item->exists; @endphp

<div class="form-group">
    <label for="judul">Judul Berita <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
        value="{{ old('judul', $item->judul ?? '') }}" required>
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="tanggal">Tanggal Publikasi <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal"
                value="{{ old('tanggal', $isEdit && $item->tanggal ? ($item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : $item->tanggal) : date('Y-m-d')) }}"
                required>
            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="published" {{ old('status', $item->status ?? 'published') == 'published' ? 'selected' :
                    '' }}>Published</option>
                <option value="draft" {{ old('status', $item->status ?? '') == 'draft' ? 'selected' : '' }}>Draft
                </option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="deskripsi">Deskripsi Singkat (Kutipan) <span class="text-danger">*</span></label>
    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
        required>{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="isi">Isi Berita Lengkap <span class="text-danger">*</span></label>
    <textarea class="form-control @error('isi') is-invalid @enderror tinymce-editor" id="isi" name="isi"
        rows="15">{{ old('isi', $item->isi ?? '') }}</textarea>
    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label for="image">Gambar Thumbnail (Max 2MB, JPG/PNG)</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
        accept="image/jpeg, image/png, image/jpg, image/gif, image/svg">
    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    @if($isEdit && $item->image)
    <div class="mt-2">
        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}" class="img-thumbnail"
            style="max-height: 150px; max-width:250px; border:1px solid #ddd; padding:5px;">
        <p class="text-muted small">Gambar saat ini. Upload baru untuk mengganti.</p>
    </div>
    @endif
</div>

<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> {{ $isEdit ? 'Update Berita' :
        'Simpan Berita' }}</button>
    <a href="{{ route('admin.berita.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i> Batal</a>
</div>

{{-- Script TinyMCE hanya di-push sekali, idealnya dari layout utama atau file JS terpisah --}}
{{-- Jika belum ada di layout utama, push di sini --}}
@pushOnce('scripts')
<script src="https://cdn.tiny.cloud/1/YOUR_TINYMCE_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof tinymce !== 'undefined') {
          tinymce.init({
            selector: 'textarea.tinymce-editor',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak media table paste code help
            wordcount autoresize',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent | removeformat | link image media table | code | help',
            height: 400,
            // PASTIKAN BARIS BERIKUT DI-KOMEN ATAU DIHAPUS JIKA TIDAK DIGUNAKAN:
            // images_upload_url: '{{-- route("admin.tinymce.upload") --}}',
            // images_upload_credentials: true,
            // file_picker_types: 'image',
            // file_picker_callback: function (cb, value, meta) { /* ... */ },
            });
        } else {
            console.error("TinyMCE script not loaded. Check your layout or if the CDN link is correct.");
        }
    });
</script>
@endPushOnce
