<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminCommentController extends Controller
{
    /**
     * Display comments from Kepala Sekolah
     */
    public function index()
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->where(function ($query) {
                $query->where('to_role', 'admin')
                    ->orWhere('from_role', 'admin');
            })
            ->latest()
            ->paginate(10);

        // Count unread comments
        $unreadCount = Comment::where('to_role', 'admin')
            ->whereNull('read_at')
            ->count();

        return view('admin.komentar.index', compact('comments', 'unreadCount'));
    }

    /**
     * Reply to Kepala Sekolah comment
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Mark original comment as read
        if ($comment->is_unread) {
            $comment->markAsRead();
        }

        Comment::create([
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'from_role' => 'admin',
            'to_role' => 'kepala_sekolah',
            'subject' => 'Re: ' . $comment->subject,
            'message' => $request->message,
            'priority' => $comment->priority,
            'status' => 'pending',
        ]);

        // Update original comment status if needed
        if ($comment->status === 'pending') {
            $comment->update(['status' => 'in_progress']);
        }

        return redirect()->route('admin.komentar.index')
            ->with('success', 'Balasan berhasil dikirim ke Kepala Sekolah.');
    }

    /**
     * Update comment status
     */
    public function updateStatus(Request $request, Comment $comment)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $comment->update(['status' => $request->status]);

        return redirect()->route('admin.komentar.index')
            ->with('success', 'Status komentar berhasil diperbarui.');
    }

    /**
     * Mark comment as read
     */
    public function markAsRead(Comment $comment)
    {
        $comment->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all comments as read
     */
    public function markAllAsRead()
    {
        Comment::where('to_role', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->route('admin.komentar.index')
            ->with('success', 'Semua komentar telah ditandai sebagai dibaca.');
    }

    /**
     * Get unread comment count for navbar
     */
    public function getUnreadCount()
    {
        $count = Comment::where('to_role', 'admin')
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Send new message to Kepala Sekolah
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'from_role' => 'admin',
            'to_role' => 'kepala_sekolah',
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.komentar.index')
            ->with('success', 'Pesan berhasil dikirim ke Kepala Sekolah.');
    }

    /**
     * Delete comment
     */
    public function destroy(Comment $comment)
    {
        // Only allow deleting own comments or as admin
        if (Auth::user()->role !== 'admin' && $comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('admin.komentar.index')
            ->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Show comment details
     */
    public function show(Comment $comment)
    {
        $comment->load(['user', 'replies.user']);

        // Mark as read if it's for admin
        if ($comment->to_role === 'admin' && $comment->is_unread) {
            $comment->markAsRead();
        }

        return view('admin.komentar.show', compact('comment'));
    }

    /**
     * Add internal note to comment (admin only)
     */
    public function addNote(Request $request, Comment $comment)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        $comment->update([
            'admin_notes' => ($comment->admin_notes ?? '') . "\n" . '[' . now()->format('d/m/Y H:i') . '] ' . Auth::user()->nama_lengkap . ': ' . $request->note
        ]);

        return redirect()->back()->with('success', 'Catatan internal berhasil ditambahkan.');
    }
}
