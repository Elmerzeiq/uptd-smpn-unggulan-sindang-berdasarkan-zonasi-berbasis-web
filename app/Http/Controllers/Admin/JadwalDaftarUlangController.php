<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDaftarUlang;
use Illuminate\Http\Request;

class JadwalDaftarUlangController extends Controller
{
    public function index()
    {
        $jadwals = JadwalDaftarUlang::latest()->paginate(15);
        return view('admin.daftar_ulang.jadwal.index', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sesi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'kuota' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $overlap = JadwalDaftarUlang::where('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                    ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai]);
            })->exists();

        if ($overlap) {
            return back()->with('error', 'Jadwal bertabrakan dengan jadwal lain pada tanggal tersebut.');
        }

        JadwalDaftarUlang::create($request->all());
        return redirect()->route('admin.daftar-ulang.jadwal.index')->with('success', 'Jadwal berhasil dibuat.');
    }

    // Other methods (create, show, edit, update, destroy, peserta, generateAuto) remain as in original
}
