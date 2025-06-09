@extends('admin.dashboard')

@section('admin-content')
    {{-- Header Halaman --}}
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3.5 mb-4 flex items-center gap-4">
        <a href="{{ route('admin.jadwal-kuliah.index') }}">
            <i
                class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
        </a>
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Tambah Jadwal
            Kuliah
        </h1>
    </div>

    {{-- Konten Form --}}
    <div class="px-6">
        <div
            class="bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow p-6">

            @if ($errors->any())
                <div
                    class="p-4 bg-red-100 dark:bg-red-900/50 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-lg mb-6">
                    <h3 class="font-bold">Terjadi Kesalahan</h3>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.jadwal-kuliah.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kolom Kiri --}}
                    <div class="flex flex-col gap-6">
                        <div>
                            <label for="hari"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hari</label>
                            <select name="hari" id="hari"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Hari</option>
                                <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            </select>
                            @error('hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mata_kuliah"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mata Kuliah</label>
                            <select name="mata_kuliah" id="mata_kuliah"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach ($mataKuliah as $mk)
                                    <option value="{{ $mk->id }}"
                                        {{ old('mata_kuliah') == $mk->id ? 'selected' : '' }}>{{ $mk->name }}</option>
                                @endforeach
                            </select>
                            @error('mata_kuliah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ruangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ruangan</label>
                            <select name="ruangan" id="ruangan"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}"
                                        {{ old('ruangan') == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ruangan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dosen"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dosen</label>
                            <select name="dosen" id="dosen"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Dosen</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->id }}"
                                        {{ old('dosen') == $dosen->id ? 'selected' : '' }}>{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                            @error('dosen')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="flex flex-col gap-6">
                        <div>
                            <label for="semester"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester</label>
                            <select name="semester" id="semester"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Semester</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}"
                                        {{ old('semester') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->display_name }}</option>
                                @endforeach
                            </select>
                            @error('semester')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program_studi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Program
                                Studi</label>
                            <select name="program_studi" id="program_studi"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Program Studi</option>
                                @foreach ($programStudi as $ps)
                                    <option value="{{ $ps->id }}"
                                        {{ old('program_studi') == $ps->id ? 'selected' : '' }}>{{ $ps->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('program_studi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="golongan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Golongan</label>
                            <select name="golongan" id="golongan"
                                class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                required>
                                <option value="">Pilih Golongan</option>
                            </select>
                            @error('golongan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="jam_mulai"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam
                                    Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}"
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
                                <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}"
                                    class="w-full text-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:[&::-webkit-calendar-picker-indicator]:invert"
                                    required>
                                @error('jam_selesai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <span class="text-xs text-red-400">* Untuk memunculkan pilihan golongan, silahkan pilih program studi
                        terlebih dahulu.</span>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.jadwal-kuliah.index') }}"
                            class="text-sm px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="text-sm px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const programStudiSelect = document.getElementById('program_studi');
            const golonganSelect = document.getElementById('golongan');
            const golonganData = @json($golonganData);
            const oldGolongan = "{{ old('golongan') }}";

            function updateGolonganOptions() {
                const selectedProdi = programStudiSelect.value;
                golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>'; // Reset

                // Tambahkan opsi "Semua Golongan"
                const semuaOption = document.createElement('option');
                semuaOption.value = 'all';
                semuaOption.textContent = 'Semua Golongan (Kelas Besar)';
                if (oldGolongan === 'all') {
                    semuaOption.selected = true;
                }
                golonganSelect.appendChild(semuaOption);

                if (selectedProdi && golonganData[selectedProdi]) {
                    golonganData[selectedProdi].forEach(function(golongan) {
                        const option = document.createElement('option');
                        option.value = golongan.id;
                        option.textContent = golongan.nama_golongan;
                        if (oldGolongan == golongan.id) {
                            option.selected = true;
                        }
                        golonganSelect.appendChild(option);
                    });
                }
            }

            programStudiSelect.addEventListener('change', updateGolonganOptions);

            // Panggil fungsi saat halaman dimuat untuk mengisi dropdown golongan jika ada data old() dari prodi
            if (programStudiSelect.value) {
                updateGolonganOptions();
            }
        });
    </script>
@endsection
