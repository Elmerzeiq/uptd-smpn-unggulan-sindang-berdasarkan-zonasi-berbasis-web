<?php


namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\DaftarUlang;
use Illuminate\Http\Request;
use App\Models\JadwalDaftarUlang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\KomponenBiayaDaftarUlang;

class DaftarUlangController extends Controller
{
    /**
     * Menampilkan dashboard daftar ulang admin.
     */
    public function index()
    {
        // PERBAIKAN: Menambahkan filter 'role' untuk menghitung siswa yang lulus
        $totalSiswaLulus = User::where('role', 'siswa')->where('status_pendaftaran', 'lulus_seleksi')->count();

        // PERBAIKAN: Membuat query dasar yang hanya menargetkan data dari 'siswa'
        $querySiswa = DaftarUlang::whereHas('user', function ($query) {
            $query->where('role', 'siswa');
        });

        // Menghitung statistik berdasarkan query siswa yang sudah difilter
        $totalDaftarUlang = $querySiswa->count();
        $menungguVerifikasiBerkas = (clone $querySiswa)->where('status_daftar_ulang', 'menunggu_verifikasi_berkas')->count();
        $menungguVerifikasiPembayaran = (clone $querySiswa)->where('status_daftar_ulang', 'menunggu_verifikasi_pembayaran')->count();
        $daftarUlangSelesai = (clone $querySiswa)->where('status_daftar_ulang', 'daftar_ulang_selesai')->count();

        // Mengambil pendaftaran terbaru dari siswa yang sudah difilter
        $recentRegistrations = (clone $querySiswa)->with(['user', 'jadwal'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.daftar-ulang.index', compact(
            'totalSiswaLulus',
            'totalDaftarUlang',
            'menungguVerifikasiBerkas',
            'menungguVerifikasiPembayaran',
            'daftarUlangSelesai',
            'recentRegistrations'
        ));
    }

    /**
     * Menampilkan halaman daftar siswa yang melakukan daftar ulang.
     */
    public function daftarSiswa(Request $request)
    {
        // Query utama hanya untuk mengambil data daftar ulang milik siswa
        $query = DaftarUlang::whereHas('user', function ($q) {
            $q->where('role', 'siswa');
        })->with(['user.biodata', 'jadwal']);

        // Terapkan filter jika ada
        if ($request->filled('status')) {
            $query->where('status_daftar_ulang', $request->status);
        }
        if ($request->filled('pembayaran')) {
            $query->where('status_pembayaran', $request->pembayaran);
        }
        if ($request->filled('jadwal')) {
            $query->where('jadwal_id', $request->jadwal);
        }

        $daftarUlang = $query->latest()->paginate(20);
        $jadwalList = JadwalDaftarUlang::orderBy('tanggal')->get();
        return view('admin.daftar-ulang.daftar-siswa', compact('daftarUlang', 'jadwalList'));
    }

    /**
     * Menampilkan halaman laporan.
     */
    public function laporan(Request $request)
    {
        // Query 1: Untuk mengambil daftar siswa yang MENDAFTAR pada periode yang dipilih
        $daftarSiswaQuery = DaftarUlang::whereHas('user', function ($q) {
            $q->where('role', 'siswa');
        });

        // Query 2: Untuk menghitung TOTAL PEMBAYARAN LUNAS pada periode yang dipilih
        $totalPembayaranQuery = DaftarUlang::whereHas('user', function ($q) {
            $q->where('role', 'siswa');
        })->where('status_pembayaran', 'sudah_lunas');

        // Terapkan filter periode pada kedua query, tetapi dengan kolom tanggal yang berbeda
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'hari_ini':
                    $daftarSiswaQuery->whereDate('created_at', today());
                    $totalPembayaranQuery->whereDate('tanggal_verifikasi_pembayaran', today());
                    break;
                case 'minggu_ini':
                    $daftarSiswaQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    $totalPembayaranQuery->whereBetween('tanggal_verifikasi_pembayaran', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $daftarSiswaQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    $totalPembayaranQuery->whereMonth('tanggal_verifikasi_pembayaran', now()->month)->whereYear('tanggal_verifikasi_pembayaran', now()->year);
                    break;
            }
        }

        // Terapkan filter rentang tanggal custom pada kedua query
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $daftarSiswaQuery->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
            $totalPembayaranQuery->whereBetween('tanggal_verifikasi_pembayaran', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // Eksekusi query
        $daftarUlang = $daftarSiswaQuery->with(['user.biodata', 'jadwal'])->get();

        // Hitung total pembayaran dari query yang benar
        $totalPembayaran = $totalPembayaranQuery->sum('total_biaya');

        // Statistik lainnya tetap berasal dari daftar siswa yang mendaftar
        $totalPendaftar = $daftarUlang->count();
        $totalSelesai = $daftarUlang->where('status_daftar_ulang', 'daftar_ulang_selesai')->count();

        return view('admin.daftar-ulang.laporan', compact(
            'daftarUlang',
            'totalPendaftar',
            'totalSelesai',
            'totalPembayaran'
        ));
    }

    /**
     * Menampilkan halaman detail siswa.
     */
    public function detailSiswa($id)
    {
        $daftarUlang = DaftarUlang::with([
            'user.biodata',
            'user.orangTua',
            'user.berkas',
            'jadwal',
            'detailBiaya.komponenBiaya',
            'verifier'
        ])->findOrFail($id);
        return view('admin.daftar-ulang.detail-siswa', compact('daftarUlang'));
    }

