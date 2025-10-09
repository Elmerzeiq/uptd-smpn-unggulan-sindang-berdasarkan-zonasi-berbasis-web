<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
// Anda tidak perlu App\Models\User di sini jika logika role ada di attempt

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Semua orang boleh mencoba login
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login_identifier' => ['required', 'string'], // Bisa email, nisn
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'], // Untuk checkbox "Ingat Saya"
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginIdentifier = $this->input('login_identifier');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // Prioritaskan percobaan login sebagai admin menggunakan guard 'admin' jika email cocok
        // Ini lebih aman jika Anda memiliki URL login admin terpisah.
        // Jika ini form login umum, Anda mungkin ingin logika ini ada di AdminLoginController saja.
        // Namun, jika ini satu-satunya form login, maka logika ini diperlukan di sini.
        if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('admin')->attempt(['email' => $loginIdentifier, 'password' => $password, 'role' => 'admin'], $remember)) {
                RateLimiter::clear($this->throttleKey());
                return; // Autentikasi sebagai admin berhasil
            }
        }

        // Coba login sebagai siswa (dengan NISN) menggunakan guard 'web' (default)
        if (Auth::guard('web')->attempt(['nisn' => $loginIdentifier, 'password' => $password, 'role' => 'siswa'], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return; // Autentikasi sebagai siswa berhasil
        }

        // JIKA ADMIN JUGA BISA LOGIN DENGAN EMAIL DI FORM INI (setelah gagal sebagai siswa dengan NISN jika loginIdentifier bukan email)
        // DAN MENGGUNAKAN GUARD 'web' (kurang ideal, lebih baik guard 'admin' terpisah)
        // Ini bisa terjadi jika loginIdentifier adalah email, dan percobaan sebagai admin dengan guard 'admin' di atas gagal,
        // atau jika admin mencoba login dengan email di form login siswa.
        if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('web')->attempt(['email' => $loginIdentifier, 'password' => $password, 'role' => 'admin'], $remember)) {
                // Admin berhasil login via guard 'web' (JIKA INI DIPERBOLEHKAN)
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }


        // Jika semua percobaan gagal
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login_identifier' => trans('auth.failed'), // Pesan error umum
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) { // 5 percobaan
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // Menggunakan kombinasi login_identifier dan IP untuk throttling
        return Str::transliterate(Str::lower($this->input('login_identifier')) . '|' . $this->ip());
    }
}
