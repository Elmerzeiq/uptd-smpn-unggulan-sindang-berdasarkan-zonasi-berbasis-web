<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Define categories as a class property for consistency
    private $kategoriList = ['kegiatan', 'ekstrakurikuler', 'prestasi'];

    public function index(Request $request)
    {
        // Ambil query ?kategori=...
        $kategori = $request->query('kategori');

        // Validasi kategori; kalau tidak valid, dianggap null (tampilkan semua)
        if ($kategori && !in_array($kategori, $this->kategoriList)) {
            $kategori = null;
        }

        $query = Gallery::query();

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Use paginate() instead of get() to support firstItem() method in blade
        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung jumlah foto per kategori untuk info tambahan
        $kategoriCounts = [];
        foreach ($this->kategoriList as $kat) {
            $kategoriCounts[$kat] = Gallery::where('kategori', $kat)->count();
        }

        return view('admin.galeri.index', compact('items', 'kategori', 'kategoriCounts'))->with('kategoriList', $this->kategoriList);
    }

    public function create()
    {
        // Pass kategoriList to the create view
        return view('admin.galeri.create', ['kategoriList' => $this->kategoriList]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:' . implode(',', $this->kategoriList),
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid. Pilih salah satu: ' . implode(', ', array_map(function ($k) {
                return ucwords(str_replace('_', ' ', $k));
            }, $this->kategoriList)),
            'image.required' => 'Gambar wajib diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpg, jpeg, png, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $validated['image'] = $image->storeAs('gallery_images', $fileName, 'public');
        }

        Gallery::create($validated);
        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function show(Gallery $galeri)
    {
        return redirect()->route('admin.galeri.edit', $galeri);
    }

    public function edit(Gallery $galeri)
    {
        // Pass both item and kategoriList to the edit view
        return view('admin.galeri.edit', [
            'item' => $galeri,
            'kategoriList' => $this->kategoriList
        ]);
    }

    public function update(Request $request, Gallery $galeri)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:' . implode(',', $this->kategoriList),
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid. Pilih salah satu: ' . implode(', ', array_map(function ($k) {
                return ucwords(str_replace('_', ' ', $k));
            }, $this->kategoriList)),
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus: jpg, jpeg, png, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($galeri->image && Storage::disk('public')->exists($galeri->image)) {
                Storage::disk('public')->delete($galeri->image);
            }

            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $validated['image'] = $image->storeAs('gallery_images', $fileName, 'public');
        } else {
            unset($validated['image']);
        }

        $galeri->update($validated);
        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $galeri)
    {
        if ($galeri->image && Storage::disk('public')->exists($galeri->image)) {
            Storage::disk('public')->delete($galeri->image);
        }
        $galeri->delete();
        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil dihapus.');
    }

    /**
     * Method untuk menampilkan galeri di frontend
     */
    public function gallery(Request $request)
    {
        $kategori = $request->query('kategori');

        if ($kategori && !in_array($kategori, $this->kategoriList)) {
            $kategori = null;
        }

        $query = Gallery::query();

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $gallery = $query->orderBy('created_at', 'desc')->get();

        // Hitung jumlah foto per kategori
        $kategoriCounts = [];
        foreach ($this->kategoriList as $kat) {
            $kategoriCounts[$kat] = Gallery::where('kategori', $kat)->count();
        }

        return view('home.galeri', compact('gallery', 'kategori', 'kategoriCounts'))->with('kategoriList', $this->kategoriList);
    }
}
