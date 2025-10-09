<x-auth-kaiadmin-layout :title="'Registrasi Akun Administrator'"
    :subtitle="'Pembuatan Akun Baru untuk Administrator Sistem'"
    :background-image-url="asset('techedu/img/slider/2.png')" {{-- Ganti path --}}
    :form-card-class="'col-xl-5 col-lg-6 col-md-8 col-sm-10'">

    <h4 class="fw-bold mb-4 text-center text-gray-900">Registrasi Akun Admin</h4>

    <form method="POST" action="{{ route('admin.register.form') }}">
        @csrf

        <div class="form-group mb-3">
            <label for="nama_lengkap" class="form-label fw-semibold">{{ __('Nama Lengkap') }} <span
                    class="text-danger">*</span></label>
            <input id="nama_lengkap" type="text"
                class="form-control form-control-lg @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap"
                value="{{ old('nama_lengkap') }}" required autofocus autocomplete="name"
                placeholder="Nama lengkap admin">
            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email_admin_reg" class="form-label fw-semibold">{{ __('Alamat Email') }} <span
                    class="text-danger">*</span></label>
            <input id="email_admin_reg" type="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" placeholder="Email admin">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password_admin_reg" class="form-label fw-semibold">{{ __('Password') }} <span
                    class="text-danger">*</span></label>
            <input id="password_admin_reg" type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required
                autocomplete="new-password" placeholder="Buat password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group mb-4">
            <label for="password_confirmation_admin_reg" class="form-label fw-semibold">{{ __('Konfirmasi Password') }}
                <span class="text-danger">*</span></label>
            <input id="password_confirmation_admin_reg" type="password" class="form-control form-control-lg"
                name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg w-100 btn-round fw-bold">
                REGISTRASI ADMIN
            </button>
        </div>
    </form>
    <p class="mt-4 text-center text-sm">
        Sudah punya akun admin? <a href="{{ route('admin.login.form') }}" class="text-primary fw-bold">Login di sini</a>
    </p>
</x-auth-kaiadmin-layout>
