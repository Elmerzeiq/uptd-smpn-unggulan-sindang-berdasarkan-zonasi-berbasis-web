<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Biodata;
use App\Models\OrangTua;
use App\Models\Wali;
use App\Models\Berkas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\JadwalPpdb;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ReflectionObject;

class DataPendaftarController extends Controller
{
    /**
     * Display a listing of the students
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa')->with(['biodata', 'berkas', 'orangTua']);

        // Apply filters
        if ($request->filled('jalur')) {
            $query->where('jalur_pendaftaran', $request->jalur);
        }
        if ($request->filled('status')) {
            $query->where('status_pendaftaran', $request->status);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', "%{$searchTerm}%")
                    ->orWhere('nisn', 'like', "%{$searchTerm}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $perPage = $request->get('per_page', 20);
        $pendaftars = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Prepare options for filters
        $statusOptions = [
            'akun_terdaftar',
            'menunggu_kelengkapan_data',
            'menunggu_verifikasi_berkas',
            'berkas_tidak_lengkap',
            'berkas_diverifikasi',
            'lulus_seleksi',
            'tidak_lulus_seleksi',
            'mengundurkan_diri',
            'daftar_ulang_selesai'
        ];
        $jalurOptions = ['domisili', 'prestasi', 'afirmasi', 'mutasi'];

        return view('admin.pendaftar.index', compact('pendaftars', 'statusOptions', 'jalurOptions'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $statusOptions = [
            'akun_terdaftar' => 'Akun Terdaftar',
            'menunggu_kelengkapan_data' => 'Menunggu Kelengkapan Data',
            'menunggu_verifikasi_berkas' => 'Menunggu Verifikasi Berkas',
        ];
        $jalurOptions = ['domisili', 'prestasi', 'afirmasi', 'mutasi'];

        return view('admin.pendaftar.create', compact('statusOptions', 'jalurOptions'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:users,nisn',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'jalur_pendaftaran' => 'required|in:domisili,prestasi,afirmasi,mutasi',
            'status_pendaftaran' => 'required|in:akun_terdaftar,menunggu_kelengkapan_data,menunggu_verifikasi_berkas',

            // Biodata validation
            'tempat_lahir' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date|before:today',
            'jns_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:50',
            'asal_sekolah' => 'nullable|string|max:255',
            'alamat_rumah' => 'nullable|string|max:500',
            'anak_ke' => 'nullable|integer|min:1|max:20',

            // Orang tua validation
            'nama_ayah' => 'nullable|string|max:255',
            'no_hp_ayah' => 'nullable|string|max:20',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',
            'nama_ibu' => 'nullable|string|max:255',
            'no_hp_ibu' => 'nullable|string|max:20',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|in:SD,SMP,SMA,D3,S1,S2,S3',

            // Wali validation
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali_dgn_calon_peserta' => 'nullable|string|max:50',
            'no_hp_wali' => 'nullable|string|max:20',
            'alamat_wali' => 'nullable|string|max:500',

            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        try {
            // Generate nomor pendaftaran
            $noPendaftaran = $this->generateNoPendaftaran();

            // Create user
            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'no_pendaftaran' => $noPendaftaran,
                'jalur_pendaftaran' => $request->jalur_pendaftaran,
                'status_pendaftaran' => $request->status_pendaftaran,
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            // Create biodata if provided
            if ($request->filled(['tempat_lahir', 'tgl_lahir', 'jns_kelamin', 'agama', 'asal_sekolah', 'alamat_rumah', 'anak_ke'])) {
                $user->biodata()->create([
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'jns_kelamin' => $request->jns_kelamin,
                    'agama' => $request->agama,
                    'asal_sekolah' => $request->asal_sekolah,
                    'alamat_rumah' => $request->alamat_rumah,
                    'anak_ke' => $request->anak_ke,
                ]);
            }

            // Create orang tua if provided
            if ($request->filled(['nama_ayah', 'nama_ibu'])) {
                $user->orangTua()->create([
                    'nama_ayah' => $request->nama_ayah,
                    'no_hp_ayah' => $request->no_hp_ayah,
                    'pekerjaan_ayah' => $request->pekerjaan_ayah,
                    'pendidikan_ayah' => $request->pendidikan_ayah,
                    'nama_ibu' => $request->nama_ibu,
                    'no_hp_ibu' => $request->no_hp_ibu,
                    'pekerjaan_ibu' => $request->pekerjaan_ibu,
                    'pendidikan_ibu' => $request->pendidikan_ibu,
                ]);
            }

            // Create wali if provided
            if ($request->filled('nama_wali')) {
                $user->wali()->create([
                    'nama_wali' => $request->nama_wali,
                    'hubungan_wali_dgn_calon_peserta' => $request->hubungan_wali_dgn_calon_peserta,
                    'no_hp_wali' => $request->no_hp_wali,
                    'alamat_wali' => $request->alamat_wali,
                ]);
            }

            // Create empty berkas record
            $user->berkas()->create([]);

            return redirect()->route('admin.pendaftar.show', $user->id)
                ->with('success', 'Data pendaftar berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified student
     */
    public function show(User $user)
    {
        if ($user->role !== 'siswa') {
            abort(404);
        }
        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);
        $jalur = $user->jalur_pendaftaran;
        $berkasList = $this->getBerkasListByJalur($jalur);

