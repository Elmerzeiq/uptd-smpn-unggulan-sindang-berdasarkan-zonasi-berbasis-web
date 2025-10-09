<?php

namespace App\Http\Controllers\KepalaSekolah\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class KepalaSekolahLoginController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('guest:kepala_sekolah')->except('logout');
    }

    public function showLoginForm()
    {
        // Debug jika diperlukan
        // if (Auth::guard('kepala_sekolah')->check()) {
        //     dd('ERROR: Kepala Sekolah masih terdeteksi login di showLoginForm padahal ada middleware guest:kepala_sekolah. Guard: kepala_sekolah');
        // }
        // if (Auth::guard('web')->check()) {
        //     dd('WARNING: Guard web (kemungkinan siswa) aktif saat mencoba akses login kepala sekolah. Guard: web, User ID: ' . Auth::guard('web')->id());
        // }
        // dd('Menampilkan form login kepala sekolah...');

        return view('kepala_sekolah.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'], // Untuk checkbox "ingat saya"
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'kepala_sekolah' // Filter penting untuk role kepala sekolah
        ];

        if (Auth::guard('kepala_sekolah')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect ke dashboard kepala sekolah
            return redirect()->intended(route('kepala-sekolah.dashboard'));
        }

        // Jika login gagal
        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan tidak valid.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('kepala_sekolah')->logout(); // Fokus pada guard kepala_sekolah
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect kembali ke form login kepala sekolah
        return redirect()->route('kepala-sekolah.login')->with('success', 'Anda telah berhasil logout.');
    }

    public function showForgotPasswordForm()
    {
        return view('kepala-sekolah.auth.forgot-password');
    }
}
