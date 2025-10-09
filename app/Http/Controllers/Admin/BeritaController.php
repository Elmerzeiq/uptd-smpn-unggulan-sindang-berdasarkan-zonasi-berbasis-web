<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Berita::with('author');

        if ($request->filled('search_berita')) {
            $query->where('judul', 'like', '%' . $request->search_berita . '%');
        }
        if ($request->filled('status_berita')) {
            $query->where('status', $request->status_berita);
        }

        $items = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $statusOptions = ['draft', 'published'];

        return view('admin.berita.index', compact('items', 'statusOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berita.create', ['item' => new Berita()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:beritas,judul',
            'deskripsi' => 'required|string|max:500',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'status' => ['required', Rule::in(['draft', 'published'])],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validatedData['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('berita_images', 'public');
        }

        Berita::create($validatedData);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Berita $beritum)
    {
        return view('admin.berita.show', ['item' => $beritum]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Berita $beritum)
    {
        return view('admin.berita.edit', ['item' => $beritum]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $beritum)
    {
        $validatedData = $request->validate([
            'judul' => ['required', 'string', 'max:255', Rule::unique('beritas')->ignore($beritum->id)],
            'deskripsi' => 'required|string|max:500',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'status' => ['required', Rule::in(['draft', 'published'])],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($beritum->image && Storage::disk('public')->exists($beritum->image)) {
                Storage::disk('public')->delete($beritum->image);
            }
            $validatedData['image'] = $request->file('image')->store('berita_images', 'public');
        }

        $beritum->update($validatedData);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $beritum)
    {
        // Hapus gambar terkait jika ada
        if ($beritum->image && Storage::disk('public')->exists($beritum->image)) {
            Storage::disk('public')->delete($beritum->image);
        }

        $beritum->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
