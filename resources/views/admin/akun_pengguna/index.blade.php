@extends('layouts.admin.app')
@section('title', 'Manajemen Akun Pengguna')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Manajemen Akun Pengguna</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Daftar Akun Pengguna</h4>
                            <a href="{{ route('admin.akun-pengguna.create') }}"
                                class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i> Tambah Akun Admin
                            </a>
                        </div>
                        <form method="GET" action="{{ route('admin.akun-pengguna.index') }}" class="mt-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label for="search_akun_filter" class="form-label">Cari Pengguna</label>
                                    <input type="text" name="search_akun" id="search_akun_filter" class="form-control"
                                        placeholder="Nama/Email/NISN..." value="{{ request('search_akun') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="role_filter_akun" class="form-label">Filter Role</label>
                                    <select name="role_filter" id="role_filter_akun" class="form-select">
                                        <option value="">Semua Role</option>
                                        @foreach($roleOptions as $role)
                                        <option value="{{ $role }}" {{ request('role_filter')==$role ? 'selected' : ''
                                            }}>{{ ucfirst($role) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-round w-100 mb-1"><i
                                            class="fas fa-search"></i> Cari</button>
                                    <a href="{{ route('admin.akun-pengguna.index') }}"
                                        class="btn btn-secondary btn-round w-100"><i class="fas fa-sync-alt"></i>
                                        Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="akunPenggunaTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        {{-- <th>Foto Profil</th> --}}
                                        <th>Nama Lengkap</th>
                                        <th>Email (Admin)</th>
                                        <th>NISN (Siswa)</th>
                                        <th class="text-center">Role</th>
                                        <th>Tgl. Dibuat</th>
                                        <th style="width: 15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $index => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        {{-- <td>
                                        @if($user->foto_profil && Storage::disk('public')->exists('profil/' . $user->foto_profil))
                                        <a href="{{ Storage::url('profil/' . $user->foto_profil) }}" target="_blank">
                                            <img src="{{ Storage::url('profil/' . $user->foto_profil) }}" width="100" class="img-thumbnail">
                                        </a>
                                        @else
                                        <span>Tidak ada foto</span>
                                        @endif
                                        </td> --}}
                                        <td>{{ $user->nama_lengkap }}</td>
                                        <td>{{ $user->email ?? '-' }}</td>
                                        <td>{{ $user->nisn ?? '-' }}</td>
                                        <td class="text-center"><span class="badge {{ $user->role == 'admin' ? 'badge-primary' : 'badge-info' }}">{{ ucfirst($user->role) }}</span></td>
                                        <td>{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="form-button-action">
                                                <a href="{{ route('admin.akun-pengguna.edit', $user->id) }}" data-bs-toggle="tooltip" title="Edit Akun" class="btn btn-link btn-primary btn-lg p-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if(Auth::id() !== $user->id)
                                                <button type="button" class="btn btn-link btn-danger delete-button p-1" data-bs-toggle="tooltip" title="Hapus Akun" data-form-id="delete-form-akun-{{ $user->id }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <form id="delete-form-akun-{{ $user->id }}" action="{{ route('admin.akun-pengguna.destroy', $user->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                @else
                                                <button type="button" class="btn btn-link btn-secondary p-1" data-bs-toggle="tooltip" title="Tidak dapat menghapus akun sendiri" disabled>
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center p-3">Tidak ada data akun pengguna ditemukan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $('#akunPenggunaTable .delete-button').on('click', function(e) {
            e.preventDefault();
            var formId = $(this).data('form-id');
            var form = $('#' + formId);
            var itemName = $(this).closest('tr').find('td:nth-child(2)').text().trim(); // Ambil Nama Lengkap

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: "Anda akan menghapus akun pengguna: <br><strong>" + itemName + "</strong><br>Tindakan ini tidak dapat dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

