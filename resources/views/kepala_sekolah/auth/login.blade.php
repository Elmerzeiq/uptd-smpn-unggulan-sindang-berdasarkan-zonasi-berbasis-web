<x-auth-kaiadmin-layout :title="'Login Kepala Sekolah'" :subtitle="'Akses khusus untuk Kepala Sekolah'"
    :background-image-url="asset('techedu/img/slider/2.png')" :form-card-class="'col-xl-4 col-lg-5 col-md-7 col-sm-9'">

    <h4 class="fw-bold mb-4 text-center text-gray-900">Login Kepala Sekolah</h4>

    {{-- Tampilkan error spesifik login --}}
    @if ($errors->has('email') || $errors->has('password'))
    <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
        {{ $errors->first('email') ?: $errors->first('password') }}
        <button type="button" class="btn-close py-0 px-2" style="font-size: 0.75rem;" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('kepala-sekolah.login.form') }}">
        @csrf

        <div class="form-group mb-3">
            <label for="email" class="form-label fw-semibold" :value="__('Email')">{{ __('Alamat Email Resmi')
                }}</label>
            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                placeholder="Masukkan email kepala sekolah">
            {{-- Error di bawah input sudah dihandle oleh SweetAlert jika general, tapi bisa juga spesifik --}}
        </div>

        <div class="form-group mb-3">
            <label for="password" class="form-label fw-semibold" :value="__('Password')">{{ __('Password') }}</label>
            <input id="password" type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required
                autocomplete="current-password" placeholder="Masukkan password Anda">
        </div>

        <div class="form-group form-check mt-3 mb-4">
            <input id="remember_me_kepala_sekolah" type="checkbox" class="form-check-input" name="remember" {{
                old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember_me_kepala_sekolah">
                {{ __('Ingat saya') }}
            </label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg w-100 btn-round fw-bold">
                LOGIN SEBAGAI KEPALA SEKOLAH
            </button>
        </div>
    </form>

    {{-- Link tambahan untuk kepala sekolah --}}
    <div class="mt-4 text-center">
        <p class="text-sm">
            <a href="{{ route('kepala-sekolah.forgot-password') }}" class="text-primary">Lupa Password?</a>
        </p>
    </div>

    <div class="text-center">
        <p class="text-sm text-muted mb-2">Belum memiliki akun?</p>
        <a href="{{ route('kepala-sekolah.register') }}" class="btn btn-outline-primary btn-sm">Daftar sebagai Kepala
            Sekolah</a>
    </div>

    <hr class="my-4">

    <div class="text-center">
        <p class="text-sm text-muted mb-2">Login sebagai:</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('admin.login.form') }}" class="btn btn-outline-secondary btn-sm">Administrator</a>
        </div>
    </div>

    <p class="mt-4 text-center text-sm">
        <a href="{{ route('home') }}" class="text-primary">Kembali ke Beranda Situs</a>
    </p>

</x-auth-kaiadmin-layout>
