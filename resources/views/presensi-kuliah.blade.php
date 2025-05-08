<x-layout>
    <x-slot:title>{{ $title ?? 'Presensi Kuliah' }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Presensi Kuliah</h1>

        @auth
            @php
                $semesterTempuh = Auth::user()->id_semester;
                $isGanjil = $semesterTempuh % 2 != 0;

                // Menentukan bulan pilihan berdasarkan semester
                $bulanPilihan = $isGanjil ? range(7, 12) : range(1, 6);

                // Mengambil bulan yang dipilih atau default ke bulan yang sesuai dengan semester
                $bulanRequest = (int) request('bulan', $bulanPilihan[0]);
                $bulanSekarang = in_array($bulanRequest, $bulanPilihan) ? $bulanRequest : $bulanPilihan[0];

                // Untuk menjaga pilihan mata kuliah, minggu di URL
                $mataKuliahSelected = request('mata_kuliah');
                $today = \Carbon\Carbon::now();
                $mingguRequest = $mingguRequest ?? 1;
            @endphp

            <div class="flex items-center space-x-5 mb-4">
                <form action="/presensi-kuliah" method="GET">
                    <div class="flex items-center space-x-5 mb-4">
                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Semester</label>
                            <input type="text" value="{{ $semesterTempuh }}" disabled
                                class="mt-1 block w-30 px-2 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 cursor-not-allowed">
                        </div>

                        <!-- Mata Kuliah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                            <select name="mata_kuliah"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm"
                                onchange="this.form.submit()">
                                <option value="">Semua Mata Kuliah</option>
                                @foreach ($mata_kuliah as $mata_kuliahItem)
                                    <option value="{{ $mata_kuliahItem->name }}"
                                        {{ request('mata_kuliah') == $mata_kuliahItem->name ? 'selected' : '' }}>
                                        {{ $mata_kuliahItem->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bulan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bulan</label>
                            <select name="bulan"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm"
                                onchange="this.form.submit()">
                                @foreach ($bulanPilihan as $i)
                                    <option value="{{ $i }}" {{ $i == $bulanSekarang ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->locale('id')->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Minggu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Minggu</label>
                            <select name="minggu"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm"
                                onchange="this.form.submit()">
                                @for ($minggu = 1; $minggu <= 5; $minggu++)
                                    <option value="{{ $minggu }}" {{ $minggu == $mingguRequest ? 'selected' : '' }}>
                                        Minggu {{ $minggu }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Presensi Table -->
            <div class="overflow-x-auto bg-white rounded-xl shadow mb-5">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Waktu Presensi
                            </th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                        @forelse ($presensiGrouped as $tanggal => $items)
                            @foreach ($items as $index => $item)
                                <tr>
                                    @if ($index === 0)
                                        <td rowspan="{{ $items->count() }}" class="px-6 py-3 text-center">
                                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d-F-Y') }}
                                        </td>
                                    @endif
                                    <td class="px-4 py-2 text-center">{{ $item->mataKuliah->name }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($item->status == 'Hadir')
                                            {{ \Carbon\Carbon::parse($item->waktu_presensi)->format('H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center flex items-center justify-center">
                                        <div
                                            class="
                                            @if ($item->status == 'Tidak Hadir') bg-red-200 text-red-500 border
                                            @elseif($item->status == 'Hadir') bg-green-200 text-green-500 border
                                            @elseif($item->status == 'Sakit' || $item->status == 'Izin') bg-blue-200 text-blue-500 border @endif
                                            px-4 py-2 text-sm font-medium rounded-full w-28 ">
                                            {{ ucfirst($item->status) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        {{ !empty($item->keterangan) ? $item->keterangan : '-' }}</td>

                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data presensi untuk bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-start mt-4 space-x-5 mb-5">
                <div class="flex gap-3">
                    <a href="{{ url('/presensi-kuliah/download/xlsx') }}" x-on:click="$store.loading.value = true">
                        <button
                            class="flex items-center gap-1 text-sm text-white bg-gray-800 border border-gray-700 hover:bg-gray-900 px-3 py-2 rounded transition  {{ $presensi->isEmpty() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                            {{ $presensi->isEmpty() ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                            </svg>
                            Download Excel
                        </button>
                    </a>

                    <a href="{{ url('/presensi-kuliah/download/pdf') }}" x-on:click="$store.loading.value = true">
                        <button
                            class="flex items-center gap-1 text-sm text-gray-800 bg-transparent border border-gray-700 hover:bg-gray-800 hover:text-white px-3 py-2 rounded transition {{ $presensi->isEmpty() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                            {{ $presensi->isEmpty() ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                            </svg>
                            Download PDF
                        </button>
                    </a>
                </div>
            </div>
        @endauth
    </div>
</x-layout>
