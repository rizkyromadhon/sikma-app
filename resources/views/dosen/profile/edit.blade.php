@extends('dosen.layouts')

@section('dosen-content')
    {{-- Header Halaman --}}
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-sky-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-edit text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit Profil Saya</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui data diri Anda di sini.</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="p-4">
        <form action="{{ route('dosen.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-row gap-8">
                {{-- Kolom Kiri: Foto & Tombol --}}
                <div class="w-1/4" x-data="{ photoName: null, photoPreview: null }">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 flex flex-col items-center justify-between h-full">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Foto Profil</h3>

                        {{-- Image Preview --}}
                        <div class="w-55 h-70 mb-4">
                            <img x-show="!photoPreview"
                                src="{{ $dosen->foto ? asset('storage/' . $dosen->foto) : asset('img/avatar-default.png') }}"
                                alt="Foto Profil Saat Ini" class="w-55 h-70 object-cover">
                            <div x-show="photoPreview" class="w-55 h-70 bg-cover bg-center"
                                :style="'background-image: url(\'' + photoPreview + '\');'"></div>
                        </div>

                        <input type="file" name="foto" id="foto" class="hidden"
                            @change="photoName = $event.target.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($event.target.files[0]);">

                        <label for="foto"
                            class="cursor-pointer w-full text-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-sm font-medium py-2 px-4 rounded-lg transition">
                            <i class="fas fa-upload mr-2"></i>Pilih Foto Baru
                        </label>
                        <div x-show="photoName" class="text-xs text-gray-500 dark:text-gray-400 mt-2 truncate"
                            x-text="photoName"></div>
                        @error('foto')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Informasi --}}
                <div class="w-3/4">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
                        <div class="p-6 border-b dark:border-slate-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Diri</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap &
                                    Gelar</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $dosen->name) }}"
                                    class="mt-1 px-4 py-2 block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="nip"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
                                    <input type="text" name="nip" id="nip" value="{{ $dosen->nip }}"
                                        class="mt-1 px-4 py-2  block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm sm:text-sm dark:bg-slate-900 dark:text-gray-400 cursor-not-allowed"
                                        readonly>
                                </div>
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input type="email" name="email" id="email" value="{{ $dosen->email }}"
                                        class="mt-1 px-4 py-2  block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm sm:text-sm dark:bg-slate-900 dark:text-gray-400 cursor-not-allowed"
                                        readonly>
                                </div>
                            </div>
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No.
                                    HP (WhatsApp)</label>
                                <input type="text" name="no_hp" id="no_hp"
                                    value="{{ old('no_hp', $dosen->no_hp) }}"
                                    class="mt-1 px-4 py-2  block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                                @error('no_hp')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="alamat"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="mt-1 px-4 py-2  block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-slate-700 dark:text-white">{{ old('alamat', $dosen->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 text-right space-x-4">
                            <a href="{{ route('dosen.profile') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
