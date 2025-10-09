<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PengumumanHasilController extends Controller
{
    /**
     * Display a listing of pengumuman hasil PPDB
     */
    public function index(): View
    {
        // Statistik siswa untuk dashboard
        $stats = [
            'total_pendaftar' => User::where('role', 'siswa')->count(),
            'diterima' => User::where('role', 'siswa')->where('status_pendaftaran', 'lulus_seleksi')->count(),
            'ditolak' => User::where('role', 'siswa')->where('status_pendaftaran', 'tidak_lulus')->count(),
            'dalam_proses' => User::where('role', 'siswa')
                ->whereIn('status_pendaftaran', ['pending', 'verified'])
                ->count(),
        ];

        // Pengumuman hasil PPDB
        $pengumumans = Pengumuman::where('tipe', 'pengumuman_hasil')
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengumuman-hasil.index', compact('stats', 'pengumumans'));
    }

    /**
     * Show form for creating pengumuman hasil PPDB
     */
    public function create(): View
    {
        return view('admin.pengumuman-hasil.create');
    }

    /**
     * Store new pengumuman hasil PPDB
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_penerima' => 'required|in:semua,calon_siswa,siswa_diterima,siswa_ditolak',
            'tanggal' => 'nullable|date',
            'aktif' => 'boolean',
        ]);

        try {
            Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'tipe' => 'pengumuman_hasil',
                'tanggal' => $request->tanggal ?? now(),
                'user_id' => Auth::id(),
                'target_penerima' => $request->target_penerima,
                'aktif' => $request->has('aktif'),
            ]);

            return redirect()->route('admin.pengumuman-hasil.index')
                ->with('success', 'Pengumuman hasil SPMB berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error("Error creating pengumuman hasil: " . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat pengumuman hasil.');
        }
    }

    /**
     * Display specific pengumuman hasil
     */
    public function show(Pengumuman $pengumumanHasil): View
    {
        // Pastikan hanya pengumuman hasil yang bisa diakses
        if ($pengumumanHasil->tipe !== 'pengumuman_hasil') {
            abort(404);
        }

        $pengumumanHasil->load('admin');
        return view('admin.pengumuman-hasil.show', compact('pengumumanHasil'));
    }

    /**
     * Show form for editing pengumuman hasil
     */
    public function edit(Pengumuman $pengumumanHasil): View
    {
        // Pastikan hanya pengumuman hasil yang bisa diedit
        if ($pengumumanHasil->tipe !== 'pengumuman_hasil') {
            abort(404);
        }

        // Format tanggal untuk input datetime-local
        $pengumumanHasil->tanggal_input = $pengumumanHasil->tanggal ? $pengumumanHasil->tanggal->format('Y-m-d\TH:i') : null;
        return view('admin.pengumuman-hasil.edit', compact('pengumumanHasil'));
    }

    /**
     * Update pengumuman hasil
     */
    public function update(Request $request, Pengumuman $pengumumanHasil): RedirectResponse
    {
        // Pastikan hanya pengumuman hasil yang bisa diupdate
        if ($pengumumanHasil->tipe !== 'pengumuman_hasil') {
            abort(404);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_penerima' => 'required|in:semua,calon_siswa,siswa_diterima,siswa_ditolak',
            'tanggal' => 'nullable|date',
            'aktif' => 'boolean',
        ]);

        try {
            $pengumumanHasil->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'tanggal' => $request->tanggal ?? $pengumumanHasil->tanggal,
                'target_penerima' => $request->target_penerima,
                'aktif' => $request->has('aktif'),
            ]);

            return redirect()->route('admin.pengumuman-hasil.index')
                ->with('success', 'Pengumuman hasil SPMB berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error updating pengumuman hasil ID {$pengumumanHasil->id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui pengumuman hasil.');
        }
    }

    /**
     * Remove pengumuman hasil
     */
    public function destroy(Pengumuman $pengumumanHasil): RedirectResponse
    {
        // Pastikan hanya pengumuman hasil yang bisa dihapus
        if ($pengumumanHasil->tipe !== 'pengumuman_hasil') {
            abort(404);
        }

        try {
            $pengumumanHasil->delete();
            return redirect()->route('admin.pengumuman-hasil.index')
                ->with('success', 'Pengumuman hasil SPMB berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting pengumuman hasil ID {$pengumumanHasil->id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengumuman hasil.');
        }
    }
}
