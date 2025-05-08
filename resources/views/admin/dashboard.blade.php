<x-layout hide-navbar is-admin>
    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed top-0 left-0 h-screen bg-white shadow-lg text-gray-700 transition-all duration-300 ease-in-out w-64 border-r border-gray-200 flex flex-col">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <a href="/"
                class="text-xl font-semibold truncate transition-all duration-300 ease-in-out sidebar-text text-gray-800 cursor-pointer">
                'SIKMA'
            </a>
            <button id="toggleButton"
                class="p-2 rounded-lg hover:bg-gray-100 focus:outline-none text-gray-500 cursor-pointer">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="mt-6 flex-1">
            <ul class="space-y-1 px-2">
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                        <div class="min-w-[20px] flex items-center justify-center">
                            <i class="fas fa-home text-gray-500"></i>
                        </div>
                        <span
                            class="ml-3 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap">Dashboard</span>
                    </a>
                </li>

                <!-- Manajemen Akademik -->
                <li>
                    <button id="toggleAkademik"
                        class="flex items-center w-full px-3 py-2.5 {{ request()->routeIs('admin.semester.index') || request()->routeIs('admin.prodi.index') || request()->routeIs('admin.golongan.index') || request()->routeIs('admin.dosen.index') || request()->routeIs('admin.mahasiswa.index') || request()->routeIs('admin.ruangan.index') || request()->routeIs('admin.mata-kuliah.index') || request()->routeIs('admin.jadwal-kuliah.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group cursor-pointer">
                        <div class="min-w-[20px] flex items-center justify-center">
                            <i class="fas fa-university  text-gray-500"></i>
                        </div>
                        <span
                            class="ml-3 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap">Manajemen
                            Akademik</span>
                        <i id="chevronAkademikIcon" class="fas fa-chevron-down ml-auto text-gray-500"></i>
                    </button>
                    <ul id="akademikSubmenu"
                        class="space-y-1 mt-1 pl-6 hidden overflow-hidden transition-all duration-300 ease-in-out">
                        <li>
                            <a href="{{ route('admin.dosen.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.dosen.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Dosen</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.mahasiswa.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.mahasiswa.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Mahasiswa</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.semester.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.semester.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Semester</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.prodi.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.prodi.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-building text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Program Studi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.golongan.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.golongan.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-layer-group text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Golongan</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.ruangan.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.ruangan.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-door-open text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Ruangan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.mata-kuliah.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.mata-kuliah.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-book-open text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Mata Kuliah</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.jadwal-kuliah.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.jadwal-kuliah.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Jadwal Kuliah</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.presensi') }}"
                        class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.presensi') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                        <div class="min-w-[20px] flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-gray-500"></i>
                        </div>
                        <span
                            class="ml-3 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap">Presensi</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile Section -->
        <div class="mt-auto border-t-2 border-gray-100">
            <div class="px-2 py-3">
                <div class="flex items-center px-3 py-2.5 hover:bg-gray-50 transition-all duration-300 ease-in-out text-gray-700 rounded-lg group"
                    x-data="{ showLogoutConfirm: false }">
                    <div class="min-w-[20px] flex items-center justify-center">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <span
                        class="ml-3 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap overflow-hidden">{{ Auth::user()->name }}</span>

                    <div
                        class="ml-auto transition-all duration-300 transform sidebar-text flex items-center overflow-hidden">
                        <button @click="showLogoutConfirm = true" type="button"
                            class="p-1 hover:bg-gray-100 rounded-lg text-gray-500 focus:outline-none transition-all duration-300">
                            <i class="fas fa-sign-out-alt cursor-pointer"></i>
                        </button>
                    </div>

                    <div x-show="showLogoutConfirm" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <!-- Backdrop with animation -->
                        <div x-show="showLogoutConfirm" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="fixed inset-0 backdrop-blur-xs bg-black/40"></div>
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
                                        <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none transition-colors duration-200 cursor-pointer">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main" class="ml-64 transition-all duration-300 ease-in-out bg-gray-50 min-h-screen">
        @yield('admin-content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const toggleButton = document.getElementById('toggleButton');
            const toggleAkademik = document.getElementById('toggleAkademik');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const akademikSubmenu = document.getElementById('akademikSubmenu');
            const chevronIcon = document.getElementById('chevronAkademikIcon');
            let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true' ? true : false;
            let isOpenDropdownAkademik = localStorage.getItem("isOpenDropdownAkademik") === "true" ? true : false;

            if (isCollapsed) {
                if (isOpenDropdownAkademik) {
                    localStorage.setItem("isOpenDropdownAkademik", isOpenDropdownAkademik) === "true" ?
                        "true" : "false";
                    akademikSubmenu.classList.remove('hidden');
                    chevronIcon.classList.add('rotate-180');
                } else {
                    akademikSubmenu.classList.add('hidden');
                    chevronIcon.classList.remove('rotate-180');
                }
                chevronIcon.classList.remove('rotate-180');
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                main.classList.remove('ml-64');
                main.classList.add('ml-16');
                sidebarTexts.forEach(text => {
                    text.classList.add('opacity-0', 'translate-x-[-50px]');
                    setTimeout(() => {
                        text.classList.add('w-0');
                    }, 150);
                });
                toggleButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
                akademikSubmenu.classList.add('hidden');
                chevronIcon.style.display = 'none';
            } else {

                akademikSubmenu.classList.remove('hidden');
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');
                main.classList.remove('ml-16');
                main.classList.add('ml-64');
                sidebarTexts.forEach(text => {
                    text.classList.remove('w-0');
                    setTimeout(() => {
                        text.classList.remove('opacity-0', 'translate-x-[-50px]');
                    }, 150);
                });
                toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
                chevronIcon.style.display = 'block';
            }

            toggleButton.addEventListener('click', () => {
                isCollapsed = !isCollapsed;
                if (isOpenDropdownAkademik) {
                    localStorage.setItem("isOpenDropdownAkademik", "false");
                    chevronIcon.classList.remove('rotate-180');
                }
                localStorage.setItem("sidebarCollapsed", isCollapsed);

                if (isCollapsed) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-16');
                    main.classList.remove('ml-64');
                    main.classList.add('ml-16');
                    sidebarTexts.forEach(text => {
                        text.classList.add('opacity-0', 'translate-x-[-50px]');
                        setTimeout(() => {
                            text.classList.add('w-0');
                        }, 150);
                    });
                    toggleButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
                    akademikSubmenu.classList.add('hidden');
                    chevronIcon.style.display = 'none';
                } else {
                    sidebar.classList.remove('w-16');
                    if (isOpenDropdownAkademik) {
                        localStorage.setItem("isOpenDropdownAkademik", isOpenDropdownAkademik) === "true" ?
                            "true" : "false";
                        akademikSubmenu.classList.remove('hidden');
                        chevronIcon.classList.add('rotate-180');
                    } else {
                        akademikSubmenu.classList.add('hidden');
                        chevronIcon.classList.remove('rotate-180');
                    }
                    sidebar.classList.add('w-64');
                    main.classList.remove('ml-16');
                    main.classList.add('ml-64');
                    sidebarTexts.forEach(text => {
                        text.classList.remove('w-0');
                        setTimeout(() => {
                            text.classList.remove('opacity-0', 'translate-x-[-50px]');
                        }, 150);
                    });
                    toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
                    chevronIcon.style.display = 'block';
                }
            });

            if (isOpenDropdownAkademik) {
                akademikSubmenu.classList.remove('hidden');
                chevronIcon.classList.add('rotate-180');
            } else {
                akademikSubmenu.classList.add('hidden');
                chevronIcon.classList.remove('rotate-180');
            }

            toggleAkademik.addEventListener('click', () => {
                isOpenDropdownAkademik = !isOpenDropdownAkademik;
                localStorage.setItem("isOpenDropdownAkademik", isOpenDropdownAkademik);
                if (isOpenDropdownAkademik) {
                    akademikSubmenu.classList.remove('hidden');
                    chevronIcon.classList.add('rotate-180');
                } else {
                    akademikSubmenu.classList.add('hidden');
                    chevronIcon.classList.remove('rotate-180');
                }
            });

            function updateActiveLink(url) {
                const sidebarLinks = document.querySelectorAll('.sidebar-link');
                sidebarLinks.forEach(link => {
                    link.classList.remove('bg-gray-100');
                    if (link.getAttribute('href') === url) {
                        link.classList.add('bg-gray-100');
                    }
                });
            }

            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = this.getAttribute('href');
                    window.history.pushState({}, '', url);
                    updateActiveLink(url);
                    document.getElementById('main').load(
                        url);
                });
            });
        });
    </script>
</x-layout>
