<x-auth-kaiadmin-layout :title="'Registrasi Kepala Sekolah'" :subtitle="'Daftar sebagai Kepala Sekolah Baru'"
    :background-image-url="asset('techedu/img/slider/2.png')" :form-card-class="'col-xl-8 col-lg-9 col-md-10'">

    <h4 class="fw-bold mb-4 text-center text-gray-900">Registrasi Kepala Sekolah</h4>

    {{-- Tampilkan pesan sukses --}}
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close py-0 px-2" style="font-size: 0.75rem;" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
    @endif

    {{-- Tampilkan error umum --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close py-0 px-2" style="font-size: 0.75rem;" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('kepala-sekolah.register.store') }}">
        @csrf

        <div class="row">
            {{-- Data Pribadi --}}
            <div class="col-12">
                <h5 class="text-primary fw-bold mb-3">Data Pribadi</h5>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="nip" class="form-label fw-semibold">{{ __('NIP') }} <span
                            class="text-danger">*</span></label>
                    <input id="nip" type="text" class="form-control @error('nip') is-invalid @enderror" name="nip"
                        value="{{ old('nip') }}" required autofocus placeholder="Masukkan NIP (18 digit)">
                    @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="nama_lengkap" class="form-label fw-semibold">{{ __('Nama Lengkap') }} <span
                            class="text-danger">*</span></label>
                    <input id="nama_lengkap" type="text"
                        class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap"
                        value="{{ old('nama_lengkap') }}" required placeholder="Masukkan nama lengkap">
                    @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="email" class="form-label fw-semibold">{{ __('Email') }} <span
                            class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required placeholder="Masukkan alamat email">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="jenis_kelamin" class="form-label fw-semibold">{{ __('Jenis Kelamin') }} <span
                            class="text-danger">*</span></label>
                    <select id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror"
                        name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="laki-laki" {{ old('jenis_kelamin')=='laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="perempuan" {{ old('jenis_kelamin')=='perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                    @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="tempat_lahir" class="form-label fw-semibold">{{ __('Tempat Lahir') }} <span
                            class="text-danger">*</span></label>
                    <input id="tempat_lahir" type="text"
                        class="form-control @error('tempat_lahir') is-invalid @enderror" name="tempat_lahir"
                        value="{{ old('tempat_lahir') }}" required placeholder="Masukkan tempat lahir">
                    @error('tempat_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="tanggal_lahir" class="form-label fw-semibold">{{ __('Tanggal Lahir') }} <span
                            class="text-danger">*</span></label>
                    <input id="tanggal_lahir" type="date"
                        class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-3">
                    <label for="alamat" class="form-label fw-semibold">{{ __('Alamat Lengkap') }} <span
                            class="text-danger">*</span></label>
                    <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat"
                        rows="3" required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="no_telepon" class="form-label fw-semibold">{{ __('No. Telepon') }} <span
                            class="text-danger">*</span></label>
                    <input id="no_telepon" type="tel" class="form-control @error('no_telepon') is-invalid @enderror"
                        name="no_telepon" value="{{ old('no_telepon') }}" required placeholder="Masukkan nomor telepon">
                    @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="no_whatsapp" class="form-label fw-semibold">{{ __('No. WhatsApp') }}</label>
                    <input id="no_whatsapp" type="tel" class="form-control @error('no_whatsapp') is-invalid @enderror"
                        name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                        placeholder="Masukkan nomor WhatsApp (opsional)">
                    @error('no_whatsapp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Data Pendidikan --}}
            <div class="col-12 mt-4">
                <h5 class="text-primary fw-bold mb-3">Data Pendidikan</h5>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="pendidikan_terakhir" class="form-label fw-semibold">{{ __('Pendidikan Terakhir') }}
                        <span class="text-danger">*</span></label>
                    <select id="pendidikan_terakhir"
                        class="form-control @error('pendidikan_terakhir') is-invalid @enderror"
                        name="pendidikan_terakhir" required>
                        <option value="">Pilih Pendidikan Terakhir</option>
                        <option value="S1" {{ old('pendidikan_terakhir')=='S1' ? 'selected' : '' }}>S1 (Sarjana)
                        </option>
                        <option value="S2" {{ old('pendidikan_terakhir')=='S2' ? 'selected' : '' }}>S2 (Magister)
                        </option>
                        <option value="S3" {{ old('pendidikan_terakhir')=='S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                    </select>
                    @error('pendidikan_terakhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="jurusan" class="form-label fw-semibold">{{ __('Jurusan') }} <span
                            class="text-danger">*</span></label>
                    <input id="jurusan" type="text" class="form-control @error('jurusan') is-invalid @enderror"
                        name="jurusan" value="{{ old('jurusan') }}" required placeholder="Masukkan jurusan">
                    @error('jurusan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="universitas" class="form-label fw-semibold">{{ __('Universitas/Institut') }} <span
                            class="text-danger">*</span></label>
                    <input id="universitas" type="text" class="form-control @error('universitas') is-invalid @enderror"
                        name="universitas" value="{{ old('universitas') }}" required
                        placeholder="Masukkan nama universitas">
                    @error('universitas')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="tahun_lulus" class="form-label fw-semibold">{{ __('Tahun Lulus') }} <span
                            class="text-danger">*</span></label>
                    <input id="tahun_lulus" type="number"
                        class="form-control @error('tahun_lulus') is-invalid @enderror" name="tahun_lulus"
                        value="{{ old('tahun_lulus') }}" required min="1970" max="{{ date('Y') }}"
                        placeholder="Masukkan tahun lulus">
                    @error('tahun_lulus')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Data Kepegawaian --}}
            <div class="col-12 mt-4">
                <h5 class="text-primary fw-bold mb-3">Data Kepegawaian</h5>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="tanggal_mulai_tugas" class="form-label fw-semibold">{{ __('Tanggal Mulai Tugas') }}
                        <span class="text-danger">*</span></label>
                    <input id="tanggal_mulai_tugas" type="date"
                        class="form-control @error('tanggal_mulai_tugas') is-invalid @enderror"
                        name="tanggal_mulai_tugas" value="{{ old('tanggal_mulai_tugas') }}" required>
                    @error('tanggal_mulai_tugas')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="sk_pengangkatan" class="form-label fw-semibold">{{ __('No. SK Pengangkatan') }}</label>
                    <input id="sk_pengangkatan" type="text"
                        class="form-control @error('sk_pengangkatan') is-invalid @enderror" name="sk_pengangkatan"
                        value="{{ old('sk_pengangkatan') }}" placeholder="Masukkan nomor SK (opsional)">
                    @error('sk_pengangkatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="col-12 mt-4">
                <h5 class="text-primary fw-bold mb-3">Keamanan Akun</h5>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="password" class="form-label fw-semibold">{{ __('Password') }} <span
                            class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="new-password"
                        placeholder="Masukkan password (minimal 8 karakter)">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">{{ __('Konfirmasi Password') }}
                        <span class="text-danger">*</span></label>
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                        required autocomplete="new-password" placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg w-100 btn-round fw-bold">
                DAFTAR SEBAGAI KEPALA SEKOLAH
            </button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <p class="text-sm">
            Sudah memiliki akun? <a href="{{ route('kepala-sekolah.login') }}" class="text-primary fw-bold">Login di
                sini</a>
        </p>
    </div>

    <p class="mt-4 text-center text-sm">
        <a href="{{ route('home') }}" class="text-primary">Kembali ke Beranda Situs</a>
    </p>

</x-auth-kaiadmin-layout>
