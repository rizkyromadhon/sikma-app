@extends('dosen.layouts')

@section('dosen-content')
    {{-- Header Halaman --}}
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Kelola Presensi</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pilih tanggal untuk melihat jadwal & presensi</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="p-4 sm:p-6 lg:p-8">
        {{-- [BARU] Filter Tanggal --}}
        {{-- Judul Hari yang Dipilih --}}
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Jadwal untuk:
                {{ $selectedDate->translatedFormat('l, d F Y') }}</h2>
            <form action="{{ route('dosen.presensi.index') }}" method="GET"
                class="flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full sm:w-auto">
                    <label for="tanggal" class="sr-only">Pilih Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $selectedDate->format('Y-m-d') }}"
                        onChange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </form>
        </div>
        @livewire('dosen.kelola-presensi', ['selectedDate' => $selectedDate, 'dosenId' => auth()->id()])
    </main>
@endsection
