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

class PendaftarTidakLolosExport implements
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
        $data = User::with(['biodata', 'berkas'])
            ->where('role', 'siswa')
            ->whereIn('status_pendaftaran', [
                'tidak_lulus_seleksi',
                'berkas_tidak_lengkap',
                'tidak_memenuhi_syarat',
                'ditolak'
            ])
            ->orderBy('nama_lengkap')
            ->get();

        $collection = collect();
        foreach ($data as $user) {
            // Determine reason for not passing
            $alasanTidakLolos = match ($user->status_pendaftaran) {
                'tidak_lulus_seleksi' => 'Tidak Lulus Seleksi',
                'berkas_tidak_lengkap' => 'Berkas Tidak Lengkap',
                'tidak_memenuhi_syarat' => 'Tidak Memenuhi Syarat',
                'ditolak' => 'Ditolak',
                default => 'Tidak Diketahui'
            };

            $collection->push([
                $user->no_pendaftaran,
                $user->nama_lengkap,
                $user->nisn ?? '-',
                $user->jalur_pendaftaran ?? '-',
                ucwords(str_replace('_', ' ', $user->status_pendaftaran)),
                $alasanTidakLolos,
                $user->biodata->asal_sekolah ?? '-',
                $user->biodata->tempat_lahir ?? '-',
                $user->biodata->tanggal_lahir ? $user->biodata->tanggal_lahir->format('d-m-Y') : '-',
                $user->berkas ? ($user->berkas->status_verifikasi ?? 'Belum Diverifikasi') : 'Tidak Ada',
                $user->keterangan_penolakan ?? '-',
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
            'Alasan Tidak Lolos',
            'Asal Sekolah',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Status Berkas',
            'Keterangan Penolakan',
        ];
    }

    public function title(): string
    {
        return 'Siswa Tidak Lolos';
    }

    public function startRow(): int
    {
        return 11;
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A10:K10')->applyFromArray([
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

        // Style untuk semua data (akan diterapkan setelah data ditambahkan)
        return $sheet;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert header rows
                $sheet->insertNewRowBefore(1, 9);

                // Header kop surat
                $sheet->mergeCells('A2:K2')->setCellValue('A2', 'PEMERINTAH KABUPATEN INDRAMAYU');
                $sheet->mergeCells('A3:K3')->setCellValue('A3', 'SMP UNGGULAN SINDANG');
                $sheet->mergeCells('A4:K4')->setCellValue('A4', 'Jl. Pendidikan No. 123, Sindang, Kabupaten Indramayu, Jawa Barat');
                $sheet->mergeCells('A5:K5')->setCellValue('A5', 'Telepon: (021) 12345678 | Email: admin@smpunggulansindang.sch.id');

                // Garis pemisah kop
                $sheet->getStyle('A6:K6')->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Judul laporan
                $sheet->mergeCells('A7:K7')->setCellValue('A7', 'DAFTAR SISWA TIDAK LOLOS');
                $sheet->mergeCells('A8:K8')->setCellValue('A8', 'Tahun Ajaran: 2025/2026');
                $sheet->mergeCells('A9:K9')->setCellValue('A9', 'Tanggal Cetak: ' . now()->format('d M Y H:i'));

                // Style untuk kop surat
                $sheet->getStyle('A2:A4')->getFont()->setSize(12);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12);

                // Center alignment untuk header
                $sheet->getStyle('A2:K9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Style untuk data
                $lastRow = $sheet->getHighestRow();
                if ($lastRow > 10) {
                    $sheet->getStyle('A11:K' . $lastRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);
                }

                // Footer
                $footerRow = $lastRow + 2;
                $sheet->mergeCells("A{$footerRow}:K{$footerRow}")
                    ->setCellValue("A{$footerRow}", 'Dokumen ini dicetak secara otomatis melalui SPMB Online SMP Unggulan Sindang');
                $sheet->getStyle("A{$footerRow}:K{$footerRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Page setup
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Auto-size columns
                foreach (range('A', 'K') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];

        // Logo Pemda (kiri)
        if (file_exists(public_path('kaiadmin/assets/img/kaiadmin/logoindramayu.png'))) {
            $pemda = new Drawing();
            $pemda->setName('Logo Pemda');
            $pemda->setDescription('Logo Pemda');
            $pemda->setPath(public_path('kaiadmin/assets/img/kaiadmin/logoindramayu.png'));
            $pemda->setHeight(60);
            $pemda->setCoordinates('A1');
            $pemda->setOffsetX(10);
            $pemda->setOffsetY(5);
            $drawings[] = $pemda;
        }

        // Logo SMP (kanan)
        if (file_exists(public_path('kaiadmin/assets/img/kaiadmin/favicon.png'))) {
            $smp = new Drawing();
            $smp->setName('Logo SMP');
            $smp->setDescription('Logo SMP');
            $smp->setPath(public_path('kaiadmin/assets/img/kaiadmin/favicon.png'));
            $smp->setHeight(60);
            $smp->setCoordinates('K1');
            $smp->setOffsetX(5);
            $smp->setOffsetY(5);
            $drawings[] = $smp;
        }

        return $drawings;
    }
}
