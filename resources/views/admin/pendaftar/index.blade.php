@extends('layouts.admin.app')
@section('title', 'Data Pendaftar SPMB')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="mb-4 page-header">
            <h3 class="gap-2 page-title fw-bold text-primary d-flex align-items-center">
                <i class="bi bi-person-lines-fill"></i> Data Pendaftar SPMB
            </h3>

        </div>
        {{-- <div class="page-action">
            <button class="btn btn-success me-2" onclick="exportData()">
                <i class="bi bi-download me-1"></i> Export Excel
            </button>
            <button class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak
            </button>
        </div>
        <br> --}}
        <!-- Statistics Cards -->
        <div class="mb-4 row">
            <div class="col-md-3">
                <div class="text-white shadow-sm card bg-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $pendaftars->total() }}</h4>
                                <p class="mb-0">Total Pendaftar</p>
                            </div>
                            <div>
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-white shadow-sm card bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $pendaftars->where('status_pendaftaran', 'lulus_seleksi')->count()
                                    }}</h4>
                                <p class="mb-0">Lulus Seleksi</p>
                            </div>
                            <div>
                                <i class="bi bi-trophy fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-white shadow-sm card bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $pendaftars->where('status_pendaftaran',
                                    'menunggu_verifikasi_berkas')->count() }}</h4>
                                <p class="mb-0">Menunggu Verifikasi</p>
                            </div>
                            <div>
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="shadow-sm card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-0">{{ $pendaftars->where('status_pendaftaran',
                                    'berkas_tidak_lengkap')->count() }}</h4>
                                <p class="mb-0">Berkas Belum Lengkap</p>
                            </div>
                            <div>
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 border-0 shadow-sm card">
            <div class="card-header bg-light">
                <h5 class="mb-0 card-title fw-semibold">Filter & Pencarian Data</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.pendaftar.index') }}" class="row g-3">
                    <input type="hidden" name="view_mode" value="{{ request('view_mode') }}">
                    <div class="col-md-3">
                        <label class="form-label small">Cari Data</label>
                        <input type="text" name="search" class="shadow-sm form-control"
                            placeholder="Nama/NISN/No. Daftar" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Jalur</label>
                        <select name="jalur" class="shadow-sm form-select">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurOptions as $jalur)
                            <option value="{{ $jalur }}" {{ request('jalur')==$jalur ? 'selected' : '' }}>{{
                                ucfirst($jalur) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Status</label>
                        <select name="status" class="shadow-sm form-select">
                            <option value="">Semua Status</option>
                            @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ request('status')==$status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Per Halaman</label>
                        <select name="per_page" class="shadow-sm form-select">
                            <option value="20" {{ request('per_page', 20)==20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">&nbsp;</label>
                        <div class="gap-1 d-flex">
                            <button type="submit" class="shadow-sm btn btn-primary flex-fill">
                                <i class="bi bi-search"></i>
                            </button>
                            <a href="{{ route('admin.pendaftar.index') }}"
                                class="shadow-sm btn btn-outline-secondary flex-fill">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="border-0 shadow-sm card">
            <div class="text-white card-header bg-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white card-title">
                        <i class="bi bi-table me-2"></i>Daftar Pendaftar
                        <span class="badge bg-light text-primary ms-2">
                            {{ $pendaftars->firstItem() }}-{{ $pendaftars->lastItem() }} dari {{ $pendaftars->total() }}
                        </span>
                    </h5>
                    <div class="btn-group" role="group">
                        <input type="checkbox" class="btn-check" id="select-all" autocomplete="off">
                        <label class="btn btn-sm btn-outline-light" for="select-all">Pilih Semua</label>
                        <button type="button" class="btn btn-sm btn-warning"
                            onclick="bulkAction('berkas_diverifikasi')">
                            <i class="bi bi-check-circle me-1"></i>Verifikasi Terpilih
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="check-all" class="form-check-input">
                            </th>
                            <th>No.</th>
                            <th>Foto</th>
                            <th>Data Pendaftar</th>
                            <th>NISN</th>
                            <th>Jalur</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftars as $key => $pendaftar)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input row-checkbox"
                                    value="{{ $pendaftar->id }}">
                            </td>
                            <td>{{ $pendaftars->firstItem() + $key }}</td>
                            <td>
                                @if($pendaftar->berkas && $pendaftar->berkas->file_pas_foto)
                                <img src="{{ Storage::url($pendaftar->berkas->file_pas_foto) }}" alt="Foto"
                                    class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="text-white bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $pendaftar->nama_lengkap }}</strong>
                                    <small class="d-block text-muted">{{ $pendaftar->no_pendaftaran }}</small>

                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="d-block text-muted">{{ $pendaftar->nisn }}</strong>
                                    @if($pendaftar->orangTua)
                                    <small class="d-block text-muted">
                                        {{ $pendaftar->orangTua->no_hp_ortu ?? $pendaftar->orangTua->no_hp_ayah ??
                                        $pendaftar->orangTua->no_hp_ibu ?? '-' }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($pendaftar->jalur_pendaftaran) }}</span>
                                @if($pendaftar->jalur_pendaftaran == 'domisili' && $pendaftar->kecamatan_domisili)
                                <small class="d-block text-muted">Kec. {{ ucfirst($pendaftar->kecamatan_domisili)
                                    }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge fs-6 px-2 py-1
                                    @if($pendaftar->status_pendaftaran == 'lulus_seleksi') bg-success
                                    @elseif(in_array($pendaftar->status_pendaftaran, ['tidak_lulus_seleksi', 'berkas_tidak_lengkap'])) bg-danger
                                    @elseif(in_array($pendaftar->status_pendaftaran, ['menunggu_verifikasi_berkas', 'berkas_diverifikasi'])) bg-info
                                    @else bg-warning @endif">
                                    {{ ucwords(str_replace('_', ' ', $pendaftar->status_pendaftaran ?? 'N/A')) }}
                                </span>
                                @if($pendaftar->catatan_verifikasi)
                                <i class="bi bi-chat-text text-warning ms-1" title="Ada catatan"
                                    data-bs-toggle="tooltip"></i>
                                @endif
                            </td>
                            <td>
                                <small class="d-block">{{ $pendaftar->created_at->format('d M Y') }}</small>
                                <small class="text-muted">{{ $pendaftar->created_at->format('H:i') }}</small>
                                @if($pendaftar->updated_at != $pendaftar->created_at)
                                <small class="d-block text-info">Diperbarui: {{ $pendaftar->updated_at->diffForHumans()
                                    }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pendaftar.show', $pendaftar->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pendaftar.edit', $pendaftar->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lain">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="quickStatus({{ $pendaftar->id }}, 'berkas_diverifikasi')">
                                                    <i class="bi bi-check-circle text-success me-2"></i>Verifikasi
                                                    Berkas
                                                </a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="quickStatus({{ $pendaftar->id }}, 'berkas_tidak_lengkap')">
                                                    <i class="bi bi-x-circle text-danger me-2"></i>Tolak Berkas
                                                </a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="quickStatus({{ $pendaftar->id }}, 'lulus_seleksi')">
                                                    <i class="bi bi-trophy text-warning me-2"></i>Lulus Seleksi
                                                </a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="#"
                                                    onclick="deleteUser({{ $pendaftar->id }})">
                                                    <i class="bi bi-trash me-2"></i>Hapus Data
                                                </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-5 text-center text-muted">
                                <i class="bi bi-person-x fs-1 text-muted"></i>
                                <h5 class="mt-2">Tidak ada data pendaftar ditemukan</h5>
                                <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-0 card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $pendaftars->firstItem() ?? 0 }} - {{ $pendaftars->lastItem() ?? 0 }}
                            dari {{ $pendaftars->total() }} data
                        </small>
                    </div>
                    <div>
                        {{ $pendaftars->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box untuk Admin -->
        <div class="mt-4 row">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Panduan untuk Admin</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Aksi yang dapat dilakukan:</strong></p>
                            <ul class="mb-0">
                                <li>Lihat detail lengkap setiap pendaftar</li>
                                <li>Edit data pendaftar dan ubah status</li>
                                <li>Verifikasi atau tolak berkas pendaftar</li>
                                <li>Export data ke Excel untuk analisis</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Shortcut Keyboard:</strong></p>
                            <ul class="mb-0">
                                <li><kbd>Ctrl + P</kbd> - Cetak halaman</li>
                                <li><kbd>Ctrl + F</kbd> - Pencarian browser</li>
                                <li><kbd>Ctrl + A</kbd> - Pilih semua baris</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Quick Status Update -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status Pendaftar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_status" class="form-label">Status Baru</label>
                        <select class="form-select" id="modal_status" name="status_pendaftaran_baru" required>
                            <option value="berkas_diverifikasi">Berkas Diverifikasi</option>
                            <option value="berkas_tidak_lengkap">Berkas Tidak Lengkap</option>
                            <option value="lulus_seleksi">Lulus Seleksi</option>
                            <option value="tidak_lulus_seleksi">Tidak Lulus Seleksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_catatan" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="modal_catatan" name="catatan_verifikasi_baru" rows="3"
                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    .page-action {
        display: flex;
        gap: 10px;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .table td {
        vertical-align: middle;
    }

    .badge.fs-6 {
        font-size: 0.75rem !important;
    }

    @media (max-width: 768px) {
        .btn-group .btn {
            padding: 0.25rem 0.4rem;
        }

        .table td small {
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Checkbox functionality
        const checkAll = document.getElementById('check-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');

        checkAll.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
                checkAll.checked = allChecked;
                checkAll.indeterminate = someChecked && !allChecked;
            });
        });
    });

    // Quick status update
    function quickStatus(userId, status) {
        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        const form = document.getElementById('statusForm');
        const statusSelect = document.getElementById('modal_status');
        const catatanTextarea = document.getElementById('modal_catatan');

        form.action = `/admin/data-pendaftar/${userId}/update-status`;
        statusSelect.value = status;

        // Auto-fill catatan berdasarkan status
        switch(status) {
            case 'berkas_diverifikasi':
                catatanTextarea.value = 'Berkas lengkap dan sesuai dengan persyaratan.';
                break;
            case 'berkas_tidak_lengkap':
                catatanTextarea.value = 'Berkas tidak lengkap atau tidak sesuai dengan persyaratan. Silakan lengkapi kembali.';
                break;
            case 'lulus_seleksi':
                catatanTextarea.value = 'Selamat! Anda lulus seleksi SPMB. Silakan melakukan daftar ulang.';
                break;
            default:
                catatanTextarea.value = '';
        }

        modal.show();
    }

    // Bulk actions
    function bulkAction(action) {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Pilih minimal satu data untuk diproses.');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin ${action} untuk ${checkedBoxes.length} data terpilih?`)) {
            // Implement bulk action logic here
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            console.log('Bulk action:', action, 'IDs:', ids);
            // You would typically send this to a bulk action endpoint
        }
    }

    // Export data
    function exportData() {
        const currentParams = new URLSearchParams(window.location.search);
        window.location.href = '/admin/data-pendaftar/export?' + currentParams.toString();
    }

    // Delete user
    function deleteUser(userId) {
        if (confirm('Apakah Anda yakin ingin menghapus data pendaftar ini? Tindakan ini tidak dapat dibatalkan.')) {
            // Implement delete logic
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/data-pendaftar/${userId}`;
            form.style.display = 'none';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'a') {
            e.preventDefault();
            document.getElementById('check-all').click();
        }
    });
</script>
@endpush
