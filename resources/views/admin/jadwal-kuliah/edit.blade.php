{{-- @dd($jadwal->mataKuliah); --}}

@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.jadwal-kuliah.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition0">Edit Jadwal Kuliah</h1>
        </div>

        <div class="px-2 rounded-md">
            <form action="{{ route('admin.jadwal-kuliah.update', $jadwal->id) }}?page={{ request()->get('page') }}"
                enctype="multipart/form-data" method="post" class="flex gap-6">
                @csrf
                @method('PUT')

                <div
                    class="bg-white dark:bg-black dark:border dark:border-gray-700 px-8 py-4 shadow w-full max-w-4xl h-fit">
                    @if ($errors->any())
                        <div
                            class="p-4 bg-red-100 dark:bg-red-900/50 border-2 text-red-500 dark:text-red-600 rounded flex items-center gap-2 w-full mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-500 dark:text-red-100 text-sm ml-4 py-1">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="flex gap-6">
                        <div class="flex flex-col flex-1 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="hari"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Hari</label>
                                <input type="text" id="hari" name="hari" value="{{ old('hari', $jadwal->hari) }}"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="Senin">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="mata_kuliah"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Mata
                                    Kuliah</label>
                                <select name="mata_kuliah" id="mata_kuliah"
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($mataKuliah as $matkul)
                                        <option value="{{ $matkul->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ old('mata_kuliah', $matkul->id == $jadwal->id_matkul ? 'selected' : '') }}>
                                            {{ $matkul->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="ruangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Ruangan</label>
                                <select name="ruangan" id="ruangan"
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($ruangans as $ruangan)
                                        <option value="{{ $ruangan->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ old('ruangan', $ruangan->id == $jadwal->id_ruangan ? 'selected' : '') }}>
                                            {{ $ruangan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="dosen"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Dosen</label>
                                <select name="dosen" id="dosen"
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ old('dosen', $dosen->id == $jadwal->id_dosen ? 'selected' : '') }}>
                                            {{ $dosen->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="flex flex-col flex-1 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="semester"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Semester
                                    Tempuh</label>
                                <select name="semester" id="semester"
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ old('semester', $semester->id == $jadwal->id_semester ? 'selected' : '') }}>
                                            {{ $semester->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="program_studi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                                    Studi</label>
                                <select id="programStudi" name="program_studi"
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($programStudi as $ps)
                                        <option value="{{ $ps->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                            {{ $ps->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="golongan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Golongan</label>
                                <select id="golongan" name="golongan"
                                    class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    <option value="{{ old('golongan', $jadwal->id_golongan) }}"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                        {{ $jadwal->golongan->nama_golongan }}</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col gap-2">
                                    <label for="jam_mulai"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Jam
                                        Mulai</label>
                                    <input type="time" id="jam_mulai" name="jam_mulai"
                                        value="{{ old('jam_mulai', $jadwal->jam_mulai) }}"
                                        class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition dark:[&::-webkit-calendar-picker-indicator]:invert h-10">
                                </div>
                                <div class="flex items-center justify-center mt-7">-</div>
                                <div class="flex flex-col gap-2">
                                    <label for="jam_selesai"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Jam
                                        Berakhir</label>
                                    <input type="time" id="jam_selesai" name="jam_selesai"
                                        value="{{ old('jam_selesai', $jadwal->jam_selesai) }}"
                                        class="block w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition dark:[&::-webkit-calendar-picker-indicator]:invert h-10">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const golonganData = @json($golonganData); // Mengambil data golongan dari controller
            const prodiSelect = document.getElementById('programStudi');
            const golonganSelect = document.getElementById('golongan');
            const selectedGolonganId =
                "{{ old('golongan', $jadwal->id_golongan) }}"; // Mendapatkan golongan_id yang terpilih sebelumnya

            function filterGolongan() {
                const selectedProdi = prodiSelect.value; // Mendapatkan id_prodi yang dipilih
                let firstVisibleOption = null;

                // Kosongkan dropdown golongan
                golonganSelect.innerHTML =
                    '<option value="" class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Pilih Golongan</option>';

                // Jika ada program studi yang dipilih
                if (selectedProdi) {
                    // Ambil golongan yang sesuai dengan id_prodi yang dipilih
                    const golongans = golonganData[selectedProdi] || [];

                    // Tambahkan opsi golongan ke dropdown
                    golongans.forEach(golongan => {
                        const option = document.createElement('option');
                        option.value = golongan.id;
                        option.textContent = golongan.nama_golongan;
                        option.className =
                            'dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition';
                        golonganSelect.appendChild(option);

                        // Menandai opsi pertama yang cocok untuk dipilih
                        if (!firstVisibleOption) {
                            firstVisibleOption = option;
                        }
                    });

                    // Jika ada old value golongan, pilih opsi yang sesuai
                    if (selectedGolonganId) {
                        const selectedOption = golonganSelect.querySelector(
                            `option[value="${selectedGolonganId}"]`);
                        if (selectedOption) {
                            selectedOption.selected = true; // Menandai opsi yang dipilih sebelumnya
                        }
                    }
                }

                // Set nilai default ke opsi pertama yang cocok jika tidak ada old value
                if (!selectedGolonganId && firstVisibleOption) {
                    golonganSelect.value = firstVisibleOption.value;
                }
            }

            // Panggil filterGolongan saat halaman pertama kali dimuat
            filterGolongan();

            // Tambahkan event listener untuk perubahan program studi
            prodiSelect.addEventListener('change', filterGolongan);
        });
    </script>
@endsection
