@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.jadwal-kuliah.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Tambah Jadwal Kuliah</h1>
        </div>

        <div>
            <form action="{{ route('admin.jadwal-kuliah.store') }}" enctype="multipart/form-data" method="post"
                class="flex gap-6">
                @csrf
                @method('POST')

                <div class="bg-white px-8 py-4 shadow w-full max-w-4xl h-fit">
                    @if ($errors->any())
                        <div class="p-4 bg-red-100 border-2 text-red-500 rounded flex items-center gap-2 w-full mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-500 text-xs ml-2 py-1">{{ $error }}</p>
                                @endforeach
                            </div>

                        </div>
                    @endif
                    <div class="flex gap-6">
                        <div class="flex flex-col flex-1 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="hari" class="block text-sm font-medium text-gray-700">Hari</label>
                                <input type="text" id="hari" name="hari"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10"
                                    placeholder="Senin">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="mata_kuliah" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                                <select name="mata_kuliah" id="mata_kuliah"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10">
                                    @foreach ($mataKuliah as $matkul)
                                        <option value="{{ $matkul->id }}"
                                            {{ old('semester', request('semester')) == $matkul->id ? 'selected' : '' }}>
                                            {{ $matkul->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="ruangan" class="block text-sm font-medium text-gray-700">Ruangan</label>
                                <select name="ruangan" id="ruangan"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10">
                                    @foreach ($ruangans as $ruangan)
                                        <option value="{{ $ruangan->id }}"
                                            {{ old('semester', request('semester')) == $ruangan->id ? 'selected' : '' }}>
                                            {{ $ruangan->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="dosen" class="block text-sm font-medium text-gray-700">Dosen</label>
                                <select name="dosen" id="dosen"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10">
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}"
                                            {{ old('semester', request('semester')) == $dosen->id ? 'selected' : '' }}>
                                            {{ $dosen->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="flex flex-col flex-1 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="semester" class="block text-sm font-medium text-gray-700">Semester
                                    Tempuh</label>
                                <select name="semester" id="semester"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10">
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            {{ old('semester', request('semester')) == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="program_studi" class="block text-sm font-semibold text-gray-700">Program
                                    Studi</label>
                                <select id="programStudi" name="program_studi"
                                    class="text-sm px-2 py-2 border border-gray-300 rounded-md shadow h-10">
                                    @foreach ($programStudi as $ps)
                                        <option value="{{ $ps->id }}">{{ $ps->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="golongan" class="block text-sm font-semibold text-gray-700">Golongan</label>
                                <select id="golongan" name="golongan"
                                    class="text-sm px-2 py-2 border border-gray-300 rounded-md shadow h-10">
                                    <option value="">Pilih Golongan</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col gap-2">
                                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                    <input type="time" id="jam_mulai" name="jam_mulai"
                                        class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10">
                                </div>
                                <div class="flex items-center justify-center mt-7">-</div>
                                <div class="flex flex-col gap-2">
                                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam
                                        Berakhir</label>
                                    <input type="time" id="jam_selesai" name="jam_selesai"
                                        class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const golonganData = @json($golonganData); // Mengambil data golongan dari controller
            const prodiSelect = document.getElementById('programStudi');
            const golonganSelect = document.getElementById('golongan');

            function filterGolongan() {
                const selectedProdi = prodiSelect.value; // Mendapatkan id_prodi yang dipilih
                let firstVisibleOption = null;

                // Kosongkan dropdown golongan
                golonganSelect.innerHTML = '<option value="">Pilih Golongan</option>';

                // Jika ada program studi yang dipilih
                if (selectedProdi) {
                    // Ambil golongan yang sesuai dengan id_prodi yang dipilih
                    const golongans = golonganData[selectedProdi] || [];

                    // Tambahkan opsi golongan ke dropdown
                    golongans.forEach(golongan => {
                        const option = document.createElement('option');
                        option.value = golongan.id;
                        option.textContent = golongan.nama_golongan;
                        golonganSelect.appendChild(option);

                        // Menandai opsi pertama yang cocok untuk dipilih
                        if (!firstVisibleOption) {
                            firstVisibleOption = option;
                        }
                    });
                }

                // Set nilai default ke opsi pertama yang cocok
                golonganSelect.value = firstVisibleOption ? firstVisibleOption.value : "";
            }

            // Panggil filterGolongan saat halaman pertama kali dimuat
            filterGolongan();

            // Tambahkan event listener untuk perubahan program studi
            prodiSelect.addEventListener('change', filterGolongan);
        });
    </script>
@endsection
