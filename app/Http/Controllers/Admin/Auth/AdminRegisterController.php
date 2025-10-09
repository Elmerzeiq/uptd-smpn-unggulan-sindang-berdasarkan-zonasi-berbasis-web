<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Menggunakan model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class AdminRegisterController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in routes file
    }

    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Pastikan email unik di tabel users
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'email_verified_at' => now(), // Admin yang dibuat manual dianggap terverifikasi
            // NISN dan field siswa lainnya bisa diisi null atau tidak disertakan
        ]);

        event(new Registered($user)); // Event jika diperlukan

        // Login otomatis admin yang baru terdaftar (opsional)
        // Auth::guard('admin')->login($user);

        // return redirect(route('admin.dashboard'))->with('success', 'Akun admin berhasil dibuat!');
        // Atau redirect ke halaman login admin dengan pesan sukses
        return redirect()->route('admin.login.form')->with('status', 'Registrasi admin berhasil! Silakan login.');
    }
}
