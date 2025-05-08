<x-layout :isOldPassword="$isOldPassword">
    <div id="home-page" class="bg-gray-50 py-2 sm:py-4 rounded-md" x-data
        x-bind:class="{ 'overflow-hidden': $store.loading.value }">

        <div x-show="$store.loading.value" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-700/70 flex items-center justify-center z-[9999] h-screen">
            <div class="w-12 h-12 border-6 border-gray-800 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <div class="mx-auto">
            <p
                class="mx-auto text-center mt-6 lg:mt-0 text-3xl font-semibold tracking-tight text-balance text-gray-950 lg:text-4xl lg:w-120">
                Sudahkah anda presensi hari ini?</p>
            <div class="mt-10 grid gap-4 sm:mt-12 grid-cols-1 lg:grid-cols-2">
                <div class="relative lg:row-span-2 min-h-[30rem] max-h-[50rem] flex flex-col">
                    <div class="absolute inset-0 rounded-lg bg-white lg:rounded-l-[2rem] h-full"></div>
                    <div
                        class="relative flex flex-col h-full overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)] flex-grow">
                        <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-8 sm:pb-2 flex flex-col h-full">
                            <p class="text-xl font-semibold  tracking-tight text-gray-950 text-center">
                                Mahasiswa yang melakukan presensi
                            </p>
                            <div class="relative mt-5 mb-5 flex-1">
                                <div class="absolute inset-0 rounded-lg bg-white lg:rounded-t-[1rem] h-full"></div>
                                <div
                                    class="relative flex flex-col h-full overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-t-[calc(1rem+1px)] flex-grow">
                                    <div id="presensi-list"
                                        class="px-4 p-3 sm:px-6 sm:p-5 flex-grow overflow-y-auto h-30 max-h-xl">
                                        <div class="flex justify-center items-center h-24 mt-25">
                                            <p class="text-gray-500 italic text-center">
                                                Belum ada yang melakukan presensi pada hari ini.
                                            </p>
                                        </div>
                                    </div>

                                </div>
                                <div
                                    class="pointer-events-none absolute inset-0 rounded-lg ring-1 shadow-sm ring-black/5 lg:rounded-[1rem] h-full">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="pointer-events-none absolute inset-0 rounded-lg ring-1 shadow-sm ring-black/5 lg:rounded-l-[2rem] h-full">
                    </div>
                </div>

                <div class="relative lg:row-span-2 min-h-[30rem] max-h-[50rem] flex flex-col">
                    <div class="absolute inset-0 rounded-lg bg-white h-full"></div>
                    <div
                        class="relative flex flex-col h-full overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-r-[calc(2rem+1px)] flex-grow">

                        <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-8 sm:pb-2 flex flex-col h-full">
                            <p class="text-xl font-semibold tracking-tight text-gray-950 text-center">Jumlah
                                Mahasiswa yang melakukan presensi hari ini</p>
                            <div id="rekap-container">
                                @foreach ($rekapPresensi as $rekap)
                                    <div class="relative mt-5">
                                        <div
                                            class="absolute inset-px rounded-lg bg-white ring-1 shadow-sm ring-black/5 lg:rounded-lg">
                                        </div>
                                        <a href="{{ route('detail.presensi', ['program_studi' => $rekap['program_studi']]) }}"
                                            class="relative flex flex-col overflow-hidden lg:rounded-2xl cursor-pointer">
                                            <div class="px-6 p-3 lg:px-6 sm:p-3">
                                                <div class="flex justify-between">
                                                    <p class="text-lg font-medium tracking-tight text-black">
                                                        {{ $rekap['program_studi'] }}
                                                        <!-- Pastikan program_studi adalah nama program studi -->
                                                    </p>
                                                    <div class="flex justify-between space-x-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-7 text-gray-600" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd"
                                                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <p class="text-lg text-gray-600 max-w-lg pr-5">
                                                            {{ $rekap['sudah'] }}</p>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="size-7 text-red-600" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd"
                                                                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <p class="text-lg text-gray-600 max-w-lg pr-5">
                                                            {{ $rekap['belum'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <p class="mt-6 text-lg font-medium tracking-tight text-gray-950 text-center">
                                Total
                                Keseluruhan Mahasiswa yang melakukan presensi hari ini</p>
                            <div class="relative
                                mt-3">
                                <div
                                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-t-[calc(2rem+1px)]">
                                    <div class="px-8 p-3 sm:px-10 sm:p-3">
                                        <div class="flex justify-center space-x-4">
                                            <div id="total-sudah"
                                                class="text-sm text-gray-600 max-w-lg flex justify-between items-center space-x-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-10 text-gray-600">
                                                    <path fill-rule="evenodd"
                                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p id="total-sudah-value" class="text-xl text-gray-600 max-w-lg pr-5">
                                                    {{ $totalSudahPresensiSemua }}
                                                </p>

                                            </div>
                                            <div id="total-belum"
                                                class="text-sm text-gray-600 max-w-lg flex justify-between items-center space-x-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="currentColor" class="size-10 text-red-600">
                                                    <path fill-rule="evenodd"
                                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <p id="total-belum-value" class="text-xl text-gray-600 max-w-lg">
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
</x-layout>
