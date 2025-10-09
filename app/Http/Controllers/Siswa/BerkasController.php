<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JadwalPpdb;
use App\Models\BerkasPendaftar;
use App\Services\BerkasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BerkasController extends Controller
{
    protected $berkasService;

    public function __construct(BerkasService $berkasService)
    {
        $this->berkasService = $berkasService;
    }

    /**
     * Menampilkan halaman utama untuk upload berkas.
     */
    public function index()
    {
        $user = Auth::guard('web')->user();

        if (!$user || $user->role !== 'siswa') {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai siswa untuk mengakses halaman ini.');
        }

        $user->load(['berkas', 'biodata', 'orangTua']);
        $berkasPendaftar = $user->berkas ?? $user->berkas()->firstOrNew(['user_id' => $user->id]);
        $jalur = $user->jalur_pendaftaran;

        if (!$jalur) {
            return redirect()->route('siswa.dashboard')->with('warning', 'Jalur pendaftaran Anda belum ditentukan. Harap hubungi panitia jika ini adalah kesalahan.');
        }

        // // Cek kelengkapan biodata
        // $biodataLengkap = $user->biodata && $user->orangTua &&
        //     !empty($user->biodata->nama_lengkap_siswa) &&
        //     !empty($user->orangTua->nama_ibu);

        // if (!$biodataLengkap && !in_array($user->status_pendaftaran, ['berkas_tidak_lengkap', 'menunggu_verifikasi_berkas'])) {
        //     return redirect()->route('siswa.biodata.index')->with('warning', 'Harap lengkapi biodata dan data keluarga terlebih dahulu sebelum mengupload berkas.');
        // }

        $agama = $user->biodata->agama ?? 'Islam';
        $daftarBerkas = $this->berkasService->getBerkasListByJalur($jalur, $agama);
        $progressBerkas = $this->berkasService->calculateBerkasProgress($user);
        $jadwalSiswaAktif = JadwalPpdb::aktif()->first();

        // Logika untuk mengizinkan upload
        $allowUpload = false;
        $statusBisaUpload = ['akun_terdaftar', 'menunggu_kelengkapan_data', 'menunggu_verifikasi_berkas', 'berkas_tidak_lengkap'];
        if ($jadwalSiswaAktif && $jadwalSiswaAktif->isPendaftaranOpen() && in_array($user->status_pendaftaran, $statusBisaUpload)) {
            $allowUpload = true;
        }

        return view('siswa.berkas.index', compact(
            'user',
            'berkasPendaftar',
            'jalur',
            'daftarBerkas',
            'allowUpload',
            'progressBerkas'
        ));
    }

    /**
     * Menyimpan atau memperbarui berkas yang diupload.
     */
    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user || $user->role !== 'siswa') {
            return redirect()->route('login')->with('error', 'Tidak diizinkan.');
        }

        $jalur = $user->jalur_pendaftaran;
        if (!$jalur) {
            return back()->with('error', 'Jalur pendaftaran tidak valid.');
        }

        $jadwalSiswaAktif = JadwalPpdb::aktif()->first();
        if (!$jadwalSiswaAktif || !$jadwalSiswaAktif->isPendaftaranOpen()) {
            return back()->with('error', 'Periode upload berkas sudah ditutup atau belum dibuka.');
        }

        $statusBisaUpload = ['akun_terdaftar', 'menunggu_kelengkapan_data', 'menunggu_verifikasi_berkas', 'berkas_tidak_lengkap'];
        if (!in_array($user->status_pendaftaran, $statusBisaUpload)) {
            return redirect()->route('siswa.berkas.index')->with('warning', 'Status pendaftaran Anda tidak memungkinkan untuk mengubah berkas.');
        }

        $user->load('biodata');
        $agama = $user->biodata->agama ?? 'Islam';
        $daftarBerkasDefinisi = $this->berkasService->getBerkasListByJalur($jalur, $agama);

        $rules = [];
        $attributeLabels = [];
        $uploadFields = [];
        $fileTypes = 'pdf,jpg,jpeg,png';

        foreach ($daftarBerkasDefinisi as $field => $details) {
            // Extract max size from keterangan
            preg_match('/Max:\s*(\d+)\s*(MB|KB)/i', $details['keterangan'], $matches);
            $maxSizeKB = 5 * 1024; // Default 5MB
            if (count($matches) === 3) {
                $size = (int)$matches[1];
                $unit = strtolower($matches[2]);
                $maxSizeKB = ($unit === 'mb') ? $size * 1024 : $size;
            }

            if (isset($details['multiple']) && $details['multiple']) {
                $rules[$field] = ['nullable', 'array', 'max:5'];
                $rules[$field . '.*'] = ['file', 'mimes:' . $fileTypes, 'max:' . $maxSizeKB];
            } else {
                $rules[$field] = ['nullable', 'file', 'mimes:' . $fileTypes, 'max:' . $maxSizeKB];
            }

            if ($request->hasFile($field)) {
                $uploadFields[] = $field;
                $attributeLabels[$field] = $details['label'];
                if (isset($details['multiple']) && $details['multiple']) {
                    $attributeLabels[$field . '.*'] = $details['label'] . ' (File)';
                }
            }
        }

        if (!empty($uploadFields)) {
            $validator = Validator::make($request->all(), $rules, [], $attributeLabels);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $berkasPendaftar = $user->berkas()->firstOrNew(['user_id' => $user->id]);
        $somethingUploaded = false;
        $uploadPathBase = "berkas_ppdb/{$user->id}_{$user->nisn}/{$jalur}";

        DB::transaction(function () use ($request, $berkasPendaftar, $daftarBerkasDefinisi, $uploadFields, $uploadPathBase, &$somethingUploaded) {
            foreach ($uploadFields as $field) {
                if ($request->hasFile($field)) {
                    $details = $daftarBerkasDefinisi[$field];

                    if (isset($details['multiple']) && $details['multiple']) {
                        // Multiple files
                        $existingFiles = json_decode($berkasPendaftar->$field, true) ?? [];
                        // Delete existing files
                        foreach ($existingFiles as $path) {
                            Storage::disk('public')->delete($path);
                        }

                        $newPaths = [];
                        foreach ($request->file($field) as $file) {
                            $newPaths[] = $file->store($uploadPathBase . '/' . $field, 'public');
                        }
                        $berkasPendaftar->$field = json_encode($newPaths);
                    } else {
                        // Single file
                        if ($berkasPendaftar->$field) {
                            Storage::disk('public')->delete($berkasPendaftar->$field);
                        }
                        $berkasPendaftar->$field = $request->file($field)->store($uploadPathBase, 'public');
                    }
                    $somethingUploaded = true;
                }
            }

            if ($somethingUploaded || !$berkasPendaftar->exists) {
                $berkasPendaftar->save();
            }
        });

        // Update user status after upload
        $this->updateUserStatusAfterDataCompletion($user->fresh(['biodata', 'orangTua', 'berkas']));

        $message = $somethingUploaded ? 'Berkas berhasil diupload/diperbarui.' : 'Tidak ada berkas baru yang dipilih untuk diupload.';
        return redirect()->route('siswa.berkas.index')->with('success', $message);
    }

    /**
     * Menghapus file individual yang sudah diupload.
     */
    public function deleteFile(Request $request, $field_name)
    {
        $user = Auth::guard('web')->user();
        $berkasPendaftar = $user->berkas;

        if (!$berkasPendaftar) {
            return back()->with('error', 'Data berkas tidak ditemukan.');
        }

        $agama = $user->biodata->agama ?? 'Islam';
        $daftarBerkasDefinisi = $this->berkasService->getBerkasListByJalur($user->jalur_pendaftaran, $agama);

        if (!array_key_exists($field_name, $daftarBerkasDefinisi)) {
            return back()->with('error', "Field berkas tidak valid.");
        }

        $details = $daftarBerkasDefinisi[$field_name];

        DB::transaction(function () use ($request, $berkasPendaftar, $field_name, $details, $user) {
            if (isset($details['multiple']) && $details['multiple']) {
                $fileIndex = $request->input('file_index');
                $storedFiles = json_decode($berkasPendaftar->$field_name, true) ?? [];

                if (isset($storedFiles[$fileIndex])) {
                    Storage::disk('public')->delete($storedFiles[$fileIndex]);
                    unset($storedFiles[$fileIndex]);
                    $berkasPendaftar->$field_name = !empty($storedFiles) ? json_encode(array_values($storedFiles)) : null;
                }
            } else {
                Storage::disk('public')->delete($berkasPendaftar->$field_name);
                $berkasPendaftar->$field_name = null;
            }

            $berkasPendaftar->save();
            $this->updateUserStatusAfterDataCompletion($user->fresh(['biodata', 'orangTua', 'berkas']));
        });

        return redirect()->route('siswa.berkas.index')->with('success', "Berkas '{$details['label']}' berhasil dihapus.");
    }

    /*
    |--------------------------------------------------------------------------
    | Resource Methods (Redirect to Index)
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return redirect()->route('siswa.berkas.index');
    }

    public function show($id)
    {
        return redirect()->route('siswa.berkas.index');
    }

    public function edit($id)
    {
        return redirect()->route('siswa.berkas.index');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('siswa.berkas.index');
    }

    public function destroy($id)
    {
        return redirect()->route('siswa.berkas.index')->with('error', 'Operasi ini tidak diizinkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */
    private function updateUserStatusAfterDataCompletion(User $user)
    {
        $finalStatuses = ['lulus_seleksi', 'tidak_lulus_seleksi', 'daftar_ulang_selesai'];
        if (in_array($user->status_pendaftaran, $finalStatuses)) {
            return;
        }

        $biodataLengkap = $user->biodata &&
            $user->biodata->tgl_lahir &&
            $user->orangTua &&
            $user->orangTua->nama_ibu;

        $agama = $user->biodata ? $user->biodata->agama : 'Islam';
        $definisiBerkas = $this->berkasService->getBerkasListByJalur($user->jalur_pendaftaran, $agama);
        $berkasLengkap = $this->berkasService->isBerkasWajibLengkap($user, $definisiBerkas);

        if ($biodataLengkap && $berkasLengkap) {
            $user->status_pendaftaran = 'menunggu_verifikasi_berkas';
            $user->catatan_verifikasi = null;
        } elseif ($user->status_pendaftaran !== 'berkas_tidak_lengkap') {
            $user->status_pendaftaran = 'menunggu_kelengkapan_data';
        }

        $user->save();
    }
}
