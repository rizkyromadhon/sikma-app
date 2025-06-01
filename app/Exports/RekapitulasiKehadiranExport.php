<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Semester;
use Illuminate\Support\Str;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Log; // Masih perlu untuk catch (\Exception $e)
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

// Definisikan kelas untuk sheet kosong jika belum ada
if (!class_exists('App\Exports\EmptySheetExport')) {
    class EmptySheetExport implements WithTitle, FromCollection, WithHeadings
    {
        private $message;
        public function __construct(string $message)
        {
            $this->message = $message;
        }
        public function collection()
        {
            return new \Illuminate\Support\Collection([[$this->message]]);
        }
        public function headings(): array
        {
            return ['Informasi'];
        }
        public function title(): string
        {
            return 'Tidak Ada Data';
        }
    }
}

class RekapitulasiKehadiranExport implements WithMultipleSheets
{
    use Exportable;

    protected array $exportData;

    public function __construct(array $exportData)
    {
        $this->exportData = $exportData;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $programStudis = $this->exportData['programStudis'] ?? collect();
        $mahasiswaPerProdi = $this->exportData['mahasiswaPerProdi'] ?? collect();
        $filters = $this->exportData['filters'];

        if ($mahasiswaPerProdi->isEmpty()) {
            $sheets[] = new EmptySheetExport('Tidak ada data mahasiswa yang cocok untuk diexport berdasarkan filter yang dipilih.');
            return $sheets;
        }

        $programStudisForLoop = $programStudis;

        foreach ($programStudisForLoop as $prodi) {
            $prodiIdKey = $prodi->id ?? '';

            if ($mahasiswaPerProdi->has($prodiIdKey) && $mahasiswaPerProdi->get($prodiIdKey)->isNotEmpty()) {
                $mahasiswaUntukSheetIni = $mahasiswaPerProdi->get($prodiIdKey);

                $sheetSpecificData = [
                    'sheetTitle' => Str::limit(preg_replace('/[\\\\\\/\\?\\*\\:\\\[\\]]/', '', $prodi->name), 30, ''),
                    'appName' => $this->exportData['appName'],
                    'namaInstitusi' => $this->exportData['namaInstitusi'],
                    'filters' => $filters,
                    'filterProgramStudiNama' => $prodi->name,
                    'filterSemesterNama' => $filters['semester_nama'],
                    'tahunAjaran' => $filters['tahun_ajaran_display'],
                    'dateColumns' => $this->exportData['dateColumns'],
                    'mahasiswaCollection' => $mahasiswaUntukSheetIni,
                    'presensiData' => $this->exportData['presensiData'],
                    'bulanRange' => $this->exportData['bulanRange'],
                ];
                $sheets[] = new RekapSemesteranPerProdiSheet($sheetSpecificData);
            }
        }

        if (empty($sheets)) {
            $sheets[] = new EmptySheetExport('Tidak ada data mahasiswa yang cocok untuk diexport.');
        }

        return $sheets;
    }
}

class RekapSemesteranPerProdiSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithEvents, WithColumnWidths, ShouldAutoSize
{
    protected array $sheetData;
    protected array $dateColumnsKeys;
    protected int $rowNumber = 0;
    protected int $startDataRow = 8; // Data mahasiswa dimulai pada baris ke-8
    protected string $highestColumnLetter;

    public function __construct(array $sheetData)
    {
        $this->sheetData = $sheetData;
        $this->dateColumnsKeys = array_keys($this->sheetData['dateColumns'] ?? []);
        $this->highestColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(2 + count($this->dateColumnsKeys) + 4);
    }

    public function collection()
    {
        return $this->sheetData['mahasiswaCollection'] ?? collect();
    }

    public function title(): string
    {
        return $this->sheetData['sheetTitle'] ?? 'Sheet';
    }