        return view('admin.pendaftar.show', compact('user', 'berkasList'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(User $user)
    {
        if ($user->role !== 'siswa') {
            abort(404);
        }

        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);
        $jalur = $user->jalur_pendaftaran;
        $berkasList = $this->getBerkasListByJalur($jalur);

        return view('admin.pendaftar.edit', compact('user', 'berkasList'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'User bukan siswa.');
        }

        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'jalur_pendaftaran' => 'required|in:domisili,prestasi,afirmasi,mutasi',
            'status_pendaftaran' => 'required|in:akun_terdaftar,menunggu_kelengkapan_data,menunggu_verifikasi_berkas,berkas_tidak_lengkap,berkas_diverifikasi,lulus_seleksi,tidak_lulus_seleksi,mengundurkan_diri,daftar_ulang_selesai',

            // Biodata validation
            'tempat_lahir' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date|before:today',
            'jns_kelamin' => 'nullable|in:L,P',
            'agama' => 'nullable|string|max:50',
            'asal_sekolah' => 'nullable|string|max:255',
            'alamat_rumah' => 'nullable|string|max:500',
            'anak_ke' => 'nullable|integer|min:1|max:20',

            // Catatan admin
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        try {
            // Update data user
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'jalur_pendaftaran' => $request->jalur_pendaftaran,
                'status_pendaftaran' => $request->status_pendaftaran,
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            // Update atau create biodata
            $biodataData = [
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'jns_kelamin' => $request->jns_kelamin,
                'agama' => $request->agama,
                'asal_sekolah' => $request->asal_sekolah,
                'alamat_rumah' => $request->alamat_rumah,
                'anak_ke' => $request->anak_ke,
            ];

            if ($user->biodata) {
                $user->biodata->update($biodataData);
            } else {
                $user->biodata()->create($biodataData);
            }

            return redirect()->route('admin.pendaftar.show', $user->id)->with('success', 'Data pendaftar berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified student
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'User bukan siswa.');
        }

        try {
            // Hapus relasi
            if ($user->biodata) {
                $user->biodata->delete();
            }
            if ($user->orangTua) {
                $user->orangTua->delete();
            }
            if ($user->wali) {
                $user->wali->delete();
            }
            if ($user->berkas) {
                // Hapus file-file berkas dari storage
                $reflection = new ReflectionObject($user->berkas);
                $properties = $reflection->getProperties();
                foreach ($properties as $property) {
                    if (strpos($property->getName(), 'file_') === 0) {
                        $filePath = $property->getValue($user->berkas);
                        if ($filePath && Storage::exists($filePath)) {
                            Storage::delete($filePath);
                        }
                    }
                }
                $user->berkas->delete();
            }

            // Hapus user
            $user->delete();

            return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pendaftaran
     */
    public function updateStatusPendaftaran(Request $request, User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'User bukan siswa.');
        }

        $request->validate([
            'status_pendaftaran_baru' => 'required|string',
            'catatan_verifikasi_baru' => 'nullable|string',
        ]);

        $user->status_pendaftaran = $request->status_pendaftaran_baru;
        $user->catatan_verifikasi = $request->catatan_verifikasi_baru ?? $user->catatan_verifikasi;
        $user->save();

        return redirect()->route('admin.pendaftar.show', $user->id)->with('success', 'Status pendaftaran siswa berhasil diperbarui.');
    }

    /**
     * Verifikasi berkas
     */
    public function verifikasiBerkas(Request $request, User $user)
    {
        if ($user->role !== 'siswa') {
            return back()->with('error', 'User bukan siswa.');
        }

        $request->validate([
            'status_verifikasi' => 'required|in:berkas_diverifikasi,berkas_tidak_lengkap',
            'catatan_verifikasi' => Rule::requiredIf($request->status_verifikasi === 'berkas_tidak_lengkap'),
        ]);

        $user->status_pendaftaran = $request->status_verifikasi;
        $user->catatan_verifikasi = $request->catatan_verifikasi;
        $user->save();

        return redirect()->route('admin.pendaftar.show', $user->id)->with('success', 'Verifikasi berkas berhasil disimpan.');
    }

    /**
     * Export data to CSV
     */
    public function export(Request $request)
    {
        $query = User::where('role', 'siswa')->with(['biodata', 'orangTua', 'wali', 'berkas']);

        // Apply filters
        if ($request->filled('jalur')) {
            $query->where('jalur_pendaftaran', $request->jalur);
        }
        if ($request->filled('status')) {
            $query->where('status_pendaftaran', $request->status);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', "%{$searchTerm}%")
                    ->orWhere('nisn', 'like', "%{$searchTerm}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$searchTerm}%");
            });
        }

