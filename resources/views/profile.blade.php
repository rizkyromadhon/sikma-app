<x-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        {{-- [DIUBAH] Menambahkan class kondisional untuk border atas berwarna --}}
        <div @class([
            'bg-white dark:bg-slate-800/50 shadow-lg rounded-xl p-6 md:p-8',
        ])>

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-slate-100">
                    Profil Saya
                </h2>

                {{-- [BARU] Visual Pembeda Role --}}
                @if (Auth::user()->role == 'mahasiswa')
                    <span
                        class="text-xs font-semibold inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                        <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                    </span>
                @elseif(Auth::user()->role == 'dosen')
                    <span
                        class="text-xs font-semibold inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Dosen
                    </span>
                @elseif(Auth::user()->role == 'admin')
                    <span
                        class="text-xs font-semibold inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </span>
                @endif
            </div>

            {{-- ============================================= --}}
            {{-- TAMPILAN UNTUK ROLE MAHASISWA --}}
            {{-- ============================================= --}}
            @if (Auth::user()->role == 'mahasiswa')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Kolom Kiri: Foto & Tombol --}}
                    <div class="md:col-span-1 flex flex-col items-center">
                        <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                            alt="Foto Profil"
                            class="w-32 h-40 rounded-lg object-cover mb-4 border-2 border-slate-200 dark:border-slate-600">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-block md:w-full md:mt-5 text-center px-4 py-2 bg-gray-800 text-white dark:text-gray-200 dark-mode-transition rounded-md hover:bg-gray-900 transition text-sm md:text-md">
                            Edit Profil
                        </a>
                    </div>
                    {{-- Kolom Kanan: Detail Info --}}
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Nama
                                Lengkap</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->name }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">NIM</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->nim ?? '-' }}
                            </p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Program
                                Studi</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">
                                {{ optional(Auth::user()->programStudi)->name ?? '-' }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Semester</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">
                                {{ optional(Auth::user()->semester)->display_name ?? '-' }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Golongan</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">
                                {{ optional(Auth::user()->golongan)->nama_golongan ?? '-' }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">No. HP</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->no_hp ?? '-' }}
                            </p>
                        </div>
                        <div class="sm:col-span-2"><label
                                class="block text-xs font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- TAMPILAN UNTUK ROLE DOSEN --}}
                {{-- ============================================= --}}
            @elseif (Auth::user()->role == 'dosen')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Kolom Kiri: Foto & Tombol --}}
                    <div class="md:col-span-1 flex flex-col items-center">
                        <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('img/avatar-default.png') }}"
                            alt="Foto Profil"
                            class="w-32 h-40 rounded-lg object-cover mb-4 border-2 border-slate-200 dark:border-slate-600">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-block md:w-full text-center px-4 py-2 bg-gray-800 text-white dark:text-gray-200 dark-mode-transition rounded-md hover:bg-gray-900 transition text-sm md:text-md">
                            Edit Profil
                        </a>
                    </div>
                    {{-- Kolom Kanan: Detail Info --}}
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Nama
                                Lengkap</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->name }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">NIP</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->nip ?? '-' }}
                            </p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">{{ Auth::user()->email }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Program Studi
                            </label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">
                                {{ optional(Auth::user()->prodi)->name ?? '-' }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">No. HP</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">
                                {{ Auth::user()->no_hp ?? '-' }}</p>
                        </div>
                        <div><label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                            <p class="mt-1 text-base text-gray-900 dark:text-slate-200">
                                {{ Auth::user()->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- TAMPILAN UNTUK ROLE ADMIN --}}
                {{-- ============================================= --}}
            @elseif (Auth::user()->role == 'admin')
                <div class="text-center py-8 px-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                    <div
                        class="w-16 h-16 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-3xl text-slate-500 dark:text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tidak Ada Profil Detail</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Admin Program Studi tidak memiliki profil
                        detail seperti mahasiswa atau dosen.</p>
                </div>
            @endif

        </div>
    </div>
</x-layout>
