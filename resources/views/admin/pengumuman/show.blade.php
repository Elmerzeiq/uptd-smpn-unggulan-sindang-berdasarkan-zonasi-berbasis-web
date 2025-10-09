{{-- resources/views/admin/pengumuman/show.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Detail Pengumuman')
@section('title_header_admin', 'Detail Pengumuman')

@section('admin_content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Detail Pengumuman</h4>
        <ul class="breadcrumbs">
            <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="{{ route('admin.pengumuman.index') }}">Pengumuman</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a>Detail</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            {{-- Preview Card --}}
            <div class="card mb-4">
                <div class="card-header {{ $pengumuman->tipe_badge_class }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i class="{{ $pengumuman->tipe_icon }} me-2"></i>{{
                            $pengumuman->judul }}</h4>
                        <div><span class="badge bg-light text-dark me-2">{{ strtoupper($pengumuman->tipe) }}</span><span
                                class="badge bg-light text-dark">{{ $pengumuman->target_text }}</span></div>
                    </div>
                    <small class="text-white-50"><i class="fas fa-calendar me-1"></i>{{
                        $pengumuman->getFormattedTanggalAttribute() }}</small>
                </div>
                <div class="card-body">
                    <div class="content-display">{!! $pengumuman->isi !!}</div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center"><small class="text-muted"><i
                                class="fas fa-user me-1"></i>Dibuat oleh: {{ optional($pengumuman->admin)->nama_lengkap
                            ?? 'N/A' }}</small><small class="text-muted"><i class="fas fa-clock me-1"></i>{{
                            $pengumuman->created_at->format('d M Y H:i') }}</small></div>
                </div>
            </div>

            {{-- Detail Information --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fas fa-info-circle me-2"></i>Informasi Detail</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Tipe</dt>
                        <dd class="col-sm-9"><span class="badge {{ $pengumuman->tipe_badge_class }}">{{
                                ucwords($pengumuman->tipe) }}</span></dd>
                        <dt class="col-sm-3">Target</dt>
                        <dd class="col-sm-9"><span class="badge {{ $pengumuman->target_badge_class }}">{{
                                $pengumuman->target_text }}</span></dd>
                        <dt class="col-sm-3">Tanggal Tayang</dt>
                        <dd class="col-sm-9">{{ $pengumuman->tanggal ? $pengumuman->tanggal->format('d M Y H:i') :
                            'Segera' }}</dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            @if($pengumuman->status_text == 'aktif')<span class="badge bg-success"><i
                                    class="fas fa-check-circle me-1"></i>Aktif</span>
                            @elseif($pengumuman->status_text == 'terjadwal')<span class="badge bg-info"><i
                                    class="fas fa-clock me-1"></i>Terjadwal</span>
                            @else<span class="badge bg-secondary"><i class="fas fa-pause me-1"></i>Tidak
                                Aktif</span>@endif
                        </dd>
                        <dt class="col-sm-3">Dilihat</dt>
                        <dd class="col-sm-9"><span class="badge bg-info"><i class="fas fa-eye me-1"></i>{{
                                $pengumuman->views_count ?? 0 }} kali</span></dd>
                    </dl>
                </div>
                <div class="card-action">
                    <a href="{{ route('admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-warning btn-round"><i
                            class="fas fa-edit me-2"></i>Edit</a>
                    <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST"
                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">@csrf
                        @method('DELETE')<button type="submit" class="btn btn-danger btn-round"><i
                                class="fas fa-trash me-2"></i>Hapus</button></form>
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary btn-round"><i
                            class="fas fa-arrow-left me-2"></i>Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-display {
        line-height: 1.6;
    }

    .content-display h1,
    .content-display h2,
    .content-display h3 {
        margin-top: 20px;
        margin-bottom: 15px;
    }

    .content-display p {
        margin-bottom: 12px;
    }
</style>
@endpush
