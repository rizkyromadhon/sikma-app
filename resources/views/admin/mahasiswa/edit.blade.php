@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.mahasiswa.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a> </h1>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Edit Mahasiswa
                {{ $user->name }}</h1>
        </div>

        <div class="px-2 rounded-md">
            <form action="{{ route('admin.mahasiswa.update', [$user->id, 'page' => request()->get('page')]) }}"
                enctype="multipart/form-data" method="post" class="flex gap-6">
                @csrf
                @method('POST')
                <div class="bg-white dark:bg-black dark:border dark:border-gray-700 p-4 shadow w-full">
                    <div class="flex gap-4">
                        <div class="flex flex-col flex-1 gap-4 mb-4">
                            <div class="flex flex-col gap-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                                    Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="Ahmad Sutedjo, S.Kom, M.Kom">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="nim"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">NIM</label>
                                <input type="text" id="nim" name="nim" value="{{ old('nim', $user->nim) }}"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="12345678">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="no_hp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="contoh@gmail.com">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="no_hp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">No.
                                    HP</label>
                                <input type="varchar" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="0812345678">
                            </div>
                        </div>
                        <div class="flex flex-col flex-1 gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="semester"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Semester
                                    Tempuh</label>
                                <select name="id_semester" id="semester"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ $user->id_semester == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="program_studi"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                                    Studi</label>
                                <select name="id_prodi" id="program_studi"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($programStudi as $prodi)
                                        <option value="{{ $prodi->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ $user->id_prodi == $prodi->id ? 'selected' : '' }}>
                                            {{ $prodi->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="golongan"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Golongan</label>
                                <select name="id_golongan" id="golongan"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($golonganData as $golongan)
                                        <option value="{{ $golongan->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ $user->id_golongan == $golongan->id ? 'selected' : '' }}>
                                            {{ $golongan->nama_golongan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="gender"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Gender</label>
                                <select name="gender" id="gender"
                                    class="block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    <option value="Laki-laki"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                        {{ $user->gender == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                        {{ $user->gender == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="alamat"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Alamat</label>
                        <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $user->alamat) }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                            placeholder="Jalan Raya 123, Jakarta">
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Simpan</button>
                </div>
                <div
                    class="bg-white dark:bg-black dark:border dark:border-gray-700 dark-mode-transition p-4 shadow w-[400px] space-y-4 flex flex-col justify-between h-[500px]">
                    <div class="w-full flex items-center justify-center">
                        <img id="preview-image"
                            src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/avatar-default.png') }}"
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
