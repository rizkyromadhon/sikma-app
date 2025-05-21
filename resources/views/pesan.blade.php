<x-layout>
    <div class="max-w-2xl mx-auto mt-6 bg-white p-4 shadow-md rounded-md">
        <h2 class="text-xl font-semibold mb-4">Kotak Pesan</h2>

        @forelse ($laporan as $item)
            <div class="flex justify-center mb-4"><span
                    class="text-sm font-semibold px-2 py-1 rounded
                    @if ($item->status == 'Belum Ditangani') bg-red-200 text-red-800
                    @elseif($item->status == 'Sedang Diproses') bg-yellow-200 text-yellow-800
                    @elseif($item->status == 'Selesai') bg-green-200 text-green-800 @endif">
                    {{ ucfirst($item->status) }}
                </span></div>
            {{-- Pesan dari mahasiswa (kanan) --}}
            <div class="flex justify-end mb-4 items-start">
                {{-- Avatar mahasiswa --}}
                <div class="max-w-xs">
                    <div class="relative bg-blue-100 text-sm p-3 rounded-lg shadow-inner rounded-br-none">
                        <p class="font-semibold text-blue-900 mb-1">Anda</p>
                        <p>{{ $item->pesan }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500">{{ $item->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <i class="fas fa-user-circle text-2xl text-gray-400 ml-2"></i>
            </div>

            {{-- Pesan dari admin (kiri) --}}
            @if ($item->balasan)
                <div class="flex justify-start mb-6 items-start">
                    {{-- Avatar admin --}}
                    <i class="fas fa-user-shield text-xl text-gray-400 mr-2"></i>
                    <div class="max-w-xs">
                        <div class="relative bg-gray-100 text-sm p-3 rounded-lg shadow-inner rounded-bl-none">
                            <p class="font-semibold text-gray-800 mb-1">Admin Program Studi</p>
                            <p>{{ $item->balasan }}</p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500">{{ $item->updated_at->format('d M Y H:i') }}</p>
                                {{-- Status pesan admin bisa tambahkan ikon di sini --}}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex justify-start mb-6 items-start">
                    {{-- Avatar admin --}}
                    <i class="fas fa-user-shield text-xl text-gray-400 mr-2"></i>
                    <div class="max-w-xs">
                        <div class="relative bg-gray-100 text-sm p-3 rounded-lg shadow-inner rounded-bl-none">
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500">{{ $item->updated_at->format('d M Y H:i') }}</p>
                                {{-- Status pesan admin bisa tambahkan ikon di sini --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (!$loop->last)
                <div class="my-8 border-t border-gray-300 mx-auto w-9/10"></div>
            @endif
        @empty
            <p class="text-gray-600">Belum ada pesan atau laporan.</p>
        @endforelse
    </div>
</x-layout>
