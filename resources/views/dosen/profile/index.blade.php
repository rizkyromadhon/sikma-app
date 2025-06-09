@extends('dosen.layouts')

@section('dosen-content')
    {{-- Header Halaman --}}
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-circle text-white text-base"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Informasi detail mengenai data diri Anda.</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="p-4">
        <div
            class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-700 p-8 border-b border-gray-100 dark:border-slate-600 rounded-xl shadow-sm border flex items-center gap-8">
            <div class="flex flex-col items-center w-1/3">
                @if ($dosen->foto)
                    <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto Profil"
                        class="w-32 h-32 rounded-full object-cover mb-4 ring-4 ring-white dark:ring-slate-700">
                @else
                    <div
                        class="w-32 h-32 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center mb-4 ring-4 ring-white dark:ring-slate-700 overflow-hidden">
                        <i class="fas fa-user-tie text-9xl text-gray-200"></i>
                    </div>
                @endif

                <h3 class="text-xl text-center font-bold text-gray-900 dark:text-white">{{ $dosen->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">NIP: {{ $dosen->nip ?? '-' }}</p>

                <div class="w-full border-t dark:border-slate-600 my-6"></div>

                <div class="w-full space-y-3">
                    <a href="{{ route('dosen.profile.edit') }}"
                        class="w-full text-sm flex items-center justify-center gap-2 text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-edit fa-fw"></i>Edit Profil
                    </a>
                    <a href="{{ route('dosen.password.edit') }}"
                        class="w-full text-sm flex items-center justify-center gap-2 text-center bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600 font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-key fa-fw"></i>Ubah Password
                    </a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 h-full w-2/3">
                <div class="p-6 border-b dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Info Kontak & Akademik</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-slate-200">{{ $dosen->email }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">No. HP
                            (WhatsApp)</label>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-slate-200">
                            {{ $dosen->no_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Program Studi
                        </label>
                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-slate-200">
                            {{ optional($dosen->programStudi)->name ?? 'Tidak terhubung ke prodi' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                        <p class="mt-1 text-base text-gray-900 dark:text-slate-200 leading-relaxed">
                            {{ $dosen->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
