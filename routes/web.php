<?php


use App\Models\Ekskul;
use App\Models\DaftarUlang;
use Termwind\Components\Hr;
use App\Models\JadwalDaftarUlang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\EkskulController;
use App\Http\Controllers\Siswa\BerkasController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LaporanController;
// use App\Http\Controllers\JadwalDaftarUlangController;
use App\Http\Controllers\Admin\SeleksiController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\JadwalPpdbController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\AdminBerkasController;
use App\Http\Controllers\Admin\DaftarUlangController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AkunPenggunaController;


use App\Http\Controllers\Admin\GuruDanStaffController;
use App\Http\Controllers\Admin\DataPendaftarController;
use App\Http\Controllers\Admin\ProfilSekolahController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PetunjukTeknisController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Admin\AlurPendaftaranController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\PengumumanHasilController;
use App\Http\Controllers\Siswa\SiswaPengumumanController;
use App\Http\Controllers\Siswa\DaftarUlangSiswaController;
use App\Http\Controllers\Siswa\KartuPendaftaranController;
use App\Http\Controllers\Admin\JadwalDaftarUlangController;
use App\Http\Controllers\Admin\JadwalPendaftaranController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\DokumenPersyaratanController;
use App\Http\Controllers\Admin\AdminKartuPendaftaranController;
use App\Http\Controllers\KepalaSekolah\KepalaSekolahController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\EkskulController as AdminEkskulController;
use App\Http\Controllers\KepalaSekolah\Auth\KepalaSekolahLoginController;
use App\Http\Controllers\Siswa\BerkasController as SiswaBerkasController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Siswa\BiodataController as SiswaBiodataController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\KepalaSekolah\Auth\KepalaSekolahRegisterController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\GuruDanStaffController as AdminGuruDanStaffController;
use App\Http\Controllers\Admin\ProfilSekolahController as AdminProfilSekolahController;
use App\Http\Middleware\PpdbActiveMiddleware; // Jika belum di-alias di bootstrap/app.php
use App\Http\Controllers\Siswa\PengumumanHasilController as SiswaPengumumanHasilController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');




Route::get('/profil/visi-misi', [HomeController::class, 'profilVisiMisi'])->name('profil.visi_misi');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
// Public Routes (untuk frontend)
Route::get('/tentang-kami', [HomeController::class, 'tentangKami'])->name('tentang-kami');
Route::get('/sambutan', [HomeController::class, 'sambutan'])->name('sambutan');
// Route::get('/kepala-sekolah', [HomeController::class, 'sambutan'])->name('kepala-sekolah');
Route::get('/sejarah', [HomeController::class, 'sejarah'])->name('sejarah');
Route::get('/kurikulum', [HomeController::class, 'kurikulum'])->name('kurikulum');
Route::get('/fasilitas', [HomeController::class, 'fasilitas'])->name('fasilitas');
Route::get('/guru', [HomeController::class, 'Guru'])->name('guru_staff');
Route::get('/prestasi', [HomeController::class,'prestasiSekolah'])->name('prestasi');
Route::get('/spmb', [HomeController::class, 'spmb'])->name('spmb');
Route::get('/ekskul', function () {
    $ekskuls = Ekskul::latest()->get();
    return view('home.ekskul.index', compact('ekskuls'));
})->name('ekskul.index');

