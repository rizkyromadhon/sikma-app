@php
    $user = Auth::user();
@endphp

<div x-data="{ open: false }" :class="open ? 'overflow-hidden' : ''" @toggle-sidebar.window="open = $event.detail.open"
    @keydown.escape.window="open = false">
    <nav class="fixed top-0 left-0 w-full z-50">
        @if ($isOldPassword)
            <div id="headerOldPassword"
                class="bg-red-100 dark:bg-red-800/30 dark-mode-transition border border-red-300 dark:border-red-600 text-red-800 dark:text-red-200 px-4 py-2 text-center flex items-center justify-center relative space-x-4 md:space-x-0">
                <div class="flex items-center">
                    <p class="font-normal text-sm">Anda masih menggunakan password lama. Demi keamanan
                        silahkan
                        ganti
                        password <a href="{{ route('ganti-password') }}" class="font-bold underline">disini.</a></p>
                </div>

                <button id="btnClosePassword"
                    class="absolute right-4 md:right-6 top-1/2 -translate-y-1/2 cursor-pointer">
                    <i class="fa-regular fa-circle-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Navbar -->
        <div class="shadow-md w-full bg-white dark:bg-black dark-mode-transition dark:border-b dark:border-gray-700 z-50 h-16 flex items-center justify-between p-4 sm:p-6 lg:px-8"
            aria-label="Global">
            <div class="flex flex-1">
                <a href="/"
                    class="-m-1.5 p-1.5 text-lg sm:text-xl font-bold text-gray-700 dark:text-gray-100 dark-mode-transition tracking-tight hover:text-gray-900 dark:hover:text-gray-300">'SIKMA'</a>
            </div>

            <!-- Tombol Hamburger -->
            <div class="flex lg:hidden">
                <button type="button" @click="open = !open" x-cloak
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-100 dark-mode-transition">
                    <span class="sr-only">Open main menu</span>

                    <!-- Ikon Hamburger -->
                    <svg x-show="!open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>

                    <!-- Ikon Close -->
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden lg:flex lg:gap-x-6 xl:gap-x-12">
                <a href="/"
                    class="text-md font-semibold text-gray-900 dark:text-gray-100 dark-mode-transition hover:text-gray-700 dark:hover:text-gray-300 transition-all ease-in-out duration-100">Home</a>
                <a href="{{ route('jadwal-kuliah.index') }}"
                    class="text-md font-semibold text-gray-900 dark:text-gray-100 dark-mode-transition hover:text-gray-700 dark:hover:text-gray-300 transition-all ease-in-out duration-100">Jadwal
                    Kuliah</a>
                <a href="/presensi-kuliah?bulan={{ now()->month }}&minggu={{ now()->weekOfMonth }}"
                    class="text-md font-semibold text-gray-900 dark:text-gray-100 dark-mode-transition hover:text-gray-700 dark:hover:text-gray-300 transition-all ease-in-out duration-100">Presensi
                    Kuliah</a>
                <a href="{{ route('pusat-bantuan') }}"
                    class="text-md font-semibold text-gray-900 dark:text-gray-100 dark-mode-transition hover:text-gray-700 dark:hover:text-gray-300 transition-all ease-in-out duration-100">Pusat
                    Bantuan</a>
            </div>

            <!-- User - Desktop -->
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                @auth
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 xl:space-x-3 focus:outline-none transition-all ease-in-out duration-100 transform hover:scale-105 cursor-pointer">
                                @if (Auth::user()->foto && Auth::user()->role == 'dosen')
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil"
                                        class="w-8 h-8 mt-3.5 rounded-full object-cover mb-4 ring-4 ring-white dark:ring-slate-700">
                                @elseif (Auth::user()->foto && Auth::user()->role == 'mahasiswa')
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Profil"
                                        class="w-8 h-8 mt-3.5 rounded-full object-cover mb-4 ring ring-white dark:ring-slate-700">
                                @elseif (!Auth::user()->foto && Auth::user()->role == 'dosen')
                                    <div
                                        class="w-8 h-8 mt-3.5 rounded-full flex items-end bg-gray-200 dark:bg-gray-300 justify-center mb-4 ring ring-white dark:ring-slate-700 overflow-hidden">
                                        <i class="fas fa-user-tie text-3xl text-gray-500 dark:text-gray-500"></i>
                                    </div>
                                @elseif (!Auth::user()->foto && Auth::user()->role == 'mahasiswa')
                                    <div
                                        class="w-8 h-8 mt-3.5 rounded-full flex items-end bg-gray-200 dark:bg-gray-300 justify-center mb-4 ring ring-white dark:ring-slate-700 overflow-hidden">
                                        <i class="fas fa-user text-3xl text-gray-500 dark:text-gray-500"></i>
                                    </div>
                                @else
                                    <div
                                        class="w-8 h-8 mt-3.5 rounded-full flex items-end bg-gray-200 dark:bg-gray-300 justify-center mb-4 ring ring-white dark:ring-slate-700 overflow-hidden">
                                        <i class="fas fa-user text-3xl text-gray-500 dark:text-gray-500"></i>
                                    </div>
                                @endif

                                {{-- <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/user.png') }}"
                                    alt="Foto Profil" class="w-7 h-7 xl:w-8 xl:h-8 rounded-full object-cover"> --}}

                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 dark-mode-transition">
                                    {{ Auth::user()->role == 'dosen' ? Auth::user()->nip : Auth::user()->nim }}
                                </span>

                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-100 dark-mode-transition" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 9l-7 7-7-7" />
                                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"
                                        style="display: none;" />
                                </svg>

                                @if ($isProfileCompleted == '0')
                                    <div class="px-1 py-1 rounded-full bg-red-500 dark:bg-red-600/80 dark-mode-transition">

                                    </div>
                                @endif
                            </button>

                            <div x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-42 origin-top-right rounded-md bg-white dark:bg-gray-900/30 dark-mode-transition backdrop-blur-sm shadow-lg ring-1 ring-gray-300 dark:ring-gray-600 ring-opacity-5 focus:outline-none z-50"
                                style="display: none;">
                                <div class="py-1">
                                    <a href="/profile"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark-mode-transition dark:hover:bg-gray-900/90 transition">Profil
                                        @if (Auth::check() && !$isProfileCompleted)
                                            <span
                                                class="inline-block w-2 h-2 bg-red-500 dark:bg-red-600/80 rounded-full absolute right-4 mt-1.5"></span>
                                        @endif
                                    </a>

                                    @if (Auth::check() && Auth::user()->role == 'mahasiswa')
                                        <a href="{{ route('mahasiswa.pesan') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900/90 dark-mode-transition transition">Pesan</a>
                                        <a href="{{ route('mahasiswa.izin.index') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900/90 dark-mode-transition transition">Pengajuan
                                            Izin</a>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900/90 dark-mode-transition transition">
                                            Dashboard
                                        </a>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role === 'dosen')
                                        <a href="{{ route('dosen.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900/90 dark-mode-transition transition">
                                            Dashboard
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="button" @click="$dispatch('open-logout-modal')"
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-900/90 dark-mode-transition transition cursor-pointer">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login"
                        class="text-md font-semibold text-gray-900 dark:text-gray-100 dark-mode-transition hover:text-gray-700 dark:hover:text-gray-300 transition-all ease-in-out duration-100">
                        Login <span aria-hidden="true">&rarr;</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar (Terpisah dari navbar) -->
    <div>
        <!-- Sidebar Overlay -->
        <div x-show="open" class="fixed inset-0 bg-black/50 dark:bg-black/70 z-[9999]" @click="open = false"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak></div>

        <!-- Sidebar Content -->
        <div x-show="open"
            class="fixed inset-y-0 right-0 w-full max-w-xs sm:max-w-sm bg-white dark:bg-black/80 dark:backdrop-blur-sm shadow-xl z-[9999] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" x-cloak>

            <div class="flex items-center justify-between py-6 px-4 border-b border-gray-200 dark:border-gray-700">
                <a href="/" class="text-xl font-bold text-gray-800 dark:text-gray-100">'SIKMA'</a>
                <button @click="open = false" class="rounded-md p-2 text-gray-700 dark:text-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-4 py-6">
                <div class="space-y-1">
                    <a href="/" x-on:click="loading = true; open = false"
                        class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Home</a>
                    <a href="{{ route('jadwal-kuliah.index') }}" x-on:click="loading = true; open = false"
                        class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Jadwal
                        Kuliah</a>
                    <a href="/presensi-kuliah?bulan={{ now()->month }}&minggu={{ now()->weekOfMonth }}"
                        x-on:click="loading = true; open = false"
                        class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Presensi
                        Kuliah</a>
                    <a href="{{ route('pusat-bantuan') }}" x-on:click="loading = true; open = false"
                        class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Pusat
                        Bantuan</a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    @auth
                        <div class="flex space-x-3 px-3 py-3 mb-2 rounded-md bg-gray-100 dark:bg-black/70">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/user.png') }}"
                                alt="Foto Profil" class="w-10 h-10 rounded-full object-cover">
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</span>
                                <span
                                    class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->nim }}</span>
                            </div>


                        </div>

                        <a href="/profile" x-on:click="loading = true; open = false"
                            class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Profil
                            @if (Auth::check() && !$isProfileCompleted)
                                <span
                                    class="inline-block w-2 h-2 bg-red-500 dark:bg-red-600/80 rounded-full absolute right-6 mt-1.5"></span>
                            @endif
                        </a>
                        @if (Auth::user()->role == 'mahasiswa')
                            <a href="{{ route('mahasiswa.pesan') }}" x-on:click="loading = true; open = false"
                                class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Pesan</a>
                        @endif
                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" x-on:click="loading = true; open = false"
                                class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Dashboard</a>
                        @endif
                        @if (Auth::check() && Auth::user()->role === 'dosen')
                            <a href="{{ route('dosen.dashboard') }}"
                                class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="button" @click="$dispatch('open-logout-modal'); open = false"
                                class="w-full text-left px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Logout</button>
                        </form>
                    @else
                        <a href="/login" x-on:click="loading = true; open = false"
                            class="block px-3 py-2 text-base font-semibold text-gray-900 dark:text-gray-100 rounded-md">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<script>
    // Script untuk menutup peringatan password lama
    document.addEventListener('DOMContentLoaded', function() {
        const headerOldPassword = document.getElementById('headerOldPassword');
        const btnClosePassword = document.getElementById('btnClosePassword');

        if (btnClosePassword && headerOldPassword) {
            btnClosePassword.addEventListener('click', () => {
                headerOldPassword.classList.add('hidden');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const headerProfileCompleted = document.getElementById('headerProfileCompleted');
        const btnCloseProfile = document.getElementById('btnCloseProfile');

        if (btnCloseProfile && headerProfileCompleted) {
            btnCloseProfile.addEventListener('click', () => {
                headerProfileCompleted.classList.add('hidden');
            });
        }
    });
</script>
