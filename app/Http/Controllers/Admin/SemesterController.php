<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Semester;
use App\Models\AlatPresensi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        $this->updateActiveSemester(); // Tetap panggil method ini

        // Mengambil semua data semester, diurutkan agar grup konsisten
        $allSemesters = Semester::orderBy('start_year', 'asc')
            ->orderBy('semester_type', 'asc')
            // Anda mungkin ingin menambahkan urutan sekunder di sini,
            // misalnya berdasarkan display_name atau id, untuk konsistensi urutan dalam grup
            ->orderBy('display_name', 'asc')
            ->get();

        // Mengelompokkan semester berdasarkan 'semester_code'
        $groupedSemesters = $allSemesters->groupBy('semester_code');

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        // Kirim data yang sudah dikelompokkan ke view
        return view('admin.semester.index', ['groupedSemesters' => $groupedSemesters]);
    }

    public function create()
    {
        return view('admin.semester.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'semester_type' => ['required', Rule::in(['Ganjil', 'Genap'])], // Validasi ENUM
            'start_year' => 'required|integer|digits:4', // Tahun mulai Tahun Ajaran
            'semester_code' => [
                'required',
                'string',
                'max:10', // Sesuaikan panjang maksimal jika perlu
            ],
        ], [
            'display_name.required' => 'Nama tampilan semester wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal berakhir wajib diisi.',
            'end_date.date' => 'Format tanggal berakhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
            'semester_type.required' => 'Tipe semester wajib dipilih.',
            'semester_type.in' => 'Tipe semester tidak valid.',
            'start_year.required' => 'Tahun mulai Tahun Ajaran wajib diisi.',
            'start_year.integer' => 'Tahun mulai Tahun Ajaran harus berupa angka.',
            'start_year.digits' => 'Tahun mulai Tahun Ajaran harus 4 digit.',
            'semester_code.required' => 'Kode semester wajib diisi.',
        ]);

        $semester = new Semester();

        $semester->display_name = $validated['display_name'];
        $semester->start_date = $validated['start_date'];
        $semester->end_date = $validated['end_date'];
        $semester->semester_type = $validated['semester_type'];
        $semester->start_year = $validated['start_year']; // Simpan ke kolom yang benar
        $semester->semester_code = $validated['semester_code'];
        // is_currently_active akan diurus oleh method updateActiveSemester() atau logic terpisah

        $semester->save();

        return redirect()->route('admin.semester.index')->with('success', 'Berhasil menambahkan semester baru!');
    }

    public function edit($id)
    {
        $semester = Semester::where('id', $id)->first();
        return view('admin.semester.edit', compact('semester'));
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id); // Cari semester atau tampilkan error 404 jika tidak ditemukan

        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'semester_type' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'start_year' => 'required|integer|digits:4', // Ini adalah academic_year_start_year Anda
            'semester_code' => [
                'required',
                'string',
                'max:10', // Sesuaikan panjangnya jika kode Anda lebih pendek/panjang
                Rule::unique('semesters', 'semester_code')->ignore($semester->id), // Pastikan unik, kecuali untuk record ini sendiri
            ],
        ], [
            'display_name.required' => 'Nama tampilan semester wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal berakhir wajib diisi.',
            'end_date.date' => 'Format tanggal berakhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
            'semester_type.required' => 'Tipe semester wajib dipilih.',
            'semester_type.in' => 'Tipe semester tidak valid (hanya Ganjil atau Genap).',
            'start_year.required' => 'Tahun mulai Tahun Ajaran wajib diisi.',
            'start_year.integer' => 'Tahun mulai Tahun Ajaran harus berupa angka.',
            'start_year.digits' => 'Tahun mulai Tahun Ajaran harus 4 digit.',
            'semester_code.required' => 'Kode semester wajib diisi.',
            'semester_code.unique' => 'Kode semester ini sudah digunakan oleh semester lain.',
        ]);

        $semester->display_name = $validated['display_name'];
        $semester->start_date = $validated['start_date'];
        $semester->end_date = $validated['end_date'];
        $semester->semester_type = $validated['semester_type'];

        $semester->start_year = $validated['start_year'];

        $semester->semester_code = $validated['semester_code'];

        $semester->save();
        return redirect()->route('admin.semester.index')->with('success', 'Berhasil memperbarui semester!');
    }

    public function destroy($id)
    {
        Semester::where('id', $id)->delete();

        return redirect()->route('admin.semester.index')->with('success', 'Berhasil menghapus semester!');
    }

    private function updateActiveSemester()
    {
        $today = Carbon::now()->startOfDay();

        $semesters = Semester::all();

        foreach ($semesters as $semester) {
            $isActive = false;
            if ($semester->start_date && $semester->end_date) {
                $startDate = Carbon::parse($semester->start_date)->startOfDay();
                $endDate = Carbon::parse($semester->end_date)->endOfDay();

                if ($today->between($startDate, $endDate)) { // Pengecekan inti
                    $isActive = true;
                }
            }

            if ($semester->is_currently_active !== $isActive) {
                $semester->is_currently_active = $isActive;
                $semester->save();
            }
        }
    }
}