        $pendaftars = $query->orderBy('created_at', 'desc')->get();

        // Create CSV
        $filename = 'data-pendaftar-ppdb-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($pendaftars) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, [
                'No. Pendaftaran',
                'Nama Lengkap',
                'NISN',
                'Email',
                'Jalur Pendaftaran',
                'Status Pendaftaran',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Agama',
                'Asal Sekolah',
                'Alamat Rumah',
                'Nama Ayah',
                'No. HP Ayah',
                'Nama Ibu',
                'No. HP Ibu',
                'Tanggal Daftar',
                'Catatan Verifikasi'
            ]);

            // Data
            foreach ($pendaftars as $pendaftar) {
                fputcsv($file, [
                    $pendaftar->no_pendaftaran,
                    $pendaftar->nama_lengkap,
                    $pendaftar->nisn,
                    $pendaftar->email,
                    ucfirst($pendaftar->jalur_pendaftaran),
                    ucwords(str_replace('_', ' ', $pendaftar->status_pendaftaran)),
                    $pendaftar->biodata?->tempat_lahir ?? '',
                    $pendaftar->biodata?->tgl_lahir?->format('d/m/Y') ?? '',
                    $pendaftar->biodata?->jns_kelamin == 'L' ? 'Laki-laki' : ($pendaftar->biodata?->jns_kelamin == 'P' ? 'Perempuan' : ''),
                    $pendaftar->biodata?->agama ?? '',
                    $pendaftar->biodata?->asal_sekolah ?? '',
                    $pendaftar->biodata?->alamat_rumah ?? '',
                    $pendaftar->orangTua?->nama_ayah ?? '',
                    $pendaftar->orangTua?->no_hp_ayah ?? '',
                    $pendaftar->orangTua?->nama_ibu ?? '',
                    $pendaftar->orangTua?->no_hp_ibu ?? '',
                    $pendaftar->created_at->format('d/m/Y H:i'),
                    $pendaftar->catatan_verifikasi ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'required|in:berkas_diverifikasi,berkas_tidak_lengkap,lulus_seleksi,tidak_lulus_seleksi',
            'catatan' => 'nullable|string|max:1000'
        ]);

        try {
            $updatedCount = User::whereIn('id', $request->user_ids)
                ->where('role', 'siswa')
                ->update([
                    'status_pendaftaran' => $request->status,
                    'catatan_verifikasi' => $request->catatan
                ]);

            return redirect()->route('admin.pendaftar.index')
                ->with('success', "Berhasil mengupdate status {$updatedCount} pendaftar.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => User::where('role', 'siswa')->count(),
            'lulus_seleksi' => User::where('role', 'siswa')->where('status_pendaftaran', 'lulus_seleksi')->count(),
            'menunggu_verifikasi' => User::where('role', 'siswa')->where('status_pendaftaran', 'menunggu_verifikasi_berkas')->count(),
            'berkas_tidak_lengkap' => User::where('role', 'siswa')->where('status_pendaftaran', 'berkas_tidak_lengkap')->count(),
            'daftar_ulang_selesai' => User::where('role', 'siswa')->where('status_pendaftaran', 'daftar_ulang_selesai')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Quick status update via AJAX
     */
    public function quickStatusUpdate(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:berkas_diverifikasi,berkas_tidak_lengkap,lulus_seleksi,tidak_lulus_seleksi',
            'catatan' => 'nullable|string|max:1000'
        ]);

        try {
            $user->update([
                'status_pendaftaran' => $request->status,
                'catatan_verifikasi' => $request->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate',
                'status' => $user->status_pendaftaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate nomor pendaftaran
     */
    private function generateNoPendaftaran()
    {
        $tahun = date('Y');
        $bulan = date('m');

        // Format: PPDB-YYYY-MM-XXXX
        $prefix = "SPMB-{$tahun}-{$bulan}-";

        // Cari nomor terakhir
        $lastNumber = User::where('no_pendaftaran', 'like', $prefix . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->value('no_pendaftaran');

        if ($lastNumber) {
            $lastSequence = (int) substr($lastNumber, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $prefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get berkas list by jalur
     */
    public function getBerkasListByJalur($jalur)
    {
        $commonBerkas = [
            'file_ijazah_skl' => ['label' => 'Ijazah SD/MI atau Surat Keterangan Lulus'],
            'file_nisn_screenshot' => ['label' => 'Screenshot NISN'],
            'file_kk' => ['label' => 'Kartu Keluarga'],
            'file_akta_kia' => ['label' => 'Akta Kelahiran/KIA/Surat Keterangan Lahir'],
            'file_ktp_ortu' => ['label' => 'KTP Orang Tua'],
            'file_pas_foto' => ['label' => 'Pas Foto 3x4'],
            'file_surat_pernyataan_ortu' => ['label' => 'Surat Pernyataan Keaslian Dokumen'],
            'file_skkb_sd_desa' => ['label' => 'Surat Keterangan Kelakuan Baik'],
            'file_ijazah_mda_pernyataan' => ['label' => 'Ijazah MDA/Surat Pernyataan MDA', 'optional' => true],
            'file_suket_baca_quran_mda' => ['label' => 'Surat Keterangan Mampu Membaca Al-Quran', 'optional' => true],
        ];

        $berkasList = [
            'domisili' => array_merge($commonBerkas, [
                'file_suket_domisili' => ['label' => 'Surat Keterangan Domisili'],
            ]),
            'prestasi' => array_merge($commonBerkas, [
                'file_sertifikat_prestasi_lomba' => ['label' => 'Sertifikat Prestasi Lomba'],
                'file_surat_pertanggungjawaban_kepsek_lomba' => ['label' => 'Surat Pertanggungjawaban Kepala Sekolah'],
                'file_rapor_5_semester' => ['label' => 'Rapor 5 Semester'],
                'file_suket_nilai_rapor_peringkat_kepsek' => ['label' => 'Surat Keterangan Nilai Rapor'],
            ]),
            'afirmasi' => array_merge($commonBerkas, [
                'file_kartu_bantuan_sosial' => ['label' => 'KIP/PKH/KKS'],
                'file_sktm_dtks_dinsos' => ['label' => 'SKTM/DTKS Dinsos'],
                'file_suket_disabilitas_dokter_psikolog' => ['label' => 'Surat Keterangan Disabilitas', 'optional' => true],
            ]),
            'mutasi' => array_merge($commonBerkas, [
                'file_surat_penugasan_ortu_instansi' => ['label' => 'Surat Penugasan Ortu/Wali'],
                'file_sk_penugasan_guru_tendik' => ['label' => 'SK Penugasan Guru/Tenaga Kependidikan', 'optional' => true],
                'file_surat_rekomendasi_dirjen_luarnegeri' => ['label' => 'Surat Rekomendasi Izin Belajar (Luar Negeri)', 'optional' => true],
            ]),
        ];

        return $berkasList[$jalur] ?? $commonBerkas;
    }
}
