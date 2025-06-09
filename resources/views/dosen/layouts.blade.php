<x-layout hide-navbar is-dosen>
    <div id="sidebar" x-data="{
        lockedOpen: localStorage.getItem('sidebarLockedOpen') === 'true' || false,
        isHovering: false,
        jadwalOpen: localStorage.getItem('jadwalOpen') === 'true' || false,
        presensiOpen: localStorage.getItem('presensiOpen') === 'true' || false,
    
        get isEffectivelyExpanded() {
            return this.lockedOpen || this.isHovering;
        }
    }" x-init="$watch('lockedOpen', value => localStorage.setItem('sidebarLockedOpen', value));
    $watch('jadwalOpen', value => localStorage.setItem('jadwalOpen', value));
    $watch('presensiOpen', value => localStorage.setItem('presensiOpen', value));" x-cloak @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
        class="fixed top-0 left-0 h-screen bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg text-slate-700 dark:text-slate-300 transition-all duration-300 ease-in-out border-r border-slate-200 dark:border-slate-800 flex flex-col z-30 overflow-x-hidden"
        :style="{ width: isEffectivelyExpanded ? '16rem' : '4rem', 'will-change': 'width' }">

        <div
            class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800 dark-mode-transition h-16 flex-shrink-0">
            <a href="/" x-show="isEffectivelyExpanded"
                x-transition:enter="transition ease-out duration-200 delay-100" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="text-lg font-bold text-slate-800 dark:text-slate-100 dark-mode-transition cursor-pointer tracking-tight">
                'SIKMA'
            </a>
            <button @click="lockedOpen = !lockedOpen; $dispatch('sidebar-toggle', { locked: lockedOpen })"
                class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none text-slate-500 dark:text-slate-400 dark-mode-transition cursor-pointer transition-all duration-200 flex items-center justify-center"
                :class="!isEffectivelyExpanded ? 'mx-auto' : ''">
                <span class="relative w-4 h-4 flex items-center justify-center">
                    <i class="fas fa-bars absolute inset-0" x-cloak x-show="lockedOpen" x-transition.opacity.150ms></i>
                    <i class="fas fa-thumbtack absolute inset-0" x-cloak x-show="!lockedOpen && isEffectivelyExpanded"
                        x-transition.opacity.150ms></i>
                    <i class="fas fa-chevron-right absolute inset-0" x-cloak
                        x-show="!lockedOpen && !isEffectivelyExpanded" x-transition.opacity.150ms></i>
                </span>
            </button>
        </div>

        <nav
            class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-700 scrollbar-track-transparent">
            <ul class="space-y-1 px-2 py-4">
                <li>
                    <a href="{{ route('dosen.dashboard') }}"
                        class="flex items-center px-3 py-2.5 transition-colors duration-200 {{ request()->routeIs('dosen.dashboard') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}  rounded-lg group">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-home text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dosen.profile') }}"
                        class="flex items-center px-3 py-2.5 transition-colors duration-200 {{ request()->routeIs('dosen.profile')
                            ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white'
                            : 'hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-lg group">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-user text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded" class="ml-3 text-sm font-medium whitespace-nowrap">Profil
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dosen.jadwal.index') }}"
                        class="flex items-center px-3 py-2.5 transition-colors duration-200 {{ request()->routeIs('dosen.jadwal.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}  rounded-lg group">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-calendar-alt text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded" class="ml-3 text-sm font-medium whitespace-nowrap">Jadwal
                            Kuliah
                        </span>
                    </a>
                </li>

                <li>
                    <button @click="presensiOpen = !presensiOpen"
                        class="flex items-center w-full px-3 py-2.5 transition-colors duration-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg group cursor-pointer">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-clipboard-check text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap flex-1 text-left">Presensi</span>
                        <i x-show="isEffectivelyExpanded" :class="presensiOpen ? 'rotate-180' : ''"
                            class="fas fa-chevron-down text-slate-500 transition-transform duration-300 ease-in-out text-xs"></i>
                    </button>
                    <div x-show="presensiOpen && isEffectivelyExpanded" x-collapse class="pl-6 pt-1">
                        <ul class="space-y-1 border-l border-slate-200 dark:border-slate-700">
                            <li>
                                <a href="{{ route('dosen.presensi.index') }}"
                                    class="flex items-center px-3 py-2.5 ml-2 transition-colors duration-200 {{ request()->routeIs('dosen.presensi.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-lg group">
                                    <i
                                        class="fas fa-user-check w-5 text-slate-400 group-hover:text-blue-500 transition-colors duration-200 text-xs"></i>
                                    <span class="ml-3 text-sm">Kelola Presensi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dosen.izin.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 {{ request()->routeIs('dosen.izin.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-lg group">
                                    <i
                                        class="fas fa-file-medical w-5 text-slate-400 group-hover:text-blue-500 transition-colors duration-200 text-xs"></i>
                                    <span class="ml-3 text-sm">Pengajuan Izin/Sakit</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{ route('dosen.pengumuman.index') }}"
                        class="flex items-center px-3 py-2.5 transition-colors duration-200 {{ request()->routeIs('dosen.pengumuman.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-lg group">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-bullhorn text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap">Pengumuman</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto border-t border-slate-200 dark:border-slate-800 p-2">
            <div x-data="{ showLogoutConfirm: false }">
                <div
                    class="flex items-center justify-between p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="fas fa-user-circle text-2xl text-slate-500 dark:text-slate-400 flex-shrink-0"></i>
                        <div x-show="isEffectivelyExpanded" class="ml-3 min-w-0" x-transition.opacity.duration.200ms>
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">
                                {{ auth()->user()->name }}</p>
                        </div>
                    </div>
                    <button @click="$dispatch('open-logout-modal')" type="button" x-show="isEffectivelyExpanded"
                        class="p-2 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-red-500 dark:hover:text-red-500 transition-colors duration-200"
                        x-transition.opacity.duration.200ms>
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </div>
                <template x-teleport="body">
                    <div x-show="showLogoutConfirm" class="fixed inset-0 z-[9999] overflow-y-auto"
                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div
                            class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showLogoutConfirm" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/75 transition-opacity">
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div x-show="showLogoutConfirm" x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-100"
                                                id="modal-title">Konfirmasi Logout</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-slate-500 dark:text-slate-400">Apakah Anda yakin
                                                    ingin keluar? Anda akan dialihkan ke halaman login.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-slate-50 dark:bg-slate-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button" @click="window.location.href = '/login'"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">Ya,
                                        Logout</button>
                                    <button type="button" @click="showLogoutConfirm = false"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-700 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main" x-data="{ isSidebarLockedOpen: localStorage.getItem('sidebarLockedOpen') === 'true' || false }" x-cloak
        @sidebar-toggle.window="isSidebarLockedOpen = $event.detail.locked"
        class="bg-gray-50 dark:bg-slate-900/80 min-h-screen z-20 dark-mode-transition"
        :style="{
            'padding-left': isSidebarLockedOpen ? '16rem' : '4rem',
            'transition': 'padding-left 0.3s ease-in-out'
        }">
        @if (Auth::check() && Illuminate\Support\Facades\Hash::check('passworddosen', Auth::user()->password))
            <div x-data="{ showWarning: true }" x-show="showWarning" x-transition
                class="bg-red-700/95 backdrop-blur-sm text-white sticky top-0 z-20">

                {{-- [DIUBAH] Kontainer utama sekarang menjadi flexbox --}}
                <div class="flex items-center justify-between px-4 py-1">

                    {{-- Konten Kiri (Ikon & Teks) --}}
                    <div class="flex items-center">
                        <span class="flex p-2 rounded-lg bg-red-800/80">
                            <i class="fas fa-shield-virus"></i>
                        </span>
                        <p class="ml-3 font-medium text-sm">
                            <span>Anda masih menggunakan password default. Demi keamanan, silakan ganti password </span>
                            <a href="{{ route('dosen.password.edit') }}"
                                class="font-bold underline hover:text-red-100 transition whitespace-nowrap">
                                disini.
                            </a>
                        </p>
                    </div>

                    {{-- Tombol Tutup Kanan --}}
                    <div class="flex-shrink-0">
                        <button @click="showWarning = false" type="button"
                            class="flex p-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="sr-only">Tutup</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        @yield('dosen-content')
    </div>

    {{-- <div id="main" x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true' || false,
        sidebarLocked: localStorage.getItem('sidebarLockedOpen') === 'true' || false
    }" x-init="$watch('darkMode', value => {
        localStorage.setItem('darkMode', value);
        document.documentElement.classList.toggle('dark', value);
    });
    document.documentElement.classList.toggle('dark', darkMode);
    $el.style.paddingLeft = sidebarLocked ? '16rem' : '4rem';
    $el.addEventListener('sidebar-toggle', (e) => {
        sidebarLocked = e.detail.locked;
        $el.style.paddingLeft = e.detail.locked ? '16rem' : '4rem';
    });"
        class="transition-all duration-300 ease-in-out min-h-screen bg-slate-50 dark:bg-slate-900">


    </div> --}}

    <style>
        /* Custom scrollbar for webkit browsers */
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .scrollbar-track-transparent::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            /* slate-300 */
            border-radius: 10px;
        }

        .dark .scrollbar-thumb-slate-700::-webkit-scrollbar-thumb {
            background: #334155;
            /* slate-700 */
        }

        .scrollbar-thumb-slate-300::-webkit-scrollbar-thumb:hover,
        .dark .scrollbar-thumb-slate-700::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
            /* slate-400 */
        }

        .dark .scrollbar-thumb-slate-700::-webkit-scrollbar-thumb:hover {
            background: #475569;
            /* slate-600 */
        }

        /* Anti-aliasing for smoother fonts */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Will-change for performance */
        #main,
        #sidebar {
            will-change: padding-left, width;
        }

        /* Alpine.js x-cloak helper */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        // Alpine is already initialized in the HTML, this script block can be minimal.
        document.addEventListener('alpine:init', () => {
            // You can add global Alpine functions or data here if needed.
        });
    </script>
</x-layout>
