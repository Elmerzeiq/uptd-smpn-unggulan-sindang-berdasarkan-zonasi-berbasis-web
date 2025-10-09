@extends('layouts.admin.app')
@section('title', 'Manajemen Berkas Siswa')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Manajemen Berkas Siswa</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item active">Berkas Siswa</li>
            </ul>
        </div>
       
        <div class="mb-4 row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Siswa</p>
                                    <h4 class="card-title">{{ $statistik['total_siswa'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-success bubble-shadow-small">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Sudah Upload</p>
                                    <h4 class="card-title">{{ $statistik['ada_berkas'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-warning bubble-shadow-small">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Menunggu Verifikasi</p>
                                    <h4 class="card-title">{{ $statistik['menunggu_verifikasi'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-danger bubble-shadow-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Berkas Tidak Lengkap</p>
                                    <h4 class="card-title">{{ $statistik['berkas_tidak_lengkap'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 shadow card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Filter & Pencarian</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.berkas.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jalur_pendaftaran">Jalur Pendaftaran</label>
                                <select class="form-select" name="jalur_pendaftaran" id="jalur_pendaftaran">
                                    <option value="">Semua Jalur</option>
                                    <option value="domisili" {{ request('jalur_pendaftaran')==='domisili' ? 'selected'
                                        : '' }}>Domisili</option>
                                    <option value="prestasi" {{ request('jalur_pendaftaran')==='prestasi' ? 'selected'
                                        : '' }}>Prestasi</option>
                                    <option value="afirmasi" {{ request('jalur_pendaftaran')==='afirmasi' ? 'selected'
                                        : '' }}>Afirmasi</option>
                                    <option value="mutasi" {{ request('jalur_pendaftaran')==='mutasi' ? 'selected' : ''
                                        }}>Mutasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status_pendaftaran">Status Pendaftaran</label>
                                <select class="form-select" name="status_pendaftaran" id="status_pendaftaran">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu_verifikasi_berkas" {{
                                        request('status_pendaftaran')==='menunggu_verifikasi_berkas' ? 'selected' : ''
                                        }}>Menunggu Verifikasi</option>
                                    <option value="berkas_diverifikasi" {{
                                        request('status_pendaftaran')==='berkas_diverifikasi' ? 'selected' : '' }}>
                                        Berkas Diverifikasi</option>
                                    <option value="berkas_tidak_lengkap" {{
                                        request('status_pendaftaran')==='berkas_tidak_lengkap' ? 'selected' : '' }}>
                                        Berkas Tidak Lengkap</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status_berkas">Status Berkas</label>
                                <select class="form-select" name="status_berkas" id="status_berkas">
                                    <option value="">Semua</option>
                                    <option value="ada_berkas" {{ request('status_berkas')==='ada_berkas' ? 'selected'
                                        : '' }}>Sudah Upload</option>
                                    <option value="belum_upload" {{ request('status_berkas')==='belum_upload'
                                        ? 'selected' : '' }}>Belum Upload</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text" class="form-control" name="search" id="search"
                                    placeholder="Nama, NISN, No. Pendaftaran" value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    <div class="gap-2 d-flex">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.berkas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh me-1"></i> Reset
                        </a>
                        {{-- <button type="button" class="btn btn-success" onclick="exportData()">
                            <i class="fas fa-download me-1"></i> Export
                        </button> --}}
                    </div>
                </form>
            </div>
        </div>

        <div class="shadow card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">Daftar Berkas Siswa ({{ $siswa->total() }} siswa)</h5>
                    <div class="gap-2 d-flex">
                        <button type="button" class="btn btn-warning btn-sm" onclick="bulkAction('tolak')"
                            id="btn-bulk-tolak" disabled>
                            <i class="fas fa-times me-1"></i> Tolak Terpilih
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('verifikasi')"
                            id="btn-bulk-verifikasi" disabled>
                            <i class="fas fa-check me-1"></i> Verifikasi Terpilih
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($siswa->isNotEmpty())
                <form id="bulk-form" action="{{ route('admin.berkas.bulk-verifikasi') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%"><input type="checkbox" id="select-all"
                                            class="form-check-input bulk-checkbox-all"></th>
                                    <th>Siswa</th>
                                    <th>Jalur</th>
                                    <th>Status Berkas</th>
                                    <th>Status Pendaftaran</th>
                                    <th>Tanggal Upload</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa as $s)
                                <tr>
                                    <td>
                                        @php
                                        // Tentukan apakah siswa ini bisa dipilih untuk bulk action
                                        $isSelectable = $s->berkas && in_array($s->status_pendaftaran,
                                        ['menunggu_verifikasi_berkas', 'berkas_tidak_lengkap']);
                                        @endphp

                                        {{-- PERBAIKAN: Selalu render checkbox, tapi disable jika tidak relevan --}}
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}"
                                            class="form-check-input bulk-checkbox-item" {{ $isSelectable ? ''
                                            : 'disabled' }}>
                                    </td>
                                    <td><strong>{{ $s->nama_lengkap }}</strong><br><small class="text-muted">NISN: {{
                                            $s->nisn }} | No: {{ $s->no_pendaftaran }}</small></td>
                                    <td><span class="badge bg-info">{{ ucwords(str_replace('_', ' ',
                                            $s->jalur_pendaftaran)) }}</span></td>
                                    <td>@if($s->berkas)<span class="badge bg-success"><i class="fas fa-check me-1"></i>
                                            Ada Berkas</span>@else<span class="badge bg-secondary"><i
                                                class="fas fa-times me-1"></i> Belum Upload</span>@endif</td>
                                    <td>
                                        @php
                                        $statusBadges = [
                                        'menunggu_verifikasi_berkas' => ['class' => 'bg-warning text-dark', 'text' =>
                                        'Menunggu Verifikasi'],
                                        'berkas_diverifikasi' => ['class' => 'bg-success', 'text' => 'Diverifikasi'],
                                        'berkas_tidak_lengkap' => ['class' => 'bg-danger', 'text' => 'Tidak Lengkap'],
                                        'lulus_seleksi' => ['class' => 'bg-primary', 'text' => 'Lulus Seleksi'],
                                        ];
                                        $currentStatus = $statusBadges[$s->status_pendaftaran] ?? ['class' =>
                                        'bg-secondary', 'text' => 'Lainnya'];
                                        @endphp
                                        <span class="badge {{ $currentStatus['class'] }}">{{ $currentStatus['text']
                                            }}</span>
                                    </td>
                                    <td>@if($s->berkas)<small>{{ $s->berkas->updated_at->format('d M Y H:i')
                                            }}</small>@else<small class="text-muted">-</small>@endif</td>
                                    <td><a href="{{ route('admin.berkas.show', $s->id) }}"
                                            class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                            title="Lihat Detail & Verifikasi"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Menampilkan {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }}
                        dari {{ $siswa->total() }} data</small>
                    {{ $siswa->withQueryString()->links() }}
                </div>
                @else
                <div class="py-5 text-center"><i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                    <h5 class="text-muted">Tidak ada data siswa ditemukan</h5>
                    <p class="text-muted">Ubah filter Anda atau tunggu siswa mendaftar.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL UNTUK KONFIRMASI BULK ACTION --}}
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionTitle">Konfirmasi Aksi</h5><button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan <strong id="bulk-action-text"></strong> <strong id="selected-count">0</strong> siswa
                    terpilih. Lanjutkan?</p>
                <div class="form-group"><label for="bulk-catatan">Catatan (Opsional)</label><textarea
                        class="form-control" id="bulk-catatan" rows="3"
                        placeholder="Catatan ini akan diterapkan untuk semua siswa terpilih..."></textarea></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-primary"
                    id="confirm-bulk-action">Ya, Lanjutkan</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    // Inisialisasi Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const selectAllCheckbox = $('.bulk-checkbox-all');
    // PERBAIKAN: Target checkbox yang bisa dipilih saja (bukan yang disabled)
    const itemCheckboxes = $('.bulk-checkbox-item:not(:disabled)');

    selectAllCheckbox.on('change', function() {
        itemCheckboxes.prop('checked', this.checked);
        updateBulkActionButtons();
    });

    itemCheckboxes.on('change', function() {
        updateBulkActionButtons();
        // Cek apakah semua checkbox yang bisa dipilih sudah tercentang
        if (itemCheckboxes.length === $('.bulk-checkbox-item:checked').length) {
            selectAllCheckbox.prop('checked', true);
        } else {
            selectAllCheckbox.prop('checked', false);
        }
    });

    $('#confirm-bulk-action').on('click', function() {
        const form = $('#bulk-form');
        const catatan = $('#bulk-catatan').val();
        form.find('input[name="catatan"]').remove();
        if (catatan) {
            form.append(`<input type="hidden" name="catatan" value="${catatan}">`);
        }
        form.submit();
    });
});

function updateBulkActionButtons() {
    const checkedCount = $('.bulk-checkbox-item:checked').length;
    $('#btn-bulk-verifikasi, #btn-bulk-tolak').prop('disabled', checkedCount === 0);
    $('#selected-count').text(checkedCount);
}

function bulkAction(action) {
    const checkedCount = $('.bulk-checkbox-item:checked').length;
    if (checkedCount === 0) {
        alert('Silakan pilih minimal satu siswa terlebih dahulu.');
        return;
    }
    const modal = $('#bulkActionModal');
    const actionText = action === 'verifikasi' ? 'memverifikasi' : 'menolak';
    const btnClass = action === 'verifikasi' ? 'btn-success' : 'btn-warning';

    $('#bulk-form').find('input[name="aksi"]').remove();
    $('#bulk-form').append(`<input type="hidden" name="aksi" value="${action}">`);

    modal.find('#bulk-action-text').text(actionText);
    modal.find('#confirm-bulk-action').removeClass('btn-success btn-warning').addClass(btnClass);
    modal.modal('show');
}
</script>
@endpush
