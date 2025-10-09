@php
$isUploaded = $berkasPendaftar && !empty($berkasPendaftar->$field);
if ($isUploaded && isset($details['multiple'])) {
$fileData = json_decode($berkasPendaftar->$field, true);
$isUploaded = !empty($fileData);
} else {
$fileData = $isUploaded ? [$berkasPendaftar->$field] : [];
}
$borderColor = $details['required'] ? ($isUploaded ? 'border-success' : 'border-danger') : 'border-light';
@endphp

<div class="form-group mb-4 p-3 border rounded {{ $borderColor }}">
    <label for="{{ $field }}" class="form-label fw-bold">
        {{ $nomor }}. {{ $details['label'] }}
        @if($details['required']) <span class="text-danger">*</span> @else <span class="text-info">(Opsional)</span>
        @endif
    </label>
    <p class="text-muted small mb-2">{{ $details['keterangan'] }}</p>

    @if($isUploaded)
    <div class="mb-2">
        <p class="mb-1 small fw-medium text-success"><i class="fas fa-check-circle me-1"></i>File Terupload:</p>
        <ul class="list-group list-group-sm">
            @foreach($fileData as $index => $filePath)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ Storage::url($filePath) }}" target="_blank" class="text-primary text-truncate"
                    style="max-width: 80%;">{{ basename($filePath) }}</a>
                @if($allowUpload)
                <form action="{{ route('siswa.berkas.deleteFile', $field) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                    @csrf
                    @if(isset($details['multiple']) && $details['multiple'])
                    <input type="hidden" name="file_index" value="{{ $index }}">
                    @endif
                    <button type="submit" class="btn btn-danger btn-xs btn-link p-0 ms-2" title="Hapus file ini"><i
                            class="fas fa-times-circle"></i></button>
                </form>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($allowUpload)
    @if(isset($details['multiple']) && $details['multiple'])
    <p class="small text-info mt-2">Anda dapat menambahkan file lagi (jika belum mencapai batas maksimal).</p>
    @elseif($isUploaded)
    <p class="small text-info mt-2">Untuk mengganti file, silakan hapus file terupload terlebih dahulu, lalu upload file
        yang baru.</p>
    @endif

    <input type="file" class="form-control @error($field) is-invalid @enderror @error($field.'.*') is-invalid @enderror"
        id="{{ $field }}" name="{{ $field }}{{ (isset($details['multiple']) && $details['multiple']) ? '[]' : '' }}" {{
        (isset($details['multiple']) && $details['multiple']) ? 'multiple' : '' }} accept=".pdf,.jpg,.jpeg,.png">

    @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
    @error($field.'.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @endif
</div>