Route::get('/ekskul/{id}', [EkskulController::class, 'show'])->name('ekskul.show');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
// Public News routes
Route::get('/berita', [HomeController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [HomeController::class, 'beritaShow'])->name('berita.show');




// Route untuk API jika diperlukan:
Route::prefix('api')->group(function () {
    Route::get('/guru', [HomeController::class, 'apiGuru'])->name('api.guru');
    Route::get('/staff', [HomeController::class, 'apiStaff'])->name('api.staff');
    Route::get('/kepala-sekolah', [HomeController::class, 'apiKepalaSekolah'])->name('api.kepala-sekolah');
});







// Kepala Sekolah Authentication Routes (Guest only - tidak perlu auth middleware)
Route::prefix('kepala-sekolah')->name('kepala-sekolah.')->group(function () {

    // Guest routes (untuk yang belum login)
    Route::middleware('guest:kepala_sekolah')->group(function () {
        // Login routes
        Route::get('/login', [KepalaSekolahLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [KepalaSekolahLoginController::class, 'login'])->name('login.form');

        // Registration routes
        Route::get('/register', [KepalaSekolahRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [KepalaSekolahRegisterController::class, 'register'])->name('register.store');

        // Forgot password
        Route::get('/forgot-password', [KepalaSekolahLoginController::class, 'showForgotPasswordForm'])->name('forgot-password');
    });

    // Protected routes (untuk yang sudah login sebagai kepala sekolah)
    Route::middleware(['auth:kepala_sekolah', 'role:kepala_sekolah'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');
        Route::get('/get-statistics', [KepalaSekolahController::class, 'getStatistics'])->name('get_statistics');

        // Logout
        Route::post('/logout', [KepalaSekolahLoginController::class, 'logout'])->name('logout');

        // Profile routes
        // Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
        // Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

        // Laporan Routes
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/semua-pendaftar', [KepalaSekolahController::class, 'laporanSemuaPendaftar'])->name('semua_pendaftar');
            Route::get('/siswa-diterima', [KepalaSekolahController::class, 'laporanSiswaDiterima'])->name('siswa_diterima');
            Route::get('/siswa-tidak-lolos', [KepalaSekolahController::class, 'laporanSiswaTidakLolos'])->name('siswa_tidak_lolos');
            Route::get('/berkas', [KepalaSekolahController::class, 'laporanBerkas'])->name('berkas');
            Route::get('/daftar-ulang', [KepalaSekolahController::class, 'laporanDaftarUlang'])->name('daftar_ulang');

            // Export routes
            Route::post('/export', [KepalaSekolahController::class, 'exportLaporan'])->name('export');
        });

        // Komentar/Komunikasi Routes
        Route::prefix('komentar')->name('komentar.')->group(function () {
            Route::get('/', [KepalaSekolahController::class, 'komentarIndex'])->name('index');
            Route::post('/', [KepalaSekolahController::class, 'komentarStore'])->name('store');
            Route::post('/{comment}/reply', [KepalaSekolahController::class, 'komentarReply'])->name('reply');
            Route::patch('/{comment}/complete', [KepalaSekolahController::class, 'komentarComplete'])->name('complete');
        });

        // Management routes (jika diperlukan)
        // Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        // Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    });
});







// Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
// Route::get('/logout', [AdminDashboardController::class, 'logout'])->name('logout');

// Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');
// Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.attempt');

// Route::get('/force-logout-all', function (Request $request) {
//     Auth::guard('web')->logout();
//     Auth::guard('admin')->logout();
//     $request->session()->invalidate();
//     $request->session()->regenerateToken();
//     return redirect('/')->with('status', 'Semua sesi telah dilogout.');
// });

// Route::get('/super-logout', function (Request $request) {
//     Auth::guard('web')->logout();    // Logout guard default
//     Auth::guard('admin')->logout();  // Logout guard admin
//     // Anda bisa menambahkan guard lain jika ada

//     $request->session()->invalidate();    // Membuat semua data session tidak valid
//     $request->session()->regenerateToken(); // Membuat token CSRF baru

//     // Hapus cookie sesi secara manual (cara lebih paksa)
//     $sessionCookieName = config('session.cookie');
//     if ($sessionCookieName) {
//         Cookie::queue(Cookie::forget($sessionCookieName));
//     }

//     return redirect('/')->with('status', 'Logout dari semua guard berhasil. Sesi dan cookie terkait telah dihapus.');
// })->name('super.logout');





// Rute untuk Siswa
Route::middleware(['auth'])->prefix('siswa/daftar-ulang')->name('siswa.daftar-ulang.')->group(function () {
    Route::get('/', [DaftarUlangSiswaController::class, 'index'])->name('index');
    Route::post('/upload-kartu', [DaftarUlangSiswaController::class, 'uploadKartuLolos'])->name('upload-kartu');
    Route::post('/pilih-jadwal', [DaftarUlangSiswaController::class, 'pilihJadwal'])->name('pilih-jadwal');
    Route::post('/upload-bukti', [DaftarUlangSiswaController::class, 'uploadBuktiPembayaran'])->name('upload-bukti');
    Route::delete('/delete-file/{type}', [DaftarUlangSiswaController::class, 'deleteFile'])->name('delete-file');
    Route::get('/info-pembayaran', [DaftarUlangSiswaController::class, 'infoPembayaran'])->name('info-pembayaran');
});




// ADMIN AUTHENTICATION ROUTES
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () { // PENTING: guest:admin
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login.form');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.attempt');

                // Opsional: Registrasi Admin (jika ada)
                Route::get('register', [AdminRegisterController::class, 'showRegistrationForm'])->name('register.form');
                Route::post('register', [AdminRegisterController::class, 'register'])->name('register.attempt');
            });

            // Rute yang Memerlukan Admin Sudah Login (Panel Admin)
            Route::middleware(['auth:admin', 'role:admin'])->group(function () { // Pastikan 'role:admin' juga ada di sini
                Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout'); // URL menjadi /admin/logout
                Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');


            // Route::get('/logout', [AdminDashboardController::class, 'logout'])->name('logout');
            Route::resource('jadwal-ppdb', JadwalPpdbController::class);



        // Rute untuk Admin (Memperbaiki error 'Route not defined')
        Route::middleware(['auth'])->prefix('/daftar-ulang')->name('daftar-ulang.')->group(function () {

            // Dashboard
            Route::get('/', [DaftarUlangController::class, 'index'])->name('index');

            // Jadwal (Route yang menyebabkan error)
            Route::get('/jadwal', [DaftarUlangController::class, 'jadwal'])->name('jadwal'); // Ini akan membuat rute bernama 'admin.daftar-ulang.jadwal'
            Route::post('/jadwal', [DaftarUlangController::class, 'storeJadwal'])->name('jadwal.store');
            Route::put('/jadwal/{id}', [DaftarUlangController::class, 'updateJadwal'])->name('jadwal.update');
            Route::delete('/jadwal/{id}', [DaftarUlangController::class, 'destroyJadwal'])->name('jadwal.destroy');

            // Biaya
            Route::get('/biaya', [DaftarUlangController::class, 'biaya'])->name('biaya');
            Route::post('/biaya', [DaftarUlangController::class, 'storeBiaya'])->name('biaya.store');
            Route::put('/biaya/{id}', [DaftarUlangController::class, 'updateBiaya'])->name('biaya.update');

            // Siswa & Verifikasi
            Route::get('/daftar-siswa', [DaftarUlangController::class, 'daftarSiswa'])->name('daftar-siswa');
            Route::get('/detail-siswa/{id}', [DaftarUlangController::class, 'detailSiswa'])->name('detail-siswa');
            Route::post('/verifikasi-berkas/{id}', [DaftarUlangController::class, 'verifikasiBerkas'])->name('verifikasi-berkas');
            Route::post('/verifikasi-pembayaran/{id}', [DaftarUlangController::class, 'verifikasiPembayaran'])->name('verifikasi-pembayaran');

            // Laporan & Export
            Route::get('/laporan', [DaftarUlangController::class, 'laporan'])->name('laporan');
            Route::get('/export', [DaftarUlangController::class, 'export'])->name('export');
        });









        // Route untuk laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {

            Route::get('/laporan', [LaporanController::class, 'index'])->name('index');
            Route::get('/laporan/semua', [LaporanController::class, 'all'])->name('all'); // Untuk filter laporan
            Route::get('/laporan/siswa-diterima', [LaporanController::class, 'siswaDiterima'])->name('siswa_diterima');

            // Route untuk export PDF
            Route::get('/laporan/all-pdf/{status?}', [LaporanController::class, 'allPdf'])->name('all-pdf')
                ->where('status', '(lulus_seleksi|all)?');
            Route::get('/laporan/siswa-diterima-pdf/{status?}', [LaporanController::class, 'siswaDiterimaPdf'])->name('siswaditerima-pdf')
                ->where('status', '(lulus_seleksi|all)?');

            // Siswa Tidak Lolos Routes
            Route::get('/siswa-tidak-lolos', [LaporanController::class, 'siswaTidakLolos'])
                ->name('siswa-tidak-lolos');
            Route::get('/siswa-tidak-lolos/pdf', [LaporanController::class, 'siswaTidakLolosPdf'])
                ->name('siswatidaklolos-pdf');
            Route::get('/siswa-tidak-lolos/excel', [LaporanController::class, 'siswaTidakLolosExcel'])
                ->name('siswatidaklolos-excel');

            // Route untuk export Excel
            Route::get('/laporan/all-excel/{status?}', [LaporanController::class, 'allExcel'])->name('all-excel')
                ->where('status', '(lulus_seleksi|all)?');
            Route::get('/laporan/siswa-diterima-excel/{status?}', [LaporanController::class, 'siswaDiterimaExcel'])->name('siswaditerima-excel')
                ->where('status', '(lulus_seleksi|all)?');

                // Route untuk Laporan Berkas
                Route::get('/berkas', [LaporanController::class, 'berkas'])->name('berkas');
                Route::get('/berkas/pdf', [LaporanController::class, 'berkasPdf'])->name('berkas-pdf');
                Route::get('/berkas/excel', [LaporanController::class, 'berkasExcel'])->name('berkas-excel');

                // Route untuk Laporan Daftar Ulang
                Route::get('/daftar-ulang', [LaporanController::class, 'daftarUlang'])->name('daftar-ulang');
                Route::get('/daftar-ulang/pdf', [LaporanController::class, 'daftarUlangPdf'])->name('daftar-ulang-pdf');
                Route::get('/daftar-ulang/excel', [LaporanController::class, 'daftarUlangExcel'])->name('daftar-ulang-excel');

            });
        // Route::get('/pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');

        // // Route untuk menampilkan detail pendaftar
        // // Laravel akan otomatis meng-inject model User berdasarkan ID di URL (Route Model Binding)
        // Route::get('/pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show');

        // // Route untuk POST request dari form verifikasi berkas
        // Route::post('/pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi_berkas');

        // // Route untuk POST request dari form ubah status pendaftaran manual
        // Route::post('/pendaftar/{user}/update-status-manual', [DataPendaftarController::class, 'updateStatusManual'])->name('pendaftar.update_status_manual');

        // // Route untuk PUT request dari modal edit biodata (karena ini update data)
        // Route::put('/pendaftar/{user}/update-data-dasar', [DataPendaftarController::class, 'updateDataDasar'])->name('pendaftar.update_data_dasar');

        // // Route untuk PUT request dari modal edit orang tua/wali
        // Route::put('/pendaftar/{user}/update-orang-tua-wali', [DataPendaftarController::class, 'updateOrangTuaWali'])->name('pendaftar.update_orang_tua_wali');

        // Data Pendaftar CRUD Routes
        Route::get('data-pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');
        Route::get('data-pendaftar/create', [DataPendaftarController::class, 'create'])->name('pendaftar.create');
        Route::post('data-pendaftar', [DataPendaftarController::class, 'store'])->name('pendaftar.store');
        Route::get('data-pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show');
        Route::get('data-pendaftar/{user}/edit', [DataPendaftarController::class, 'edit'])->name('pendaftar.edit');
        Route::put('data-pendaftar/{user}', [DataPendaftarController::class, 'update'])->name('pendaftar.update');
        Route::delete('data-pendaftar/{user}', [DataPendaftarController::class, 'destroy'])->name('pendaftar.destroy');

        // Admin Actions
        Route::post('data-pendaftar/{user}/update-status', [DataPendaftarController::class, 'updateStatusPendaftaran'])->name('pendaftar.update_status');
        Route::post('data-pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi_berkas');

        // Bulk Actions & Export
        Route::post('data-pendaftar/bulk-update-status', [DataPendaftarController::class, 'bulkUpdateStatus'])->name('pendaftar.bulk_update_status');
        Route::get('data-pendaftar-export', [DataPendaftarController::class, 'export'])->name('pendaftar.export');

        // API Routes for AJAX
        Route::get('api/pendaftar/stats', [DataPendaftarController::class, 'getStats'])->name('pendaftar.stats');
        Route::post('api/pendaftar/{user}/quick-status', [DataPendaftarController::class, 'quickStatusUpdate'])->name('pendaftar.quick_status');


        // Route::get('data-pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');
        // Route::get('data-pendaftar/{user}/edit', [DataPendaftarController::class, 'edit'])->name('pendaftar.edit'); // user adalah ID siswa
        // Route::get('data-pendaftar/create', [DataPendaftarController::class, 'create'])->name('pendaftar.create');
        // Route::post('data-pendaftar', [DataPendaftarController::class, 'store'])->name('pendaftar.store');
        // Route::get('data-pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show'); // user adalah ID siswa
        // Route::post('data-pendaftar/{user}/update-status', [DataPendaftarController::class, 'updateStatusPendaftaran'])->name('pendaftar.update_status');
        // Route::post('data-pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi_berkas'); // Khusus untuk form verifikasi

        // // Bulk Actions
        // Route::post('data-pendaftar/bulk-update-status', [DataPendaftarController::class, 'bulkUpdateStatus'])->name('pendaftar.bulk_update_status');

        // // Export
        // Route::get('data-pendaftar/export', [DataPendaftarController::class, 'export'])->name('pendaftar.export');


        // Comment Management Routes
        Route::prefix('komentar')->name('komentar.')->group(function () {
            Route::get('/', [AdminCommentController::class, 'index'])->name('index');
            Route::post('/', [AdminCommentController::class, 'store'])->name('store');
            Route::get('/{comment}', [AdminCommentController::class, 'show'])->name('show');
            Route::post('/{comment}/reply', [AdminCommentController::class, 'reply'])->name('reply');
            Route::patch('/{comment}/status', [AdminCommentController::class, 'updateStatus'])->name('update_status');
            Route::post('/{comment}/mark-as-read', [AdminCommentController::class, 'markAsRead'])->name('mark_as_read');
            Route::get('/mark-all-read', [AdminCommentController::class, 'markAllAsRead'])->name('mark_all_read');
            Route::get('/unread-count', [AdminCommentController::class, 'getUnreadCount'])->name('unread_count');
            Route::post('/{comment}/add-note', [AdminCommentController::class, 'addNote'])->name('add_note');
            Route::delete('/{comment}', [AdminCommentController::class, 'destroy'])->name('destroy');
        });



        // Route::get('/pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');
        // Route::get('/pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show');
        // Route::post('/pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasiBerkas');
        // Route::post('/pendaftar/{user}/update-status', [DataPendaftarController::class, 'updateStatusManual'])->name('pendaftar.updateStatus');
        // Route::post('/pendaftar/{user}/update-data-dasar', [DataPendaftarController::class, 'updateDataDasar'])->name('pendaftar.updateDataDasar');
        // // Route::post('/pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasiBerkas');



        // Route::get('/pendaftar', [AdminPendaftarController::class, 'index'])->name('admin.pendaftar.index');

        // // Berkas Management Routes
        // Route::get('/pendaftar/{user}/berkas', [BerkasPendaftarController::class, 'index'])->name('admin.berkas.index');
        // Route::get('/pendaftar/{user}/berkas/create', [BerkasController::class, 'create'])->name('admin.berkas.create');
        // Route::post('/pendaftar/{user}/berkas', [BerkasController::class, 'store'])->name('admin.berkas.store');
        // Route::get('/pendaftar/{user}/berkas/{berkas}/edit', [BerkasController::class, 'edit'])->name('admin.berkas.edit');
        // Route::put('/pendaftar/{user}/berkas/{berkas}', [BerkasController::class, 'update'])->name('admin.berkas.update');
        // Route::delete('/pendaftar/{user}/berkas/{berkas}', [BerkasController::class, 'destroy'])->name('admin.berkas.destroy');

        // // Kartu Pendaftaran Routes
        // Route::get('/kartu/{kartu}', [KartuPendaftaranController::class, 'show'])->name('admin.kartu.show');

        // // Pendaftar Management Routes (if not already defined)
        // Route::put('/pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('admin.pendaftar.verifikasi-berkas');
        // Route::put('/pendaftar/{user}/update-status', [AdminPendaftarController::class, 'updateStatus'])->name('admin.pendaftar.update-status');
        // Route::put('/pendaftar/{user}/update-data-dasar', [AdminPendaftarController::class, 'updateDataDasar'])->name('admin.pendaftar.update-data-dasar');

        // // Data Pendaftar Routes
        // Route::get('pendaftar', [DataPendaftarController::class]);
        // // Route::get('data-pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');
        // Route::get('pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show'); // {user} adalah ID siswa
        // Route::post('pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi_berkas');
        // Route::post('pendaftar/{user}/update-status-manual', [DataPendaftarController::class, 'updateStatusManual'])->name('pendaftar.update_status_manual');
        // Route::post('pendaftar/{user}/update-data-dasar', [DataPendaftarController::class, 'updateDataDasar'])->name('pendaftar.update_data_dasar');
        // Route::put('pendaftar/{user}/update-orang-tua-wali', [DataPendaftarController::class, 'updateOrangTuaWali'])->name('pendaftar.update_orang_tua_wali');


        // Route::prefix('pendaftar')->name('pendaftar.')->group(function () {

        // CRUD Pendaftar
        //     Route::get('/', [DataPendaftarController::class, 'index'])
        //         ->name('index');

        //     Route::get('/show/{user}', [DataPendaftarController::class, 'show'])
        //         ->name('show')->where('user', '[0-9]+');

        //     Route::get('/create', [DataPendaftarController::class, 'create'])
        //         ->name('create');

        //     Route::post('/store', [DataPendaftarController::class, 'store'])
        //         ->name('store');

        //     Route::get('/edit/{user}', [DataPendaftarController::class, 'edit'])
        //         ->name('edit')->where('user', '[0-9]+');

        //     Route::put('/update/{user}', [DataPendaftarController::class, 'update'])
        //         ->name('update')->where('user', '[0-9]+');

        //     Route::delete('/destroy/{user}', [DataPendaftarController::class, 'destroy'])
        //         ->name('destroy')->where('user', '[0-9]+');

        //     // Verifikasi & Status
        //     Route::post('/verifikasi-berkas/{user}', [DataPendaftarController::class, 'verifikasiBerkas'])
        //         ->name('verifikasi-berkas')->where('user', '[0-9]+');

        //     Route::post('/update-status-manual/{user}', [DataPendaftarController::class, 'updateStatusManual'])
        //         ->name('update-status-manual')->where('user', '[0-9]+');

        //     // Update Data
        //     Route::post('/update-data-dasar/{user}', [DataPendaftarController::class, 'updateDataDasar'])
        //         ->name('update-data-dasar')->where('user', '[0-9]+');

        //     Route::post('/update-biodata/{user}', [DataPendaftarController::class, 'updateBiodata'])
        //         ->name('update-biodata')->where('user', '[0-9]+');

        //     Route::post('/update-ortu/{user}', [DataPendaftarController::class, 'updateOrangTua'])
        //         ->name('update-ortu')->where('user', '[0-9]+');

        //     // Bulk Actions
        //     Route::post('/bulk-action', [DataPendaftarController::class, 'bulkAction'])
        //         ->name('bulk-action');

        //     // Generate & Utilities
        //     Route::post('/generate-no-pendaftaran/{user}', [DataPendaftarController::class, 'generateNoPendaftaran'])
        //         ->name('generate-no-pendaftaran')->where('user', '[0-9]+');

        //     Route::post('/reset-password/{user}', [DataPendaftarController::class, 'resetPassword'])
        //         ->name('reset-password')->where('user', '[0-9]+');

        //     // Export
        //     Route::get('/export/excel', [DataPendaftarController::class, 'exportExcel'])
        //         ->name('export.excel');

        //     Route::get('/export/pdf', [DataPendaftarController::class, 'exportPdf'])
        //         ->name('export.pdf');
        // });


        // Route::get('pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');
        // Route::get('pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show');
        // Route::post('pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi_berkas');
        // Route::post('pendaftar/{user}/update-status-manual', [DataPendaftarController::class, 'updateStatusManual'])->name('pendaftar.update_status_manual');
        // Route::post('pendaftar/{user}/update-data-dasar', [DataPendaftarController::class, 'updateDataDasar'])->name('pendaftar.update_data_dasar');
        // Route::put('pendaftar/{user}/update-orang-tua-wali', [DataPendaftarController::class, 'updateOrangTuaWali'])->name('pendaftar.update_orang_tua_wali');
        // Route::get('pendaftar/{user}/berkas', [DataPendaftarController::class, 'manageBerkasIndex'])->name('berkas.index');
        // Route::post('pendaftar/{user}/berkas', [DataPendaftarController::class, 'manageBerkas'])->name('pendaftar.berkas.manage');
        // Route::delete('pendaftar/{user}/berkas/{berkasField}', [DataPendaftarController::class, 'deleteBerkas'])->name('pendaftar.berkas.delete');



        //Route::resource('pendaftaran', DataPendaftarController::class);
        // Opsional: Tambahkan rute kustom untuk berkas jika belum ada
        //Route::post('pendaftaran/{user}/berkas', [DataPendaftarController::class, 'storeOrUpdateBerkas'])->name('pendaftaran.store-or-update-berkas');
        //Route::delete('pendaftaran/{user}/berkas/{field}', [DataPendaftarController::class, 'destroyBerkas'])->name('pendaftaran.destroy-berkas');


        // Route::get('seleksi', [SeleksiController::class, 'index'])->name('seleksi.index');
        //     Route::get('seleksi/jalur/{jalur}', [SeleksiController::class, 'jalurSeleksi'])->name('seleksi.jalur');
        // Route::get('hasil/{jalur}', [SeleksiController::class, 'hasilSeleksiJalur'])->name('hasil_jalur');
        // Route::post('proses-otomatis', [SeleksiController::class, 'prosesSeleksiOtomatis'])->name('proses_otomatis');
        // Route::post('reset-hasil', [SeleksiController::class, 'resetHasilSeleksi'])->name('reset_hasil'); // {jalur} = domisili, prestasi, dll.
        //     Route::post('seleksi/update-siswa/{user}', [SeleksiController::class, 'updateSeleksiSiswa'])->name('seleksi.update');
        //     Route::post('seleksi/proses-otomatis', [SeleksiController::class, 'prosesSeleksiOtomatis'])->name('seleksi.proses_otomatis');
        // Route::post('seleksi/umumkan-hasil', [SeleksiController::class, 'umumkanHasilSeleksi'])->name('seleksi.umumkan_hasil'); // Jika ada flag global


        Route::prefix('seleksi')->name('seleksi.')->group(function () {
            // Dashboard utama seleksi
            Route::get('/', [App\Http\Controllers\Admin\SeleksiController::class, 'index'])
                ->name('index');

            // Lihat hasil seleksi per jalur (READ-ONLY)
            Route::get('/hasil/{jalur}', [App\Http\Controllers\Admin\SeleksiController::class, 'hasilSeleksiJalur'])
                ->name('hasil_jalur')
                ->where('jalur', 'domisili|prestasi|afirmasi|mutasi');

            // Proses seleksi otomatis
            Route::post('/proses-otomatis', [App\Http\Controllers\Admin\SeleksiController::class, 'prosesSeleksiOtomatis'])
                ->name('proses_otomatis');

            // Reset hasil seleksi
            Route::post('/reset-hasil', [App\Http\Controllers\Admin\SeleksiController::class, 'resetHasilSeleksi'])
                ->name('reset_hasil');
        });





        // Daftar Ulang Routes
        // Route::get('daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang.index');
        // Route::post('daftar-ulang/update-status/{user}', [DaftarUlangController::class, 'updateStatus'])->name('daftar-ulang.update_status');
        // Anda bisa menambahkan route untuk melihat detail proses daftar ulang per siswa jika diperlukan
        // Route::get('daftar-ulang/detail/{user}', [DaftarUlangController::class, 'showDetail'])->name('daftar-ulang.show_detail');


        // Route::get('/daftar-ulang', [App\Http\Controllers\Admin\DaftarUlangController::class, 'index'])->name('daftar-ulang.index');
        // Route::post('/daftar-ulang/{user}/update-status', [App\Http\Controllers\Admin\DaftarUlangController::class, 'updateStatus'])->name('daftar-ulang.update_status');


         //spmb
                Route::resource('jadwal-pendaftaran', JadwalPendaftaranController::class);

                Route::get('petunjuk-teknis', [PetunjukTeknisController::class, 'index'])->name('petunjuk-teknis.index');
                Route::get('petunjuk-teknis/create', [PetunjukTeknisController::class, 'create'])->name('petunjuk-teknis.create');
                Route::post('petunjuk-teknis', [PetunjukTeknisController::class, 'store'])->name('petunjuk-teknis.store');
                Route::get('petunjuk-teknis/{petunjukTeknis}', [PetunjukTeknisController::class, 'show'])->name('petunjuk-teknis.show');
                Route::get('petunjuk-teknis/{petunjukTeknis}/edit', [PetunjukTeknisController::class, 'edit'])->name('petunjuk-teknis.edit');
                Route::put('petunjuk-teknis/{petunjukTeknis}', [PetunjukTeknisController::class, 'update'])->name('petunjuk-teknis.update');
                Route::delete('petunjuk-teknis/{petunjukTeknis}', [PetunjukTeknisController::class, 'destroy'])->name('petunjuk-teknis.destroy');

                Route::resource('kategori', KategoriController::class);
                Route::resource('dokumen-persyaratan', DokumenPersyaratanController::class);
                Route::resource('alur-pendaftaran', AlurPendaftaranController::class);


        // Route::resource('pendaftaran', DataPendaftarController::class);
        // Route::post('pendaftaran/{user}/berkas', [DataPendaftarController::class, 'storeOrUpdateBerkas'])->name('pendaftaran.store-or-update-berkas');
        // Route::delete('pendaftaran/{user}/berkas/{field}', [DataPendaftarController::class, 'destroyBerkas'])->name('pendaftaran.destroy-berkas');

        // Manajemen Akun Pengguna
        Route::resource('akun-pengguna', AkunPenggunaController::class);
        // Route::resource akan membuat route berikut:
        // GET admin/akun-pengguna -> AkunPenggunaController@index -> name('admin.akun-pengguna.index')
        // GET admin/akun-pengguna/create -> AkunPenggunaController@create -> name('admin.akun-pengguna.create')
        // POST admin/akun-pengguna -> AkunPenggunaController@store -> name('admin.akun-pengguna.store')
        // GET admin/akun-pengguna/{akun_pengguna} -> AkunPenggunaController@show -> name('admin.akun-pengguna.show')
        // GET admin/akun-pengguna/{akun_pengguna}/edit -> AkunPenggunaController@edit -> name('admin.akun-pengguna.edit')
        // PUT/PATCH admin/akun-pengguna/{akun_pengguna} -> AkunPenggunaController@update -> name('admin.akun-pengguna.update')
        // DELETE admin/akun-pengguna/{akun_pengguna} -> AkunPenggunaController@destroy -> name('admin.akun-pengguna.destroy')


        // Route::middleware(['auth', 'role:admin'])->group(function () {
        //     Route::get('admin/kartu-pendaftaran', [KartuPendaftaranController::class, 'index'])->name('admin.kartu-pendaftaran.index');
        //     Route::get('admin/kartu-pendaftaran/{id}/verify', [KartuPendaftaranController::class, 'verify'])->name('admin.kartu-pendaftaran.verify');
        // });


        // Route::resource('pendaftar', DataPendaftarController::class);
        // Route::get('pendaftar/{user}/berkas', [DataPendaftarController::class, 'berkasIndex'])->name('berkas.index');
        // Route::get('pendaftar/{user}/berkas/create-or-edit', [DataPendaftarController::class, 'berkasCreateOrEdit'])->name('berkas.create-or-edit');
        // Route::post('pendaftar/{user}/berkas/store-or-update', [DataPendaftarController::class, 'berkasStoreOrUpdate'])->name('berkas.store-or-update');
        // Route::post('pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi-berkas');
        // Route::post('pendaftar/{user}/update-status-manual', [DataPendaftarController::class, 'updateStatusManual'])->name('pendaftar.update-status-manual');
        // Route::post('pendaftar/{user}/update-data-dasar', [DataPendaftarController::class, 'updateDataDasar'])->name('pendaftar.update-data-dasar');


        // Pengaturan PPDB
        // Route::get('/pengaturan/kuota', [PengaturanController::class, 'kuota'])->name('pengaturan.kuota');
        // Route::get('/koordinat', [KoordinatController::class, 'index'])->name('koordinat.index');
        // Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        // Route::get('/laporan/statistik', [LaporanController::class, 'statistik'])->name('laporan.statistik');

        // Route::prefix('berkas')->name('berkas.')->group(function () {
        //     Route::get('/', [AdminBerkasController::class, 'index'])->name('index');
        //     Route::get('/{id}', [AdminBerkasController::class, 'show'])->name('show');
        //     Route::post('/{id}/verifikasi', [AdminBerkasController::class, 'verifikasi'])->name('verifikasi');
        //     Route::post('/bulk-verifikasi', [AdminBerkasController::class, 'bulkVerifikasi'])->name('bulk-verifikasi');

        //     // Export routes
        //     Route::get('/export', [AdminBerkasController::class, 'export'])->name('export');
        //     Route::get('/export-csv', [AdminBerkasController::class, 'exportCsv'])->name('export.csv');
        //     Route::get('/export-statistics', [AdminBerkasController::class, 'exportStatistics'])->name('export.statistics');
        //     Route::get('/template/{templateName}', [AdminBerkasController::class, 'downloadTemplate'])->name('template');
        // });
        Route::patch('/admin/siswa/{id}/verifikasi', [AdminBerkasController::class, 'verify'])->name('siswa.verify.card');

        // Berkas Management Routes
        Route::prefix('berkas')->name('berkas.')->group(function () {

            // Basic CRUD
            Route::get('/', [AdminBerkasController::class, 'index'])->name('index');
            Route::get('/{id}', [AdminBerkasController::class, 'show'])->name('show');

            // Verification
            Route::post('/{id}/verifikasi', [AdminBerkasController::class, 'verifikasi'])->name('verifikasi');
            Route::post('/bulk-verifikasi', [AdminBerkasController::class, 'bulkVerifikasi'])->name('bulk-verifikasi');


            // Export Routes
            Route::get('/export/excel', [AdminBerkasController::class, 'export'])->name('export');
            Route::get('/export/csv', [AdminBerkasController::class, 'exportCsv'])->name('export.csv');
            Route::get('/export/statistics', [AdminBerkasController::class, 'exportStatistics'])->name('export.statistics');
            Route::post('/berkas/{id}/checklist', [AdminBerkasController::class, 'updateChecklist'])->name('checklist.update');
            // Template Downloads
            Route::get('/template/{templateName}', [AdminBerkasController::class, 'downloadTemplate'])->name('template.download');
            Route::get('/{id}/download/{field}/{index?}', [AdminBerkasController::class, 'download'])->name('download');


        });
        // // Berkas Management Routes
        // Route::prefix('berkas')->name('berkas.')->group(function () {

        //     // Basic CRUD
        //     Route::get('/', [AdminBerkasController::class, 'index'])->name('index');
        //     Route::get('/{id}', [AdminBerkasController::class, 'show'])->name('show');

        //     // Verification
        //     Route::post('/{id}/verifikasi', [AdminBerkasController::class, 'verifikasi'])->name('verifikasi');
        //     Route::post('/bulk-verifikasi', [AdminBerkasController::class, 'bulkVerifikasi'])->name('bulk-verifikasi');

        //     // Export Routes
        //     Route::get('/export/excel', [AdminBerkasController::class, 'export'])->name('export');
        //     Route::get('/export/csv', [AdminBerkasController::class, 'exportCsv'])->name('export.csv');
        //     Route::get('/export/statistics', [AdminBerkasController::class, 'exportStatistics'])->name('export.statistics');

        //     // Template Downloads
        //     Route::get('/template/{templateName}', [AdminBerkasController::class, 'downloadTemplate'])->name('template.download');
        // });

        // Route::prefix('daftar-ulang')->name('daftar-ulang.')->group(function () {
        //     Route::get('/', [DaftarUlangController::class, 'index'])->name('index');

        //     // PERBAIKAN: Tambahkan route ini untuk halaman detail
        //     Route::get('/{daftarUlang}', [DaftarUlangController::class, 'show'])->name('show');

        //     // Route untuk aksi
        //     Route::put('/{id}/verifikasi-dokumen', [DaftarUlangController::class, 'verifikasiDokumen'])->name('verifikasi-dokumen');
        //     Route::put('/{id}/verifikasi-pembayaran', [DaftarUlangController::class, 'verifikasiPembayaran'])->name('verifikasi-pembayaran');

        // });

        // Route::get('/', [App\Http\Controllers\Admin\DaftarUlangController::class, 'index'])->name('index');
        // Route::get('/{daftarUlang}', [App\Http\Controllers\Admin\DaftarUlangController::class, 'show'])->name('show');
        // Route::put('/{id}/verifikasi-dokumen', [App\Http\Controllers\Admin\DaftarUlangController::class, 'verifikasiDokumen'])->name('verifikasi-dokumen');
        // Route::put('/{id}/verifikasi-pembayaran', [App\Http\Controllers\Admin\DaftarUlangController::class, 'verifikasiPembayaran'])->name('verifikasi-pembayaran');
        // Route::get('/jadwal', [App\Http\Controllers\Admin\DaftarUlangController::class, 'manageJadwal'])->name('jadwal');
        // Route::post('/jadwal', [App\Http\Controllers\Admin\DaftarUlangController::class, 'storeJadwal'])->name('store');
        // Route::put('/jadwal/{jadwal}', [App\Http\Controllers\Admin\DaftarUlangController::class, 'updateJadwal'])->name('jadwal.update');

        // // Admin Routes
        // Route::prefix('daftar-ulang')->name('daftar-ulang.')->group(function () {
        //     // Dashboard
        //     Route::get('/', [DaftarUlangController::class, 'index'])->name('index');

        //     // Kelola Jadwal
        //     Route::get('/jadwal', [DaftarUlangController::class, 'jadwal'])->name('jadwal');
        //     Route::post('/jadwal', [DaftarUlangController::class, 'storeJadwal'])->name('jadwal.store');
        //     Route::put('/jadwal/{id}', [DaftarUlangController::class, 'updateJadwal'])->name('jadwal.update');
        //     Route::delete('/jadwal/{id}', [DaftarUlangController::class, 'destroyJadwal'])->name('jadwal.destroy');

        //     // Kelola Biaya
        //     Route::get('/biaya', [DaftarUlangController::class, 'biaya'])->name('biaya');
        //     Route::post('/biaya', [DaftarUlangController::class, 'storeBiaya'])->name('biaya.store');
        //     Route::put('/biaya/{id}', [DaftarUlangController::class, 'updateBiaya'])->name('biaya.update');

        //     // Daftar Siswa
        //     Route::get('/daftar-siswa', [DaftarUlangController::class, 'daftarSiswa'])->name('daftar-siswa');
        //     Route::get('/detail-siswa/{id}', [DaftarUlangController::class, 'detailSiswa'])->name('detail-siswa');

        //     // Verifikasi
        //     Route::post('/verifikasi-berkas/{id}', [DaftarUlangController::class, 'verifikasiBerkas'])->name('verifikasi-berkas');
        //     Route::post('/verifikasi-pembayaran/{id}', [DaftarUlangController::class, 'verifikasiPembayaran'])->name('verifikasi-pembayaran');

        //     // Laporan
        //     Route::get('/laporan', [DaftarUlangController::class, 'laporan'])->name('laporan');
        //     Route::get('/export', [DaftarUlangController::class, 'export'])->name('export');
        // });

        // Route::prefix('jadwal-daftar-ulang')->name('jadwal-daftar-ulang.')->group(function () {
        //     // Main CRUD routes
        //     Route::get('/', [JadwalDaftarUlangController::class, 'index'])->name('index');
        //     Route::get('/create', [JadwalDaftarUlangController::class, 'create'])->name('create');
        //     Route::post('/', [JadwalDaftarUlangController::class, 'store'])->name('store');
        //     Route::get('/{jadwalDaftarUlang}', [JadwalDaftarUlangController::class, 'show'])->name('show');
        //     Route::get('/{jadwalDaftarUlang}/edit', [JadwalDaftarUlangController::class, 'edit'])->name('edit');
        //     Route::put('/{jadwalDaftarUlang}', [JadwalDaftarUlangController::class, 'update'])->name('update');
        //     Route::delete('/{jadwalDaftarUlang}', [JadwalDaftarUlangController::class, 'destroy'])->name('destroy');

        //     // Additional routes
        //     Route::get('/{jadwalDaftarUlang}/peserta', [JadwalDaftarUlangController::class, 'peserta'])->name('peserta');
        //     Route::get('/{jadwalDaftarUlang}/peserta/export', [JadwalDaftarUlangController::class, 'exportPeserta'])->name('export-peserta');
        //     Route::get('/{jadwalDaftarUlang}/peserta/print', [JadwalDaftarUlangController::class, 'printPeserta'])->name('print-peserta');
        //     Route::post('/generate-auto', [JadwalDaftarUlangController::class, 'generateAuto'])->name('generate-auto');



        //     // Additional routes for enhanced functionality
        //     Route::post('/bulk-update-status', [JadwalDaftarUlangController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        //     Route::patch('/{jadwalDaftarUlang}/toggle-status', [JadwalDaftarUlangController::class, 'toggleStatus'])->name('toggle-status');
        //     Route::get('/calendar', [JadwalDaftarUlangController::class, 'calendar'])->name('calendar');
        //     Route::get('/analytics', [JadwalDaftarUlangController::class, 'analytics'])->name('analytics');
        //     Route::post('/duplicate/{jadwalDaftarUlang}', [JadwalDaftarUlangController::class, 'duplicate'])->name('duplicate');

        //     // Conflict check route (for AJAX)
        //     Route::post('/check-conflict', [JadwalDaftarUlangController::class, 'checkConflict'])->name('check-conflict');

        //     // Quick actions
        //     Route::post('/quick-create', [JadwalDaftarUlangController::class, 'quickCreate'])->name('quick-create');

        //     // Template management
        //     Route::get('/templates', [JadwalDaftarUlangController::class, 'templates'])->name('templates');
        //     Route::post('/save-template', [JadwalDaftarUlangController::class, 'saveTemplate'])->name('save-template');
        //     Route::post('/apply-template/{template}', [JadwalDaftarUlangController::class, 'applyTemplate'])->name('apply-template');
        // });

        // });



        // Route::get('jadwal-daftar-ulang/{jadwal}/peserta', [JadwalDaftarUlangController::class, 'peserta'])
        //     ->name('jadwal-daftar-ulang.peserta');

        // Route::post('jadwal-daftar-ulang/generate-auto', [JadwalDaftarUlangController::class, 'generateAuto'])
        //     ->name('jadwal-daftar-ulang.generate-auto');

        // // Route resource untuk CRUD Jadwal (create, read, update, delete)
        // Route::resource('jadwal-daftar-ulang', JadwalDaftarUlangController::class);



        // // Route untuk manajemen berkas siswa
        // Route::prefix('berkas')->name('berkas.')->group(function () {
        //     // Daftar semua berkas siswa
        //     Route::get('/', [App\Http\Controllers\Admin\AdminBerkasController::class, 'index'])
        //         ->name('index');
        //     // Detail berkas siswa tertentu
        //     Route::get('/{id}', [App\Http\Controllers\Admin\AdminBerkasController::class, 'show'])
        //         ->name('show');
        //     // Verifikasi berkas siswa
        //     Route::post('/{id}/verifikasi', [App\Http\Controllers\Admin\AdminBerkasController::class, 'verifikasi'])
        //         ->name('verifikasi');
        //     // Bulk verifikasi berkas multiple siswa
        //     Route::post('/bulk-verifikasi', [App\Http\Controllers\Admin\AdminBerkasController::class, 'bulkVerifikasi'])
        //         ->name('bulk-verifikasi');
        //     // Hapus file berkas tertentu
        //     Route::delete('/{id}/delete-file/{field}', [App\Http\Controllers\Admin\AdminBerkasController::class, 'deleteFile'])
        //         ->name('delete-file');
        //     // Download file berkas
        //     Route::get('/{id}/download/{field}/{index?}', [App\Http\Controllers\Admin\AdminBerkasController::class, 'downloadFile'])
        //         ->name('download');
        //     // Export data berkas (Excel/PDF)
        //     Route::get('/export', [App\Http\Controllers\Admin\AdminBerkasController::class, 'export'])
        //         ->name('export');
        // });

        // Route::prefix('kartu-pendaftaran')->name('kartu-pendaftaran.')->group(function () {
        //     Route::get('/', [AdminKartuPendaftaranController::class, 'index'])->name('index');
        //     Route::post('/verify/{id}', [AdminKartuPendaftaranController::class, 'verify'])->name('verify');
        //     Route::post('/unverify/{id}', [AdminKartuPendaftaranController::class, 'unverify'])->name('unverify');
        //     Route::post('/bulk-update', [AdminKartuPendaftaranController::class, 'bulkUpdateStatus'])->name('bulk-update');
        //     Route::get('/export', [AdminKartuPendaftaranController::class, 'export'])->name('export');
        // });



        // Kartu Pendaftaran Management
        Route::get('/kartu-pendaftaran', [AdminKartuPendaftaranController::class, 'index'])
            ->name('kartu-pendaftaran.index');

        Route::get('/kartu-pendaftaran/{id}', [AdminKartuPendaftaranController::class, 'show'])
            ->name('kartu-pendaftaran.show');
        Route::get('/kartu-pendaftaran/{id}/edit', [AdminKartuPendaftaranController::class, 'edit'])->name('kartu-pendaftaran.edit');
        Route::put('/kartu-pendaftaran/{id}', [AdminKartuPendaftaranController::class, 'update'])->name('kartu-pendaftaran.update'); // Perhatikan method PUT
        // Route::patch('/{id}/verify', [AdminKartuPendaftaranController::class, 'verify'])->name('kartu-pendaftaran.verify');
        // Route::patch('/{id}/unverify', [AdminKartuPendaftaranController::class, 'unverify'])->name('kartu-pendaftaran.unverify');

        Route::post('/kartu-pendaftaran/bulk-update', [AdminKartuPendaftaranController::class, 'bulkUpdateStatus'])
            ->name('kartu-pendaftaran.bulk-update');

    Route::prefix('kartu-pendaftaran')->name('kartu-pendaftaran.')->group(function () {
        Route::post('/bulk-action', [KartuPendaftaranController::class, 'bulkAction'])->name('bulk-action');
        Route::patch('/{kartu}/verify', [KartuPendaftaranController::class, 'verify'])->name('verify');
        Route::patch('/{kartu}/unverify', [KartuPendaftaranController::class, 'unverify'])->name('unverify');
        Route::post('/bulk-action', [KartuPendaftaranController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [KartuPendaftaranController::class, 'export'])->name('export');
    });
        Route::get('/kartu-pendaftaran/export', [AdminKartuPendaftaranController::class, 'export'])
            ->name('kartu-pendaftaran.export');

        // QR Code specific routes
        Route::get('/kartu-pendaftaran/{id}/generate-qr', [AdminKartuPendaftaranController::class, 'generateQRCode'])
            ->name('kartu-pendaftaran.generate-qr');

        Route::post('/kartu-pendaftaran/verify-qr-scan', [AdminKartuPendaftaranController::class, 'verifyQRCodeScan'])
            ->name('kartu-pendaftaran.verify-qr-scan');

        Route::get('/kartu-pendaftaran/qr-stats', [AdminKartuPendaftaranController::class, 'qrCodeStats'])
            ->name('kartu-pendaftaran.qr-stats');





        /*
    |--------------------------------------------------------------------------
    | PENGUMUMAN UMUM - FULL CRUD
    |--------------------------------------------------------------------------
    */
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [PengumumanController::class, 'index'])->name('index');           // GET /admin/pengumuman
            Route::get('/create', [PengumumanController::class, 'create'])->name('create');   // GET /admin/pengumuman/create
            Route::post('/', [PengumumanController::class, 'store'])->name('store');          // POST /admin/pengumuman
            Route::get('/{pengumuman}', [PengumumanController::class, 'show'])->name('show'); // GET /admin/pengumuman/{id}
            Route::get('/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('edit'); // GET /admin/pengumuman/{id}/edit
            Route::put('/{pengumuman}', [PengumumanController::class, 'update'])->name('update'); // PUT /admin/pengumuman/{id}
            Route::delete('/{pengumuman}', [PengumumanController::class, 'destroy'])->name('destroy'); // DELETE /admin/pengumuman/{id}
        });

        /*
    |--------------------------------------------------------------------------
    | PENGUMUMAN HASIL PPDB - FULL CRUD
    |--------------------------------------------------------------------------
    */
        Route::prefix('pengumuman-hasil')->name('pengumuman-hasil.')->group(function () {
            Route::get('/', [PengumumanHasilController::class, 'index'])->name('index');           // GET /admin/pengumuman-hasil
            Route::get('/create', [PengumumanHasilController::class, 'create'])->name('create');   // GET /admin/pengumuman-hasil/create
            Route::post('/', [PengumumanHasilController::class, 'store'])->name('store');          // POST /admin/pengumuman-hasil
            Route::get('/{pengumumanHasil}', [PengumumanHasilController::class, 'show'])->name('show'); // GET /admin/pengumuman-hasil/{id}
            Route::get('/{pengumumanHasil}/edit', [PengumumanHasilController::class, 'edit'])->name('edit'); // GET /admin/pengumuman-hasil/{id}/edit
            Route::put('/{pengumumanHasil}', [PengumumanHasilController::class, 'update'])->name('update'); // PUT /admin/pengumuman-hasil/{id}
            Route::delete('/{pengumumanHasil}', [PengumumanHasilController::class, 'destroy'])->name('destroy'); // DELETE /admin/pengumuman-hasil/{id}
        });

        // Search siswa untuk pengumuman hasil (AJAX)
        // Route::get('/siswa/search', [App\Http\Controllers\Admin\SiswaController::class, 'searchForHasil'])
        //     ->name('siswa.search');

        // Pengumuman regular (existing routes with additional for hasil)
        // Route::resource('pengumuman', App\Http\Controllers\Admin\PengumumanController::class);


        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');          // List all students
            Route::get('/create', [SiswaController::class, 'create'])->name('create'); // Create new student
            Route::post('/', [SiswaController::class, 'store'])->name('store');        // Store new student
            Route::get('/{siswa}', [SiswaController::class, 'show'])->name('show');    // Show any student
            Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('edit'); // Edit any student
            Route::put('/{siswa}', [SiswaController::class, 'update'])->name('update'); // Update any student
            Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('destroy'); // Delete any student
        });



                Route::resource('profil-sekolah', ProfilSekolahController::class);
                Route::resource('guru-staff', GuruDanStaffController::class);
                Route::resource('galeri', GalleryController::class);
                Route::resource('ekskul', EkskulController::class);
                Route::resource('berita', BeritaController::class);



                // Route::resource('pengumuman', PengumumanController::class);
                // Route::put('admin/berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
                // Route::put('berita/{beritum}', [BeritaController::class, 'update'])->name('berita.update');
                // Route::put('berita/{berita}', ['as' => 'admin.berita.update', 'uses' => 'App\Http\Controllers\Admin\BeritaController@update']);
        });
    });





        // // API Routes
        // Route::middleware(['auth:sanctum'])->prefix('api')->name('api.')->group(function () {
        //     Route::prefix('daftar-ulang')->name('daftar-ulang.')->group(function () {
        //         Route::get('/status', [DaftarUlangSiswaController::class, 'getStatus'])->name('status');
        //         Route::get('/statistics', [DaftarUlangController::class, 'getStatistics'])->name('statistics');
        //     });
        // });


