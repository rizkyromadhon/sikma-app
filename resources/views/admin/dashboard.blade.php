<x-layout hide-navbar is-admin>
    <!-- Sidebar -->
    <div id="sidebar" x-data="{
        lockedOpen: localStorage.getItem('sidebarLockedOpen') === 'true' || false,
        isHovering: false,
        akademikOpen: localStorage.getItem('akademikOpen') === 'true' || {{ request()->routeIs('admin.semester.index') || request()->routeIs('admin.prodi.index') || request()->routeIs('admin.golongan.index') || request()->routeIs('admin.dosen.index') || request()->routeIs('admin.mahasiswa.index') || request()->routeIs('admin.ruangan.index') || request()->routeIs('admin.mata-kuliah.index') || request()->routeIs('admin.jadwal-kuliah.index') ? 'true' : 'false' }},
        presensiOpen: localStorage.getItem('presensiOpen') === 'true' || {{ request()->routeIs('admin.alat-presensi.index') || request()->routeIs('admin.rfid.index') ? 'true' : 'false' }},
    
        get isEffectivelyExpanded() {
            return this.lockedOpen || this.isHovering;
        }
    }" x-init="$watch('lockedOpen', value => localStorage.setItem('sidebarLockedOpen', value));
    $watch('akademikOpen', value => localStorage.setItem('akademikOpen', value));
    $watch('presensiOpen', value => localStorage.setItem('presensiOpen', value));" x-cloak @mouseenter="isHovering = true"
        @mouseleave="isHovering = false"
        class="fixed top-0 left-0 h-screen bg-white dark:bg-black backdrop-blur-sm shadow-lg text-gray-700 dark:text-gray-200 transition-all duration-300 ease-in-out border-r border-gray-200 dark:border-gray-700 flex flex-col z-30 overflow-x-hidden"
        :style="{ width: isEffectivelyExpanded ? '16rem' : '4rem', 'will-change': 'width' }">
        <!-- Sidebar Header -->
        <div
            class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700 dark-mode-transition h-16">
            <a href="/" x-show="isEffectivelyExpanded"
                x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition cursor-pointer">
                'SIKMA'
            </a>
            <button @click="lockedOpen = !lockedOpen; $dispatch('sidebar-toggle', { locked: lockedOpen })"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-black/60 dark:backdrop-blur-sm focus:outline-none text-gray-500 dark:text-gray-300 dark-mode-transition cursor-pointer transition-all duration-300 hover:scale-110 flex items-center justify-center"
                :class="!isEffectivelyExpanded ? 'mx-auto' : ''">
                <span class="relative w-4 h-4 flex items-center justify-center">
                    <i class="fas fa-bars absolute inset-0 flex items-center justify-center" x-cloak x-show="lockedOpen"
                        x-transition:enter="transition-opacity ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in duration-150"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    </i>
                    <i class="fas fa-thumbtack absolute inset-0 flex items-center justify-center" x-cloak
                        x-show="!lockedOpen && isEffectivelyExpanded"
                        x-transition:enter="transition-opacity ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in duration-150"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    </i>
                    <i class="fas fa-chevron-right absolute inset-0 flex items-center justify-center" x-cloak
                        x-show="!lockedOpen && !isEffectivelyExpanded"
                        x-transition:enter="transition-opacity ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in duration-150"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    </i>
                </span>
            </button>
        </div>

        <nav
            class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
            <ul class="space-y-1 px-2 py-2">
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-3 hover:bg-gray-50 dark:hover:bg-black/60 transition-all duration-300 ease-in-out text-gray-700 dark:text-gray-200 rounded-lg group relative overflow-hidden dark-mode-transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-home text-gray-500 dark:text-gray-200 transition-colors duration-300 group-hover:text-blue-600 dark:group-hover:text-gray-100 dark-mode-transition"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap relative z-10 group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300 dark-mode-transition">Dashboard</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 dark:from-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </a>
                </li>

                <!-- Manajemen Akademik -->
                <li>
                    <button @click="akademikOpen = !akademikOpen"
                        class="flex items-center w-full px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 dark:text-gray-300 rounded-lg group relative overflow-hidden cursor-pointer {{ request()->routeIs('admin.semester.index') || request()->routeIs('admin.prodi.index') || request()->routeIs('admin.golongan.index') || request()->routeIs('admin.dosen.index') || request()->routeIs('admin.mahasiswa.index') || request()->routeIs('admin.ruangan.index') || request()->routeIs('admin.mata-kuliah.index') || request()->routeIs('admin.jadwal-kuliah.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-university text-gray-500 dark:text-gray-200 transition-colors duration-300 group-hover:text-blue-600 dark:group-hover:text-gray-100 dark-mode-transition"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap flex-1 text-left relative z-10 group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Manajemen
                            Akademik</span>
                        <i x-show="isEffectivelyExpanded" :class="akademikOpen ? 'rotate-180' : 'rotate-0'"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75"
                            class="fas fa-chevron-down text-gray-500 dark:text-gray-200 transition-all duration-300 ease-in-out relative z-10 text-sm flex-shrink-0"></i>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 dark:from-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </button>

                    <div :class="akademikOpen && isEffectivelyExpanded ? 'open' : 'closed'"
                        x-show="isEffectivelyExpanded" x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 translate-x-4" class="submenu-container pl-6">
                        <ul class="space-y-2">
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-100"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.dosen.index') }}"
                                    class="flex items-center px-3 py-2.5 mt-2 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.dosen.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-chalkboard-teacher text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Dosen</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-150"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.mahasiswa.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.mahasiswa.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-user-graduate text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Mahasiswa</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.semester.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.semester.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-graduation-cap text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Semester</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.prodi.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.prodi.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-building text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Program
                                        Studi</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.golongan.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.golongan.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-layer-group text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Golongan</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.ruangan.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.ruangan.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-door-open text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Ruangan</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.mata-kuliah.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.mata-kuliah.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-book-open text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Mata
                                        Kuliah</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.jadwal-kuliah.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.jadwal-kuliah.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-calendar-alt text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Jadwal
                                        Kuliah</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <button @click="presensiOpen = !presensiOpen"
                        class="flex items-center w-full px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 dark:text-gray-300 rounded-lg group relative overflow-hidden cursor-pointer {{ request()->routeIs('admin.alat-presensi.index') || request()->routeIs('admin.rfid.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-clipboard-list text-gray-500 transition-colors duration-300 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap flex-1 text-left relative z-10 dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Manajemen
                            Presensi</span>
                        <i x-show="isEffectivelyExpanded" :class="presensiOpen ? 'rotate-180' : 'rotate-0'"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 scale-75"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-75"
                            class="fas fa-chevron-down text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-all duration-300 ease-in-out relative z-10 text-sm flex-shrink-0"></i>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 dark:from-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </button>

                    <div :class="presensiOpen && isEffectivelyExpanded ? 'open' : 'closed'"
                        x-show="isEffectivelyExpanded" x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 translate-x-4" class="submenu-container pl-6">
                        <ul class="space-y-2">
                            <li x-show="presensiOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.alat-presensi.index') }}"
                                    class="flex items-center px-3 py-2.5 mt-2 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.alat-presensi.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-tools text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Alat
                                        Presensi</span>
                                </a>
                            </li>
                            <li x-show="presensiOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.rfid.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.rfid.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-credit-card text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Registrasi
                                        RFID</span>
                                </a>
                            </li>
                            <li x-show="presensiOpen && isEffectivelyExpanded"
                                x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.rekapitulasi.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 dark:text-gray-300 rounded-lg group {{ request()->routeIs('admin.rekapitulasi.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-chart-line text-gray-500 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Rekapitulasi
                                        Kehadiran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 dark:text-gray-300 rounded-lg group relative overflow-hidden {{ request()->routeIs('admin.laporan.index') ? 'bg-gray-100 dark:bg-gray-800/70' : 'hover:bg-gray-50 dark:hover:bg-gray-800/40' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-comment-dots text-gray-500 transition-colors duration-300 dark:text-gray-200 group-hover:text-blue-500 dark:group-hover:text-gray-100 text-sm"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap relative z-10 dark:text-gray-200 group-hover:text-blue-700 dark:group-hover:text-blue-500 transition-colors duration-300">Laporan
                            Mahasiswa</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 dark:from-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto border-t-2 border-gray-100 dark:border-gray-700">
            <div class="px-2 py-3">
                <div x-data="{ showLogoutConfirm: false }" class="admin-profile" :class="!isEffectivelyExpanded ? 'collapsed' : ''">
                    <div class="profile-content">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i
                                class="fas fa-user-circle text-xl text-gray-500 dark:text-gray-300 dark-mode-transition transition-colors duration-300"></i>
                        </div>
                        <span x-show="isEffectivelyExpanded" class="ml-3 text-sm whitespace-nowrap">Admin Program
                            Studi</span>
                    </div>
                    <button @click="$dispatch('open-logout-modal')" type="button" x-show="isEffectivelyExpanded"
                        class="flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-black/60 rounded-lg text-gray-500 dark:text-gray-300 focus:outline-none transition-all duration-300 group">
                        <i
                            class="fas fa-sign-out-alt group-hover:text-red-500 transition-colors duration-300 text-sm"></i>
                    </button>

                    <!-- Logout Modal -->
                    <template x-teleport="body">
                        <div x-show="showLogoutConfirm" class="fixed inset-0 z-[9999] overflow-y-auto">
                            <div x-show="showLogoutConfirm" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                @click="showLogoutConfirm = false"
                                class="fixed inset-0 backdrop-blur-sm bg-black/50 dark:bg-black/60">
                            </div>

                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="relative bg-white dark:bg-gray-900/60 backdrop-blur-sm rounded-lg shadow-xl w-[320px] mx-auto"
                                    @click.away="showLogoutConfirm = false" x-show="showLogoutConfirm"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95">
                                    <div class="p-6">
                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                                                <i
                                                    class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 ml-3">
                                                Logout</h3>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-300 mb-5">Anda yakin ingin logout?</p>

                                        <div class="flex justify-end space-x-2">
                                            <button type="button" @click="showLogoutConfirm = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200 cursor-pointer">
                                                Cancel
                                            </button>
                                            <button type="button" @click="showLogoutConfirm = false"
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 dark:bg-red-700 border border-transparent rounded-md hover:bg-red-700 dark:hover:bg-red-800 focus:outline-none transition-colors duration-200 cursor-pointer">
                                                Logout
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content -->
    <div id="main" x-data="{ isSidebarLockedOpen: localStorage.getItem('sidebarLockedOpen') === 'true' || false }" x-cloak
        @sidebar-toggle.window="isSidebarLockedOpen = $event.detail.locked" class="bg-gray-50 min-h-screen z-20"
        :style="{
            'padding-left': isSidebarLockedOpen ? '16rem' : '4rem',
            'transition': 'padding-left 0.3s ease-in-out'
        }">
        @yield('admin-content')
    </div>

    <style>
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-track-transparent::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Smooth animations */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Enhanced backdrop blur */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Improved hover states */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Ensure smooth transitions for main content */
        #main {
            will-change: padding-left;
        }

        /* Fix submenu animations */
        .submenu-container {
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .submenu-container.closed {
            max-height: 0;
            opacity: 0;
            transform: translateY(-8px);
        }

        .submenu-container.open {
            max-height: 500px;
            opacity: 1;
            transform: translateY(0);
        }

        /* Fix admin profile positioning */
        .admin-profile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            transition: all 0.3s ease-in-out;
            min-height: 48px;
        }

        .admin-profile.collapsed {
            justify-content: center;
            padding: 0.5rem 0.25rem;
        }

        .admin-profile .profile-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .admin-profile.collapsed .profile-content {
            justify-content: center;
        }
    </style>
</x-layout>
