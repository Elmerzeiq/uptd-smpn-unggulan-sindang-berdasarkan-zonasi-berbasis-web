<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlurPendaftaran;
use Illuminate\Http\Request;

class AlurPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alurs = AlurPendaftaran::orderBy('urutan')->get();
        return view('admin.alur-pendaftaran.index', compact('alurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.alur-pendaftaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'urutan' => 'required|integer',
            'nama' => 'required',
            'keterangan' => 'required',
        ]);

        AlurPendaftaran::create($request->all());

        return redirect()->route('admin.alur-pendaftaran.index')->with('success', 'Alur Pendaftaran created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AlurPendaftaran $alurPendaftaran)
    {
        return view('admin.alur-pendaftaran.edit', compact('alurPendaftaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AlurPendaftaran $alurPendaftaran)
    {
        $request->validate([
            'urutan' => 'required|integer',
            'nama' => 'required',
            'keterangan' => 'required',
        ]);

        $alurPendaftaran->update($request->all());

        return redirect()->route('admin.alur-pendaftaran.index')->with('success', 'Alur Pendaftaran updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AlurPendaftaran $alurPendaftaran)
    {
        $alurPendaftaran->delete();

        return redirect()->route('admin.alur-pendaftaran.index')->with('success', 'Alur Pendaftaran deleted successfully.');
    }
}
