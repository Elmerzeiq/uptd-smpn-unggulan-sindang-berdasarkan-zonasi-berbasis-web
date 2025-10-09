<x-auth-kaiadmin-layout :title="'Registrasi Akun SPMB - ' . config('app.name')"
    :subtitle="'Lengkapi formulir berikut untuk membuat akun pendaftaran siswa baru.'"
    :background-image-url="asset('techedu/img/slider/1.png')"
    :form-card-class="'col-xl-7 col-lg-8 col-md-10 col-sm-12'">

    @if (isset($pendaftaranAkunDibuka) && !$pendaftaranAkunDibuka)
    <div class="alert alert-warning text-center shadow-sm">
        <h4 class="alert-heading"><i class="fas fa-calendar-times me-2"></i> Pendaftaran Ditutup</h4>
        <p class="mb-2">Mohon maaf, periode pendaftaran akun SPMB saat ini belum dibuka atau sudah ditutup.</p>
        <p>Silakan periksa jadwal pendaftaran pada halaman informasi SPMB kami.</p>
        <hr class="my-3">
        <a href="{{ route('home') }}" class="btn btn-primary btn-sm btn-round">
            <i class="fas fa-home me-1"></i> Kembali ke Beranda
        </a>
    </div>
    @else
    <form method="POST" action="{{ route('register') }}" id="registrationForm">
        @csrf
        <h5 class="text-primary mb-3 fw-bold"><i class="fas fa-user-plus me-2"></i>Data Akun Pendaftaran</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap (Sesuai Akta) <span
                            class="text-danger">*</span></label>
                    <input id="nama_lengkap" type="text"
                        class="form-control form-control-lg @error('nama_lengkap') is-invalid @enderror"
                        name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autofocus autocomplete="name"
                        placeholder="Nama lengkap Anda">
                    @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="nisn" class="form-label fw-semibold">NISN (10 Digit) <span
                            class="text-danger">*</span></label>
                    <input id="nisn" type="text"
                        class="form-control form-control-lg @error('nisn') is-invalid @enderror" name="nisn"
                        value="{{ old('nisn') }}" required autocomplete="username" placeholder="NISN Anda"
                        maxlength="10" pattern="\d{10}" title="NISN harus terdiri dari 10 digit angka.">
                    @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="jalur_pendaftaran" class="form-label fw-semibold">Pilih Jalur Pendaftaran <span
                    class="text-danger">*</span></label>
            <select id="jalur_pendaftaran" name="jalur_pendaftaran"
                class="form-select form-select-lg @error('jalur_pendaftaran') is-invalid @enderror" required>
                {{-- <option value="">-- Pilih Jalur --</option> --}}
                @if(isset($jalurPendaftaranOptions) && is_array($jalurPendaftaranOptions))
                @foreach($jalurPendaftaranOptions as $value => $label)
                <option value="{{ $value }}" {{ old('jalur_pendaftaran')==$value ? 'selected' : '' }}>{{ $label }}
                </option>
                @endforeach
                @else
                <optgroup label="Zonasi">
                    <option value="domisili" {{ old('jalur_pendaftaran')=='domisili' ? 'selected' : '' }}>Domisili
                    </option>
                </optgroup>
                {{-- <optgroup label="Prestasi">
                    <option value="prestasi_akademik_lomba" {{ old('jalur_pendaftaran')=='prestasi_akademik_lomba'
                        ? 'selected' : '' }}>Prestasi Akademik (Lomba)</option>
                    <option value="prestasi_non_akademik_lomba" {{
                        old('jalur_pendaftaran')=='prestasi_non_akademik_lomba' ? 'selected' : '' }}>Prestasi
                        Non-Akademik (Lomba)</option>
                    <option value="prestasi_rapor" {{ old('jalur_pendaftaran')=='prestasi_rapor' ? 'selected' : '' }}>
                        Prestasi Nilai Rapor</option>
                </optgroup>
                <optgroup label="Afirmasi">
                    <option value="afirmasi_ketm" {{ old('jalur_pendaftaran')=='afirmasi_ketm' ? 'selected' : '' }}>
                        Afirmasi KETM</option>
                    <option value="afirmasi_disabilitas" {{ old('jalur_pendaftaran')=='afirmasi_disabilitas'
                        ? 'selected' : '' }}>Afirmasi Disabilitas</option>
                </optgroup>
                <optgroup label="Mutasi">
                    <option value="mutasi_pindah_tugas" {{ old('jalur_pendaftaran')=='mutasi_pindah_tugas' ? 'selected'
                        : '' }}>Mutasi Pindah Tugas Orang Tua/Wali</option>
                    <option value="mutasi_anak_guru" {{ old('jalur_pendaftaran')=='mutasi_anak_guru' ? 'selected' : ''
                        }}>Mutasi Anak Guru</option>
                </optgroup> --}}
                @endif
            </select>
            @error('jalur_pendaftaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div id="domisili_fields_group" class="p-3 border rounded bg-light mb-3"
            style="display: {{ old('jalur_pendaftaran') == 'domisili' ? 'block' : 'none' }};">
            <h6 class="text-primary fw-bold mb-3"><i class="fas fa-map-marker-alt me-2"></i>Data Domisili (Untuk Jalur
                Zonasi)</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="kecamatan_domisili_input" class="form-label fw-semibold">Kecamatan Domisili (Sesuai
                            KK) <span id="kecamatan_required_star" class="text-danger"
                                style="display: none;">*</span></label>
                        <input type="text" id="kecamatan_domisili_input" name="kecamatan_domisili_input"
                            class="form-control form-control-lg @error('kecamatan_domisili_input') is-invalid @enderror"
                            value="{{ old('kecamatan_domisili_input') }}" placeholder="Ketik nama kecamatan Anda">
                        <small class="form-text text-muted">
                            Contoh: {{ isset($kecamatanZonasiConfig) && is_array($kecamatanZonasiConfig) ? implode(', ',
                            $kecamatanZonasiConfig) : 'Indramayu,dll' }}. Pastikan penulisan
                            benar.
                        </small>
                        @error('kecamatan_domisili_input') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="desa_kelurahan_domisili_input" class="form-label fw-semibold">Desa/Kelurahan
                            Domisili (Sesuai KK) <span id="desa_kelurahan_required_star" class="text-danger"
                                style="display: none;">*</span></label>
                        <input type="text" id="desa_kelurahan_domisili_input" name="desa_kelurahan_domisili_input"
                            class="form-control form-control-lg @error('desa_kelurahan_domisili_input') is-invalid @enderror"
                            value="{{ old('desa_kelurahan_domisili_input') }}"
                            placeholder="Ketik nama desa/kelurahan Anda">
                        <small class="form-text text-muted">
                            Contoh: {{ isset($desaKelurahanZonasiConfig) && is_array($desaKelurahanZonasiConfig) ?
                            implode(', ', array_slice($desaKelurahanZonasiConfig, 0, 5)) : 'Sindang, dll' }}... (Lihat daftar lengkap di informasi SPMB).
                        </small>
                        @error('desa_kelurahan_domisili_input') <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label fw-semibold">Titik Koordinat Tempat Tinggal <span id="koordinat_required_star"
                        class="text-danger" style="display: none;">*</span></label>
                <div id="mapWrapper" class="w-100 overflow-hidden rounded shadow-sm mb-2 border" style="height: 300px;">
                    <div id="mapRegistrasi" style="height: 100%; width: 100%; cursor: pointer;"></div>
                </div>
                <button type="button" id="btnGunakanLokasi" class="btn btn-outline-primary btn-sm mb-2">
                    <i class="fas fa-map-marker-alt me-1"></i> Gunakan Lokasi Saya
                </button>
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" id="koordinat_lat" name="koordinat_lat"
                            class="form-control form-control-sm @error('koordinat_lat') is-invalid @enderror"
                            value="{{ old('koordinat_lat') }}" placeholder="Latitude (Otomatis Terisi)" readonly>
                        @error('koordinat_lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="koordinat_lng" name="koordinat_lng"
                            class="form-control form-control-sm @error('koordinat_lng') is-invalid @enderror"
                            value="{{ old('koordinat_lng') }}" placeholder="Longitude (Otomatis Terisi)" readonly>
                        @error('koordinat_lng') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <small class="form-text text-muted">Klik pada peta untuk menentukan lokasi rumah Anda, atau geser marker
                    yang muncul. Pastikan titik koordinat akurat.</small>
            </div>
        </div>

        <h5 class="text-primary mt-4 mb-3 fw-bold"><i class="fas fa-lock me-2"></i>Buat Password Akun</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="password" class="form-label fw-semibold">Password <span
                            class="text-danger">*</span></label>
                    <input id="password" type="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror" name="password"
                        required autocomplete="new-password" placeholder="Minimal 8 karakter">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password <span
                            class="text-danger">*</span></label>
                    <input id="password_confirmation" type="password" class="form-control form-control-lg"
                        name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg w-100 btn-round fw-bold">
                <i class="fas fa-user-plus me-2"></i> REGISTRASI AKUN SPMB
            </button>
        </div>
    </form>
    <p class="mt-4 text-center">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold">Login di sini</a>
    </p>
    <p class="mt-2 text-center">
        <a href="{{ route('home') }}" class="text-primary"><i class="fas fa-home me-1"></i> Kembali ke Beranda</a>
    </p>
    @endif

    <x-slot name="styles">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
        <style>
            #mapWrapper {
                position: relative;
            }

            #mapRegistrasi {
                height: 100%;
                width: 100%;
            }
        </style>
    </x-slot>

    <x-slot name="scripts">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // SweetAlert untuk pesan sukses
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                @endif

                // SweetAlert untuk pesan error
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK',
                    });
                @endif

                // SweetAlert untuk pesan validasi
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonText: 'OK',
                    });
                @endif

                const jalurPendaftaranSelect = document.getElementById('jalur_pendaftaran');
                const domisiliFieldsGroup = document.getElementById('domisili_fields_group');
                const kecamatanInput = document.getElementById('kecamatan_domisili_input');
                const desaKelurahanInput = document.getElementById('desa_kelurahan_domisili_input');
                const latInput = document.getElementById('koordinat_lat');
                const lngInput = document.getElementById('koordinat_lng');
                const kecamatanRequiredStar = document.getElementById('kecamatan_required_star');
                const desaKelurahanRequiredStar = document.getElementById('desa_kelurahan_required_star');
                const koordinatRequiredStar = document.getElementById('koordinat_required_star');
                const btnGunakanLokasi = document.getElementById('btnGunakanLokasi');

                let mapRegistrasi = null;
                let markerRegistrasi = null;

                const sekolahLat = parseFloat("{{ config('ppdb.koordinat_sekolah.lat', -6.3390) }}");
                const sekolahLng = parseFloat("{{ config('ppdb.koordinat_sekolah.lng', 108.3225) }}");

                function initMap() {
                    mapRegistrasi = L.map('mapRegistrasi', {
                        scrollWheelZoom: false
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(mapRegistrasi);

                    let lat = sekolahLat;
                    let lng = sekolahLng;
                    let zoom = 13;

                    if (latInput.value && lngInput.value) {
                        lat = parseFloat(latInput.value);
                        lng = parseFloat(lngInput.value);
                        zoom = 17;
                    }

                    mapRegistrasi.setView([lat, lng], zoom);

                    markerRegistrasi = L.marker([lat, lng], { draggable: true }).addTo(mapRegistrasi)
                        .bindPopup("Geser marker ke rumah Anda atau klik peta.").openPopup();

                    markerRegistrasi.on('dragend', function (event) {
                        const pos = event.target.getLatLng();
                        latInput.value = pos.lat.toFixed(6);
                        lngInput.value = pos.lng.toFixed(6);
                    });

                    mapRegistrasi.on('click', function (e) {
                        markerRegistrasi.setLatLng(e.latlng);
                        latInput.value = e.latlng.lat.toFixed(6);
                        lngInput.value = e.latlng.lng.toFixed(6);
                    });

                    setTimeout(() => mapRegistrasi.invalidateSize(), 200);
                }

                function destroyMap() {
                    if (mapRegistrasi) {
                        mapRegistrasi.remove();
                        mapRegistrasi = null;
                        markerRegistrasi = null;
                    }
                }

                function toggleDomisili() {
                    const isDomisili = jalurPendaftaranSelect.value === 'domisili';
                    domisiliFieldsGroup.style.display = isDomisili ? 'block' : 'none';
                    kecamatanInput.required = isDomisili;
                    desaKelurahanInput.required = isDomisili;
                    latInput.required = isDomisili;
                    lngInput.required = isDomisili;
                    kecamatanRequiredStar.style.display = isDomisili ? 'inline' : 'none';
                    desaKelurahanRequiredStar.style.display = isDomisili ? 'inline' : 'none';
                    koordinatRequiredStar.style.display = isDomisili ? 'inline' : 'none';

                    if (isDomisili && !mapRegistrasi) {
                        initMap();
                    } else if (!isDomisili) {
                        destroyMap();
                    }
                }

                function getCurrentLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function (position) {
                                const userLat = position.coords.latitude;
                                const userLng = position.coords.longitude;

                                latInput.value = userLat.toFixed(6);
                                lngInput.value = userLng.toFixed(6);

                                if (markerRegistrasi) {
                                    markerRegistrasi.setLatLng([userLat, userLng]).openPopup();
                                    mapRegistrasi.setView([userLat, userLng], 17);
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Peta Belum Dimuat',
                                        text: 'Pilih jalur domisili terlebih dahulu.',
                                        confirmButtonText: 'OK',
                                    });
                                }
                            },
                            function (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Mendeteksi Lokasi',
                                    text: error.message,
                                    confirmButtonText: 'OK',
                                });
                            }
                        );
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Geolocation Tidak Didukung',
                            text: 'Browser Anda tidak mendukung Geolocation.',
                            confirmButtonText: 'OK',
                        });
                    }
                }

                jalurPendaftaranSelect.addEventListener('change', toggleDomisili);
                if (btnGunakanLokasi) {
                    btnGunakanLokasi.addEventListener('click', getCurrentLocation);
                }
                toggleDomisili();
            });
        </script>
    </x-slot>
</x-auth-kaiadmin-layout>
