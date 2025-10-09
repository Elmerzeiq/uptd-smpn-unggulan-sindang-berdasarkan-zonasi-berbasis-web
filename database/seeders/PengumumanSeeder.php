<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengumuman;
use App\Models\User;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user admin
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'nama_lengkap' => 'Admin SPMB',
                'email' => 'admin@smpnunggulansindang.sch.id',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        // Data pengumuman umum
        $pengumumanUmum = [
            [
                'judul' => 'Selamat Datang di SPMB SMPN Unggulan Sindang',
                'isi' => $this->getWelcomeContent(),
                'tipe' => 'info',
                'target_penerima' => 'semua',
                'tanggal' => now(),
                'aktif' => true,
                'priority' => 1,
            ],
            [
                'judul' => 'Jadwal Verifikasi Berkas SPMB',
                'isi' => $this->getScheduleContent(),
                'tipe' => 'warning',
                'target_penerima' => 'calon_siswa',
                'tanggal' => now()->addDays(1),
                'aktif' => true,
                'priority' => 2,
            ],
            [
                'judul' => 'Informasi Daftar Ulang Siswa Baru',
                'isi' => $this->getDaftarUlangContent(),
                'tipe' => 'success',
                'target_penerima' => 'siswa_diterima',
                'tanggal' => now()->addDays(7),
                'aktif' => true,
                'priority' => 1,
            ],
            [
                'judul' => 'Motivasi untuk Siswa',
                'isi' => $this->getMotivationContent(),
                'tipe' => 'info',
                'target_penerima' => 'siswa_ditolak',
                'tanggal' => now()->addDays(3),
                'aktif' => true,
                'priority' => 3,
            ],
            [
                'judul' => 'Pengumuman Libur Semester',
                'isi' => $this->getLiburContent(),
                'tipe' => 'info',
                'target_penerima' => 'semua',
                'tanggal' => now()->addMonths(2),
                'aktif' => false,
                'priority' => 5,
            ]
        ];

        // Data pengumuman hasil PPDB
        $pengumumanHasil = [
            [
                'judul' => 'Pengumuman Hasil Seleksi SPMB SMPN Unggulan Sindang Tahun ' . date('Y'),
                'isi' => $this->getHasilUmumContent(),
                'tipe' => 'pengumuman_hasil',
                'target_penerima' => 'semua',
                'tanggal' => now()->addDays(5),
                'aktif' => true,
                'priority' => 1,
            ],
            [
                'judul' => 'Selamat kepada Siswa yang Diterima!',
                'isi' => $this->getHasilDiterimaContent(),
                'tipe' => 'pengumuman_hasil',
                'target_penerima' => 'siswa_diterima',
                'tanggal' => now()->addDays(5),
                'aktif' => true,
                'priority' => 1,
            ],
            [
                'judul' => 'Pesan untuk Siswa yang Belum Berhasil',
                'isi' => $this->getHasilDitolakContent(),
                'tipe' => 'pengumuman_hasil',
                'target_penerima' => 'siswa_ditolak',
                'tanggal' => now()->addDays(5),
                'aktif' => true,
                'priority' => 1,
            ]
        ];

        // Insert pengumuman umum
        foreach ($pengumumanUmum as $data) {
            Pengumuman::create([
                ...$data,
                'user_id' => $admin->id,
                'views_count' => rand(10, 100),
            ]);
        }

        // Insert pengumuman hasil
        foreach ($pengumumanHasil as $data) {
            Pengumuman::create([
                ...$data,
                'user_id' => $admin->id,
                'views_count' => rand(50, 200),
            ]);
        }

        $this->command->info('✅ Pengumuman seeder completed successfully!');
        $this->command->info('📊 Created: ' . count($pengumumanUmum) . ' pengumuman umum');
        $this->command->info('🏆 Created: ' . count($pengumumanHasil) . ' pengumuman hasil');
    }

    private function getWelcomeContent(): string
    {
        return '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2><strong>🎓 SELAMAT DATANG DI SPMB ONLINE</strong></h2>
                <h3>SMPN UNGGULAN SINDANG</h3>
            </div>

            <p>Assalamu\'alaikum Warahmatullahi Wabarakatuh</p>
            <p><strong>Selamat datang di sistem SPMB Online SMPN Unggulan Sindang!</strong></p>

            <div style="background-color: #e7f3ff; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #0066cc;">📋 Langkah-langkah SPMB:</h4>
                <ol>
                    <li>Lengkapi biodata pribadi dan orang tua</li>
                    <li>Upload berkas persyaratan</li>
                    <li>Tunggu proses verifikasi</li>
                    <li>Pantau pengumuman hasil seleksi</li>
                    <li>Download bukti hasil jika sudah diumumkan</li>
                </ol>
            </div>

            <div style="background-color: #fff9e6; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #cc6600;">⚠️ PENTING!</h4>
                <ul>
                    <li>Pastikan data yang diinput benar dan valid</li>
                    <li>Upload berkas dengan format dan ukuran yang sesuai</li>
                    <li>Pantau terus pengumuman terbaru</li>
                    <li>Hubungi panitia jika ada kendala</li>
                </ul>
            </div>

            <p style="text-align: center; margin-top: 30px;">
                <strong>Semoga sukses dalam proses SPMB!</strong><br>
                <em>Panitia SPMB SMPN Unggulan Sindang</em><br>
                📱 Instagram: @smpnunggulansindang.official
            </p>
        ';
    }

    private function getScheduleContent(): string
    {
        return '
            <p><strong>Kepada Yth. Seluruh Calon Siswa SMPN Unggulan Sindang</strong></p>

            <p>Berikut adalah jadwal verifikasi berkas SPMB yang harus diperhatikan:</p>

            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; margin: 20px 0;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Kegiatan</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Tanggal</th>
                        <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px;">Pendaftaran Online</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . now()->format('d M') . ' - ' . now()->addDays(14)->format('d M Y') . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">24 Jam</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px;">Verifikasi Berkas</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . now()->addDays(15)->format('d M') . ' - ' . now()->addDays(17)->format('d M Y') . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">08:00 - 14:00</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px;">Pengumuman Hasil</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . now()->addDays(20)->format('d M Y') . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">10:00</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px;">Daftar Ulang</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">' . now()->addDays(22)->format('d M') . ' - ' . now()->addDays(24)->format('d M Y') . '</td>
                        <td style="border: 1px solid #ddd; padding: 12px;">08:00 - 14:00</td>
                    </tr>
                </tbody>
            </table>

            <div style="background-color: #fef2f2; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 5px solid #ef4444;">
                <h4 style="color: #991b1b;">⚠️ PERHATIAN!</h4>
                <p>Pastikan semua berkas sudah diupload sebelum batas waktu. Berkas yang tidak lengkap akan mempengaruhi proses seleksi.</p>
            </div>

            <p><strong>Terima kasih atas perhatiannya.</strong></p>
            <p><strong>Panitia SPMB SMPN Unggulan Sindang</strong></p>
        ';
    }

    private function getDaftarUlangContent(): string
    {
        return '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: #00aa00;"><strong>🎉 SELAMAT! ANDA DITERIMA! 🎉</strong></h2>
            </div>

            <p><strong>Kepada Yth. Siswa yang telah dinyatakan DITERIMA</strong></p>

            <p>Selamat atas diterimanya Anda sebagai siswa baru SMPN Unggulan Sindang! Langkah selanjutnya adalah melakukan daftar ulang dengan ketentuan sebagai berikut:</p>

            <div style="background-color: #e7ffe7; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #00aa00;">📅 JADWAL DAFTAR ULANG:</h4>
                <ul>
                    <li><strong>Tanggal:</strong> ' . now()->addDays(22)->format('d M Y') . ' - ' . now()->addDays(24)->format('d M Y') . '</li>
                    <li><strong>Waktu:</strong> 08:00 - 14:00 WIB</li>
                    <li><strong>Tempat:</strong> SMPN Unggulan Sindang</li>
                </ul>
            </div>

            <h4>📋 DOKUMEN YANG HARUS DIBAWA:</h4>
            <ol>
                <li>Bukti hasil penerimaan (download dari sistem)</li>
                <li>Ijazah SD/MI asli dan fotokopi</li>
                <li>SKHUN SD/MI asli dan fotokopi</li>
                <li>Kartu Keluarga asli dan fotokopi</li>
                <li>Akta Kelahiran asli dan fotokopi</li>
                <li>Kartu Identitas Anak (KIA) atau KTP jika sudah ada</li>
                <li>Pas foto terbaru 3x4 sebanyak 6 lembar</li>
                <li>Map plastik warna biru</li>
            </ol>

            <div style="background-color: #fff9e6; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #cc6600;">💰 BIAYA DAFTAR ULANG:</h4>
                <p>Informasi mengenai biaya akan disampaikan saat daftar ulang. Siapkan uang tunai secukupnya.</p>
            </div>

            <p style="text-align: center; color: #00aa00; font-size: 18px; font-weight: bold;">
                Sekali lagi SELAMAT dan selamat bergabung di keluarga besar SMPN Unggulan Sindang! 🎓
            </p>
        ';
    }

    private function getMotivationContent(): string
    {
        return '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: #0066cc;"><strong>💪 TETAP SEMANGAT & JANGAN MENYERAH!</strong></h2>
            </div>

            <p><strong>Kepada Adik-adik yang belum berhasil pada seleksi SPMB kali ini</strong></p>

            <p>Kami dari panitia SPMB SMPN Unggulan Sindang ingin menyampaikan bahwa hasil seleksi bukan merupakan akhir dari segalanya. <strong>Masih banyak kesempatan dan jalan menuju kesuksesan!</strong></p>

            <div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #0066cc;">🌟 PESAN MOTIVASI:</h4>
                <blockquote style="font-style: italic; color: #0066cc; border-left: 4px solid #0066cc; padding-left: 15px;">
                    <p>"Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas."</p>
                    <p>"Setiap penolakan membawa kamu selangkah lebih dekat dengan penerimaan yang tepat."</p>
                    <p>"Kesuksesan tidak ditentukan oleh satu kesempatan, tapi oleh ketekunan dalam berusaha."</p>
                </blockquote>
            </div>

            <h4>🎯 LANGKAH SELANJUTNYA:</h4>
            <ol>
                <li><strong>Evaluasi Diri:</strong> Lihat apa yang bisa diperbaiki untuk masa depan</li>
                <li><strong>Cari Alternatif:</strong> Masih banyak sekolah bagus lainnya</li>
                <li><strong>Tingkatkan Prestasi:</strong> Gunakan waktu untuk belajar lebih giat</li>
                <li><strong>Jangan Menyerah:</strong> Terus berusaha dan berdoa</li>
                <li><strong>Minta Dukungan:</strong> Berbicara dengan orang tua dan guru</li>
            </ol>

            <div style="background-color: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
                <h4 style="color: #0066cc;">🏆 INGAT SELALU:</h4>
                <p style="font-size: 16px; color: #0066cc;">
                    Sekolah hanyalah tempat belajar. Yang terpenting adalah <strong>semangat belajar</strong> dan <strong>tekad untuk sukses</strong> yang ada dalam diri kalian!
                </p>
            </div>

            <p style="text-align: center; margin-top: 30px;">
                <strong>Tetap semangat dan jangan pernah menyerah!</strong><br>
                <em>Panitia SPMB SMPN Unggulan Sindang</em><br>
                ❤️ Kami mendoakan kesuksesan kalian di masa depan
            </p>
        ';
    }

    private function getLiburContent(): string
    {
        return '
            <p><strong>Kepada Yth. Seluruh Siswa SMPN Unggulan Sindang</strong></p>

            <p>Dengan ini kami sampaikan bahwa dalam rangka libur semester, kegiatan belajar mengajar akan diliburkan mulai:</p>

            <div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
                <h4 style="color: #0066cc;">📅 JADWAL LIBUR SEMESTER</h4>
                <p><strong>Tanggal:</strong> ' . now()->addMonths(2)->format('d M Y') . ' s/d ' . now()->addMonths(2)->addWeeks(2)->format('d M Y') . '</p>
                <p><strong>Masuk kembali:</strong> ' . now()->addMonths(2)->addWeeks(2)->addDay()->format('d M Y') . '</p>
            </div>

            <h4>📋 KEGIATAN SELAMA LIBUR:</h4>
            <ul>
                <li>Istirahat dan refreshing</li>
                <li>Mengerjakan tugas liburan (jika ada)</li>
                <li>Membantu orang tua di rumah</li>
                <li>Membaca buku-buku pengetahuan</li>
                <li>Menjaga kesehatan</li>
            </ul>

            <p>Selamat berlibur dan sampai bertemu kembali di semester baru!</p>
            <p><strong>Panitia SPMB SMPN Unggulan Sindang</strong></p>
        ';
    }

    private function getHasilUmumContent(): string
    {
        return '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2><strong>PENGUMUMAN HASIL SELEKSI SPMB</strong></h2>
                <h3>SMPN UNGGULAN SINDANG</h3>
                <h4>TAHUN PELAJARAN ' . date('Y') . '/' . (date('Y') + 1) . '</h4>
            </div>

            <p>Assalamu\'alaikum Warahmatullahi Wabarakatuh</p>
            <p><strong>Puji syukur kami panjatkan kepada Allah SWT</strong></p>

            <p>Berdasarkan hasil seleksi yang telah dilaksanakan dengan penuh kehati-hatian dan transparansi, dengan ini diumumkan hasil Sistem Penerimaan Murid Baru (SPMB) SMPN Unggulan Sindang:</p>

            <div style="background-color: #e7ffe7; padding: 20px; border-radius: 8px; margin: 20px 0; border: 2px solid #00aa00; text-align: center;">
                <h3 style="color: #00aa00;">🎉 HASIL SELEKSI TELAH DIUMUMKAN! 🎉</h3>
                <p style="font-size: 16px;">Silakan login ke akun masing-masing untuk melihat hasil individual dan download bukti hasil dalam format PDF.</p>
            </div>

            <div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #0066cc;">📋 UNTUK SISWA YANG DITERIMA:</h4>
                <ol>
                    <li>Download bukti hasil penerimaan dari dashboard akun Anda</li>
                    <li>Lakukan daftar ulang sesuai jadwal yang telah ditentukan</li>
                    <li>Siapkan semua dokumen yang diperlukan</li>
                    <li>Datang ke sekolah dengan membawa bukti hasil dan dokumen</li>
                </ol>
            </div>

            <div style="background-color: #fef2f2; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #991b1b;">💪 UNTUK SISWA YANG BELUM BERHASIL:</h4>
                <p>Jangan berkecil hati! Masih ada kesempatan di sekolah lain. Tetap semangat dan jangan menyerah dalam mengejar cita-cita. Download bukti hasil sebagai dokumentasi.</p>
            </div>

            <div style="background-color: #fff9e6; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #cc6600;">📞 INFORMASI LEBIH LANJUT:</h4>
                <ul>
                    <li><strong>Website:</strong> www.smpnunggulansindang.sch.id</li>
                    <li><strong>Instagram:</strong> @smpnunggulansindang.official</li>
                    <li><strong>Email:</strong> info@smpnunggulansindang.sch.id</li>
                    <li><strong>Telepon:</strong> (0234) 123-4567</li>
                </ul>
            </div>

            <p style="text-align: center; margin-top: 30px;">
                <strong>Terima kasih atas partisipasi dalam SPMB SMPN Unggulan Sindang</strong><br>
                <em>Wassalamu\'alaikum Warahmatullahi Wabarakatuh</em><br><br>
                <strong>Panitia SPMB SMPN Unggulan Sindang</strong>
            </p>
        ';
    }

    private function getHasilDiterimaContent(): string
    {
        return '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: #00aa00;"><strong>🎉 SELAMAT! ANDA DITERIMA! 🎉</strong></h2>
                <h3>SMPN UNGGULAN SINDANG</h3>
            </div>

            <div style="background-color: #e7ffe7; padding: 25px; border-radius: 12px; margin: 20px 0; border: 3px solid #00aa00; text-align: center;">
                <h3 style="color: #00aa00; margin-bottom: 15px;">🏆 SELAMAT! 🏆</h3>
                <p style="font-size: 18px; color: #00aa00; font-weight: bold;">
                    Anda telah dinyatakan DITERIMA sebagai siswa baru SMPN Unggulan Sindang Tahun Pelajaran ' . date('Y') . '/' . (date('Y') + 1) . '
                </p>
            </div>

            <h4>📋 LANGKAH SELANJUTNYA:</h4>
            <ol style="font-size: 16px; line-height: 1.8;">
                <li><strong>Download Bukti Hasil:</strong> Simpan dan cetak sebagai bukti penerimaan resmi</li>
                <li><strong>Persiapkan Dokumen:</strong> Siapkan semua berkas yang diperlukan untuk daftar ulang</li>
                <li><strong>Pantau Jadwal:</strong> Tunggu pengumuman jadwal daftar ulang</li>
                <li><strong>Daftar Ulang:</strong> Datang ke sekolah sesuai jadwal dengan membawa bukti dan dokumen</li>
            </ol>

            <div style="background-color: #fff9e6; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 5px solid #cc6600;">
                <h4 style="color: #cc6600;">⚠️ SANGAT PENTING!</h4>
                <ul>
                    <li>Download dan simpan bukti hasil penerimaan dari dashboard Anda</li>
                    <li>Bukti ini WAJIB dibawa saat daftar ulang ke sekolah</li>
                    <li>Jangan sampai terlewat jadwal daftar ulang</li>
                    <li>Hubungi panitia jika ada kendala atau pertanyaan</li>
                </ul>
            </div>

            <div style="background-color: #f0f9ff; padding: 25px; border-radius: 12px; margin: 20px 0; text-align: center;">
                <h4 style="color: #0066cc;">🎓 SELAMAT BERGABUNG!</h4>
                <p style="font-size: 16px; color: #0066cc;">
                    Selamat bergabung di keluarga besar SMPN Unggulan Sindang!<br>
                    Semoga menjadi siswa yang berprestasi dan berakhlak mulia.
                </p>
            </div>

            <p style="text-align: center; margin-top: 30px; font-size: 18px; color: #00aa00; font-weight: bold;">
                Sekali lagi, SELAMAT dan selamat menempuh pendidikan di SMPN Unggulan Sindang! 🌟
            </p>
        ';
    }

    private function getHasilDitolakContent(): string
    {
        return '
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: #cc6600;"><strong>💪 TETAP SEMANGAT!</strong></h2>
                <h3>SMPN UNGGULAN SINDANG</h3>
            </div>

            <div style="background-color: #fff2e7; padding: 25px; border-radius: 12px; margin: 20px 0; border: 2px solid #cc6600; text-align: center;">
                <h4 style="color: #cc6600;">💫 UNTUK ANDA YANG LUAR BIASA</h4>
                <p style="font-size: 16px; color: #cc6600;">
                    Maaf, Anda belum berhasil pada seleksi SPMB kali ini.<br>
                    Namun, <strong>jangan pernah menyerah!</strong> Ini bukan akhir dari perjalanan Anda.
                </p>
            </div>

            <h4>💪 PESAN KHUSUS UNTUK ANDA:</h4>
            <ul style="font-size: 16px; line-height: 1.8;">
                <li><strong>Jangan Patah Semangat:</strong> Hasil ini bukan menentukan masa depan Anda</li>
                <li><strong>Masih Ada Kesempatan:</strong> Banyak sekolah bagus lainnya yang menanti</li>
                <li><strong>Tetap Belajar:</strong> Gunakan ini sebagai motivasi untuk lebih giat</li>
                <li><strong>Evaluasi Diri:</strong> Lihat apa yang bisa diperbaiki ke depannya</li>
                <li><strong>Minta Dukungan:</strong> Berbicara dengan keluarga dan guru</li>
            </ul>

            <div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #0066cc;">🎯 ALTERNATIF YANG BISA DITEMPUH:</h4>
                <ol>
                    <li>Cari informasi sekolah lain yang masih membuka pendaftaran</li>
                    <li>Persiapkan diri lebih baik untuk kesempatan berikutnya</li>
                    <li>Tingkatkan prestasi akademik dan non-akademik</li>
                    <li>Konsultasikan pilihan terbaik dengan orang tua dan guru</li>
                    <li>Tetap optimis dan jangan menyerah pada impian</li>
                </ol>
            </div>

            <div style="background-color: #f9f9f9; padding: 25px; border-radius: 12px; margin: 20px 0; text-align: center; border-left: 5px solid #cc6600;">
                <h4 style="color: #cc6600;">🌟 KATA MOTIVASI</h4>
                <blockquote style="font-style: italic; color: #666; font-size: 16px; line-height: 1.6;">
                    <p>"Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas."</p>
                    <p>"Setiap penolakan membawa Anda selangkah lebih dekat dengan penerimaan yang tepat."</p>
                    <p>"Kesuksesan tidak ditentukan oleh satu kesempatan, tapi oleh ketekunan dalam berusaha."</p>
                </blockquote>
            </div>

            <p style="text-align: center; margin-top: 30px; font-size: 18px; color: #cc6600; font-weight: bold;">
                Tetap semangat dan jangan menyerah! Sukses menanti di depan! 🌟
            </p>

            <p style="text-align: center; margin-top: 20px; color: #666;">
                <em>Kami mendoakan kesuksesan Anda di masa depan</em><br>
                <strong>Panitia SPMB SMPN Unggulan Sindang</strong>
            </p>
        ';
    }
}
