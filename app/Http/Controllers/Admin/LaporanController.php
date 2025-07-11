<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Mail\AdminReplyMail;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\NotifikasiMahasiswaBaru;
use App\Notifications\AdminPasswordResetNotification;
use Illuminate\Support\Facades\Password;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $laporan = Laporan::with('programStudi')->orderByRaw("FIELD(status, 'Belum Ditangani', 'Sedang Diproses', 'Selesai')")->paginate(8);
    //     return view('admin.laporan.index', compact('laporan'));
    // }

    public function index(Request $request) // 1. Menerima Request
    {
        // 2. Ambil semua TIPE laporan yang unik dari database untuk mengisi dropdown filter.
        $unique_types = Laporan::query()
            ->select('tipe')
            ->distinct()
            ->pluck('tipe');

        // 3. Mulai membangun query, jangan langsung eksekusi.
        $query = Laporan::with('programStudi');

        // 4. Terapkan filter HANYA JIKA ada parameter 'tipe' di URL.
        //    Fungsi when() sangat ideal untuk filter kondisional seperti ini.
        $query->when($request->filled('tipe'), function ($q) use ($request) {
            return $q->where('tipe', $request->query('tipe'));
        });

        // Terapkan urutan custom Anda yang sudah ada
        $query->orderByRaw("FIELD(status, 'Belum Ditangani', 'Sedang Diproses', 'Selesai')")
            ->latest(); // Tambahkan latest() sebagai urutan sekunder

        // 5. Eksekusi query dengan paginasi DAN pertahankan query string (filter)
        $laporan = $query->paginate(8)->withQueryString();

        // 6. Kirim data laporan DAN daftar tipe unik ke view.
        return view('admin.laporan.index', [
            'laporan' => $laporan,
            'tipes'   => $unique_types,
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
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'nim' => $user->nim,
                'id_prodi' => $user->id_prodi,
                'email' => $user->email,
            ];
        } else {
            $request->validate([
                'nama_lengkap' => 'required|string|max:100',
                'nim' => 'required|string|max:20',
                'id_prodi' => 'required|exists:program_studi,id',
                'email' => 'required|email|max:100',
            ]);

            $matchedUser = User::where('nim', $request->nim)->first();

            $data = [
                'user_id' => $matchedUser->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nim' => $request->nim,
                'id_prodi' => $request->id_prodi,
                'email' => $request->email,
                'tipe' => 'laporan'
            ];
        }

        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        Laporan::create(array_merge($data, [
            'pesan' => $request->pesan,
            'status' => 'Belum Ditangani',
        ]));

        return redirect()->back()->with('success', 'Laporan Anda telah dikirim.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('admin.laporan.show', compact('laporan'));
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:Belum Ditangani,Sedang Diproses,Selesai,Ditolak',
            'balasan' => 'nullable|string',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->balasan = $request->balasan;
        $laporan->save();

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    public function aksi(Request $request, $id, $aksi)
    {
        $request->validate([
            'balasan' => 'required|string|min:5|max:1000',
        ], [
            'balasan.required' => 'Pesan balasan wajib diisi untuk melanjutkan.',
            'balasan.min' => 'Pesan balasan minimal 5 karakter.',
        ]);
        $laporan = Laporan::findOrFail($id);

        $laporan = Laporan::with('user')->findOrFail($id);
        $laporan->balasan = $request->balasan ?: 'Tindakan telah diproses oleh admin.';

        if ($aksi == 'proses') {
            $laporan->status = 'Sedang Diproses';
            $tipeNotifikasi = 'Laporan Anda Sedang Diproses';
        } elseif ($aksi == 'selesai') {
            $laporan->status = 'Selesai';
            $tipeNotifikasi = 'Laporan Anda Telah Selesai';
        }



        $mahasiswa = $laporan->user;

        if ($laporan->tipe === 'lupa_password') {
            try {
                $pesanDariAdmin = $request->balasan;

                // Gunakan metode sendResetLink dengan closure untuk mengirim notifikasi kustom
                Password::broker()->sendResetLink(
                    ['email' => $mahasiswa->email],
                    function ($user, $token) use ($pesanDariAdmin) {
                        $user->notify(new AdminPasswordResetNotification($token, $pesanDariAdmin));
                    }
                );
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email reset password otomatis: ' . $e->getMessage());
                $laporan->save(); // Tetap simpan status laporan
                return back()->with('info', 'Status laporan diperbarui, tapi notifikasi email GAGAL dikirim. Cek konfigurasi.');
            }
        }

        $laporan->save();

        try {
            if ($mahasiswa) {
                $kontenNotifikasi = "Tanggapan untuk laporan Anda: \"{$laporan->pesan}\". Pesan dari admin: \"{$request->balasan}\"";

                $notifikasi = Notifikasi::create([
                    'id_user'   => $mahasiswa->id,
                    'sender_id' => Auth::id(),
                    'tipe'      => $tipeNotifikasi,
                    'konten'    => $kontenNotifikasi,
                    'url_tujuan' => route('mahasiswa.pesan'), // pastikan route ini ada
                ]);

                NotifikasiMahasiswaBaru::dispatch($mahasiswa, $notifikasi);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi update status laporan: ' . $e->getMessage());
        }
        return back()->with('success', 'Status laporan diperbarui.');
    }

    public function balas(Request $request)
    {
        $laporan = Laporan::findOrFail($request->id);
        $laporan->balasan = $request->balasan;
        $laporan->save();
        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function checkNIM(Request $request)
    {
        $request->validate([
            'nim' => 'required|string'
        ]);

        $nim = $request->input('nim');

        Log::info('Checking NIM: ' . $nim);

        $user = User::with('programStudi')->where('nim', $nim)->first();

        return response()->json([
            'exists' => !!$user,
            'nim' => $nim,
            'user' => $user ? [
                'name' => $user->name,
                'email' => $user->email,
                'id_prodi' => $user->id_prodi,
                'prodi_name' => $user->programStudi->name ?? null,
            ] : null
        ]);
    }
}
