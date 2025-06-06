<x-layout>
    <div
        class="max-w-2xl mx-auto mt-6 bg-white dark:bg-gray-900/30 p-4 shadow-md rounded-md dark-mode-transition mb-8 md:mb-0">
        <h2 class="text-xl font-semibold mb-8 px-4 py-2 text-center md:text-left">Kotak Pesan</h2>

        @forelse ($laporan as $item)
            <div class="flex justify-center mb-4">
                @php
                    $statusColor = '';
                    if ($item->status === 'Belum Ditangani') {
                        $statusColor =
                            'bg-red-200 text-red-600 dark:bg-red-900/40 dark:text-red-200 dark:backdrop-blur-sm';
                    } elseif ($item->status === 'Sedang Diproses') {
                        $statusColor =
                            'bg-yellow-200 text-yellow-600 dark:bg-yellow-900/40 dark:text-yellow-200 dark:backdrop-blur-sm';
                    } else {
                        $statusColor =
                            'bg-blue-200 dark:bg-blue-900/40 dark:text-blue-200 dark:backdrop-blur-sm text-blue-600';
                    }
                @endphp
                <span
                    class="text-sm font-semibold px-2 py-1 rounded {{ $statusColor }} dark-mode-transition
                    ">
                    {{ ucfirst($item->status) }}
                </span>
            </div>
            {{-- Pesan dari mahasiswa (kanan) --}}
            <div class="flex justify-end mb-4 items-start">
                {{-- Avatar mahasiswa --}}
                <div class="max-w-xs">
                    <div
                        class="relative bg-blue-100 dark:bg-blue-900/40 dark-mode-transition text-sm p-4 rounded-lg shadow-inner rounded-br-none">
                        <p class="font-semibold text-blue-900 dark:text-blue-200 dark-mode-transition mb-1">Anda</p>
                        <p>{{ $item->pesan }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500 dark:text-gray-300 dark-mode-transition">
                                {{ $item->created_at->locale('id')->translatedFormat('l, d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                <i class="fas fa-user-circle text-2xl text-gray-400 dark:text-gray-300 dark-mode-transition ml-2"></i>
            </div>

            @if ($item->balasan)
                <div class="flex justify-start mb-6 items-start">
                    <i
                        class="fas fa-user-shield text-xl text-gray-400 dark:text-gray-300 dark-mode-transition mr-2"></i>
                    <div class="max-w-xs">
                        <div
                            class="relative bg-gray-100 dark:bg-gray-800 text-sm p-3 rounded-lg shadow-inner rounded-bl-none">
                            <p class="font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition mb-1">Admin
                                Program Studi</p>
                            <p>{{ $item->balasan }}</p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-300 dark-mode-transition">
                                    {{ $item->updated_at->locale('id')->translatedFormat('l, d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex justify-start mb-6 items-start">
                    <i
                        class="fas fa-user-shield text-xl text-gray-400 dark:text-gray-300 dark-mode-transition mr-2"></i>
                    <div class="max-w-xs">
                        <div
                            class="relative bg-gray-100 dark:bg-gray-800 dark-mode-transition text-sm p-3 rounded-lg shadow-inner rounded-bl-none">
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-300 dark-mode-transition">
                                    {{ $item->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (!$loop->last)
                <div class="my-8 border-t border-gray-300 dark:border-gray-600 dark-mode-transition mx-auto w-9/10">
                </div>
            @endif
        @empty
            <p class="py-16 text-gray-600 dark:text-gray-300 dark-mode-transition text-center">Belum ada pesan atau
                laporan.</p>
        @endforelse
    </div>
</x-layout>
