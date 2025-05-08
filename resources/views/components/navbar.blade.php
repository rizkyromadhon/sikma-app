@php
    $user = Auth::user();
@endphp

<nav class="sticky top-0 left-0 w-full">
    @if ($isOldPassword)
        <div id="headerOldPassword"
            class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md shadow-lg text-center flex items-center justify-center relative">
            <div class="flex items-center">
                <p class="font-normal text-sm">Anda masih menggunakan password lama. Demi keamanan silahkan ganti
                    password <a href="{{ route('ganti-password') }}" class="font-bold underline">disini.</a></p>
            </div>

            <button id="btnClosePassword" class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer">
                <i class="fa-regular fa-circle-xmark"></i>
            </button>
        </div>
    @endif
    <div class="lg:shadow-md w-full bg-white lg:backdrop-blur-md z-50 h-16 flex items-center justify-between p-6 lg:px-8"
        aria-label="Global" x-data="{ isOpen: false, dropdownOpen: false }">
        <div class="flex lg:flex-1">
            <a href="/" x-on:click="loading = true"
                class="-m-1.5 p-1.5 text-xl font-bold text-gray-800 tracking-tight hover:shadow-2xl hover:text-white hover:bg-gray-900 hover:transition-all hover:ease-in-out hover:duration-200 transition-all ease-in-out duration-400 rounded">'SIKMA'</a>
        </div>

        <!-- Tombol Hamburger -->
        <div class="flex lg:hidden">
            <button type="button" @click="isOpen = !isOpen"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>

                <!-- Ikon Hamburger -->
                <svg x-show="!isOpen" x-transition class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

                <!-- Ikon Close -->
                <svg x-show="isOpen" x-transition class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Desktop -->
        <div class="hidden lg:flex lg:gap-x-12">
            <a href="/" x-on:click="loading = true"
                class="underline-animation text-md font-semibold text-gray-900 transition-all ease-in-out duration-100 transform hover:scale-110">Home</a>
            <a href="{{ route('jadwal-kuliah.index') }}"
                class="underline-animation text-md font-semibold text-gray-900 transition-all ease-in-out duration-100 transform hover:scale-110">Jadwal
                Kuliah</a>
            <a href="/presensi-kuliah?bulan={{ now()->month }}&minggu={{ now()->weekOfMonth }}"
                class="underline-animation text-md font-semibold text-gray-900 transition-all ease-in-out duration-100 transform hover:scale-110">Presensi
                Kuliah</a>
        </div>

        <!-- User -->
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            @auth
                <div class="hidden lg:flex lg:flex-1 lg:justify-end" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-3 focus:outline-none transition-all ease-in-out duration-100 transform hover:scale-105 cursor-pointer">
                            <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/user.png') }}"
                                alt="Foto Profil" class="w-8 h-8 rounded-full object-cover">

                            <span class="text-sm font-semibold text-gray-800">{{ Auth::user()->nim }}</span>

                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-3 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-gray-300 ring-opacity-5 focus:outline-none z-50"
                            style="display: none;">
                            <div class="py-1">
                                <a href="/profile" x-on:click="loading = true"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Profil</a>
                                @if (Auth::check() && Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" x-on:click="loading = true"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Dashboard
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="button" @click="$dispatch('open-logout-modal')"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition cursor-pointer">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <a href="/login" x-on:click="loading = true"
                    class="underline-animation text-md font-semibold text-gray-900 transition-all ease-in-out duration-100 transform hover:scale-110">
                    Login <span aria-hidden="true">&rarr;</span>
                </a>
            @endauth
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" x-show="isOpen"
            class="lg:hidden fixed inset-y-0 right-0 z-50 w-full max-w-sm bg-white px-6 py-6 shadow-lg transform transition-all"
            x-transition:enter="duration-300 ease-out" x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="duration-200 ease-in"
            x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0">
            <div class="flex items-center justify-between">
                <a href="/" x-on:click="loading = true"
                    class="m-1.5 p-1.5 text-xl font-bold text-gray-800 tracking-tight">SIKMA</a>
                <button type="button" @click="isOpen = false" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6">
                <div class="space-y-2 py-4">
                    <a href="/" x-on:click="loading = true"
                        class="block px-3 py-2 text-base font-semibold text-gray-900">Home</a>
                    <a href="/jadwal-kelas" x-on:click="loading = true"
                        class="block px-3 py-2 text-base font-semibold text-gray-900">Jadwal Kelas</a>
                    <a href="/presensi-kuliah" x-on:click="loading = true"
                        class="block px-3 py-2 text-base font-semibold text-gray-900">Presensi
                        Kuliah</a>
                    <a href="/tentang-kami" x-on:click="loading = true"
                        class="block px-3 py-2 text-base font-semibold text-gray-900">Tentang Kami</a>
                </div>
                <div class="py-4 border-t border-gray-200">
                    @auth
                        <a href="/profile" class="block px-3 py-2 text-base font-semibold text-gray-900">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 py-2 text-base font-semibold text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="block px-3 py-2 text-base font-semibold text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

</nav>
<script>
    const headerOldPassword = document.getElementById('headerOldPassword');
    const btnClosePassword = document.getElementById('btnClosePassword');

    btnClosePassword.addEventListener('click', () => {
        headerOldPassword.classList.add('hidden');
    });
</script>
