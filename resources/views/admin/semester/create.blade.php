@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.semester.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Tambah Semester</h1>
        </div>
        <div class="px-4 rounded-md">
            <div class="bg-white dark:bg-black dark:border dark:border-gray-700 shadow-md rounded-lg p-6 w-fit">
                <form action="{{ route('admin.semester.store') }}" method="post" class="flex flex-col gap-4 max-w-md">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="start_year" id="academic_year_start_year">
                    <div class="flex flex-col gap-2">
                        <label for="display_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                            Tampilan
                            Semester</label>
                        @error('display_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                            placeholder="Contoh: Semester 1">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="start_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Tanggal
                            Mulai</label>
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow dark:[&::-webkit-calendar-picker-indicator]:invert">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="end_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Tanggal
                            Berakhir</label>
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow dark:[&::-webkit-calendar-picker-indicator]:invert">
                    </div>

                    <div class="flex items-end gap-6">
                        <div class="flex flex-col gap-2">
                            <h1 class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">
                                Pilih Tipe Semester</h1>
                            @error('semester_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex gap-6 items-center">
                                <label
                                    class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50  flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md">
                                    <input type="radio" name="semester_type" id="ganjil" value="Ganjil"
                                        {{ old('semester_type') == 'Ganjil' ? 'checked' : '' }} class="hidden peer" />
                                    <div
                                        class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600 dark:peer-checked:border-blue-600">
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-200 peer-checked:text-indigo-600 dark:peer-checked:text-blue-600">Ganjil</span>
                                </label>

                                <label
                                    class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50  flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md">
                                    <input type="radio" name="semester_type" id="genap" value="Genap"
                                        {{ old('semester_type') == 'Genap' ? 'checked' : '' }} class="hidden peer" />
                                    <div
                                        class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600 dark:peer-checked:border-blue-600">
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-200 peer-checked:text-indigo-600 dark:peer-checked:text-blue-600">Genap</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="semester_code_display"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Kode
                                Semester</label>
                            <input type="text" name="semester_code" id="semester_code_display" readonly
                                class="bg-gray-100 dark:bg-black dark:border-gray-700 text-gray-600 dark:text-gray-200 border border-gray-300 rounded-lg px-3 py-2 text-sm w-32 cursor-not-allowed" />
                        </div>
                    </div>

                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const semesterTypeRadios = document.querySelectorAll('input[name="semester_type"]');
            const semesterCodeInput = document.getElementById('semester_code_display'); // Untuk tampilan
            const academicYearStartYearInput = document.getElementById('academic_year_start_year'); // Input hidden
            const startDateInput = document.getElementById('start_date');

            function updateDerivedFields() {
                const startDateValue = startDateInput.value;
                let selectedSemesterType = null;
                semesterTypeRadios.forEach(radio => {
                    if (radio.checked) {
                        selectedSemesterType = radio.value;
                    }
                });

                if (startDateValue && selectedSemesterType) {
                    const startDateObj = new Date(startDateValue);
                    const calendarYear = startDateObj.getFullYear();
                    const calendarMonth = startDateObj.getMonth() + 1; // getMonth() is 0-indexed

                    let academicYearForCode = calendarYear;

                    // Logika penentuan tahun ajaran untuk kode semester:
                    // Jika Genap dan dimulai pada semester pertama kalender (Jan-Juli),
                    // maka tahun ajaran untuk KODE SEMESTER biasanya adalah tahun kalender sebelumnya.
                    if (selectedSemesterType === 'Genap' && calendarMonth <=
                        7) { // Asumsi batas bulan Juli untuk Genap "awal tahun"
                        academicYearForCode = calendarYear - 1;
                    }
                    // Untuk Ganjil, tahun ajaran untuk kode semester adalah tahun kalender dari tanggal mulai.
                    // Tidak perlu penyesuaian khusus untuk Ganjil di sini, academicYearForCode sudah benar.

                    academicYearStartYearInput.value = academicYearForCode; // Simpan tahun ajaran yg sbnrnya

                    const semesterCodeSuffix = selectedSemesterType === 'Ganjil' ? '1' : '2';
                    semesterCodeInput.value = academicYearForCode.toString() + semesterCodeSuffix;
                } else {
                    semesterCodeInput.value = '';
                    academicYearStartYearInput.value = '';
                }
            }

            // Event listeners
            startDateInput.addEventListener('change', updateDerivedFields);
            semesterTypeRadios.forEach(radio => {
                radio.addEventListener('change', updateDerivedFields);
            });

            // Panggil sekali saat load untuk mengisi jika ada old input (misal setelah validation error)
            updateDerivedFields();
        });
    </script>
@endsection
