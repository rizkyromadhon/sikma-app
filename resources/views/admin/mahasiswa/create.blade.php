@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.mahasiswa.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Tambah Mahasiswa</h1>
        </div>

        <div>
            <form action="{{ route('admin.mahasiswa.store') }}" enctype="multipart/form-data" method="post"
                class="flex gap-6">
                @csrf
                @method('POST')

                <div class="bg-white p-4 shadow w-full h-[480px]">
                    <div class="flex gap-4">
                        <div class="flex flex-col flex-1 gap-4 mb-4">
                            <div class="flex flex-col gap-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama
                                    Lengkap</label>
                                <input type="text" id="name" name="name"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10"
                                    placeholder="Ahmad Sutedjo, S.Kom, M.Kom">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                                <input type="text" id="nim" name="nim"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10"
                                    placeholder="12345678">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10"
                                    placeholder="contoh@gmail.com">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="varchar" id="no_hp" name="no_hp"
                                    class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10"
                                    placeholder="0812345678">
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
                                            {{ $semester->semester_name }}
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
                            <div class="flex flex-col gap-2">
                                <label for="gender" class="block text-sm font-semibold text-gray-700">Gender</label>
                                <select id="gender" name="gender"
                                    class="text-sm px-2 py-2 border border-gray-300 rounded-md shadow h-10">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <input type="text" id="alamat" name="alamat"
                            class="text-sm px-2 py-2 rounded border border-gray-300 shadow h-10"
                            placeholder="Jalan Raya 123, Jakarta">
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
                </div>
                <div class="bg-white p-4 shadow w-[400px] space-y-4 flex flex-col justify-between h-[480px]">
                    <div class="bg-gray-200 w-full flex items-center justify-center">
                        <img id="preview-image"
                            src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                            alt="pas-photo" class="object-cover object-center h-[350px]">
                    </div>
                    <div>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                        <label for="foto">
                            <div
                                class="w-full h-10 border-2  text-gray-800 flex items-center rounded border-dashed justify-center hover:border-gray-900 hover:bg-gray-100 cursor-pointer transition-all hover:text-gray-900">
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
