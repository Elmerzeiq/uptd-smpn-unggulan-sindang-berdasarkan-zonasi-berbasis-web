<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilSekolahController extends Controller
{
    private function getProfil()
    {
        return ProfilSekolah::firstOrCreate([], [
            'nama_sekolah' => 'Cerulean School',
            'visi' => '',
            'misi' => '',
            'sejarah' => '',
            'jml_siswa' => 0,
            'jml_guru' => 0,
            'jml_staff' => 0,
            'logo_sekolah' => '',
            'image' => '',
            'alamat' => '',
            'kontak1' => '',
            'kontak2' => '',
            'email' => '',
            'prestasi_sekolah' => '',
            'metode_pengajaran' => '',
            'kurikulum' => '',
            'budaya_sekolah' => '',
            'fasilitas_sekolah' => '',
        ]);
    }

    public function index()
    {
        $profil = $this->getProfil();
        return view('admin.profil-sekolah.index', compact('profil'));
    }

    public function update(Request $request, ProfilSekolah $profilSekolah)
    {
        // Get the correct profile to update
        $profilToUpdate = $this->getProfil();

        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'sejarah' => 'nullable|string',
            'jml_siswa' => 'required|integer|min:0',
            'jml_guru' => 'required|integer|min:0',
            'jml_staff' => 'required|integer|min:0',
            'logo_sekolah' => 'nullable|mimes:png,jpg,jpeg|max:1024',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:5120',
            'alamat' => 'required|string',
            'kontak1' => 'required|string|max:255',
            'kontak2' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'prestasi_sekolah' => 'nullable|string',
            'metode_pengajaran' => 'nullable|string',
            'kurikulum' => 'nullable|string',
            'budaya_sekolah' => 'nullable|string',
            'fasilitas_sekolah' => 'nullable|string',
        ]);

        // Handle image upload (banner/general image)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($profilToUpdate->image && file_exists(public_path('uploads/images/' . $profilToUpdate->image))) {
                unlink(public_path('uploads/images/' . $profilToUpdate->image));
            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/images/'), $imageName);
            $validated['image'] = $imageName;
        }

        // Handle logo upload
        if ($request->hasFile('logo_sekolah')) {
            // Delete old logo if exists
            if ($profilToUpdate->logo_sekolah && Storage::disk('public')->exists($profilToUpdate->logo_sekolah)) {
                Storage::disk('public')->delete($profilToUpdate->logo_sekolah);
            }

            $validated['logo_sekolah'] = $request->file('logo_sekolah')->store('profil_sekolah', 'public');
        }

        // Update the existing profile record
        $profilToUpdate->update($validated);

        return redirect()->route('admin.profil-sekolah.index')->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    // Method lain dari resource bisa dikosongkan atau redirect jika tidak digunakan
    public function create()
    {
        return redirect()->route('admin.profil-sekolah.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.profil-sekolah.index');
    }

    public function show(ProfilSekolah $profilSekolah)
    {
        return redirect()->route('admin.profil-sekolah.index');
    }

    public function edit(ProfilSekolah $profilSekolah)
    {
        return redirect()->route('admin.profil-sekolah.index');
    }

    public function destroy(ProfilSekolah $profilSekolah)
    {
        return redirect()->route('admin.profil-sekolah.index')->with('error', 'Profil sekolah tidak dapat dihapus.');
    }
}