    public function headings(): array
    {
        $appName = $this->sheetData['appName'] ?? 'Aplikasi';
        $namaInstitusi = $this->sheetData['namaInstitusi'] ?? 'Institusi';
        $semesterFilter = $this->sheetData['filters']['semester_nama'] ?? 'Semua';
        $prodiFilter = $this->sheetData['filterProgramStudiNama'] ?? 'Semua';
        $tahunAjaranDisplay = $this->sheetData['filters']['tahun_ajaran_display'] ?? '';

        // Baris 1: Header utama dengan logo/institusi
        $headerInstitusi = [$namaInstitusi . ' - ' . $appName];

        // Baris 2: Judul laporan
        $headerTitle = ['REKAPITULASI KEHADIRAN MAHASISWA'];

        // Baris 3: Info semester dan prodi dalam format yang lebih rapi
        $semesterInfo = $semesterFilter;
        if (!Str::startsWith(strtolower($semesterFilter), 'semester')) {
            $semesterInfo = "Semester " . $semesterFilter;
        }
        $headerInfo = [$semesterInfo . ' (' . $tahunAjaranDisplay . ') - ' . $prodiFilter];

        // Baris 4: Kosong sebagai pemisah
        $emptyRow = [''];

        // Baris 5: Header bulan (akan diisi di registerEvents)
        $headerBulanRow = array_fill(0, count($this->dateColumnsKeys) + 6, '');

        // Baris 6: Header kolom utama (Nama kolom Rekapitulasi diubah di sini)
        $headerKolom = ['No', 'Nama Mahasiswa'];
        foreach ($this->sheetData['dateColumns'] ?? [] as $dateInfo) {
            $headerKolom[] = $dateInfo['day_number'];
        }
        $headerKolom[] = 'Hadir'; // Tetap Hadir
        $headerKolom[] = 'Izin/Sakit'; // Diubah dari 'Izin'
        $headerKolom[] = 'Tidak Hadir'; // Diubah dari 'Alpha'
        $headerKolom[] = 'Persentase'; // Tetap Persentase

        // Baris 7: Nama hari (akan diatur rotasinya di registerEvents)
        $headerHari = ['', ''];
        foreach ($this->sheetData['dateColumns'] ?? [] as $dateInfo) {
            $headerHari[] = substr($dateInfo['day_name_short'], 0, 3);
        }
        // Biarkan kosong, teks Hadir, Izin/Sakit, Tidak Hadir, Persentase akan di-merge dan dirotasi
        $headerHari[] = '';
        $headerHari[] = '';
        $headerHari[] = '';
        $headerHari[] = '';


        return [
            $headerInstitusi,    // Baris 1
            $headerTitle,        // Baris 2
            $headerInfo,         // Baris 3
            $emptyRow,           // Baris 4
            $headerBulanRow,     // Baris 5
            $headerKolom,        // Baris 6
            $headerHari,         // Baris 7
        ];
    }

