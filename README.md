<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

![alt text](https://github.com/Dellafaiza/uptd-smpn-unggulan-sindang-berdasarkan-zonasi-berbasis-web/blob/main/public/1.png?raw=true)

![alt text](https://github.com/Dellafaiza/uptd-smpn-unggulan-sindang-berdasarkan-zonasi-berbasis-web/blob/main/public/2.png?raw=true)

![alt text](https://github.com/Dellafaiza/uptd-smpn-unggulan-sindang-berdasarkan-zonasi-berbasis-web/blob/main/public/3.png?raw=true)

![alt text](https://github.com/Dellafaiza/uptd-smpn-unggulan-sindang-berdasarkan-zonasi-berbasis-web/blob/main/public/4.png?raw=true)

![alt text](https://github.com/Dellafaiza/uptd-smpn-unggulan-sindang-berdasarkan-zonasi-berbasis-web/blob/main/public/5.png?raw=true)



# 🏫 Website UPTD SMPN Unggulan Sindang

Website profil sekolah dan sistem Penerimaan Peserta Didik Baru (PPDB) online berbasis zonasi untuk UPTD SMPN Unggulan Sindang, Indramayu, Jawa Barat.

## 📋 Tentang Proyek

Aplikasi web ini dikembangkan untuk memudahkan proses penerimaan peserta didik baru di UPTD SMPN Unggulan Sindang dengan sistem seleksi berbasis zonasi. Sistem ini secara otomatis menghitung jarak tempat tinggal calon siswa ke sekolah dan menentukan kuota penerimaan berdasarkan zona yang telah ditetapkan, sesuai dengan peraturan Permendikbud tentang PPDB berbasis zonasi.

## ✨ Fitur Utama

### Website Profil Sekolah
- 📰 Informasi lengkap tentang sekolah (visi, misi, sejarah)
- 🏆 Profil sekolah unggulan dan prestasi
- 👥 Profil guru dan tenaga kependidikan
- 🎓 Program unggulan dan kegiatan ekstrakurikuler
- 📸 Galeri foto dan dokumentasi kegiatan
- 📢 Berita dan pengumuman terkini
- 📞 Informasi kontak dan lokasi sekolah

### Sistem PPDB Berbasis Zonasi
- 📍 **Sistem Zonasi Otomatis** - Perhitungan jarak otomatis dari alamat rumah ke sekolah
- 🗺️ **Google Maps Integration** - Validasi alamat dan mapping lokasi
- 📝 Formulir pendaftaran online lengkap dengan data zonasi
- 📤 Upload dokumen persyaratan (KK, Akta Kelahiran, dll)
- 🎯 **Seleksi Otomatis Berdasarkan Zona:**
  - Zona 1: Prioritas utama (jarak terdekat)
  - Zona 2: Prioritas kedua
  - Zona 3: Jalur umum
- 📊 Dashboard monitoring kuota per zona
- 🔍 Tracking status pendaftaran dan zona real-time
- ✅ Verifikasi dan validasi data pendaftar
- 📈 Laporan dan statistik PPDB per zona
- 🔔 Notifikasi status penerimaan berdasarkan zona
- 🖨️ Cetak bukti pendaftaran dengan informasi zona
- 📉 Perhitungan ranking berdasarkan jarak dan nilai

### Fitur Admin
- 👨‍💼 Dashboard admin lengkap
- ⚙️ Pengaturan batas zona (radius dalam kilometer)
- 📋 Pengaturan kuota per zona
- 🗓️ Manajemen jadwal PPDB
- ✏️ Verifikasi manual untuk kasus khusus
- 📊 Laporan statistik dan visualisasi data zonasi
- 📧 Sistem notifikasi ke pendaftar

## 🛠️ Teknologi yang Digunakan

- **Framework:** Laravel (PHP)
- **Frontend:** Blade Template, Bootstrap/Tailwind CSS, JavaScript
- **Database:** MySQL
- **Maps API:** Google Maps API / Leaflet.js
- **Authentication:** Laravel Breeze/Sanctum
- **File Storage:** Laravel Storage
- **Geocoding:** Google Geocoding API untuk konversi alamat ke koordinat

## 📦 Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL/MariaDB >= 5.7
- Node.js & NPM (untuk asset compilation)
- Apache/Nginx
- Google Maps API Key (untuk fitur zonasi)

## 🚀 Instalasi

1. Clone repository
```bash
git clone https://github.com/username/uptd-smpn-unggulan-sindang.git
cd uptd-smpn-unggulan-sindang
```

2. Install dependencies
```bash
composer install
npm install
```

3. Copy file environment
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Konfigurasi database dan Google Maps API di file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppdb_smpn_sindang
DB_USERNAME=username
DB_PASSWORD=password

GOOGLE_MAPS_API_KEY=your_google_maps_api_key_here
```

6. Jalankan migration dan seeder
```bash
php artisan migrate --seed
```

7. Compile assets
```bash
npm run dev
```

8. Jalankan aplikasi
```bash
php artisan serve
```

Aplikasi dapat diakses di `http://localhost:8000`

## 🗺️ Konfigurasi Zonasi

Setelah instalasi, login sebagai admin dan atur:

1. **Koordinat Sekolah** - Tentukan titik koordinat sekolah sebagai pusat zonasi
2. **Radius Zona** - Atur jarak untuk setiap zona:
   - Zona 1: 0 - 3 km (contoh)
   - Zona 2: 3 - 5 km (contoh)
   - Zona 3: > 5 km (contoh)
3. **Kuota Per Zona** - Tentukan jumlah siswa yang diterima per zona


## 📐 Cara Kerja Sistem Zonasi

1. **Pendaftaran** - Calon siswa mengisi formulir dengan alamat lengkap
2. **Geocoding** - Sistem mengkonversi alamat menjadi koordinat GPS
3. **Perhitungan Jarak** - Sistem menghitung jarak dari rumah ke sekolah menggunakan rumus Haversine
4. **Penentuan Zona** - Siswa otomatis dimasukkan ke zona berdasarkan jarak
5. **Seleksi** - Sistem memprioritaskan zona 1, lalu zona 2, kemudian zona 3
6. **Ranking** - Dalam satu zona, siswa diranking berdasarkan jarak terdekat dan nilai

## 📚 Dokumentasi Laravel

Laravel memiliki [dokumentasi](https://laravel.com/docs) yang lengkap dan menyeluruh, serta video tutorial library yang membuat Anda mudah memulai dengan framework ini.

Anda juga dapat mencoba [Laravel Bootcamp](https://bootcamp.laravel.com), di mana Anda akan dipandu membangun aplikasi Laravel modern dari awal.

Jika tidak ingin membaca, [Laracasts](https://laracasts.com) dapat membantu. Laracasts berisi ribuan video tutorial tentang berbagai topik termasuk Laravel, modern PHP, unit testing, dan JavaScript.

## 🤝 Kontribusi

Kontribusi selalu diterima! Silakan buat pull request atau laporkan issue jika menemukan bug.

Untuk berkontribusi pada Laravel framework, silakan lihat [panduan kontribusi Laravel](https://laravel.com/docs/contributions).

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).


## 🙏 Acknowledgments

- Google Maps API untuk sistem pemetaan
- Laravel Framework untuk fondasi aplikasi yang solid
- Kementerian Pendidikan dan Kebudayaan untuk regulasi PPDB zonasi

---

Dikembangkan dengan ❤️ untuk pendidikan yang lebih baik dan merata
