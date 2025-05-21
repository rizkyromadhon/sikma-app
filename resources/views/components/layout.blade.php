<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
        <div class="{{ !$isAdmin ? 'mx-auto max-w-11/12 py-3 sm:px-6 lg:px-2' : '' }}">
            {{ $slot }}
        </div>
    </main>

    <!-- Notifikasi -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-6 z-50 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-md shadow-lg">
            <strong>Sukses.</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-6 z-50 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-md shadow-lg">
            <strong>Gagal.</strong> {{ session('error') }}
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-6 z-50 bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-2 rounded-md shadow-lg">
            <strong>Info.</strong> {{ session('info') }}
        </div>
    @endif

    @if ($errors->has('login'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-6 z-50 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-md shadow-lg">
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

    <div x-show="showContactModal" x-transition.opacity.duration.200
        class="fixed inset-0 z-[9999] flex items-center justify-center">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/50" @click="showContactModal = false"></div>

        <!-- Modal Box -->
        <div x-show="showContactModal" x-transition.scale.duration.200
            class="relative bg-white w-full max-w-md mx-auto rounded-md shadow-lg z-50 p-6">
            <!-- Tombol Tutup -->
            <button @click="showContactModal = false"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">&times;</button>

            <!-- Judul -->
            <h2 class="text-xl font-semibold mb-4 text-center">Form Laporan ke Admin</h2>

            <!-- Form -->
            <form method="POST" action="{{ route('laporan.store') }}">
                @csrf
                @method('POST')
                @guest
                    <div class="mb-4 space-y-2 mt-6">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" required placeholder="Mohammad Rizky Romadhon"
                            class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                    </div>
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">NIM</label>
                        <input type="text" name="nim" required placeholder="E32222530"
                            class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                    </div>
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                        <select name="id_prodi" required
                            class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                            @foreach ($programStudi as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" required placeholder="rizky@gmail.com"
                            class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500">
                    </div>
                @endguest

                <div class="mb-4 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Pesan / Laporan</label>
                    <textarea name="pesan" rows="4" required placeholder="Kartu RFID Hilang / Rusak"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-gray-500"></textarea>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition cursor-pointer">
                        Kirim
                    </button>
                </div>
            </form>

        </div>
    </div>


    <script>
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