    public function map($mahasiswa): array
    {
        $this->rowNumber++;
        $rowData = [
            $this->rowNumber,
            $mahasiswa->name ?? '-',
        ];

        $presensiMahasiswaPerTanggal = ($this->sheetData['presensiData'] ?? collect())->get($mahasiswa->id) ?? collect();

        $totalHadir = 0;
        $totalIzinSakit = 0;
        $totalAlpha = 0;

        $jumlahHariKuliahEfektif = 0; // Ubah nama variabel agar lebih jelas

        foreach ($this->dateColumnsKeys as $dateKey) {
            // Dapatkan informasi tanggal dari dateColumns yang disimpan
            $dateInfo = $this->sheetData['dateColumns'][$dateKey] ?? null;
            $presensiHariIni = $presensiMahasiswaPerTanggal->get($dateKey);
            $statusDisplay = '';

            if ($dateInfo && $dateInfo['is_holiday']) { // Jika ini hari libur (Sabtu/Minggu/Libur Nasional)
                $statusDisplay = 'L'; // Tampilkan 'L' untuk Libur
            } else { // Ini adalah hari kerja/kuliah
                $jumlahHariKuliahEfektif++; // Hitung sebagai hari efektif

                if ($presensiHariIni) {
                    $status = strtolower($presensiHariIni->status ?? '');
                    switch ($status) {
                        case 'hadir':
                            $statusDisplay = 'V';
                            $totalHadir++;
                            break;
                        case 'izin':
                        case 'sakit':
                        case 'izin/sakit':
                            $statusDisplay = 'I';
                            $totalIzinSakit++;
                            break;
                        case 'tidak hadir':
                            $statusDisplay = '✗';
                            $totalAlpha++;
                            break;
                        default:
                            $statusDisplay = '?'; // Tandai status yang tidak dikenal
                            break;
                    }
                } else {
                    $statusDisplay = 'X'; // Jika bukan hari libur dan tidak ada record, itu Alpha
                    $totalAlpha++;
                }
            }
            $rowData[] = $statusDisplay;
        }

        $rowData[] = $totalHadir;
        $rowData[] = $totalIzinSakit;
        $rowData[] = $totalAlpha;

        $persentaseHadir = ($jumlahHariKuliahEfektif > 0) ? round(($totalHadir / $jumlahHariKuliahEfektif) * 100, 1) : 0;
        $rowData[] = $persentaseHadir . '%';

        return $rowData;
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 6,   // No
            'B' => 35,  // Nama Mahasiswa
        ];

