@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.dosen.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Edit Dosen
                {{ $datas->name }}</h1>
        </div>

        <div class="px-2 rounded-md">
            <form action="{{ route('admin.dosen.update', $datas->id) }}" enctype="multipart/form-data" method="post"
                class="flex gap-6">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-black dark:border dark:border-gray-700 p-4 shadow w-full">
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
                    <div class="flex gap-4 ">
                        <div class="flex flex-1 flex-col gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">Nama</label>
                                <input type="text" id="name" name="name"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                    placeholder="Ahmad Sutedjo, S.Kom, M.Kom" value="{{ old('name', $datas->name) }}">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="nip"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">NIP</label>
                                <input type="text" id="nip" name="nip"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                    placeholder="12345678" value="{{ old('nip', $datas->nip) }}">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="alamat"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">Alamat</label>
                                <input type="text" id="alamat" name="alamat"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                    placeholder="Jalan Raya 123, Jakarta" value="{{ old('alamat', $datas->alamat) }}">
                            </div>

                        </div>
                        <div class="flex flex-col gap-4 flex-1">
                            <div class="flex flex-col gap-2">
                                <label for="no_hp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">No.
                                    HP</label>
                                <input type="varchar" id="no_hp" name="no_hp"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                    placeholder="0812345678" value="{{ old('no_hp', $datas->no_hp) }}">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="no_hp"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition dark-mode-transition">Email</label>
                                <input type="email" id="email" name="email"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                    placeholder="dosen@gmail.com" value="{{ old('email', $datas->email) }}">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="program_studi"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                                    Studi</label>
                                <select name="program_studi" id="program_studi"
                                    class="mt-1 block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition">
                                    @foreach ($programStudi as $program)
                                        <option value="{{ $program->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                            {{ old('program_studi', request('program_studi')) == $program->name ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Simpan</button>
                </div>
                <div
                    class="bg-white dark:bg-black dark:border dark:border-gray-700 dark-mode-transition p-4 shadow w-1/5 space-y-4 flex flex-col justify-between h-[323px]">
                    <div class="w-full flex items-center justify-center">
                        <img id="preview-image"
                            src="{{ $datas->foto ? asset('storage/' . $datas->foto) : asset('img/avatar-default.png') }}"
                            alt="pas-photo" class="object-cover object-center h-[240px]">
                    </div>
                    <div>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                        <label for="foto">
                            <div
                                class="w-full h-10 border-2 text-gray-800 dark:text-gray-200 flex items-center rounded border-dashed justify-center hover:border-gray-900 hover:bg-gray-100 dark:hover:bg-gray-900 dark:border-gray-400 cursor-pointer transition-all hover:text-gray-900">

                                <p class="text-sm font-medium">
                                    {{ $datas->foto ? 'Ganti Foto Profil' : 'Pilih Foto Profil' }}</p>
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
