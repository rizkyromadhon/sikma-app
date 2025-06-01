<?php

namespace App\Http\Controllers\Admin; // Sesuaikan namespace jika perlu

use Carbon\Carbon;
use App\Models\User;
use App\Models\Presensi;
use App\Models\Semester;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel; // Import Excel Facade
use App\Exports\RekapitulasiKehadiranExport; // Import Export Class Anda
use San103\Phpholidayapi\HolidayClient;

class RekapitulasiController extends Controller
{
    // Metode untuk menampilkan halaman utama rekapitulasi kehadiran
    public function index(Request $request)
    {
        $today = Carbon::today();
        $mahasiswaQuery = User::where('role', 'Mahasiswa');
        $totalMahasiswa = $mahasiswaQuery->count();
        $mahasiswaIds = $mahasiswaQuery->pluck('id')->all(); // Ensure it's an array for whereIn

        $allPresensiTodayForStudents = Presensi::whereIn('user_id', $mahasiswaIds)
            ->whereDate('tanggal', $today)
            ->get();

        $hadirHariIniCount_students = 0;
        $tidakHadirHariIniCount_students = 0;
        $izinSakitHariIniCount_students = 0;

        foreach ($mahasiswaIds as $mahasiswaId) {
            $presensiMahasiswaHariIni = $allPresensiTodayForStudents->where('user_id', $mahasiswaId);
            $statusUntukHariIni = 'Tidak Hadir';

            if (!$presensiMahasiswaHariIni->isEmpty()) {
                $hadirCount = $presensiMahasiswaHariIni->where('status', 'Hadir')->count();
                $tidakHadirCount = $presensiMahasiswaHariIni->where('status', 'Tidak Hadir')->count();
                $izinCount = $presensiMahasiswaHariIni->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count(); // More robust check for Izin/Sakit

                $statusCounts = [
                    'Hadir' => $hadirCount,
                    'Tidak Hadir' => $tidakHadirCount,
                    'Izin/Sakit' => $izinCount,
                ];
                $maxCount = 0;
                if (!empty($statusCounts)) { // Ensure statusCounts is not empty before calling max()
                    $maxCount = max($statusCounts);
                }

                $statusWithMaxCount = array_keys($statusCounts, $maxCount);

                if (in_array('Hadir', $statusWithMaxCount)) {
                    $statusUntukHariIni = 'Hadir';
                } elseif (in_array('Izin/Sakit', $statusWithMaxCount)) {
                    $statusUntukHariIni = 'Izin/Sakit';
                } else {
                    $statusUntukHariIni = 'Tidak Hadir';
                }
            }

            if ($statusUntukHariIni === 'Hadir') {
                $hadirHariIniCount_students++;
            } elseif ($statusUntukHariIni === 'Tidak Hadir') {
                $tidakHadirHariIniCount_students++;
            } elseif ($statusUntukHariIni === 'Izin/Sakit') {
                $izinSakitHariIniCount_students++;
            }
        }

        $totalHadirToday = $hadirHariIniCount_students;
        $totalTidakHadirToday = $tidakHadirHariIniCount_students;
        $totalIzinToday = $izinSakitHariIniCount_students;

        $totalValidation = $totalHadirToday + $totalTidakHadirToday + $totalIzinToday;
        if ($totalValidation !== $totalMahasiswa && $totalMahasiswa > 0) { // Avoid division by zero or incorrect validation if no students
            // If there are students, but validation fails, then it's an issue.
            // If totalMahasiswa is 0, totalValidation will also be 0, which is fine.
            // If totalMahasiswa > 0 and totalValidation != totalMahasiswa, it's an issue.
            // Special case: if totalMahasiswa > 0 but no one has any status (e.g., all are 'Belum Presensi' implicitly)
            // then totalTidakHadirToday should actually be $totalMahasiswa.
            // The current logic defaults to 'Tidak Hadir', so this validation check is slightly complex.
            // For now, just logging is okay. A more robust check would ensure all students are accounted for.
            if ($totalMahasiswa > 0 && $totalValidation < $totalMahasiswa) {
                // This means some students were not categorized, implicitly they are 'Tidak Hadir'
                $totalTidakHadirToday += ($totalMahasiswa - $totalValidation);
            }
            Log::warning("Total validation mismatch after adjustment: Total calculated = {$totalHadirToday} + {$totalTidakHadirToday} + {$totalIzinToday} = " . ($totalHadirToday + $totalTidakHadirToday + $totalIzinToday) . ", Total mahasiswa = {$totalMahasiswa}");
        }


        $persenHadirToday = $totalMahasiswa > 0 ? round(($totalHadirToday / $totalMahasiswa) * 100, 2) : 0;
        $persenTidakHadirToday = $totalMahasiswa > 0 ? round(($totalTidakHadirToday / $totalMahasiswa) * 100, 2) : 0;
        $persenIzinToday = $totalMahasiswa > 0 ? round(($totalIzinToday / $totalMahasiswa) * 100, 2) : 0;

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyAttendanceCount = 0;
        $weeklyDates = [];
        $currentDate = $startOfWeek->copy();

        while ($currentDate->lte($endOfWeek)) {
            // Only consider weekdays for weekly average calculation
            if ($currentDate->isWeekday()) {
                $weeklyDates[] = $currentDate->toDateString();
            }
            $currentDate->addDay();
        }

        $actualWeekDaysSoFar = 0;
        $today = Carbon::today();
        foreach ($weeklyDates as $idx => $date) {
            if (Carbon::parse($date)->lte($today)) { // only count days up to today
                $actualWeekDaysSoFar++;
            }
        }


        $totalHadirDaysInWeekForAverage = 0;

        if (!empty($mahasiswaIds)) { // Check if there are any students
            $allPresensiThisWeek = Presensi::whereIn('user_id', $mahasiswaIds)
                ->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->get();

            foreach ($mahasiswaIds as $mahasiswaId) {
                $hadirDaysInWeek = 0;
                foreach ($weeklyDates as $date) {
                    // Only count up to today for average calculation basis
                    if (Carbon::parse($date)->gt(Carbon::today())) {
                        continue;
                    }

                    $presensiForDate = $allPresensiThisWeek->where('user_id', $mahasiswaId)
                        ->where('tanggal', $date); // date is already Y-m-d string
                    $statusForDate = 'Tidak Hadir';

                    if (!$presensiForDate->isEmpty()) {
                        $hadirCount = $presensiForDate->where('status', 'Hadir')->count();
                        $tidakHadirCount = $presensiForDate->where('status', 'Tidak Hadir')->count();
                        $izinCount = $presensiForDate->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count();

                        $statusCounts = ['Hadir' => $hadirCount, 'Tidak Hadir' => $tidakHadirCount, 'Izin/Sakit' => $izinCount];
                        $maxCount = 0;
                        if (!empty($statusCounts)) $maxCount = max($statusCounts);
                        $statusWithMaxCount = array_keys($statusCounts, $maxCount);

                        if (in_array('Hadir', $statusWithMaxCount)) {
                            $statusForDate = 'Hadir';
                        } elseif (in_array('Izin/Sakit', $statusWithMaxCount)) {
                            $statusForDate = 'Izin/Sakit';
                        } else {
                            $statusForDate = 'Tidak Hadir';
                        }
                    }
                    if ($statusForDate === 'Hadir') {
                        $hadirDaysInWeek++;
                    }
                }
                // Add this student's contribution to the total hadir days
                $totalHadirDaysInWeekForAverage += $hadirDaysInWeek;
            }
        }

        // Average = (total hadir days by all students) / (total students * number of weekdays passed so far in the week)
        $persenRataRataHadirMingguan = ($totalMahasiswa > 0 && $actualWeekDaysSoFar > 0)
            ? round(($totalHadirDaysInWeekForAverage / ($totalMahasiswa * $actualWeekDaysSoFar)) * 100, 2)
            : 0;


        $programStudis = ProgramStudi::orderBy('name')->get();
        $semesters = Semester::orderBy('id')->get();
        $filters = $request->only(['search', 'program', 'semester', 'dateFrom', 'dateTo']);

        return view('admin.rekapitulasi.index', compact(
            'programStudis',
            'semesters',
            'totalMahasiswa',
            'totalHadirToday',
            'totalTidakHadirToday',
            'totalIzinToday',
            'persenHadirToday',
            'persenTidakHadirToday',
            'persenIzinToday',
            'persenRataRataHadirMingguan',
            'filters'
        ));
    }

