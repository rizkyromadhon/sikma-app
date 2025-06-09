@extends('admin.dashboard')

@section('admin-content')
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4.5 mb-4 flex items-center gap-4">
        <a href="{{ route('admin.jadwal-kuliah.index') }}">
            <i
                class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Edit Jadwal
            Kuliah
            @if ($isKelasBesar)
                <span class="text-xl text-blue-600 dark:text-blue-400">(Kelas Besar - {{ $relatedSchedules->count() }}
                    Golongan)</span>
            @endif
        </h1>
    </div>
    {{-- <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3.5 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">
            Edit Jadwal Kuliah
            @if ($isKelasBesar)
                <span class="text-xl text-blue-600 dark:text-blue-400">(Kelas Besar - {{ $relatedSchedules->count() }}
                    Golongan)</span>
            @endif
        </h1>
        <div class="flex items-center justify-start">
            <a href="{{ route('admin.jadwal-kuliah.index') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark-mode-transition text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black dark:hover:bg-gray-900">
                <span>Kembali</span>
            </a>
        </div>
    </div> --}}

    <div class="px-6">
        <div
            class="bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">
            @if ($isKelasBesar)
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-2">Informasi Kelas Besar</h3>
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        Jadwal ini adalah kelas besar yang mencakup {{ $relatedSchedules->count() }} golongan:
                        <strong>{{ $relatedSchedules->pluck('golongan.nama_golongan')->sort()->join(', ') }}</strong>
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        Jika Anda mengubah pengaturan golongan, semua jadwal terkait akan disesuaikan.
                    </p>
                </div>
            @endif

            <form action="{{ route('admin.jadwal-kuliah.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Form fields sama seperti sebelumnya -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hari</label>
                        <select name="hari" id="hari"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        </select>
                        @error('hari')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester</label>
                        <select name="semester" id="semester"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Semester</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}"
                                    {{ $jadwal->id_semester == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dosen</label>
                        <select name="dosen" id="dosen"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Dosen</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}"
                                    {{ $jadwal->id_user == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dosen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Program Studi</label>
                        <select name="program_studi" id="program_studi"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Program Studi</option>
                            @foreach ($programStudi as $prodi)
                                <option value="{{ $prodi->id }}"
                                    {{ $jadwal->id_prodi == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mata Kuliah</label>
                        <select name="mata_kuliah" id="mata_kuliah"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach ($mataKuliah as $mk)
                                <option value="{{ $mk->id }}" {{ $jadwal->id_matkul == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mata_kuliah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Golongan</label>
                        <select name="golongan" id="golongan"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Golongan</option>
                            @if ($isKelasBesar)
                                <option value="all" selected>Semua Golongan</option>
                            @else
                                <option value="all">Semua Golongan</option>
                            @endif
                        </select>
                        @error('golongan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ruangan</label>
                        <select name="ruangan" id="ruangan"
                            class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            required>
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}"
                                    {{ $jadwal->id_ruangan == $ruangan->id ? 'selected' : '' }}>
                                    {{ $ruangan->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam
                                Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ $jadwal->jam_mulai }}"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:[&::-webkit-calendar-picker-indicator]:invert"
                                required>
                            @error('jam_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div> 
                        <div>
                            <label for="jam_selesai"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam
                                Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ $jadwal->jam_selesai }}"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:[&::-webkit-calendar-picker-indicator]:invert"
                                required>
                            @error('jam_selesai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                </div>

                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('admin.jadwal-kuliah.index') }}"
                        class="text-sm px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="text-sm px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const programStudiSelect = document.getElementById('program_studi');
        const golonganSelect = document.getElementById('golongan');
        const golonganData = @json($golonganData);
        const currentGolongan = {{ $jadwal->id_golongan }};
        const isKelasBesar = {{ $isKelasBesar ? 'true' : 'false' }};

        function updateGolonganOptions() {
            const selectedProdi = programStudiSelect.value;

            // Clear existing options except "Semua Golongan"
            golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>';

            if (isKelasBesar) {
                golonganSelect.innerHTML += '<option value="all" selected>Semua Golongan</option>';
            } else {
                golonganSelect.innerHTML += '<option value="all">Semua Golongan</option>';
            }

            if (selectedProdi && golonganData[selectedProdi]) {
                golonganData[selectedProdi].forEach(function(golongan) {
                    const selected = (!isKelasBesar && golongan.id == currentGolongan) ? 'selected' : '';
                    golonganSelect.innerHTML +=
                        `<option value="${golongan.id}" ${selected}>${golongan.nama_golongan}</option>`;
                });
            }
        }

        programStudiSelect.addEventListener('change', updateGolonganOptions);

        // Initialize on page load
        updateGolonganOptions();
    </script>
@endsection
