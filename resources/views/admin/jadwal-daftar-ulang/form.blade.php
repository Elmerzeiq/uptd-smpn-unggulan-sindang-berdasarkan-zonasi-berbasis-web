@extends('layouts.admin')

@section('title', isset($jadwal) ? 'Edit Jadwal Daftar Ulang' : 'Tambah Jadwal Daftar Ulang')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if(isset($jadwal))
                        <i class="fas fa-edit"></i> Edit Jadwal Daftar Ulang
                        @else
                        <i class="fas fa-plus"></i> Tambah Jadwal Daftar Ulang
                        @endif
                    </h3>
                </div>

                <form
                    action="{{ isset($jadwal) ? route('admin.daftar-ulang.jadwal-daftar-ulang.update', $jadwal) : route('admin.daftar-ulang.jadwal-daftar-ulang.store') }}"
                    method="POST">
                    @csrf
                    @if(isset($jadwal))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nama_sesi" class="form-label">Nama Sesi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_sesi') is-invalid @enderror"
                                        id="nama_sesi" name="nama_sesi"
                                        value="{{ old('nama_sesi', $jadwal->nama_sesi ?? '') }}"
                                        placeholder="Contoh: Sesi Pagi, Gelombang 1, dll" required>
                                    @error('nama_sesi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                        id="tanggal" name="tanggal"
                                        value="{{ old('tanggal', isset($jadwal) ? $jadwal->tanggal : '') }}" required>
                                    @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kuota" class="form-label">Kuota <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('kuota') is-invalid @enderror"
                                        id="kuota" name="kuota" value="{{ old('kuota', $jadwal->kuota ?? 50) }}" min="1"
                                        placeholder="Jumlah peserta maksimal" required>
                                    @error('kuota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="waktu_mulai" class="form-label">Waktu Mulai <span
                                            class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror"
                                        id="waktu_mulai" name="waktu_mulai"
                                        value="{{ old('waktu_mulai', isset($jadwal) ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : '') }}"
                                        required>
                                    @error('waktu_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="waktu_selesai" class="form-label">Waktu Selesai <span
                                            class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror"
                                        id="waktu_selesai" name="waktu_selesai"
                                        value="{{ old('waktu_selesai', isset($jadwal) ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') : '') }}"
                                        required>
                                    @error('waktu_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="aktif" name="aktif" {{
                                            old('aktif', $jadwal->aktif ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="aktif">
                                            <strong>Status Aktif</strong>
                                            <small class="text-muted d-block">Jadwal aktif dapat dipilih oleh
                                                mahasiswa</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(isset($jadwal))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Informasi:</strong> Jadwal ini dibuat pada {{
                                    $jadwal->created_at->format('d/m/Y H:i') }}
                                    @if($jadwal->updated_at != $jadwal->created_at)
                                    dan terakhir diperbarui pada {{ $jadwal->updated_at->format('d/m/Y H:i') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                {{ isset($jadwal) ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Validasi waktu selesai harus lebih besar dari waktu mulai
    const waktuMulai = document.getElementById('waktu_mulai');
    const waktuSelesai = document.getElementById('waktu_selesai');

    function validateTime() {
        if (waktuMulai.value && waktuSelesai.value) {
            if (waktuSelesai.value <= waktuMulai.value) {
                waktuSelesai.setCustomValidity('Waktu selesai harus lebih besar dari waktu mulai');
            } else {
                waktuSelesai.setCustomValidity('');
            }
        }
    }

    waktuMulai.addEventListener('change', validateTime);
    waktuSelesai.addEventListener('change', validateTime);

    // Set minimum date to today
    const tanggalInput = document.getElementById('tanggal');
    const today = new Date().toISOString().split('T')[0];
    tanggalInput.min = today;
});
</script>
@endpush
@endsection
