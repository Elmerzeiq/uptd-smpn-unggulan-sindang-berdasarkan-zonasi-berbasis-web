{{-- resources/views/admin/pengumuman-hasil/show.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Detail Pengumuman Hasil SPMB')
@section('title_header_admin', 'Detail Pengumuman Hasil ')

@section('admin_content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Detail Pengumuman Hasil</h4>
        <ul class="breadcrumbs">
            <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="{{ route('admin.pengumuman-hasil.index') }}">Hasil SPMB</a></li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a>Detail</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0"><i class="fas fa-trophy me-2"></i>{{ $pengumumanHasil->judul }}</h4>
                </div>
                <div class="card-body">
                    <dl class="row mb-4">
                        <dt class="col-sm-3">Target Penerima</dt>
                        <dd class="col-sm-9"><span class="badge {{ $pengumumanHasil->target_badge_class }}">{{
                                $pengumumanHasil->target_text }}</span></dd>
                        <dt class="col-sm-3">Tanggal Publikasi</dt>
                        <dd class="col-sm-9">{{ $pengumumanHasil->tanggal ? $pengumumanHasil->tanggal->format('d M Y
                            H:i') : 'Segera' }}</dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">@if($pengumumanHasil->aktif)<span
                                class="badge bg-success">Aktif</span>@else<span class="badge bg-secondary">Tidak
                                Aktif</span>@endif</dd>
                        <dt class="col-sm-3">Admin Pembuat</dt>
                        <dd class="col-sm-9">{{ optional($pengumumanHasil->admin)->nama_lengkap ?? 'N/A' }}</dd>
                    </dl>
                    <hr>
                    <h5><i class="fas fa-file-alt me-2"></i>Isi Pengumuman</h5>
                    <div class="content-display border p-4 rounded bg-light">{!! $pengumumanHasil->isi !!}</div>
                </div>
                <div class="card-action">
                    <a href="{{ route('admin.pengumuman-hasil.edit', $pengumumanHasil->id) }}"
                        class="btn btn-warning btn-round"><i class="fas fa-edit me-2"></i>Edit</a>
                    <a href="{{ route('admin.pengumuman-hasil.index') }}"
                        class="btn btn-secondary btn-round">Kembali</a>
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
</style>
@endpush
