<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPpdb; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JadwalPpdbController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalPpdb::query();
        if ($request->filled('search_jadwal')) {
            $query->where('tahun_ajaran', 'like', '%' . $request->search_jadwal . '%');
        }
        $items = $query->orderBy('tahun_ajaran', 'desc')->paginate(10)->withQueryString();
        return view('admin.jadwal_ppdb.index', compact('items'));
    }

    public function create()
    {
        return view('admin.jadwal_ppdb.create', ['jadwalPpdb' => new JadwalPpdb()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|max:9|unique:jadwal_ppdb,tahun_ajaran',
            'pembukaan_pendaftaran' => 'required|date|after_or_equal:today',
            'penutupan_pendaftaran' => 'required|date|after_or_equal:pembukaan_pendaftaran',
            'pengumuman_hasil' => 'required|date|after_or_equal:penutupan_pendaftaran',
            'mulai_daftar_ulang' => 'required|date|after_or_equal:pengumuman_hasil',
            'selesai_daftar_ulang' => 'required|date|after_or_equal:mulai_daftar_ulang',
            'kuota_total_keseluruhan' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated) {
            if (isset($validated['is_active']) && $validated['is_active']) {
                JadwalPpdb::where('is_active', true)->update(['is_active' => false]);
            } else {
                $validated['is_active'] = false;
            }
            JadwalPpdb::create($validated);
        });

        return redirect()->route('admin.jadwal-ppdb.index')->with('success', 'Jadwal SPMB berhasil ditambahkan.');
    }

    public function show(JadwalPpdb $jadwalPpdb) // Parameter sesuai resource 'jadwal-ppdb'
    {
        // Biasanya tidak ada halaman show, langsung ke edit
        return redirect()->route('admin.jadwal-ppdb.edit', $jadwalPpdb->id);
    }

    public function edit(JadwalPpdb $jadwalPpdb)
    {
        return view('admin.jadwal_ppdb.edit', compact('jadwalPpdb'));
    }

    public function update(Request $request, JadwalPpdb $jadwalPpdb)
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:9', Rule::unique('jadwal_ppdb')->ignore($jadwalPpdb->id)],
            'pembukaan_pendaftaran' => 'required|date',
            'penutupan_pendaftaran' => 'required|date|after_or_equal:pembukaan_pendaftaran',
            'pengumuman_hasil' => 'required|date|after_or_equal:penutupan_pendaftaran',
            'mulai_daftar_ulang' => 'required|date|after_or_equal:pengumuman_hasil',
            'selesai_daftar_ulang' => 'required|date|after_or_equal:mulai_daftar_ulang',
            'kuota_total_keseluruhan' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request, $jadwalPpdb) {
            $isActiveRequest = $request->boolean('is_active'); // Ambil boolean dari request

            if ($isActiveRequest) {
                // Nonaktifkan semua jadwal lain jika ini diaktifkan
                JadwalPpdb::where('is_active', true)->where('id', '!=', $jadwalPpdb->id)->update(['is_active' => false]);
                $validated['is_active'] = true;
            } else {
                // Jika checkbox tidak dicentang, pastikan is_active adalah false
                // Kecuali jika ini satu-satunya jadwal aktif dan tidak ada jadwal lain yang bisa diaktifkan
                // (logika ini bisa lebih kompleks jika diperlukan untuk mencegah tidak ada jadwal aktif sama sekali)
                $validated['is_active'] = false;
            }
            $jadwalPpdb->update($validated);
        });

        return redirect()->route('admin.jadwal-ppdb.index')->with('success', 'Jadwal SPMB berhasil diperbarui.');
    }

    public function destroy(JadwalPpdb $jadwalPpdb)
    {
        if ($jadwalPpdb->is_active) {
            return redirect()->route('admin.jadwal-ppdb.index')->with('error', 'Tidak dapat menghapus jadwal yang sedang aktif. Aktifkan jadwal lain terlebih dahulu.');
        }
        // Tambahan: Cek apakah ada pendaftar yang terkait dengan jadwal ini sebelum menghapus (opsional)
        // if (User::whereHas('pendaftaran', fn($q) => $q->where('jadwal_ppdb_id', $jadwalPpdb->id))->exists()) {
        // return back()->with('error', 'Tidak dapat menghapus jadwal karena sudah ada pendaftar terkait.');
        // }

        $jadwalPpdb->delete();
        return redirect()->route('admin.jadwal-ppdb.index')->with('success', 'Jadwal SPMB berhasil dihapus.');
    }
}
