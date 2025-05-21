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

        <nav
            class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
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
                    <button id="togglePresensi"
                        class="flex items-center w-full px-3 py-2.5 {{ request()->routeIs('admin.alat-presensi.index') || request()->routeIs('admin.rfid.index') || request()->routeIs('admin.golongan.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group cursor-pointer">
                        <div class="min-w-[20px] flex items-center justify-center">
                            <i class="fas fa-clipboard-list  text-gray-500"></i>
                        </div>
                        <span
                            class="ml-3 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap">Manajemen
                            Presensi</span>
                        <i id="chevronPresensiIcon" class="fas fa-chevron-down ml-auto text-gray-500"></i>
                    </button>
                    <ul id="presensiSubmenu"
                        class="space-y-1 mt-1 pl-6 hidden overflow-hidden transition-all duration-300 ease-in-out">
                        <li>
                            <a href="{{ route('admin.alat-presensi.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.alat-presensi.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-tools text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Alat Presensi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.rfid.index') }}"
                                class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.rfid.index') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                                <div class="min-w-[20px] flex items-center justify-center">
                                    <i class="fas fa-credit-card text-gray-500"></i>
                                </div>
                                <span class="ml-3 text-sm">Registrasi RFID</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center px-3 py-2.5 {{ request()->routeIs('admin.laporan') ? 'bg-gray-100' : 'hover:bg-gray-50' }} transition-all duration-300 ease-in-out text-gray-700 rounded-lg group">
                        <div class="min-w-[20px] flex items-center justify-center">
                            <i class="fas fa-comment-dots text-gray-500"></i>
                        </div>
                        <span
                            class="ml-3 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap">Laporan
                            Mahasiswa</span>
                    </a>
                </li>

            </ul>
        </nav>


        <!-- User Profile Section -->
        <div class="mt-auto border-t-2 border-gray-100">
            <div class="px-2 py-3">
                <div x-data="{ showLogoutConfirm: false }"
                    class="ml-auto transition-all duration-300 transform sidebar-text flex items-center overflow-hidden">
                    <div class="flex items-center justify-between gap-8">
                        <button @click="showLogoutConfirm = true" type="button"
                            class="p-1 hover:bg-gray-100 rounded-lg text-gray-500 focus:outline-none transition-all duration-300">
                            <span
                                class="ml-3 mr-10 transition-all duration-300 transform sidebar-text text-sm whitespace-nowrap overflow-hidden">{{ Auth::user()->name }}</span>
                            <i class="fas fa-sign-out-alt cursor-pointer"></i>
                        </button>
                    </div>


                    <!-- Template Alpine.js untuk modal -->
                    <template x-teleport="body">
                        <div x-show="showLogoutConfirm" class="fixed inset-0 z-[9999] overflow-y-auto">
                            <!-- Backdrop dengan blur efek yang lebih kuat -->
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
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main" class="ml-64 transition-all duration-300 ease-in-out bg-gray-50 min-h-screen z-20">
        @yield('admin-content')
    </div>
    <style>
        /* Sembunyikan scrollbar default dari browser */
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
        }

        /* Track scrollbar */
        .scrollbar-track-transparent::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Thumb scrollbar */
        .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 20px;
        }

        /* Sembunyikan scrollbar ketika tidak di-hover (opsional) */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }

        /* Tampilkan scrollbar saat hover (opsional) */
        .scrollbar-thin:hover::-webkit-scrollbar-thumb {
            background: #9ca3af;
        }

        /* Force backdrop filter to work with higher specificity */
        .backdrop-blur-sm {
            -webkit-backdrop-filter: blur(4px) !important;
            backdrop-filter: blur(4px) !important;
        }

        /* Force charts to respect z-index context */
        #chartSemester,
        #chartProdi,
        #pie-chart,
        #chartDosen {
            position: relative;
            z-index: 1;
        }

        /* Ensure modal is always on top */
        [x-show="showLogoutConfirm"] {
            isolation: isolate;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mencari semua chart container
            const chartContainers = [
                document.getElementById('chartSemester'),
                document.getElementById('chartProdi'),
                document.getElementById('pie-chart'),
                document.getElementById('chartDosen')
            ];

            // Fungsi untuk mengontrol blur pada chart
            Alpine.effect(() => {
                // Berikut ini akan berjalan setiap kali nilai showLogoutConfirm berubah
                const isModalOpen = Alpine.store('modalState')?.isOpen || false;

                chartContainers.forEach(container => {
                    if (container) {
                        if (isModalOpen) {
                            // Tambahkan class filter blur pada SVG elemen chart
                            const svgElements = container.querySelectorAll('svg');
                            svgElements.forEach(svg => {
                                svg.style.filter = 'blur(4px)';
                            });
                        } else {
                            // Hapus class filter blur
                            const svgElements = container.querySelectorAll('svg');
                            svgElements.forEach(svg => {
                                svg.style.filter = '';
                            });
                        }
                    }
                });
            });

            // Inisialisasi Alpine store untuk melacak status modal
            if (!Alpine.store('modalState')) {
                Alpine.store('modalState', {
                    isOpen: false
                });
            }

            // Implementasikan observer untuk memantau perubahan modal
            const body = document.body;
            const observer = new MutationObserver(mutations => {
                for (const mutation of mutations) {
                    if (mutation.type === 'attributes' || mutation.type === 'childList') {
                        // Cek apakah modal logout terlihat
                        const modalBackdrop = document.querySelector('.backdrop-blur-sm');
                        Alpine.store('modalState').isOpen = !!modalBackdrop && window.getComputedStyle(
                            modalBackdrop).display !== 'none';
                    }
                }
            });

            observer.observe(body, {
                attributes: true,
                childList: true,
                subtree: true
            });
        });
        // Script untuk menghandle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const toggleButton = document.getElementById('toggleButton');
            const sidebarNav = document.querySelector('#sidebar nav'); // Elemen scrollable

            // Akademik
            const toggleAkademik = document.getElementById('toggleAkademik');
            const akademikSubmenu = document.getElementById('akademikSubmenu');
            const chevronIcon = document.getElementById('chevronAkademikIcon');
            let isOpenDropdownAkademik = localStorage.getItem("isOpenDropdownAkademik") === "true";

            // Presensi
            const togglePresensi = document.getElementById('togglePresensi');
            const presensiSubmenu = document.getElementById('presensiSubmenu');
            const chevronPresensiIcon = document.getElementById('chevronPresensiIcon');
            let isOpenDropdownPresensi = localStorage.getItem("isOpenDropdownPresensi") === "true";

            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Fungsi toggle sidebar
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
                presensiSubmenu.classList.add('hidden');
                chevronPresensiIcon.style.display = 'none';
            } else {
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
                chevronPresensiIcon.style.display = 'block';

                // Tampilkan submenu jika terbuka
                if (isOpenDropdownAkademik) akademikSubmenu.classList.remove('hidden');
                else akademikSubmenu.classList.add('hidden');

                if (isOpenDropdownPresensi) presensiSubmenu.classList.remove('hidden');
                else presensiSubmenu.classList.add('hidden');
            }

            toggleButton.addEventListener('click', () => {
                isCollapsed = !isCollapsed;
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
                    presensiSubmenu.classList.add('hidden');
                    chevronIcon.style.display = 'none';
                    chevronPresensiIcon.style.display = 'none';
                } else {
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
                    chevronPresensiIcon.style.display = 'block';
                    if (isOpenDropdownAkademik) akademikSubmenu.classList.remove('hidden');
                    if (isOpenDropdownPresensi) presensiSubmenu.classList.remove('hidden');
                }

                // Memastikan scroll tetap berfungsi saat toggle sidebar
                setTimeout(() => {
                    sidebarNav.scrollTop = sidebarNav.scrollTop;
                }, 300);
            });

            // Toggle menu akademik
            toggleAkademik.addEventListener('click', () => {
                isOpenDropdownAkademik = !isOpenDropdownAkademik;
                localStorage.setItem("isOpenDropdownAkademik", isOpenDropdownAkademik);
                akademikSubmenu.classList.toggle('hidden');
                chevronIcon.classList.toggle('rotate-180');
            });

            // Toggle menu presensi
            togglePresensi.addEventListener('click', () => {
                isOpenDropdownPresensi = !isOpenDropdownPresensi;
                localStorage.setItem("isOpenDropdownPresensi", isOpenDropdownPresensi);
                presensiSubmenu.classList.toggle('hidden');
                chevronPresensiIcon.classList.toggle('rotate-180');
            });

            // Aktifkan link sidebar
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
                    document.getElementById('main').load(url);
                });
            });
        });
    </script>
</x-layout>
