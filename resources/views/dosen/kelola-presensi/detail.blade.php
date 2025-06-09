@extends('dosen.layouts')

@section('dosen-content')
    {{-- Header Halaman --}}
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                {{-- Tombol kembali ke halaman kelola dengan tanggal yang sama --}}
                <a href="{{ route('dosen.presensi.index', ['tanggal' => $jadwal->tanggal ?? now()->format('Y-m-d')]) }}"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Detail Presensi Kelas</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $jadwal->mataKuliah->name }} -
                        {{ \Carbon\Carbon::parse($jadwal->tanggal ?? now())->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="p-4 sm:p-6 lg:p-8">
        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold dark:text-white">{{ $stats['total'] }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Mahasiswa</p>
            </div>
            <div class="bg-green-100 dark:bg-green-900/50 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['Hadir'] }}</p>
                <p class="text-sm text-green-600 dark:text-green-400">Hadir</p>
            </div>
            <div class="bg-yellow-100 dark:bg-yellow-900/50 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats['Izin/Sakit'] }}</p>
                <p class="text-sm text-yellow-600 dark:text-yellow-400">Izin/Sakit</p>
            </div>
            <div class="bg-red-100 dark:bg-red-900/50 p-4 rounded-lg text-center">
                <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $stats['Tidak Hadir'] }}</p>
                <p class="text-sm text-red-600 dark:text-red-400">Tidak Hadir</p>
            </div>
        </div>

        {{-- Tabel Daftar Mahasiswa --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 text-xs uppercase text-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 w-16">No</th>
                            <th class="px-6 py-3 w-32">NIM</th>
                            <th class="px-6 py-3 w-48">Nama Mahasiswa</th>
                            <th class="px-6 py-3 w-24 text-center">Status</th>
                            <th class="px-6 py-3 w-32 text-center">Waktu Presensi</th>
                            <th class="px-6 py-3 w-36 text-center">Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarHadir as $mahasiswa)
                            <tr class="border-b dark:border-slate-700">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 truncate">{{ $mahasiswa->nim }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $mahasiswa->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($mahasiswa->status_kehadiran == 'Hadir')
                                        <span
                                            class="px-5.5 py-1 font-semibold text-xs leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-800 dark:text-green-100">Hadir</span>
                                    @elseif($mahasiswa->status_kehadiran == 'Izin/Sakit')
                                        <span
                                            class="px-3 py-1 font-semibold text-xs leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-800 dark:text-yellow-100">Izin/Sakit</span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold text-xs leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-800 dark:text-red-100">Tidak
                                            Hadir</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-center">
                                    {{ $mahasiswa->waktu_kehadiran }}</td>
                                <td class="px-6 py-4 text-center relative">
                                    <div class="relative inline-block text-left">
                                        <button onclick="toggleDropdown('dropdown-{{ $mahasiswa->id }}')"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-slate-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-edit text-xs mr-1"></i>
                                            Ubah Status
                                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                        </button>

                                        <div id="dropdown-{{ $mahasiswa->id }}"
                                            class="hidden fixed bg-white dark:bg-slate-800 rounded-md shadow-lg border dark:border-slate-600 z-[9999] w-44"
                                            style="min-width: 180px;">
                                            <div class="py-1">
                                                <form
                                                    action="{{ route('dosen.presensi.updateStatus', ['jadwal' => $jadwal->id, 'tanggal' => $tanggal]) }}"
                                                    method="POST" class="block">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $mahasiswa->id }}">
                                                    <input type="hidden" name="status" value="Hadir">
                                                    <button type="submit"
                                                        class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                                        </i>Ubah ke Hadir
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('dosen.presensi.updateStatus', ['jadwal' => $jadwal->id, 'tanggal' => $tanggal]) }}"
                                                    method="POST" class="block">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $mahasiswa->id }}">
                                                    <input type="hidden" name="status" value="Izin/Sakit">
                                                    <button type="submit"
                                                        class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                                        </i>Ubah
                                                        ke Izin/Sakit
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('dosen.presensi.updateStatus', ['jadwal' => $jadwal->id, 'tanggal' => $tanggal]) }}"
                                                    method="POST" class="block">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $mahasiswa->id }}">
                                                    <input type="hidden" name="status" value="Tidak Hadir">
                                                    <button type="submit"
                                                        class="flex items-center w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                                                        </i>Ubah ke Tidak
                                                        Hadir
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 px-6 text-gray-500 dark:text-gray-400">
                                    <div
                                        class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-users-slash text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    Tidak ada mahasiswa yang terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- JavaScript untuk Dropdown --}}
    <script>
        function toggleDropdown(dropdownId) {
            // Tutup semua dropdown yang lain
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
            allDropdowns.forEach(dropdown => {
                if (dropdown.id !== dropdownId) {
                    dropdown.classList.add('hidden');
                }
            });

            // Toggle dropdown yang diklik
            const dropdown = document.getElementById(dropdownId);
            const button = dropdown.previousElementSibling;

            if (dropdown.classList.contains('hidden')) {
                // Posisikan dropdown
                const buttonRect = button.getBoundingClientRect();
                dropdown.style.left = buttonRect.left + 'px';
                dropdown.style.top = (buttonRect.bottom + 5) + 'px';
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Tutup dropdown ketika klik di luar
        document.addEventListener('click', function(event) {
            const isClickInsideDropdown = event.target.closest('.relative.inline-block');
            if (!isClickInsideDropdown) {
                const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
                allDropdowns.forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        // Tutup dropdown ketika scroll
        window.addEventListener('scroll', function() {
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
            allDropdowns.forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        });
    </script>
@endsection
