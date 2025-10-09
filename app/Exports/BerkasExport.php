<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\BerkasHelper;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class BerkasExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    ShouldAutoSize,
    WithEvents,
    WithDrawings,
    WithStartRow
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?? new Request();
    }

    /**
     * Mengambil koleksi data dasar berdasarkan filter.
     */
    public function collection()
    {
        $query = User::where('role', 'siswa')
            ->whereNotNull('jalur_pendaftaran')
            ->with(['berkas', 'biodata']);

        // Menerapkan filter dari request
        if ($this->request->filled('jalur_pendaftaran')) {
            $query->where('jalur_pendaftaran', $this->request->jalur_pendaftaran);
        }
        if ($this->request->filled('status_pendaftaran')) {
            $query->where('status_pendaftaran', $this->request->status_pendaftaran);
        }
        if ($this->request->filled('status_berkas')) {
            if ($this->request->status_berkas === 'ada_berkas') {
                $query->whereHas('berkas');
            } elseif ($this->request->status_berkas === 'belum_upload') {
                $query->whereDoesntHave('berkas');
            }
        }
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_lengkap', 'asc')->get();
    }

    /**
     * Mendefinisikan header untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'NISN',
            'Nama Lengkap',
            'Jalur Pendaftaran',
            'Status Pendaftaran',
            'Status Berkas',
            'Progress Berkas (%)',
            'Berkas Wajib Lengkap',
            'Total Berkas Terupload',
            'Total Berkas Diperlukan',
            'Tanggal Pendaftaran',
            'Tanggal Upload Berkas',
            'Catatan Verifikasi',
            'Detail Status Berkas',
        ];
    }

    /**
     * Memetakan setiap baris data ke format yang diinginkan.
     */
    public function map($user): array
    {
        static $no = 0;
        $no++;

        try {
            $progress = BerkasHelper::calculateBerkasProgress($user);
            if (!is_array($progress)) {
                $progress = $this->getDefaultProgress();
            }
            $progress = array_merge($this->getDefaultProgress(), $progress);
        } catch (\Exception $e) {
            Log::error('Error calculating berkas progress for user ' . $user->id . ': ' . $e->getMessage());
            $progress = $this->getDefaultProgress();
        }

        $berkasDetails = $this->getBerkasDetails($user);

        return [
            $no,
            $user->no_pendaftaran ?? '-',
            $user->nisn ?? '-',
            $user->nama_lengkap ?? '-',
            ucwords(str_replace('_', ' ', $user->jalur_pendaftaran ?? '')),
            ucwords(str_replace('_', ' ', $user->status_pendaftaran ?? '')),
            $user->berkas ? 'Sudah Upload' : 'Belum Upload',
            $progress['percentage'] ?? 0,
            ($progress['is_wajib_lengkap'] ?? false) ? 'Ya' : 'Tidak',
            $progress['completed_wajib'] ?? 0,
            $progress['total_wajib'] ?? 0,
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
            optional($user->berkas)->created_at ? $user->berkas->created_at->format('d/m/Y H:i') : '-',
            optional($user->berkas)->catatan_admin ?? '-',
            $berkasDetails,
        ];
    }

    /**
     * Mendapatkan default progress values
     */
    private function getDefaultProgress(): array
    {
        return [
            'percentage' => 0,
            'is_wajib_lengkap' => false,
            'completed_wajib' => 0,
            'total_wajib' => 0,
        ];
    }

    /**
     * Mendapatkan detail status setiap berkas dalam bentuk string.
     */
    private function getBerkasDetails($user): string
    {
        if (!$user->berkas || !$user->jalur_pendaftaran) {
            return 'Belum ada berkas';
        }

        try {
            // Log if biodata or agama is missing
            if (!$user->biodata) {
                Log::warning('Biodata missing for user', ['user_id' => $user->id, 'nama_lengkap' => $user->nama_lengkap ?? 'Unknown']);
            } elseif (!$user->biodata->agama) {
                Log::warning('Agama missing in biodata for user', ['user_id' => $user->id, 'nama_lengkap' => $user->nama_lengkap ?? 'Unknown']);
            }

            // Ensure agama is always a string
            $agama = $user->biodata && $user->biodata->agama ? (string) $user->biodata->agama : 'Islam';

            $definisiBerkas = BerkasHelper::getBerkasListForJalur($user->jalur_pendaftaran, $agama);

            if (!is_array($definisiBerkas) || empty($definisiBerkas)) {
                return 'Definisi berkas tidak ditemukan';
            }

            $details = [];
            foreach ($definisiBerkas as $field => $info) {
                $status = 'Kosong';
                if (!empty($user->berkas->$field)) {
                    if (isset($info['multiple']) && $info['multiple']) {
                        $files = json_decode($user->berkas->$field, true) ?? [];
                        $status = 'Uploaded (' . count($files) . ' file)';
                    } else {
                        $status = 'Uploaded';
                    }
                }
                $required = ($info['required'] ?? false) ? '(Wajib)' : '(Opsional)';
                $label = $info['label'] ?? $field;
                $details[] = "{$label} {$required}: {$status}";
            }
            return implode(";\n", $details);
        } catch (\Exception $e) {
            Log::error('Error getting berkas details for user ' . $user->id . ': ' . $e->getMessage());
            return 'Error memuat detail berkas';
        }
    }

    /**
     * Memberi style pada worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        $startRow = $this->startRow();
        $headerRow = $startRow;

        $sheet->getStyle("A{$headerRow}:O{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);
        $sheet->getStyle("A{$headerRow}:O" . $sheet->getHighestRow())->getAlignment()->setVertical('top');
    }

    /**
     * Menentukan lebar kolom.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 18,
            'C' => 15,
            'D' => 30,
            'E' => 18,
            'F' => 22,
            'G' => 15,
            'H' => 12,
            'I' => 15,
            'J' => 12,
            'K' => 12,
            'L' => 18,
            'M' => 18,
            'N' => 30,
            'O' => 60,
        ];
    }

    /**
     * Memberi judul pada sheet.
     */
    public function title(): string
    {
        return 'Laporan Berkas Siswa';
    }

    /**
     * Menentukan baris mulai untuk data.
     */
    public function startRow(): int
    {
        return 11;
    }

    /**
     * Menambahkan kop surat dan footer.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert rows for header
                $sheet->insertNewRowBefore(1, 9);

                // Adding header content
                $sheet->mergeCells('A2:O2')->setCellValue('A2', 'PEMERINTAH KABUPATEN INDRAMAYU');
                $sheet->mergeCells('A3:O3')->setCellValue('A3', 'SMP UNGGULAN SINDANG');
                $sheet->mergeCells('A4:O4')->setCellValue('A4', 'Jl. Pendidikan No. 123, Sindang, Kabupaten Indramayu, Jawa Barat');
                $sheet->mergeCells('A5:O5')->setCellValue('A5', 'Telepon: (021) 12345678 | Email: admin@smpunggulansindang.sch.id');

                // Header border
                $sheet->getStyle('A6:O6')->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Report title and metadata
                $sheet->mergeCells('A7:O7')->setCellValue('A7', 'LAPORAN STATUS BERKAS SISWA');
                $sheet->mergeCells('A8:O8')->setCellValue('A8', 'Tahun Ajaran: 2025/2026');
                $sheet->mergeCells('A9:O9')->setCellValue('A9', 'Tanggal Cetak: ' . now()->format('d M Y H:i'));

                // Style header text
                $sheet->getStyle('A2:A4')->getFont()->setSize(12);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A2:O9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Footer note
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->mergeCells("A{$lastRow}:O{$lastRow}")
                    ->setCellValue("A{$lastRow}", 'Dokumen ini dicetak secara otomatis melalui sistem SPMB Online SMP Unggulan Sindang');
                $sheet->getStyle("A{$lastRow}:O{$lastRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Page setup
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }

    /**
     * Menambahkan logo pada kop surat.
     */
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
        $smp->setCoordinates('O1');
        $smp->setOffsetX(5);
        $smp->setOffsetY(5);
        $drawings[] = $smp;

        return $drawings;
    }
}
