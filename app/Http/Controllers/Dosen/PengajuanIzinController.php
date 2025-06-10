<?php

namespace App\Http\Controllers\Dosen;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Laporan;
use App\Models\Presensi;
use App\Models\Notifikasi;
use App\Models\JadwalKuliah;
use Illuminate\Http\Request;
use App\Models\PengajuanIzin;
use App\Events\NotifikasiIzinBaru;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\NotifikasiMahasiswaBaru;
use App\Http\Controllers\Admin\LaporanController;

class PengajuanIzinController extends Controller
{
    public function indexMahasiswa()
    {
        $riwayatPengajuan = PengajuanIzin::with('jadwalKuliah.mataKuliah')
            ->where('id_user', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pengajuan-izin', [
            'riwayatPengajuan' => $riwayatPengajuan,
        ]);
    }
    public function index(Request $request)
    {
        $request->validate(['status' => 'nullable|in:Baru,Disetujui,Ditolak']);

        $dosenId = Auth::id();
        $statusFilter = $request->input('status', 'Baru');

        $jadwalIds = JadwalKuliah::where('id_user', $dosenId)->pluck('id');

        $pengajuanIzin = PengajuanIzin::with(['users', 'jadwalKuliah.mataKuliah'])
            ->whereIn('id_jadwal_kuliah', $jadwalIds)
            ->where('status', $statusFilter)
            ->latest()
            ->paginate(10);

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

        $jadwalMahasiswa = JadwalKuliah::with('mataKuliah', 'dosen')
            ->where('id_prodi', $user->id_prodi)
            ->where('id_semester', $user->id_semester)
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
            'file_bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pathBukti = null;
        if ($request->hasFile('file_bukti')) {
            $pathBukti = $request->file('file_bukti')->store('bukti-izin', 'public');
        }

        $pengajuan = PengajuanIzin::create([
            'id_user' => Auth::id(),
            'id_jadwal_kuliah' => $request->jadwal_kuliah_id,
            'tanggal_izin' => $request->tanggal_izin,
            'tipe_pengajuan' => $request->tipe_pengajuan,
            'pesan' => $request->pesan,
            'file_bukti' => $pathBukti,
            'status' => 'Baru',
        ]);

        $dosenId = $pengajuan->jadwalKuliah->id_user;

        // event(new NotifikasiIzinBaru($dosenId, $pengajuan));
        try {
            // Dapatkan objek User Dosen dari relasi
            $dosen = $pengajuan->jadwalKuliah->dosen;

            if ($dosen) {
                $mahasiswaName = Auth::user()->name;
                $matkulName = $pengajuan->jadwalKuliah->mataKuliah->name;
                $tanggalFormatted = Carbon::parse($pengajuan->tanggal_izin)->isoFormat('D MMMM YYYY');

                // 1. SIMPAN notifikasi ke database untuk dosen
                $notifikasiUntukDosen = Notifikasi::create([
                    'id_user'   => $dosen->id, // Penerima adalah Dosen
                    'sender_id' => Auth::id(), // Pengirim adalah Mahasiswa
                    'tipe'      => 'Pengajuan Izin Baru',
                    'konten'    => "{$mahasiswaName} mengajukan izin untuk mata kuliah {$matkulName} pada tanggal {$tanggalFormatted}.",
                    'url_tujuan' => route('dosen.izin.index', ['status' => 'Baru']),
                ]);

                // 2. DISPATCH event dengan argumen objek yang sudah ada
                NotifikasiIzinBaru::dispatch($dosen, $notifikasiUntukDosen);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi izin ke dosen: ' . $e->getMessage());
        }

        return redirect()->route('mahasiswa.izin.index') // Arahkan ke dasbor mahasiswa atau halaman lain
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
        if ($pengajuan->jadwalKuliah->id_user !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_dosen' => 'nullable|string|max:500',
        ]);

        $pengajuan->status = $validated['status'];
        $pengajuan->catatan_dosen = $validated['catatan_dosen'];
        $pengajuan->save();

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
                    'waktu_presensi' => null,
                    'keterangan' => 'Pengajuan disetujui oleh Dosen.',
                ]
            );
        }

        try {
            $mahasiswa = $pengajuan->users;

            $dosenName = Auth::user()->name;
            $matkulName = $pengajuan->jadwalKuliah->mataKuliah->name;
            $tanggalFormatted = Carbon::parse($pengajuan->tanggal_izin)->isoFormat('dddd, D MMMM YYYY');
            $statusIzin = $validated['status'];

            $tipeNotifikasi = ($statusIzin === 'Disetujui') ? 'Izin Diterima' : 'Izin Ditolak';

            $kontenNotifikasi = "Pengajuan izin Anda pada mata kuliah {$matkulName} untuk {$tanggalFormatted} telah {$statusIzin} oleh {$dosenName}.";

            if (!empty($validated['catatan_dosen'])) {
                $kontenNotifikasi .= " Catatan: " . $validated['catatan_dosen'];
            }

            $notifikasi = Notifikasi::create([
                'id_user'   => $mahasiswa->id,
                'sender_id' => Auth::id(),
                'tipe'      => $tipeNotifikasi,
                'konten'    => $kontenNotifikasi,
                'url_tujuan' => route('mahasiswa.izin.index'),
            ]);

            NotifikasiMahasiswaBaru::dispatch($mahasiswa, $notifikasi);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi update status izin: ' . $e->getMessage());
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
