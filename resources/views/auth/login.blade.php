{{-- resources/views/auth/login.blade.php --}}
<x-auth-kaiadmin-layout :title="config('app.name').' | Login Akun'"
    :subtitle="'Silakan login untuk melanjutkan ke dashboard Anda.'"
    :background-image-url="asset('techedu/img/slider/1.png')" {{-- Ganti path background --}}
    :form-card-class="'col-xl-4 col-lg-5 col-md-7 col-sm-9'">

    <h4 class="fw-bold mb-4 text-center text-gray-900">Login Akun</h4>

    {{-- Notifikasi session 'status' dari Breeze (misal setelah reset password) akan dihandle SweetAlert di layout --}}

    <form method="POST" action="{{ route('login') }}"> {{-- Pastikan nama route ini benar (login.proses atau
        login saja) --}}
        @csrf

        <div class="form-group mb-3">
            <label for="login_identifier" class="form-label fw-semibold">{{ __('NISN') }}</label>
            <input id="login_identifier" type="text"
                class="form-control form-control-lg @error('login_identifier') is-invalid @enderror @error('email') is-invalid @enderror @error('nisn') is-invalid @enderror"
                name="login_identifier" value="{{ old('login_identifier') }}" required autofocus autocomplete="username"
                placeholder="Masukkan NISN">
            @error('login_identifier') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror {{-- Untuk error 'auth.failed'
            --}}
            @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label fw-semibold mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-primary hover:text-primary-dark fw-medium">
                    {{ __('Lupa password?') }}
                </a>
                @endif
            </div>
            <input id="password" type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required
                autocomplete="current-password" placeholder="Masukkan Password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group form-check mt-3 mb-4">
            <input id="remember_me_siswa" type="checkbox" class="form-check-input" name="remember" {{ old('remember')
                ? 'checked' : '' }}>
            <label class="form-check-label" for="remember_me_siswa">
                {{ __('Ingat saya') }}
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg w-100 btn-round fw-bold">
                LOGIN
            </button>
        </div>
    </form>

    @if (Route::has('register'))
    <p class="mt-4 text-center text-muted">
        Belum punya akun SPMB? <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar Sekarang!</a>
    </p>
    @endif
    <p class="mt-2 text-center text-sm">
        <a href="{{ route('home') }}" class="text-primary">Kembali ke Beranda Situs</a>
        @if(Route::has('admin.login.form')) {{-- Jika ada login admin terpisah --}}
        | <a href="{{ route('admin.login.form') }}" class="text-primary">Login sebagai Admin?</a>
        @endif
    </p>
    
    <p class="mt-2 text-center text-sm">
    @if(Route::has('kepala-sekolah.login'))
    <a href="{{ route('kepala-sekolah.login') }}" class="text-primary">Login sebagai Kepala Sekolah?</a>
    @endif
    </p>
</x-auth-kaiadmin-layout>