    public function filterStudents(Request $request)
    {
        // Set locale Carbon ke Bahasa Indonesia untuk nama hari
        // Sebaiknya ini di set global di AppServiceProvider.php -> boot() method: Carbon::setLocale('id');
        // Jika belum, bisa sementara di sini, tapi tidak ideal:
        // Carbon::setLocale('id');

        $query = User::where('role', 'Mahasiswa')->with(['programStudi', 'semester']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('nim', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('program')) {
            $query->where('id_prodi', $request->program);
        }

        if ($request->filled('semester')) {
            $query->where('id_semester', $request->semester);
        }

        $perPage = $request->input('perPage', 10);
        $users = $query->orderBy('name')->paginate($perPage);

        $today = Carbon::today();
        $userIds = $users->pluck('id')->all();

        $presensiTodayCollection = Presensi::whereIn('user_id', $userIds)
            ->whereDate('tanggal', $today->toDateString())
            ->get();

        // Untuk Riwayat Kehadiran (Senin - Jumat minggu ini)
        $startOfWeekCurrent = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeekCurrent = $today->copy()->endOfWeek(Carbon::FRIDAY); // Hanya sampai Jumat

        $presensiForHistoryCollection = Presensi::whereIn('user_id', $userIds)
            ->whereBetween('tanggal', [$startOfWeekCurrent->toDateString(), $endOfWeekCurrent->toDateString()])
            ->get();

        $allPresensiForUsersCollection = Presensi::whereIn('user_id', $userIds)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_presensi', 'desc')
            ->get();

        $users->getCollection()->transform(function ($user) use ($today, $presensiTodayCollection, $presensiForHistoryCollection, $allPresensiForUsersCollection, $startOfWeekCurrent) {

            // --- STATUS HARI INI (Untuk tabel utama dan modal) ---
            $presensiUserHariIni = $presensiTodayCollection->where('user_id', $user->id);
            $statusUntukHariIni = null; // Default ke null -> akan jadi "Belum Presensi" di Blade jika tidak ada data
            $waktuUntukHariIni = '-';

            if (!$presensiUserHariIni->isEmpty()) {
                $hadirCount = $presensiUserHariIni->where('status', 'Hadir')->count();
                $tidakHadirCount = $presensiUserHariIni->where('status', 'Tidak Hadir')->count();
                $izinCount = $presensiUserHariIni->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count();

                // Prioritas: Hadir > Izin/Sakit > Tidak Hadir
                if ($hadirCount > 0) {
                    $statusUntukHariIni = 'Hadir';
                    $firstHadirRecord = $presensiUserHariIni->where('status', 'Hadir')->sortBy('waktu_presensi')->first();
                    if ($firstHadirRecord && $firstHadirRecord->waktu_presensi) {
                        try {
                            $waktuUntukHariIni = Carbon::parse($firstHadirRecord->waktu_presensi)->format('H:i');
                        } catch (\Exception $e) {
                            Log::warning("Format waktu presensi salah untuk user {$user->id} tgl {$today->toDateString()}: {$firstHadirRecord->waktu_presensi}");
                        }
                    }
                } elseif ($izinCount > 0) {
                    $statusUntukHariIni = 'Izin/Sakit'; // Bisa 'Izin' atau 'Sakit' tergantung data Anda
                } elseif ($tidakHadirCount > 0) {
                    $statusUntukHariIni = 'Tidak Hadir';
                } else {
                    // Ada record presensi tapi tidak ada status yang cocok (seharusnya tidak terjadi jika data status valid)
                    $statusUntukHariIni = 'Tidak Hadir';
                }
            }
            // Jika $presensiUserHariIni->isEmpty(), $statusUntukHariIni akan tetap null (Belum Presensi)

            // --- RIWAYAT KEHADIRAN (Senin - Jumat Minggu Ini) ---
            $attendanceHistory = [];
            $dayNameMapping = ['Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat'];

            for ($d = 0; $d < 5; $d++) { // Loop Senin sampai Jumat
                $currentDayInLoop = $startOfWeekCurrent->copy()->addDays($d);
                $dateStr = $currentDayInLoop->toDateString();
                $statusRiwayat = null; // Default: Belum Presensi / Abu-abu

                if ($currentDayInLoop->gt($today)) {
                    // Hari di masa depan (dalam minggu ini, setelah hari ini) -> Belum Presensi (null)
                    $statusRiwayat = null;
                } else {
                    // Hari ini atau hari lalu dalam minggu ini
                    $presensiPadaHariRiwayat = $presensiForHistoryCollection
                        ->where('user_id', $user->id)
                        ->where('tanggal', $dateStr);

                    if (!$presensiPadaHariRiwayat->isEmpty()) {
                        $hadirCountR = $presensiPadaHariRiwayat->where('status', 'Hadir')->count();
                        $tidakHadirCountR = $presensiPadaHariRiwayat->where('status', 'Tidak Hadir')->count();
                        $izinCountR = $presensiPadaHariRiwayat->where('status', 'Izin/Sakit')->count();

                        if ($hadirCountR > 0) $statusRiwayat = 'Hadir';
                        elseif ($izinCountR > 0) $statusRiwayat = 'Izin/Sakit'; // Atau 'Izin/Sakit'
                        elseif ($tidakHadirCountR > 0) $statusRiwayat = 'Tidak Hadir';
                        else $statusRiwayat = 'Tidak Hadir'; // Default jika ada record tanpa status cocok
                    } else {
                        // Tidak ada record presensi sama sekali untuk hari ini atau hari lalu
                        $statusRiwayat = 'Tidak Hadir';
                    }
                    // Override khusus untuk hari ini di riwayat, agar konsisten dengan status utama hari ini
                    if ($currentDayInLoop->isToday()) {
                        $statusRiwayat = $statusUntukHariIni; // null jika belum presensi hari ini
                    }
                }

                $englishDayShort = $currentDayInLoop->format('D'); // Mon, Tue, etc.
                $attendanceHistory[] = [
                    'dayName' => $dayNameMapping[$englishDayShort] ?? $currentDayInLoop->translatedFormat('D'),
                    'date' => $currentDayInLoop->format('d'),
                    'status' => $statusRiwayat,
                ];
            }

            // --- STATISTIK KESELURUHAN/SEMESTER (Untuk modal "Informasi Kehadiran") ---
            $allUserPresensiHistoris = $allPresensiForUsersCollection->where('user_id', $user->id);
            $semesterDaysPresent = 0;
            $semesterDaysAbsent = 0;
            $semesterDaysExcused = 0;
            $totalUniqueDaysWithPresensi = 0;

            if (!$allUserPresensiHistoris->isEmpty()) {
                $groupedByDate = $allUserPresensiHistoris->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal)->toDateString();
                });
                $totalUniqueDaysWithPresensi = $groupedByDate->count();

                foreach ($groupedByDate as $date => $recordsForDay) {
                    $hadirCount_s = $recordsForDay->where('status', 'Hadir')->count();
                    $tidakHadirCount_s = $recordsForDay->where('status', 'Tidak Hadir')->count();
                    $izinCount_s = $recordsForDay->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count();

                    if ($hadirCount_s > 0) $semesterDaysPresent++;
                    elseif ($izinCount_s > 0) $semesterDaysExcused++;
                    elseif ($tidakHadirCount_s > 0) $semesterDaysAbsent++;
                    else $semesterDaysAbsent++;
                }
            }
            $semesterAttendanceRate = $totalUniqueDaysWithPresensi > 0 ? round(($semesterDaysPresent / $totalUniqueDaysWithPresensi) * 100, 0) : 0;

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'nim' => $user->nim,
                'initials' => $user->initials,
                'program_studi_name' => $user->programStudi ? $user->programStudi->name : 'N/A',
                'semester_name' => $user->semester ? ($user->semester->semester_ke ?? $user->semester->name ?? $user->id_semester) : 'N/A',

                // Untuk tabel utama
                'status_kehadiran_hari_ini' => $statusUntukHariIni, // Ini akan jadi null jika belum presensi hari ini
                'waktu_kehadiran_hari_ini' => $waktuUntukHariIni,
                // 'prodi' => $user->programStudi, // jika perlu objek prodi di JS

                // Data untuk Modal Detail
                'program' => $user->programStudi ? $user->programStudi->name : 'N/A', // untuk modal
                'semester' => $user->semester ? ($user->semester->semester_ke ?? $user->semester->name ?? $user->id_semester) : 'N/A', // untuk modal

                'totalPresent' => $semesterDaysPresent,
                'totalAbsent' => $semesterDaysAbsent,
                'totalExcused' => $semesterDaysExcused,
                'attendanceRate' => $semesterAttendanceRate,
                'status' => $statusUntukHariIni, // Status hari ini untuk modal
                'time' => $waktuUntukHariIni,   // Waktu presensi hari ini untuk modal
                'attendanceHistory' => $attendanceHistory,
            ];
        });

        return response()->json([
            'students' => $users,
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:mark-present,mark-absent,mark-excused',
            'student_ids' => 'required|array',
            'student_ids.*' => [
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || $user->role !== 'Mahasiswa') {
                        $fail($attribute . ' bukan ID mahasiswa yang valid.');
                    }
                },
            ],
        ]);

        $studentUserIds = $request->student_ids;
        $action = $request->action;
        $today = Carbon::today();
        $now = Carbon::now();

        $status = '';
        $keterangan = '';
        $updatedCount = 0;

        if ($action === 'mark-present') {
            $status = 'Hadir';
            $keterangan = 'Ditandai Hadir oleh Admin via Rekapitulasi';
        } elseif ($action === 'mark-absent') {
            $status = 'Tidak Hadir';
            $keterangan = 'Ditandai Tidak Hadir oleh Admin via Rekapitulasi';
        } elseif ($action === 'mark-excused') {
            $status = 'Izin/Sakit'; // Consistent with other parts
            $keterangan = 'Ditandai Izin/Sakit oleh Admin via Rekapitulasi';
        }

        DB::beginTransaction();
        try {
            foreach ($studentUserIds as $userId) {
                // To avoid multiple "admin-marked" entries for the same day if run multiple times,
                // we might want to delete previous admin-marked entries for today or be more specific.
                // For simplicity, updateOrCreate will handle if one record per day per user.
                // If mata_kuliah is a factor, the unique key for updateOrCreate would need it.
                // Assuming one general status per day marked by admin for now.

                // Delete any existing records for this user and date to ensure only one status is set by admin action.
                // This is crucial if a student could have multiple presences (e.g. per subject) in a day.
                // Bulk action should override ALL for that day with a single status.
                Presensi::where('user_id', $userId)
                    ->whereDate('tanggal', $today->toDateString())
                    ->delete();

                Presensi::create( // Use create since we deleted previous ones
                    [
                        'user_id' => $userId,
                        'tanggal' => $today->toDateString(),
                        'status' => $status,
                        'waktu_presensi' => $now, // Sets current time for the action
                        'keterangan' => $keterangan,
                        'mata_kuliah' => 'Aksi Massal Admin', // Or null, or a specific identifier
                        // Add other necessary fields if your Presensi table requires them.
                    ]
                );
                $updatedCount++;
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Aksi berhasil diterapkan pada ' . $updatedCount . ' mahasiswa.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action error: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Gagal melakukan aksi massal: ' . $e->getMessage()], 500);
        }
    }

    private function getFilteredMahasiswaData(Request $request, $isExport = true)
    {
        $query = User::where('role', 'Mahasiswa')
            ->with(['programStudi', 'semester', 'golongan']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('nim', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('program')) {
            $query->where('id_prodi', $request->program);
        }
        if ($request->filled('semester')) {
            $query->where('id_semester', $request->semester);
        }

        // Filter tanggal jika ada
        $dateFrom = $request->filled('dateFrom') ? Carbon::parse($request->dateFrom) : null;
        $dateTo = $request->filled('dateTo') ? Carbon::parse($request->dateTo) : null;

        $mahasiswa = $isExport
            ? $query->orderBy('id_prodi')->orderBy('id_semester')->orderBy('id_golongan')->orderBy('name')->get()
            : $query->orderBy('name')->paginate($request->input('perPage', 10));

        if ($mahasiswa->isEmpty()) {
            return new Collection();
        }

        $today = Carbon::today();
        $mahasiswaIds = $mahasiswa->pluck('id')->all();

        // Ambil presensi hari ini
        $presensiTodayCollection = Presensi::whereIn('user_id', $mahasiswaIds)
            ->whereDate('tanggal', $today->toDateString())
            ->get()
            ->groupBy('user_id');

        // Ambil SEMUA presensi untuk perhitungan total (dengan filter tanggal jika ada)
        $allPresensiQuery = Presensi::whereIn('user_id', $mahasiswaIds);

        if ($dateFrom) {
            $allPresensiQuery->whereDate('tanggal', '>=', $dateFrom->toDateString());
        }
        if ($dateTo) {
            $allPresensiQuery->whereDate('tanggal', '<=', $dateTo->toDateString());
        }

        $allPresensiCollection = $allPresensiQuery->get()->groupBy('user_id');

        $transformedMahasiswa = ($isExport ? $mahasiswa : $mahasiswa->getCollection())->map(function ($user) use ($today, $presensiTodayCollection, $allPresensiCollection) {

            // === STATUS HARI INI ===
            $presensiUserHariIni = $presensiTodayCollection->get($user->id) ?? collect();
            $statusUntukHariIni = null;
            $waktuUntukHariIni = '-';

            if ($presensiUserHariIni->isNotEmpty()) {
                $hadirCount = $presensiUserHariIni->where('status', 'Hadir')->count();
                $izinCount = $presensiUserHariIni->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count();
                $tidakHadirCount = $presensiUserHariIni->where('status', 'Tidak Hadir')->count();

                if ($hadirCount > 0) {
                    $statusUntukHariIni = 'Hadir';
                    $firstHadirRecord = $presensiUserHariIni->where('status', 'Hadir')->sortBy('waktu_presensi')->first();
                    if ($firstHadirRecord && $firstHadirRecord->waktu_presensi) {
                        $waktuUntukHariIni = Carbon::parse($firstHadirRecord->waktu_presensi)->format('H:i');
                    }
                } elseif ($izinCount > 0) {
                    $statusUntukHariIni = 'Izin/Sakit';
                } elseif ($tidakHadirCount > 0) {
                    $statusUntukHariIni = 'Tidak Hadir';
                } else {
                    $statusUntukHariIni = 'Tidak Hadir';
                }
            }

            // === STATISTIK KESELURUHAN ===
            $allUserPresensi = $allPresensiCollection->get($user->id) ?? collect();

            $totalHadir = 0;
            $totalTidakHadir = 0;
            $totalIzinSakit = 0;
            $totalTerlambat = 0;

            if ($allUserPresensi->isNotEmpty()) {
                // Group by tanggal untuk menghitung status per hari
                $groupedByDate = $allUserPresensi->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal)->toDateString();
                });

                foreach ($groupedByDate as $date => $recordsForDay) {
                    $hadirCount_day = $recordsForDay->where('status', 'Hadir')->count();
                    $tidakHadirCount_day = $recordsForDay->where('status', 'Tidak Hadir')->count();
                    $izinCount_day = $recordsForDay->whereIn('status', ['Izin', 'Sakit', 'Izin/Sakit'])->count();

                    // Hitung terlambat (asumsi ada field 'keterangan' yang mengandung 'terlambat' atau field terpisah)
                    $terlambatCount_day = $recordsForDay->where(function ($item) {
                        return $item->status === 'Hadir' &&
                            (stripos($item->keterangan ?? '', 'terlambat') !== false ||
                                ($item->waktu_presensi && Carbon::parse($item->waktu_presensi)->format('H:i') > '08:00'));
                    })->count();

                    // Tentukan status dominan untuk hari ini
                    if ($hadirCount_day > 0) {
                        $totalHadir++;
                        if ($terlambatCount_day > 0) {
                            $totalTerlambat++;
                        }
                    } elseif ($izinCount_day > 0) {
                        $totalIzinSakit++;
                    } elseif ($tidakHadirCount_day > 0) {
                        $totalTidakHadir++;
                    } else {
                        // Tidak ada record untuk hari ini = tidak hadir
                        $totalTidakHadir++;
                    }
                }
            }

            // Set properties untuk user
            $user->status_kehadiran_hari_ini = $statusUntukHariIni;
            $user->waktu_kehadiran_hari_ini = $waktuUntukHariIni;

            // === TAMBAHKAN STATISTIK KESELURUHAN ===
            $user->total_hadir = $totalHadir;
            $user->total_tidak_hadir = $totalTidakHadir;
            $user->total_izin_sakit = $totalIzinSakit;
            $user->total_terlambat = $totalTerlambat;

            // Hitung persentase kehadiran
            $totalPertemuan = $totalHadir + $totalTidakHadir + $totalIzinSakit;
            $user->persentase_kehadiran = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100, 2) : 0;

            // Status kehadiran keseluruhan
            if ($user->persentase_kehadiran >= 90) {
                $user->status_kehadiran_keseluruhan = 'Sangat Baik';
            } elseif ($user->persentase_kehadiran >= 80) {
                $user->status_kehadiran_keseluruhan = 'Baik';
            } elseif ($user->persentase_kehadiran >= 70) {
                $user->status_kehadiran_keseluruhan = 'Cukup';
            } else {
                $user->status_kehadiran_keseluruhan = 'Kurang';
            }

            return $user;
        });

        if ($isExport) {
            return $transformedMahasiswa;
        } else {
            $usersPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $transformedMahasiswa,
                $mahasiswa->total(),
                $mahasiswa->perPage(),
                $mahasiswa->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );
            return $usersPaginator;
        }
    }


    public function exportExcel(Request $request)
    {
        // PENTING: Gunakan try-catch di sekitar seluruh logika untuk menangkap error
        // dan mengembalikannya ke halaman sebelumnya. Excel::download() akan menghentikan eksekusi
        // jika berhasil, sehingga return redirect() hanya akan terpanggil saat ada error.
        try {
            // --- 1. Validasi Input dan Dapatkan Informasi Filter ---
            $selectedSemesterId = $request->input('semester');
            $selectedProgramId = $request->input('program');
            $requestedMonthFrom = (int) $request->input('monthFrom');
            $requestedMonthTo = (int) $request->input('monthTo');

            if (!$selectedSemesterId) {
                return back()->with('error', 'Silakan pilih semester terlebih dahulu untuk export Excel rekapitulasi semesteran.');
            }

            $semesterModel = Semester::find($selectedSemesterId);

            // Validasi keberadaan semester dan format tanggal awal/akhir
            if (!$semesterModel || empty($semesterModel->start_month) || empty($semesterModel->end_month)) {
                return back()->with('error', 'Data semester tidak valid atau tanggal mulai/selesai semester tidak ditemukan.');
            }

            // Tentukan Tahun Dasar Semester
            // Ini adalah tahun ketika semester yang dipilih (misal Ganjil 2024/2025) dimulai.
            // Ambil dari kolom 'kode' atau 'semester_name' jika ada format tahun di dalamnya.
            $baseYear = Carbon::now()->year; // Default ke tahun sekarang
            if (!empty($semesterModel->kode) && is_numeric(substr($semesterModel->kode, 0, 4))) {
                $baseYear = (int) substr($semesterModel->kode, 0, 4);
            } elseif (Str::contains($semesterModel->semester_name, '/')) {
                $parts = explode('/', $semesterModel->semester_name);
                $baseYear = (int) $parts[0]; // Ambil tahun awal (e.g., 2024 dari 2024/2025)
            }

            // Dapatkan nomor bulan dari kolom start_month dan end_month di DB (yang berupa string 'YYYY-MM-DD')
            $semesterDbStartMonthNum = Carbon::parse($semesterModel->start_month)->month;
            $semesterDbEndMonthNum = Carbon::parse($semesterModel->end_month)->month;

            // Tentukan bulan awal dan akhir yang efektif untuk periode ekspor
            // Prioritaskan input dari request modal, fallback ke bulan dari data semester DB
            $effectiveStartMonth = $requestedMonthFrom ?: $semesterDbStartMonthNum;
            $effectiveEndMonth = $requestedMonthTo ?: $semesterDbEndMonthNum;

            // --- Logika Penentuan Tanggal Awal & Akhir Periode Export (Carbon Objects) ---
            $startDate = Carbon::create($baseYear, $effectiveStartMonth, 1)->startOfDay();
            $endDate = Carbon::create($baseYear, $effectiveEndMonth, 1)->endOfMonth()->endOfDay();

            // Penyesuaian tahun jika rentang bulan melewati batas tahun (misal: Sept - Feb)
            // Ini akan membuat endDate di tahun berikutnya jika startMonth > endMonth
            if ($effectiveStartMonth > $effectiveEndMonth) {
                $endDate->addYear();
            }

            // Jika $startDate yang sudah di-adjust tahunnya masih lebih besar dari $endDate
            // ini bisa terjadi jika $effectiveStartMonth berasal dari tahun depan (semester ganjil di tahun berikutnya)
            // sedangkan $baseYear adalah tahun semester saat ini.
            if ($startDate->greaterThan($endDate)) {
                $startDate->subYear(); // Misal, Sept di tahun sebelumnya
                // Periksa lagi setelah subYear, jika masih belum benar, tambahkan tahun ke endDate
                if ($startDate->greaterThan($endDate)) {
                    $endDate->addYear(); // Ini harusnya tidak terjadi jika logic awal sudah benar
                }
            }

            Log::info('DEBUG: Final Start Date: ' . $startDate->toDateString());
            Log::info('DEBUG: Final End Date: ' . $endDate->toDateString());


            // Informasi Filter untuk Display di Excel Header
            $namaInstitusi = 'POLITEKNIK NEGERI JEMBER';
            $appName = config('app.name', 'SIKMA');
            $namaSemesterFilter = $semesterModel->semester_name;
            $namaProgramStudiFilter = 'Semua Program Studi';
            if ($selectedProgramId) {
                $programStudiModel = ProgramStudi::find($selectedProgramId);
                if ($programStudiModel) {
                    $namaProgramStudiFilter = $programStudiModel->name;
                }
            }

            $filtersForExport = [
                'program_id' => $selectedProgramId,
                'program_nama' => $namaProgramStudiFilter,
                'semester_id' => $selectedSemesterId,
                'semester_nama' => $namaSemesterFilter,
                'tahun_ajaran_display' => $semesterModel->tahun_ajaran ?? ($baseYear . '/' . ($baseYear + 1)), // Ambil dari model jika ada, fallback ke baseYear
                'rentang_bulan_display' => $startDate->translatedFormat('F Y') . ' - ' . $endDate->translatedFormat('F Y'),
            ];

            // --- 2. Kumpulkan Tanggal Kolom yang Relevan (untuk Header Excel) ---
            $period = CarbonPeriod::create($startDate, $endDate);
            $dateColumns = [];
            foreach ($period as $date) {
                // Opsional: filter hanya hari kerja
                // if ($date->isWeekday()) {
                $dateColumns[$date->toDateString()] = [
                    'day_number' => $date->format('j'),
                    'day_name_short' => $date->translatedFormat('D')
                ];
                // }
            }

            Log::info('DEBUG: Number of date columns: ' . count($dateColumns));

            if (empty($dateColumns)) {
                return back()->with('info', 'Tidak ada tanggal yang valid dalam rentang bulan yang dipilih untuk diexport. Rentang tanggal: ' . $startDate->toDateString() . ' sampai ' . $endDate->toDateString());
            }

            // --- 3. Kumpulkan Data Mahasiswa yang Sudah Difilter dengan Benar ---
            $mahasiswaQuery = User::where('role', 'Mahasiswa');

            // FILTER UTAMA: MAHASISWA BERDASARKAN SEMESTER ASAL MEREKA
            $mahasiswaQuery->where('id_semester', $selectedSemesterId);

            if ($selectedProgramId) {
                $mahasiswaQuery->where('id_prodi', $selectedProgramId);
            }

            if ($request->filled('search')) {
                $mahasiswaQuery->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('nim', 'like', "%{$request->search}%");
                });
            }

            $mahasiswaCollection = $mahasiswaQuery->orderBy('name')->get();
            Log::info('DEBUG: Number of students found: ' . $mahasiswaCollection->count());

            if ($mahasiswaCollection->isEmpty()) {
                return back()->with('info', 'Tidak ada data mahasiswa yang sesuai dengan filter untuk diexport.');
            }

            // --- 4. Kumpulkan Data Presensi yang Relevan ---
            $mahasiswaIds = $mahasiswaCollection->pluck('id')->all();
            $presensiData = Presensi::whereIn('user_id', $mahasiswaIds)
                ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
                ->get()
                ->groupBy('user_id')
                ->map(function ($presencesForUser) {
                    return $presencesForUser->keyBy(function ($p) {
                        return Carbon::parse($p->tanggal)->toDateString();
                    });
                });

            Log::info('DEBUG: Total unique users with presensi data: ' . $presensiData->count());

            if ($presensiData->isNotEmpty()) {
                $firstUserId = $presensiData->keys()->first();
                Log::info('DEBUG: Presensi for first user (' . $firstUserId . '): ' . $presensiData->get($firstUserId)->toJson());
            }

            // --- 5. Siapkan Data Mahasiswa per Prodi untuk Multi-Sheet Export ---
            $mahasiswaPerProdi = $mahasiswaCollection->groupBy('id_prodi');

            $prodiIdsInCollection = $mahasiswaPerProdi->keys()->filter(fn($key) => $key !== null && $key !== '')->all();
            $programStudisForSheets = ProgramStudi::whereIn('id', $prodiIdsInCollection)->orderBy('name')->get();

            if ($mahasiswaPerProdi->has('') || $mahasiswaPerProdi->has(null)) {
                $programStudisForSheets->prepend((object)['id' => '', 'name' => 'Tanpa Prodi']);
            }

            // Jika ada filter program studi spesifik, pastikan hanya prodi itu yang ada di list
            if ($selectedProgramId && $programStudisForSheets->isNotEmpty()) {
                $programStudisForSheets = $programStudisForSheets->filter(fn($prodi) => $prodi->id == $selectedProgramId);
            }

            // --- 6. Finalisasi Data Export ---
            $exportData = [
                'namaInstitusi' => $namaInstitusi,
                'appName' => $appName,
                'filters' => $filtersForExport,
                'dateColumns' => $dateColumns,
                'presensiData' => $presensiData,
                'programStudis' => $programStudisForSheets,
                'mahasiswaPerProdi' => $mahasiswaPerProdi,
                'bulanRange' => [
                    'monthFrom' => $effectiveStartMonth,
                    'monthTo' => $effectiveEndMonth,
                    'year' => $baseYear,
                ],
            ];

            $fileName = 'Rekap_Kehadiran_' . Str::slug($namaSemesterFilter) . '_' . Str::slug($namaProgramStudiFilter) . '_' . Carbon::now()->format('YmdHis') . '.xlsx';

            return Excel::download(new RekapitulasiKehadiranExport($exportData), $fileName);
        } catch (\Exception $e) {
            // Log error secara detail untuk debugging server-side
            Log::error('Error generating Excel Rekapitulasi: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_input' => $request->all() // Log input request untuk debugging
            ]);
            // Redirect kembali ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Gagal membuat file Excel: Terjadi kesalahan pada server. Silakan hubungi administrator.');
        }
    }

    public function exportPDF(Request $request)
    {
        try {
            // Ambil data yang sudah difilter (tidak dipaginasi untuk export)
            $mahasiswaDataCollection = $this->getFilteredMahasiswaData($request, true);

            $filters = $request->only(['search', 'program', 'semester', 'dateFrom', 'dateTo']);

            // Get Program Studi info
            $programStudi = null;
            if ($request->filled('program')) {
                $programStudi = ProgramStudi::find($request->program);
                $filters['program_nama'] = $programStudi ? $programStudi->name : 'N/A';
            } else {
                $filters['program_nama'] = 'Semua Program Studi';
            }

            // Get Semester info
            $semester = null;
            if ($request->filled('semester')) {
                $semester = Semester::find($request->semester);
                // PERBAIKAN: Gunakan kolom 'semester_name'
                $filters['semester_nama'] = $semester ? $semester->semester_name : 'N/A';
            } else {
                $filters['semester_nama'] = 'Semua Semester';
            }

            // Hitung statistik kehadiran - GUNAKAN FIELD YANG SUDAH DIHITUNG
            $summaryHadir = 0;
            $summaryTerlambat = 0;
            $summaryTidakHadir = 0;
            $summaryIzinSakit = 0;

            foreach ($mahasiswaDataCollection as $mahasiswa) {
                $summaryHadir += $mahasiswa->total_hadir ?? 0;
                $summaryTerlambat += $mahasiswa->total_terlambat ?? 0;
                $summaryTidakHadir += $mahasiswa->total_tidak_hadir ?? 0;
                $summaryIzinSakit += $mahasiswa->total_izin_sakit ?? 0;
            }

            // Transform data mahasiswa untuk template
            $studentsData = $mahasiswaDataCollection->map(function ($mahasiswa, $index) {
                return [
                    'no' => $index + 1,
                    'nim' => $mahasiswa->nim ?? '-',
                    'name' => $mahasiswa->name ?? '-',
                    'program_studi' => $mahasiswa->programStudi ? $mahasiswa->programStudi->name : 'N/A',
                    'semester' => $mahasiswa->semester ? ($mahasiswa->semester->semester_ke ?? $mahasiswa->semester->name) : 'N/A',
                    'totalhadir' => $mahasiswa->total_hadir ?? 0,
                    'totaltidakhadir' => $mahasiswa->total_tidak_hadir ?? 0,
                    'totalizinsakit' => $mahasiswa->total_izin_sakit ?? 0,
                    'totalterlambat' => $mahasiswa->total_terlambat ?? 0,
                    'persentase_kehadiran' => $mahasiswa->persentase_kehadiran ?? 0,
                    'status_akhir' => $mahasiswa->status_kehadiran_keseluruhan ?? 'Kurang'
                ];
            })->toArray();

            // Siapkan data untuk template sesuai dengan struktur HTML
            $reportData = [
                // Header Information
                'title' => 'SIKMA - Rekapitulasi Presensi',
                'logoChar' => 'S',
                'namaSistem' => config('app.name', 'SIKMA'),
                'namaInstitusi' => 'Sistem Kehadiran Mahasiswa',

                // Report Title
                'reportTitle' => 'Rekapitulasi Presensi',
                'reportSubtitle' => 'Laporan Kehadiran Mahasiswa',

                // Info Grid Data
                'infoSemesterTahun' => $filters['semester_nama'] . ' / ' . date('Y'),
                'infoKelas' => '-',
                'infoProgramStudi' => $filters['program_nama'],
                'infoPeriode' => $this->getPeriodeText($request),

                // Summary Statistics
                'summaryHadir' => $summaryHadir,
                'summaryTerlambat' => $summaryTerlambat,
                'summaryTidakHadir' => $summaryTidakHadir,
                'summaryIzinSakit' => $summaryIzinSakit,
                'summaryTotalMahasiswa' => $mahasiswaDataCollection->count(),

                // Students Data
                'students' => $studentsData,

                // Footer/Signature Information
                'namaKaprodi' => $programStudi ? ($programStudi->kepala_prodi ?? '(....................................)') : '(..................................)',
                'infoDosenPengampu' => '(..................................)',
                'kotaTtd' => 'Jember',
                'tanggalTtd' => Carbon::now()->translatedFormat('d F Y'),
            ];

            // Data untuk view
            $dataForView = [
                'reportData' => $reportData,
                'filters' => $filters,
                'reportDate' => Carbon::now()->translatedFormat('l, d F Y'),
                'reportTimestamp' => Carbon::now()->translatedFormat('H:i') . ' WIB',
                'appName' => config('app.name', 'SIKMA')
            ];

            // Debug: Log sample data untuk troubleshooting
            Log::info('Sample students data for PDF:', [
                'total_students' => count($studentsData),
                'summary' => [
                    'hadir' => $summaryHadir,
                    'tidak_hadir' => $summaryTidakHadir,
                    'izin_sakit' => $summaryIzinSakit,
                    'terlambat' => $summaryTerlambat
                ],
                'first_student' => $studentsData[0] ?? null
            ]);

            // Render Blade view ke HTML string
            $html = view('exports.export-pdf', $dataForView)->render();

            // Generate PDF menggunakan Browsershot dengan MARGIN NOL
            $pdf = Browsershot::html($html)
                ->setNodeBinary(env('NODE_BINARY_PATH', "C:/Program Files/nodejs/node.exe"))
                ->setNpmBinary(env('NPM_BINARY_PATH', "C:/Program Files/nodejs/npm.cmd"))
                ->noSandbox()
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();

            // Generate filename dengan timestamp
            $filename = 'rekapitulasi_kehadiran_' .
                ($programStudi ? Str::slug($programStudi->name) . '_' : '') .
                ($semester ? Str::slug($semester->semester_ke ?? $semester->name) . '_' : '') .
                Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            Log::error('Error generating PDF with Browsershot: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return response()->json([
                'error' => 'Gagal membuat PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getPeriodeText(Request $request)
    {
        $dateFrom = $request->filled('dateFrom') ? Carbon::parse($request->dateFrom)->translatedFormat('d F Y') : null;
        $dateTo = $request->filled('dateTo') ? Carbon::parse($request->dateTo)->translatedFormat('d F Y') : null;

        if ($dateFrom && $dateTo) {
            return $dateFrom . ' - ' . $dateTo;
        } elseif ($dateFrom) {
            return 'Mulai ' . $dateFrom;
        } elseif ($dateTo) {
            return 'Sampai ' . $dateTo;
        } else {
            return 'Semua Periode';
        }
    }

    public function pdfPreview(Request $request)
    {
        $mahasiswaDataCollection = $this->getFilteredMahasiswaData($request, true);

        $filters = $request->only(['search', 'program', 'semester', 'dateFrom', 'dateTo']);

        // Get Program Studi info
        $programStudi = null;
        if ($request->filled('program')) {
            $programStudi = ProgramStudi::find($request->program);
            $filters['program_nama'] = $programStudi ? $programStudi->name : 'N/A';
        } else {
            $filters['program_nama'] = 'Semua Program Studi';
        }

        // Get Semester info
        $semester = null;
        if ($request->filled('semester')) {
            $semester = Semester::find($request->semester);
            $filters['semester_nama'] = $semester ? ($semester->semester_ke ?? $semester->name) : 'N/A';
        } else {
            $filters['semester_nama'] = 'Semua Semester';
        }

        // Hitung statistik kehadiran - GUNAKAN FIELD YANG SUDAH DIHITUNG
        $summaryHadir = 0;
        $summaryTerlambat = 0;
        $summaryTidakHadir = 0;
        $summaryIzinSakit = 0;

        foreach ($mahasiswaDataCollection as $mahasiswa) {
            $summaryHadir += $mahasiswa->total_hadir ?? 0;
            $summaryTerlambat += $mahasiswa->total_terlambat ?? 0;
            $summaryTidakHadir += $mahasiswa->total_tidak_hadir ?? 0;
            $summaryIzinSakit += $mahasiswa->total_izin_sakit ?? 0;
        }

        // Transform data mahasiswa untuk template
        $studentsData = $mahasiswaDataCollection->map(function ($mahasiswa, $index) {
            return [
                'no' => $index + 1,
                'nim' => $mahasiswa->nim ?? '-',
                'name' => $mahasiswa->name ?? '-',
                'program_studi' => $mahasiswa->programStudi ? $mahasiswa->programStudi->name : 'N/A',
                'semester' => $mahasiswa->semester ? ($mahasiswa->semester->semester_ke ?? $mahasiswa->semester->name) : 'N/A',
                'totalhadir' => $mahasiswa->total_hadir ?? 0,
                'totaltidakhadir' => $mahasiswa->total_tidak_hadir ?? 0,
                'totalizinsakit' => $mahasiswa->total_izin_sakit ?? 0,
                'totalterlambat' => $mahasiswa->total_terlambat ?? 0,
                'persentase_kehadiran' => $mahasiswa->persentase_kehadiran ?? 0,
                'status_akhir' => $mahasiswa->status_kehadiran_keseluruhan ?? 'Kurang'
            ];
        })->toArray();

        // Siapkan data untuk template sesuai dengan struktur HTML
        $reportData = [
            // Header Information
            'title' => 'SIKMA - Rekapitulasi Presensi',
            'logoChar' => 'S',
            'namaSistem' => config('app.name', 'SIKMA'),
            'namaInstitusi' => 'Sistem Kehadiran Mahasiswa',

            // Report Title
            'reportTitle' => 'Rekapitulasi Presensi',
            'reportSubtitle' => 'Laporan Kehadiran Mahasiswa',

            // Info Grid Data
            'infoSemesterTahun' => $filters['semester_nama'] . ' / ' . date('Y'),
            'infoKelas' => '-',
            'infoProgramStudi' => $filters['program_nama'],
            'infoPeriode' => $this->getPeriodeText($request),

            // Summary Statistics
            'summaryHadir' => $summaryHadir,
            'summaryTerlambat' => $summaryTerlambat,
            'summaryTidakHadir' => $summaryTidakHadir,
            'summaryIzinSakit' => $summaryIzinSakit,
            'summaryTotalMahasiswa' => $mahasiswaDataCollection->count(),

            // Students Data
            'students' => $studentsData,

            // Footer/Signature Information
            'namaKaprodi' => $programStudi ? ($programStudi->kepala_prodi ?? '(....................................)') : '(..................................)',
            'infoDosenPengampu' => '(..................................)',
            'kotaTtd' => 'Jember',
            'tanggalTtd' => Carbon::now()->translatedFormat('d F Y'),
        ];

        // Data untuk view
        $dataForView = [
            'reportData' => $reportData,
            'filters' => $filters,
            'reportDate' => Carbon::now()->translatedFormat('l, d F Y'),
            'reportTimestamp' => Carbon::now()->translatedFormat('H:i') . ' WIB',
            'appName' => config('app.name', 'SIKMA')
        ];
        return view('exports.export-pdf', $dataForView);
    }
}
