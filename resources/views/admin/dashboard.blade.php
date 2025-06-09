<x-layout hide-navbar is-admin>
    <div id="sidebar" x-data="{
        lockedOpen: localStorage.getItem('sidebarLockedOpen') === 'true' || false,
        isHovering: false,
        akademikOpen: localStorage.getItem('akademikOpen') === 'true' || {{ request()->routeIs('admin.semester.index', 'admin.prodi.index', 'admin.golongan.index', 'admin.dosen.index', 'admin.mahasiswa.index', 'admin.ruangan.index', 'admin.mata-kuliah.index', 'admin.jadwal-kuliah.index') ? 'true' : 'false' }},
        presensiOpen: localStorage.getItem('presensiOpen') === 'true' || {{ request()->routeIs('admin.alat-presensi.index', 'admin.rfid.index', 'admin.rekapitulasi.index') ? 'true' : 'false' }},

        get isEffectivelyExpanded() {
            return this.lockedOpen || this.isHovering;
        }
    }" x-init="$watch('lockedOpen', value => localStorage.setItem('sidebarLockedOpen', value));
    $watch('akademikOpen', value => localStorage.setItem('akademikOpen', value));
    $watch('presensiOpen', value => localStorage.setItem('presensiOpen', value));" x-cloak @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
        class="fixed top-0 left-0 h-screen bg-white/80 dark:bg-black backdrop-blur-lg text-slate-700 dark:text-slate-300 transition-all duration-300 ease-in-out border-r border-slate-200 dark:border-slate-800 flex flex-col z-30 overflow-x-hidden"
        :style="{ width: isEffectivelyExpanded ? '16rem' : '4rem', 'will-change': 'width' }">

        <div
            class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800 dark-mode-transition h-16 flex-shrink-0">
            <a href="/" x-show="isEffectivelyExpanded"
                x-transition:enter="transition ease-out duration-200 delay-100" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="text-xl font-bold text-slate-800 dark:text-slate-100 dark-mode-transition cursor-pointer tracking-tight">
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
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-2.5 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-home {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'text-slate-500 dark:text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-500' }} transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap group-hover:text-blue-500 dark:group-hover:text-blue-500 transition">Dashboard</span>
                    </a>
                </li>

                @php
                    $isAkademikActive = request()->routeIs(
                        'admin.semester.index',
                        'admin.prodi.index',
                        'admin.golongan.index',
                        'admin.dosen.index',
                        'admin.mahasiswa.index',
                        'admin.ruangan.index',
                        'admin.mata-kuliah.index',
                        'admin.jadwal-kuliah.index',
                    );
                @endphp
                <li>
                    <button @click="akademikOpen = !akademikOpen"
                        class="flex items-center justify-between w-full px-3 py-2.5 transition-colors duration-200 rounded-lg group cursor-pointer {{ $isAkademikActive ? 'bg-slate-100 dark:bg-slate-800' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-university {{ $isAkademikActive ? 'text-blue-500' : 'text-slate-500 dark:text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-500' }} transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap group-hover:text-blue-500 dark:group-hover:text-blue-500 transition">Manajemen
                            Akademik</span>
                        <i x-show="isEffectivelyExpanded" :class="akademikOpen ? 'rotate-180' : ''"
                            class="fas fa-chevron-down text-slate-500 transition-transform duration-300 ease-in-out text-xs ml-auto"></i>
                    </button>
                    <div x-show="akademikOpen && isEffectivelyExpanded" x-collapse class="pl-6 pt-1">
                        <ul class="space-y-1 border-l border-slate-200 dark:border-slate-700">
                            <li><a href="{{ route('admin.dosen.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.dosen.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-chalkboard-teacher w-5 text-xs"></i><span
                                        class="ml-3 text-sm">Dosen</span></a></li>
                            <li><a href="{{ route('admin.mahasiswa.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.mahasiswa.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-user-graduate w-5 text-xs"></i><span
                                        class="ml-3 text-sm">Mahasiswa</span></a></li>
                            <li><a href="{{ route('admin.semester.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.semester.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-graduation-cap w-5 text-xs"></i><span
                                        class="ml-3 text-sm">Semester</span></a></li>
                            <li><a href="{{ route('admin.prodi.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.prodi.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-building w-5 text-xs"></i><span class="ml-3 text-sm">Program
                                        Studi</span></a></li>
                            <li><a href="{{ route('admin.golongan.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.golongan.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-layer-group w-5 text-xs"></i><span
                                        class="ml-3 text-sm">Golongan</span></a></li>
                            <li><a href="{{ route('admin.ruangan.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.ruangan.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-door-open w-5 text-xs"></i><span
                                        class="ml-3 text-sm">Ruangan</span></a></li>
                            <li><a href="{{ route('admin.mata-kuliah.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.mata-kuliah.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-book-open w-5 text-xs"></i><span class="ml-3 text-sm">Mata
                                        Kuliah</span></a></li>
                            <li><a href="{{ route('admin.jadwal-kuliah.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.jadwal-kuliah.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-calendar-alt w-5 text-xs"></i><span class="ml-3 text-sm">Jadwal
                                        Kuliah</span></a></li>
                        </ul>
                    </div>
                </li>

                @php
                    $isPresensiActive = request()->routeIs(
                        'admin.alat-presensi.index',
                        'admin.rfid.index',
                        'admin.rekapitulasi.index',
                    );
                @endphp
                <li>
                    <button @click="presensiOpen = !presensiOpen"
                        class="flex items-center justify-between w-full px-3 py-2.5 transition-colors duration-200 rounded-lg group cursor-pointer {{ $isPresensiActive ? 'bg-slate-100 dark:bg-slate-800' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-clipboard-list {{ $isPresensiActive ? 'text-blue-500' : 'text-slate-500 dark:text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-500' }} transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap group-hover:text-blue-500 dark:group-hover:text-blue-500 transition">Manajemen
                            Presensi</span>
                        <i x-show="isEffectivelyExpanded" :class="presensiOpen ? 'rotate-180' : ''"
                            class="fas fa-chevron-down text-slate-500 transition-transform duration-300 ease-in-out text-xs ml-auto"></i>
                    </button>
                    <div x-show="presensiOpen && isEffectivelyExpanded" x-collapse class="pl-6 pt-1">
                        <ul class="space-y-1 border-l border-slate-200 dark:border-slate-700">
                            <li><a href="{{ route('admin.alat-presensi.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.alat-presensi.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-tools w-5 text-xs"></i><span class="ml-3 text-sm">Alat
                                        Presensi</span></a></li>
                            <li><a href="{{ route('admin.rfid.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.rfid.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-credit-card w-5 text-xs"></i><span class="ml-3 text-sm">Registrasi
                                        RFID</span></a></li>
                            <li><a href="{{ route('admin.rekapitulasi.index') }}"
                                    class="flex items-center px-3 py-2 ml-2 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.rekapitulasi.index') ? 'text-blue-500' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500 dark:hover:text-blue-500' }}"><i
                                        class="fas fa-chart-line w-5 text-xs"></i><span
                                        class="ml-3 text-sm">Rekapitulasi Kehadiran</span></a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center px-3 py-2.5 transition-colors duration-200 rounded-lg group {{ request()->routeIs('admin.laporan.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-comment-dots {{ request()->routeIs('admin.laporan.index') ? 'text-blue-500' : 'text-slate-500 dark:text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-500' }} transition-colors duration-200"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            class="ml-3 text-sm font-medium whitespace-nowrap group-hover:text-blue-500 dark:group-hover:text-blue-500 transition">Laporan
                            Mahasiswa</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto border-t border-slate-200 dark:border-slate-800 p-2">
            <div>
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
            </div>
        </div>
    </div>

    <div id="main" x-data="{ isSidebarLockedOpen: localStorage.getItem('sidebarLockedOpen') === 'true' || false }" x-cloak
        @sidebar-toggle.window="isSidebarLockedOpen = $event.detail.locked"
        class="min-h-screen bg-slate-50 dark:bg-gray-900/40 z-20 transition-all duration-300 ease-in-out"
        :style="{ 'padding-left': isSidebarLockedOpen ? '16rem' : '4rem' }">
        @yield('admin-content')
    </div>
</x-layout>
