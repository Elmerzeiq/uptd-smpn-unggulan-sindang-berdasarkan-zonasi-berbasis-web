<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ekskul;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EkskulController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Ekskul::query();

            // Jika ada pencarian nama/kategori
            if ($search = $request->input('search_ekskul')) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', '%' . $search . '%')
                        ->orWhere('kategori', 'like', '%' . $search . '%');
                });
            }

            // Selalu gunakan paginate untuk konsistensi
            $perPage = $request->get('per_page', 10);
            $ekskuls = $query->latest()->paginate($perPage);

            // Tambahkan query string untuk mempertahankan parameter
            if ($request->hasAny(['search_ekskul', 'per_page'])) {
                $ekskuls->appends($request->only(['search_ekskul', 'per_page']));
            }

            return view('admin.ekskul.index', compact('ekskuls'));
        } catch (\Exception $e) {
            Log::error('Error in EkskulController@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback: return empty paginated result jika ada error
            $ekskuls = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                10,
                1,
                ['path' => $request->url(), 'pageName' => 'page']
            );

            return view('admin.ekskul.index', compact('ekskuls'))
                ->with('error', 'Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
        }
    }

    public function create()
    {
        return view('admin.ekskul.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'judul' => 'required|string|max:255|unique:ekskuls,judul',
                'kategori' => 'nullable|string|max:100',
                'deskripsi' => 'required|string',
                'isi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('ekskul_images', 'public');
            }

            $data['tanggal'] = now();

            Ekskul::create($data);
            return redirect()->route('admin.ekskul.index')
                ->with('success', 'Data Ekstrakurikuler berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error in EkskulController@store: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $ekskul = Ekskul::findOrFail($id);
            $ekskul_detail = Ekskul::where('id', '!=', $id)->latest()->take(6)->get();
            return view('home.ekskul.show', compact('ekskul', 'ekskul_detail'));
        } catch (\Exception $e) {
            Log::error('Error in EkskulController@show: ' . $e->getMessage());
            return redirect()->route('admin.ekskul.index')
                ->with('error', 'Data tidak ditemukan.');
        }
    }

    public function edit(Ekskul $ekskul)
    {
        try {
            // Pastikan variable name konsisten dengan view
            $item = $ekskul;
            return view('admin.ekskul.edit', compact('item'));
        } catch (\Exception $e) {
            Log::error('Error in EkskulController@edit: ' . $e->getMessage());
            return redirect()->route('admin.ekskul.index')
                ->with('error', 'Terjadi kesalahan saat membuka halaman edit.');
        }
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        try {
            $data = $request->validate([
                'judul' => ['required', 'string', 'max:255', Rule::unique('ekskuls')->ignore($ekskul->id)],
                'kategori' => 'nullable|string|max:100',
                'deskripsi' => 'required|string',
                'isi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($ekskul->image && Storage::disk('public')->exists($ekskul->image)) {
                    Storage::disk('public')->delete($ekskul->image);
                }
                $data['image'] = $request->file('image')->store('ekskul_images', 'public');
            }

            $ekskul->update($data);
            return redirect()->route('admin.ekskul.index')
                ->with('success', 'Data Ekstrakurikuler berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error in EkskulController@update: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.')
                ->withInput();
        }
    }

    public function destroy(Ekskul $ekskul)
    {
        try {
            // Hapus gambar jika ada
            if ($ekskul->image && Storage::disk('public')->exists($ekskul->image)) {
                Storage::disk('public')->delete($ekskul->image);
            }

            $ekskul->delete();
            return redirect()->route('admin.ekskul.index')
                ->with('success', 'Data Ekstrakurikuler berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error in EkskulController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
