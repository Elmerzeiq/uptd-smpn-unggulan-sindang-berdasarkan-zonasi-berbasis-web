<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminLoginController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        // if (Auth::guard('admin')->check()) {
        //     // Ini seharusnya tidak terjadi jika middleware guest:admin bekerja
        //     dd('ERROR: Admin masih terdeteksi login di showLoginForm padahal ada middleware guest:admin. Guard: admin');
        // }
        // if (Auth::guard('web')->check()) {
        //     // Ini untuk melihat apakah ada sesi dari guard lain yang aktif
        //     dd('WARNING: Guard web (kemungkinan siswa) aktif saat mencoba akses login admin. Guard: web, User ID: ' . Auth::guard('web')->id());
        // }
        // dd('Menampilkan form login admin...');
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'], // Tambahkan jika ada checkbox "remember me"
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin' // Filter penting jika model User dipakai bersama siswa
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // Fokus pada guard admin
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.form'); // Arahkan kembali ke form login admin
    }
}
