<x-layout :isOldPassword="$isOldPassword">
    <div id="home-page" class="bg-white dark:bg-gray-900/20 dark-mode-transition py-2 sm:py-4 rounded-md" x-data
        x-bind:class="{ 'overflow-hidden': $store.loading.value }">

        <div class="mx-auto">
            <p
                class="mx-auto text-center mt-6 md:mt-6 text-3xl font-semibold tracking-tight text-balance text-gray-950 dark:text-gray-100 lg:text-4xl lg:w-120 dark-mode-transition">
                Sudahkah anda presensi hari ini?</p>
            <div class="mt-10 grid gap-4 sm:mt-12 grid-cols-1 lg:grid-cols-2 px-4 md:px-4">
                <div class="relative lg:row-span-2 min-h-[30rem] max-h-[50rem] flex flex-col">
                    <div
                        class="absolute inset-0 rounded-lg bg-white dark:bg-gray-900/30 dark-mode-transition border border-gray-200 dark:border-gray-700 lg:rounded-l-[1rem] h-full">
                    </div>
                    <div
                        class="relative flex flex-col h-full overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)] flex-grow">
                        <div class="px-4 md:px-8 pt-8 pb-3 sm:px-10 sm:pt-8 sm:pb-2 flex flex-col h-full">
                            <p
                                class="text-xl font-semibold  tracking-tight text-gray-950 dark:text-gray-100 text-center dark-mode-transition">
                                Mahasiswa yang melakukan presensi
                            </p>
                            <div class="relative mt-5 mb-5 flex-1">
                                <div
                                    class="absolute inset-0 rounded-lg bg-white dark:bg-gray-900/40 dark-mode-transition border border-gray-200 dark:border-gray-700 lg:rounded-t-[1rem] h-full">
                                </div>
                                <div
                                    class="relative flex flex-col h-full overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-t-[calc(1rem+1px)] flex-grow">
                                    <div id="presensi-list"
                                        class="px-4 p-3 sm:px-6 sm:p-5 flex-grow overflow-y-auto h-30 max-h-xl">
                                        <div class="flex justify-center items-center h-24 mt-25">
                                            <p class="text-gray-500 dark:text-gray-100 italic text-center text-sm">
                                                Belum ada yang melakukan presensi pada hari ini.
                                            </p>
                                        </div>
                                    </div>

                                </div>
                                <div
                                    class="pointer-events-none absolute inset-0 rounded-lg ring-1 shadow-sm ring-black/5 lg:rounded-r-[1rem] h-full">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="pointer-events-none absolute inset-0 rounded-lg ring-1 shadow-sm ring-black/5 lg:rounded-l-[1rem] h-full">
                    </div>
                </div>

                <div class="relative lg:row-span-2 min-h-[30rem] max-h-[50rem] flex flex-col">
                    <div
                        class="absolute inset-0 rounded-lg bg-white dark:bg-gray-900/30 dark-mode-transition border border-gray-200 dark:border-gray-700 lg:rounded-r-[1rem] h-full">
                    </div>
                    <div
                        class="relative flex flex-col h-full overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-r-[calc(2rem+1px)] flex-grow">

                        <div class="px-6 pt-8 pb-3 sm:px-10 sm:pt-8 sm:pb-2 flex flex-col h-full">
                            <p
                                class="text-xl font-semibold tracking-tight text-gray-950 dark:text-gray-100 text-center dark-mode-transition">
                                Jumlah
                                Mahasiswa yang melakukan presensi hari ini</p>
                            <div id="rekap-container">
                                @foreach ($rekapPresensi as $rekap)
                                    <div class="relative mt-5">
                                        <div
                                            class="absolute inset-px rounded-lg bg-white dark:bg-gray-900/40 dark-mode-transition border border-gray-200 dark:border-gray-700 ring-1 shadow-sm ring-black/5 dark:ring-white/10 lg:rounded-lg">
                                        </div>
                                        <a href="{{ route('detail.presensi', ['program_studi' => $rekap['program_studi']]) }}"
                                            class="relative flex flex-col overflow-hidden lg:rounded-2xl cursor-pointer">
                                            <div
                                                class="flex justify-between items-center px-4 py-3 h-[80px] md:h-0 md:py-6">
                                                <p
                                                    class="text-lg font-medium tracking-tight text-black dark:text-white dark-mode-transition">
                                                    {{ $rekap['program_studi'] }}
                                                </p>
                                                <div
                                                    class="flex justify-between items-center px-5 py-3 h-[80px] md:h-0 md:py-6">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="size-7 text-gray-600 dark:text-gray-100 dark-mode-transition"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd"
                                                            d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <p
                                                        class="text-lg text-gray-600 dark:text-gray-100 dark-mode-transition max-w-lg pr-2 md:pr-5">
                                                        {{ $rekap['sudah'] }}</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="size-7 text-red-600 dark-mode-transition"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd"
                                                            d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <p
                                                        class="text-lg text-gray-600 dark:text-gray-100 dark-mode-transition max-w-lg pr-2 md:pr-5">
                                                        {{ $rekap['belum'] }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <p
                                class="mt-6 text-lg font-medium tracking-tight text-gray-950 dark:text-gray-100 dark-mode-transition text-center">
                                Total
                                Keseluruhan Mahasiswa yang melakukan presensi hari ini</p>
                            <div
                                class="relative
                                mt-3 bg-white dark:bg-gray-900/40 dark-mode-transition border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-t-[calc(2rem+1px)]">
                                    <div class="px-8 p-3 sm:px-10 sm:p-3">
                                        <div class="flex justify-center space-x-4">
                                            <div id="total-sudah"
                                                class="text-sm text-gray-600 max-w-lg flex justify-between items-center space-x-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="size-10 text-gray-600 dark:text-gray-400 dark-mode-transition">
                                                    <path fill-rule="evenodd"
                                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p id="total-sudah-value"
                                                    class="text-xl text-gray-600 dark:text-gray-100 dark-mode-transition max-w-lg pr-5">
                                                    {{ $totalSudahPresensiSemua }}
                                                </p>

                                            </div>
                                            <div id="total-belum"
                                                class="text-sm text-gray-600 max-w-lg flex justify-between items-center space-x-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="size-10 text-red-600 dark:text-red-500 dark-mode-transition">
                                                    <path fill-rule="evenodd"
                                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p id="total-belum-value"
                                                    class="text-xl text-gray-600 dark:text-gray-100 dark-mode-transition max-w-lg">
                                                    {{ $totalBelumPresensiSemua }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/5 lg:rounded-[1rem]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/5 rounded-b-[1rem] lg:rounded-r-[1rem] lg:rounded-bl-lg">
                    </div>
                </div>
            </div>
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
        let lastDataLength = 0;

        function refreshPresensi() {
            fetch('/presensi/today')
                .then(response => response.json())
                .then(data => {
                    console.log('Data Presensi:', data);
                    const presensiList = document.getElementById('presensi-list');
                    if (!presensiList) return;

                    presensiList.innerHTML = ''; // Clear presensi list sebelum menambahkan data baru

                    if (data.length === 0) {
                        presensiList.innerHTML = `
                        <div class="flex justify-center items-center h-24 mt-25">
                            <p class="text-gray-500 dark:text-gray-100 dark-mode-transition italic text-center text-sm">
                                Belum ada yang melakukan presensi pada hari ini.
                            </p>
                        </div>
                    `;
                        return;
                    }

                    // Menambahkan data presensi ke dalam list
                    data.reverse().forEach((item, index) => {
                        const presensiItem = document.createElement('p');
                        presensiItem.classList.add('mt-1', 'max-w-lg', 'text-sm/6',
                            'text-gray-600', 'dark:text-gray-100', 'dark-mode-transition',
                            'lg:text-start');
                        presensiItem.innerHTML =
                            `${index + 1}. <strong>${item.user.name}</strong>, telah melakukan presensi pada mata kuliah
                    <strong>${item.mataKuliah.name}</strong> di <strong>${item.jadwalKuliah.ruangan.name}</strong>
                    pada <strong>${new Date(item.waktu_presensi).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false })}</strong>.`;

                        presensiList.appendChild(presensiItem);
                    });

                    // Auto-scroll ke bawah jika data baru ditambahkan
                    if (data.length > lastDataLength) {
                        presensiList.scrollTop = presensiList.scrollHeight;
                    }
                    lastDataLength = data.length;
                })
                .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk refresh rekap presensi
        function refreshRekapPresensi() {
            fetch('/rekap/presensi/json')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('rekap-container');
                    if (!container) return;

                    container.innerHTML = ''; // Clear rekap sebelum menambahkan data baru
                    data.rekapPresensi.forEach(item => {
                        container.innerHTML += `
                    <div class="relative mt-5">
                        <div class="absolute inset-px rounded-lg bg-white dark:bg-gray-900/40 dark-mode-transition border border-gray-200 dark:border-gray-700 ring-1 shadow-sm ring-black/5 dark:ring-white/10 lg:rounded-lg"></div>
                        <a href="/detail-presensi/${encodeURIComponent(item.program_studi)}" class="relative flex flex-col overflow-hidden lg:rounded-2xl cursor-pointer">
                                <div class="flex justify-between items-center px-5 py-3 h-[80px] md:h-0 md:py-6">
                                    <p class="text-lg font-medium tracking-tight text-black dark:text-gray-100 dark-mode-transition">
                                        ${item.program_studi}
                                    </p>

                                    <div class="flex items-center justify-between space-x-2 md:space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-gray-600 dark:text-gray-400 dark-mode-transition" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-lg text-gray-600 dark:text-gray-100 dark-mode-transition max-w-lg pr-2 md:pr-5">${item.sudah}</p>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-red-600 dark:text-red-500 dark-mode-transition" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-lg text-gray-600 dark:text-gray-100 dark-mode-transition max-w-lg pr-2 md:pr-5">${item.belum}</p>
                                    </div>
                                </div>
                        </a>
                    </div>
                `;
                    });

                    // Update total presensi
                    const totalSudahEl = document.getElementById('total-sudah-value');
                    const totalBelumEl = document.getElementById('total-belum-value');
                    if (totalSudahEl) totalSudahEl.textContent = data.totalSudah;
                    if (totalBelumEl) totalBelumEl.textContent = data.totalBelum;
                })
                .catch(err => console.error('Gagal ambil rekap:', err));
        }
        // Inisialisasi interval untuk refresh
        setInterval(refreshPresensi, 3000); // Refresh setiap 5 detik
        setInterval(refreshRekapPresensi, 3000); // Refresh setiap 5 detik

        refreshPresensi();
        refreshRekapPresensi();
    </script>
</x-layout>
