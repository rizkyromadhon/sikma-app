<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Livewire\Livewire;
use App\Models\Golongan;
use App\Models\Semester;
use App\Events\RfidScanned;
use App\Models\AlatPresensi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class RfidController extends Controller
{
    // Menampilkan daftar mahasiswa
    public function index(Request $request)
    {
        $allSemestersForFilter = Semester::all(); // Ambil semua semester

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            // Ekstrak angka dari string display_name, contoh "Semester 5" -> 5
            // Menggunakan regular expression untuk mengambil angka di akhir string
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1]; // Kembalikan angka sebagai integer
            }
            // Jika format tidak cocok atau tidak ada angka, beri nilai default
            // atau bisa juga berdasarkan kriteria lain jika display_name tidak selalu "Semester X"
            // Untuk display_name yang tidak mengandung angka di akhir, mereka akan dikelompokkan
            // berdasarkan nilai kembalian ini. PHP_INT_MAX akan menempatkannya di akhir.
            // Jika display_name bisa null atau kosong, tambahkan pengecekan:
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            // Jika tidak ada angka tapi display_name ada, urutkan secara alfabetis setelah yang berangka
            return $semester->display_name; // Fallback ke pengurutan string jika tidak ada angka
        })->values(); // ->values() untuk mereset keys array setelah sorting
        // Ambil seluruh data mahasiswa
        $programStudiData = ProgramStudi::all();
        $golonganData = Golongan::select('nama_golongan')
            ->distinct()
            ->orderBy('nama_golongan', 'asc')
            ->pluck('nama_golongan');

        $mahasiswa = User::where('role', 'mahasiswa')
            ->whereNotNull('id_prodi')
            ->whereNotNull('id_semester')
            ->whereNotNull('id_golongan')
            ->when($request->filled('semester') && $request->semester !== 'all', function ($query) use ($request) {
                $query->where('id_semester', $request->semester);
            })
            ->when($request->filled('program_studi') && $request->program_studi !== 'all', function ($query) use ($request) {
                $query->where('id_prodi', $request->program_studi);
            })
            ->when($request->filled('golongan') && $request->golongan !== 'all', function ($query) use ($request) {
                $query->whereHas('golongan', function ($q) use ($request) {
                    $q->where('nama_golongan', $request->golongan);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nim', $request->search);
            })
            ->with(['programStudi', 'semester', 'golongan']) // tambahkan eager loading
            ->orderByRaw('uid IS NULL')
            ->orderBy('name', 'asc')
            ->orderBy('id_semester', 'asc')
            ->orderBy('id_prodi', 'asc')
            ->orderBy('id_golongan', 'asc')
            ->paginate(8)
            ->appends($request->query());


        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);
        // Kembalikan view dengan data mahasiswa
        return view('admin.rfid.index', compact('mahasiswa', 'semesters', 'programStudiData', 'golonganData'));
    }

    public function registrasi($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->where('id', $id)->first();

        AlatPresensi::where('id', 1)->update(['mode' => 'register']);
        return view('admin.rfid.registrasi', compact('mahasiswa'));
    }

    public function store(Request $request, $id)
    {
        Log::info('UID yang diterima: ' . $request->uid);
        $request->validate([
            'uid' => 'required|unique:users,uid,except,id'
        ], [
            'uid.unique' => 'Kartu RFID sudah terdaftar!'
        ]);
        $mahasiswa = User::where('role', 'mahasiswa')->where('id', $id)->first();
        $mahasiswa->update([
            'uid' => $request->uid
        ]);

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);
        return redirect()->route('admin.rfid.index')->with('success', 'Berhasil mendaftarkan RFID');
    }

    public function edit($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->where('id', $id)->first();

        AlatPresensi::where('id', 1)->update(['mode' => 'register']);
        return view('admin.rfid.edit', compact('mahasiswa'));
    }

    // Fungsi untuk mengupdate UID
    public function update(Request $request, $id)
    {
        Log::info('UID yang diterima: ' . $request->uid);
        $request->validate([
            'uid' => 'required|unique:users,uid,except,id'
        ], [
            'uid.unique' => 'Kartu RFID sudah terdaftar!'
        ]);
        $mahasiswa = User::where('role', 'mahasiswa')->where('id', $id)->first();
        $mahasiswa->update([
            'uid' => $request->uid
        ]);

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);
        return redirect()->route('admin.rfid.index')->with('success', 'Berhasil mengubah RFID');
    }

    public function getRfid(Request $request)
    {
        $uid = $request->input('uid');
        Log::info('UID masuk dari ESP32: ' . $uid);
        Cache::put('rfid-uid', $uid, now()->addSeconds(5));

        broadcast(new RfidScanned($uid))->toOthers();
        return response()->json(['message' => 'UID diterima']);
    }

    public function resetMode()
    {
        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);
        return redirect()->route('admin.rfid.index')->with('success', 'Mode kembali ke attendance');
    }
}
