<x-layout>
    <div class="px-4 md:px-6 py-4">
        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200 dark-mode-transition mb-4">
            <a href="{{ route('home') }}"
                class="text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </h1>
        <h1 class="text-xl md:text-2xl font-medium text-gray-800 dark:text-gray-200 dark-mode-transition mb-6">
            Detail Presensi - <strong>Program Studi {{ $program_studi }}</strong>
        </h1>

        <div class="flex flex-col-reverse md:flex-row items-center w-full md:gap-8">
            <div class="flex-1 w-full">
                <form method="GET" action="{{ route('detail.presensi', $program_studi) }}" class="mb-4">
                    <div class="w-full grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Semester -->
                        <div class="w-full col-span-1">
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester" id="semester" onchange="fetchDataPresensi()"
                                class="mt-1 block w-full px-2 py-2 border border-gray-300 dark:border-gray-600 dark-mode-transition rounded-md text-sm">
                                <option value=""
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                    Semua Semester</option>
                                @foreach ($semesterOptions as $semester)
                                    <option value="{{ $semester->id }}"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                        {{ request('semester') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Golongan -->
                        <div class="w-full col-span-1">
                            <label for="golongan" class="block text-sm font-medium text-gray-700">Golongan</label>
                            <select name="golongan" id="golongan" onchange="fetchDataPresensi()"
                                class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value=""
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                    Semua Golongan</option>
                                @foreach ($golonganOptions as $golongan)
                                    <option value="{{ $golongan->id }}"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                        {{ request('golongan') == $golongan->id ? 'selected' : '' }}>
                                        {{ $golongan->nama_golongan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ruangan -->
                        <div class="w-full col-span-1">
                            <label for="ruangan" class="block text-sm font-medium text-gray-700">Ruangan</label>
                            <select name="ruangan" id="ruangan" onchange="fetchDataPresensi()"
                                class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value=""
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                    Semua Ruangan</option>
                                @foreach ($ruanganOptions as $ruangan)
                                    <option value="{{ $ruangan->id }}"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                        {{ request('ruangan') == $ruangan->id ? 'selected' : '' }}>
                                        {{ $ruangan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mata Kuliah -->
                        <div class="w-full col-span-1">
                            <label for="mata_kuliah" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                            <select name="mata_kuliah" id="mata_kuliah" onchange="fetchDataPresensi()"
                                class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value=""
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">
                                    Semua Mata Kuliah</option>
                                @foreach ($mataKuliahOptions as $mataKuliah)
                                    <option value="{{ $mataKuliah->id }}"
                                        class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                        {{ request('mata_kuliah') == $mataKuliah->id ? 'selected' : '' }}>
                                        {{ $mataKuliah->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="md:mt-2 mb-2 md:mb-0 w-full md:w-auto">
                <form id="search-form" class="flex items-center justify-end">
                    <input type="text" name="search" placeholder="Cari Mahasiswa berdasarkan NIM...."
                        id="search-input" value="{{ old('search', request('search')) }}"
                        class="px-4 py-2 w-full text-sm rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-gray-700 focus:border-transparent transition md:w-2xs dark:placeholder-gray-300">
                </form>
            </div>
        </div>

        <div
            class="overflow-x-auto bg-white dark:bg-gray-900/80 border dark-mode-transition border-gray-200 dark:border-gray-100 rounded-xl shadow mb-5">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-100 dark:bg-black/60 dark-mode-transition">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            No.</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Nama</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            NIM</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Semester</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Program Studi
                        </th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Golongan</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Mata Kuliah</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Ruangan</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Waktu Presensi
                        </th>
                    </tr>
                </thead>

                <tbody
                    class="bg-white dark:bg-black/20 divide-y divide-gray-200 dark:divide-gray-100 text-sm text-gray-800 dark:text-gray-100 dark-mode-transition">
                    @forelse ($presensi as $items)
                        @foreach ($items as $index => $item)
                            <tr>
                                @if ($index === 0)
                                    <!-- No. -->
                                    <td class="px-4 py-3 text-center" rowspan="{{ $items->count() }}">
                                        {{ $loop->parent->iteration }}.
                                    </td>
                                    <!-- Nama -->
                                    <td class="px-4 py-3 text-center" rowspan="{{ $items->count() }}">
                                        {{ $item->user->name }}
                                    </td>
                                    <!-- NIM -->
                                    <td class="px-4 py-3 text-center" rowspan="{{ $items->count() }}">
                                        {{ $item->user->nim }}
                                    </td>
                                @endif

                                <!-- Semester -->
                                <td class="px-4 py-3 text-center">
                                    {{ $item->jadwalKuliah?->semester?->display_name ?? '-' }}
                                </td>
                                <!-- Program Studi -->
                                <td class="px-4 py-3 text-center">{{ $item->user->programStudi->name }}</td>
                                <!-- Golongan -->
                                <td class="px-4 py-3 text-center">
                                    {{ $item->jadwalKuliah->golongan->nama_golongan }}
                                </td>
                                <!-- Mata Kuliah -->
                                <td class="px-4 py-3 text-center">{{ $item->mataKuliah->name }}</td>
                                <!-- Ruangan -->
                                <td class="px-4 py-3 text-center">{{ $item->jadwalKuliah->ruangan->name }}</td>
                                <!-- Waktu Presensi -->
                                <td class="px-4 py-2 text-center">
                                    {{ \Carbon\Carbon::parse($item->waktu_presensi)->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada data presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    <script>
        const echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
        });

        echo.channel('presensi')
            .listen('.eventPresensi', (e) => {
                console.log(e);
                refreshPresensi();
            })
            .error((error) => {
                console.log("Error:", error);
            });

        let params = new URLSearchParams();

        function fetchDataPresensi() {
            const programStudi = @json($program_studi);
            const semester = document.querySelector('#semester')?.value || '';
            const golongan = document.querySelector('#golongan')?.value || '';
            const ruangan = document.querySelector('#ruangan')?.value || '';
            const mataKuliah = document.querySelector('#mata_kuliah')?.value || '';
            const search = document.getElementById('search-input')?.value || '';

            params = new URLSearchParams({
                program_studi: programStudi,
                semester,
                golongan,
                ruangan,
                mata_kuliah: mataKuliah,
                search
            });

            fetch(`/presensi/filtered?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    updateTable(data);
                });
        }

        function updateTable(data) {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada data presensi.</td>
            </tr>`;
                return;
            }

            data.forEach((item, index) => {
                const semesterName = (item.jadwalKuliah && item.jadwalKuliah.semester) ?
                    item.jadwalKuliah.semester.display_name :
                    'N/A';

                const golonganNama = (item.jadwalKuliah && item.jadwalKuliah.golongan) ?
                    item.jadwalKuliah.golongan.nama_golongan :
                    'N/A';

                const ruanganName = (item.jadwalKuliah && item.jadwalKuliah.ruangan) ?
                    item.jadwalKuliah.ruangan.name :
                    'N/A';

                const mataKuliahName = item.mataKuliah ? item.mataKuliah.name : 'N/A';

                const userName = item.user ? item.user.name : 'N/A';
                const userNim = item.user ? item.user.nim : 'N/A';

                const jamPresensi = new Date(item.waktu_presensi).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const rowHTML = `
        <tr>
            <td class="px-4 py-3 text-center">${index + 1}.</td>
            <td class="px-4 py-3 text-center">${userName}</td>
            <td class="px-4 py-3 text-center">${userNim}</td>
            <td class="px-4 py-3 text-center">${semesterName}</td>
            <td class="px-4 py-3 text-center">${item.user.programStudi ? item.user.programStudi.name : 'N/A'}</td>
            <td class="px-4 py-3 text-center">${golonganNama}</td>
            <td class="px-4 py-3 text-center">${mataKuliahName}</td>
            <td class="px-4 py-3 text-center">${ruanganName}</td>
            <td class="px-4 py-2 text-center">${jamPresensi}</td>
        </tr>
        `;
                tbody.innerHTML += rowHTML;
            });
        }

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        document.querySelectorAll('#semester, #golongan, #ruangan, #mata_kuliah, #search')
            .forEach(el => {
                el.addEventListener('change', fetchDataPresensi);
            });

        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Hindari reload halaman

            // Ambil value input baru
            const updatedSearch = document.getElementById('search-input').value;
            params.set('search', updatedSearch);

            // Jalankan fetch presensi dengan search terbaru
            fetchDataPresensi();
        });

        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', debounce(function() {
            fetchDataPresensi();
        }, 400));

        setInterval(fetchDataPresensi, 3000); // Refresh setiap 5 detik

        fetchDataPresensi();
    </script>
</x-layout>
