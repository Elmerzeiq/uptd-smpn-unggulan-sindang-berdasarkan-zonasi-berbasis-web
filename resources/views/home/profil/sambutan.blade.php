@extends('home.layouts.app')

@section('home')

<div class="breadcrumb-banner-area teachers">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Kepala Sekolah</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Kepala Sekolah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Principal Details Area Start -->
<div class="teacher-details-area section-padding">
    <div class="container">
        <div class="row">
            <!-- Image Section -->
            <div class="col-lg-4 col-md-12">
                <div class="text-center">
                    @if($kepalaSekolah && $kepalaSekolah->image)
                    <img src="{{ Storage::url($kepalaSekolah->image) }}" alt="{{ $kepalaSekolah->nama }}" class="img-fluid principal-photo"
                    style="border-radius: 8px; max-width: 300px; max-height: 300px; object-fit: cover;">
                    {{-- <img src="{{ asset('uploads/images/' . $guru_staff->image) }}"
                        alt="Foto {{ $kepalaSekolah->nama }}" class="img-fluid principal-photo"
                        style="border-radius: 8px; max-width: 300px; max-height: 300px; object-fit: cover;" /> --}}
                    @else
                    <img src="{{ asset('img/teacher/teacher-details.jpg') }}" alt="Foto Kepala Sekolah"
                        class="img-fluid principal-photo"
                        style="border-radius: 8px; max-width: 300px; max-height: 300px; object-fit: cover;" />
                    @endif
                </div>
                <div class="mt-3 teacher-details-info" style="text-align: center;">
                    <h3>{{ $kepalaSekolah->nama ?? 'Nama Kepala Sekolah' }}</h3>
                    <h4><i>{{ $kepalaSekolah->jabatan ?? 'Kepala Sekolah' }}</i></h4>
                    @if($kepalaSekolah && $kepalaSekolah->nip)
                    <p><strong>NIP:</strong> {{ $kepalaSekolah->nip }}</p>
                    @endif
                </div>
            </div>

            <!-- Welcome Message Section -->
            <div class="col-lg-8 col-md-12">
                <div class="teacher-about-info" style="text-align: left;">
                    <div class="single-title">
                        <h3>Kata Sambutan Kepala Sekolah</h3>
                    </div>
                    <div class="welcome-message">
                        @if($kepalaSekolah && $kepalaSekolah->sambutan)
                        {!! nl2br(e($kepalaSekolah->sambutan)) !!}
                        @else
                        <p class="text-muted">Tidak ada sambutan yang tersedia saat ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Principal Details Area -->

{{-- <!-- Teachers Section -->
@if($teachers->count() > 0)
<div class="teachers-column-carousel-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title-wrapper">
                    <div class="section-title">
                        <h2>Guru dan Tenaga Pendidik</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="teacher-fullwidth-area">
            <div class="container">
                <div class="row">
                    @foreach($teachers as $teacher)
                    <div class="mb-4 col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="text-center single-teachers-column">
                            <div class="teachers-image-column">
                                @if($teacher->image)
                                <img src="{{ asset('uploads/images/' . $teacher->image) }}"
                                    alt="Foto {{ $teacher->nama }}" class="img-fluid teacher-photo"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" />
                                @else
                                <img src="{{ asset('img/teacher/default-teacher.jpg') }}"
                                    alt="Foto {{ $teacher->nama }}" class="img-fluid teacher-photo"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" />
                                @endif
                            </div>
                            <div class="mt-3 teacher-column-carousel-text">
                                <h4>{{ $teacher->nama }}</h4>
                                <p><i>{{ $teacher->jabatan }}</i></p>
                                @if($teacher->nip)
                                <small class="text-muted">NIP: {{ $teacher->nip }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Staff Section -->
@php
$nonTeachingStaff = $staffs->where('kategori', 'staff');
@endphp --}}

{{-- @if($nonTeachingStaff->count() > 0)
<div class="teachers-column-carousel-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title-wrapper">
                    <div class="section-title">
                        <h2>Tenaga Kependidikan</h2>
                    </div>
                </div>
            </div>
        </div> --}}
{{--
        <div class="teacher-fullwidth-area">
            <div class="container">
                <div class="row">
                    @foreach($nonTeachingStaff as $staff)
                    <div class="mb-4 col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="text-center single-teachers-column">
                            <div class="teachers-image-column">
                                @if($staff->image)
                                <img src="{{ asset('uploads/images/' . $staff->image) }}" alt="Foto {{ $staff->nama }}"
                                    class="img-fluid staff-photo"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" />
                                @else
                                <img src="{{ asset('img/staff/default-staff.jpg') }}" alt="Foto {{ $staff->nama }}"
                                    class="img-fluid staff-photo"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" />
                                @endif
                            </div>
                            <div class="mt-3 teacher-column-carousel-text">
                                <h4>{{ $staff->nama }}</h4>
                                <p><i>{{ $staff->jabatan }}</i></p>
                                @if($staff->nip)
                                <small class="text-muted">NIP: {{ $staff->nip }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div> --}}
    </div>
</div>
{{-- @endif --}}

@endsection
