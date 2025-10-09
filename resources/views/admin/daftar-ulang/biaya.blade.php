@extends('layouts.admin.app')

@section('title', 'Kelola Komponen Biaya')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fas fa-money-bill-wave"> Kelola Komponen Biaya Daftar Ulang</i></h4>

        </div>
     <div class="page-header d-flex justify-content-end">
        <ul class="breadcrumbs">
            <li>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBiayaModal">
                    <i class="fas fa-plus"></i>
                </button>
            </li>
            <li>
                <a href="{{ route('admin.daftar-ulang.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </li>
        </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Komponen Biaya</h4>
                    </div>
                    <div class="card-body">


                    @if($komponenBiaya->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Komponen</th>
                                    <th>Biaya</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($komponenBiaya as $komponen)
                                <tr>
                                    <td>
                                        {{ $komponen->nama_komponen }}
                                        <small class="d-block text-muted">{{ $komponen->keterangan }}</small>
                                    </td>
                                    <td>Rp {{ number_format($komponen->biaya, 0, ',', '.') }}</td>
                                    <td>
                                        @if($komponen->is_wajib)
                                        <span class="badge bg-danger">Wajib</span>
                                        @else
                                        <span class="badge bg-info">Opsional</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($komponen->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning"
                                            onclick="editBiaya({{ $komponen->toJson() }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                        <p>Belum ada komponen biaya yang ditambahkan.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Komponen Biaya -->
<div class="modal fade" id="addBiayaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.daftar-ulang.biaya.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Komponen Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_komponen" class="form-label">Nama Komponen</label>
                        <input type="text" class="form-control" name="nama_komponen" required>
                    </div>
                    <div class="mb-3">
                        <label for="biaya" class="form-label">Biaya (Rp)</label>
                        <input type="number" class="form-control" name="biaya" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_wajib" value="1"
                                id="is_wajib">
                            <label class="form-check-label" for="is_wajib">Biaya Wajib</label>
                        </div>
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

<!-- Modal Edit Komponen Biaya -->
<div class="modal fade" id="editBiayaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBiayaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Komponen Biaya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_komponen" class="form-label">Nama Komponen</label>
                        <input type="text" class="form-control" id="edit_nama_komponen" name="nama_komponen" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_biaya" class="form-label">Biaya (Rp)</label>
                        <input type="number" class="form-control" id="edit_biaya" name="biaya" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_wajib" value="1"
                                id="edit_is_wajib">
                            <label class="form-check-label" for="edit_is_wajib">Biaya Wajib</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
                                id="edit_is_active">
                            <label class="form-check-label" for="edit_is_active">Status Aktif</label>
                        </div>
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
                </div>

<script>
    function editBiaya(komponen) {
    document.getElementById('editBiayaForm').action = `/admin/daftar-ulang/biaya/${komponen.id}`;
    document.getElementById('edit_nama_komponen').value = komponen.nama_komponen;
    document.getElementById('edit_biaya').value = parseFloat(komponen.biaya);
    document.getElementById('edit_keterangan').value = komponen.keterangan;
    document.getElementById('edit_is_wajib').checked = komponen.is_wajib;
    document.getElementById('edit_is_active').checked = komponen.is_active;

    new bootstrap.Modal(document.getElementById('editBiayaModal')).show();
}
</script>
@endsection
