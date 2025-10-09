{{-- resources/views/admin/pengumuman/_form.blade.php --}}
@php $isEdit = isset($item) && $item->exists; @endphp

<div class="form-group">
    <label for="judul">Judul Pengumuman <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
        value="{{ old('judul', $item->judul ?? '') }}" required>
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="tanggal">Tanggal Publikasi <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal"
                value="{{ old('tanggal', isset($item->tanggal) ? ($item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : $item->tanggal) : date('Y-m-d')) }}"
                required>
            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="target_penerima">Target Penerima <span class="text-danger">*</span></label>
            <select class="form-select @error('target_penerima') is-invalid @enderror" id="target_penerima"
                name="target_penerima" required>
                <option value="semua" {{ old('target_penerima', $item->target_penerima ?? 'semua') == 'semua' ?
                    'selected' : '' }}>Semua Pengunjung</option>
                <option value="calon_siswa" {{ old('target_penerima', $item->target_penerima ?? '') == 'calon_siswa' ?
                    'selected' : '' }}>Calon Siswa (Info SPMB)</option>
                <option value="siswa_diterima" {{ old('target_penerima', $item->target_penerima ?? '') ==
                    'siswa_diterima' ? 'selected' : '' }}>Siswa Diterima (Hasil SPMB)</option>
            </select>
            @error('target_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label for="isi">Isi Pengumuman Lengkap <span class="text-danger">*</span></label>
    <textarea class="form-control @error('isi') is-invalid @enderror tinymce-editor" id="isi" name="isi"
        rows="15">{{ old('isi', $item->isi ?? '') }}</textarea>
    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> {{ $isEdit ? 'Update Pengumuman'
        : 'Simpan Pengumuman' }}</button>
    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i> Batal</a>
</div>
{{-- Pastikan script TinyMCE di-push jika belum global --}}
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
                height: 300
            });
        }
    });
</script>
@endpush
