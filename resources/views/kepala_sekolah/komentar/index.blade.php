@extends('layouts.kepala_sekolah.app')

@section('title', 'Komunikasi dengan Admin')

@section('kepala_sekolah_content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Komunikasi dengan Admin</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('kepala-sekolah.dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item">Komunikasi Admin</li>
            </ul>
        </div>

        <!-- Create New Comment Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-plus"></i> Kirim Pesan Baru ke Admin
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kepala-sekolah.komentar.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Subjek</label>
                                        <input type="text" name="subject"
                                            class="form-control @error('subject') is-invalid @enderror"
                                            placeholder="Masukkan subjek pesan..." value="{{ old('subject') }}"
                                            required>
                                        @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Prioritas</label>
                                        <select name="priority"
                                            class="form-control @error('priority') is-invalid @enderror" required>
                                            <option value="normal" {{ old('priority')=='normal' ? 'selected' : '' }}>
                                                Normal</option>
                                            <option value="low" {{ old('priority')=='low' ? 'selected' : '' }}>Rendah
                                            </option>
                                            <option value="high" {{ old('priority')=='high' ? 'selected' : '' }}>Tinggi
                                            </option>
                                            <option value="urgent" {{ old('priority')=='urgent' ? 'selected' : '' }}>
                                                Mendesak</option>
                                        </select>
                                        @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pesan</label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                                    rows="4" placeholder="Tulis pesan Anda..." required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </form>
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
                            <i class="fas fa-comments"></i> Riwayat Komunikasi
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($comments as $comment)
                        <div class="comment-item border-bottom pb-3 mb-3">
                            <!-- Main Comment -->
                            <div class="comment-header d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">{{ $comment->subject }}</h5>
                                    <div class="comment-meta">
                                        <span class="badge {{ $comment->priority_badge }}">
                                            <i class="fas fa-flag"></i> {{ $comment->formatted_priority }}
                                        </span>
                                        <span class="badge {{ $comment->status_badge }}">
                                            <i class="fas fa-circle"></i> {{ $comment->formatted_status }}
                                        </span>
                                        <small class="text-muted ms-2">
                                            <i class="fas fa-clock"></i> {{ $comment->time_ago }}
                                        </small>
                                    </div>
                                </div>
                                <div class="comment-actions">
                                    @if($comment->status !== 'completed')
                                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                        data-target="#replyModal{{ $comment->id }}">
                                        <i class="fas fa-reply"></i> Balas
                                    </button>
                                    <form action="{{ route('kepala-sekolah.komentar.complete', $comment) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('Tandai sebagai selesai?')">
                                            <i class="fas fa-check"></i> Selesai
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>

                            <div class="comment-content mt-2">
                                <p>{{ $comment->message }}</p>
                            </div>

                            <!-- Replies -->
                            @if($comment->hasReplies())
                            <div class="replies mt-3 ps-4 border-start">
                                @foreach($comment->replies as $reply)
                                <div class="reply-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $reply->user->nama_lengkap }}</strong>
                                            <span
                                                class="badge badge-{{ $reply->from_role === 'admin' ? 'info' : 'warning' }}">
                                                {{ ucfirst($reply->from_role) }}
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $reply->time_ago }}</small>
                                    </div>
                                    <p class="mt-2 mb-0">{{ $reply->message }}</p>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Reply Modal -->
                            <div class="modal fade" id="replyModal{{ $comment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Balas: {{ $comment->subject }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('kepala-sekolah.komentar.reply', $comment) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Pesan Balasan</label>
                                                    <textarea name="message" class="form-control" rows="4"
                                                        required></textarea>
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
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada komunikasi dengan admin</h5>
                            <p class="text-muted">Silakan kirim pesan baru menggunakan form di atas</p>
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

<style>
    .comment-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .comment-header h5 {
        color: #495057;
        font-weight: 600;
    }

    .comment-meta {
        margin-bottom: 10px;
    }

    .comment-content {
        background: white;
        padding: 15px;
        border-radius: 6px;
        border-left: 4px solid #007bff;
    }

    .replies {
        background: #ffffff;
        border-radius: 6px;
        padding: 15px;
    }

    .reply-item {
        background: #f1f3f4;
        padding: 12px;
        border-radius: 6px;
        border-left: 3px solid #28a745;
    }

    .badge {
        font-size: 11px;
    }
</style>
@endsection
