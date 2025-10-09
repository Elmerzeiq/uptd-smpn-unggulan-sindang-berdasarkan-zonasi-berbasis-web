<?php

namespace App\Exports;

use App\Models\DaftarUlang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DaftarUlangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DaftarUlang::with(['user.biodata', 'jadwalDaftarUlang'])->get()->map(function ($du, $index) {
            return [
                'No' => $index + 1,
                'No Daftar Ulang' => $du->nomor_daftar_ulang,
                'Nama' => $du->user->nama_lengkap,
                'Status Dokumen' => $du->status_text,
                'Status Pembayaran' => $du->status_pembayaran_text,
                'Jadwal' => optional($du->jadwalDaftarUlang)->nama_sesi ?? 'Belum Pilih',
                'Tanggal Jadwal' => optional(optional($du->jadwalDaftarUlang)->tanggal)->format('d M Y'),
                'Kehadiran' => $du->hadir_daftar_ulang ? 'Hadir' : 'Belum Hadir',
                'Waktu Kehadiran' => optional($du->waktu_kehadiran)->format('d M Y H:i'),
                'Status Akhir' => $du->status == 'selesai' ? 'Selesai' : 'Dalam Proses',
            ];
        });
    }

    public function headings(): array
    {
        return ["No", "No Daftar Ulang", "Nama", "Status Dokumen", "Status Pembayaran", "Jadwal", "Tanggal Jadwal", "Kehadiran", "Waktu Kehadiran", "Status Akhir"];
    }
}
