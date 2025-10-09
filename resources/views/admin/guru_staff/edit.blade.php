@extends('layouts.admin.app')

@section('title', 'Edit Data: ' . $item->nama)

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title"><i class="fa fa-edit "> Edit Data Guru & Staff </i></h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('admin.dashboard') }}"><i class="flaticon-home"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Data: {{ $item->nama }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.guru-staff.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.guru_staff._form', ['item' => $item])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
