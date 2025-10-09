<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\QRCodeService;
use Illuminate\Validation\Rule;
use App\Models\KartuPendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminKartuPendaftaranController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function index(Request $request)
    {
        $query = KartuPendaftaran::with(['user.biodata']);

        if ($request->has('status') && $request->status !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status_pendaftaran', $request->status);
            });
        }

        if ($request->has('jalur') && $request->jalur !== '') {
            $query->where('jalur_pendaftaran', $request->jalur);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_kartu', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    });
            });
        }

        $kartuPendaftarans = $query->orderBy('created_at', 'desc')->paginate(20);
        $statistics = $this->getStatistics();

        return view('admin.kartu-pendaftaran.index', compact('kartuPendaftarans', 'statistics'));
    }

    public function show($id, Request $request)
    {
        $kartu = KartuPendaftaran::with([
            'user' => function ($query) {
                $query->select('id', 'nama_lengkap', 'email', 'nisn', 'status_pendaftaran');
            },
            'user.biodata' => function ($query) {
                $query->select('id', 'user_id', 'tempat_lahir', 'tgl_lahir', 'jns_kelamin', 'asal_sekolah', 'alamat_rumah');
            },
            'user.orangTua'
        ])->findOrFail($id);
        $qrCodeResult = $this->qrCodeService->generateStyledQRCode($kartu, false);

        return view('admin.kartu-pendaftaran.show', [
            'kartu' => $kartu,
            'qrCodeDataUri' => $qrCodeResult['data_uri']
        ]);
    }

    public function edit($id)
    {
        $kartu = KartuPendaftaran::with([
            'user' => function ($query) {
                $query->select('id', 'nama_lengkap', 'email', 'nisn', 'status_pendaftaran');
            },
            'user.biodata',
            'user.orangTua'
        ])->findOrFail($id);
        return view('admin.kartu-pendaftaran.edit', compact('kartu'));
    }

    public function update(Request $request, $id)
    {
        $kartu = KartuPendaftaran::with('user')->findOrFail($id);
        $user = $kartu->user;

        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'jalur_pendaftaran' => ['required', 'string', Rule::in([
                'domisili',
                'prestasi_akademik_lomba',
                'prestasi_non_akademik_lomba',
                'prestasi_rapor',
                'afirmasi_ketm',
                'afirmasi_disabilitas',
                'mutasi_pindah_tugas',
                'mutasi_anak_guru'
            ])],
            'nisn' => ['required', 'string', 'size:10', Rule::unique('users')->ignore($user->id)],
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jns_kelamin' => 'required|in:L,P',
            'asal_sekolah' => 'required|string|max:150',
            'alamat_rumah' => 'required|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'no_hp_ayah' => 'nullable|string|max:15',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'no_hp_ibu' => 'required|string|max:15',
            'status_pendaftaran' => [
                'required',
                'string',
                Rule::in([
                    'belum_diverifikasi',
                    'menunggu_kelengkapan_data',
                    'menunggu_verifikasi_berkas',
                    'berkas_tidak_lengkap',
                    'berkas_diverifikasi',
                    'lulus_seleksi',
                    'tidak_lulus_seleksi',
                    'mengundurkan_diri',
                    'daftar_ulang_selesai'
                ]),
            ],
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'nama_lengkap' => $validatedData['nama_lengkap'],
                'email' => $validatedData['email'],
                'nisn' => $validatedData['nisn'],
                'status_pendaftaran' => $validatedData['status_pendaftaran'],
            ]);

            $kartu->update([
                'jalur_pendaftaran' => $validatedData['jalur_pendaftaran'],
            ]);

            if ($user->biodata) {
                $user->biodata->update([
                    'tempat_lahir' => $validatedData['tempat_lahir'],
                    'tgl_lahir' => $validatedData['tgl_lahir'],
                    'jns_kelamin' => $validatedData['jns_kelamin'],
                    'asal_sekolah' => $validatedData['asal_sekolah'],
                    'alamat_rumah' => $validatedData['alamat_rumah'],
                ]);
            }

            if ($user->orangTua) {
                $user->orangTua->update([
                    'nama_ayah' => $validatedData['nama_ayah'],
                    'pekerjaan_ayah' => $validatedData['pekerjaan_ayah'],
                    'no_hp_ayah' => $validatedData['no_hp_ayah'],
                    'nama_ibu' => $validatedData['nama_ibu'],
                    'pekerjaan_ibu' => $validatedData['pekerjaan_ibu'],
                    'no_hp_ibu' => $validatedData['no_hp_ibu'],
                ]);
            }

            DB::commit();

            Log::info('Data pendaftaran berhasil diupdate oleh admin.', [
                'admin_id' => auth()->id(),
                'card_id' => $kartu->id,
                'user_id' => $user->id
            ]);

            return redirect()->route('admin.kartu-pendaftaran.show', $kartu->id)
                ->with('success', 'Data pendaftaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengupdate data pendaftaran: ' . $e->getMessage(), [
                'admin_id' => auth()->id(),
                'card_id' => $kartu->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.')->withInput();
        }
    }

    public function verify($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $kartu = KartuPendaftaran::with('user')->findOrFail($id);

            $kartu->update([
                'verified_by_admin' => true,
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);

            if (in_array($kartu->user->status_pendaftaran, ['belum_diverifikasi', 'berkas_tidak_lengkap'])) {
                $kartu->user->update(['status_pendaftaran' => 'berkas_diverifikasi']);
            }

            DB::commit();

            Log::info('Kartu pendaftaran verified', [
                'admin_id' => auth()->id(),
                'card_id' => $id,
                'user_id' => $kartu->user_id
            ]);

            return redirect()->route('admin.kartu-pendaftaran.show', $id)
                ->with('success', 'Kartu berhasil diverifikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to verify card: ' . $e->getMessage(), [
                'admin_id' => auth()->id(),
                'card_id' => $id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal memverifikasi kartu.');
        }
    }

    public function unverify($id, Request $request)
    {
        try {
            $kartu = KartuPendaftaran::with('user')->findOrFail($id);

            $kartu->update([
                'verified_by_admin' => false,
                'verified_at' => null,
                'verified_by' => null
            ]);

            if ($kartu->user->status_pendaftaran === 'berkas_diverifikasi') {
                $kartu->user->update(['status_pendaftaran' => 'belum_diverifikasi']);
            }

            Log::info('Kartu pendaftaran unverified', [
                'admin_id' => auth()->id(),
                'card_id' => $id,
                'user_id' => $kartu->user_id
            ]);

            return redirect()->route('admin.kartu-pendaftaran.show', $id)
                ->with('success', 'Verifikasi kartu berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Failed to unverify card: ' . $e->getMessage(), [
                'admin_id' => auth()->id(),
                'card_id' => $id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal membatalkan verifikasi.');
        }
    }

    private function getStatistics()
    {
        return [
            'total_cards' => KartuPendaftaran::count(),
            'verified_cards' => KartuPendaftaran::where('verified_by_admin', true)->count(),
            'pending_verification' => KartuPendaftaran::where('verified_by_admin', false)->count(),
            'lulus_seleksi' => User::where('status_pendaftaran', 'lulus_seleksi')->count(),
            'final_cards_eligible' => User::whereIn('status_pendaftaran', ['lulus_seleksi', 'daftar_ulang_selesai'])->count(),
            'by_jalur' => KartuPendaftaran::select('jalur_pendaftaran', DB::raw('count(*) as total'))
                ->groupBy('jalur_pendaftaran')
                ->get()
                ->pluck('total', 'jalur_pendaftaran')
                ->toArray()
        ];
    }

}
