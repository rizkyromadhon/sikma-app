<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Laporan;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::with('programStudi')->orderByRaw("FIELD(status, 'Belum Ditangani', 'Sedang Diproses', 'Selesai')")->paginate(8);
        return view('admin.laporan.index', compact('laporan'));
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
            // User login: ambil dari data user
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
            ];
        }

        // Validasi pesan tetap dilakukan untuk semua
        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        // Simpan laporan
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
        $laporan = Laporan::findOrFail($id);

        $laporan->balasan = $request->balasan;

        if ($aksi == 'proses') {
            $laporan->status = 'Sedang Diproses';
        } elseif ($aksi == 'selesai') {
            $laporan->status = 'Selesai';
        }

        $laporan->save();
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
