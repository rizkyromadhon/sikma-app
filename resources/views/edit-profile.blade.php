<x-layout>
    <div id="edit-profile-page" class="max-w-4xl mx-auto py-12 px-4 md:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl md:text2xl font-bold text-gray-800 mb-6">Edit Profil</h2>

            <form action="/profile/update" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex flex-col-reverse md:flex-row items-center gap-6 w-full">
                    <div class="flex flex-col gap-6 w-full">
                        <div class="flex-1 w-full">
                            <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="px-2 py-2 rounded-sm mt-1 w-full border-gray-300 shadow-sm sm:text-sm">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-1 w-full">
                            <label class="text-sm font-medium text-gray-700">No. Hp (WhatsApp)</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}"
                                class="px-2 py-2 rounded-sm mt-1 w-full border-gray-300 shadow-sm sm:text-sm">
                        </div>

                        <div class="flex-1 w-full">
                            <label class="text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" rows="3" class="px-2 py-2 rounded-sm mt-1 w-full border-gray-300 shadow-sm sm:text-sm">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                        </div>


                    </div>
                    <div
                        class="bg-white p-4 shadow space-y-4 flex flex-col justify-between h-[265px] w-[200px] rounded">
                        <div class="bg-transparent w-full flex items-center justify-center rounded h-full">
                            <img id="preview-image"
                                src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/avatar-default.png') }}"
                                alt="pas-photo" class="object-cover object-center h-42">
                        </div>
                        <div>
                            <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                            <label for="foto">
                                <div
                                    class="w-full h-10 border-2  text-gray-800 flex items-center rounded border-dashed justify-center hover:border-gray-900 hover:bg-gray-100 cursor-pointer transition-all hover:text-gray-900">
                                    <p class="text-sm font-medium">
                                        {{ $user->foto ? 'Ganti Foto Profil' : 'Pilih Foto Profil' }}</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>


                <div class="pt-4 flex items-center gap-4">
                    <button type="submit"
                        class="inline-block px-6 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-900 transition duration-200 cursor-pointer">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('profile') }}"
                        class="inline-block px-6 py-2 bg-white text-gray-800 border font-medium rounded-md hover:bg-gray-800 hover:text-white transition duration-200 cursor-pointer">
                        Batal
                    </a>
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
