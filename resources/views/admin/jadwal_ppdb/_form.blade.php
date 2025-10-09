{{-- resources/views/admin/jadwal_ppdb/_form.blade.php --}}
@php
// Menentukan apakah ini mode edit atau create
// $jadwalPpdb dikirim dari controller create (new Model) atau edit (instance Model)
$isEdit = isset($jadwalPpdb) && $jadwalPpdb->exists;
@endphp

<div class="form-group">
    <label for="tahun_ajaran">Tahun Ajaran (Contoh: 2024/2025) <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror" id="tahun_ajaran"
        name="tahun_ajaran"
        value="{{ old('tahun_ajaran', $jadwalPpdb->tahun_ajaran ?? (date('Y').'/'.(date('Y')+1))) }}" required
        placeholder="YYYY/YYYY">
    @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="pembukaan_pendaftaran">Pembukaan Pendaftaran <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('pembukaan_pendaftaran') is-invalid @enderror"
                id="pembukaan_pendaftaran" name="pembukaan_pendaftaran"
                value="{{ old('pembukaan_pendaftaran', $isEdit && $jadwalPpdb->pembukaan_pendaftaran ? $jadwalPpdb->pembukaan_pendaftaran->format('Y-m-d\TH:i') : '') }}"
                required>
            @error('pembukaan_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="penutupan_pendaftaran">Penutupan Pendaftaran <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('penutupan_pendaftaran') is-invalid @enderror"
                id="penutupan_pendaftaran" name="penutupan_pendaftaran"
                value="{{ old('penutupan_pendaftaran', $isEdit && $jadwalPpdb->penutupan_pendaftaran ? $jadwalPpdb->penutupan_pendaftaran->format('Y-m-d\TH:i') : '') }}"
                required>
            @error('penutupan_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="pengumuman_hasil">Pengumuman Hasil <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('pengumuman_hasil') is-invalid @enderror"
                id="pengumuman_hasil" name="pengumuman_hasil"
                value="{{ old('pengumuman_hasil', $isEdit && $jadwalPpdb->pengumuman_hasil ? $jadwalPpdb->pengumuman_hasil->format('Y-m-d\TH:i') : '') }}"
                required>
            @error('pengumuman_hasil') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="kuota_total_keseluruhan">Kuota Total Keseluruhan <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('kuota_total_keseluruhan') is-invalid @enderror"
                id="kuota_total_keseluruhan" name="kuota_total_keseluruhan"
                value="{{ old('kuota_total_keseluruhan', $jadwalPpdb->kuota_total_keseluruhan ?? '320') }}" required
                min="1">
            @error('kuota_total_keseluruhan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="mulai_daftar_ulang">Mulai Daftar Ulang <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('mulai_daftar_ulang') is-invalid @enderror"
                id="mulai_daftar_ulang" name="mulai_daftar_ulang"
                value="{{ old('mulai_daftar_ulang', $isEdit && $jadwalPpdb->mulai_daftar_ulang ? $jadwalPpdb->mulai_daftar_ulang->format('Y-m-d\TH:i') : '') }}"
                required>
            @error('mulai_daftar_ulang') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="selesai_daftar_ulang">Selesai Daftar Ulang <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('selesai_daftar_ulang') is-invalid @enderror"
                id="selesai_daftar_ulang" name="selesai_daftar_ulang"
                value="{{ old('selesai_daftar_ulang', $isEdit && $jadwalPpdb->selesai_daftar_ulang ? $jadwalPpdb->selesai_daftar_ulang->format('Y-m-d\TH:i') : '') }}"
                required>
            @error('selesai_daftar_ulang') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-check">
        {{-- Menggunakan $jadwalPpdb->is_active langsung karena $jadwalPpdb adalah instance model --}}
        <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active',
            $jadwalPpdb->is_active ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
            Aktifkan Jadwal Ini (Akan menonaktifkan jadwal lain yang sedang aktif secara otomatis)
        </label>
    </div>
    @error('is_active') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="card-action">
    <button type="submit" class="btn btn-success btn-round"><i class="fas fa-save"></i> {{ $isEdit ? 'Update Jadwal' :
        'Simpan Jadwal Baru' }}</button>
    <a href="{{ route('admin.jadwal-ppdb.index') }}" class="btn btn-danger btn-round"><i class="fas fa-times"></i>
        Batal</a>
</div>
