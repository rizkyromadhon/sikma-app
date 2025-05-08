@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.dosen.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Tambah Dosen</h1>
        </div>

        <div>
            <form action="{{ route('admin.dosen.store') }}" enctype="multipart/form-data" method="post" class="flex gap-6">
                @csrf
                @method('POST')


                <div class="bg-white p-4 shadow w-full">
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
                    <div class="flex gap-4 ">
                        <div class="flex flex-1 flex-col gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <input type="text" id="name" name="name"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                                    placeholder="Ahmad Sutedjo, S.Kom, M.Kom">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                                <input type="text" id="nip" name="nip"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="12345678">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input type="text" id="alamat" name="alamat"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                                    placeholder="Jalan Raya 123, Jakarta">
                            </div>

                        </div>
                        <div class="flex flex-col gap-4 flex-1">
                            <div class="flex flex-col gap-2">
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                <input type="varchar" id="no_hp" name="no_hp"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                                    placeholder="0812345678">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                                    placeholder="dosen@gmail.com">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="program_studi" class="block text-sm font-semibold text-gray-700">Program
                                    Studi</label>
                                <select name="program_studi" id="program_studi"
                                    class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition">
                                    @foreach ($programStudi as $program)
                                        <option value="{{ $program->id }}"
                                            {{ old('program_studi', request('program_studi')) == $program->name ? 'selected' : '' }}>
                                            {{ $program->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
                </div>
                <div class="bg-white p-4 shadow w-1/5 space-y-4 flex flex-col justify-between h-[323px]">
                    <div class="w-full flex items-center justify-center">
                        <img id="preview-image"
                            src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                            alt="pas-photo" class="object-cover object-center h-[240px]">
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
