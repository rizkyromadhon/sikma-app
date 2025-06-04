<x-layout>
    <div id="edit-profile-page" class="max-w-4xl mx-auto py-12 px-4 md:px-8">
        <div class="bg-white dark:bg-gray-900/40 dark-mode-transition shadow-md rounded-lg p-6">
            <h2
                class="text-2xl text-center md:text-left font-bold text-gray-800 dark:text-gray-200 dark-mode-transition mb-6">
                Edit
                Profil</h2>

            <form action="/profile/update" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex flex-col-reverse md:flex-row items-center gap-6 w-full">
                    <div class="flex flex-col gap-6 w-full">
                        <div class="flex-1 w-full">
                            <label
                                class="text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="px-3 py-2 rounded-md mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm text-sm md:text-md dark-mode-transition">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-1 w-full">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">No.
                                Hp (WhatsApp)</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}"
                                class="px-3 py-2 rounded-md mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm text-sm md:text-md dark-mode-transition">
                        </div>

                        <div class="flex-1 w-full">
                            <label
                                class="text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Alamat</label>
                            <textarea name="alamat" rows="3"
                                class="px-3 py-2 rounded-md mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm text-sm md:text-md dark-mode-transition">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                        </div>


                    </div>
                    <div
                        class="bg-white dark:bg-transparent dark:border dark:border-gray-600 dark-mode-transition p-4 shadow dark:shadow-none space-y-4 flex flex-col justify-between h-[265px] w-[200px] rounded">
                        <div class="bg-transparent w-full flex items-center justify-center rounded h-full">
                            <img id="preview-image"
                                src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/avatar-default.png') }}"
                                alt="pas-photo" class="object-cover object-center h-42">
                        </div>
                        <div>
                            <input type="file" id="foto" name="foto" accept="image/*" class="hidden">
                            <label for="foto">
                                <div
                                    class="w-full h-10 border-2 text-gray-800 dark:text-gray-200 dark-mode-transition flex items-center rounded border-dashed justify-center hover:border-gray-900 dark:hover:border-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 cursor-pointer transition-all hover:text-gray-900">
                                    <p class="text-sm font-medium">
                                        {{ $user->foto ? 'Ganti Foto Profil' : 'Pilih Foto Profil' }}</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                @if ($user->is_profile_complete == '1')
                    <div class="pt-4 flex items-center justify-center md:justify-start gap-4 w-full">
                        <button type="submit"
                            class="inline-block px-6 py-2 bg-gray-800 text-white text-sm md:text-md font-medium rounded-md hover:bg-gray-900 transition duration-200 cursor-pointer">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('profile') }}"
                            class="inline-block px-6 py-2 bg-white text-gray-800 border font-medium text-sm md:text-md rounded-md hover:bg-gray-800 hover:text-white transition duration-200 cursor-pointer">
                            Batal
                        </a>
                    </div>
                @else
                    <div class="flex items-center justify-center md:justify-start w-full">
                        <button type="submit"
                            class="inline-block px-6 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-900 transition duration-200 cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                @endif
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
