<x-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil</h2>
            @if ($errors->has('nim'))
                <div class="alert alert-warning">
                    {{ $errors->first('nim') }}
                </div>
            @endif

            <form action="/profile/update" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', Auth::user()->nim) }}"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">
                        @error('nim')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <input type="text" name="program_studi"
                            value="{{ old('program_studi', Auth::user()->program_studi) }}"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Golongan/Kelas</label>
                        <input type="text" name="kelas" value="{{ old('kelas', Auth::user()->kelas) }}"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Hp (WhatsApp)</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="px-2 py-2 rounded-sm mt-1 block w-full border-gray-300 shadow-sm sm:text-sm">{{ old('alamat', Auth::user()->alamat) }}</textarea>

                    </div>

                    <div class="md:row">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <input type="file" name="foto" accept="image/*"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none">
                        @error('foto')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-4">
                            <img id="preview-image"
                                src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/user.png') }}"
                                class="w-32 h-32 rounded-full object-cover border shadow">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-500 transition duration-200">
                        Simpan Perubahan
                    </button>
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
</x-layout>
