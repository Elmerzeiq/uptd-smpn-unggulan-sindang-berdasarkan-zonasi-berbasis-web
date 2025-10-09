@extends('home.layouts.app')

@section('home')
<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Ekstrakurikuler</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Ekstrakurikuler</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="kurikulum-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-wrapper">
                    <!-- Header Section -->
                    <div class="mb-5 text-center section-header">
                        <h2 class="section-title">Kegiatan Ekstrakurikuler SMP Unggulan</h2>
                        <div class="title-underline"></div>
                        <p class="section-subtitle">SMP Unggulan dikenal sebagai sekolah yang aktif membina bakat dan
                            minat siswa melalui berbagai
                            kegiatan ekstrakurikuler.
                            Dengan rekam jejak prestasi di tingkat daerah, provinsi, hingga nasional, para siswa kami
                            telah
                            mengharumkan nama sekolah
                            dalam bidang olahraga, seni, sains, dan organisasi. Kami percaya, kegiatan ekstrakurikuler
                            bukan
                            hanya sarana hiburan,
                            tetapi juga tempat untuk melatih kepemimpinan, disiplin, dan kerja sama tim.</p>
                    </div>








                    <!-- Ekskul Card Area Start -->
                    <div class="section-padding">
                        <div class="container">
                            <div class="row">
                                @forelse($ekskuls as $item)
                                <div class="mb-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="border-0 shadow-sm card h-100">
                                        @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->judul }}"
                                            class="card-img-top" style="height: 250px; object-fit: cover;">
                                        @else
                                        <img src="{{ asset('images/default-ekskul.jpg') }}" alt="{{ $item->judul }}"
                                            class="card-img-top" style="height: 250px; object-fit: cover;">
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $item->judul }}</h5>
                                            @if($item->kategori)
                                            <span class="mb-2 badge badge-primary">{{ $item->kategori }}</span>
                                            @endif
                                            <p class="card-text text-muted" style="flex-grow: 1;">
                                                {{ Str::limit($item->deskripsi, 100) }}
                                            </p>
                                            <a href="{{ route('ekskul.show', $item->id) }}"
                                                class="mt-auto btn btn-primary">
                                                Lihat Selengkapnya
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center col-12">
                                    <p>Belum ada data ekstrakurikuler.</p>
                                </div>
                                @endforelse
                            </div>

                            {{--
                            <!-- Pagination -->
                            @if($ekskuls->hasPages())
                            <div class="row">
                                <div class="text-center col-12">
                                    {{ $ekskuls->links() }}
                                </div>
                            </div>
                            @endif --}}
                        </div>
                    </div>
                    <!-- End of Ekskul Card Area -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
