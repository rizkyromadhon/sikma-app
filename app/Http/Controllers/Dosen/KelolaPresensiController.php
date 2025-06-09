<?php

namespace App\Http\Controllers\Dosen;

use Carbon\Carbon;
use PresensiKuliah;
use App\Models\User;
use App\Models\Presensi;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KelolaPresensiController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input tanggal, jika tidak valid, gunakan hari ini
        $validated = $request->validate(['tanggal' => 'nullable|date_format:Y-m-d']);

        // Tentukan tanggal yang dipilih. Default ke hari ini jika tidak ada input.
        $selectedDate = Carbon::parse($validated['tanggal'] ?? now())->startOfDay();

        $dosenId = Auth::id();

        // Dapatkan nama hari dari tanggal yang dipilih (misal: "Senin")
        $namaHariDipilih = $selectedDate->translatedFormat('l');

        // 1. Ambil jadwal dosen yang sesuai dengan HARI dari tanggal yang dipilih
        $jadwalPadaHariItu = JadwalKuliah::with(['mataKuliah', 'ruangan', 'prodi', 'semester', 'golongan'])
            ->where('id_user', $dosenId)
            ->where('hari', $namaHariDipilih)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // 2. Kelompokkan jadwal untuk menggabungkan kelas besar
        $grupJadwalSama = $jadwalPadaHariItu->groupBy(function ($jadwal) {
            return $jadwal->id_matkul . '-' . $jadwal->jam_mulai;
        });

        // 3. Proses setiap grup untuk menambahkan data presensi dan status
        $jadwalDosen = $grupJadwalSama->map(function ($grup) use ($selectedDate) {
            $jadwalUtama = $grup->first();
            $jadwalIds = $grup->pluck('id');

            // Hitung total mahasiswa yang terdaftar di grup kelas ini
            $totalMahasiswaKelas = 0;
            // ... (Logika perhitungan total mahasiswa tetap sama) ...
            $grupKelasUnik = $grup->unique(function ($item) {
                return $item['id_prodi'] . '-' . $item['id_semester'] . '-' . $item['id_golongan'];
            });
            foreach ($grupKelasUnik as $g) {
                $query = User::where('role', 'mahasiswa')
                    ->where('id_prodi', $g->id_prodi)
                    ->where('id_semester', $g->id_semester);
                if ($g->golongan && $g->golongan->nama_golongan !== 'Semua Golongan') {
                    $query->where('id_golongan', $g->id_golongan);
                }
                $totalMahasiswaKelas += $query->count();
            }

            // [PENTING] Hitung jumlah hadir dari TANGGAL YANG DIPILIH
            $jumlahHadir = Presensi::whereIn('id_jadwal_kuliah', $jadwalIds)
                ->whereDate('tanggal', $selectedDate)
                ->where('status', 'hadir')
                ->distinct('user_id')
                ->count();

            // Tentukan status kelas berdasarkan TANGGAL YANG DIPILIH
            $jamMulai = Carbon::parse($selectedDate->toDateString() . ' ' . $jadwalUtama->jam_mulai);
            $jamSelesai = Carbon::parse($selectedDate->toDateString() . ' ' . $jadwalUtama->jam_selesai);
            $statusKelas = 'Akan Datang';
            if (now()->between($jamMulai, $jamSelesai)) {
                $statusKelas = 'Berlangsung';
            } elseif (now()->isAfter($jamSelesai) || $selectedDate->isPast()) {
                $statusKelas = 'Selesai';
            }

            // Siapkan data untuk view
            $jadwalUtama->is_kelas_besar = $grup->count() > 1;
            $jadwalUtama->semua_golongan_string = $grup->pluck('golongan.nama_golongan')->sort()->join(', ');
            $jadwalUtama->total_mahasiswa_kelas = $totalMahasiswaKelas;
            $jadwalUtama->jumlah_hadir = $jumlahHadir;
            $jadwalUtama->status_kelas = $statusKelas;
            $jadwalUtama->persentase_kehadiran = ($totalMahasiswaKelas > 0) ? ($jumlahHadir / $totalMahasiswaKelas) * 100 : 0;

            return $jadwalUtama;
        });

        // Kirim data ke view
        return view('dosen.kelola-presensi.index', [
            'jadwalDosen' => $jadwalDosen,
            'selectedDate' => $selectedDate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($jadwalId, $tanggal)
    {
        $dosenId = Auth::id();
        $selectedDate = Carbon::parse($tanggal)->startOfDay();

        $jadwalUtama = JadwalKuliah::with(['mataKuliah', 'ruangan', 'prodi', 'semester', 'golongan'])
            ->where('id_user', $dosenId)->findOrFail($jadwalId);

        $grupJadwal = JadwalKuliah::where('id_user', $dosenId)
            ->where('id_matkul', $jadwalUtama->id_matkul)
            ->where('hari', $jadwalUtama->hari)
            ->where('jam_mulai', $jadwalUtama->jam_mulai)
            ->get();

        $jadwalIds = $grupJadwal->pluck('id');

        // ... (Logika mengambil semua mahasiswa kelas tetap sama) ...
        $semuaMahasiswaKelas = collect();
        $grupKelasUnik = $grupJadwal->unique(fn($item) => $item['id_prodi'] . '-' . $item['id_semester'] . '-' . $item['id_golongan']);
        foreach ($grupKelasUnik as $grup) {
            $query = User::where('role', 'mahasiswa')
                ->where('id_prodi', $grup->id_prodi)
                ->where('id_semester', $grup->id_semester);
            if ($grup->golongan && $grup->golongan->nama_golongan !== 'Semua Golongan') {
                $query->where('id_golongan', $grup->id_golongan);
            }
            $semuaMahasiswaKelas = $semuaMahasiswaKelas->merge($query->get());
        }
        $semuaMahasiswaKelas = $semuaMahasiswaKelas->unique('id');

        // [DIPERBAIKI] Ambil data presensi dari TANGGAL YANG DIPILIH
        $dataPresensi = Presensi::whereIn('id_jadwal_kuliah', $jadwalIds)
            ->whereDate('tanggal', $selectedDate)
            ->get()
            ->keyBy('user_id');

        // [DIPERBAIKI] Logika penggabungan data
        $daftarHadirLengkap = $semuaMahasiswaKelas->map(function ($mahasiswa) use ($dataPresensi) {
            $presensi = $dataPresensi->get($mahasiswa->id);
            $status = 'Tidak Hadir';
            $waktu = '-';

            if ($presensi) {
                $status = $presensi->status;
                if ($status == 'Izin' || $status == 'Sakit') {
                    $status = 'Izin/Sakit';
                }
                $waktu = ($status == 'Hadir') ? Carbon::parse($presensi->waktu_presensi)->format('H:i') : '-';
            }

            $mahasiswa->status_kehadiran = $status;
            $mahasiswa->waktu_kehadiran = $waktu;
            return $mahasiswa;
        });

        // [DIPERBAIKI] Hitung statistik sesuai tampilan baru
        $stats = $daftarHadirLengkap->countBy('status_kehadiran');

        return view('dosen.kelola-presensi.detail', [
            'jadwal' => $jadwalUtama,
            'daftarHadir' => $daftarHadirLengkap->sortBy('name'),
            'stats' => [
                'total' => $daftarHadirLengkap->count(),
                'Hadir' => $stats->get('Hadir', 0),
                'Izin/Sakit' => $stats->get('Izin/Sakit', 0),
                'Tidak Hadir' => $stats->get('Tidak Hadir', 0) + $stats->get('Absen', 0),
            ],
            'tanggal' => $tanggal,
        ]);
    }

    public function updateStatus(Request $request, $jadwalId, $tanggal)
    {
        // [DIPERBAIKI] Validasi menggunakan mahasiswa_id
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => ['required', Rule::in(['Hadir', 'Izin/Sakit', 'Tidak Hadir'])],
        ]);

        $jadwal = JadwalKuliah::findOrFail($jadwalId);
        $waktuPresensiFinal = null; // Defaultnya adalah NULL

        if ($request->status == 'Hadir') {
            $waktuPresensiFinal = Carbon::parse($tanggal . ' ' . $jadwal->jam_mulai);
        }

        // [DIPERBAIKI] Gunakan tanggal dari parameter, bukan tanggal hari ini
        Presensi::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'id_jadwal_kuliah' => $jadwal->id,
                'tanggal' => Carbon::parse($tanggal)->toDateString(),
            ],
            [
                'id_matkul' => $jadwal->id_matkul,
                'status' => $request->status,
                'waktu_presensi' => $waktuPresensiFinal,
                'keterangan' => 'Divalidasi oleh ' . Auth::user()->name . ' pada ' . now()->format('Y-m-d'),
            ]
        );

        return back()->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
