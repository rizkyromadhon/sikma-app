@extends('dosen.layouts')

@section('dosen-content')
    <div x-data="{
        currentTime: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
        notifDropdownOpen: false,
        notifications: {{ $notifications->toJson() }},
        unreadCount: {{ $unreadNotificationsCount }},
        quickActions: [{
                icon: 'fas fa-calendar-alt',
                title: 'Lihat Jadwal',
                color: 'blue',
                href: '{{ route('dosen.jadwal.index') }}'
            },
            {
                icon: 'fas fa-user-check',
                title: 'Kelola Presensi',
                color: 'green',
                href: '{{ route('dosen.presensi.index') }}'
            },
            {
                icon: 'fas fa-file-medical',
                title: 'Pengajuan Izin/Sakit',
                color: 'purple',
                href: '{{ route('dosen.izin.index') }}'
            },
            {
                icon: 'fas fa-bullhorn',
                title: 'Pengumuman',
                color: 'orange',
                href: '{{ route('dosen.pengumuman.index') }}'
            }
        ],
        toasts: [], // Array untuk menyimpan notifikasi toast yang aktif
    
        initListener() {
            if (window.Echo) {
                window.Echo.private('notifikasi-dosen.{{ auth()->id() }}')
                    .listen('NotifikasiIzinBaru', (e) => { // <-- Nama Event sudah benar
                        console.log('Notifikasi baru untuk dosen diterima:', e);
                        this.unreadCount++;
    
                        // 'e' sekarang berisi data notifikasi, tidak ada properti .notification lagi
                        this.notifications.unshift(e);
    
                        // Kirim 'e' langsung ke fungsi toast
                        this.addToast(e, 'info');
                    });
            } else {
                console.error('Laravel Echo tidak terdefinisi.');
            }
        },
        markAsReadAllDosen() {
            if (this.unreadCount > 0) {
                fetch('{{ route('notifikasi.markAllAsReadDosen') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') { this.unreadCount = 0; }
                    });
            }
        },
    
        // Fungsi untuk menambahkan toast baru
        addToast(notification, type = 'info') {
            const id = Date.now();
            this.toasts.push({
                id: id,
                type: type,
                // Perbaiki ini untuk menggunakan properti 'konten' dari event
                message: notification.konten || 'Anda menerima notifikasi baru.'
            });
            setTimeout(() => this.removeToast(id), 5000);
        },
    
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }" x-init="setInterval(() => currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }), 60000);
    initListener();">

        <header
            class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-sm"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Dashboard Dosen</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sistem Presensi & Rekapitulasi</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div
                        class="hidden md:flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-lg">
                        <i class="fas fa-clock"></i>
                        <span x-text="currentTime"></span>
                    </div>
                    <div class="relative">
                        <button @click="notifDropdownOpen = !notifDropdownOpen; markAsReadAllDosen();"
                            class="relative p-2 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span x-show="unreadCount > 0" x-cloak
                                class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse"
                                x-text="unreadCount"></span>
                        </button>

                        <div x-show="notifDropdownOpen" @click.outside="notifDropdownOpen = false" x-transition
                            class="absolute right-0 mt-3 w-72 sm:w-80 origin-top-right rounded-md bg-white dark:bg-gray-900 dark-mode-transition backdrop-blur-md shadow-lg ring-1 ring-gray-300 dark:ring-gray-600 ring-opacity-5 focus:outline-none z-50"
                            style="display: none;">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                            </div>
                            <div class="p-2 max-h-64 overflow-y-auto custom-scrollbar">
                                <template x-if="notifications.length === 0">
                                    <p class="text-center text-sm text-gray-500 p-4">Tidak ada notifikasi.</p>
                                </template>
                                <template x-for="notif in notifications" :key="notif.id">
                                    <a :href="notif.url_tujuan || '#'"
                                        class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                        <p class="font-bold" x-text="notif.tipe"></p>
                                        <p class="text-xs" x-text="notif.konten"></p>
                                        <p class="text-xs mt-1 text-blue-500 dark:text-blue-400"
                                            x-text="notif.created_at_human"></p>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 mx-auto">
            <div
                class="mb-8 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl p-6 z-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2">Selamat Datang Kembali - {{ auth()->user()->name }}!</h2>
                    <p class="text-blue-100 mb-4">
                        @if ($jumlahKelasHariIni > 0)
                            Anda memiliki <strong>{{ $jumlahKelasHariIni }}</strong> kelas yang dijadwalkan hari ini.
                        @else
                            Tidak ada kelas yang dijadwalkan hari ini. Semoga harimu tenang!
                        @endif
                    </p>
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-calendar"></i>
                            <span
                                x-text="new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                        </div>
                    </div>
                </div>
                <div class="absolute top-4 right-4 opacity-20">
                    <i class="fas fa-chalkboard-teacher text-6xl"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Kuliah</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $jumlahMataKuliah }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Mahasiswa</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalMahasiswa }}</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1"><i class="fas fa-users"></i> Yang
                                diajar semester ini.</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jam Mengajar</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $jamMengajarPerMinggu }}</p>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1"><i class="fas fa-clock"></i> Per
                                minggu</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kehadiran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ round($persentaseKehadiran) }}%
                            </p>
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1"><i class="fas fa-arrow-up"></i> +5%
                                dari bulan lalu</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i> Aksi Cepat
                </h3>
                <div class="flex flex-row w-full gap-4">
                    <template x-for="action in quickActions" :key="action.title">
                        <a :href="action.href"
                            class="  flex-1 group bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700  hover:shadow-lg transition-all duration-200 text-center"
                            :class="{
                                'hover:border-blue-500 dark:hover:border-blue-400': action.color === 'blue',
                                'hover:border-green-500 dark:hover:border-green-400': action.color === 'green',
                                'hover:border-purple-500 dark:hover:border-purple-400': action.color === 'purple',
                                'hover:border-orange-500 dark:hover:border-orange-400': action.color === 'orange'
                            }">
                            <div class="mb-4">
                                <i
                                    :class="{
                                        'text-3xl': true,
                                        'text-gray-400': true,
                                        'transition-colors': true,
                                        'duration-200': true,
                                        [action.icon]: true,
                                        'group-hover:text-blue-500': action.color === 'blue',
                                        'group-hover:text-green-500': action.color === 'green',
                                        'group-hover:text-purple-500': action.color === 'purple',
                                        'group-hover:text-orange-500': action.color === 'orange'
                                    }"></i>
                            </div>
                            <h4 class="font-medium text-gray-900 dark:text-white transition-colors duration-200"
                                :class="{
                                    'group-hover:text-blue-600 dark:group-hover:text-blue-400': action
                                        .color === 'blue',
                                    'group-hover:text-green-600 dark:group-hover:text-green-400': action
                                        .color === 'green',
                                    'group-hover:text-purple-600 dark:group-hover:text-purple-400': action
                                        .color === 'purple',
                                    'group-hover:text-orange-600 dark:group-hover:text-orange-400': action
                                        .color === 'orange'
                                }"
                                x-text="action.title"></h4>
                        </a>
                    </template>
                </div>
            </div>

            @livewire('dosen.jadwal-hari-ini')
        </main>

        <div x-cloak aria-live="assertive" class="fixed inset-0 flex items-start px-4 py-6 pointer-events-none z-[9999]">
            <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                <template x-for="toast in toasts" :key="toast.id">
                    <div x-data="{ show: false }" x-init="setTimeout(() => {
                        show = true;
                        setTimeout(() => removeToast(toast.id), 5000);
                    }, 50);" x-show="show"
                        @click.away="removeToast(toast.id)"
                        x-transition:enter="transform ease-out duration-300 transition"
                        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="max-w-sm w-full bg-white dark:bg-slate-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 dark:ring-white/10 overflow-hidden">
                        <div class="p-4 relative">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <template x-if="toast.type === 'success'">
                                        <i class="fas fa-check-circle text-green-500 h-6 w-6"></i>
                                    </template>
                                    <template x-if="toast.type === 'error'">
                                        <i class="fas fa-times-circle text-red-500 h-6 w-6"></i>
                                    </template>
                                    <template x-if="toast.type === 'info'">
                                        <i class="fas fa-info-circle text-blue-500 h-6 w-6"></i>
                                    </template>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi Baru</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" x-text="toast.message"></p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex">
                                    <button @click="removeToast(toast.id)"
                                        class="inline-flex text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Tutup</span>
                                        <i class="fas fa-times h-5 w-5"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Progress bar menggunakan animasi CSS -->
                            <div class="absolute bottom-0 left-0 h-1"
                                :class="{
                                    'bg-green-500': toast.type === 'success',
                                    'bg-red-500': toast.type === 'error',
                                    'bg-blue-500': toast.type === 'info'
                                }"
                                style="animation: toast-progress 5s linear forwards;">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <style>
        @keyframes toast-progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
@endsection
