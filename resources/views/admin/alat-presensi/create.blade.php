@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.alat-presensi.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Tambah Alat Presensi</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg py-6 w-full max-w-xl flex items-center justify-center">
            <form action="{{ route('admin.alat-presensi.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
                @csrf
                @method('POST')
                <div class="flex items-center justify-center gap-6">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">
                            <label for="name" class="text-sm font-medium text-gray-700">Nama Alat</label>
                            <input type="text" name="name" id="name"
                                class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="SIKMA-AJK">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="ssid" class="text-sm font-medium text-gray-700">SSID WiFi</label>
                            <input type="text" name="ssid" id="ssid"
                                class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                                placeholder="Contoh: WiFi_Kampus">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="wifi_password" class="text-sm font-medium text-gray-700">Password WiFi</label>
                            <input type="text" name="wifi_password" id="wifi_password"
                                class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="Password WiFi">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">
                            <label for="id_ruangan" class="text-sm font-medium text-gray-700">Ruangan</label>
                            <select name="id_ruangan" id="id_ruangan"
                                class="text-sm px-4 py-2 rounded border border-gray-300 shadow">
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="jadwal_nyala" class="text-sm font-medium text-gray-700">Jadwal Nyala</label>
                            <input type="time" name="jadwal_nyala" id="jadwal_nyala"
                                class="text-sm px-4 py-2 rounded border border-gray-300 shadow">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="jadwal_mati" class="text-sm font-medium text-gray-700">Jadwal Mati</label>
                            <input type="time" name="jadwal_mati" id="jadwal_mati"
                                class="text-sm px-4 py-2 rounded border border-gray-300 shadow">
                        </div>
                    </div>
                </div>
                <button type="submit"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">
                    Tambah Alat
                </button>
            </form>
        </div>
    </div>
@endsection
