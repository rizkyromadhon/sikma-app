<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ruangan;
use App\Models\Golongan;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\AlatPresensi;
use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JadwalKuliahController extends Controller
{
    public function index(Request $request)
    {
        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        // Ambil semua jadwal kuliah dengan relasi
        $jadwalQuery = JadwalKuliah::with(['mataKuliah', 'dosen', 'ruangan', 'golongan', 'prodi', 'semester']);

        // Filter berdasarkan request jika ada
        if ($request->has('program_studi') && $request->program_studi) {
            $jadwalQuery->where('id_prodi', $request->program_studi);
        }

        if ($request->has('semester') && $request->semester) {
            $jadwalQuery->where('id_semester', $request->semester);
        }

        $allSchedules = $jadwalQuery->get();

        // Grup jadwal berdasarkan kriteria yang sama
        $groupedSchedules = [];

        foreach ($allSchedules as $schedule) {
            // Buat key unik berdasarkan hari, mata kuliah, dosen, jam, ruangan, program studi, semester
            $key = md5(
                $schedule->hari .
                    $schedule->id_matkul .
                    $schedule->id_user .
                    $schedule->jam_mulai .
                    $schedule->jam_selesai .
                    $schedule->id_ruangan .
                    $schedule->id_prodi .
                    $schedule->id_semester
            );

            if (!isset($groupedSchedules[$key])) {
                $groupedSchedules[$key] = [
                    'hari' => $schedule->hari,
                    'mata_kuliah' => $schedule->mataKuliah->name,
                    'dosen' => $schedule->dosen->name,
                    'jam_mulai' => $schedule->jam_mulai,
                    'jam_selesai' => $schedule->jam_selesai,
                    'ruangan' => $schedule->ruangan->name,
                    'program_studi' => $schedule->prodi->name,
                    'semester' => $schedule->semester->display_name ? explode(' ', $schedule->semester->display_name)[1] : '-',
                    'golongan' => [],
                    'ids' => [],
                    'schedules' => collect([])
                ];
            }

            // Tambahkan golongan ke grup
            $groupedSchedules[$key]['golongan'][] = $schedule->golongan->nama_golongan;
            $groupedSchedules[$key]['ids'][] = $schedule->id;
            $groupedSchedules[$key]['schedules']->push($schedule);
        }

        // Urutkan golongan dalam setiap grup
        foreach ($groupedSchedules as &$group) {
            sort($group['golongan']);
        }

        // Convert ke indexed array
        $groupedSchedules = array_values($groupedSchedules);

        // PERBAIKAN: Buat pagination manual untuk grouped data
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $total = count($groupedSchedules);
        $offset = ($currentPage - 1) * $perPage;

        // Ambil data untuk halaman saat ini
        $currentPageData = array_slice($groupedSchedules, $offset, $perPage);

        // Buat paginator
        $datas = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageData,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        // Append query parameters untuk maintain filter
        $datas->appends(request()->query());

        // Data untuk filter dropdown jika diperlukan
        $programStudi = ProgramStudi::all();
        $semesters = Semester::all();

        return view('admin.jadwal-kuliah.index', compact('datas', 'programStudi', 'semesters'));
    }

    public function create()
    {
        $allSemestersForFilter = Semester::all(); // Ambil semua semester

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1]; // Kembalikan angka sebagai integer
            }
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            return $semester->display_name; // Fallback ke pengurutan string jika tidak ada angka
        })->values(); // ->values() untuk mereset keys array setelah sorting
        $programStudi = ProgramStudi::all();
        $golonganData = Golongan::orderBy('nama_golongan')->get()->groupBy('id_prodi');
        $mataKuliah = MataKuliah::all();
        $ruangans = Ruangan::all();
        $dosens = User::where('role', 'dosen')->get();


        return view('admin.jadwal-kuliah.create', compact('dosens', 'golonganData', 'semesters', 'mataKuliah', 'programStudi', 'ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'dosen' => 'required',
            'mata_kuliah' => 'required',
            'ruangan' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'semester' => 'required',
            'program_studi' => 'required',
            'golongan' => 'required',
        ], [
            'hari.required' => 'Hari harus diisi',
            'dosen.required' => 'Dosen harus diisi',
            'mata_kuliah.required' => 'Mata kuliah harus diisi',
            'ruangan.required' => 'Ruangan harus diisi',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'semester.required' => 'Semester harus diisi',
            'program_studi.required' => 'Program studi harus diisi',
            'golongan.required' => 'Golongan harus diisi',
        ]);

        if ($request->golongan === 'all') {
            // Ambil semua golongan berdasarkan program studi dan semester
            $golongans = Golongan::where('id_prodi', $request->program_studi)
                ->get();

            // Buat jadwal untuk setiap golongan
            foreach ($golongans as $golongan) {
                JadwalKuliah::create([
                    'id_user' => $request->dosen,
                    'id_matkul' => $request->mata_kuliah,
                    'id_ruangan' => $request->ruangan,
                    'hari' => $request->hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'id_semester' => $request->semester,
                    'id_prodi' => $request->program_studi,
                    'id_golongan' => $golongan->id,
                    'is_kelas_besar' => true, // Optional: tambahkan flag untuk menandai ini adalah kelas besar
                ]);
            }

            return redirect()->route('admin.jadwal-kuliah.index')
                ->with('success', 'Jadwal Kuliah berhasil ditambahkan.');
        } else {
            // Buat jadwal untuk golongan spesifik
            JadwalKuliah::create([
                'id_user' => $request->dosen,
                'id_matkul' => $request->mata_kuliah,
                'id_ruangan' => $request->ruangan,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'id_semester' => $request->semester,
                'id_prodi' => $request->program_studi,
                'id_golongan' => $request->golongan,
                'is_kelas_besar' => false, // Optional: tandai sebagai kelas reguler
            ]);

            return redirect()->route('admin.jadwal-kuliah.index')
                ->with('success', 'Jadwal Kuliah berhasil ditambahkan.');
        }
    }

    public function edit($id)
    {
        $allSemestersForFilter = Semester::all();

        $semesters = $allSemestersForFilter->sortBy(function ($semester) {
            if (preg_match('/(\d+)$/', $semester->display_name, $matches)) {
                return (int) $matches[1];
            }
            if (empty($semester->display_name)) {
                return PHP_INT_MAX;
            }
            return $semester->display_name;
        })->values();

        $jadwal = JadwalKuliah::where('id', $id)->first();

        // Cek apakah ini kelas besar dengan mencari jadwal serupa
        $relatedSchedules = JadwalKuliah::where('hari', $jadwal->hari)
            ->where('id_matkul', $jadwal->id_matkul)
            ->where('id_user', $jadwal->id_user)
            ->where('jam_mulai', $jadwal->jam_mulai)
            ->where('jam_selesai', $jadwal->jam_selesai)
            ->where('id_ruangan', $jadwal->id_ruangan)
            ->where('id_prodi', $jadwal->id_prodi)
            ->where('id_semester', $jadwal->id_semester)
            ->get();

        $isKelasBesar = $relatedSchedules->count() > 1;

        $programStudi = ProgramStudi::all();
        $golonganData = Golongan::orderBy('nama_golongan')->get()->groupBy('id_prodi');
        $mataKuliah = MataKuliah::all();
        $ruangans = Ruangan::all();
        $dosens = User::where('role', 'dosen')->get();

        return view('admin.jadwal-kuliah.edit', compact(
            'jadwal',
            'dosens',
            'golonganData',
            'semesters',
            'mataKuliah',
            'programStudi',
            'ruangans',
            'isKelasBesar',
            'relatedSchedules'
        ));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalKuliah::findOrFail($id);

        $request->validate([
            'hari' => 'required',
            'dosen' => 'required',
            'mata_kuliah' => 'required',
            'ruangan' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'semester' => 'required',
            'program_studi' => 'required',
            'golongan' => 'required',
        ], [
            'hari.required' => 'Hari harus diisi',
            'dosen.required' => 'Dosen harus diisi',
            'mata_kuliah.required' => 'Mata kuliah harus diisi',
            'ruangan.required' => 'Ruangan harus diisi',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'semester.required' => 'Semester harus diisi',
            'program_studi.required' => 'Program studi harus diisi',
            'golongan.required' => 'Golongan harus diisi',
        ]);

        // Cari jadwal serupa (kelas besar)
        $relatedSchedules = JadwalKuliah::where('hari', $jadwal->hari)
            ->where('id_matkul', $jadwal->id_matkul)
            ->where('id_user', $jadwal->id_user)
            ->where('jam_mulai', $jadwal->jam_mulai)
            ->where('jam_selesai', $jadwal->jam_selesai)
            ->where('id_ruangan', $jadwal->id_ruangan)
            ->where('id_prodi', $jadwal->id_prodi)
            ->where('id_semester', $jadwal->id_semester)
            ->get();

        $isKelasBesar = $relatedSchedules->count() > 1;

        if ($isKelasBesar) {
            // Ini adalah kelas besar
            if ($request->golongan === 'all') {
                // Update semua jadwal dalam grup dengan data baru
                foreach ($relatedSchedules as $schedule) {
                    $schedule->update([
                        'id_user' => $request->dosen,
                        'id_matkul' => $request->mata_kuliah,
                        'id_ruangan' => $request->ruangan,
                        'hari' => $request->hari,
                        'jam_mulai' => $request->jam_mulai,
                        'jam_selesai' => $request->jam_selesai,
                        'id_semester' => $request->semester,
                        'id_prodi' => $request->program_studi,
                        'is_kelas_besar' => true,
                    ]);
                }
            } else {
                // Golongan diubah dari "semua" ke golongan spesifik
                // Hapus semua jadwal dalam grup
                JadwalKuliah::whereIn('id', $relatedSchedules->pluck('id'))->delete();

                // Buat jadwal baru untuk golongan spesifik
                JadwalKuliah::create([
                    'id_user' => $request->dosen,
                    'id_matkul' => $request->mata_kuliah,
                    'id_ruangan' => $request->ruangan,
                    'hari' => $request->hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'id_semester' => $request->semester,
                    'id_prodi' => $request->program_studi,
                    'id_golongan' => $request->golongan,
                    'is_kelas_besar' => false,
                ]);
            }
        } else {
            // Ini adalah jadwal biasa (single)
            if ($request->golongan === 'all') {
                // Hapus jadwal lama
                $jadwal->delete();

                // Buat jadwal untuk semua golongan
                $golongans = Golongan::where('id_prodi', $request->program_studi)->get();

                foreach ($golongans as $golongan) {
                    JadwalKuliah::create([
                        'id_user' => $request->dosen,
                        'id_matkul' => $request->mata_kuliah,
                        'id_ruangan' => $request->ruangan,
                        'hari' => $request->hari,
                        'jam_mulai' => $request->jam_mulai,
                        'jam_selesai' => $request->jam_selesai,
                        'id_semester' => $request->semester,
                        'id_prodi' => $request->program_studi,
                        'id_golongan' => $golongan->id,
                        'is_kelas_besar' => true,
                    ]);
                }
            } else {
                // Update jadwal biasa
                $jadwal->update([
                    'id_user' => $request->dosen,
                    'id_matkul' => $request->mata_kuliah,
                    'id_ruangan' => $request->ruangan,
                    'hari' => $request->hari,
                    'jam_mulai' => $request->jam_mulai,
                    'jam_selesai' => $request->jam_selesai,
                    'id_semester' => $request->semester,
                    'id_prodi' => $request->program_studi,
                    'id_golongan' => $request->golongan,
                    'is_kelas_besar' => false,
                ]);
            }
        }

        return redirect()->route('admin.jadwal-kuliah.index')->with('success', 'Jadwal Kuliah berhasil diubah.');
    }

    public function destroy($id)
    {
        JadwalKuliah::where('id', $id)->delete();
        return redirect()->route('admin.jadwal-kuliah.index')->with('success', 'Berhasil menghapus jadwal kuliah!');
    }

    public function destroyGroup(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return redirect()->route('admin.jadwal-kuliah.index')
                    ->with('error', 'Tidak ada jadwal yang dipilih untuk dihapus.');
            }

            // Hapus semua jadwal berdasarkan ID yang diberikan
            JadwalKuliah::whereIn('id', $ids)->delete();

            $count = count($ids);
            $message = $count > 1
                ? "Berhasil menghapus {$count} jadwal kuliah."
                : "Berhasil menghapus jadwal kuliah.";

            return redirect()->route('admin.jadwal-kuliah.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.jadwal-kuliah.index')
                ->with('error', 'Terjadi kesalahan saat menghapus jadwal kuliah.');
        }
    }
}
