<x-layout>
    {{-- <x-slot:title>{{ $title }}</x-slot:title> --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Jadwal Kuliah</h1>
        @auth
            @if (auth()->user()->role === 'admin')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md shadow-md">
                    <strong>Info:</strong> Admin Program Studi tidak memiliki Jadwal Kuliah.
                </div>
            @else
                <div class="overflow-x-auto bg-white rounded-xl shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Hari</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Mata Kuliah
                                </th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Dosen</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Jam</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Ruangan</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                            @forelse ($jadwalGrouped as $hari => $items)
                                @foreach ($items as $index => $item)
                                    <tr>
                                        @if ($index === 0)
                                            <td class="px-6 py-4 align-center" rowspan="{{ $items->count() }}">
                                                {{ $hari }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 text-left">{{ $item->mataKuliah->name }}</td>
                                        <td class="px-6 py-4 text-center">{{ $item->dosen->name }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ $item->ruangan->name }}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada jadwal kuliah untuk
                                        semester ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        @endauth

        @guest
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md shadow-md">
                <strong>Info:</strong> Silahkan <a href="{{ route('login') }}" class="text-blue-600 underline">login</a>
                untuk melihat jadwal kuliah anda.
            </div>
        @endguest
    </div>
</x-layout>
