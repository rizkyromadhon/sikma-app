<x-layout>
    <x-slot:title>{{ $title ?? 'Presensi Kuliah' }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Presensi Kuliah</h1>

        @auth
            @if (Auth::user()->role == 'admin')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md shadow-md">
                    <strong>Info:</strong> Admin Program Studi tidak memiliki Presensi Kuliah.
                </div>
            @else
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

                {{-- <form action="/presensi-kuliah" method="GET" class="w-full flex items-center mb-4 md:mb-0"> --}}
                <div
                    class="flex flex-col md:flex-row w-full md:w-150 items-center space-x-0 space-y-3 md:space-y-0 md:space-x-5 mb-4">
                    <!-- Semester -->
                    {{-- <div class="w-full flex-1">
                        <label class="text-sm font-medium text-gray-700">Semester</label>
                        <input type="text" value="{{ $semesterTempuh }}" disabled
                            class="w-full px-2 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 cursor-not-allowed">
                    </div> --}}

                    <!-- Mata Kuliah -->
                    <div class="w-full flex-1">
                        <label class="text-sm font-medium text-gray-700">Mata Kuliah</label>
                        <select name="mata_kuliah" onchange="fetchData()"
                            class="w-full px-2 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">Semua Mata Kuliah</option>
                            @foreach ($mata_kuliah as $mata_kuliahItem)
                                <option value="{{ $mata_kuliahItem->id }}"
                                    {{ request('mata_kuliah') == $mata_kuliahItem->id ? 'selected' : '' }}>
                                    {{ $mata_kuliahItem->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Bulan -->
                    <div class="w-full flex-1">
                        <label class="text-sm font-medium text-gray-700">Bulan</label>
                        <select name="bulan" onchange="fetchData()"
                            class="w-full px-2 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">Semua Bulan</option>
                            @foreach ($bulanPilihan as $i)
                                <option value="{{ $i }}" {{ $i == $bulanSekarang ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('id')->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Minggu -->
                    <div class="w-full flex-1">
                        <label class="text-sm font-medium text-gray-700">Minggu</label>
                        <select name="minggu" onchange="fetchData()"
                            class="w-full px-2 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">Semua Minggu</option>
                            @for ($minggu = 1; $minggu <= 5; $minggu++)
                                <option value="{{ $minggu }}" {{ $minggu == $mingguRequest ? 'selected' : '' }}>
                                    Minggu {{ $minggu }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                {{-- </form> --}}

                <!-- Presensi Table -->
                <div class="overflow-x-auto bg-white rounded-xl shadow mb-5">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Mata Kuliah
                                </th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Waktu
                                    Presensi
                                </th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Keterangan
                                </th>
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
                                        <td class="px-4 py-2 text-center">
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
                                    <td colspan="5" class="text-center py-4">Tidak ada data presensi untuk bulan ini.
                                    </td>
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
            @endif
        @endauth
        @guest
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md shadow-md">
                <strong>Info:</strong> Silahkan <a href="{{ route('login') }}" class="text-blue-600 underline">login</a>
                untuk melihat presensi kuliah anda.
            </div>
        @endguest
    </div>

    <script>
        function fetchData() {
            const mataKuliah = document.querySelector('[name="mata_kuliah"]').value;
            const bulan = document.querySelector('[name="bulan"]').value;
            const minggu = document.querySelector('[name="minggu"]').value;

            const params = new URLSearchParams({
                mata_kuliah: mataKuliah,
                bulan,
                minggu
            });

            fetch(`/presensi-kuliah/ajax?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("HTTP error " + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    updateTable(data);
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                    document.querySelector('table tbody').innerHTML = `
                <tr><td colspan="5" class="text-center py-4 text-red-500">Terjadi kesalahan saat mengambil data.</td></tr>
            `;
                });
        }

        function updateTable(data) {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data presensi.</td>
            </tr>
        `;
                return;
            }

            // Group by tanggal
            const grouped = {};
            data.forEach(item => {
                if (!grouped[item.tanggal]) {
                    grouped[item.tanggal] = [];
                }
                grouped[item.tanggal].push(item);
            });

            // Render grouped rows
            Object.entries(grouped).forEach(([tanggal, items]) => {
                items.forEach((item, index) => {
                    const statusColor =
                        item.status === 'Hadir' ? 'bg-green-200 text-green-600' :
                        item.status === 'Tidak Hadir' ? 'bg-red-200 text-red-600' :
                        'bg-blue-200 text-blue-600';

                    const row = document.createElement('tr');

                    if (index === 0) {
                        const tdTanggal = document.createElement('td');
                        tdTanggal.className = 'px-6 py-3 text-center align-middle';
                        tdTanggal.rowSpan = items.length;
                        tdTanggal.textContent = tanggal;
                        row.appendChild(tdTanggal);
                    }

                    row.innerHTML += `
                <td class="px-4 py-2 text-center">${item.mata_kuliah}</td>
                <td class="px-4 py-2 text-center">${item.waktu}</td>
                <td class="px-4 py-2 text-center">
                    <span class="px-4 py-2 text-sm font-medium rounded-full w-28 border ${statusColor} w-28">
                        ${item.status}
                    </span>
                </td>
                <td class="px-4 py-2 text-center">${item.keterangan}</td>
            `;

                    tbody.appendChild(row);
                });
            });
        }
    </script>
</x-layout>
