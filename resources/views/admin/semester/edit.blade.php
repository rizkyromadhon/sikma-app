@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.semester.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Edit
                {{ $semester->display_name }}</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 w-fit">
            <form action="{{ route('admin.semester.update', $semester->id) }}" method="post"
                class="flex flex-col gap-4 max-w-md">
                @csrf
                @method('PUT')

                {{-- Input Tersembunyi untuk start_year (academic_year_start_year) --}}
                {{-- Nilai awal dari $semester->start_year atau old('start_year') --}}
                <input type="hidden" name="start_year" id="academic_year_start_year"
                    value="{{ old('start_year', $semester->start_year) }}">

                <div class="flex flex-col gap-2">
                    <label for="display_name" class="block text-sm font-medium text-gray-700">Nama Tampilan Semester</label>
                    @error('display_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <input type="text" id="display_name" name="display_name"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                        placeholder="Contoh: TA 2025/2026 Ganjil"
                        value="{{ old('display_name', $semester->display_name ?? $semester->semester_name) }}">
                    {{-- Fallback ke semester_name jika display_name belum ada --}}
                </div>

                <div class="flex flex-col gap-2">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <input type="date" id="start_date" name="start_date"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                        value="{{ old('start_date', $semester->start_date ? \Carbon\Carbon::parse($semester->start_date ?? $semester->start_month)->format('Y-m-d') : '') }}">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <input type="date" id="end_date" name="end_date"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                        value="{{ old('end_date', $semester->end_date ? \Carbon\Carbon::parse($semester->end_date ?? $semester->end_month)->format('Y-m-d') : '') }}">
                </div>

                <div class="flex items-end gap-6">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-sm font-medium text-gray-700">Pilih Tipe Semester</h1>
                        @error('semester_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-6 items-center">
                            <label
                                class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md">
                                <input type="radio" name="semester_type" id="ganjil" value="Ganjil" class="hidden peer"
                                    {{ old('semester_type', $semester->semester_type) == 'Ganjil' ? 'checked' : '' }} />
                                <div
                                    class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600">
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Ganjil</span>
                            </label>

                            <label
                                class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md">
                                <input type="radio" name="semester_type" id="genap" value="Genap" class="hidden peer"
                                    {{ old('semester_type', $semester->semester_type) == 'Genap' ? 'checked' : '' }} />
                                <div
                                    class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600">
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Genap</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="semester_code_display" class="text-sm font-medium text-gray-700">Kode Semester</label>
                        @error('semester_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <input type="text" name="semester_code" id="semester_code_display" readonly
                            class="bg-gray-100 text-gray-600 border border-gray-300 rounded-lg px-3 py-2 text-sm w-32 cursor-not-allowed"
                            value="{{ old('semester_code', $semester->semester_code ?? $semester->kode) }}" />
                        {{-- Fallback ke kode lama jika semester_code belum ada --}}
                    </div>
                </div>

                <button type="submit"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">Simpan</button>
            </form>
        </div>
    </div>
    <script>
        // JavaScript yang sama dengan halaman create.blade.php
        document.addEventListener('DOMContentLoaded', function() {
            const semesterTypeRadios = document.querySelectorAll('input[name="semester_type"]');
            const semesterCodeInput = document.getElementById('semester_code_display'); // Untuk tampilan
            const academicYearStartYearInput = document.getElementById('academic_year_start_year'); // Input hidden
            const startDateInput = document.getElementById('start_date');

            function updateDerivedFields() {
                const startDateValue = startDateInput
                    .value; // Akan mengambil nilai dari old() jika ada, atau dari $semester->start_date
                let selectedSemesterType = null;
                semesterTypeRadios.forEach(
                    radio => { // Akan membaca status checked dari old() jika ada, atau dari $semester->semester_type
                        if (radio.checked) {
                            selectedSemesterType = radio.value;
                        }
                    });

                if (startDateValue && selectedSemesterType) {
                    const startDateObj = new Date(startDateValue);
                    const calendarYear = startDateObj.getFullYear();
                    const calendarMonth = startDateObj.getMonth() + 1; // getMonth() is 0-indexed

                    let academicYearForCode = calendarYear;

                    if (selectedSemesterType === 'Genap' && calendarMonth <= 7) {
                        academicYearForCode = calendarYear - 1;
                    }

                    // Update input hidden untuk start_year
                    if (academicYearStartYearInput) { // Pastikan elemen ada
                        academicYearStartYearInput.value = academicYearForCode;
                    }

                    const semesterCodeSuffix = selectedSemesterType === 'Ganjil' ? '1' : '2';
                    semesterCodeInput.value = academicYearForCode.toString() + semesterCodeSuffix;
                } else {
                    semesterCodeInput.value = '';
                    if (academicYearStartYearInput) {
                        academicYearStartYearInput.value = '';
                    }
                }
            }

            // Event listeners
            if (startDateInput) {
                startDateInput.addEventListener('change', updateDerivedFields);
            }
            semesterTypeRadios.forEach(radio => {
                radio.addEventListener('change', updateDerivedFields);
            });

            updateDerivedFields();
        });
    </script>
@endsection
