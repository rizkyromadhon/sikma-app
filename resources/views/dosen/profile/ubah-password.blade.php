@extends('dosen.layouts')

@section('dosen-content')
    {{-- Header Halaman --}}
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-slate-500 to-gray-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-key text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Ubah Password</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Ganti password Anda secara berkala untuk keamanan.
                    </p>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
                <form action="{{ route('dosen.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-6">
                        <div>
                            <label for="current_password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" required
                                class="mt-1 px-4 py-2 block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru</label>
                            <div class="relative mt-1">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                    class="pr-10 px-4 py-2 block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                                    <i class="fas fa-eye" x-show="!show" x-cloak></i>
                                    <i class="fas fa-eye-slash" x-show="show" x-cloak></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password
                                Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="mt-1 px-4 py-2 block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-slate-700 dark:text-white">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 text-right space-x-4 rounded-b-xl">
                        <a href="{{ route('dosen.profile') }}"
                            class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
