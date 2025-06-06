<div wire:poll.1s>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark-mode-transition">
        <thead class="bg-gray-100 dark:bg-gray-900/30 dark-mode-transition dark:border-t dark:border-gray-700">
            <tr>
                <th
                    class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                    ID Alat</th>
                <th
                    class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                    Nama</th>
                <th
                    class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                    Lokasi</th>
                <th
                    class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                    SSID</th>
                <th
                    class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                    Jadwal Nyala</th>
                <th
                    class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                    Jadwal Mati</th>
                <th
                    class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                    Status</th>
                <th
                    class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                    Aksi</th>
            </tr>
        </thead>
        <tbody
            class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200 dark-mode-transition">
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
                            class="{{ $item->status == 1 ? 'text-green-600 dark:text-green-200 bg-green-100 dark:bg-green-900/60 px-4 py-2 rounded-md' : 'text-red-600 dark:text-red-200 bg-red-100 dark:bg-red-900/60   px-4 py-2 rounded-md' }}">
                            {{ $item->status == 1 ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 flex text-center items-center justify-center gap-2">
                        <a href="{{ route('admin.alat-presensi.edit', $item->id) }}"
                            class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition">Edit</a>
                        <button type="button" id="btnDeleteModal{{ $item->id }}"
                            class="text-sm text-gray-800 bg-transparent dark:bg-red-900/70 dark:text-white dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus
                        </button>
                    </td>
                </tr>
                
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada alat presensi yang
                        ditambahkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
