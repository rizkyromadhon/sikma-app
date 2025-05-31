<?php

namespace App\Exports;

use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\Presensi; // Jika diperlukan untuk data detail
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection as SupportCollection; // Alias untuk SupportCollection

class RekapitulasiKehadiranExport implements WithMultipleSheets
{
    use Exportable;

    protected $filters;
    protected $dataMahasiswaPerProdi; // Data yang sudah dikelompokkan

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        $this->prepareData();
    }

    protected function prepareData()
    {
        // Logika untuk mengambil dan memproses data mahasiswa berdasarkan filter
        // Mirip dengan yang ada di RekapitulasiController->filterStudents()
        // Namun, ambil SEMUA data, bukan paginasi, dan kelompokkan per prodi->

        $query = User::where('role', 'Mahasiswa')
            ->with(['programStudi', 'semester', 'golongan']) // Asumsi ada relasi golongan
            ->orderBy('id_prodi') // Urutkan berdasarkan prodi, lalu semester, golongan, nama
            ->orderBy('id_semester')
            ->orderBy('id_golongan')
            ->orderBy('name');

        if (!empty($this->filters['search'])) {
            $searchTerm = $this->filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('nim', 'like', "%{$searchTerm}%");
            });
        }
        if (!empty($this->filters['program'])) {
            $query->where('id_prodi', $this->filters['program']);
        }
        if (!empty($this->filters['semester'])) {
            $query->where('id_semester', $this->filters['semester']);
        }
        // Tambahkan filter lain jika ada (misal dateFrom, dateTo)

        $allMahasiswa = $query->get();

        // Ambil data presensi hari ini untuk semua mahasiswa yang terfilter
        $today = Carbon::today();
        $mahasiswaIds = $allMahasiswa->pluck('id')->all();
        $presensiTodayCollection = Presensi::whereIn('user_id', $mahasiswaIds)
            ->whereDate('tanggal', $today->toDateString())
            ->get()
            ->keyBy('user_id'); // Key by user_id untuk akses mudah

        // Transform data (tambahkan status hari ini, dll->)
        $transformedMahasiswa = $allMahasiswa->map(function ($user) use ($today, $presensiTodayCollection) {
            $presensiUserHariIni = $presensiTodayCollection->get($user->id); // Ambil by key
            $statusUntukHariIni = null;
            $waktuUntukHariIni = '-';

            if ($presensiUserHariIni) { // Jika ada record tunggal atau gunakan ->where('user_id') jika collection of collections
                // Jika $presensiTodayCollection di-keyBy user_id dan hanya ada satu record per user per hari:
                if ($presensiUserHariIni && !$presensiUserHariIni->isEmpty()) { // Cek jika collection atau single object
                    $records = $presensiUserHariIni instanceof SupportCollection ? $presensiUserHariIni : collect([$presensiUserHariIni]);
                    $hadirCount = $records->where('status', 'Hadir')->count();
                    $izinCount = $records->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count();
                    $tidakHadirCount = $records->where('status', 'Tidak Hadir')->count();

                    if ($hadirCount > 0) {
                        $statusUntukHariIni = 'Hadir';
                        $firstHadirRecord = $records->where('status', 'Hadir')->sortBy('waktu_presensi')->first();
                        if ($firstHadirRecord && $firstHadirRecord->waktu_presensi) {
                            $waktuUntukHariIni = Carbon::parse($firstHadirRecord->waktu_presensi)->format('H:i');
                        }
                    } elseif ($izinCount > 0) {
                        $statusUntukHariIni = 'Izin/Sakit';
                    } elseif ($tidakHadirCount > 0) {
                        $statusUntukHariIni = 'Tidak Hadir';
                    } else {
                        $statusUntukHariIni = 'Tidak Hadir'; // Jika ada record tapi status tidak dikenali
                    }
                }
            }
            // Jika tidak ada presensi, $statusUntukHariIni tetap null (Belum Presensi)

            $user->status_kehadiran_hari_ini = $statusUntukHariIni;
            $user->waktu_kehadiran_hari_ini = $waktuUntukHariIni;
            return $user;
        });

        $this->dataMahasiswaPerProdi = $transformedMahasiswa->groupBy('id_prodi');
    }

    public function sheets(): array
    {
        $sheets = [];
        $programStudis = ProgramStudi::whereIn('id', $this->dataMahasiswaPerProdi->keys())->orderBy('name')->get();

        if ($this->dataMahasiswaPerProdi->isEmpty()) {
            // Jika tidak ada data sama sekali setelah filter, buat satu sheet kosong dengan pesan
            $sheets[] = new EmptySheetExport('Tidak ada data untuk diexport berdasarkan filter yang dipilih->');
            return $sheets;
        }

        foreach ($programStudis as $prodi) {
            if ($this->dataMahasiswaPerProdi->has($prodi->id)) {
                $sheets[] = new PerProdiSheetExport($prodi->name, $this->dataMahasiswaPerProdi->get($prodi->id));
            }
        }
        // Jika ada mahasiswa yang id_prodi nya null atau tidak ada di tabel program_studis
        if ($this->dataMahasiswaPerProdi->has('')) { // Atau key lain untuk prodi null/unknown
            $sheets[] = new PerProdiSheetExport('Program Studi Tidak Diketahui', $this->dataMahasiswaPerProdi->get(''));
        }


        return $sheets;
    }
}

