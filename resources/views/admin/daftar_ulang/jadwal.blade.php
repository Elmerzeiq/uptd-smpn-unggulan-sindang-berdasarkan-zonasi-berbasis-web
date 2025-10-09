{{-- resources/views/admin/daftar-ulang/jadwal.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Jadwal Daftar Ulang')

@section('admin_content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Jadwal Daftar Ulang</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addJadwalModal">
                        <i class="fas fa-plus"></i> Tambah Jadwal
                    </button>
                </div>
                <div class="card-body">

                    @if($jadwalList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="jadwalTable">
                            <thead>
                                <tr>
                                    <th>Nama Sesi</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Kuota</th>
                                    <th>Terisi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwalList as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->nama_sesi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{
                                        \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                                    <td>{{ $jadwal->kuota }}</td>
                                    <td>
                                        <span class="badge bg-{{ $jadwal->isFull() ? 'danger' : 'success' }}">
                                            {{ $jadwal->terisi }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($jadwal->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="editJadwal({{ $jadwal->id }}, '{{ $jadwal->nama_sesi }}', '{{ $jadwal->tanggal }}', '{{ $jadwal->waktu_mulai }}', '{{ $jadwal->waktu_selesai }}', {{ $jadwal->kuota }}, {{ $jadwal->is_active ? 'true' : 'false' }}, '{{ $jadwal->keterangan }}')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            @if($jadwal->terisi == 0)
                                            <form action="{{ route('admin.daftar-ulang.jadwal.destroy', $jadwal->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar fa-3x mb-3"></i>
                        <p>Belum ada jadwal daftar ulang</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addJadwalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.daftar-ulang.jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_sesi" class="form-label">Nama Sesi</label>
                        <input type="text" class="form-control" id="nama_sesi" name="nama_sesi" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}"
                            required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kuota" class="form-label">Kuota</label>
                        <input type="number" class="form-control" id="kuota" name="kuota" min="1" max="200" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editJadwalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editJadwalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_sesi" class="form-label">Nama Sesi</label>
                        <input type="text" class="form-control" id="edit_nama_sesi" name="nama_sesi" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="edit_waktu_mulai" class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" id="edit_waktu_mulai" name="waktu_mulai" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="edit_waktu_selesai" class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" id="edit_waktu_selesai" name="waktu_selesai"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kuota" class="form-label">Kuota</label>
                        <input type="number" class="form-control" id="edit_kuota" name="kuota" min="1" max="200"
                            required>
                        <div class="form-text">Kuota tidak boleh kurang dari jumlah siswa yang sudah terdaftar</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active"
                                value="1">
                            <label class="form-check-label" for="edit_is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editJadwal(id, nama, tanggal, waktuMulai, waktuSelesai, kuota, isActive, keterangan) {
    document.getElementById('editJadwalForm').action = `/admin/daftar-ulang/jadwal/${id}`;
    document.getElementById('edit_nama_sesi').value = nama;
    document.getElementById('edit_tanggal').value = tanggal;
    document.getElementById('edit_waktu_mulai').value = waktuMulai;
    document.getElementById('edit_waktu_selesai').value = waktuSelesai;
    document.getElementById('edit_kuota').value = kuota;
    document.getElementById('edit_is_active').checked = isActive;
    document.getElementById('edit_keterangan').value = keterangan;

    new bootstrap.Modal(document.getElementById('editJadwalModal')).show();
}
</script>
@endsection
