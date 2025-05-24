<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIKMA - APP</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (config('livewire.broadcasting.enabled'))
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.1/dist/echo.iife.js"></script>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.1/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="h-full {{ !$hideNavbar ? '' : '' }} " x-data="{ showContactModal: false }">

    <!-- Navbar -->
    @unless ($hideNavbar)
        <x-navbar></x-navbar>
    @endunless

    @livewireStyles
    @livewireScripts
    <!-- Konten halaman -->
    <main>
        <div @class([
            !$isAdmin ? 'mx-auto w-full md:max-w-11/12 py-3 md:py-6' : '',
            $isOldPassword && !$isAdmin ? 'mt-30 md:mt-24' : ($isAdmin ? '' : 'mt-16'),
        ])>
            {{ $slot }}
        </div>
    </main>

    <!-- Notifikasi -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed left-1/2 -translate-x-1/2 md:left-auto md:right-6 md:-translate-x-0 w-[320px] md:w-auto z-50 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-md shadow-lg {{ $isOldPassword ? ' top-36 md:top-30' : ' top-20' }}">
            <strong>Sukses.</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed left-1/2 -translate-x-1/2 md:left-auto md:right-6 md:-translate-x-0 w-[320px] md:w-auto bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-md shadow-lg {{ $isOldPassword ? ' top-36 md:top-30' : ' top-20' }}">
            <strong>Gagal.</strong> {{ session('error') }}
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed left-1/2 -translate-x-1/2 md:left-auto md:right-6 md:-translate-x-0 w-[320px] md:w-auto bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-2 rounded-md shadow-lg {{ $isOldPassword ? ' top-36 md:top-30' : ' top-20' }}">
            <strong>Info.</strong> {{ session('info') }}
        </div>
    @endif

    @if ($errors->has('login'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed left-1/2 -translate-x-1/2 md:left-auto md:right-6 md:-translate-x-0 w-[320px] md:w-auto z-50 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-md shadow-lg {{ $isOldPassword ? ' top-36 md:top-30' : ' top-20' }}">
            <strong>Gagal.</strong> {{ $errors->first('login') }}
        </div>
    @endif

    <div x-data="{ showLogoutConfirm: false }" @open-logout-modal.window="showLogoutConfirm = true">
        <div x-show="showLogoutConfirm" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
            <!-- Backdrop dengan animasi -->
            <div x-show="showLogoutConfirm" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 backdrop-blur-sm bg-black/30"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-lg shadow-xl w-[320px] mx-auto"
                    @click.away="showLogoutConfirm = false" x-show="showLogoutConfirm"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <div class="p-6">
                        <!-- Header dengan Icon -->
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 ml-3">Logout</h3>
                        </div>

                        <!-- Message -->
                        <p class="text-gray-500 mb-5">Anda yakin ingin logout?</p>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="showLogoutConfirm = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none transition-colors duration-200 cursor-pointer">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                                @csrf
                                <button type="submit" x-on:click="loading = true"
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

    @if (!request()->is('admin/*') && (!Auth::check() || (Auth::check() && Auth::user()->role != 'admin')))
        <div class="fixed bottom-6 right-6 z-[9998]">
            <button @click="showContactModal = true"
                class="group bg-gray-800 text-white px-4 py-3 rounded-full shadow-lg hover:bg-gray-900 flex items-center justify-center gap-0 hover:gap-2 transition-all duration-500 ease-in-out cursor-pointer">

                <i class="fas fa-envelope"></i>

                <!-- Text with expanding effect -->
                <span
                    class="max-w-0 group-hover:max-w-[200px] overflow-hidden whitespace-nowrap transition-all duration-600 ease-in-out">
                    Hubungi Admin
                </span>
            </button>
        </div>
    @endif

    <div x-show="showContactModal" x-cloak x-transition.opacity.duration.200
        class="fixed inset-0 z-[9999] flex items-center justify-center">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/50" @click="showContactModal = false"></div>

        <!-- Modal Box -->
        <div x-data="formValidation()" x-show="showContactModal" x-transition.scale.duration.200
            class="relative bg-white w-full max-w-xs md:max-w-md mx-auto rounded-md shadow-lg z-50 p-6">

            <!-- Tombol Tutup -->
            <button @click="showContactModal = false"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">&times;</button>

            <!-- Judul -->
            <h2 class="text-xl font-semibold mb-4 text-center">Form Laporan ke Admin</h2>

            <!-- Form -->
            <form method="POST" action="{{ route('laporan.store') }}" @submit="handleSubmit">
                @csrf
                @method('POST')

                @auth
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">NIM</label>
                        <input type="text" name="nim" required readonly placeholder="E32222530"
                            value="{{ Auth::user()->nim }}"
                            class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required readonly placeholder="Nama Lengkap"
                            value="{{ Auth::user()->name }}"
                            class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>

                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required readonly placeholder="Email"
                            value="{{ Auth::user()->email }}"
                            class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>

                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <input type="text" name="prodi" required readonly placeholder="Program Studi"
                            value="{{ Auth::user()->programStudi->name ?? '-' }}"
                            class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>
                    <input type="hidden" name="id_prodi" value="{{ Auth::user()->id_prodi }}">
                @endauth

                @guest
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">NIM</label>
                        <div class="relative">
                            <input type="text" name="nim" x-model="form.nim" @input="validateNIM()" required
                                placeholder="E32222530"
                                class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">

                            <!-- Loading indicator -->
                            <div x-show="nimValidation.loading" class="absolute right-2 top-2.5">
                                <div class="animate-spin h-4 w-4 border-2 border-gray-300 border-t-gray-600 rounded-full">
                                </div>
                            </div>

                            <!-- Success icon -->
                            <div x-show="nimValidation.valid && !nimValidation.loading"
                                class="absolute right-2 top-2.5 text-green-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>

                            <!-- Error icon -->
                            <div x-show="nimValidation.invalid && !nimValidation.loading"
                                class="absolute right-2 top-2.5 text-red-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div x-show="nimValidation.invalid" class="text-red-500 text-xs mt-1">
                            NIM tidak terdaftar
                        </div>
                    </div>
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" x-model="form.nama_lengkap" required readonly
                            placeholder="Nama Lengkap"
                            class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>

                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email"x-model="form.email" required readonly placeholder="Email"
                            class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>

                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <input type="text" name="prodi" x-model="form.prodi" required readonly
                            placeholder="Program Studi"
                            class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-md focus:outline-none cursor-not-allowed bg-gray-100">
                    </div>
                    <input type="hidden" name="id_prodi" x-model="form.id_prodi">
                @endguest

                <div class="mb-4 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Pesan / Laporan</label>
                    <textarea name="pesan" x-model="form.pesan" rows="4" required placeholder="Kartu RFID Hilang / Rusak"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500"></textarea>
                </div>

                <div class="text-right">
                    <button type="submit" :disabled="!canSubmit"
                        :class="canSubmit ? 'bg-gray-800 hover:bg-gray-900' : 'bg-gray-400 cursor-not-allowed'"
                        class="text-white px-4 py-2 rounded transition">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function formValidation() {
            return {
                form: {
                    nama_lengkap: '{{ Auth::user()->name ?? '' }}',
                    nim: '{{ Auth::user()->nim ?? '' }}',
                    email: '{{ Auth::user()->email ?? '' }}',
                    id_prodi: '{{ Auth::user()->id_prodi ?? '' }}',
                    prodi: '{{ Auth::user()->programStudi->name ?? '' }}',
                    pesan: ''
                },
                nimValidation: {
                    valid: {{ Auth::check() ? 'true' : 'false' }},
                    invalid: false,
                    loading: false
                },
                nimTimeout: null,

                get canSubmit() {
                    return this.nimValidation.valid && this.form.pesan;
                },

                validateNIM() {
                    if (this.nimTimeout) {
                        clearTimeout(this.nimTimeout);
                    }

                    this.nimValidation = {
                        valid: false,
                        invalid: false,
                        loading: false
                    };

                    if (!this.form.nim.trim()) {
                        return;
                    }

                    this.nimValidation.loading = true;

                    this.nimTimeout = setTimeout(() => {
                        this.checkNIMInDatabase();
                    }, 500);
                },


                async checkNIMInDatabase() {
                    try {
                        const response = await fetch('/check-nim', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                nim: this.form.nim.trim() // Trim whitespace
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        // Debug logging
                        if (this.debugMode) {
                            console.log('NIM Check Response:', data);
                        }

                        this.nimValidation = {
                            valid: data.exists,
                            invalid: !data.exists,
                            loading: false
                        };

                        if (data.exists && data.user) {
                            this.form.nama_lengkap = data.user.name;
                            this.form.email = data.user.email;
                            this.form.id_prodi = data.user.id_prodi;
                            this.form.prodi = data.user.prodi_name;
                        } else {
                            this.form.nama_lengkap = '';
                            this.form.email = '';
                            this.form.id_prodi = '';
                            this.form.prodi = '';
                        }

                        // Debug info jika tidak ditemukan
                        if (!data.exists && this.debugMode) {
                            console.log('NIM not found:', {
                                input: this.form.nim,
                                trimmed: this.form.nim.trim(),
                                debug: data.debug
                            });
                        }

                    } catch (error) {
                        console.error('Error checking NIM:', error);

                        // Tampilkan error yang lebih spesifik
                        if (error.message.includes('404')) {
                            console.error('API endpoint /check-nim not found. Check your routes.');
                        } else if (error.message.includes('419')) {
                            console.error('CSRF token mismatch. Check your CSRF token.');
                        } else if (error.message.includes('500')) {
                            console.error('Server error. Check your controller and database connection.');
                        }

                        this.nimValidation = {
                            valid: false,
                            invalid: true,
                            loading: false
                        };
                    }
                },

                handleSubmit(e) {
                    if (!this.canSubmit) {
                        e.preventDefault();
                        alert('Mohon lengkapi form dan pastikan NIM terdaftar');
                        return false;
                    }

                    return true;
                },

            }
        }
        document.addEventListener('alpine:init', () => {
            window.addEventListener('load', () => {
                Alpine.store('loading').value = false;
            });
        });

        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => {
                if (Alpine.store('loading')?.value !== false) {
                    Alpine.store('loading').value = false;
                }
            }, 5000); // fallback jika window.load tidak terpanggil (jaga-jaga)
        });

        document.addEventListener('alpine:init', () => {
            Alpine.store('loading', {
                value: true
            });
        });
    </script>

</body>


</html>
