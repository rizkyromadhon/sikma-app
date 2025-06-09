<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Admin\LaporanController;
use Carbon\Carbon;
use App\Models\Laporan;
use App\Models\Presensi;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Auth;

class PengajuanIzinController extends Controller
{
    public function indexMahasiswa()
    {
        $riwayatPengajuan = PengajuanIzin::with('jadwalKuliah.mataKuliah')
            ->where('id_user', Auth::id())
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Gunakan pagination

        return view('pengajuan-izin', [
            'riwayatPengajuan' => $riwayatPengajuan,
        ]);
    }
    public function index(Request $request)
    {
        // Validasi filter status, pastikan nilainya hanya salah satu dari ini
        $request->validate(['status' => 'nullable|in:Baru,Disetujui,Ditolak']);

        $dosenId = Auth::id();
        $statusFilter = $request->input('status', 'Baru'); // Default filter adalah 'Baru'

        // Ambil semua ID jadwal yang diampu oleh dosen ini
        $jadwalIds = JadwalKuliah::where('id_user', $dosenId)->pluck('id');

        // Ambil data pengajuan yang ditujukan untuk kelas dosen ini
        $pengajuanIzin = PengajuanIzin::with(['users', 'jadwalKuliah.mataKuliah'])
            ->whereIn('id_jadwal_kuliah', $jadwalIds)
            ->where('status', $statusFilter)
            ->latest() // Tampilkan yang terbaru di atas
            ->paginate(10); // Gunakan pagination agar halaman tidak berat

        return view('dosen.pengajuan-izin.index', [
            'pengajuanIzin' => $pengajuanIzin,
            'currentStatus' => $statusFilter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Ambil semua jadwal kuliah yang diikuti oleh mahasiswa di semester aktifnya
        $jadwalMahasiswa = JadwalKuliah::with('mataKuliah', 'dosen')
            ->where('id_prodi', $user->id_prodi)
            ->where('id_semester', $user->id_semester)
            // Filter untuk golongan spesifik mahasiswa ATAU kelas besar (semua golongan)
            ->where(function ($query) use ($user) {
                $query->where('id_golongan', $user->id_golongan)
                    ->orWhereHas('golongan', function ($q) {
                        $q->where('nama_golongan', 'Semua Golongan');
                    });
            })
            ->get();

        return view('pengajuan-izin-create', [
            'jadwalMahasiswa' => $jadwalMahasiswa
        ]);
    }

    /**
     * Menyimpan pengajuan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_kuliah_id' => 'required|exists:jadwal_kuliah,id',
            'tanggal_izin' => 'required|date',
            'tipe_pengajuan' => 'required|in:Izin,Sakit',
            'pesan' => 'required|string|max:1000',
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Maks 2MB
        ]);

        $pathBukti = null;
        if ($request->hasFile('file_bukti')) {
            $pathBukti = $request->file('file_bukti')->store('bukti-izin', 'public');
        }

        PengajuanIzin::create([
            'id_user' => Auth::id(),
            'id_jadwal_kuliah' => $request->jadwal_kuliah_id,
            'tanggal_izin' => $request->tanggal_izin,
            'tipe_pengajuan' => $request->tipe_pengajuan,
            'pesan' => $request->pesan,
            'file_bukti' => $pathBukti,
            'status' => 'Baru', // Status awal saat dibuat
        ]);

        return redirect()->route('home') // Arahkan ke dasbor mahasiswa atau halaman lain
            ->with('success', 'Pengajuan Anda telah berhasil terkirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, PengajuanIzin $pengajuan)
    {
        // Keamanan: Pastikan dosen yang login berhak memproses pengajuan ini
        if ($pengajuan->jadwalKuliah->id_user !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_dosen' => 'nullable|string|max:500',
        ]);

        // 1. Update status di tabel pengajuan_izin
        $pengajuan->status = $validated['status'];
        $pengajuan->catatan_dosen = $validated['catatan_dosen'];
        $pengajuan->save();

        // 2. Jika disetujui, update atau buat record di tabel presensi_kuliah
        if ($validated['status'] === 'Disetujui') {
            Presensi::updateOrCreate(
                [
                    'user_id' => $pengajuan->id_user,
                    'id_jadwal_kuliah' => $pengajuan->id_jadwal_kuliah,
                    'tanggal' => $pengajuan->tanggal_izin,
                ],
                [
                    'id_matkul' => $pengajuan->jadwalKuliah->id_matkul,
                    'status' => 'Izin/Sakit',
                    'waktu_presensi' => null, // Tidak ada waktu fisik
                    'keterangan' => 'Pengajuan disetujui oleh Dosen.',
                ]
            );
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
