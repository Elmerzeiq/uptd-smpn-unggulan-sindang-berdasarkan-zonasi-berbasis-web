<?php
// =====================================================================
// FILE 1: app/Http/Controllers/Siswa/ProfileController.php
// =====================================================================

namespace App\Http\Controllers\Siswa;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);

        return view('siswa.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);

        return view('siswa.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

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
        ]);

        try {
            // Update data user
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'jalur_pendaftaran' => $request->jalur_pendaftaran,
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

            // Update atau create data orang tua
            $orangTuaData = [
                'nama_ayah' => $request->nama_ayah,
                'no_hp_ayah' => $request->no_hp_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp_ibu' => $request->no_hp_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
            ];

            if ($user->orangTua) {
                $user->orangTua->update($orangTuaData);
            } else {
                $user->orangTua()->create($orangTuaData);
            }

            // Update atau create data wali (jika ada)
            if ($request->filled('nama_wali')) {
                $waliData = [
                    'nama_wali' => $request->nama_wali,
                    'hubungan_wali_dgn_calon_peserta' => $request->hubungan_wali_dgn_calon_peserta,
                    'no_hp_wali' => $request->no_hp_wali,
                    'alamat_wali' => $request->alamat_wali,
                ];

                if ($user->wali) {
                    $user->wali->update($waliData);
                } else {
                    $user->wali()->create($waliData);
                }
            } else {
                // Hapus data wali jika tidak diisi
                if ($user->wali) {
                    $user->wali->delete();
                }
            }

            return redirect()->route('siswa.profile.show')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('siswa.profile.show')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Show pendaftar details (accessible by any student)
     */
    public function showPendaftar(User $user)
    {
        if ($user->role !== 'siswa') {
            abort(404);
        }

        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);
        $jalur = $user->jalur_pendaftaran;
        $berkasList = $this->getBerkasListByJalur($jalur);

        return view('siswa.pendaftar.show', compact('user', 'berkasList'));
    }

    /**
     * Edit pendaftar form (only for own data)
     */
    public function editPendaftar(User $user)
    {
        // Siswa hanya bisa edit data mereka sendiri
        if ($user->role !== 'siswa' || $user->id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);
        $jalur = $user->jalur_pendaftaran;
        $berkasList = $this->getBerkasListByJalur($jalur);

        return view('siswa.pendaftar.edit', compact('user', 'berkasList'));
    }

    /**
     * Update pendaftar data (only for own data)
     */
    public function updatePendaftar(Request $request, User $user)
    {
        // Siswa hanya bisa update data mereka sendiri
        if ($user->role !== 'siswa' || $user->id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate data ini.');
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
        ]);

        try {
            // Update data user
            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'email' => $request->email,
                'jalur_pendaftaran' => $request->jalur_pendaftaran,
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

            // Update atau create data orang tua
            $orangTuaData = [
                'nama_ayah' => $request->nama_ayah,
                'no_hp_ayah' => $request->no_hp_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp_ibu' => $request->no_hp_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
            ];

            if ($user->orangTua) {
                $user->orangTua->update($orangTuaData);
            } else {
                $user->orangTua()->create($orangTuaData);
            }

            // Update atau create data wali (jika ada)
            if ($request->filled('nama_wali')) {
                $waliData = [
                    'nama_wali' => $request->nama_wali,
                    'hubungan_wali_dgn_calon_peserta' => $request->hubungan_wali_dgn_calon_peserta,
                    'no_hp_wali' => $request->no_hp_wali,
                    'alamat_wali' => $request->alamat_wali,
                ];

                if ($user->wali) {
                    $user->wali->update($waliData);
                } else {
                    $user->wali()->create($waliData);
                }
            } else {
                // Hapus data wali jika tidak diisi
                if ($user->wali) {
                    $user->wali->delete();
                }
            }

            return redirect()->route('siswa.pendaftar.show', $user->id)->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Check profile completion status
     */
    public function checkCompletion()
    {
        $user = Auth::user();
        $user->load(['biodata', 'orangTua', 'wali', 'berkas']);

        $completion = [
            'biodata' => $user->biodata ? 100 : 0,
            'orang_tua' => $user->orangTua ? 100 : 0,
            'wali' => $user->wali ? 100 : 0,
            'berkas' => 0
        ];

        // Calculate berkas completion
        if ($user->berkas) {
            $berkasList = $this->getBerkasListByJalur($user->jalur_pendaftaran);
            $uploaded = 0;
            $total = count($berkasList);

            foreach ($berkasList as $field => $details) {
                if ($user->berkas->$field) {
                    $uploaded++;
                }
            }

            $completion['berkas'] = $total > 0 ? round(($uploaded / $total) * 100) : 0;
        }

        $overall = round(array_sum($completion) / count($completion));

        return response()->json([
            'completion' => $completion,
            'overall' => $overall
        ]);
    }

    /**
     * Get berkas upload progress
     */
    public function getBerkasProgress()
    {
        $user = Auth::user();
        $user->load(['berkas']);

        $berkasList = $this->getBerkasListByJalur($user->jalur_pendaftaran);
        $progress = [];

        foreach ($berkasList as $field => $details) {
            $progress[$field] = [
                'label' => $details['label'],
                'uploaded' => $user->berkas && $user->berkas->$field ? true : false,
                'required' => !isset($details['optional']) || !$details['optional']
            ];
        }

        return response()->json($progress);
    }

    /**
     * Get berkas list by jalur
     */
    private function getBerkasListByJalur($jalur)
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
