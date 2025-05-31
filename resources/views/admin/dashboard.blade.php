<x-layout hide-navbar is-admin>
    <!-- Sidebar -->
    <div id="sidebar" x-data="{
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
        akademikOpen: localStorage.getItem('akademikOpen') === 'true' || {{ request()->routeIs('admin.semester.index') || request()->routeIs('admin.prodi.index') || request()->routeIs('admin.golongan.index') || request()->routeIs('admin.dosen.index') || request()->routeIs('admin.mahasiswa.index') || request()->routeIs('admin.ruangan.index') || request()->routeIs('admin.mata-kuliah.index') || request()->routeIs('admin.jadwal-kuliah.index') ? 'true' : 'false' }},
        presensiOpen: localStorage.getItem('presensiOpen') === 'true' || {{ request()->routeIs('admin.alat-presensi.index') || request()->routeIs('admin.rfid.index') ? 'true' : 'false' }}
    }" x-init="$watch('collapsed', value => localStorage.setItem('sidebarCollapsed', value));
    $watch('akademikOpen', value => localStorage.setItem('akademikOpen', value));
    $watch('presensiOpen', value => localStorage.setItem('presensiOpen', value));" x-cloak
        class="fixed top-0 left-0 h-screen bg-white shadow-lg text-gray-700 transition-all duration-300 ease-in-out border-r border-gray-200 flex flex-col z-30"
        :style="{ width: collapsed ? '4rem' : '16rem' }">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100 h-16">
            <a href="/" x-show="!collapsed" x-transition:enter="transition ease-out duration-300 delay-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" class="text-xl font-semibold text-gray-800 cursor-pointer">
                'SIKMA'
            </a>
            <button @click="collapsed = !collapsed; $dispatch('sidebar-toggle', { collapsed: collapsed })"
                class="p-2 rounded-lg hover:bg-gray-100 focus:outline-none text-gray-500 cursor-pointer transition-all duration-300 hover:scale-110 flex items-center justify-center"
                :class="collapsed ? 'mx-auto' : ''">
                <i :class="collapsed ? 'fas fa-chevron-right' : 'fas fa-bars'"
                    class="transition-all duration-300 ease-in-out w-4 h-4 flex items-center justify-center"></i>
            </button>
        </div>

        <nav
            class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
            <ul class="space-y-1 px-2 py-2">
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 rounded-lg group relative overflow-hidden {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-home text-gray-500 transition-colors duration-300 group-hover:text-blue-600"></i>
                        </div>
                        <span x-show="!collapsed" x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap relative z-10">Dashboard</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </a>
                </li>

                <!-- Manajemen Akademik -->
                <li>
                    <button @click="akademikOpen = !akademikOpen"
                        class="flex items-center w-full px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 rounded-lg group relative overflow-hidden cursor-pointer {{ request()->routeIs('admin.semester.index') || request()->routeIs('admin.prodi.index') || request()->routeIs('admin.golongan.index') || request()->routeIs('admin.dosen.index') || request()->routeIs('admin.mahasiswa.index') || request()->routeIs('admin.ruangan.index') || request()->routeIs('admin.mata-kuliah.index') || request()->routeIs('admin.jadwal-kuliah.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-university text-gray-500 transition-colors duration-300 group-hover:text-blue-600"></i>
                        </div>
                        <span x-show="!collapsed" x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap flex-1 text-left relative z-10">Manajemen
                            Akademik</span>
                        <i x-show="!collapsed" :class="akademikOpen ? 'rotate-180' : 'rotate-0'"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75"
                            class="fas fa-chevron-down text-gray-500 transition-all duration-300 ease-in-out relative z-10 text-sm flex-shrink-0"></i>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </button>

                    <div :class="akademikOpen && !collapsed ? 'open' : 'closed'" x-show="!collapsed"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-x-0"
                        x-transition:leave-end="opacity-0 translate-x-4" class="submenu-container pl-6">
                        <ul class="space-y-1">
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-100"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.dosen.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.dosen.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-chalkboard-teacher text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Dosen</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-150"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.mahasiswa.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.mahasiswa.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-user-graduate text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Mahasiswa</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.semester.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.semester.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-graduation-cap text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Semester</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.prodi.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.prodi.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-building text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Program
                                        Studi</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.golongan.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.golongan.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-layer-group text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Golongan</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.ruangan.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.ruangan.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-door-open text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Ruangan</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.mata-kuliah.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.mata-kuliah.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-book-open text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Mata
                                        Kuliah</span>
                                </a>
                            </li>
                            <li x-show="akademikOpen" x-transition:enter="transition ease-out duration-300 delay-200"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.jadwal-kuliah.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.jadwal-kuliah.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i
                                            class="fas fa-calendar-alt text-gray-500 group-hover:text-blue-500 transition-colors duration-300 text-sm"></i>
                                    </div>
                                    <span
                                        class="ml-3 text-sm group-hover:text-blue-700 transition-colors duration-300">Jadwal
                                        Kuliah</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <button @click="presensiOpen = !presensiOpen"
                        class="flex items-center w-full px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 rounded-lg group relative overflow-hidden cursor-pointer {{ request()->routeIs('admin.alat-presensi.index') || request()->routeIs('admin.rfid.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-clipboard-list text-gray-500 transition-colors duration-300 group-hover:text-blue-600"></i>
                        </div>
                        <span x-show="!collapsed" x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap flex-1 text-left relative z-10">Manajemen
                            Presensi</span>
                        <i x-show="!collapsed" :class="presensiOpen ? 'rotate-180' : 'rotate-0'"
                            x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 scale-75"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-75"
                            class="fas fa-chevron-down text-gray-500 transition-all duration-300 ease-in-out relative z-10 text-sm flex-shrink-0"></i>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </button>

                    <div :class="presensiOpen && !collapsed ? 'open' : 'closed'" x-show="!collapsed"
                        x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-y-100 translate-y-0"
                        x-transition:leave-end="opacity-0 transform scale-y-0 -translate-y-2"
                        class="submenu-container pl-6">
                        <ul class="space-y-1">
                            <li x-show="presensiOpen" x-transition:enter="transition ease-out duration-300 delay-100"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.alat-presensi.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.alat-presensi.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-tools text-gray-500 text-sm"></i>
                                    </div>
                                    <span class="ml-3 text-sm">Alat Presensi</span>
                                </a>
                            </li>
                            <li x-show="presensiOpen" x-transition:enter="transition ease-out duration-300 delay-100"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.rfid.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.rfid.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-credit-card text-gray-500 text-sm"></i>
                                    </div>
                                    <span class="ml-3 text-sm">Registrasi RFID</span>
                                </a>
                            </li>
                            <li x-show="presensiOpen" x-transition:enter="transition ease-out duration-300 delay-100"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0">
                                <a href="{{ route('admin.rekapitulasi.index') }}"
                                    class="flex items-center px-3 py-2.5 hover:bg-blue-50 transition-all duration-300 ease-in-out text-gray-600 rounded-lg group {{ request()->routeIs('admin.rekapitulasi.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                                    <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-credit-card text-gray-500 text-sm"></i>
                                    </div>
                                    <span class="ml-3 text-sm">Rekapitulasi Kehadiran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center px-3 py-3 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 rounded-lg group relative overflow-hidden {{ request()->routeIs('admin.laporan.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }}">
                        <div class="w-5 h-5 flex items-center justify-center relative z-10 flex-shrink-0">
                            <i
                                class="fas fa-comment-dots text-gray-500 transition-colors duration-300 group-hover:text-blue-600 text-sm"></i>
                        </div>
                        <span x-show="!collapsed" x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap relative z-10">Laporan Mahasiswa</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto border-t-2 border-gray-100">
            <div class="px-2 py-3">
                <div x-data="{ showLogoutConfirm: false }" class="admin-profile" :class="collapsed ? 'collapsed' : ''">
                    <div class="profile-content">
                        <div class="w-5 h-5 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-circle text-gray-500 transition-colors duration-300 text-sm"></i>
                        </div>
                        <span x-show="!collapsed" x-transition:enter="transition ease-out duration-300 delay-150"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4"
                            class="ml-3 text-sm whitespace-nowrap">Admin Program Studi</span>
                    </div>
                    <button @click="showLogoutConfirm = true" type="button"
                        class="flex items-center px-3 py-2 hover:bg-gray-100 rounded-lg text-gray-500 focus:outline-none transition-all duration-300 group"
                        :class="collapsed ? 'hidden' : ''">
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
                                @click="showLogoutConfirm = false" class="fixed inset-0 backdrop-blur-sm bg-black/50">
                            </div>

                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="relative bg-white rounded-lg shadow-xl w-[320px] mx-auto"
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
                                                class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-exclamation-circle text-red-600"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Logout</h3>
                                        </div>
                                        <p class="text-gray-500 mb-5">Anda yakin ingin logout?</p>

                                        <div class="flex justify-end space-x-2">
                                            <button type="button" @click="showLogoutConfirm = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none transition-colors duration-200 cursor-pointer">
                                                Cancel
                                            </button>
                                            <button type="button" @click="showLogoutConfirm = false"
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none transition-colors duration-200 cursor-pointer">
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
    <div id="main" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false }" x-cloak
        @sidebar-toggle.window="sidebarCollapsed = $event.detail.collapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
        class="bg-gray-50 min-h-screen z-20 transition-none"
        x-bind:style="{
            'padding-left': sidebarCollapsed ? '4rem' : '16rem',
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
