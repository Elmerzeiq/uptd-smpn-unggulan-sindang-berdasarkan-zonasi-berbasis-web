<?php
// app/Helpers/PpdbHelper.php - Complete version

namespace App\Helpers;

class PpdbHelper
{
    public static function getJalurOptions()
    {
        return [
            'domisili' => 'Zonasi Domisili',
            'prestasi_akademik' => 'Prestasi Akademik',
            'prestasi_non_akademik' => 'Prestasi Non-Akademik',
            'prestasi_rapor' => 'Prestasi Rapor',
            'afirmasi_ketm' => 'Afirmasi Ekonomi Tidak Mampu',
            'afirmasi_disabilitas' => 'Afirmasi Disabilitas',
            'mutasi_ortu' => 'Mutasi Orang Tua',
            'mutasi_guru' => 'Mutasi Guru/Tendik'
        ];
    }

    public static function getStatusOptions()
    {
        return [
            'akun_terdaftar' => 'Akun Terdaftar',
            'menunggu_kelengkapan_data' => 'Menunggu Kelengkapan Data',
            'menunggu_verifikasi_berkas' => 'Menunggu Verifikasi Berkas',
            'berkas_tidak_lengkap' => 'Berkas Tidak Lengkap',
            'berkas_diverifikasi' => 'Berkas Diverifikasi',
            'lulus_seleksi' => 'Lulus Seleksi',
            'tidak_lulus_seleksi' => 'Tidak Lulus Seleksi',
            'daftar_ulang_selesai' => 'Daftar Ulang Selesai'
        ];
    }

    public static function getStatusBadgeClass($status)
    {
        $classes = [
            'akun_terdaftar' => 'secondary',
            'menunggu_kelengkapan_data' => 'warning',
            'menunggu_verifikasi_berkas' => 'info',
            'berkas_tidak_lengkap' => 'danger',
            'berkas_diverifikasi' => 'primary',
            'lulus_seleksi' => 'success',
            'tidak_lulus_seleksi' => 'danger',
            'daftar_ulang_selesai' => 'success'
        ];

        return $classes[$status] ?? 'secondary';
    }

    public static function getPendidikanOptions()
    {
        return ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'];
    }

    public static function getPenghasilanOptions()
    {
        return [
            '<2juta' => 'Kurang dari 2 Juta',
            '2-5juta' => '2 - 5 Juta',
            '5-10juta' => '5 - 10 Juta',
            '>10juta' => 'Lebih dari 10 Juta'
        ];
    }

    public static function getAgamaOptions()
    {
        return ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'];
    }

    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public static function isEditAllowed($user)
    {
        // Students can't edit if their data has been verified
        $restrictedStatuses = [
            'berkas_diverifikasi',
            'lulus_seleksi',
            'tidak_lulus_seleksi',
            'daftar_ulang_selesai'
        ];

        return !in_array($user->status_pendaftaran, $restrictedStatuses);
    }

    public static function getCompletionPercentage($user)
    {
        $completion = 0;

        if ($user->biodata) $completion += 30;
        if ($user->orangTua) $completion += 30;
        if ($user->wali) $completion += 20;
        if ($user->berkas && $user->berkas->hasAnyFile()) $completion += 20;

        return $completion;
    }
}
