<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PengumumanController extends Controller
{
    /**
     * Display a listing of pengumuman umum
     */
    public function index(): View
    {
        $pengumumans = Pengumuman::where('tipe', '!=', 'pengumuman_hasil')
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    /**
     * Show form for creating new pengumuman umum
     */
    public function create(): View
    {
        return view('admin.pengumuman.create');
    }

    /**
     * Store new pengumuman umum
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:info,warning,danger,success',
            'tanggal' => 'nullable|date',
            'target_penerima' => 'required|in:semua,calon_siswa,siswa_diterima,siswa_ditolak',
            'aktif' => 'boolean',
            'priority' => 'nullable|integer|min:1|max:10'
        ]);

        try {
            $pengumuman = Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'tipe' => $request->tipe,
                'tanggal' => $request->tanggal ?? now(),
                'user_id' => Auth::id(),
                'target_penerima' => $request->target_penerima,
                'aktif' => $request->has('aktif'),
                'priority' => $request->priority ?? 5,
                'views_count' => 0
            ]);

            // Log activity
            Log::info("Pengumuman created", [
                'id' => $pengumuman->id,
                'judul' => $pengumuman->judul,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap
            ]);

            return redirect()->route('admin.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error("Error creating pengumuman: " . $e->getMessage(), [
                'admin_id' => Auth::id(),
                'request_data' => $validated
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat pengumuman.');
        }
    }

    /**
     * Display specific pengumuman
     */
    public function show(Pengumuman $pengumuman): View
    {
        $pengumuman->load('admin');

        // Increment views for admin tracking
        $pengumuman->increment('views_count');

        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show form for editing pengumuman
     */
    public function edit(Pengumuman $pengumuman): View
    {
        // Format tanggal untuk input datetime-local
        $pengumuman->tanggal_input = $pengumuman->tanggal ? $pengumuman->tanggal->format('Y-m-d\TH:i') : null;
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update pengumuman
     */
    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:info,warning,danger,success',
            'tanggal' => 'nullable|date',
            'target_penerima' => 'required|in:semua,calon_siswa,siswa_diterima,siswa_ditolak',
            'aktif' => 'boolean',
            'priority' => 'nullable|integer|min:1|max:10'
        ]);

        try {
            $oldData = $pengumuman->toArray();

            $pengumuman->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'tipe' => $request->tipe,
                'tanggal' => $request->tanggal ?? $pengumuman->tanggal,
                'target_penerima' => $request->target_penerima,
                'aktif' => $request->has('aktif'),
                'priority' => $request->priority ?? $pengumuman->priority ?? 5
            ]);

            // Log activity
            Log::info("Pengumuman updated", [
                'id' => $pengumuman->id,
                'judul' => $pengumuman->judul,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap,
                'changes' => array_diff_assoc($pengumuman->toArray(), $oldData)
            ]);

            return redirect()->route('admin.pengumuman.index')
                ->with('success', 'Pengumuman berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error updating pengumuman ID {$pengumuman->id}: " . $e->getMessage(), [
                'admin_id' => Auth::id(),
                'request_data' => $validated
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui pengumuman.');
        }
    }

    /**
     * Remove pengumuman
     */
    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        try {
            $pengumumanData = $pengumuman->toArray();
            $pengumuman->delete();

            // Log activity
            Log::info("Pengumuman deleted", [
                'deleted_pengumuman' => $pengumumanData,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap
            ]);

            return redirect()->route('admin.pengumuman.index')
                ->with('success', 'Pengumuman berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting pengumuman ID {$pengumuman->id}: " . $e->getMessage(), [
                'admin_id' => Auth::id()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengumuman.');
        }
    }

    /**
     * Toggle status aktif pengumuman (AJAX)
     */
    public function toggleStatus(Pengumuman $pengumuman): JsonResponse
    {
        try {
            $oldStatus = $pengumuman->aktif;
            $pengumuman->update(['aktif' => !$pengumuman->aktif]);

            // Log activity
            Log::info("Pengumuman status toggled", [
                'id' => $pengumuman->id,
                'judul' => $pengumuman->judul,
                'old_status' => $oldStatus,
                'new_status' => $pengumuman->aktif,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pengumuman berhasil diubah',
                'new_status' => $pengumuman->aktif
            ]);
        } catch (\Exception $e) {
            Log::error("Error toggling pengumuman status ID {$pengumuman->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ], 500);
        }
    }

    /**
     * Duplicate pengumuman
     */
    public function duplicate(Pengumuman $pengumuman): RedirectResponse
    {
        try {
            $newPengumuman = $pengumuman->replicate();
            $newPengumuman->judul = 'Copy of ' . $pengumuman->judul;
            $newPengumuman->user_id = Auth::id();
            $newPengumuman->aktif = false; // Set as inactive by default
            $newPengumuman->views_count = 0;
            $newPengumuman->tanggal = null; // Remove scheduled date
            $newPengumuman->save();

            // Log activity
            Log::info("Pengumuman duplicated", [
                'original_id' => $pengumuman->id,
                'new_id' => $newPengumuman->id,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap
            ]);

            return redirect()->route('admin.pengumuman.edit', $newPengumuman->id)
                ->with('success', 'Pengumuman berhasil diduplikasi. Silakan edit sesuai kebutuhan.');
        } catch (\Exception $e) {
            Log::error("Error duplicating pengumuman ID {$pengumuman->id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menduplikasi pengumuman.');
        }
    }

    /**
     * Bulk actions untuk pengumuman
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:pengumumans,id'
        ]);

        try {
            $pengumumans = Pengumuman::whereIn('id', $request->ids)->get();
            $count = 0;

            foreach ($pengumumans as $pengumuman) {
                switch ($request->action) {
                    case 'activate':
                        $pengumuman->update(['aktif' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $pengumuman->update(['aktif' => false]);
                        $count++;
                        break;
                    case 'delete':
                        $pengumuman->delete();
                        $count++;
                        break;
                }
            }

            // Log activity
            Log::info("Bulk action performed on pengumumans", [
                'action' => $request->action,
                'ids' => $request->ids,
                'count' => $count,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->nama_lengkap
            ]);

            $actionText = [
                'activate' => 'diaktifkan',
                'deactivate' => 'dinonaktifkan',
                'delete' => 'dihapus'
            ];

            return response()->json([
                'success' => true,
                'message' => "{$count} pengumuman berhasil {$actionText[$request->action]}"
            ]);
        } catch (\Exception $e) {
            Log::error("Error in bulk action: " . $e->getMessage(), [
                'action' => $request->action,
                'ids' => $request->ids ?? []
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan aksi'
            ], 500);
        }
    }

    /**
     * Get pengumuman statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $stats = Pengumuman::where('tipe', '!=', 'pengumuman_hasil')
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN aktif = 1 THEN 1 ELSE 0 END) as aktif,
                    SUM(CASE WHEN aktif = 0 THEN 1 ELSE 0 END) as tidak_aktif,
                    SUM(CASE WHEN tanggal > NOW() THEN 1 ELSE 0 END) as terjadwal,
                    SUM(views_count) as total_views
                ')
                ->first();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error("Error getting pengumuman statistics: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    /**
     * Preview pengumuman sebelum publish
     */
    public function preview(Request $request): JsonResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:info,warning,danger,success',
            'target_penerima' => 'required|in:semua,calon_siswa,siswa_diterima,siswa_ditolak'
        ]);

        try {
            // Create temporary pengumuman object for preview
            $pengumuman = new Pengumuman($request->all());
            $pengumuman->user_id = Auth::id();
            $pengumuman->tanggal = $request->tanggal ? now()->parse($request->tanggal) : now();

            $previewHtml = view('admin.pengumuman.preview-template', compact('pengumuman'))->render();

            return response()->json([
                'success' => true,
                'html' => $previewHtml
            ]);
        } catch (\Exception $e) {
            Log::error("Error generating preview: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat preview'
            ], 500);
        }
    }
}
