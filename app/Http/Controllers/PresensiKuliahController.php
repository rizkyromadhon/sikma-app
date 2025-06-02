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
// use Illuminate\Container\Attributes\Log;
use App\Events\PresensiCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class PresensiKuliahController extends Controller
{
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

            // Hitung minggu keberapa hari ini dalam bulan yang dipilih
            $tanggalHariIni = Carbon::now();
            $mingguSaatIni = Carbon::now()->weekOfMonth;

            // Ambil minggu dari request, atau default ke minggu saat ini
            $mingguRequest = (int) $request->input('minggu', $mingguSaatIni);

            // Ambil presensi berdasarkan filter bulan, minggu, dan mata kuliah
            $presensiQuery = Presensi::with(['user', 'jadwalKuliah', 'mataKuliah'])
                ->where('user_id', $userId)
                ->whereMonth('tanggal', $bulanSekarang);

            // Tambahkan filter mata kuliah jika dipilih
            if ($mataKuliahSelected) {
                $presensiQuery->where('id_matkul', $mataKuliahSelected);
            }

            if ($mingguRequest) {
                $tanggalAwal = Carbon::create(null, $bulanSekarang, 1)->startOfMonth();
                $tanggalAkhir = $tanggalAwal->copy()->addWeeks($mingguRequest - 1)->endOfWeek();
                $presensiQuery->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
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
    public function ajaxFilter(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $semesterTempuh = $user->id_semester;
        $isGanjil = $semesterTempuh % 2 != 0;
        $bulanPilihan = $isGanjil ? range(7, 12) : range(1, 6);

        $bulanRequest = (int) $request->input('bulan', $bulanPilihan[0]);
        $bulanSekarang = in_array($bulanRequest, $bulanPilihan) ? $bulanRequest : $bulanPilihan[0];
        $mingguRequest = (int) $request->input('minggu', Carbon::now()->weekOfMonth);
        $mataKuliahSelected = $request->input('mata_kuliah');

        $presensiQuery = Presensi::with('mataKuliah')
            ->where('user_id', $userId)
            ->whereMonth('tanggal', $bulanSekarang);

        if ($mataKuliahSelected) {
            $presensiQuery->where('id_matkul', $mataKuliahSelected);
        }

        if ($mingguRequest) {
            $tanggalAwal = Carbon::create(null, $bulanSekarang, 1)->startOfMonth();
            $tanggalAkhir = $tanggalAwal->copy()->addWeeks($mingguRequest - 1)->endOfWeek();
            $presensiQuery->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
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

    // public function store(Request $request)
    // {
    //     // Ambil UID dari request
    //     $uid = $request->input('uid');

    //     // Tulis UID ke log Laravel
    //     Log::info('UID diterima dari ESP32:', ['uid' => $uid]);

    //     // Kirim event ke channel lain
    //     broadcast(new PresensiCreated($uid))->toOthers();

    //     // Kirim respons balik
    //     return response()->json([
    //         'message' => 'UID diterima',
    //         'uid' => $uid
    //     ]);
    // }

    public function store(Request $request)
    {
        // $uid = $request->input('uid');
        $validated = $request->validate([
            // 'id' => 'required',
            'uid' => 'required'
        ]);

        $userUid = $validated['uid'];
        $user = User::where('role', 'mahasiswa')->where('uid', $userUid)->first();
        // $user = User::findOrFail($userId);
        $parts = explode(' ', trim($user->name));
        $lastName = end($parts);
        $now = Carbon::now();
        $hari = $now->translatedFormat('l');
        $jam = $now->format('H:i:s');
        $tanggal = now()->format('Y-m-d');

        if (!$user) {
            return response()->json([
                'message' => 'User tidak terdaftar'
            ], 404);
        }

        $jadwal = JadwalKuliah::where('id_prodi', $user->id_prodi)
            ->where('id_golongan', $user->id_golongan)
            ->where('id_semester', $user->id_semester)
            ->where('hari', $hari)
            ->whereTime('jam_mulai', '<=', $jam)
            ->whereTime('jam_selesai', '>=', $jam)
            ->orderBy('jam_mulai', 'desc') // untuk berjaga kalau ada dua jadwal yang tumpang tindih
            ->first();

        if (!$jadwal) {
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', tidak ada jadwal kuliah saat ini.'
            ], 400);
        }
        $toleransi = Carbon::parse($jadwal->jam_mulai)->addMinutes(15)->format('H:i:s');

        if ($now->greaterThan($toleransi)) {
            return response()->json([
                'message' => 'Maaf ' . $lastName . ', Anda terlambat.'
            ], 400);
        } else {
            // Cek apakah sudah presensi sebelumnya
            $sudahPresensi = Presensi::where('user_id', $user->id)
                ->where('id_jadwal_kuliah', $jadwal->id)
                ->where('tanggal', $tanggal)
                ->exists();

            if ($sudahPresensi) {
                return response()->json([
                    'message' => 'Maaf ' . $lastName . ', Anda sudah presensi untuk jadwal ini.'
                ], 400);
            }

            // Simpan presensi
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'id_matkul' => $jadwal->id_matkul,
                'id_jadwal_kuliah' => $jadwal->id,
                'waktu_presensi' => now(),
                'tanggal' => today(),
                'status' => 'Hadir'
            ]);


            broadcast(new PresensiCreated($presensi))->toOthers();

            return response()->json([
                'message' => 'Hai ' . $lastName  . ', Berhasil melakukan presensi.'
            ], 200);
        }
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
