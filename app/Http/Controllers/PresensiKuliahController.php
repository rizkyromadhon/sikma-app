<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Golongan;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Events\PresensiCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Events\KehadiranDiperbarui;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class PresensiKuliahController extends Controller
{
    // Perbaikan untuk method index dengan logika minggu dimulai dari Senin pertama
    public function index(Request $request)
    {
        $user = Auth::user();
        $presensi = [];
        $mata_kuliah = [];
        $bulanPilihan = [];
        $semesterTempuh = [];
        $bulanSekarang = [];
        $presensiGrouped = [];
        $mingguRequest = [];

        if ($user) {
            $userId = Auth::id();
            $semesterTempuh = $user->id_semester;
            $isGanjil = $semesterTempuh % 2 != 0;

            $bulanPilihan = $isGanjil ? range(7, 12) : range(1, 6);

            $bulanRequest = (int) $request->input('bulan', $bulanPilihan[0]);
            $bulanSekarang = in_array($bulanRequest, $bulanPilihan) ? $bulanRequest : $bulanPilihan[0];

            // Ambil mata kuliah yang dipilih oleh user (jika ada)
            $mataKuliahSelected = $request->input('mata_kuliah');

            // Ambil minggu dari request
            $mingguRequest = (int) $request->input('minggu');

            // Ambil presensi berdasarkan filter bulan, minggu, dan mata kuliah
            $presensiQuery = Presensi::with(['user', 'jadwalKuliah', 'mataKuliah'])
                ->where('user_id', $userId)
                ->whereMonth('tanggal', $bulanSekarang)
                ->orderBy('tanggal', 'asc');

            // Tambahkan filter mata kuliah jika dipilih
            if ($mataKuliahSelected) {
                $presensiQuery->where('id_matkul', $mataKuliahSelected);
            }

            // Filter berdasarkan minggu jika dipilih
            if ($mingguRequest) {
                $tahunSekarang = Carbon::now()->year;

                // Ambil tanggal 1 bulan yang dipilih
                $tanggal1 = Carbon::create($tahunSekarang, $bulanSekarang, 1);

                // Cari Senin pertama dalam bulan tersebut
                $seninPertama = $tanggal1->copy();

                // Jika tanggal 1 bukan Senin, cari Senin berikutnya
                if ($seninPertama->dayOfWeek != Carbon::MONDAY) {
                    $seninPertama = $seninPertama->next(Carbon::MONDAY);
                }

                // Pastikan Senin pertama masih dalam bulan yang sama
                if ($seninPertama->month != $bulanSekarang) {
                    // Jika tidak ada Senin dalam bulan ini, return empty
                    $presensiQuery->whereRaw('1 = 0'); // Kondisi yang selalu false
                } else {
                    // Hitung minggu ke-N dari Senin pertama
                    $tanggalMulaiMinggu = $seninPertama->copy()->addWeeks($mingguRequest - 1);
                    $tanggalAkhirMinggu = $tanggalMulaiMinggu->copy()->addDays(6); // Senin sampai Minggu

                    // Pastikan masih dalam bulan yang sama
                    $akhirBulan = Carbon::create($tahunSekarang, $bulanSekarang, 1)->endOfMonth();

                    if ($tanggalMulaiMinggu->month == $bulanSekarang) {
                        // Pastikan tanggal akhir tidak melewati akhir bulan
                        if ($tanggalAkhirMinggu->gt($akhirBulan)) {
                            $tanggalAkhirMinggu = $akhirBulan;
                        }

                        $presensiQuery->whereBetween('tanggal', [
                            $tanggalMulaiMinggu->format('Y-m-d'),
                            $tanggalAkhirMinggu->format('Y-m-d')
                        ]);
                    } else {
                        // Jika minggu yang diminta sudah keluar dari bulan, return empty
                        $presensiQuery->whereRaw('1 = 0');
                    }
                }
            }

            $presensi = $presensiQuery->get();
            $mata_kuliah = MataKuliah::with('presensi')->get();

            // Mengelompokkan presensi berdasarkan tanggal
            $presensiGrouped = $presensi->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });
        }

        return view('presensi-kuliah', [
            'presensi' => $presensi,
            'mata_kuliah' => $mata_kuliah,
            'bulanPilihan' => $bulanPilihan,
            'semesterTempuh' => $semesterTempuh,
            'bulanSekarang' => $bulanSekarang,
            'presensiGrouped' => $presensiGrouped,
            'mingguRequest' => $mingguRequest
        ]);
    }

    // Perbaikan untuk method ajaxFilter dengan logika minggu dimulai dari Senin pertama
    public function ajaxFilter(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $semesterTempuh = $user->id_semester;
        $isGanjil = $semesterTempuh % 2 != 0;
        $bulanPilihan = $isGanjil ? range(7, 12) : range(1, 6);

        $bulanRequest = (int) $request->input('bulan', $bulanPilihan[0]);
        $bulanSekarang = in_array($bulanRequest, $bulanPilihan) ? $bulanRequest : $bulanPilihan[0];
        $mingguRequest = (int) $request->input('minggu');
        $mataKuliahSelected = $request->input('mata_kuliah');

        $presensiQuery = Presensi::with('mataKuliah')
            ->where('user_id', $userId)
            ->whereMonth('tanggal', $bulanSekarang)
            ->orderBy('tanggal', 'asc');

        if ($mataKuliahSelected) {
            $presensiQuery->where('id_matkul', $mataKuliahSelected);
        }

        // Filter berdasarkan minggu jika dipilih
        if ($mingguRequest) {
            $tahunSekarang = Carbon::now()->year;

            // Ambil tanggal 1 bulan yang dipilih
            $tanggal1 = Carbon::create($tahunSekarang, $bulanSekarang, 1);

            // Cari Senin pertama dalam bulan tersebut
            $seninPertama = $tanggal1->copy();

            // Jika tanggal 1 bukan Senin, cari Senin berikutnya
            if ($seninPertama->dayOfWeek != Carbon::MONDAY) {
                $seninPertama = $seninPertama->next(Carbon::MONDAY);
            }

            // Pastikan Senin pertama masih dalam bulan yang sama
            if ($seninPertama->month != $bulanSekarang) {
                // Jika tidak ada Senin dalam bulan ini, return empty
                $presensiQuery->whereRaw('1 = 0'); // Kondisi yang selalu false
            } else {
                // Hitung minggu ke-N dari Senin pertama
                $tanggalMulaiMinggu = $seninPertama->copy()->addWeeks($mingguRequest - 1);
                $tanggalAkhirMinggu = $tanggalMulaiMinggu->copy()->addDays(6); // Senin sampai Minggu

                // Pastikan masih dalam bulan yang sama
                $akhirBulan = Carbon::create($tahunSekarang, $bulanSekarang, 1)->endOfMonth();

                if ($tanggalMulaiMinggu->month == $bulanSekarang) {
                    // Pastikan tanggal akhir tidak melewati akhir bulan
                    if ($tanggalAkhirMinggu->gt($akhirBulan)) {
                        $tanggalAkhirMinggu = $akhirBulan;
                    }

                    $presensiQuery->whereBetween('tanggal', [
                        $tanggalMulaiMinggu->format('Y-m-d'),
                        $tanggalAkhirMinggu->format('Y-m-d')
                    ]);
                } else {
                    // Jika minggu yang diminta sudah keluar dari bulan, return empty
                    $presensiQuery->whereRaw('1 = 0');
                }
            }
        }

        $presensi = $presensiQuery->get();

        // Format ke bentuk JSON yang mudah di-render frontend
        $result = $presensi->map(function ($item) {
            return [
                'tanggal' => Carbon::parse($item->tanggal)->locale('id')->translatedFormat('l, d-F-Y'),
                'mata_kuliah' => $item->mataKuliah->name ?? '-',
                'waktu' => $item->status === 'Hadir' ? Carbon::parse($item->waktu_presensi)->format('H:i') : '-',
                'status' => $item->status,
                'keterangan' => $item->keterangan ?? '-',
            ];
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        // Validasi input UID
        $validated = $request->validate([
            'uid' => 'required'
        ]);

        $userUid = $validated['uid'];

        // Cari user berdasarkan UID dan role mahasiswa
        $user = User::where('role', 'mahasiswa')->where('uid', $userUid)->first();

        // Jika user tidak ditemukan
        if (!$user) {
            return response()->json([
                'message' => 'User tidak terdaftar.'
            ], 404);
        }

        // Ambil nama belakang user untuk pesan
        $parts = explode(' ', trim($user->name));
        $lastName = end($parts);

        // Waktu saat ini
        $now = Carbon::now();
        $hariIni = $now->translatedFormat('l');
        $jamSekarang = $now->format('H:i:s');
        $tanggalSekarang = $now->format('Y-m-d');

        $jadwal = JadwalKuliah::with('matakuliah')
            ->where('id_prodi', $user->id_prodi)
            ->where('id_golongan', $user->id_golongan)
            ->where('id_semester', $user->id_semester)
            ->where('hari', $hariIni)
            ->whereTime('jam_selesai', '>=', $jamSekarang) // Memastikan jadwal belum benar-benar berakhir
            ->orderBy('jam_mulai', 'asc')                 // Ambil jadwal paling awal yang relevan
            ->first();

        if (!$jadwal) {
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', tidak ada jadwal kuliah yang aktif saat ini.'
            ], 400);
        }

        $jamMulaiJadwal = Carbon::parse($jadwal->jam_mulai);
        $jamSelesaiJadwal = Carbon::parse($jadwal->jam_selesai);

        $batasAwalPresensi = $jamMulaiJadwal->copy()->subMinutes(10);
        $batasAkhirToleransi = $jamMulaiJadwal->copy()->addMinutes(15); // Sesuai logika toleransi awal

        $namaMatkul = 'mata kuliah ini'; // Default
        if (isset($jadwal->matakuliah)) {
            if (isset($jadwal->matakuliah->name)) { // Sesuai preferensi dari feedback Anda
                $namaMatkul = $jadwal->matakuliah->name;
            } elseif (isset($jadwal->matakuliah->name)) {
                $namaMatkul = $jadwal->matakuliah->name;
            }
        }

        $sudahPresensi = Presensi::where('user_id', $user->id)
            ->where('id_jadwal_kuliah', $jadwal->id)
            ->where('tanggal', $tanggalSekarang)
            ->exists();

        if ($sudahPresensi) {
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', Anda sudah melakukan presensi untuk ' . $namaMatkul . ' hari ini.'
            ], 400);
        }

        if ($now->lessThan($batasAwalPresensi)) {
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', presensi untuk ' . $namaMatkul . ' baru dibuka pukul ' . $batasAwalPresensi->format('H:i') . '.'
            ], 400);
        }

        if ($now->greaterThan($batasAkhirToleransi)) {
            if ($now->greaterThan($jamSelesaiJadwal)) {
                return response()->json([
                    'message' => 'Maaf ' . $lastName . ', jadwal kuliah untuk ' . $namaMatkul . ' sudah berakhir pada pukul ' . $jamSelesaiJadwal->format('H:i') . '.'
                ], 400);
            }
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', Anda terlambat untuk melakukan presensi ' . $namaMatkul . '. Batas toleransi adalah pukul ' . $batasAkhirToleransi->format('H:i') . '.'
            ], 400);
        }

        if ($now->greaterThan($batasAkhirToleransi)) {
            // Periksa juga apakah keterlambatan ini masih dalam jam kuliah atau sudah lewat jam selesai
            if ($now->greaterThan($jamSelesaiJadwal)) {
                return response()->json([
                    'message' => 'Maaf ' . $lastName . ', jadwal kuliah untuk ' . $namaMatkul . ' sudah berakhir pada pukul ' . $jamSelesaiJadwal->format('H:i') . '.'
                ], 400);
            }
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', Anda terlambat untuk melakukan presensi ' . $namaMatkul . '. Batas toleransi adalah pukul ' . $batasAkhirToleransi->format('H:i') . '.'
            ], 400);
        }

        if ($now->greaterThan($jamSelesaiJadwal)) {
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', jadwal kuliah untuk ' . $namaMatkul . ' sudah berakhir pada pukul ' . $jamSelesaiJadwal->format('H:i') . '.'
            ], 400);
        }


        // Jika semua pengecekan lolos, simpan presensi
        $presensi = Presensi::create([
            'user_id' => $user->id,
            'id_matkul' => $jadwal->id_matkul, // Pastikan field ini sesuai dengan model JadwalKuliah Anda
            'id_jadwal_kuliah' => $jadwal->id,
            'waktu_presensi' => $now, // Simpan waktu presensi lengkap dengan tanggal dan jam
            'tanggal' => $tanggalSekarang, // Simpan tanggal presensi
            'status' => 'Hadir' // Atau status lain sesuai kebutuhan
        ]);

        // Broadcast event jika menggunakan Laravel Echo atau sejenisnya
        if (class_exists(PresensiCreated::class)) {
            broadcast(new PresensiCreated($presensi))->toOthers();
        }

        if ($presensi->jadwalKuliah) {
            Log::info('Mencoba mengirim event KehadiranDiperbarui untuk jadwal ID: ' . $presensi->jadwalKuliah->id);

            // [DIPERBAIKI] Gunakan broadcast() agar dikirim langsung tanpa antrean
            broadcast(new KehadiranDiperbarui($presensi->jadwalKuliah))->toOthers();
        }

        return response()->json([
            'message' => 'Hai ' . $lastName . ', presensi berhasil dicatat.'
        ], 200);
    }
    public function download($format)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $semesterTempuh = $user->id_semester; // Ambil id_semester dari user
        $prodiId = $user->id_prodi;
        $isGanjil = $semesterTempuh % 2 != 0;
        $bulanPilihan = $isGanjil ? range(7, 12) : range(1, 6);

        // Mengambil presensi dengan relasi ke tabel Mata Kuliah dan Program Studi
        $presensi = Presensi::where('user_id', $userId)
            ->whereIn(DB::raw('MONTH(tanggal)'), $bulanPilihan)
            ->whereYear('tanggal', now()->year)
            ->orderBy('tanggal')
            ->with(['mataKuliah']) // Menambahkan relasi untuk mata kuliah dan program studi
            ->get();

        // Mengambil nama semester
        $semester = Semester::find($semesterTempuh);
        $semesterText = $semester ? $semester->display_name : '0';
        $semesterNumber = explode(' ', $semesterText)[1] ?? $semesterText;

        $prodi = ProgramStudi::find($prodiId);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.presensi-pdf', [
                'presensi' => $presensi,
                'user' => $user,
                'semester' => $semesterNumber
            ]);
            return $pdf->download('Rekap_Presensi_Semester_' . $semesterTempuh . '.pdf');
        }

        // XLSX
        if ($format === 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set informasi mahasiswa di bagian atas
            $sheet->setCellValue('A1', 'Nama');
            $sheet->setCellValue('B1', ': ' . $user->name);

            $sheet->setCellValue('A2', 'NIM');
            $sheet->setCellValue('B2', ': ' . $user->nim);

            $sheet->setCellValue('A3', 'Semester');
            $sheet->setCellValue('B3', ': ' . $semesterNumber);

            $sheet->setCellValue('A4', 'Program Studi');
            $sheet->setCellValue('B4', ': ' . ($prodi ? $prodi->name : 'Tidak Ditemukan'));

            // Judul tabel (dengan sedikit jarak dari atas info)
            $sheet->setCellValue('A6', 'Tanggal');
            $sheet->setCellValue('B6', 'Mata Kuliah');
            $sheet->setCellValue('C6', 'Status');
            $sheet->setCellValue('D6', 'Keterangan');

            // Bold header
            $headerStyle = [
                'font' => ['bold' => true],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ],
            ];
            $sheet->getStyle('A6:D6')->applyFromArray($headerStyle);

            // Data presensi
            $row = 7;
            foreach ($presensi as $item) {
                $mataKuliah = optional($item->mataKuliah)->name; // Menyediakan fallback jika tidak ada mata kuliah
                $keterangan = $item->keterangan ?? '-'; // Jika keterangan null, tampilkan '-'

                // Menulis ke dalam sheet
                $sheet->setCellValue('A' . $row, Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY'));
                $sheet->setCellValue('B' . $row, $mataKuliah); // Menampilkan mata kuliah
                $sheet->setCellValue('C' . $row, ucfirst($item->status));
                $sheet->setCellValue('D' . $row, $keterangan); // Menampilkan keterangan
                $row++;
            }

            // Auto-size kolom
            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Nama file
            $filename = 'Rekap_Presensi_' . $user->nim . '_Semester_' . $semesterTempuh . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        }

        abort(404);
    }


    public function markAbsentForMissingPresences()
    {
        // Dapatkan hari ini
        $hariIni = Carbon::now()->translatedFormat('l'); // Mendapatkan nama hari dalam bahasa Indonesia

        // Dapatkan semua jadwal kuliah yang telah lewat waktunya berdasarkan hari ini
        $jadwalKuliah = JadwalKuliah::where('hari', $hariIni)
            ->whereTime('jam_selesai', '<', Carbon::now()) // Pastikan jadwal sudah lewat waktunya
            ->get();

        foreach ($jadwalKuliah as $jadwal) {
            // Dapatkan semua mahasiswa yang terdaftar di jadwal ini
            $mahasiswa = User::where('program_studi', $jadwal->program_studi)
                ->where('golongan_id', $jadwal->golongan_id)
                ->get();

            foreach ($mahasiswa as $user) {
                // Cek apakah mahasiswa ini sudah melakukan presensi pada hari ini
                $presensi = Presensi::where('user_id', $user->id)
                    ->where('jadwal_kuliah_id', $jadwal->id)
                    ->whereDate('waktu_presensi', Carbon::today()) // Hanya presensi hari ini
                    ->first();

                // Jika tidak ada presensi, maka tandai mahasiswa sebagai "Tidak Hadir"
                if (!$presensi) {
                    Presensi::create([
                        'user_id' => $user->id,
                        'waktu_presensi' => Carbon::now(),
                        'mata_kuliah' => $jadwal->mata_kuliah,
                        'jadwal_kuliah_id' => $jadwal->id,
                        'tanggal' => Carbon::today()->format('Y-m-d'),
                        'status' => 'Tidak hadir', // Menandai status Tidak Hadir
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Presensi Tidak Hadir telah diperbarui untuk jadwal yang terlewat.',
        ]);
    }
}
