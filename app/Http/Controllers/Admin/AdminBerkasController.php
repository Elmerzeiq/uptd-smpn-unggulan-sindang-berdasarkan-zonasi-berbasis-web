<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\BerkasHelper;
use App\Exports\BerkasExport;
use App\Http\Requests\Admin\FilterBerkasRequest;
use App\Http\Requests\Admin\VerifikasiBerkasRequest;
use App\Http\Requests\Admin\BulkVerifikasiBerkasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class AdminBerkasController extends Controller
{
    public function index(FilterBerkasRequest $request)
    {
        // Query dasar untuk mengambil siswa
        $query = User::where('role', 'siswa')
            ->whereNotNull('jalur_pendaftaran')
            ->with(['berkas', 'biodata']);

        // Terapkan filter dari request
        if ($request->filled('jalur_pendaftaran')) {
            $query->where('jalur_pendaftaran', $request->jalur_pendaftaran);
        }
        if ($request->filled('status_pendaftaran')) {
            $query->where('status_pendaftaran', $request->status_pendaftaran);
        }
        if ($request->filled('status_berkas')) {
            if ($request->status_berkas === 'ada_berkas') {
                $query->whereHas('berkas');
            } elseif ($request->status_berkas === 'belum_upload') {
                $query->whereDoesntHave('berkas');
            }
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        // Terapkan sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Lakukan paginasi
        $perPage = $request->get('per_page', 25);
        $siswa = $query->paginate($perPage)->withQueryString();

        // Ambil data statistik
        $statistik = BerkasHelper::getBerkasStatistics($request);

        // PERBAIKAN: Hapus blok kode yang salah yang mencoba mencari satu user.
        // Variabel yang dibutuhkan oleh view 'admin.berkas.index' hanyalah 'siswa' dan 'statistik'.

        return view('admin.berkas.index', compact('siswa', 'statistik'));
    }

    /**
     * Menampilkan halaman detail berkas SATU siswa.
     */
    public function show($id)
    {
        $siswa = User::where('role', 'siswa')
            ->with(['berkas', 'biodata'])
            ->findOrFail($id);

        // Ambil agama dengan cara yang aman
        $agama = optional($siswa->biodata)->agama ?? 'Islam';

        $definisiBerkas = BerkasHelper::getBerkasListForJalur($siswa->jalur_pendaftaran, $agama);
        $progress = BerkasHelper::calculateBerkasProgress($siswa);
        $berkasLengkap = $progress['is_wajib_lengkap'];

        return view('admin.berkas.show', compact('siswa', 'definisiBerkas', 'progress', 'berkasLengkap'));
    }

    public function verifikasi($id, VerifikasiBerkasRequest $request)
    {
        try {
            $siswa = User::where('role', 'siswa')->findOrFail($id);

            if (!$siswa->berkas) {
                return redirect()->back()->with('error', 'Siswa belum mengupload berkas.');
            }

            $aksi = $request->aksi;
            $catatan = $request->catatan;

            if ($aksi === 'verifikasi') {
                $siswa->update(['status_pendaftaran' => 'berkas_diverifikasi']);
                $message = 'Berkas siswa berhasil diverifikasi.';
            } else {
                $siswa->update(['status_pendaftaran' => 'berkas_tidak_lengkap']);
                $message = 'Berkas siswa ditolak.';
            }

            if ($siswa->berkas) {
                $siswa->berkas->update([
                    'catatan_admin' => $catatan,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);
            }

            BerkasHelper::logBerkasActivity("berkas_{$aksi}", $siswa->id, auth()->id(), [
                'catatan' => $catatan
            ]);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error verifikasi berkas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat verifikasi berkas.');
        }
    }


    public function bulkVerifikasi(BulkVerifikasiBerkasRequest $request)
    {
        try {
            $siswaIds = $request->siswa_ids;
            $aksi = $request->aksi;
            $catatan = $request->catatan;

            $successCount = 0;
            $errors = [];

            // Pastikan ada siswa yang dipilih
            if (empty($siswaIds)) {
                return redirect()->back()->with('error', 'Tidak ada siswa yang dipilih.');
            }

            foreach ($siswaIds as $siswaId) {
                $siswa = User::where('role', 'siswa')->find($siswaId);
                if (!$siswa || !$siswa->berkas) {
                    $errors[] = "Siswa ID {$siswaId} tidak valid atau belum upload berkas.";
                    continue;
                }

                if ($aksi === 'verifikasi') {
                    $siswa->update(['status_pendaftaran' => 'berkas_diverifikasi']);
                } else {
                    $siswa->update(['status_pendaftaran' => 'berkas_tidak_lengkap']);
                }

                $siswa->berkas->update([
                    'catatan_admin' => $catatan,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                BerkasHelper::logBerkasActivity("bulk_berkas_{$aksi}", $siswa->id, auth()->id(), ['catatan' => $catatan]);
                $successCount++;
            }

            $message = "Berhasil memproses {$successCount} siswa.";
            if (!empty($errors)) {
                $message .= " Gagal pada: " . implode(', ', $errors);
                return redirect()->back()->with('warning', $message);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error bulk verifikasi berkas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat proses aksi massal.');
        }
    }

    public function export(Request $request)
    {
        try {
            $filename = 'berkas-siswa-' . date('Y-m-d-H-i-s') . '.xlsx';

            BerkasHelper::logBerkasActivity('export_berkas', null, auth()->id(), [
                'filters' => $request->all(),
                'filename' => $filename
            ]);

            return Excel::download(new BerkasExport($request), $filename);
        } catch (\Exception $e) {
            Log::error('Error export berkas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data.');
        }
    }

    public function exportCsv(Request $request)
    {
        try {
            $filename = 'berkas-siswa-' . date('Y-m-d-H-i-s') . '.csv';

            BerkasHelper::logBerkasActivity('export_berkas_csv', null, auth()->id(), [
                'filters' => $request->all(),
                'filename' => $filename
            ]);

            return Excel::download(new BerkasExport($request), $filename, \Maatwebsite\Excel\Excel::CSV);
        } catch (\Exception $e) {
            Log::error('Error export berkas CSV: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data CSV.');
        }
    }

    public function exportStatistics()
    {
        try {
            $statistik = BerkasHelper::getBerkasStatistics(request());
            $filename = 'statistik-berkas-' . date('Y-m-d-H-i-s') . '.json';

            $headers = [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            BerkasHelper::logBerkasActivity('export_statistik', null, auth()->id());

            return Response::make(json_encode($statistik, JSON_PRETTY_PRINT), 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error export statistik: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export statistik.');
        }
    }

    public function downloadTemplate($templateName)
    {
        try {
            $templates = BerkasHelper::getTemplateFiles();

            if (!isset($templates[$templateName])) {
                return redirect()->back()->with('error', 'Template tidak ditemukan.');
            }

            $template = $templates[$templateName];
            $filePath = storage_path('app/public/' . $template['file']);

            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File template tidak ditemukan.');
            }

            BerkasHelper::logBerkasActivity('download_template', null, auth()->id(), [
                'template' => $templateName
            ]);

            return response()->download($filePath, $template['nama_lengkap'], [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            Log::error('Error download template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat download template.');
        }
    }

    public function deleteFile($id, $field, Request $request)
    {
        try {
            $siswa = User::where('role', 'siswa')->with('berkas')->findOrFail($id);

            if (!$siswa->berkas) {
                return redirect()->back()->with('error', 'Siswa belum mengupload berkas.');
            }

            $definisiBerkas = BerkasHelper::getBerkasListForJalur($siswa->jalur_pendaftaran, $siswa->biodata ? $siswa->biodata->agama : 'Islam');

            if (!array_key_exists($field, $definisiBerkas)) {
                return redirect()->back()->with('error', 'Berkas tidak valid.');
            }

            $berkas = $siswa->berkas;

            if (isset($definisiBerkas[$field]['multiple']) && $definisiBerkas[$field]['multiple']) {
                $fileIndex = $request->input('file_index');
                $files = json_decode($berkas->$field, true) ?? [];
                if (isset($files[$fileIndex]) && Storage::disk('public')->exists($files[$fileIndex])) {
                    Storage::disk('public')->delete($files[$fileIndex]);
                    unset($files[$fileIndex]);
                    $berkas->$field = !empty($files) ? json_encode(array_values($files)) : null;
                }
            } else {
                if ($berkas->$field && Storage::disk('public')->exists($berkas->$field)) {
                    Storage::disk('public')->delete($berkas->$field);
                    $berkas->$field = null;
                }
            }

            $berkas->save();

            BerkasHelper::logBerkasActivity('delete_file', $siswa->id, auth()->id(), [
                'field' => $field,
                'file_index' => $request->input('file_index', null),
            ]);

            return redirect()->back()->with('success', 'File berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus file.');
        }
    }

    public function download($id, $field, $index = null)
    {
        try {
            $siswa = User::where('role', 'siswa')->with('berkas')->findOrFail($id);

            if (!$siswa->berkas) {
                return redirect()->back()->with('error', 'Siswa belum mengupload berkas.');
            }

            $definisiBerkas = BerkasHelper::getBerkasListForJalur($siswa->jalur_pendaftaran, $siswa->biodata ? $siswa->biodata->agama : 'Islam');

            if (!array_key_exists($field, $definisiBerkas)) {
                return redirect()->back()->with('error', 'Berkas tidak valid.');
            }

            $berkas = $siswa->berkas;
            $filePath = null;

            if (isset($definisiBerkas[$field]['multiple']) && $definisiBerkas[$field]['multiple']) {
                $files = json_decode($berkas->$field, true) ?? [];
                if (!isset($files[$index])) {
                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }
                $filePath = $files[$index];
            } else {
                if (!$berkas->$field) {
                    return redirect()->back()->with('error', 'File tidak ditemukan.');
                }
                $filePath = $berkas->$field;
            }

            if (!Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak tersedia di server.');
            }

            $fileName = $siswa->nama_lengkap . '_' . $field . '_' . time() . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

            BerkasHelper::logBerkasActivity('download_file', $siswa->id, auth()->id(), [
                'field' => $field,
                'file_index' => $index,
                'file_name' => $fileName,
            ]);

            return Storage::disk('public')->download($filePath, $fileName);
        } catch (\Exception $e) {
            Log::error('Error downloading file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendownload file.');
        }
    }

    public function updateChecklist(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'field' => 'required|string',
            'checked' => 'required|boolean',
        ]);

        $siswa = User::where('role', 'siswa')->with('berkas')->findOrFail($id);

        if (!$siswa->berkas) {
            return response()->json(['error' => 'Data berkas siswa tidak ditemukan.'], 404);
        }

        // Ambil data checklist yang ada, atau buat array kosong jika belum ada
        $checklist = $siswa->berkas->checklist_status ?? [];

        // Update status untuk field yang spesifik
        $checklist[$request->field] = $request->checked;

        // Simpan kembali ke database
        $siswa->berkas->update(['checklist_status' => $checklist]);

        return response()->json(['success' => 'Status checklist diperbarui.']);
    }


}