// Definisikan kelas PerProdiSheetExport (bisa di file terpisah atau di sini)
class PerProdiSheetExport implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    private $prodiName;
    private $mahasiswaCollection;
    private $rowNumber = 0;

    public function __construct(string $prodiName, SupportCollection $mahasiswaCollection)
    {
        $this->prodiName = $prodiName;
        // Urutkan mahasiswa berdasarkan semester, golongan, lalu nama untuk sheet ini
        $this->mahasiswaCollection = $mahasiswaCollection->sortBy([
            ['id_semester', 'asc'],
            ['id_golongan', 'asc'],
            ['name', 'asc'],
        ]);
    }

    public function collection()
    {
        return $this->mahasiswaCollection;
    }

    public function title(): string
    {
        // Bersihkan nama sheet dari karakter yang tidak valid
        return preg_replace('/[\\\\\\/\\?\\*\\:\\\[\\]]/', '', substr($this->prodiName, 0, 31));
    }

    public function headings(): array
    {
        // Header untuk setiap sheet
        return [
            'No',
            'NIM',
            'Nama Mahasiswa',
            'Semester',
            'Golongan', // Asumsi ada kolom 'id_golongan' atau relasi ke nama golongan
            'Status Hari Ini',
            'Waktu',
            // Anda bisa menambahkan kolom lain seperti total hadir, izin, alpa semester ini jika datanya ada
        ];
    }

    public function map($mahasiswa): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $mahasiswa->nim ?? '-',
            $mahasiswa->name ?? '-',
            $mahasiswa->semester->semester_ke ?? ($mahasiswa->semester->name ?? ($mahasiswa->id_semester ?? '-')),
            $mahasiswa->golongan->nama_golongan ?? ($mahasiswa->id_golongan ?? '-'),
            $mahasiswa->status_kehadiran_hari_ini ?? 'Belum Presensi',
            $mahasiswa->waktu_kehadiran_hari_ini ?? '-',
        ];
    }
}

// Opsional: Sheet untuk kondisi data kosong
class EmptySheetExport implements WithTitle, FromCollection, WithHeadings
{
    private $message;
    public function __construct(string $message)
    {
        $this->message = $message;
    }
    public function collection()
    {
        return new SupportCollection([[$this->message]]);
    }
    public function headings(): array
    {
        return ['Informasi'];
    }
    public function title(): string
    {
        return 'Export Kosong';
    }
}
