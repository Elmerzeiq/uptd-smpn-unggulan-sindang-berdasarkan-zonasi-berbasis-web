@extends('layouts.admin.app')
@section('title', 'Detail Berita: ' . Str::limit($item->judul, 40))

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-newspaper"> Detail Berita</i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">{{ $item->judul }}</h4>
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-edit"></i> Edit Berita
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <p class="text-muted mb-1">
                                    <i class="fas fa-calendar-alt me-1"></i> Dipublikasikan pada: {{
                                    $item->tanggal->format('d F Y') }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user-edit me-1"></i> Oleh: {{ $item->author->nama_lengkap ?? 'N/A'
                                    }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-tag me-1"></i> Status:
                                    @if($item->status == 'published')
                                    <span class="badge bg-success text-white">Published</span>
                                    @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                    @endif
                                </p>
                            </div>
                            @if($item->image)
                            <div class="col-md-4 text-md-end">
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}"
                                    class="img-fluid rounded shadow-sm" style="max-height: 200px; width:auto;">
                            </div>
                            @endif
                        </div>

                        <h5 class="mt-4">Deskripsi Singkat:</h5>
                        <p class="lead" style="font-size: 1.1rem;"><em>{{ $item->deskripsi }}</em></p>
                        <hr>

                        <h5 class="mt-4">Isi Berita Lengkap:</h5>
                        <div class="berita-isi-lengkap" style="line-height: 1.8;">
                            {!! $item->isi !!} {{-- Output HTML dari TinyMCE --}}
                        </div>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary btn-round"><i
                                class="fas fa-arrow-left"></i> Kembali ke Daftar Berita</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
