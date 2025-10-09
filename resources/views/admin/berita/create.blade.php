@extends('layouts.admin.app')
@section('title', 'Tulis Berita Baru')

@push('css')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
@endpush

@section('admin_content')
<div class="container">
        <div class="page-inner">
                <div class="page-header">
                        <h4 class="page-title"><i class="fa fa-newspaper"> Tambah Berita Sekolah</i></h4>
                        <ul class="breadcrumbs">
                                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
                        </ul>
                </div>
                <div class="row">
                        <div class="col-md-12">
                                <div class="card">
                                        <div class="card-header">
                                                <div class="card-title">Form Tulis Berita Baru</div>
                                        </div>
                                        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="card-body">
                                                        @include('admin.berita._form', ['item' => new \App\Models\Berita()])
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
</div>
@endsection
