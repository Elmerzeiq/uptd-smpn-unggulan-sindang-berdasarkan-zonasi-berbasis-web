<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;

class AkunPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search_akun');
        $role = $request->query('role_filter');

        $query = User::query();

        if ($search) {
            $query->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%");
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->paginate(10);
        $roleOptions = ['admin', 'siswa']; // Definisikan opsi role secara eksplisit

        return view('admin.akun_pengguna.index', compact('users', 'roleOptions'));
    }

    public function create()
    {
        $user = new User();
        return view('admin.akun_pengguna.create', compact('user'));
    }


    public function edit(User $akun_pengguna)
    {
        $user = $akun_pengguna;
        return view('admin.akun_pengguna.edit', compact('user'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required_if:role,admin', 'nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'nisn' => ['required_if:role,siswa', 'nullable', 'string', 'max:10', 'unique:users,nisn'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'siswa'])],
            'status_pendaftaran' => ['nullable', Rule::in(['belum_diverifikasi', 'menunggu_kelengkapan_data', 'menunggu_verifikasi_berkas', 'berkas_tidak_lengkap', 'berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi', 'mengundurkan_diri', 'daftar_ulang_selesai'])],
            'jalur_pendaftaran' => ['nullable', Rule::in(['domisili', 'prestasi_akademik_lomba', 'prestasi_non_akademik_lomba', 'prestasi_rapor', 'afirmasi_ketm', 'afirmasi_disabilitas', 'mutasi_pindah_tugas', 'mutasi_anak_guru'])],
            'kecamatan_domisili' => ['nullable', 'string', 'max:100', Rule::requiredIf(fn() => $request->input('jalur_pendaftaran') === 'domisili' && $request->input('role') === 'siswa')],
            // 'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            // 'foto_profil.image' => 'File harus berupa gambar.',
            // 'foto_profil.mimes' => 'File harus dalam format: jpg, jpeg, atau png.',
            // 'foto_profil.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        $dataToCreate = $request->only(['nama_lengkap', 'role']);

        if ($request->role == 'admin') {
            $dataToCreate['email'] = $request->email;
            $dataToCreate['nisn'] = null;
        } elseif ($request->role == 'siswa') {
            $dataToCreate['nisn'] = $request->nisn;
            $dataToCreate['email'] = null;
            if ($request->filled('status_pendaftaran')) $dataToCreate['status_pendaftaran'] = $request->status_pendaftaran;
            if ($request->filled('jalur_pendaftaran')) $dataToCreate['jalur_pendaftaran'] = $request->jalur_pendaftaran;
            if ($request->input('jalur_pendaftaran') === 'domisili' && $request->filled('kecamatan_domisili')) {
                $dataToCreate['kecamatan_domisili'] = $request->kecamatan_domisili;
            }
        }

        $dataToCreate['password'] = Hash::make($request->password);

        // if ($request->hasFile('foto_profil')) {
        //     $fileName = 'profil_' . time() . '.' . $request->file('foto_profil')->getClientOriginalExtension();
        //     $request->file('foto_profil')->storeAs('public/profil', $fileName); // Pastikan direktori ada
        //     $dataToCreate['foto_profil'] = $fileName;
        // }

        $user = User::create($dataToCreate);

        return redirect()->route('admin.akun-pengguna.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
    }



    public function update(Request $request, User $akun_pengguna)
    {
        $user = $akun_pengguna;

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required_if:role,admin', 'nullable', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nisn' => ['required_if:role,siswa', 'nullable', 'string', 'max:10', Rule::unique('users', 'nisn')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'siswa'])],
            'status_pendaftaran' => ['nullable', Rule::in(['belum_diverifikasi', 'menunggu_kelengkapan_data', 'menunggu_verifikasi_berkas', 'berkas_tidak_lengkap', 'berkas_diverifikasi', 'lulus_seleksi', 'tidak_lulus_seleksi', 'mengundurkan_diri', 'daftar_ulang_selesai'])],
            'jalur_pendaftaran' => ['nullable', Rule::in(['domisili', 'prestasi_akademik_lomba', 'prestasi_non_akademik_lomba', 'prestasi_rapor', 'afirmasi_ketm', 'afirmasi_disabilitas', 'mutasi_pindah_tugas', 'mutasi_anak_guru'])],
            'kecamatan_domisili' => ['nullable', 'string', 'max:100', Rule::requiredIf(fn() => $request->input('jalur_pendaftaran') === 'domisili' && $request->input('role') === 'siswa')],
            // 'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            // 'remove_foto_profil' => ['nullable', 'boolean'],
        ]);
        // , [
        //     'foto_profil.image' => 'File harus berupa gambar.',
        //     'foto_profil.mimes' => 'File harus dalam format: jpg, jpeg, atau png.',
        //     'foto_profil.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        // ]

        $dataToUpdate = $request->only(['nama_lengkap', 'role']);

        if ($request->role == 'admin') {
            $dataToUpdate['email'] = $request->email;
            $dataToUpdate['nisn'] = null;
        } elseif ($request->role == 'siswa') {
            $dataToUpdate['nisn'] = $request->nisn;
            $dataToUpdate['email'] = null;
            if ($request->filled('status_pendaftaran')) $dataToUpdate['status_pendaftaran'] = $request->status_pendaftaran;
            if ($request->filled('jalur_pendaftaran')) $dataToUpdate['jalur_pendaftaran'] = $request->jalur_pendaftaran;
            if ($request->input('jalur_pendaftaran') === 'domisili' && $request->filled('kecamatan_domisili')) {
                $dataToUpdate['kecamatan_domisili'] = $request->kecamatan_domisili;
            } elseif ($request->input('jalur_pendaftaran') !== 'domisili') {
                $dataToUpdate['kecamatan_domisili'] = null;
            }
        }

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // if ($request->input('remove_foto_profil') && $user->foto_profil) {
        //     if (Storage::disk('public')->exists('profil/' . $user->foto_profil)) {
        //         Storage::disk('public')->delete('profil/' . $user->foto_profil);
        //     }
        //     $dataToUpdate['foto_profil'] = null;
        // } elseif ($request->hasFile('foto_profil')) {
        //     // Hapus foto lama jika ada
        //     if ($user->foto_profil && Storage::disk('public')->exists('profil/' . $user->foto_profil)) {
        //         Storage::disk('public')->delete('profil/' . $user->foto_profil);
        //     }
        //     // Simpan foto baru
        //     $fileName = 'profil_' . $user->id . '_' . time() . '.' . $request->file('foto_profil')->getClientOriginalExtension();
        //     $request->file('foto_profil')->storeAs('public/profil', $fileName);
        //     $dataToUpdate['foto_profil'] = $fileName;
        // }

        $user->update($dataToUpdate);

        // Refresh sesi pengguna jika akun yang diedit adalah akun yang sedang login
        if (Auth::id() == $user->id) {
            Auth::setUser($user);
        }

        return redirect()->route('admin.akun-pengguna.index')->with('success', 'Data akun pengguna berhasil diperbarui. Silakan refresh halaman untuk melihat perubahan foto profil.');
    }


    public function destroy(User $akun_pengguna)
    {
        if ($akun_pengguna->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // if ($akun_pengguna->foto_profil && Storage::disk('public')->exists('profil/' . $akun_pengguna->foto_profil)) {
        //     Storage::disk('public')->delete('profil/' . $akun_pengguna->foto_profil);
        // }

        try {
            $akun_pengguna->delete();
            return redirect()->route('admin.akun-pengguna.index')->with('success', 'Akun pengguna berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.akun-pengguna.index')->with('error', 'Gagal menghapus akun. Mungkin masih ada data terkait.');
        }
    }
}