// routes/web.php (di luar middleware auth, untuk sementara)
        // Route::get('/admin-force-logout', function (Request $request) {
        //     Auth::guard('admin')->logout();
        //     $request->session()->invalidate();
        //     $request->session()->regenerateToken();
        //     return redirect(route('admin.login.form'))->with('status', 'Anda telah logout sebagai admin.');
        // })->name('admin.force.logout');

            Route::middleware('guest:web')->group(function () { // PENTING: guest:web atau cukup guest jika 'web' adalah default
                Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
                Route::post('login', [AuthenticatedSessionController::class, 'store']);
            });




// // API Routes for AJAX calls (optional)
// Route::middleware(['auth'])->group(function () {
//     Route::prefix('api/daftar-ulang')->name('api.daftar-ulang.')->group(function () {
//         // Check jadwal availability
//         Route::get('/jadwal/{jadwal}/availability', function (JadwalDaftarUlang $jadwal) {
//             return response()->json([
//                 'available' => !$jadwal->is_full && $jadwal->aktif,
//                 'sisa_kuota' => $jadwal->sisa_kuota,
//                 'message' => $jadwal->is_full ? 'Jadwal sudah penuh' : 'Jadwal tersedia'
//             ]);
//         })->name('jadwal.availability');

//         // Get user daftar ulang status
//         Route::get('/status', function () {
//             $daftarUlang = DaftarUlang::where('user_id', auth()->id())->first();
//             return response()->json([
//                 'status' => $daftarUlang ? $daftarUlang->status : null,
//                 'progress' => $daftarUlang ? $daftarUlang->progress_percentage : 0
//             ]);
//         })->name('status');
//     });
// });



