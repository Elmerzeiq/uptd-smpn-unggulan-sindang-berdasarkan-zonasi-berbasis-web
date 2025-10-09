@extends('layouts.admin.app')

@section('title', 'Komunikasi dengan Kepala Sekolah')

@section('admin_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Komunikasi dengan Kepala Sekolah</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item">Komunikasi</li>
            </ul>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-warning bubble-shadow-small">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3">
                                <div class="numbers">
                                    <p class="card-category">Belum Dibaca</p>
                                    <h4 class="card-title">{{ $unreadCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="text-center icon-big icon-info bubble-shadow-small">
                                    <i class="fas fa-comments"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3">
                                <div class="numbers">
                                    <p class="card-category">Total Percakapan</p>
                                    <h4 class="card-title">{{ $comments->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Aksi Cepat</h5>
                                <p class="text-muted mb-0">Kelola komunikasi dengan Kepala Sekolah</p>
                            </div>
                            <div>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#newMessageModal">
                                    <i class="fas fa-plus"></i> Pesan Baru
                                </button>
                                @if($unreadCount > 0)
                                <a href="{{ route('admin.komentar.mark_all_read') }}" class="btn btn-outline-success">
                                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-inbox"></i> Pesan dari Kepala Sekolah
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($comments as $comment)
                        <div class="comment-item {{ $comment->is_unread ? 'unread' : '' }} border-bottom pb-3 mb-3">
                            <!-- Main Comment Header -->
                            <div class="comment-header d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($comment->is_unread)
                                        <div class="unread-indicator me-2"></div>
                                        @endif
                                        <h5 class="mb-0 me-3">{{ $comment->subject }}</h5>
                                        <span class="badge {{ $comment->priority_badge }}">
                                            <i class="fas fa-flag"></i> {{ $comment->formatted_priority }}
                                        </span>
                                        <span class="badge {{ $comment->status_badge }} ms-1">
                                            {{ $comment->formatted_status }}
                                        </span>
                                    </div>
                                    <div class="comment-meta">
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> {{ $comment->user->nama_lengkap }}
                                            <i class="fas fa-clock ms-2"></i> {{ $comment->time_ago }}
                                        </small>
                                    </div>
                                </div>
                                <div class="comment-actions">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                            data-toggle="dropdown">
                                            <i class="fas fa-cog"></i> Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if($comment->is_unread)
                                            <a class="dropdown-item" href="#" onclick="markAsRead({{ $comment->id }})">
                                                <i class="fas fa-eye"></i> Tandai Dibaca
                                            </a>
                                            @endif
                                            <button class="dropdown-item" data-toggle="modal"
                                                data-target="#replyModal{{ $comment->id }}">
                                                <i class="fas fa-reply"></i> Balas
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item" data-toggle="modal"
                                                data-target="#statusModal{{ $comment->id }}">
                                                <i class="fas fa-edit"></i> Ubah Status
                                            </button>
                                            @if($comment->status !== 'completed')
                                            <form action="{{ route('admin.komentar.update_status', $comment) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="fas fa-check"></i> Tandai Selesai
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Comment Content -->
                            <div class="comment-content mt-3">
                                <div class="message-body">
                                    {{ $comment->message }}
                                </div>
                            </div>

                            <!-- Replies -->
                            @if($comment->hasReplies())
                            <div class="replies mt-3 ps-4 border-start border-primary">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-comments"></i> Balasan ({{ $comment->replies->count() }})
                                </h6>
                                @foreach($comment->replies as $reply)
                                <div class="reply-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $reply->user->nama_lengkap }}</strong>
                                            <span
                                                class="badge badge-{{ $reply->from_role === 'admin' ? 'info' : 'warning' }} ms-2">
                                                {{ ucfirst(str_replace('_', ' ', $reply->from_role)) }}
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $reply->time_ago }}</small>
                                    </div>
                                    <div class="reply-content mt-2">
                                        {{ $reply->message }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Reply Modal -->
                            <div class="modal fade" id="replyModal{{ $comment->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Balas: {{ $comment->subject }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.komentar.reply', $comment) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <strong>Pesan Asli:</strong><br>
                                                    {{ Str::limit($comment->message, 200) }}
                                                </div>
                                                <div class="form-group">
                                                    <label>Pesan Balasan</label>
                                                    <textarea name="message" class="form-control" rows="5" required
                                                        placeholder="Tulis balasan Anda..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane"></i> Kirim Balasan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Modal -->
                            <div class="modal fade" id="statusModal{{ $comment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ubah Status</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.komentar.update_status', $comment) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="pending" {{ $comment->status === 'pending' ?
                                                            'selected' : '' }}>Menunggu</option>
                                                        <option value="in_progress" {{ $comment->status ===
                                                            'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                                                        <option value="completed" {{ $comment->status === 'completed' ?
                                                            'selected' : '' }}>Selesai</option>
                                                        <option value="cancelled" {{ $comment->status === 'cancelled' ?
                                                            'selected' : '' }}>Dibatalkan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada pesan dari Kepala Sekolah</h5>
                            <p class="text-muted">Pesan akan muncul di sini ketika Kepala Sekolah mengirim komunikasi
                            </p>
                        </div>
                        @endforelse

                        <!-- Pagination -->
                        @if($comments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $comments->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Message Modal -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Pesan Baru ke Kepala Sekolah</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.komentar.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Subjek</label>
                                <input type="text" name="subject" class="form-control" required
                                    placeholder="Masukkan subjek pesan...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Prioritas</label>
                                <select name="priority" class="form-control" required>
                                    <option value="normal">Normal</option>
                                    <option value="low">Rendah</option>
                                    <option value="high">Tinggi</option>
                                    <option value="urgent">Mendesak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea name="message" class="form-control" rows="5" required
                            placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .comment-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        position: relative;
        transition: all 0.3s ease;
    }

    .comment-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .comment-item.unread {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
    }

    .unread-indicator {
        width: 8px;
        height: 8px;
        background: #dc3545;
        border-radius: 50%;
        display: inline-block;
    }

    .comment-content {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #007bff;
        margin: 10px 0;
    }

    .message-body {
        line-height: 1.6;
        color: #495057;
    }

    .replies {
        background: #ffffff;
        border-radius: 8px;
        padding: 15px;
    }

    .reply-item {
        background: #f1f3f4;
        padding: 12px;
        border-radius: 6px;
        border-left: 3px solid #28a745;
    }

    .reply-content {
        color: #495057;
        line-height: 1.5;
    }

    .badge {
        font-size: 11px;
        padding: 4px 8px;
    }

    .comment-actions .dropdown-toggle {
        border: none;
        background: transparent;
        color: #6c757d;
    }

    .comment-actions .dropdown-toggle:hover {
        color: #007bff;
        background: #e9ecef;
    }
</style>

<script>
    function markAsRead(commentId) {
    fetch(`/admin/komentar/${commentId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Auto refresh unread count every 30 seconds
setInterval(function() {
    fetch('/admin/komentar/unread-count')
        .then(response => response.json())
        .then(data => {
            // Update navbar notification badge if exists
            const badge = document.querySelector('.notification-badge');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline';
            } else if (badge) {
                badge.style.display = 'none';
            }
        });
}, 30000);
</script>
@endsection
