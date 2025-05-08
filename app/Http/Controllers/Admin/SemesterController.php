<?php

namespace App\Http\Controllers\Admin;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
        // Get unique semesters
        $semesters = Semester::whereNotNull('semester_name')->distinct()->get();

        // Pass only the unique semesters to the view
        return view('admin.semester.index', compact('semesters'));
    }

    public function create()
    {
        return view('admin.semester.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester_name' => 'required|string|unique:semesters,semester_name,except,id',
            'start_month' => 'required',
            'end_month' => 'required',
            'select_semester' => 'required',
            'kode_semester' => 'required'
        ], [
            'semester_name.unique' => 'Semester sudah ada'
        ]);

        $semester = new Semester();

        $semester->semester_name = $validated['semester_name'];
        $semester->start_month = $validated['start_month'];
        $semester->end_month = $validated['end_month'];
        $semester->kode = $validated['kode_semester'];

        $semester->save();

        return redirect('/admin/semester')->with('success', 'Berhasil menambahkan semester baru!');
    }

    public function edit($id)
    {
        $semester = Semester::where('id', $id)->first();
        return view('admin.semester.edit', compact('semester'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'semester_name' => 'required|string',
            'start_month' => 'required',
            'end_month' => 'required',
            'select_semester' => 'required',
            'kode_semester' => 'required'
        ]);

        $semester = Semester::where('id', $id)->first();

        $semester->semester_name = $validated['semester_name'];
        $semester->start_month = $validated['start_month'];
        $semester->end_month = $validated['end_month'];
        $semester->kode = $validated['kode_semester'];

        $semester->save();

        return redirect()->route('admin.semester.index')->with('success', 'Berhasil memperbarui semester!');
    }

    public function destroy($id)
    {
        Semester::where('id', $id)->delete();

        return redirect()->route('admin.semester.index')->with('success', 'Berhasil menghapus semester!');
    }
}
