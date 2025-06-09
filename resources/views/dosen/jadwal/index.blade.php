@extends('dosen.layouts')

@section('dosen-content')
    {{-- Header Halaman --}}
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Jadwal Mengajar Saya</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jadwal lengkap Anda untuk semester ini</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="p-4 sm:p-6 lg:p-8">
        <div class="space-y-8">

            {{-- Loop untuk setiap hari --}}
            @forelse ($jadwalPerHari as $hari => $jadwals)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $hari }}</h3>
                    </div>
                    <div class="p-4 space-y-4">

                        {{-- Loop untuk setiap jadwal di hari tersebut --}}
                        @foreach ($jadwals as $jadwal)
                            <div
                                class="flex items-center space-x-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                {{-- Waktu --}}
                                <div class="w-24 text-center flex-shrink-0">
                                    <p class="text-base font-bold text-blue-600 dark:text-blue-400">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">-</p>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </p>
                                </div>

                                {{-- Garis Pemisah --}}
                                <div class="border-l border-gray-300 dark:border-gray-600 h-16"></div>

                                {{-- Detail Jadwal --}}
                                <div class="flex-1">
                                    <h4 class="text-md font-bold text-gray-900 dark:text-white">
                                        {{ $jadwal->mataKuliah->name }}</h4>
                                    <div
                                        class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-x-4 gap-y-1 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas fa-map-marker-alt fa-fw"></i>
                                            {{ $jadwal->ruangan->name }}
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas fa-university fa-fw"></i>
                                            {{ $jadwal->semester->display_name }}
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas fa-layer-group fa-fw"></i>
                                            {{ $jadwal->prodi->name }} -
                                            @if ($jadwal->is_kelas_besar)
                                                <span class="font-bold text-gray-700 dark:text-gray-300">Semua
                                                    golongan</span>
                                                <span
                                                    class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded-full">Kelas
                                                    Besar</span>
                                            @else
                                                <span>Golongan {{ $jadwal->semua_golongan_string }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                {{-- Tampilan jika tidak ada jadwal sama sekali --}}
                <div
                    class="text-center py-12 px-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tidak Ada Jadwal Mengajar</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Anda belum memiliki jadwal mengajar yang
                        ditugaskan untuk semester ini.</p>
                </div>
            @endforelse

        </div>
    </main>
@endsection
