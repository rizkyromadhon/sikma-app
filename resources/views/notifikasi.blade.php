<x-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            Semua Notifikasi
        </h1>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="flex flex-col">

                @if (!Auth::user()->is_profile_complete)
                    <a href="{{ route('profile.edit') }}"
                        class="block p-4 bg-yellow-100 dark:bg-yellow-800/30 border-b border-yellow-200 dark:border-yellow-700 hover:bg-yellow-200 dark:hover:bg-yellow-800/50 transition duration-150">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-triangle-exclamation text-xl text-yellow-500"></i>
                            </div>
                            <div class="ml-4 flex-grow">
                                <p class="font-bold text-yellow-800 dark:text-yellow-200">Profil Belum Lengkap</p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">Harap lengkapi data profil Anda
                                    untuk mengakses semua fitur. Klik di sini untuk melengkapi.</p>
                            </div>
                        </div>
                    </a>
                @endif


                @forelse ($notifications as $notification)
                    <a href="{{ route('notifikasi.read', $notification->id) }}"
                        @if ($notification->tipe === 'Tugas Baru') target="_blank" rel="noopener noreferrer" @endif
                        class="block p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                {{-- Logika untuk ikon berdasarkan tipe notifikasi --}}
                                @switch($notification->tipe)
                                    @case('Perkuliahan Ditiadakan')
                                        <i class="fa-solid fa-calendar-xmark text-xl text-red-500"></i>
                                    @break

                                    @case('Izin Diterima')
                                        <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
                                    @break

                                    @case('Izin Ditolak')
                                        <i class="fa-solid fa-circle-xmark text-xl text-red-500"></i>
                                    @break

                                    @default
                                        <i class="fa-solid fa-circle-info text-xl text-blue-500"></i>
                                @endswitch
                            </div>

                            <div class="ml-4 flex-grow">
                                <div class="flex justify-between items-center">
                                    <p class="font-bold text-gray-800 dark:text-gray-100">{{ $notification->tipe }}</p>
                                    @if (is_null($notification->read_at))
                                        <span
                                            class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 rounded-full">Baru</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $notification->konten }}</p>
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                    @empty
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-bell-slash fa-2x mb-2"></i>
                            <p>Anda belum memiliki notifikasi.</p>
                        </div>
                    @endforelse

                </div>

                @if ($notifications->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-layout>
