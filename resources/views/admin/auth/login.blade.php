<x-auth-kaiadmin-layout :title="'Login Administrator Panel'" :subtitle="'Akses khusus untuk Pengelola Sistem Sekolah'"
    :background-image-url="asset('techedu/img/slider/2.png')"
    :form-card-class="'col-xl-4 col-lg-5 col-md-7 col-sm-9'">

    <h4 class="fw-bold mb-4 text-center text-gray-900">Login Administrator</h4>

    {{-- Tampilkan error spesifik login --}}
    @if ($errors->has('email') || $errors->has('password'))
    <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
        {{ $errors->first('email') ?: $errors->first('password') }}
        <button type="button" class="btn-close py-0 px-2" style="font-size: 0.75rem;" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.login.form') }}">
        @csrf

        <div class="form-group mb-3">
            <label for="email" class="form-label fw-semibold":value="__('Email')">{{ __('Alamat Email') }}</label>
            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                placeholder="Masukkan email admin Anda">
            {{-- Error di bawah input sudah dihandle oleh SweetAlert jika general, tapi bisa juga spesifik --}}
        </div>

        <div class="form-group mb-3">
            <label for="password" class="form-label fw-semibold" :value="__('Password')">{{ __('Password') }}</label>
            <input id="password" type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required
                autocomplete="current-password" placeholder="Masukkan password Anda">
        </div>

        <div class="form-group form-check mt-3 mb-4">
            <input id="remember_me_admin" type="checkbox" class="form-check-input" name="remember" {{ old('remember')
                ? 'checked' : '' }}>
            <label class="form-check-label" for="remember_me_admin">
                {{ __('Ingat saya') }}
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg w-100 btn-round fw-bold">
                LOGIN SEBAGAI ADMIN
            </button>
        </div>
    </form>
    <p class="mt-4 text-center text-sm">
        <a href="{{ route('home') }}" class="text-primary">Kembali ke Beranda Situs</a>
    </p>
    <p class="mt-4 text-center text-sm">
        <a href="{{ route('admin.register.form') }}" class="text-primary">Registrasi Akun Admin</a>
    </p>
</x-auth-kaiadmin-layout>