    // --- CRUD JADWAL ---
    public function jadwal()
    {
        $jadwalList = JadwalDaftarUlang::orderBy('tanggal')->orderBy('waktu_mulai')->get();
        return view('admin.daftar-ulang.jadwal', compact('jadwalList'));
    }

    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'nama_sesi' => ['required', 'string', 'max:100'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'kuota' => ['required', 'integer', 'min:1', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        JadwalDaftarUlang::create($validated);
        return redirect()->route('admin.daftar-ulang.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function updateJadwal(Request $request, $id)
    {
        $jadwal = JadwalDaftarUlang::findOrFail($id);
        $validated = $request->validate([
            'nama_sesi' => ['required', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'kuota' => ['required', 'integer', 'min:' . $jadwal->terisi, 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $jadwal->update($validated);
        return redirect()->route('admin.daftar-ulang.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroyJadwal($id)
    {
        $jadwal = JadwalDaftarUlang::findOrFail($id);
        if ($jadwal->terisi > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus jadwal yang sudah memiliki pendaftar.');
        }
        $jadwal->delete();
        return redirect()->route('admin.daftar-ulang.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    // --- CRUD BIAYA ---
    public function biaya()
    {
        $komponenBiaya = KomponenBiayaDaftarUlang::orderBy('is_wajib', 'desc')->orderBy('nama_komponen')->get();
        return view('admin.daftar-ulang.biaya', compact('komponenBiaya'));
    }

    public function storeBiaya(Request $request)
    {
        $validated = $request->validate([
            'nama_komponen' => ['required', 'string', 'max:100'],
            'biaya' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_wajib'] = $request->has('is_wajib');
        $validated['is_active'] = true; // PERBAIKAN: Set default active saat create

        KomponenBiayaDaftarUlang::create($validated);
        return redirect()->route('admin.daftar-ulang.biaya')->with('success', 'Komponen biaya berhasil ditambahkan.');
    }

    public function updateBiaya(Request $request, $id)
    {
        $komponen = KomponenBiayaDaftarUlang::findOrFail($id);
        $validated = $request->validate([
            'nama_komponen' => ['required', 'string', 'max:100'],
            'biaya' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_wajib'] = $request->has('is_wajib');
        $validated['is_active'] = $request->has('is_active');

        $komponen->update($validated);
        return redirect()->route('admin.daftar-ulang.biaya')->with('success', 'Komponen biaya berhasil diperbarui.');
    }

    // --- AKSI VERIFIKASI ---
    public function verifikasiBerkas(Request $request, $id)
    {
        $daftarUlang = DaftarUlang::findOrFail($id);

        $request->validate([
            'status' => 'required|in:diverifikasi,ditolak',
            'catatan' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request, $daftarUlang) {
            // PERBAIKAN: Konsistensi status setelah verifikasi berkas
            if ($request->status === 'diverifikasi') {
                $daftarUlang->status_daftar_ulang = 'berkas_diverifikasi';
                // PERBAIKAN: Update status user juga
                $daftarUlang->user->update(['status_pendaftaran' => 'berkas_diverifikasi']);
            } else {
                $daftarUlang->status_daftar_ulang = 'berkas_ditolak';
                // PERBAIKAN: Update status user juga
                $daftarUlang->user->update(['status_pendaftaran' => 'berkas_ditolak']);
            }

            $daftarUlang->catatan_verifikasi = $request->catatan;
            $daftarUlang->tanggal_verifikasi_berkas = now();
            $daftarUlang->verified_by = Auth::id();
            $daftarUlang->save();
        });

        return redirect()->back()->with('success', 'Status berkas berhasil diperbarui.');
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        $daftarUlang = DaftarUlang::findOrFail($id);

        $request->validate([
            'status' => 'required|in:lunas,ditolak',
            'catatan' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($request, $daftarUlang) {
            if ($request->status === 'lunas') {
                // PERBAIKAN: Set status pembayaran dan daftar ulang dengan benar
                $daftarUlang->status_pembayaran = 'sudah_lunas';
                $daftarUlang->status_daftar_ulang = 'daftar_ulang_selesai';
                // PERBAIKAN: Update status user
                $daftarUlang->user->update(['status_pendaftaran' => 'daftar_ulang_selesai']);

                // Set tanggal verifikasi pembayaran
                $daftarUlang->tanggal_verifikasi_pembayaran = now();
            } else {
                // PERBAIKAN: Jika pembayaran ditolak, kembalikan ke status berkas diverifikasi
                $daftarUlang->status_pembayaran = 'pembayaran_ditolak';
                $daftarUlang->status_daftar_ulang = 'berkas_diverifikasi';
                // PERBAIKAN: Update status user
                $daftarUlang->user->update(['status_pendaftaran' => 'pembayaran_ditolak']);
            }

            $daftarUlang->catatan_verifikasi = $request->catatan;
            $daftarUlang->verified_by = Auth::id();
            $daftarUlang->save();
        });

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    // --- TAMBAHAN: Method untuk reset status ---
    public function resetStatus($id)
    {
        $daftarUlang = DaftarUlang::findOrFail($id);

        DB::transaction(function () use ($daftarUlang) {
            // Reset ke status awal
            $daftarUlang->update([
                'status_daftar_ulang' => 'menunggu_verifikasi_berkas',
                'status_pembayaran' => 'belum_bayar',
                'catatan_verifikasi' => null,
                'tanggal_verifikasi_berkas' => null,
                'tanggal_verifikasi_pembayaran' => null,
                'verified_by' => null
            ]);

            // Reset status user
            $daftarUlang->user->update(['status_pendaftaran' => 'lulus_seleksi']);
        });

        return redirect()->back()->with('success', 'Status berhasil direset.');
    }

    // --- EXPORT ---
    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}
