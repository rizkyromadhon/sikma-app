<x-layout>
    <x-slot:title>{{ $title ?? 'Presensi Kuliah' }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 md:px-8 mb-20 md:mb-0 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 dark-mode-transition mb-6">Presensi Kuliah</h1>

        @auth
            @if (Auth::user()->role == 'admin')
                <div
                    class="bg-yellow-100 dark:bg-yellow-900/50 dark:backdrop-blur-sm border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-100 dark-mode-transition px-4 py-3 rounded-md shadow-md">
                    <strong>Info:</strong> Admin Program Studi tidak memiliki Presensi Kuliah.
                </div>
            @else
                @php
                    $semesterTempuh = Auth::user()->id_semester;
                    $isGanjil = $semesterTempuh % 2 != 0;

                    $bulanPilihan = $isGanjil ? range(7, 12) : range(1, 6);

                    $bulanRequest = (int) request('bulan', $bulanPilihan[0]);
                    $bulanSekarang = in_array($bulanRequest, $bulanPilihan) ? $bulanRequest : $bulanPilihan[0];

                    $mataKuliahSelected = request('mata_kuliah');
                    $today = \Carbon\Carbon::now();
                    $mingguRequest = $mingguRequest ?? 1;
                @endphp

                <div
                    class="flex flex-col md:flex-row w-full md:w-150 items-center space-x-0 space-y-3 md:space-y-0 md:space-x-5 mb-4">
                    <!-- Mata Kuliah -->
                    <div class="w-full flex-1">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Mata
                            Kuliah</label>
                        <select name="mata_kuliah" onchange="fetchData()"
                            class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark-mode-transition">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                Semua Mata
                                Kuliah</option>
                            @foreach ($mata_kuliah as $mata_kuliahItem)
                                <option value="{{ $mata_kuliahItem->id }}"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    {{ request('mata_kuliah') == $mata_kuliahItem->id ? 'selected' : '' }}>
                                    {{ $mata_kuliahItem->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Bulan -->
                    <div class="w-full flex-1">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Bulan</label>
                        <select name="bulan" onchange="fetchData()"
                            class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark-mode-transition">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Bulan
                            </option>
                            @foreach ($bulanPilihan as $i)
                                <option value="{{ $i }}" {{ $i == $bulanSekarang ? 'selected' : '' }}
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                    {{ \Carbon\Carbon::create()->month($i)->locale('id')->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Minggu -->
                    <div class="w-full flex-1">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Minggu</label>
                        <select name="minggu" onchange="fetchData()"
                            class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark-mode-transition">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Minggu
                            </option>
                            @for ($minggu = 1; $minggu <= 5; $minggu++)
                                <option value="{{ $minggu }}" {{ $minggu == $mingguRequest ? 'selected' : '' }}
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                    Minggu {{ $minggu }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Presensi Table -->
                <div
                    class="overflow-x-auto bg-white dark:bg-gray-900/80 border dark-mode-transition border-gray-200 dark:border-gray-600 rounded-xl shadow">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 dark-mode-transition">
                        <thead class="bg-gray-100 dark:bg-black/60 dark-mode-transition ">
                            <tr>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-100 dark-mode-transition uppercase">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-100 dark-mode-transition uppercase">
                                    Mata Kuliah
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-100 dark-mode-transition uppercase">
                                    Waktu
                                    Presensi
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-100 dark-mode-transition uppercase">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-100 dark-mode-transition uppercase">
                                    Keterangan
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-black/20 divide-y divide-gray-200 dark:divide-gray-600 text-sm text-gray-800 dark:text-gray-100 dark-mode-transition">
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
                                        <td class="px-4 py-4 text-center">
                                            @php
                                                $statusColor = '';
                                                if ($item->status === 'Hadir') {
                                                    $statusColor =
                                                        'bg-green-200 text-green-600 dark:bg-green-900/40 dark:text-green-200 dark:backdrop-blur-sm';
                                                } elseif ($item->status === 'Tidak Hadir') {
                                                    $statusColor =
                                                        'bg-red-200 text-red-600 dark:bg-red-900/40 dark:text-red-200 dark:backdrop-blur-sm';
                                                } else {
                                                    $statusColor =
                                                        'bg-blue-200 dark:bg-blue-900/40 dark:text-blue-200 dark:backdrop-blur-sm text-blue-600';
                                                }
                                            @endphp

                                            <span
                                                class="px-4 py-2 text-sm font-medium rounded-full w-28 border inline-block text-center {{ $statusColor }} dark-mode-transition">
                                                {{ ucfirst($item->status) }}
                                            </span>
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
                <div class="flex items-center justify-center md:justify-start mt-4 space-x-5 mb-5">
                    <div class="flex gap-3">
                        <a href="{{ url('/presensi-kuliah/download/pdf') }}" x-on:click="$store.loading.value = true">
                            <button
                                class="flex items-center gap-1 text-sm text-white bg-gray-800 dark:bg-red-900/40 dark:text-red-200 dark:backdrop-blur-sm dark:hover:bg-red-900/50 dark:hover:text-red-100 border border-gray-700 hover:bg-gray-900 px-3 rounded-md py-2 transition {{ $presensi->isEmpty() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                                {{ $presensi->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-file-pdf mr-1"></i>
                                Download PDF
                            </button>
                        </a>
                        <a href="{{ url('/presensi-kuliah/download/xlsx') }}" x-on:click="$store.loading.value = true">
                            <button
                                class="flex items-center gap-1 text-sm text-gray-800 dark:text-green-200 bg-transparent dark:bg-green-900/40 backdrop-blur-sm dark:hover:bg-green-900/50 dark:hover:text-green-100 border border-gray-700 hover:bg-gray-800 hover:text-white  px-3 py-2 rounded-md transition  {{ $presensi->isEmpty() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}"
                                {{ $presensi->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-file-excel text-green-600 mr-1"></i>
                                Download Excel
                            </button>
                        </a>
                    </div>
                </div>
            @endif
        @endauth
        @guest
            <div
                class="bg-yellow-100 dark:bg-yellow-900/50 dark:backdrop-blur-sm border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-100 dark-mode-transition px-4 py-3 rounded-md shadow-md">
                <strong>Info:</strong> Silahkan <a href="{{ route('login') }}"
                    class="text-blue-600 dark:text-blue-400 dark-mode-transition underline">login</a>
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
                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-100">Tidak ada data presensi.</td>
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
                        item.status === 'Hadir' ?
                        'bg-green-200 text-green-600 dark:bg-green-900/40 dark:text-green-200 dark:backdrop-blur-sm' :
                        item.status === 'Tidak Hadir' ?
                        'bg-red-200 text-red-600 dark:bg-red-900/40 dark:text-red-200 dark:backdrop-blur-sm' :
                        'bg-blue-200 dark:bg-blue-900/40 dark:text-blue-200 dark:backdrop-blur-sm text-blue-600';

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
                <td class="px-4 py-4 text-center">
                    <span class="px-4 py-2 text-sm font-medium rounded-full w-28 border inline-block dark-mode-transition ${statusColor}">
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
