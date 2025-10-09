<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
// use App\Providers\RouteServiceProvider; // Kita akan gunakan nama route langsung

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Jika tidak ada guard spesifik yang diberikan (misalnya dari middleware 'guest' saja),
        // maka defaultnya adalah guard 'web'.
        $guards = empty($guards) ? ['web'] : $guards; // Default ke 'web' jika tidak ada guard spesifik

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) { // Cek APAKAH USER SUDAH LOGIN dengan guard ini
                // dd('RedirectIfAuthenticated - Guard \'' . $guard . '\' is CHECKED (user logged in)');

                // Jika guard yang dicek adalah 'admin' (misalnya saat akses /admin/login dengan middleware 'guest:admin')
                // DAN user yang login dengan guard 'admin' memang ada
                if ($guard === 'admin') {
                    // dd('Redirecting to admin.dashboard because guard admin is checked');
                    return redirect()->route('admin.dashboard'); // Redirect ke dashboard admin
                }

                if ($guard === 'kepala_sekolah') {
                    // dd('Redirecting to kepala_sekolah.dashboard because guard kepala_sekolah is checked');
                    return redirect()->route('kepala-sekolah.dashboard'); // Redirect ke dashboard kepala sekolah
                }

                // Jika guard yang dicek adalah 'web' (default)
                // DAN user yang login dengan guard 'web' ada DAN role-nya adalah 'siswa'
                if ($guard === 'web') {
                    $user = Auth::guard('web')->user();
                    if ($user && $user->role === 'siswa') {
                        // dd('Redirecting to siswa.dashboard because guard web is checked and user is siswa');
                        return redirect()->route('siswa.dashboard'); // Redirect ke dashboard siswa
                    }
                    // Jika user login dengan guard 'web' tapi bukan siswa (misalnya admin yang salah login via form siswa)
                    // atau tidak ada role yang cocok, redirect ke home.
                    // dd('Guard web is checked, but user is not siswa or role unknown. Redirecting to home.');
                    return redirect(route('home'));
                }
            }
            // else {
            //     dd('RedirectIfAuthenticated - Guard \'' . $guard . '\' is NOT CHECKED (user not logged in with this guard)');
            // }
        }

        // dd('RedirectIfAuthenticated - No authenticated guards matched, proceeding with request to: ' . $request->path());
        return $next($request); // Jika belum login dengan guard yang relevan, lanjutkan request (tampilkan form login)
    }
}
