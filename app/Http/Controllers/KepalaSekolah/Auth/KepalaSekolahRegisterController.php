<?php

namespace App\Http\Controllers\KepalaSekolah\Auth;

use App\Http\Controllers\Controller;
use App\Models\KepalaSekolah; // Menggunakan model KepalaSekolah
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class KepalaSekolahRegisterController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in routes file
    }

    public function showRegistrationForm()
    {
        return view('kepala_sekolah.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'string', 'max:18', 'unique:kepala_sekolahs,nip'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:kepala_sekolahs,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_telepon' => ['required', 'string', 'max:15'],
            'no_whatsapp' => ['nullable', 'string', 'max:15'],
            'pendidikan_terakhir' => ['required', 'string', 'max:50'],
            'jurusan' => ['required', 'string', 'max:100'],
            'universitas' => ['required', 'string', 'max:100'],
            'tahun_lulus' => ['required', 'integer', 'min:1970', 'max:' . date('Y')],
            'tanggal_mulai_tugas' => ['required', 'date', 'before_or_equal:today'],
            'sk_pengangkatan' => ['nullable', 'string', 'max:100'],
        ]);

        $kepalaSekolah = KepalaSekolah::create([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'no_whatsapp' => $request->no_whatsapp,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jurusan' => $request->jurusan,
            'universitas' => $request->universitas,
            'tahun_lulus' => $request->tahun_lulus,
            'tanggal_mulai_tugas' => $request->tanggal_mulai_tugas,
            'sk_pengangkatan' => $request->sk_pengangkatan,
            'role' => 'kepala_sekolah',
            'email_verified_at' => now(), // Kepala sekolah yang dibuat manual dianggap terverifikasi
            'status_aktif' => true,
        ]);

        event(new Registered($kepalaSekolah)); // Event jika diperlukan

        // Login otomatis kepala sekolah yang baru terdaftar (opsional)
        // Auth::guard('kepala_sekolah')->login($kepalaSekolah);
        // return redirect(route('kepala-sekolah.dashboard'))->with('success', 'Akun kepala sekolah berhasil dibuat!');

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('kepala-sekolah.login')->with('status', 'Registrasi kepala sekolah berhasil! Silakan login dengan akun yang telah dibuat.');
    }
}
