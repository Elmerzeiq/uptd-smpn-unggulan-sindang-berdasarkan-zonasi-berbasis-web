@extends('layouts.admin')

@section('title', 'Jadwal Daftar Ulang')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Kelola Jadwal Daftar Ulang</h1>
                    <p class="text-muted">Atur jadwal untuk proses daftar ulang mahasiswa</p>
                </div>
                <div>
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                        data-bs-target="#generateAutoModal">
                        <i class="fas fa-magic"></i> Generate Otomatis
                    </button>
                    <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold fs-4">{{ $statistics['total_jadwal'] }}</div>
                            <div class="small">Total Jadwal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold fs-4">{{ $statistics['jadwal_aktif'] }}</div>
                            <div class="small">Jadwal Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold fs-4">{{ $statistics['total_terisi'] }}</div>
                            <div class="small">Total Peserta</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            @php
                            $utilizationRate = $statistics['total_kuota'] > 0
                            ? round(($statistics['total_terisi'] / $statistics['total_kuota']) * 100, 1)
                            : 0;
                            @endphp
                            <div class="fw-bold fs-4">{{ $utilizationRate }}%</div>
                            <div class="small">Tingkat Okupansi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list"></i> Daftar Jadwal
                </h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="filterCollapse">
            <div class="card-body border-bottom bg-light">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Cari Jadwal</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama sesi atau keterangan..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status')=='aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status')=='nonaktif' ? 'selected' : '' }}>Non-Aktif
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control"
                            value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control"
                            value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($jadwals->count() > 0)
            <!-- Bulk Actions -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <input type="checkbox" id="selectAll" class="form-check-input me-2">
                    <label for="selectAll" class="form-check-label me-3">Pilih Semua</label>
                    <button type="button" class="btn btn-sm btn-outline-success" id="bulkActivate" disabled>
                        <i class="fas fa-toggle-on"></i> Aktifkan
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bulkDeactivate" disabled>
                        <i class="fas fa-toggle-off"></i> Non-Aktifkan
                    </button>
                </div>
                <div class="text-muted">
                    Menampilkan {{ $jadwals->firstItem() }} - {{ $jadwals->lastItem() }}
                    dari {{ $jadwals->total() }} jadwal
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="30">
                                <input type="checkbox" class="form-check-input" id="selectAllHeader">
                            </th>
                            <th>Jadwal</th>
                            <th>Waktu</th>
                            <th>Kapasitas</th>
                            <th>Okupansi</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $jadwal)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input row-checkbox" value="{{ $jadwal->id }}">
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1">{{ $jadwal->nama_sesi }}</h6>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $jadwal->tanggal_formatted }}
                                        <span class="ms-2 badge bg-light text-dark">{{ $jadwal->hari }}</span>
                                    </div>
                                    @if($jadwal->keterangan)
                                    <small class="text-muted">{{ Str::limit($jadwal->keterangan, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock me-2 text-muted"></i>
                                    <div>
                                        <div>{{ $jadwal->waktu_formatted }}</div>
                                        <small class="text-muted">{{ $jadwal->durasi_formatted }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users me-2 text-muted"></i>
                                    <div>
                                        <div class="fw-bold">{{ $jadwal->daftar_ulangs_count }}/{{ $jadwal->kuota }}
                                        </div>
                                        <small class="text-muted">Sisa: {{ $jadwal->sisa_kuota }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 60px; height: 8px;">
                                        <div class="progress-bar bg-{{ $jadwal->utilization_percentage > 80 ? 'danger' : ($jadwal->utilization_percentage > 60 ? 'warning' : 'success') }}"
                                            style="width: {{ $jadwal->utilization_percentage }}%"></div>
                                    </div>
                                    <span class="small">{{ $jadwal->utilization_percentage }}%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $jadwal->status_badge['class'] }}">
                                    <i class="fas fa-{{ $jadwal->status_badge['icon'] }} me-1"></i>
                                    {{ $jadwal->status_badge['text'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.peserta', $jadwal) }}"
                                        class="btn btn-info btn-sm" title="Lihat Peserta">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.edit', $jadwal) }}"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" title="Aksi Lainnya">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#"
                                                    onclick="toggleStatus({{ $jadwal->id }}, {{ $jadwal->aktif ? 'false' : 'true' }})">
                                                    <i
                                                        class="fas fa-toggle-{{ $jadwal->aktif ? 'off' : 'on' }} me-2"></i>
                                                    {{ $jadwal->aktif ? 'Non-Aktifkan' : 'Aktifkan' }}
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            @if($jadwal->canBeCancelled())
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="deleteJadwal({{ $jadwal->id }})">
                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                </a>
                                            </li>
                                            @else
                                            <li>
                                                <span class="dropdown-item-text text-muted">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Tidak dapat dihapus (ada peserta)
                                                </span>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $jadwals->appends(request()->query())->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Jadwal</h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                    Tidak ada jadwal yang sesuai dengan filter yang diterapkan.
                    @else
                    Belum ada jadwal daftar ulang yang dibuat.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'tanggal_mulai', 'tanggal_selesai']))
                <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}"
                    class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times"></i> Reset Filter
                </a>
                @endif
                <a href="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Jadwal Pertama
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Generate Auto Modal -->
<div class="modal fade" id="generateAutoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.daftar-ulang.jadwal-daftar-ulang.generate-auto') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-magic"></i> Generate Jadwal Otomatis
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" class="form-control" min="{{ date('Y-m-d') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" class="form-control" min="{{ date('Y-m-d') }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_mulai" class="form-control" value="08:00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" class="form-control" value="15:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Durasi per Sesi (menit) <span
                                        class="text-danger">*</span></label>
                                <select name="durasi_sesi" class="form-select" required>
                                    <option value="30">30 menit</option>
                                    <option value="45">45 menit</option>
                                    <option value="60" selected>1 jam</option>
                                    <option value="90">1.5 jam</option>
                                    <option value="120">2 jam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kuota per Sesi <span class="text-danger">*</span></label>
                                <input type="number" name="kuota_per_sesi" class="form-control" value="50" min="1"
                                    max="1000" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prefix Nama Sesi</label>
                                <input type="text" name="prefix_nama" class="form-control" value="Sesi"
                                    placeholder="Contoh: Sesi, Gelombang">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="skip_weekend"
                                        id="skip_weekend" checked>
                                    <label class="form-check-label" for="skip_weekend">
                                        Lewati akhir pekan (Sabtu & Minggu)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Info:</strong> Sistem akan secara otomatis menghindari jadwal yang bertabrakan
                        dengan jadwal yang sudah ada.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-magic"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllHeader = document.getElementById('selectAllHeader');
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActivate = document.getElementById('bulkActivate');
    const bulkDeactivate = document.getElementById('bulkDeactivate');

    function updateBulkButtons() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const hasSelection = checkedBoxes.length > 0;

        bulkActivate.disabled = !hasSelection;
        bulkDeactivate.disabled = !hasSelection;
    }

    function toggleAllCheckboxes(checked) {
        rowCheckboxes.forEach(cb => cb.checked = checked);
        updateBulkButtons();
    }

    [selectAllHeader, selectAll].forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleAllCheckboxes(this.checked);
            [selectAllHeader, selectAll].forEach(cb => cb.checked = this.checked);
        });
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkButtons);
    });

    // Bulk actions
    bulkActivate.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked'))
            .map(cb => cb.value);

        if (confirm(`Aktifkan ${selectedIds.length} jadwal terpilih?`)) {
            bulkUpdateStatus(selectedIds, true);
        }
    });

    bulkDeactivate.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked'))
            .map(cb => cb.value);

        if (confirm(`Non-aktifkan ${selectedIds.length} jadwal terpilih?`)) {
            bulkUpdateStatus(selectedIds, false);
        }
    });

    // Date validation for generate modal
    const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');

    tanggalMulai?.addEventListener('change', function() {
        tanggalSelesai.min = this.value;
        if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
            tanggalSelesai.value = this.value;
        }
    });
});

function bulkUpdateStatus(ids, status) {
    fetch('{{ route("admin.daftar-ulang.jadwal-daftar-ulang.bulk-update-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            ids: ids,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal memperbarui status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui status.');
    });
}

function toggleStatus(id, status) {
    const action = status ? 'mengaktifkan' : 'menonaktifkan';

    if (confirm(`Apakah Anda yakin ingin ${action} jadwal ini?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}/${id}/toggle-status`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';

        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = status;

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteJadwal(id) {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.daftar-ulang.jadwal-daftar-ulang.index') }}/${id}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

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
</script>
@endpush

@endsection
