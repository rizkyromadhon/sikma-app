@php
    use Carbon\Carbon;
    $lastDigit = substr($semester->kode, -1);
@endphp

@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.semester.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Edit Semester {{$semester->id}}</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 w-fit">
            <form action="{{ route('admin.semester.update', $semester->id) }}" method="post"
                class="flex flex-col gap-4 max-w-md">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-2">
                    <label for="semester_name" class="block text-sm font-medium text-gray-700">Nama Semester</label>
                    @error('semester_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <input type="text" id="semester_name" name="semester_name"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="Semester 1"
                        value="{{ old('semester_name', $semester->semester_name) }}">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="start_month" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" id="start_month" name="start_month"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                        value="{{ old('start_month', $semester->start_month) }}">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="end_month" class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                    <input type="date" id="end_month" name="end_month"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                        value="{{ old('end_month', $semester->end_month) }}">
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex flex-col gap-2">
                        <h1 class="text-sm font-medium text-gray-700">Pilih Semester</h1>
                        <div class="flex gap-6 items-center">
                            <!-- Ganjil -->
                            <label
                                class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md">
                                <input type="radio" name="select_semester" id="ganjil" value="Ganjil"
                                    class="hidden peer" {{ $lastDigit == '1' ? 'checked' : '' }} />
                                <div
                                    class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600">
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Ganjil</span>
                            </label>

                            <!-- Genap -->
                            <label
                                class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md">
                                <input type="radio" name="select_semester" id="genap" value="Genap"
                                    class="hidden peer" {{ $lastDigit == '2' ? 'checked' : '' }} />
                                <div
                                    class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600">
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Genap</span>
                            </label>

                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <h1 class="text-sm font-medium text-gray-700">Kode</h1>
                        <input type="text" name="kode_semester" id="kodeSemester" readonly
                            class="bg-gray-100 text-gray-600 border border-gray-300 rounded-lg px-3 py-2 text-sm w-32 cursor-not-allowed"
                            value="{{ $semester->kode }}" />
                    </div>
                </div>

                <button type="submit"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">Simpan</button>
            </form>
        </div>
    </div>
    <script>
        const radioButtons = document.querySelectorAll('input[name="select_semester"]');
        const kodeInput = document.getElementById('kodeSemester');
        const startMonthInput = document.getElementById('start_month');
        const endMonthInput = document.getElementById('end_month');

        function updateSemesterCode() {
            const startYear = startMonthInput.value ? new Date(startMonthInput.value).getFullYear() : null;
            const endYear = endMonthInput.value ? new Date(endMonthInput.value).getFullYear() : null;
            const year = startYear || endYear || new Date().getFullYear(); // Default to current year if both are empty

            radioButtons.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.checked) {
                        // Set the year plus 1 for Ganjil and 2 for Genap
                        const semesterCode = radio.value === 'Ganjil' ? year + '1' : year + '2';
                        kodeInput.value = semesterCode;
                    }
                });
            });
        }

        // Update the code whenever the date inputs are changed
        startMonthInput.addEventListener('change', updateSemesterCode);
        endMonthInput.addEventListener('change', updateSemesterCode);

        // Initial call to set the code if date is already set
        updateSemesterCode();
    </script>
@endsection
