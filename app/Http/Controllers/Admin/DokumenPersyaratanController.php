<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPersyaratan;
use Illuminate\Http\Request;

class DokumenPersyaratanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokumens = DokumenPersyaratan::all();
        return view('admin.dokumen-persyaratan.index', compact('dokumens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dokumen-persyaratan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:zonasi,afirmasi-ketm,afirmasi-disabilitas,perpindahan tugas orang tua,putra/putri guru/tenaga kependidikan,prestasi-akademik,prestasi-non-akademik,prestasi-akademik nilai raport',
            'keterangan' => 'required',
        ]);

        DokumenPersyaratan::create($request->all());

        return redirect()->route('admin.dokumen-persyaratan.index')->with('success', 'Dokumen Persyaratan created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DokumenPersyaratan $dokumenPersyaratan)
    {
        return view('admin.dokumen-persyaratan.edit', compact('dokumenPersyaratan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DokumenPersyaratan $dokumenPersyaratan)
    {
        $request->validate([
            'kategori' => 'required|in:zonasi,afirmasi-ketm,afirmasi-disabilitas,perpindahan tugas orang tua,putra/putri guru/tenaga kependidikan,prestasi-akademik,prestasi-non-akademik,prestasi-akademik nilai raport',
            'keterangan' => 'required',
        ]);

        $dokumenPersyaratan->update($request->all());

        return redirect()->route('admin.dokumen-persyaratan.index')->with('success', 'Dokumen Persyaratan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DokumenPersyaratan $dokumenPersyaratan)
    {
        $dokumenPersyaratan->delete();

        return redirect()->route('admin.dokumen-persyaratan.index')->with('success', 'Dokumen Persyaratan deleted successfully.');
    }
}