        // Kolom tanggal
        $dateColStart = 3;
        if (!empty($this->dateColumnsKeys)) {
            foreach ($this->dateColumnsKeys as $i => $dateKey) {
                $widths[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($dateColStart + $i)] = 4;
            }
            $lastDateColIndex = $dateColStart + count($this->dateColumnsKeys) - 1;
            // Menyesuaikan lebar kolom untuk header rekapitulasi yang dirotasi
            $widths[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateColIndex + 1)] = 5;    // Hadir
            $widths[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateColIndex + 2)] = 5;    // Izin/Sakit
            $widths[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateColIndex + 3)] = 5;    // Tidak Hadir
            $widths[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastDateColIndex + 4)] = 5; // Persentase (sesuaikan jika perlu lebih lebar)
        }

        return $widths;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $numDateColumns = count($this->dateColumnsKeys);
                $highestColumnLetter = $this->highestColumnLetter;
                $lastDataRow = $sheet->getHighestDataRow();

                // === GLOBAL STYLES ===
                $sheet->getDefaultRowDimension()->setRowHeight(18);
                $sheet->getStyle('A1:' . $highestColumnLetter . $lastDataRow)->getFont()->setName('Arial');
                $sheet->getStyle('A1:' . $highestColumnLetter . $lastDataRow)->getFont()->setSize(10);

                // === HEADER SECTION STYLING ===
                // Baris 1: Header Institusi
                $sheet->mergeCells('A1:' . $highestColumnLetter . '1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:' . $highestColumnLetter . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2E75B6');
                $sheet->getRowDimension(1)->setRowHeight(35);

                // Baris 2: Judul Laporan
                $sheet->mergeCells('A2:' . $highestColumnLetter . '2');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('2E75B6'));
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A2:' . $highestColumnLetter . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F2F2F2');
                $sheet->getRowDimension(2)->setRowHeight(28);

                // Baris 3: Info Filter
                $sheet->mergeCells('A3:' . $highestColumnLetter . '3');
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A3:' . $highestColumnLetter . '3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E7E6E6');
                $sheet->getRowDimension(3)->setRowHeight(25);

                // Baris 4: Spacer
                $sheet->getRowDimension(4)->setRowHeight(8);

                // === HEADER BULAN STYLING (Baris 5) ===
                if ($numDateColumns > 0) {
                    $currentMonthLabel = '';
                    $currentMonthStartColIndex = 0;

                    foreach ($this->dateColumnsKeys as $index => $dateKey) {
                        $dateCarbon = Carbon::parse($dateKey);
                        $monthLabel = $dateCarbon->translatedFormat('F Y');
                        $currentExcelColIndex = 3 + $index;

                        if ($currentMonthLabel === '' || $monthLabel !== $currentMonthLabel) {
                            if ($currentMonthStartColIndex > 0) {
                                $startMergeLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentMonthStartColIndex);
                                $endMergeLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentExcelColIndex - 1);
                                if ($currentMonthStartColIndex != ($currentExcelColIndex - 1)) {
                                    $sheet->mergeCells($startMergeLetter . '5:' . $endMergeLetter . '5');
                                }
                                $sheet->setCellValue($startMergeLetter . '5', $currentMonthLabel);
                                $this->styleMonthHeader($sheet, $startMergeLetter . '5:' . $endMergeLetter . '5');
                            }
                            $currentMonthLabel = $monthLabel;
                            $currentMonthStartColIndex = $currentExcelColIndex;
                        }
                    }

                    // Style last month
                    if ($currentMonthStartColIndex > 0) {
                        $startMergeLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentMonthStartColIndex);
                        $endMergeLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(2 + $numDateColumns);
                        if ($currentMonthStartColIndex != (2 + $numDateColumns)) {
                            $sheet->mergeCells($startMergeLetter . '5:' . $endMergeLetter . '5');
                        }
                        $sheet->setCellValue($startMergeLetter . '5', $currentMonthLabel);
                        $this->styleMonthHeader($sheet, $startMergeLetter . '5:' . $endMergeLetter . '5');
                    }

                    // Summary section header (Baris 5)
                    $summaryStartColIndex = 2 + $numDateColumns + 1;
                    $summaryStartColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($summaryStartColIndex);
                    $summaryEndColLetter = $highestColumnLetter;
                    if ($summaryStartColLetter <= $summaryEndColLetter) {
                        $sheet->mergeCells($summaryStartColLetter . '5:' . $summaryEndColLetter . '5');
                        $sheet->setCellValue($summaryStartColLetter . '5', 'REKAPITULASI');
                        $this->styleSummaryHeader($sheet, $summaryStartColLetter . '5:' . $summaryEndColLetter . '5');
                    }
                }

                // === TABLE HEADERS (Baris 6 & 7) ===
                $sheet->mergeCells('A6:A7'); // No
                $sheet->mergeCells('B6:B7'); // Nama Mahasiswa

                // Style for No & Name columns
                $sheet->getStyle('A5:B5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2E75B6'); // Ini sepertinya sisa dari baris 5, bisa dihapus atau diubah jika A5:B5 tidak ada header di sana
                $sheet->getStyle('A6:B7')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
                $sheet->getStyle('A6:B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A6:B7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('5B9BD5');
                $sheet->getStyle('A6:B7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FFFFFF');

                // Style main table headers
                $sheet->getStyle('A6:' . $highestColumnLetter . '7')->getFont()->setBold(true);
                $sheet->getStyle('A6:' . $highestColumnLetter . '7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                // Date columns styling
                $dateRangeEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(2 + $numDateColumns);
                $sheet->getStyle('C6:' . $dateRangeEnd . '7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('5B9BD5');
                $sheet->getStyle('C6:' . $dateRangeEnd . '7')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
                $sheet->getStyle('C6:' . $dateRangeEnd . '7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FFFFFF');

                // Day names rotation (Baris 7)
                $sheet->getStyle('C7:' . $dateRangeEnd . '7')->getAlignment()->setTextRotation(90);
                $sheet->getRowDimension(6)->setRowHeight(25);
                $sheet->getRowDimension(7)->setRowHeight(50);

                // === REKAPITULASI HEADERS STYLING (Baris 6 & 7) ===
                $rekapStartColIndex = 2 + $numDateColumns + 1;
                $rekapEndColIndex = 2 + $numDateColumns + 4;

                foreach (['Hadir', 'Izin/Sakit', 'Tidak Hadir', 'Persentase'] as $i => $headerText) {
                    $colIndex = $rekapStartColIndex + $i;
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->mergeCells($colLetter . '6:' . $colLetter . '7');
                    $sheet->setCellValue($colLetter . '6', $headerText);
                    $sheet->getStyle($colLetter . '6:' . $colLetter . '7')
                        ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00B050');
                    $sheet->getStyle($colLetter . '6:' . $colLetter . '7')
                        ->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'))->setBold(true);
                    $sheet->getStyle($colLetter . '6:' . $colLetter . '7')
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setTextRotation(90);
                    $sheet->getStyle($colLetter . '6:' . $colLetter . '7')
                        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FFFFFF');
                }

                // === DATA AREA STYLING ===
                if ($lastDataRow >= $this->startDataRow) {
                    $sheet->getStyle('A' . $this->startDataRow . ':' . $highestColumnLetter . $lastDataRow)
                        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('D0D0D0');

                    $sheet->getStyle('A' . $this->startDataRow . ':A' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('B' . $this->startDataRow . ':B' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('C' . $this->startDataRow . ':' . $dateRangeEnd . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($summaryStartColLetter . $this->startDataRow . ':' . $highestColumnLetter . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    for ($row = $this->startDataRow; $row <= $lastDataRow; $row++) {
                        if (($row - $this->startDataRow) % 2 == 0) {
                            $sheet->getStyle('A' . $row . ':' . $highestColumnLetter . $row)
                                ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F9F9F9');
                        }

                        for ($colIndex = 3; $colIndex <= (2 + $numDateColumns); $colIndex++) {
                            $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . $row;
                            $cellValue = trim($sheet->getCell($cellCoordinate)->getValue() ?? '');

                            $dateKey = $this->dateColumnsKeys[$colIndex - 3];
                            $dateInfo = $this->sheetData['dateColumns'][$dateKey] ?? null;

                            if ($dateInfo && $dateInfo['is_holiday']) {
                                // Style for holiday cells (light grey background, black text)
                                $sheet->getStyle($cellCoordinate)
                                    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('808080'); // Abu-abu sangat terang
                                $sheet->getStyle($cellCoordinate)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000')); // Hitam
                                $sheet->getStyle($cellCoordinate)->getFont()->setBold(true)->setSize(10);
                                $sheet->setCellValue($cellCoordinate, 'L'); // Pastikan 'L' disetel di sel
                            } else {
                                // Apply existing status styling for non-holiday cells
                                switch ($cellValue) {
                                    case 'V':
                                        $sheet->getStyle($cellCoordinate)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('00B050'));
                                        $sheet->getStyle($cellCoordinate)->getFont()->setBold(true)->setSize(10);
                                        $sheet->getStyle($cellCoordinate)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('9BFFC8');
                                        break;
                                    case 'X':
                                        $sheet->getStyle($cellCoordinate)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000'));
                                        $sheet->getStyle($cellCoordinate)->getFont()->setBold(true)->setSize(10);
                                        $sheet->getStyle($cellCoordinate)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFB9B9');
                                        break;
                                    case 'I':
                                        $sheet->getStyle($cellCoordinate)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF8C00'));
                                        $sheet->getStyle($cellCoordinate)->getFont()->setBold(true)->setSize(10);
                                        $sheet->getStyle($cellCoordinate)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE697');
                                        break;
                                    default:
                                        // Pastikan tidak ada fill jika tidak ada status yang dikenal
                                        $sheet->getStyle($cellCoordinate)->getFill()->setFillType(Fill::FILL_NONE);
                                        break;
                                }
                            }
                        }
                    }
                }
                // Freeze panes
                $sheet->freezePane('C8');
            },
        ];
    }

    private function styleMonthHeader($sheet, $range)
    {
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2E75B6');
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FFFFFF');
        $sheet->getRowDimension(5)->setRowHeight(28);
    }

    private function styleSummaryHeader($sheet, $range)
    {
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00B050');
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FFFFFF');
    }
}
