@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.mahasiswa.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Tambah Mahasiswa</h1>
        </div>

        <div class="px-2 rounded-md">
            <form action="{{ route('admin.mahasiswa.store') }}" enctype="multipart/form-data" method="post" class="flex gap-6">
                @csrf
                @method('POST')
                <div class="bg-white dark:bg-black dark:border dark:border-gray-700 p-4 shadow w-full">
                    <div class="flex gap-4">
                        <div class="flex flex-col flex-1 gap-4 mb-4">
                            <div class="flex flex-col gap-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                                    Lengkap</label>
                                <input type="text" id="name" name="name"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="Ahmad Sutedjo, S.Kom, M.Kom">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="nim"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">NIM</label>
                                <input type="text" id="nim" name="nim"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="12345678">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="no_hp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">Email</label>
                                <input type="email" id="email" name="email"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="contoh@gmail.com">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="no_hp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">No.
                                    HP</label>
                                <input type="varchar" id="no_hp" name="no_hp"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="0812345678">
                            </div>
                        </div>
                        <div class="flex flex-col flex-1 gap-4">

                            <div class="flex flex-col gap-2">
                                <label for="semester"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Semester
                                    Tempuh</label>
                                <select name="semester" id="semester"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ old('semester', request('semester')) == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="program_studi"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                                    Studi</label>
                                <select id="programStudi" name="program_studi"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($programStudi as $ps)
                                        <option value="{{ $ps->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                            {{ $ps->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="golongan"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Golongan</label>
                                <select id="golongan" name="golongan"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    <option value=""
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                        Pilih Golongan
                                    </option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="gender"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Gender</label>
                                <select id="gender" name="gender"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    <option value="Laki-laki"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="alamat"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">Alamat</label>
                        <input type="text" id="alamat" name="alamat"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                            placeholder="Jalan Raya 123, Jakarta">
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Tambah</button>
                </div>
                <div
                    class="bg-white dark:bg-black dark:border dark:border-gray-700 dark-mode-transition p-4 shadow w-[400px] space-y-4 flex flex-col justify-between h-[500px]">
                    <div class="w-full flex items-center justify-center">
                        <img id="preview-image"
                            src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                            alt="pas-photo" class="object-cover object-center h-[350px]">
                    </div>
                    <div>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                        <label for="foto">
                            <div
                                class="w-full h-10 border-2 text-gray-800 dark:text-gray-200 flex items-center rounded border-dashed justify-center hover:border-gray-900 hover:bg-gray-100 dark:hover:bg-gray-900 dark:border-gray-400 cursor-pointer transition-all hover:text-gray-900">
                                <p class="text-sm font-medium">Pilih Foto Profil</p>
                            </div>
                        </label>
                    </div>
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
                }

                // Set nilai default ke opsi pertama yang cocok
                golonganSelect.value = firstVisibleOption ? firstVisibleOption.value : "";
            }

            // Panggil filterGolongan saat halaman pertama kali dimuat
            filterGolongan();

            // Tambahkan event listener untuk perubahan program studi
            prodiSelect.addEventListener('change', filterGolongan);
        });

        const fileInput = document.querySelector('input[name="foto"]');
        const previewImage = document.getElementById('preview-image');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImage.src = evt.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
