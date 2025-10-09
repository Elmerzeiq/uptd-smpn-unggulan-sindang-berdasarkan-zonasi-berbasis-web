<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetunjukTeknis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetunjukTeknisController extends Controller
{
    public function index()
    {
        $petunjuk = PetunjukTeknis::all();
        return view('admin.petunjuk-teknis.index', compact('petunjuk'));
    }

    public function create()
    {
        return view('admin.petunjuk-teknis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'path_pdf' => 'required|file|mimes:pdf',
        ]);

        $path = $request->file('path_pdf')->storeAs('pdfs', 'Surat Petunjuk Teknis SPMB.pdf', 'public');

        PetunjukTeknis::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'path_pdf' => $path,
        ]);

        return redirect()->route('admin.petunjuk-teknis.index')->with('success', 'Petunjuk Teknis created successfully.');
    }

    // Ubah parameter dari $petunjukTeknis menjadi $petunjuk_tekni
    public function edit(PetunjukTeknis $petunjukTeknis)
    {
        return view('admin.petunjuk-teknis.edit', compact('petunjukTeknis'));
    }

    public function update(Request $request, PetunjukTeknis $petunjukTeknis)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'path_pdf' => 'nullable|file|mimes:pdf',
        ]);

        $data = $request->only(['judul', 'isi']);

        if ($request->hasFile('path_pdf')) {
            // Hapus file lama jika ada
            if ($petunjukTeknis->path_pdf) {
                Storage::disk('public')->delete($petunjukTeknis->path_pdf);
            }
            $data['path_pdf'] = $request->file('path_pdf')->storeAs('pdfs', 'Surat Petunjuk Teknis SPMB.pdf', 'public');
        }

        $petunjukTeknis->update($data);

        return redirect()->route('admin.petunjuk-teknis.index')->with('success', 'Petunjuk Teknis updated successfully.');
    }

    public function destroy(PetunjukTeknis $petunjukTeknis)
    {
        if ($petunjukTeknis->path_pdf) {
            Storage::disk('public')->delete($petunjukTeknis->path_pdf);
        }
        $petunjukTeknis->delete();

        return redirect()->route('admin.petunjuk-teknis.index')->with('success', 'Petunjuk Teknis deleted successfully.');
    }

    // Ubah parameter dari $petunjukTeknis menjadi $petunjuk_tekni
    public function download(PetunjukTeknis $petunjuk_tekni)
    {
        $file = Storage::disk('public')->get($petunjuk_tekni->path_pdf);
        $filename = 'Surat Petunjuk Teknis SPMB.pdf';

        return response($file, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}
