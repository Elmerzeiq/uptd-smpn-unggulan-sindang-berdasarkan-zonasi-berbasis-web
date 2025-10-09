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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class PendaftarDiterimaExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle,
    WithEvents,
    WithDrawings,
    WithStartRow
{
    protected $status;

    public function __construct($status = 'lulus_seleksi')
    {
        $this->status = $status;
    }

    public function collection()
    {
        $data = User::with(['biodata', 'berkas'])
            ->where('role', 'siswa')
            ->where('status_pendaftaran', $this->status)
            ->get();

        $collection = collect();
        foreach ($data as $user) {
            $collection->push([
                $user->no_pendaftaran,
                $user->nama_lengkap,
                $user->nisn ?? '-',
                $user->jalur_pendaftaran ?? '-',
                ucwords(str_replace('_', ' ', $user->status_pendaftaran)),
                $user->status_daftar_ulang ?? '-',
                optional($user->tanggal_daftar_ulang_selesai)?->format('d M Y') ?? '-',
                $user->catatan_daftar_ulang ?? '-',
                $user->biodata->asal_sekolah ?? '-',
                $user->berkas->status_verifikasi ?? 'Tidak Ada',
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'No. Pendaftaran',
            'Nama Lengkap',
            'NISN',
            'Jalur Pendaftaran',
            'Status Pendaftaran',
            'Status Daftar Ulang',
            'Tanggal Daftar Ulang Selesai',
            'Catatan Daftar Ulang',
            'Asal Sekolah',
            'Status Berkas',
        ];
    }

    public function title(): string
    {
        return 'Siswa Diterima';
    }

    public function startRow(): int
    {
        return 11;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A10:J10')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFD9D9D9'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 9);

                $sheet->mergeCells('A2:J2')->setCellValue('A2', 'PEMERINTAH KABUPATEN INDRAMAYU');
                $sheet->mergeCells('A3:J3')->setCellValue('A3', 'SMP UNGGULAN SINDANG');
                $sheet->mergeCells('A4:J4')->setCellValue('A4', 'Jl. Pendidikan No. 123, Sindang, Kabupaten Indramayu, Jawa Barat');
                $sheet->mergeCells('A5:J5')->setCellValue('A5', 'Telepon: (021) 12345678 | Email: admin@smpunggulansindang.sch.id');

                // Garis kop
                $sheet->getStyle('A6:J6')->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $sheet->mergeCells('A7:J7')->setCellValue('A7', 'DAFTAR SISWA DITERIMA');
                $sheet->mergeCells('A8:J8')->setCellValue('A8', 'Tahun Ajaran: 2025/2026');
                $sheet->mergeCells('A9:J9')->setCellValue('A9', 'Tanggal Cetak: ' . now()->format('d M Y H:i'));

                // Style teks kop
                $sheet->getStyle('A2:A4')->getFont()->setSize(12);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12);

                $sheet->getStyle('A2:J9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Footer catatan
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->mergeCells("A{$lastRow}:J{$lastRow}")
                    ->setCellValue("A{$lastRow}", 'Dokumen ini dicetak secara otomatis melalui SPMB Online SMP Unggulan Sindang');
                $sheet->getStyle("A{$lastRow}:J{$lastRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Page setup
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Auto kolom
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];

        $pemda = new Drawing();
        $pemda->setName('Logo Pemda');
        $pemda->setDescription('Logo Pemda');
        $pemda->setPath(public_path('kaiadmin/assets/img/kaiadmin/logoindramayu.png'));
        $pemda->setHeight(60);
        $pemda->setCoordinates('A1');
        $pemda->setOffsetX(10);
        $pemda->setOffsetY(5);
        $drawings[] = $pemda;

        $smp = new Drawing();
        $smp->setName('Logo SMP');
        $smp->setDescription('Logo SMP');
        $smp->setPath(public_path('kaiadmin/assets/img/kaiadmin/favicon.png'));
        $smp->setHeight(60);
        $smp->setCoordinates('J1');
        $smp->setOffsetX(5);
        $smp->setOffsetY(5);
        $drawings[] = $smp;

        return $drawings;
    }
}
