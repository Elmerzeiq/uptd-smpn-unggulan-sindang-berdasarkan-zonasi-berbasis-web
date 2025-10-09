@extends('layouts.admin.app')
@section('title', 'Detail Berkas - ' . $siswa->nama_lengkap)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Berkas Siswa</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="{{ route('admin.berkas.index') }}">Berkas Siswa</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item active">Detail</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="shadow card">
                    <div class="card-header">
                        <h5 class="mb-0 card-title">Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            @if($siswa->biodata && $siswa->biodata->foto_siswa &&
                            Storage::exists($siswa->biodata->foto_siswa))
                            <img src="{{ Storage::url($siswa->biodata->foto_siswa) }}" alt="Foto Siswa"
                                class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                            <div class="text-white bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px; font-size: 2rem; font-weight: bold;">
                                {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                            </div>
                            @endif
                        </div>

                        <table class="table table-borderless table-sm">
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>{{ $siswa->nisn }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Pendaftaran</strong></td>
                                <td><span class="badge bg-primary">{{ $siswa->no_pendaftaran }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Jalur Pendaftaran</strong></td>
                                <td><span class="badge bg-info">{{ ucwords(str_replace('_', ' ',
                                        $siswa->jalur_pendaftaran)) }}</span></td>
                            </tr>
                            {{-- <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $siswa->email }}</td>
                            </tr> --}}
                            <tr>
                                <td><strong>Asal Sekolah</strong></td>
                                <td>{{ $siswa->biodata->asal_sekolah ?? '-' }}</td>
                            </tr>

                            {{-- PERBAIKAN: Gunakan optional() untuk memeriksa biodata dan koordinat --}}
                            @if($siswa->jalur_pendaftaran === 'domisili' && optional($siswa->biodata)->koordinat_rumah)
                            <tr>
                                <td><strong>Koordinat Rumah</strong></td>
                                <td>{{ $siswa->biodata->koordinat_rumah }}</td>
                            </tr>
                            @endif

                        </table>

                        <div class="mt-3">
                            <strong>Status Pendaftaran:</strong><br>
                            @php
                            $statusBadges = [
                            'menunggu_verifikasi_berkas' => ['class' => 'bg-warning', 'icon' => 'fas fa-clock', 'text'
                            => 'Menunggu Verifikasi'],
                            'berkas_diverifikasi' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle', 'text'
                            => 'Berkas Diverifikasi'],
                            'berkas_tidak_lengkap' => ['class' => 'bg-danger', 'icon' => 'fas fa-exclamation-triangle',
                            'text' => 'Berkas Tidak Lengkap'],
                            'lulus_seleksi' => ['class' => 'bg-success', 'icon' => 'fas fa-trophy', 'text' => 'Lulus
                            Seleksi'],
                            'tidak_lulus_seleksi' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle', 'text' =>
                            'Tidak Lulus'],
                            ];
                            $currentStatus = $statusBadges[$siswa->status_pendaftaran] ?? ['class' => 'bg-secondary',
                            'icon' => 'fas fa-question', 'text' => 'Unknown'];
                            @endphp
                            <span class="badge {{ $currentStatus['class'] }} p-2">
                                <i class="{{ $currentStatus['icon'] }} me-1"></i>
                                {{ $currentStatus['text'] }}
                            </span>
                        </div>

                        @if($siswa->berkas && in_array($siswa->status_pendaftaran, ['menunggu_verifikasi_berkas',
                        'berkas_tidak_lengkap']))
                        <div class="gap-2 mt-4 d-grid">
                            <button type="button" class="btn btn-success" onclick="showVerifikasiModal('verifikasi')">
                                <i class="fas fa-check-circle me-1"></i> Verifikasi Berkas
                            </button>
                            <button type="button" class="btn btn-warning" onclick="showVerifikasiModal('tolak')">
                                <i class="fas fa-times-circle me-1"></i> Tolak Berkas
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                @if($siswa->berkas)
                <div class="mt-4 shadow card">
                    <div class="card-header">
                        <h5 class="mb-0 card-title">Summary Berkas</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center row">
                            <div class="col-6">
                                <h4 class="text-primary">{{ $progress['berkas_wajib_terupload'] +
                                    $progress['berkas_opsional_terupload'] }}/{{ $progress['total_berkas'] }}</h4>
                                <small>Total Berkas</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-{{ $progress['is_wajib_lengkap'] ? 'success' : 'warning' }}">
                                    {{ $progress['berkas_wajib_terupload'] }}/{{ $progress['berkas_wajib'] }}
                                </h4>
                                <small>Berkas Wajib</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $progress['is_wajib_lengkap'] ? 'bg-success' : 'bg-warning' }}"
                                    style="width: {{ $progress['persentase_total'] }}%">
                                </div>
                            </div>
                            <small class="text-muted">
                                Kelengkapan: {{ $progress['persentase_total'] }}%
                            </small>
                        </div>

                        @if($progress['is_wajib_lengkap'])
                        <div class="mt-3 mb-0 alert alert-success small">
                            <i class="fas fa-check-circle me-1"></i> Semua berkas wajib telah terupload
                        </div>
                        @else
                        <div class="mt-3 mb-0 alert alert-warning small">
                            <i class="fas fa-exclamation-triangle me-1"></i> Berkas wajib belum lengkap
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-8">
                <div class="shadow card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Daftar Berkas</h5>
                            <a href="{{ route('admin.berkas.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(empty($definisiBerkas))
                        <div class="py-4 text-center">
                            <i class="mb-3 fas fa-info-circle fa-2x text-muted"></i>
                            <h5 class="text-muted">Tidak ada daftar berkas</h5>
                            <p class="text-muted">Jalur pendaftaran siswa belum memiliki daftar berkas yang ditentukan.
                            </p>
                        </div>
                        @else
                        @foreach($definisiBerkas as $field => $details)
                        @php
                        $isRequired = isset($details['required']) ? $details['required'] : false;
                        if ($field === 'file_ijazah_mda_pernyataan' || $field === 'file_suket_baca_quran_mda') {
                        $isRequired = $siswa->biodata && strtolower($siswa->biodata->agama) === 'islam';
                        }
                        $hasFile = false;
                        $fileData = null;

                        if ($siswa->berkas) {
                        if (isset($details['multiple']) && $details['multiple']) {
                        $files = json_decode($siswa->berkas->$field, true);
                        $hasFile = !empty($files);
                        $fileData = $files;
                        } else {
                        $hasFile = !empty($siswa->berkas->$field);
                        $fileData = $siswa->berkas->$field;
                        }
                        }
                        @endphp

                        <div
                            class="berkas-item mb-4 p-3 border rounded {{ $hasFile ? 'border-success bg-light' : ($isRequired ? 'border-danger' : 'border-secondary') }}">
                            <div class="mb-2 d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $loop->iteration }}. {{ $details['label'] }}
                                        @if($isRequired)
                                        <span class="badge bg-danger ms-2">Wajib</span>
                                        @else
                                        <span class="badge bg-secondary ms-2">Opsional</span>
                                        @endif
                                    </h6>
                                    <p class="mb-2 text-muted small">{{ $details['keterangan'] ?? 'Tidak ada keterangan'
                                        }}</p>
                                </div>
                                <div class="status-indicator">
                                    @if($hasFile)
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                    @else
                                    <i
                                        class="fas fa-times-circle text-{{ $isRequired ? 'danger' : 'secondary' }} fa-2x"></i>
                                    @endif
                                </div>
                            </div>

                            @if($hasFile)
                            <div class="uploaded-files">
                                @if(isset($details['multiple']) && $details['multiple'])
                                <div class="row">
                                    @foreach($fileData as $index => $filePath)
                                    <div class="mb-2 col-md-6">
                                        <div class="p-2 border rounded file-card">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="file-info flex-grow-1">
                                                    <div class="mb-1 d-flex align-items-center">
                                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                                        <small class="fw-bold">{{ basename($filePath) }}</small>
                                                    </div>
                                                    @if(Storage::disk('public')->exists($filePath))
                                                    <small class="text-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        File tersedia ({{
                                                        number_format(Storage::disk('public')->size($filePath) / 1024,
                                                        1) }} KB)
                                                    </small>
                                                    @else
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        File tidak ditemukan
                                                    </small>
                                                    @endif
                                                </div>
                                                <div class="file-actions">
                                                    @if(Storage::disk('public')->exists($filePath))
                                                    <a href="{{ route('admin.berkas.download', [$siswa->id, $field]) }}" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="tooltip" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="{{ Storage::url($filePath) }}" target="_blank"
                                                        class="btn btn-info btn-sm" data-bs-toggle="tooltip"
                                                        title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endif
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="deleteFile('{{ $field }}', {{ $index }})"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="p-3 border rounded file-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="file-info flex-grow-1">
                                            <div class="mb-2 d-flex align-items-center">
                                                <i class="fas fa-file-alt text-primary me-2 fa-2x"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ basename($fileData) }}</h6>
                                                    @if(Storage::disk('public')->exists($fileData))
                                                    <small class="text-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        File tersedia ({{
                                                        number_format(Storage::disk('public')->size($fileData) / 1024,
                                                        1) }} KB)
                                                    </small>
                                                    @else
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        File tidak ditemukan
                                                    </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="file-actions">
                                            @if(Storage::disk('public')->exists($fileData))
                                                <a href="{{ route('admin.berkas.download', [$siswa->id, $field]) }}"
                                                class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <a href="{{ Storage::url($fileData) }}" target="_blank"
                                                class="btn btn-info btn-sm me-1" data-bs-toggle="tooltip" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="deleteFile('{{ $field }}')" data-bs-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @else
                            <div class="py-3 text-center">
                                <i class="mb-2 fas fa-upload text-muted fa-2x"></i>
                                <p class="mb-0 text-muted">Belum ada file yang diupload</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifikasiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifikasiTitle">Verifikasi Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="verifikasiForm" action="{{ route('admin.berkas.verifikasi', $siswa->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="aksi" id="verifikasiAksi">
                    <div class="form-group">
                        <label for="catatan">Catatan untuk Siswa</label>
                        <textarea class="textarea" name="catatan" id="catatan" rows="4"
                            placeholder="Berikan catatan atau alasan verifikasi/penolakan..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <span id="verifikasiInfo">Pastikan semua berkas telah diperiksa dengan teliti.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="confirmVerifikasi">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteFileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus file ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    File yang dihapus akan hilang permanen dari sistem.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus File</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentDeleteField = '';
    let currentDeleteIndex = null;

    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $('#confirmDelete').click(function() {
            submitDeleteFile();
        });
    });

    function showVerifikasiModal(aksi) {
        const modal = $('#verifikasiModal');
        const title = $('#verifikasiTitle');
        const aksiInput = $('#verifikasiAksi');
        const confirmBtn = $('#confirmVerifikasi');
        const info = $('#verifikasiInfo');

        if (aksi === 'verifikasi') {
            title.text('Verifikasi Berkas Siswa');
            confirmBtn.removeClass('btn-warning').addClass('btn-success');
            confirmBtn.text('Verifikasi Berkas');
            info.text('Berkas akan diverifikasi dan siswa dapat melanjutkan ke tahap selanjutnya.');
        } else {
            title.text('Tolak Berkas Siswa');
            confirmBtn.removeClass('btn-success').addClass('btn-warning');
            confirmBtn.text('Tolak Berkas');
            info.text('Berkas akan ditolak dan siswa diminta untuk memperbaiki.');
        }

        aksiInput.val(aksi);
        modal.modal('show');
    }

    function deleteFile(field, index = null) {
        currentDeleteField = field;
        currentDeleteIndex = index;
        $('#deleteFileModal').modal('show');
    }

    function submitDeleteFile() {
        const form = $('<form>', {
            method: 'POST',
            action: `/admin/berkas/{{ $siswa->id }}/delete-file/${currentDeleteField}`
        });

        form.append($('<input>', {type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content')}));
        form.append($('<input>', {type: 'hidden', name: '_method', value: 'DELETE'}));

        if (currentDeleteIndex !== null) {
            form.append($('<input>', {type: 'hidden', name: 'file_index', value: currentDeleteIndex}));
        }

        $('body').append(form);
        form.submit();
    }
</script>
@endpush
