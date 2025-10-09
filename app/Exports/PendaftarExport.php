<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Events\AfterSheet;

class PendaftarExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle,
    WithEvents,
    WithDrawings,
    WithStartRow
{
    public function collection()
    {
        $data = User::with(['biodata', 'orangTua', 'wali', 'berkas'])
            ->where('role', 'siswa')
            ->get();

        $collection = collect();
        foreach ($data as $i => $user) {
            $collection->push([
                $i + 1,
                $user->no_pendaftaran,
                $user->nama_lengkap,
                $user->nisn ?? '-',
                $user->biodata->tempat_lahir  ?? '-',
                $user->biodata->tgl_lahir ?? '-',
                $user->biodata->jenis_kelamin ?? '-',
                $user->biodata->asal_sekolah ?? '-',
                $user->jalur_pendaftaran ?? '-',
                ucwords(str_replace('_', ' ', $user->status_pendaftaran)),
                optional($user->tanggal_daftar_ulang_selesai)?->format('d M Y') ?? '-',
                $user->berkas->status_verifikasi ?? 'Tidak Ada',
                $user->orangTua->nama_ayah ?? '-',
                $user->orangTua->pekerjaan_ayah ?? '-',
                $user->orangTua->nama_ibu ?? '-',
                $user->orangTua->pekerjaan_ibu ?? '-',
                $user->wali->nama_wali ?? '-',
                $user->wali->pekerjaan_wali ?? '-',
                now()->format('d M Y H:i'),
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Nama Lengkap',
            'NISN',
            'Tempat, Tanggal Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Nama Sekolah Asal',
            'Jalur Pilihan',
            'Status Pendaftaran',
            'Tanggal Daftar Ulang',
            'Status Berkas',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'Tanggal Cetak',
        ];
    }

    public function title(): string
    {
        return 'Laporan Pendaftar';
    }

    public function startRow(): int
    {
        return 11; // Header mulai dari baris ke-11
    }

    public function styles(Worksheet $sheet)
    {
        // Warna dan gaya header
        $sheet->getStyle('A11:S11')->applyFromArray([
            // 'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => [
                'fillType' => 'solid',
                // 'startColor' => ['argb' => 'FF0070C0'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sisipkan 10 baris untuk kop surat
                $sheet->insertNewRowBefore(1, 10);

                // KOP SURAT
                $sheet->mergeCells('A1:S1')->setCellValue('A1', 'PEMERINTAH KABUPATEN INDRAMAYU');
                $sheet->mergeCells('A2:S2')->setCellValue('A2', 'SMP UNGGULAN SINDANG');
                $sheet->mergeCells('A3:S3')->setCellValue('A3', 'Jl. Pendidikan No. 123, Sindang, Kabupaten Indramayu, Jawa Barat');
                $sheet->mergeCells('A4:S4')->setCellValue('A4', 'Telepon: (021) 12345678 | Email: admin@smpunggulansindang.sch.id');

                // Judul Laporan
                $sheet->mergeCells('A6:S6')->setCellValue('A6', 'LAPORAN SEMUA PENDAFTAR SPMB');
                $sheet->mergeCells('A7:S7')->setCellValue('A7', 'Tahun Ajaran: ' . now()->year . '/' . now()->addYear()->year);
                $sheet->mergeCells('A8:S8')->setCellValue('A8', 'Tanggal Cetak: ' . now()->format('d M Y H:i'));

                // Garis kop surat (di bawah baris 8)
                $sheet->getStyle('A5:S5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Rata tengah kop
                $sheet->getStyle('A1:A8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(12);

                // Landscape A4 dan Fit to Page
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Kolom auto size
                foreach (range('A', 'S') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];

        // Logo kiri
        $logoKiri = new Drawing();
        $logoKiri->setName('Logo Pemda');
        $logoKiri->setDescription('Logo Pemda');
        $logoKiri->setPath(public_path('kaiadmin/assets/img/kaiadmin/logoindramayu.png'));
        $logoKiri->setHeight(80);
        $logoKiri->setCoordinates('A1');
        $logoKiri->setOffsetX(10);
        $logoKiri->setOffsetY(5);
        $drawings[] = $logoKiri;

        // Logo kanan
        $logoKanan = new Drawing();
        $logoKanan->setName('Logo SMP');
        $logoKanan->setDescription('Logo SMP');
        $logoKanan->setPath(public_path('kaiadmin/assets/img/kaiadmin/favicon.png'));
        $logoKanan->setHeight(80);
        $logoKanan->setCoordinates('S1');
        $logoKanan->setOffsetX(-10);
        $logoKanan->setOffsetY(5);
        $drawings[] = $logoKanan;

        return $drawings;
    }
}
