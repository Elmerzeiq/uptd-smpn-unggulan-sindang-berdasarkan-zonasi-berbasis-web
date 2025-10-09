{{-- Teacher & Staff Table View --}}
@extends('home.layouts.app')

@section('home')
<!--End of Header Area-->
<!--Breadcrumb Banner Area Start-->
<div class="breadcrumb-banner-area teachers">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-text">
                    <h1 class="text-center">Guru & Staff</h1>
                    <div class="breadcrumb-bar">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Guru & Staff</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End of Breadcrumb Banner Area-->

<div class="teacher-staff-area section-padding">
    <div class="container">


        {{-- Guru Section --}}
        @if(isset($gurus) && $gurus->count() > 0)
        <div class="section-wrapper mb-5">
            <div class="section-title text-center mb-4">
                <h2 class="main-title">Daftar Guru</h2>
                <div class="title-underline"></div>
                <p class="section-description">Tim pengajar yang berpengalaman dan berkualitas</p>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover custom-table">
                    <thead class="table-header">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama</th>
                            <th width="35%">Jabatan</th>
                            <th width="20%">NIP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gurus as $index => $guru)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-weight-bold">{{ $guru->nama }}</td>
                            <td>{{ $guru->jabatan }}</td>
                            <td>{{ $guru->nip ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-info mt-3">
                <p class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Total Guru: <strong>{{ $gurus->count() }}</strong> orang
                </p>
            </div>
        </div>
        @endif

        {{-- Staff Section --}}
        @if(isset($staff) && $staff->count() > 0)
        <div class="section-wrapper mb-5">
            <div class="section-title text-center mb-4">
                <h2 class="main-title">Daftar Staff</h2>
                <div class="title-underline"></div>
                <p class="section-description">Tim administrasi dan pendukung yang profesional</p>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover custom-table">
                    <thead class="table-header">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama</th>
                            <th width="35%">Jabatan</th>
                            <th width="20%">NIP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $index => $staffMember)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-weight-bold">{{ $staffMember->nama }}</td>
                            <td>{{ $staffMember->jabatan }}</td>
                            <td>{{ $staffMember->nip ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-info mt-3">
                <p class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Total Staff: <strong>{{ $staff->count() }}</strong> orang
                </p>
            </div>
        </div>
        @endif

        {{-- Combined Table Alternative (Optional) --}}
        {{-- Uncomment if you want all in one table
        @if((isset($gurus) && $gurus->count() > 0) || (isset($staff) && $staff->count() > 0))
        <div class="section-wrapper mb-5">
            <div class="section-title text-center mb-4">
                <h2 class="main-title">Semua Guru & Staff</h2>
                <div class="title-underline"></div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover custom-table">
                    <thead class="table-header">
                        <tr>
                            <th width="5%">No</th>
                            <th width="35%">Nama</th>
                            <th width="30%">Jabatan</th>
                            <th width="15%">Kategori</th>
                            <th width="15%">NIP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @if(isset($gurus))
                        @foreach($gurus as $guru)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="font-weight-bold">{{ $guru->nama }}</td>
                            <td>{{ $guru->jabatan }}</td>
                            <td><span class="badge badge-primary">Guru</span></td>
                            <td>{{ $guru->nip ?? '-' }}</td>
                        </tr>
                        @endforeach
                        @endif
                        @if(isset($staff))
                        @foreach($staff as $staffMember)
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td class="font-weight-bold">{{ $staffMember->nama }}</td>
                            <td>{{ $staffMember->jabatan }}</td>
                            <td><span class="badge badge-secondary">Staff</span></td>
                            <td>{{ $staffMember->nip ?? '-' }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        --}}

        {{-- Empty State --}}
        @if((!isset($gurus) || $gurus->count() === 0) && (!isset($staff) || $staff->count() === 0))
        <div class="empty-state">
            <div class="empty-content text-center">
                <div class="empty-icon">
                    <i class="fas fa-users fa-4x text-muted"></i>
                </div>
                <h3 class="empty-title">Belum Ada Data</h3>
                <p class="empty-description">Data guru dan staff akan segera ditambahkan.</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Custom Styles --}}
<style>
    /* Main Section Styling */
    .teacher-staff-area {
        background-color: #f8f9fa;
        min-height: 500px;
    }

    .section-wrapper {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    /* Section Title */
    .section-title .main-title {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        position: relative;
    }

    .title-underline {
        width: 80px;
        height: 4px;
        background: linear-gradient(45deg, #3498db, #2980b9);
        margin: 0 auto 15px;
        border-radius: 2px;
    }

    .section-description {
        color: #7f8c8d;
        font-size: 16px;
        margin: 0;
    }

    /* Custom Table Styling */
    .custom-table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: none;
        margin-bottom: 0;
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-header th {
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 14px;
        padding: 18px 15px;
        vertical-align: middle;
    }

    .custom-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }

    .custom-table tbody tr:hover {
        background-color: #f1f3ff;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .custom-table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-color: #ecf0f1;
        font-size: 14px;
    }

    .custom-table tbody td.font-weight-bold {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Table Info */
    .table-info {
        padding: 15px 20px;
        background: #ecf0f1;
        border-radius: 8px;
        margin-top: 20px;
    }

    .table-info p {
        margin: 0;
        font-size: 14px;
    }

    .table-info i {
        margin-right: 8px;
        color: #3498db;
    }

    /* Principal Welcome */

    /* Badge Styling */
    .badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .badge-primary {
        background: linear-gradient(45deg, #3498db, #2980b9);
        border: none;
    }

    .badge-secondary {
        background: linear-gradient(45deg, #95a5a6, #7f8c8d);
        border: none;
    }

    /* Empty State */
    .empty-state {
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 15px;
        margin: 30px 0;
    }

    .empty-content {
        padding: 40px;
    }

    .empty-icon {
        margin-bottom: 20px;
    }

    .empty-title {
        color: #2c3e50;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .empty-description {
        color: #7f8c8d;
        font-size: 16px;
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .section-wrapper {
            padding: 20px 15px;
            margin-bottom: 20px;
        }

        .section-title .main-title {
            font-size: 24px;
        }

        .custom-table {
            font-size: 13px;
        }

        .table-header th,
        .custom-table tbody td {
            padding: 12px 8px;
        }

        .welcome-card {
            padding: 20px 15px;
        }

        .welcome-title {
            font-size: 18px;
        }
    }

    /* Print Styles */
    @media print {

        .breadcrumb-banner-area,
        .table-info {
            display: none;
        }

        .custom-table {
            box-shadow: none;
        }

        .section-wrapper {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }
</style>

@endsection

