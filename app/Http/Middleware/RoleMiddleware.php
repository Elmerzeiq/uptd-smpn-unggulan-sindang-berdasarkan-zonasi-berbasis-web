<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        foreach ($roles as $role) {
            if ($user->role == $role) {
                return $next($request);
            }
        }

        // Jika tidak ada role yang cocok, redirect berdasarkan role user
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses tidak diizinkan ke halaman tersebut.');
        }
        // Nanti tambahkan untuk siswa
        elseif ($user->role == 'siswa') {
            return redirect()->route('siswa.dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        elseif ($user->role == 'kepala_sekolah') {
            return redirect()->route('kepala-sekolah.dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        Auth::logout(); // Logout jika role tidak terdefinisi untuk redirect
        return redirect('/login')->with('error', 'Role tidak dikenal atau akses tidak diizinkan.');
    }

    private function redirectBasedOnRole(string $userRole): \Illuminate\Http\RedirectResponse
    {
        $message = 'Anda tidak memiliki akses ke halaman tersebut.';

        switch ($userRole) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('error', $message);
            case 'kepala_sekolah':
                return redirect()->route('kepala-sekolah.dashboard')->with('error', $message);
            case 'siswa':
                return redirect()->route('siswa.dashboard')->with('error', $message);
            default:
                return redirect()->route('login')->with('error', 'Role tidak valid. Silakan hubungi administrator.');
        }
    }
}
