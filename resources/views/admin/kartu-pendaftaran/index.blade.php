@extends('layouts.admin.app')

@section('title', 'Kelola Kartu Pendaftaran')

@push('styles')
<style>
    .modal-body .form-label {
        font-weight: 600;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.5em 1em;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .row-checkbox {
        cursor: pointer;
    }
</style>
@endpush

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-id-card text-primary me-2"></i>Kelola Kartu Pendaftaran</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a>
                </li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="#">Kartu Pendaftaran</a></li>
            </ul>
        </div>

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <p class="mb-0 text-muted">Verifikasi dan kelola semua kartu pendaftaran siswa.</p>
            <div class="btn-group">
                <button type="button" class="btn btn-light" onclick="exportData()"><i
                        class="fas fa-file-excel me-1"></i>Export</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bulkActionModal"><i class="fas fa-layer-group me-1"></i>Aksi Massal</button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="border-0 shadow-sm card">
            <div class="bg-white card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title">Data Kartu Pendaftaran</h5>
                <div>
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="ms-1 form-check-label" for="selectAll">Pilih Semua</label>
                </div>
            </div>
            <div class="p-0 card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="50" class="border-0">#</th>
                                <th class="border-0">No. Kartu</th>
                                <th class="border-0">Nama Siswa</th>
                                <th class="border-0">Jalur</th>
                                <th class="border-0">Status</th>
                                <th class="text-center border-0">Verifikasi</th>
                                <th class="border-0">Tanggal Dibuat</th>
                                <th class="text-center border-0" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kartuPendaftarans as $kartu)
                            <tr>
                                <td><input type="checkbox" class="form-check-input row-checkbox"
                                        value="{{ $kartu->id }}"></td>
                                <td><span class="fw-bold text-primary">{{ $kartu->nomor_kartu }}</span></td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $kartu->user->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $kartu->user->nisn ?? '-' }}</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">{{ strtoupper($kartu->jalur_pendaftaran) }}</span>
                                </td>
                                <td>
                                    @php
                                    $statusConfig = [
                                    'belum_diverifikasi' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Belum
                                    Diverifikasi'],
                                    'menunggu_kelengkapan_data' => ['class' => 'warning', 'icon' => 'clock', 'text' =>
                                    'Menunggu Kelengkapan Data'],
                                    'menunggu_verifikasi_berkas' => ['class' => 'warning', 'icon' => 'clock', 'text' =>
                                    'Menunggu Verifikasi Berkas'],
                                    'berkas_tidak_lengkap' => ['class' => 'danger', 'icon' => 'exclamation-circle',
                                    'text' => 'Berkas Tidak Lengkap'],
                                    'berkas_diverifikasi' => ['class' => 'info', 'icon' => 'check-circle', 'text' =>
                                    'Berkas Diverifikasi'],
                                    'lulus_seleksi' => ['class' => 'success', 'icon' => 'trophy', 'text' => 'Lulus
                                    Seleksi'],
                                    'tidak_lulus_seleksi' => ['class' => 'danger', 'icon' => 'times-circle', 'text' =>
                                    'Tidak Lulus'],
                                    'mengundurkan_diri' => ['class' => 'danger', 'icon' => 'times-circle', 'text' =>
                                    'Mengundurkan Diri'],
                                    'daftar_ulang_selesai' => ['class' => 'success', 'icon' => 'user-check', 'text' =>
                                    'Daftar Ulang Selesai'],
                                    ];
                                    $status = $statusConfig[$kartu->user->status_pendaftaran] ?? ['class' =>
                                    'secondary', 'icon' => 'question-circle', 'text' => 'Unknown'];
                                    @endphp
                                    <span class="badge bg-{{ $status['class'] }}">
                                        <i class="fas fa-{{ $status['icon'] }} me-1"></i>{{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($kartu->verified_by_admin)
                                    <span class="badge bg-success"><i
                                            class="fas fa-check-circle me-1"></i>Terverifikasi</span>
                                    @else
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Belum
                                        Terverifikasi</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($kartu->created_at)->format('d M Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.kartu-pendaftaran.show', $kartu->id) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.kartu-pendaftaran.edit', $kartu->id) }}"
                                            class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>

                                        {{-- @if($kartu->verified_by_admin)
                                        <form action="{{ route('admin.kartu-pendaftaran.unverify', $kartu->id) }}"
                                            method="POST" style="display:inline;"
                                            onsubmit="return confirm('Batalkan verifikasi kartu ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="fas fa-times"></i></button>
                                        </form> --}} 
                                        {{-- @else
                                        <form action="{{ route('admin.kartu-pendaftaran.verify', $kartu->id) }}"
                                            method="POST" style="display:inline;"
                                            onsubmit="return confirm('Verifikasi kartu ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success"><i
                                                    class="fas fa-check"></i></button>
                                        </form> --}}
                                        {{-- @endif --}}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data kartu pendaftaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                {{ $kartuPendaftarans->links() }}
            </div>
        </div>

        <!-- Bulk Action Modal -->
        <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkActionModalLabel">Aksi Massal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="bulkActionForm" method="POST" action="{{ route('admin.kartu-pendaftaran.bulk-action') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="bulk_action" class="form-label">Pilih Aksi</label>
                                <select name="bulk_action" id="bulk_action" class="form-control" required>
                                    <option value="">-- Pilih Aksi --</option>
                                    <option value="verify">Verifikasi Kartu</option>
                                    <option value="unverify">Batalkan Verifikasi</option>
                                    <option value="update_status">Ubah Status Pendaftaran</option>
                                </select>
                            </div>
                            <div class="mb-3" id="status_field" style="display: none;">
                                <label for="status_pendaftaran" class="form-label">Status Pendaftaran</label>
                                <select name="status_pendaftaran" id="status_pendaftaran" class="form-control">
                                    <option value="belum_diverifikasi">Belum Diverifikasi</option>
                                    <option value="menunggu_kelengkapan_data">Menunggu Kelengkapan Data</option>
                                    <option value="menunggu_verifikasi_berkas">Menunggu Verifikasi Berkas</option>
                                    <option value="berkas_tidak_lengkap">Berkas Tidak Lengkap</option>
                                    <option value="berkas_diverifikasi">Berkas Diverifikasi</option>
                                    <option value="lulus_seleksi">Lulus Seleksi</option>
                                    <option value="tidak_lulus_seleksi">Tidak Lulus</option>
                                    <option value="mengundurkan_diri">Mengundurkan Diri</option>
                                    <option value="daftar_ulang_selesai">Daftar Ulang Selesai</option>
                                </select>
                            </div>
                            <input type="hidden" name="selected_ids" id="selected_ids">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Show/Hide status field based on bulk action
    document.getElementById('bulk_action').addEventListener('change', function () {
        const statusField = document.getElementById('status_field');
        statusField.style.display = this.value === 'update_status' ? 'block' : 'none';
    });

    // Export Data (Placeholder - Implement with actual export logic)
    function exportData() {
        const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            alert('Pilih setidaknya satu kartu untuk diekspor.');
            return;
        }
        window.location.href = "{{ route('admin.kartu-pendaftaran.export') }}?ids=" + selectedIds.join(',');
    }

    // Handle Bulk Action Form Submission
    document.getElementById('bulkActionForm').addEventListener('submit', function (e) {
        const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            e.preventDefault();
            alert('Pilih setidaknya satu kartu untuk aksi massal.');
            return;
        }
        document.getElementById('selected_ids').value = selectedIds.join(',');
    });
</script>
@endpush
