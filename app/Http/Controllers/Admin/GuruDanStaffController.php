<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruDanStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruDanStaffController extends Controller
{
    public function index()
    {
        $items = GuruDanStaff::orderBy('kategori')->orderBy('nama')->paginate(10);
        return view('admin.guru_staff.index', compact('items'));
    }

    public function create()
    {
        return view('admin.guru_staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:100|unique:guru_dan_staff,nip',
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:kepala_sekolah,guru,staff',
            'sambutan' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:1024', // Max 1MB
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/'), $imageName);
            $validated['image'] = $imageName;
        }

        // Jika kategori bukan kepala_sekolah, kosongkan sambutan
        if ($validated['kategori'] !== 'kepala_sekolah') {
            $validated['sambutan'] = null;
        }

        // Create the record with all validated data (including image if uploaded)
        GuruDanStaff::create($validated);

        return redirect()->route('admin.guru-staff.index')->with('success', 'Data Guru/Staff berhasil ditambahkan.');
    }

    public function show(GuruDanStaff $guru_staff)
    {
        // // Biasanya tidak ada halaman show khusus di admin untuk data seperti ini,
        // // detail bisa dilihat di edit atau langsung di index.
        // // Jika diperlukan, buat view 'admin.guru_staff.show'.
        // return view('admin.guru_staff.edit', ['item' => $guru_staff]); // Atau redirect ke edit
    }

    public function edit(GuruDanStaff $guru_staff)
    {
        return view('admin.guru_staff.edit', ['item' => $guru_staff]);
    }

    public function update(Request $request, GuruDanStaff $guru_staff)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:100|unique:guru_dan_staff,nip,' . $guru_staff->id,
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:kepala_sekolah,guru,staff',
            'sambutan' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($guru_staff->image && file_exists(public_path('uploads/images/' . $guru_staff->image))) {
                unlink(public_path('uploads/images/' . $guru_staff->image));
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/'), $imageName);
            $validated['image'] = $imageName;
        } else {
            // If no new image is uploaded, don't overwrite the existing image field
            unset($validated['image']);
        }

        // Jika kategori bukan kepala_sekolah, kosongkan sambutan
        if ($validated['kategori'] !== 'kepala_sekolah') {
            $validated['sambutan'] = null;
        } else {
            // Jika kategori adalah kepala sekolah tapi sambutan tidak diisi, pastikan sambutan diisi
            // atau biarkan nullable jika memang boleh kosong.
            // Untuk Kaiadmin, jika sambutan_group disembunyikan, fieldnya tidak akan terkirim.
            // Jadi, kita perlu handle jika $request->sambutan tidak ada padahal kategori kepala sekolah.
            if ($validated['kategori'] === 'kepala_sekolah' && !$request->filled('sambutan')) {
                // Biarkan $validated['sambutan'] apa adanya dari request (nullable)
                // atau set ke null jika tidak ada di request
                $validated['sambutan'] = $request->input('sambutan', $guru_staff->sambutan); // Ambil dari request atau pertahankan lama
            }
        }

        $guru_staff->update($validated);

        return redirect()->route('admin.guru-staff.index')->with('success', 'Data Guru/Staff berhasil diperbarui.');
    }

    public function destroy(GuruDanStaff $guru_staff)
    {
        // Delete image file if exists
        if ($guru_staff->image && file_exists(public_path('uploads/images/' . $guru_staff->image))) {
            unlink(public_path('uploads/images/' . $guru_staff->image));
        }

        $guru_staff->delete();
        return redirect()->route('admin.guru-staff.index')->with('success', 'Data Guru/Staff berhasil dihapus.');
    }
}
