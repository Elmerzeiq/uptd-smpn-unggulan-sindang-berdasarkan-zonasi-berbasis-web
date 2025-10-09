<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Gunakan LoginRequest yang sudah dimodifikasi
use App\Providers\RouteServiceProvider; // Atau gunakan nama route langsung
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * Path: GET /login (atau path custom Anda)
     * Name: login (atau nama custom Anda)
     */
    public function create(): View
    {
        return view('auth.login'); // View di resources/views/auth/login.blade.php
    }

    /**
     * Handle an incoming authentication request.
     * Path: POST /login (atau path custom Anda)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Method authenticate() dari LoginRequest akan menangani percobaan login
        // dan melempar ValidationException jika gagal.
        $request->authenticate();

        // Jika autentikasi berhasil, regenerate session
        $request->session()->regenerate();

        // Redirect berdasarkan role pengguna yang berhasil login
        $user = Auth::user(); // Mengambil user yang login dengan guard default ('web')
        // Jika admin login dengan guard 'admin', ini akan null
        // dan redirect admin sudah dihandle oleh LoginRequest jika admin login dengan guard admin

        if ($user) { // Jika ada user yang login dengan guard default
            if ($user->role === 'siswa') {
                return redirect()->intended(route('siswa.dashboard'));
            } elseif ($user->role === 'admin' && Auth::guard('web')->check()) {
                // Kasus admin login via form umum menggunakan guard 'web'
                // Mungkin lebih baik admin selalu diarahkan ke form login admin khusus
                Auth::guard('web')->logout(); // Logout dari guard web
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login.form')->with('info', 'Silakan login sebagai admin melalui halaman login administrator.');
            }
        } else if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {
            // Jika LoginRequest mengautentikasi admin dengan guard 'admin'
            return redirect()->intended(route('admin.dashboard'));
        }


        // Fallback jika tidak ada kondisi di atas yang terpenuhi (seharusnya tidak terjadi)
        return redirect()->intended(route('home')); // Ganti dengan route default Anda
    }

    /**
     * Destroy an authenticated session (logout untuk guard 'web').
     * Path: POST /logout (atau path custom Anda)
     * Name: logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout(); // Logout dari guard 'web'

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Arahkan ke beranda setelah logout
    }
}