// // Route Registrasi Siswa (custom, dengan middleware cek jadwal)
// Route::middleware('guest:web')->group(function () {
//     Route::get('register-ppdb', [RegisteredUserController::class, 'create'])
//         ->middleware('ppdb.active:pendaftaran_registrasi') // <-- TERAPKAN DI SINI
//         ->name('register'); // Kita override nama 'register' default

//     Route::post('register-ppdb', [RegisteredUserController::class, 'store'])
//         ->middleware('ppdb.active:pendaftaran_registrasi'); // <-- TERAPKAN DI SINI
// });

Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Route::prefix('siswa')->name('siswa.')->group(function () {
//     Route::get('/kartu-pendaftaran', [KartuPendaftaranController::class, 'index'])->name('kartu-pendaftaran.index');
//     Route::get('/kartu-pendaftaran', [KartuPendaftaranController::class, 'show'])->name('kartu-pendaftaran.show');
//     Route::get('/kartu-pendaftaran/{id}/download-pdf', [KartuPendaftaranController::class, 'downloadPdf'])->name('kartu-pendaftaran.download-pdf');
// });

// routes/web.php
// Jika belum di-alias di bootstrap/app.php

// ... (Route publik dan route admin lainnya) ...

// === HALAMAN SISWA (Setelah Login) ===
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    Route::get('dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');


        Route::get('biodata', [SiswaBiodataController::class, 'index'])->name('biodata.index');
        Route::post('biodata', [SiswaBiodataController::class, 'storeOrUpdate'])->name('biodata.store');


    // Route::get('/berkas', [BerkasController::class, 'index'])->name('berkas.index');
    Route::post('/berkas/upload', [BerkasController::class, 'upload'])->name('berkas.upload');
    // Route::delete('/berkas/delete/{field_name}', [BerkasController::class, 'deleteFile'])->name('berkas.delete');
    Route::resource('/berkas', BerkasController::class);
    // Route khusus untuk menghapus file individual
    Route::post('berkas/delete/{field_name}', [BerkasController::class, 'deleteFile'])->name('berkas.deleteFile');


    Route::get('/pengumuman', [SiswaPengumumanHasilController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/download-hasil', [SiswaPengumumanHasilController::class, 'downloadHasilPdf'])->name('pengumuman.download-hasil-pdf');
    Route::get('/pengumuman/preview-hasil', [SiswaPengumumanHasilController::class, 'previewHasil'])->name('pengumuman.preview-hasil-pdf');


    // Route::get('daftar-ulang/download', [KartuPendaftaranController::class, 'download'])
    //     ->middleware('ppdb.active:daftar_ulang')
    //     ->name('daftar-ulang');


    // Route::prefix('daftar-ulang')->name('daftar-ulang.')->group(function () {
    //     Route::get('/', [DaftarUlangSiswaController::class, 'index'])->name('index');
    //     Route::post('/upload-kartu', [DaftarUlangSiswaController::class, 'uploadKartuLolos'])->name('upload-kartu');
    //     Route::post('/pilih-jadwal', [DaftarUlangSiswaController::class, 'pilihJadwal'])->name('pilih-jadwal');
    //     Route::get('/info-pembayaran', [DaftarUlangSiswaController::class, 'infoPembayaran'])->name('info-pembayaran');
    //     Route::post('/upload-bukti', [DaftarUlangSiswaController::class, 'uploadBuktiPembayaran'])->name('upload-bukti');
    //     Route::delete('/delete-file/{type}', [DaftarUlangSiswaController::class, 'deleteFile'])->name('delete-file');
    // });





    // Profile Management Routes
    Route::get('profile', [SiswaProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [SiswaProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [SiswaProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/change-password', [SiswaProfileController::class, 'changePassword'])->name('profile.change_password');
    Route::get('profile/completion', [SiswaProfileController::class, 'checkCompletion'])->name('profile.completion');

    // Pendaftar Routes (Show & Edit Only - Self Access)
    Route::get('pendaftar/{user}', [SiswaProfileController::class, 'showPendaftar'])->name('pendaftar.show');
    Route::get('pendaftar/{user}/edit', [SiswaProfileController::class, 'editPendaftar'])->name('pendaftar.edit');
    Route::put('pendaftar/{user}', [SiswaProfileController::class, 'updatePendaftar'])->name('pendaftar.update');


        // Route::prefix('kartu-pendaftaran')->name('kartu-pendaftaran.')->group(function () {
        //     Route::get('/', [KartuPendaftaranController::class, 'show'])->name('show');
        //     Route::get('/download/{id}', [KartuPendaftaranController::class, 'downloadPdf'])->name('download');
        //     Route::get('/download/{id}/pdf', [KartuPendaftaranController::class, 'downloadPdf'])->name('download.pdf');
        //     Route::get('/verify-qr', [KartuPendaftaranController::class, 'verifyQRCode'])->name('verify-qr') ;
        // });

        // Kartu Pendaftaran Routes
    Route::prefix('kartu-pendaftaran')->name('kartu-pendaftaran.')->group(function () {
    Route::get('/kartu-pendaftaran', [KartuPendaftaranController::class, 'show'])
        ->name('show');

    Route::get('/kartu-pendaftaran/download/{id}', [KartuPendaftaranController::class, 'downloadPdf'])
        ->name('download-pdf');


        // Rute API (jika masih digunakan oleh frontend JS)
        // Route::get('/api/data', [KartuPendaftaranController::class, 'getCardData'])->name('api.data');
        // Route::get('/api/generate-qr', [KartuPendaftaranController::class, 'generateQRCode'])->name('api.generate-qr'); // Ini mungkin tidak perlu di siswa
    });
    // Route::get('/kartu-pendaftaran/{id}/download-pdf', [KartuPendaftaranController::class, 'downloadPdf'])->name('kartu-pendaftaran.download-pdf');


    // API Routes untuk frontend
    Route::get('/api/kartu-pendaftaran/data', [KartuPendaftaranController::class, 'getCardData'])
        ->name('api.kartu-pendaftaran.data');

    Route::get('/api/kartu-pendaftaran/generate-qr', [KartuPendaftaranController::class, 'generateQRCode'])
        ->name('api.kartu-pendaftaran.generate-qr');

    // Route::get('profile/password', [SiswaProfileController::class, 'editPassword'])->name('profile.password.edit');
    // Route::put('profile/password', [SiswaProfileController::class, 'updatePassword'])->name('profile.password.update');
}); // ✅ tutup blok route siswa







//admin login
// Route::get('/admin/login', [AdminDashboardController::class, 'adminLogin'])->name('admin.login');
// Route::post('login', [AdminDashboardController::class, 'loginProses'])->name('loginProses');

// Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
//     // Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//     //admin login

//     // Route::get('/logout', [AdminDashboardController::class, 'logout'])->name('logout');
//     Route::resource('jadwal-ppdb', JadwalPpdbController::class);

//     // Data Pendaftar Routes
//     Route::get('data-pendaftar', [DataPendaftarController::class, 'index'])->name('pendaftar.index');
//     Route::get('data-pendaftar/{user}', [DataPendaftarController::class, 'show'])->name('pendaftar.show'); // {user} adalah ID siswa
//     Route::post('data-pendaftar/{user}/verifikasi-berkas', [DataPendaftarController::class, 'verifikasiBerkas'])->name('pendaftar.verifikasi_berkas');
//     Route::post('data-pendaftar/{user}/update-status-manual', [DataPendaftarController::class, 'updateStatusManual'])->name('pendaftar.update_status_manual');
//     Route::post('data-pendaftar/{user}/update-data-dasar', [DataPendaftarController::class, 'updateDataDasar'])->name('pendaftar.update_data_dasar');

//     Route::get('seleksi', [SeleksiController::class, 'index'])->name('seleksi.index');
//     Route::get('seleksi/jalur/{jalur}', [SeleksiController::class, 'jalurSeleksi'])->name('seleksi.jalur'); // {jalur} = domisili, prestasi, dll.
//     Route::post('seleksi/update-siswa/{user}', [SeleksiController::class, 'updateSeleksiSiswa'])->name('seleksi.update_siswa');
//     Route::post('seleksi/proses-otomatis', [SeleksiController::class, 'prosesSeleksiOtomatis'])->name('seleksi.proses_otomatis');
//     // Route::post('seleksi/umumkan-hasil', [SeleksiController::class, 'umumkanHasilSeleksi'])->name('seleksi.umumkan_hasil'); // Jika ada flag global


//     // Daftar Ulang Routes
//     Route::get('daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftar-ulang.index');
//     Route::post('daftar-ulang/update-status/{user}', [DaftarUlangController::class, 'updateStatus'])->name('daftar-ulang.update_status');
//     // Anda bisa menambahkan route untuk melihat detail proses daftar ulang per siswa jika diperlukan
//     // Route::get('daftar-ulang/detail/{user}', [DaftarUlangController::class, 'showDetail'])->name('daftar-ulang.show_detail');


//     // Manajemen Akun Pengguna
//     Route::resource('akun-pengguna', AkunPenggunaController::class);
//     // Route::resource akan membuat route berikut:
//     // GET admin/akun-pengguna -> AkunPenggunaController@index -> name('admin.akun-pengguna.index')
//     // GET admin/akun-pengguna/create -> AkunPenggunaController@create -> name('admin.akun-pengguna.create')
//     // POST admin/akun-pengguna -> AkunPenggunaController@store -> name('admin.akun-pengguna.store')
//     // GET admin/akun-pengguna/{akun_pengguna} -> AkunPenggunaController@show -> name('admin.akun-pengguna.show')
//     // GET admin/akun-pengguna/{akun_pengguna}/edit -> AkunPenggunaController@edit -> name('admin.akun-pengguna.edit')
//     // PUT/PATCH admin/akun-pengguna/{akun_pengguna} -> AkunPenggunaController@update -> name('admin.akun-pengguna.update')
//     // DELETE admin/akun-pengguna/{akun_pengguna} -> AkunPenggunaController@destroy -> name('admin.akun-pengguna.destroy')







// });

// Route::middleware('guest')->group(function () {
//     Route::get('register-ppdb', [RegisteredUserController::class, 'create'])
//         ->middleware('ppdb.active:pendaftaran_registrasi') // Cek jadwal PPDB untuk registrasi
//         ->name('register'); // Kita override nama 'register' default
//     Route::post('register-ppdb', [RegisteredUserController::class, 'store'])
//         ->middleware('ppdb.active:pendaftaran_registrasi');
// });

// // === HALAMAN SETELAH LOGIN ===
// Route::middleware(['auth'])->group(
//     function () {
//         // Redirector berdasarkan role setelah login (jika AuthenticatedSessionController tidak handle)
//         Route::get('/dashboard-redirect', function () {
//             if (Auth::user()->role == 'admin') {
//                 return redirect()->route('admin.dashboard');
//             } elseif (Auth::user()->role == 'siswa') {
//                 return redirect()->route('siswa.dashboard');
//             }
//             return redirect('/'); // Fallback
//         })->name('dashboard.redirect');


//         // Siswa Routes
//         Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
//             Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
//             // ... (route siswa lainnya seperti biodata, berkas, dll.)
//         });

//         // Admin Routes
//         Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(
//             function () {
//                 Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//                 // ... (route admin lainnya seperti jadwal-ppdb, pendaftar, dll.)
//             });
//     });

// Opsional: Route Login Khusus Admin
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::middleware('guest')->group(function () {
//         Route::get('login', [AdminLoginController::class, 'create'])->name('login.form');
//         Route::post('login', [AdminLoginController::class, 'store'])->name('login.attempt');
//     });
//     // Route logout admin bisa diletakkan di sini atau di grup middleware auth admin
// });



//     Route::middleware(['auth','role:admin'])->group(function (){
//     // Profil Sekolah hanya edit & update karena cuma 1 record

//     Route::resource('profil-sekolah', ProfilSekolahController::class);
//     Route::resource('guru-staff', GuruDanStaffController::class);
//     Route::resource('galeri', GalleryController::class);
//     Route::resource('ekskul', EkskulController::class);
//     Route::resource('berita', BeritaController::class);
//     Route::resource('pengumuman', PengumumanController::class);
//         Route::put('berita/{beritum}', [BeritaController::class, 'update'])->name('berita.update');



//     // Nanti tambah route untuk pendaftar, seleksi, laporan
// });

// Route::get('/siswa/login', [SiswaDashboardController::class, 'siswaLogin'])->name('siswa.login');
// Route::post('login', [AdminDashboardController::class, 'ProsesLogin'])->name('ProsesLogin');
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });



// Route::prefix('admin-area')->name('admin.')->group(function () { // Mengubah prefix agar tidak bentrok /admin
//     Route::middleware('guest:admin')->group(function () { // Menggunakan guard 'admin'
//         Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
//         Route::post('login', [AdminLoginController::class, 'login']);

//         // Opsional: Registrasi Admin (biasanya tidak ada atau sangat terbatas)
//         // Route::get('register', [AdminRegisterController::class, 'showRegistrationForm'])->name('register');
//         // Route::post('register', [AdminRegisterController::class, 'register']);
//     });

//     Route::middleware('auth:admin')->group(function () { // Menggunakan guard 'admin'
//         Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
//         // Route dashboard dan lainnya akan di sini
//         // Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//     });
// });

// // Route untuk Verifikasi QR Code (tanpa middleware auth)
// Route::post('/kartu-pendaftaran/verify-qr', [KartuPendaftaranController::class, 'verifyQRCode'])->name('kartu-pendaftaran.verify-qr');


// Public QR Code Verification Route (dapat diakses tanpa login untuk scanner external)
Route::post('/verify-qr-code', [KartuPendaftaranController::class, 'verifyQRCode'])
    ->name('kartu-pendaftaran.verify-qr');

// Optional: QR Code scanner page untuk admin
Route::middleware(['auth', 'admin'])->get('/admin/qr-scanner', function () {
    return view('admin.qr-scanner');
})->name('admin.qr-scanner');


Route::get('/verify/hasil/{userId}', [PengumumanHasilController::class, 'verifyHasil'])
    ->name('verify.hasil');


require __DIR__.'/auth.php';

