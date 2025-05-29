@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Alat Presensi</h1>
        <div class="flex items-center justify-start"><a href="{{ route('admin.alat-presensi.create') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 rounded-full cursor-pointer transition hover:bg-black">
                <span>Tambah Alat Presensi</span>
            </a>
        </div>
    </div>

    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow mb-4">
            @livewire('alat-presensi-table')
            {{-- <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase">ID Alat</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Lokasi</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">SSID</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Jadwal Nyala</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Jadwal Mati</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($alatPresensi as $item)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $item->id }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->ruangan->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->ssid }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->jadwal_nyala)->format('H:i') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->jadwal_mati)->format('H:i') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span id="status{{ $item->id }}"
                                    class="{{ $item->status == 1 ? 'text-green-600 bg-green-100 px-4 py-2 rounded-md' : 'text-red-600 bg-red-100 px-4 py-2 rounded-md' }}">
                                    {{ $item->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center flex gap-2">
                                <a href="{{ route('admin.alat-presensi.edit', $item->id) }}"
                                    class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 hover:bg-black transition font-medium">Edit</a>
                                <button type="button" id="btnDeleteModal{{ $item->id }}"
                                    class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Data alat presensi tidak
                                ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table> --}}
        </div>
    </div>
@endsection
