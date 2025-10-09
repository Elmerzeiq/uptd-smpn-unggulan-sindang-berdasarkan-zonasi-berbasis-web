<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;

class JadwalPendaftaranController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPendaftaran::all();
        return view('admin.jadwal-pendaftaran.index', compact('jadwals'));
    }

    public function create()
    {
        return view('admin.jadwal-pendaftaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahap' => 'required',
            'kegiatan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        JadwalPendaftaran::create($request->all());

        return redirect()->route('admin.jadwal-pendaftaran.index')->with('success', 'Jadwal Pendaftaran created successfully.');
    }

    public function edit(JadwalPendaftaran $jadwalPendaftaran)
    {
        return view('admin.jadwal-pendaftaran.edit', compact('jadwalPendaftaran'));
    }

    public function update(Request $request, JadwalPendaftaran $jadwalPendaftaran)
    {
        $request->validate([
            'tahap' => 'required',
            'kegiatan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $jadwalPendaftaran->update($request->all());

        return redirect()->route('admin.jadwal-pendaftaran.index')->with('success', 'Jadwal Pendaftaran updated successfully.');
    }

    public function destroy(JadwalPendaftaran $jadwalPendaftaran)
    {
        $jadwalPendaftaran->delete();

        return redirect()->route('admin.jadwal-pendaftaran.index')->with('success', 'Jadwal Pendaftaran deleted successfully.');
    }
}
