<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Wali;
use App\Models\OrangTua;
use App\Helpers\PpdbHelper;
use App\Models\BiodataSiswa;
use Illuminate\Http\Request;
use App\Models\BerkasPendaftar;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    /**
     * Display a listing of all students - ADMIN ONLY
     */
    public function index(Request $request)
    {
        $query = User::siswa()->with(['biodata', 'orangTua', 'wali', 'berkas']);

        // Apply filters
        if ($request->filled('jalur')) {
            $query->byJalur($request->jalur);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $siswa = $query->paginate(20);

        $jalurOptions = PpdbHelper::getJalurOptions();
        $statusOptions = PpdbHelper::getStatusOptions();

        // Statistics for dashboard cards
        $stats = [
            'total' => User::siswa()->count(),
            'lulus' => User::siswa()->where('status_pendaftaran', 'lulus_seleksi')->count(),
            'menunggu_verifikasi' => User::siswa()->where('status_pendaftaran', 'menunggu_verifikasi_berkas')->count(),
            'belum_lengkap' => User::siswa()->where('status_pendaftaran', 'menunggu_kelengkapan_data')->count(),
        ];

        return view('admin.siswa.index', compact('siswa', 'jalurOptions', 'statusOptions', 'stats'));
    }

    /**
     * Show the form for creating a new student - ADMIN ONLY
     */
    public function create()
    {
        $jalurOptions = PpdbHelper::getJalurOptions();

        return view('admin.siswa.create', compact('jalurOptions'));
    }

    /**
     * Store a newly created student - ADMIN ONLY
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // User data
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'nisn' => 'required|string|max:10|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'jalur_pendaftaran' => 'required|in:domisili,prestasi_akademik,prestasi_non_akademik,prestasi_rapor,afirmasi_ketm,afirmasi_disabilitas,mutasi_ortu,mutasi_guru',

            // Biodata
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date|before_or_equal:' . now()->subYears(10)->format('Y-m-d'),
            'jns_kelamin' => 'required|in:L,P',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu',
            'anak_ke' => 'required|integer|min:1',
            'jml_saudara_kandung' => 'required|integer|min:0',
            'asal_sekolah' => 'required|string|max:255',
            'alamat_asal_sekolah' => 'required|string|max:500',
            'alamat_rumah' => 'required|string|max:500',
            'kelurahan_desa' => 'required|string|max:100',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'kode_pos' => 'required|string|max:5',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'berat_badan' => 'nullable|numeric|min:0',
            'lingkar_kepala' => 'nullable|numeric|min:0',
            'foto_siswa' => 'nullable|image|mimes:jpeg,png,jpg|max:500',

            // Orang Tua
            'nama_ibu' => 'required|string|max:100',
            'nama_ayah' => 'nullable|string|max:100',
            'nik_ayah' => 'nullable|string|max:16',
            'tempat_lahir_ayah' => 'nullable|string|max:100',
            'tgl_lahir_ayah' => 'nullable|date',
            'pendidikan_ayah' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'penghasilan_ayah' => 'nullable|in:<2juta,2-5juta,5-10juta,>10juta',
            'no_hp_ayah' => 'nullable|string|max:15',
            'nik_ibu' => 'nullable|string|max:16',
            'tempat_lahir_ibu' => 'nullable|string|max:100',
            'tgl_lahir_ibu' => 'nullable|date',
            'pendidikan_ibu' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'penghasilan_ibu' => 'nullable|in:<2juta,2-5juta,5-10juta,>10juta',
            'no_hp_ibu' => 'nullable|string|max:15',

            // Wali (optional)
            'nama_wali' => 'nullable|string|max:100',
            'nik_wali' => 'nullable|string|max:16',
            'hubungan_wali_dgn_siswa' => 'nullable|string|max:50',
            'alamat_wali' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // Create user
                $user = User::create([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'username' => $validated['username'],
                    'nisn' => $validated['nisn'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'siswa',
                    'jalur_pendaftaran' => $validated['jalur_pendaftaran'],
                ]);

                // Handle foto upload
                if ($request->hasFile('foto_siswa')) {
                    $validated['foto_siswa'] = $request->file('foto_siswa')
                        ->store("foto_siswa_ppdb/{$user->nisn}", 'public');
                }

                // Create biodata
                $biodataData = collect($validated)->only([
                    'tempat_lahir',
                    'tgl_lahir',
                    'jns_kelamin',
                    'agama',
                    'anak_ke',
                    'jml_saudara_kandung',
                    'asal_sekolah',
                    'alamat_asal_sekolah',
                    'alamat_rumah',
                    'kelurahan_desa',
                    'rt',
                    'rw',
                    'kode_pos',
                    'tinggi_badan',
                    'berat_badan',
                    'lingkar_kepala',
                    'foto_siswa'
                ])->toArray();
                $biodataData['user_id'] = $user->id;
                BiodataSiswa::create($biodataData);

                // Create orang tua
                $orangTuaData = collect($validated)->only([
                    'nama_ayah',
                    'nik_ayah',
                    'tempat_lahir_ayah',
                    'tgl_lahir_ayah',
                    'pendidikan_ayah',
                    'pekerjaan_ayah',
                    'penghasilan_ayah',
                    'no_hp_ayah',
                    'nama_ibu',
                    'nik_ibu',
                    'tempat_lahir_ibu',
                    'tgl_lahir_ibu',
                    'pendidikan_ibu',
                    'pekerjaan_ibu',
                    'penghasilan_ibu',
                    'no_hp_ibu'
                ])->toArray();
                $orangTuaData['user_id'] = $user->id;
                OrangTua::create($orangTuaData);

                // Create wali if data provided
                $waliData = collect($validated)->only([
                    'nama_wali',
                    'nik_wali',
                    'hubungan_wali_dgn_siswa',
                    'alamat_wali'
                ])->filter()->toArray();

                if (!empty($waliData)) {
                    $waliData['user_id'] = $user->id;
                    Wali::create($waliData);
                }

                // Create berkas record
                BerkasPendaftar::create(['user_id' => $user->id]);
            });

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified student - ADMIN ONLY
     */
    public function show(User $siswa)
    {
        // Ensure it's a student
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $siswa->load(['biodata', 'orangTua', 'wali', 'berkas']);

        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified student - ADMIN ONLY
     */
    public function edit(User $siswa)
    {
        // Ensure it's a student
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $siswa->load(['biodata', 'orangTua', 'wali']);

        $jalurOptions = PpdbHelper::getJalurOptions();
        $statusOptions = PpdbHelper::getStatusOptions();

        return view('admin.siswa.edit', compact('siswa', 'jalurOptions', 'statusOptions'));
    }

    /**
     * Update the specified student - ADMIN ONLY
     */
    public function update(Request $request, User $siswa)
    {
        // Ensure it's a student
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $validated = $request->validate([
            // User data
            'nama_lengkap' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($siswa->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($siswa->id)],
            'nisn' => ['required', 'string', 'max:10', Rule::unique('users')->ignore($siswa->id)],
            'jalur_pendaftaran' => 'required|in:domisili,prestasi_akademik,prestasi_non_akademik,prestasi_rapor,afirmasi_ketm,afirmasi_disabilitas,mutasi_ortu,mutasi_guru',
            'status_pendaftaran' => 'required|in:akun_terdaftar,menunggu_kelengkapan_data,menunggu_verifikasi_berkas,berkas_tidak_lengkap,berkas_diverifikasi,lulus_seleksi,tidak_lulus_seleksi,daftar_ulang_selesai',
            'catatan_verifikasi' => 'nullable|string',

            // Same validation rules as store method for biodata, orang tua, wali
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date|before_or_equal:' . now()->subYears(10)->format('Y-m-d'),
            'jns_kelamin' => 'required|in:L,P',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu',
            'anak_ke' => 'required|integer|min:1',
            'jml_saudara_kandung' => 'required|integer|min:0',
            'asal_sekolah' => 'required|string|max:255',
            'alamat_asal_sekolah' => 'required|string|max:500',
            'alamat_rumah' => 'required|string|max:500',
            'kelurahan_desa' => 'required|string|max:100',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'kode_pos' => 'required|string|max:5',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'berat_badan' => 'nullable|numeric|min:0',
            'lingkar_kepala' => 'nullable|numeric|min:0',
            'foto_siswa' => 'nullable|image|mimes:jpeg,png,jpg|max:500',
            'nama_ibu' => 'required|string|max:100',
            'nama_ayah' => 'nullable|string|max:100',
            // ... other parent and wali fields
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $siswa) {
                // Update user
                $siswa->update([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'username' => $validated['username'],
                    'nisn' => $validated['nisn'],
                    'jalur_pendaftaran' => $validated['jalur_pendaftaran'],
                    'status_pendaftaran' => $validated['status_pendaftaran'],
                    'catatan_verifikasi' => $validated['catatan_verifikasi'],
                ]);

                // Handle foto upload
                if ($request->hasFile('foto_siswa')) {
                    // Delete old photo
                    if ($siswa->biodata && $siswa->biodata->foto_siswa) {
                        Storage::disk('public')->delete($siswa->biodata->foto_siswa);
                    }
                    $validated['foto_siswa'] = $request->file('foto_siswa')
                        ->store("foto_siswa_ppdb/{$siswa->nisn}", 'public');
                }

                // Update biodata
                $biodataData = collect($validated)->only([
                    'tempat_lahir',
                    'tgl_lahir',
                    'jns_kelamin',
                    'agama',
                    'anak_ke',
                    'jml_saudara_kandung',
                    'asal_sekolah',
                    'alamat_asal_sekolah',
                    'alamat_rumah',
                    'kelurahan_desa',
                    'rt',
                    'rw',
                    'kode_pos',
                    'tinggi_badan',
                    'berat_badan',
                    'lingkar_kepala',
                    'foto_siswa'
                ])->filter()->toArray();

                $siswa->biodata()->updateOrCreate(['user_id' => $siswa->id], $biodataData);

                // Update orang tua
                $orangTuaData = collect($validated)->only([
                    'nama_ayah',
                    'nik_ayah',
                    'tempat_lahir_ayah',
                    'tgl_lahir_ayah',
                    'pendidikan_ayah',
                    'pekerjaan_ayah',
                    'penghasilan_ayah',
                    'no_hp_ayah',
                    'nama_ibu',
                    'nik_ibu',
                    'tempat_lahir_ibu',
                    'tgl_lahir_ibu',
                    'pendidikan_ibu',
                    'pekerjaan_ibu',
                    'penghasilan_ibu',
                    'no_hp_ibu'
                ])->toArray();

                $siswa->orangTua()->updateOrCreate(['user_id' => $siswa->id], $orangTuaData);

                // Update wali
                $waliData = collect($validated)->only([
                    'nama_wali',
                    'nik_wali',
                    'hubungan_wali_dgn_siswa',
                    'alamat_wali'
                ])->filter()->toArray();

                if (!empty($waliData)) {
                    $siswa->wali()->updateOrCreate(['user_id' => $siswa->id], $waliData);
                } else {
                    $siswa->wali()->delete();
                }
            });

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified student from storage - ADMIN ONLY
     */
    public function destroy(User $siswa)
    {
        // Ensure it's a student
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        try {
            DB::transaction(function () use ($siswa) {
                // Delete files
                if ($siswa->biodata && $siswa->biodata->foto_siswa) {
                    Storage::disk('public')->delete($siswa->biodata->foto_siswa);
                }

                // Delete student (cascade will handle related records)
                $siswa->delete();
            });

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }
}
