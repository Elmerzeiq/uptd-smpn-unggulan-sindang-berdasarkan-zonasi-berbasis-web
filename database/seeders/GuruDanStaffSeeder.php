<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GuruDanStaff;

class GuruDanStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kepala Sekolah
        GuruDanStaff::create([
            'nama' => 'Dr. Ahmad Supriyadi, S.Pd., M.Pd.',
            'nip' => '196501011988031001',
            'jabatan' => 'Kepala Sekolah',
            'kategori' => 'kepala_sekolah',
            'sambutan' => 'Assalamualaikum Wr. Wb.

Selamat datang di website resmi SMP Negeri 1 Indramayu. Sebagai Kepala Sekolah, saya dengan bangga mempersembahkan lembaga pendidikan yang telah berdedikasi untuk mencerdaskan anak bangsa.

SMP Negeri 1 Indramayu berkomitmen untuk memberikan pendidikan berkualitas yang tidak hanya mengembangkan aspek akademik, tetapi juga membentuk karakter siswa yang berakhlak mulia, kreatif, dan berdaya saing global.

Dengan didukung oleh tenaga pendidik dan kependidikan yang profesional, serta sarana dan prasarana yang memadai, kami terus berupaya mewujudkan visi sekolah untuk menjadi lembaga pendidikan yang unggul dan berkarakter.

Mari bersama-sama kita wujudkan generasi penerus bangsa yang cerdas, beriman, dan bertakwa kepada Tuhan Yang Maha Esa.

Wassalamualaikum Wr. Wb.

Dr. Ahmad Supriyadi, S.Pd., M.Pd.
Kepala SMP Negeri 1 Indramayu',
            'image' => 'kepala-sekolah.jpg',
            'is_active' => true
        ]);

        // Guru-guru
        $gurus = [
            [
                'nama' => 'Siti Nurhaliza, S.Pd.',
                'nip' => '197203051998032001',
                'jabatan' => 'Guru Bahasa Indonesia & Wali Kelas VII A',
                'kategori' => 'guru'
            ],
            [
                'nama' => 'Muhammad Ridwan, S.Pd.',
                'nip' => '198105102005011003',
                'jabatan' => 'Guru Matematika & Wali Kelas VIII B',
                'kategori' => 'guru'
            ],
            [
                'nama' => 'Dewi Sartika, S.Pd.',
                'nip' => '198909152014062001',
                'jabatan' => 'Guru IPA & Wali Kelas IX C',
                'kategori' => 'guru'
            ],
            [
                'nama' => 'Bambang Suryanto, S.Pd.',
                'nip' => '197812201999031002',
                'jabatan' => 'Guru Pendidikan Jasmani',
                'kategori' => 'guru'
            ],
            [
                'nama' => 'Rina Kusuma, S.Pd.',
                'nip' => '198503252010012014',
                'jabatan' => 'Guru Bahasa Inggris',
                'kategori' => 'guru'
            ]
        ];

        foreach ($gurus as $guru) {
            GuruDanStaff::create(array_merge($guru, [
                'sambutan' => null,
                'image' => null,
                'is_active' => true
            ]));
        }

        // Staff
        $staffs = [
            [
                'nama' => 'Andi Pratama, S.E.',
                'nip' => '198601152009021001',
                'jabatan' => 'Kepala Tata Usaha',
                'kategori' => 'staff'
            ],
            [
                'nama' => 'Maya Sari',
                'nip' => null,
                'jabatan' => 'Staff Administrasi',
                'kategori' => 'staff'
            ],
            [
                'nama' => 'Joko Susilo',
                'nip' => null,
                'jabatan' => 'Penjaga Sekolah',
                'kategori' => 'staff'
            ],
            [
                'nama' => 'Sri Mulyani',
                'nip' => null,
                'jabatan' => 'Petugas Perpustakaan',
                'kategori' => 'staff'
            ]
        ];

        foreach ($staffs as $staff) {
            GuruDanStaff::create(array_merge($staff, [
                'sambutan' => null,
                'image' => null,
                'is_active' => true
            ]));
        }
    }
}
