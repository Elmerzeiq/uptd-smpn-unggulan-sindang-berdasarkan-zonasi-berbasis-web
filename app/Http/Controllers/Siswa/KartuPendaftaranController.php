<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\KartuPendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KartuPendaftaranController extends Controller
{
    protected $qrCodeService;

    protected const VALID_STATUSES = [
        'belum_diverifikasi',
        'menunggu_kelengkapan_data',
        'menunggu_verifikasi_berkas',
        'berkas_tidak_lengkap',
        'berkas_diverifikasi',
        'lulus_seleksi',
        'tidak_lulus_seleksi',
        'mengundurkan_diri',
        'daftar_ulang_selesai'
    ];

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            Log::warning('Percobaan akses tanpa otorisasi ke kartu pendaftaran', [
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return $this->redirectWithMessage('login', 'error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        $prerequisites = $this->checkPrerequisites($user);
        if (!$prerequisites['valid']) {
            return redirect()->route('siswa.biodata.index')->with('warning', $prerequisites['message']);
        }

        if (is_null($user->status_pendaftaran)) {
            Log::error('Status pendaftaran null terdeteksi untuk pengguna', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            $user->status_pendaftaran = 'belum_diverifikasi';
            $user->save();
            Log::info('Status pendaftaran diatur ke belum_diverifikasi untuk pengguna', ['user_id' => $user->id]);
        }

        if (!in_array($user->status_pendaftaran, self::VALID_STATUSES)) {
            Log::warning('Status pendaftaran tidak valid untuk pengguna', [
                'user_id' => $user->id,
                'status' => $user->status_pendaftaran,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return redirect()->route('siswa.dashboard')->with('error', 'Status pendaftaran Anda tidak valid: ' . e($user->status_pendaftaran) . '. Silakan hubungi admin untuk perbaikan.');
        }

        $kartu = KartuPendaftaran::where('user_id', $user->id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'nama_lengkap', 'email', 'nisn', 'status_pendaftaran');
                },
                'user.biodata' => function ($query) {
                    $query->select('id', 'user_id', 'tempat_lahir', 'tgl_lahir', 'jns_kelamin', 'asal_sekolah', 'alamat_rumah');
                },
                'user.orangTua' => function ($query) {
                    $query->select('id', 'user_id', 'nama_ayah', 'pekerjaan_ayah', 'no_hp_ayah', 'nama_ibu', 'pekerjaan_ibu', 'no_hp_ibu');
                }
            ])
            ->select('id', 'user_id', 'nomor_kartu', 'jalur_pendaftaran', 'created_at')
            ->first();

        if (!$kartu) {
            $kartu = $this->createNewCard($user);
            if (!$kartu) {
                Log::error('Gagal membuat kartu pendaftaran baru untuk pengguna', [
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return back()->with('error', 'Gagal membuat kartu pendaftaran. Mohon coba lagi atau hubungi admin.');
            }
        }

        $isFinalCard = $request->query('type') === 'final';
        if ($isFinalCard && !$this->canAccessFinalCard($user)) {
            Log::warning('Percobaan akses kartu final tanpa otorisasi', [
                'user_id' => $user->id,
                'status' => $user->status_pendaftaran,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return redirect()->route('siswa.dashboard')
                ->with('warning', 'Anda belum memenuhi syarat untuk mengakses kartu final.');
        }

        try {
            $qrCodeResult = $this->qrCodeService->generateStyledQRCode($kartu, $isFinalCard);
        } catch (\Exception $e) {
            Log::error('Gagal menghasilkan QR code di show', [
                'user_id' => $user->id,
                'card_id' => $kartu->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return back()->with('error', 'Gagal menghasilkan QR code. Silakan coba lagi atau hubungi admin.');
        }

        return view('siswa.kartu-pendaftaran.show', [
            'kartu' => $kartu,
            'isFinalCard' => $isFinalCard,
            'user' => $user,
            'qrCode' => $qrCodeResult['base64'],
            'qrCodeDataUri' => $qrCodeResult['data_uri']
        ]);
    }

    public function downloadPdf($id, Request $request)
    {
        $kartu = KartuPendaftaran::with([
            'user' => function ($query) {
                $query->select('id', 'nama_lengkap', 'email', 'nisn', 'status_pendaftaran');
            },
            'user.biodata' => function ($query) {
                $query->select('id', 'user_id', 'tempat_lahir', 'tgl_lahir', 'jns_kelamin', 'asal_sekolah', 'alamat_rumah');
            },
            'user.orangTua' => function ($query) {
                $query->select('id', 'user_id', 'nama_ayah', 'pekerjaan_ayah', 'no_hp_ayah', 'nama_ibu', 'pekerjaan_ibu', 'no_hp_ibu');
            }
        ])
            ->select('id', 'user_id', 'nomor_kartu', 'jalur_pendaftaran', 'created_at')
            ->findOrFail($id);

        if ($kartu->user_id !== Auth::id()) {
            Log::warning('Percobaan akses tanpa otorisasi untuk unduh PDF', [
                'user_id' => Auth::id(),
                'card_id' => $id,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            abort(403, 'Akses tidak diizinkan.');
        }

        $user = $kartu->user;

        if (is_null($user->status_pendaftaran)) {
            Log::error('Status pendaftaran null terdeteksi untuk pengguna', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            $user->status_pendaftaran = 'belum_diverifikasi';
            $user->save();
            Log::info('Status pendaftaran diatur ke belum_diverifikasi untuk pengguna', ['user_id' => $user->id]);
        }

        if (!in_array($user->status_pendaftaran, self::VALID_STATUSES)) {
            Log::warning('Status pendaftaran tidak valid untuk pengguna', [
                'user_id' => $user->id,
                'status' => $user->status_pendaftaran,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return back()->with('error', 'Status pendaftaran Anda tidak valid: ' . e($user->status_pendaftaran) . '. Silakan hubungi admin untuk perbaikan.');
        }

        $isFinalCard = $request->query('type') === 'final';
        if ($isFinalCard && !$this->canAccessFinalCard($user)) {
            Log::warning('Percobaan unduh kartu final tanpa otorisasi', [
                'user_id' => $user->id,
                'status' => $user->status_pendaftaran,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            abort(403, 'Anda belum memenuhi syarat untuk mengunduh kartu final.');
        }

        try {
            $pdfView = 'siswa.kartu-pendaftaran.pdf';
            if (!view()->exists($pdfView)) {
                Log::error('File view PDF tidak ditemukan', [
                    'view' => $pdfView,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return back()->with('error', 'Template PDF tidak ditemukan. Silakan hubungi admin.');
            }

            $qrCodeResult = $this->qrCodeService->generateStyledQRCode($kartu, $isFinalCard);

            $pdf = Pdf::loadView($pdfView, [
                'kartu' => $kartu,
                'user' => $user,
                'isFinalCard' => $isFinalCard,
                'qrCode' => $qrCodeResult['base64'],
                'qrCodeDataUri' => $qrCodeResult['data_uri']
            ]);

            $pdf->setPaper('a4', 'portrait');
            $filename = ($isFinalCard ? 'kartu-final-SPMB-' : 'kartu-pendaftaran-') . $kartu->nomor_kartu . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Gagal menghasilkan PDF untuk pengguna {$user->id}: " . $e->getMessage(), [
                'user_id' => $user->id,
                'card_id' => $id,
                'is_final' => $isFinalCard,
                'stack_trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return back()->with('error', 'Gagal mengunduh kartu. Mohon coba lagi atau hubungi admin.');
        }
    }

    public function getCardData(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            Log::warning('Percobaan akses tanpa otorisasi ke getCardData', [
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['error' => 'Sesi Anda telah berakhir. Silakan login kembali.'], 401);
        }

        $prerequisites = $this->checkPrerequisites($user);
        if (!$prerequisites['valid']) {
            return response()->json(['error' => $prerequisites['message']], 403);
        }

        if (is_null($user->status_pendaftaran)) {
            Log::error('Status pendaftaran null terdeteksi untuk pengguna', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            $user->status_pendaftaran = 'belum_diverifikasi';
            $user->save();
            Log::info('Status pendaftaran diatur ke belum_diverifikasi untuk pengguna', ['user_id' => $user->id]);
        }

        if (!in_array($user->status_pendaftaran, self::VALID_STATUSES)) {
            Log::warning('Status pendaftaran tidak valid untuk pengguna', [
                'user_id' => $user->id,
                'status' => $user->status_pendaftaran,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['error' => 'Status pendaftaran Anda tidak valid: ' . e($user->status_pendaftaran) . '.'], 400);
        }

        $kartu = KartuPendaftaran::where('user_id', $user->id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'nama_lengkap', 'email', 'nisn', 'status_pendaftaran');
                },
                'user.biodata' => function ($query) {
                    $query->select('id', 'user_id', 'tempat_lahir', 'tgl_lahir', 'jns_kelamin', 'asal_sekolah', 'alamat_rumah');
                },
                'user.orangTua' => function ($query) {
                    $query->select('id', 'user_id', 'nama_ayah', 'pekerjaan_ayah', 'no_hp_ayah', 'nama_ibu', 'pekerjaan_ibu', 'no_hp_ibu');
                }
            ])
            ->select('id', 'user_id', 'nomor_kartu', 'jalur_pendaftaran', 'created_at')
            ->first();

        if (!$kartu) {
            Log::warning('Kartu pendaftaran tidak ditemukan untuk pengguna', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['error' => 'Kartu tidak ditemukan'], 404);
        }

        $isFinalCard = $request->query('type') === 'final';
        if ($isFinalCard && !$this->canAccessFinalCard($user)) {
            Log::warning('Percobaan akses kartu final tanpa otorisasi', [
                'user_id' => $user->id,
                'status' => $user->status_pendaftaran,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['error' => 'Anda belum memenuhi syarat untuk mengakses kartu final'], 403);
        }

        try {
            $qrCodeResult = $this->qrCodeService->generateStyledQRCode($kartu, $isFinalCard);
        } catch (\Exception $e) {
            Log::error('Gagal menghasilkan QR code di getCardData', [
                'user_id' => $user->id,
                'card_id' => $kartu->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json(['error' => 'Gagal menghasilkan QR code. Silakan coba lagi.'], 500);
        }

        return response()->json([
            'status' => 'success',
            'kartu' => $kartu,
            'user' => $user,
            'is_final_card' => $isFinalCard,
            'qr_code' => $qrCodeResult['base64'],
            'qr_code_data_uri' => $qrCodeResult['data_uri']
        ], 200);
    }

    protected function checkPrerequisites(User $user)
    {
        Log::info('Memeriksa prasyarat untuk pengguna', [
            'user_id' => $user->id,
            'timestamp' => now()->toDateTimeString()
        ]);
        if (!$user->biodata) {
            Log::warning('Biodata pengguna tidak ditemukan', ['user_id' => $user->id]);
            return ['valid' => false, 'message' => 'Lengkapi biodata Anda terlebih dahulu.'];
        }
        if (!$user->berkas || !$user->berkas->file_pas_foto) {
            Log::warning('Berkas atau pas foto pengguna tidak ditemukan', ['user_id' => $user->id]);
            return ['valid' => false, 'message' => 'Lengkapi berkas dan pas foto Anda terlebih dahulu.'];
        }
        return ['valid' => true, 'message' => ''];
    }

    protected function canAccessFinalCard(User $user)
    {
        $canAccess = in_array($user->status_pendaftaran, ['lulus_seleksi', 'daftar_ulang_selesai']);
        Log::info('Memeriksa akses kartu final', [
            'user_id' => $user->id,
            'status_pendaftaran' => $user->status_pendaftaran,
            'can_access' => $canAccess,
            'timestamp' => now()->toDateTimeString()
        ]);
        return $canAccess;
    }

    protected function createNewCard(User $user)
    {
        try {
            return KartuPendaftaran::create([
                'user_id' => $user->id,
                'nomor_kartu' => 'SPMB-' . date('Y') . '-' . Str::random(8),
                'jalur_pendaftaran' => $user->biodata->jalur_pendaftaran ?? 'domisili',
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat kartu pendaftaran baru untuk pengguna', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return null;
        }
    }

    protected function redirectWithMessage(string $route, string $type, string $message)
    {
        Log::info("Mengalihkan ke {$route} dengan pesan {$type}", [
            'message' => $message,
            'timestamp' => now()->toDateTimeString()
        ]);
        return redirect()->route($route)->with($type, $message);
    }



    public function verify(Request $request, $id) // <-- FUNGSI YANG DIPINDAHKAN
    {
        // Pemeriksaan !Auth::user()->isAdmin() tidak lagi diperlukan
        // jika rute ini sudah dilindungi oleh middleware admin.

        $request->validate([
            'status_pendaftaran' => 'required|in:berkas_diverifikasi,berkas_tidak_lengkap',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $siswa = User::findOrFail($id);
                $siswa->status_pendaftaran = $request->status_pendaftaran;
                $siswa->catatan_verifikasi = $request->catatan_verifikasi;
                $siswa->save();

                $kartu = KartuPendaftaran::firstOrNew(['user_id' => $siswa->id]);
                $kartu->verified_by = Auth::id();
                $kartu->verified_at = now();
                $kartu->catatan_admin = $request->catatan_verifikasi;
                $kartu->save();
            });

            return redirect()->back()->with('success', 'Status pendaftaran siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi data: ' . $e->getMessage());
        }
    }
}
