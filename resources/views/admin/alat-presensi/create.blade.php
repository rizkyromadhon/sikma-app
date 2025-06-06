@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.alat-presensi.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Tambah Alat Presensi</h1>
        </div>

        <div class="px-4 rounded-md">
            <div
                class="bg-white dark:bg-black dark:border dark:border-gray-700 shadow-md rounded-lg py-6 w-full max-w-xl flex items-center justify-center">
                <form action="{{ route('admin.alat-presensi.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
                    @csrf
                    @method('POST')
                    <div class="flex items-center justify-center gap-6">
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-col gap-2">
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                                    Alat</label>
                                <input type="text" name="name" id="name"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="SIKMA-AJK">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="ssid"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">SSID
                                    WiFi</label>
                                <input type="text" name="ssid" id="ssid"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="Contoh: WiFi_Kampus">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="wifi_password"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Password
                                    WiFi</label>
                                <input type="text" name="wifi_password" id="wifi_password"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 h-10"
                                    placeholder="Password WiFi">
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex flex-col gap-2">
                                <label for="id_ruangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Ruangan</label>
                                <select name="id_ruangan" id="id_ruangan"
                                    class="block px-4 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition h-10">
                                    @foreach ($ruangan as $item)
                                        <option value="{{ $item->id }}"
                                            class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="jadwal_nyala"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Jadwal
                                    Nyala</label>
                                <input type="time" name="jadwal_nyala" id="jadwal_nyala"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 dark:[&::-webkit-calendar-picker-indicator]:invert h-10">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="jadwal_mati"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Jadwal
                                    Mati</label>
                                <input type="time" name="jadwal_mati" id="jadwal_mati"
                                    class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 dark:[&::-webkit-calendar-picker-indicator]:invert h-10">
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">
                        Tambah Alat
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
