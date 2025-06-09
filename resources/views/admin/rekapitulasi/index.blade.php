@extends('admin.dashboard')
@section('admin-content')
    <div x-data="attendanceData()" x-init="init()">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="animate-slide-in">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-200 mb-2">
                        <i class="fas fa-chart-line text-gray-900 dark:text-gray-200 mr-3"></i>
                        Rekapitulasi Kehadiran
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">Kelola dan pantau kehadiran mahasiswa secara real-time</p>
                </div>

                <div class="flex items-center space-x-3">
                    <button @click="showExportPdfModal = true"
                        class="bg-gray-900 dark:bg-red-900/60 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-800 dark:hover:bg-red-900/70 transition-all duration-200 flex items-center space-x-2 hover-scale">
                        <i class="fas fa-file-pdf"></i>
                        <span>Export PDF</span>
                    </button>
                    <button @click="showExportExcelModal = true"
                        class="bg-white dark:bg-green-900/60 border border-gray-300 dark:border-transparent text-gray-700 dark:text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-green-900/70 transition-all duration-200 flex items-center space-x-2 hover-scale">
                        <i class="fas fa-file-excel text-green-600"></i>
                        <span>Export Excel</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dynamic Stats Cards -->
        <div class="px-4 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-900/60 rounded-xl shadow-sm border border-gray-200 dark:border-transparent p-6 hover:shadow-lg transition-all duration-300 hover-scale animate-fade-in flex items-center justify-between gap-8">
                    <div class="flex items-center justify-between w-full">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-200">Total Mahasiswa</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-200 mt-2"
                                x-text="stats.totalMahasiswa"></p>

                        </div>
                        <div
                            class="bg-gradient-to-br from-blue-500 dark:from-blue-900/60 to-blue-600 dark:to-blue-900 px-[18px] py-[15px] rounded-full shadow-lg">
                            <i class="fas fa-user-graduate text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900/60 rounded-xl shadow-sm border border-gray-200 dark:border-transparent p-6 hover:shadow-lg transition-all duration-300 hover-scale animate-fade-in"
                    style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-200">Hadir Hari Ini</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-700 mt-2"
                                x-text="stats.totalHadirToday"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-200 mt-2">
                                <span x-text="stats.persenHadirToday"></span>% dari total
                            <div>
                                <div class="w-full bg-gray-200 dark:bg-gray-300 rounded-full h-2 mt-1">
                                    <div class="bg-green-500 dark:bg-green-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $persenHadirToday }}%;"></div>
                                </div>
                            </div>
                            </p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-500 dark:from-green-800/60 to-green-600 dark:to-green-900 px-[18px] py-[15px] rounded-full shadow-lg">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900/60 rounded-xl shadow-sm border border-gray-200 dark:border-transparent p-6 hover:shadow-lg transition-all duration-300 hover-scale animate-fade-in"
                    style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-200">Tidak Hadir Hari ini</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-700 mt-2"
                                x-text="stats.totalTidakHadirToday"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-200 mt-2">
                                <span x-text="stats.persenTidakHadirToday"></span>% dari total
                            <div>
                                <div class="w-full bg-gray-200 dark:bg-gray-300 rounded-full h-2 mt-1">
                                    <div class="bg-red-500 dark:bg-red-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $persenTidakHadirToday }}%;"></div>
                                </div>
                            </div>
                            </p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-red-500 dark:from-red-900/60 to-red-600 dark:to-red-900 px-[18px] py-[15px] rounded-full shadow-lg">
                            <i class="fas fa-times-circle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900/60 rounded-xl shadow-sm border border-gray-200 dark:border-transparent p-6 hover:shadow-lg transition-all duration-300 hover-scale animate-fade-in"
                    style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-200">Izin/Sakit Hari ini</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-700 mt-2"
                                x-text="stats.totalIzinToday"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-200 mt-2">
                                <span x-text="stats.persenIzinToday"></span>% dari total
                            <div>
                                <div class="w-full bg-gray-200 dark:bg-gray-300 rounded-full h-2 mt-1">
                                    <div class="bg-yellow-500 dark:text-yellow-600 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ $persenIzinToday }}%;"></div>
                                </div>
                            </div>
                            </p>
                        </div>
                        <div
                            class="bg-gradient-to-br from-yellow-500 dark:from-yellow-900/60 to-yellow-600 dark:to-yellow-900 px-[18px] py-[15px] rounded-full shadow-lg">
                            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-900/60 rounded-xl shadow-sm border border-gray-200 dark:border-transparent p-6 mb-6 w-full">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-200 pb-4">Daftar Mahasiswa</h2>
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Cari
                            Mahasiswa</label>
                        <div class="relative">
                            <input type="text" placeholder="Nama atau NIM..." x-model="filters.search"
                                @input.debounce.500ms="applyFilters()"
                                class="text-sm w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-400 placeholder-gray-600/50 dark:placeholder-gray-400/50 focus:border-transparent transition-all duration-200">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Program Studi</label>
                        <select x-model="filters.program" @change="applyFilters()"
                            class="text-sm w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-400 focus:border-transparent transition-all duration-200">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Program Studi</option>
                            <template x-for="prodi in programStudis" :key="prodi.id">
                                <option :value="prodi.id"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    x-text="prodi.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Semester</label>
                        <select x-model="filters.semester" @change="applyFilters()"
                            class="text-sm w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-gray-900 dark:focus:ring-gray-400 focus:border-transparent transition-all duration-200 dark-mode-transition">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Semester</option>
                            <template x-for="semester in semesters" :key="semester.id">
                                <option :value="semester.id"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    x-text="semester.display_name"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="py-4">
                    <div class="flex items-center justify-start">
                        <div class="flex items-center space-x-2">
                            <button @click="handleBulkAction('mark-present')" x-show="selectedStudents.length > 0"
                                class="text-sm text-green-600 dark:text-green-200 hover:text-green-800 dark:hover:text-green-300 transition-colors duration-200 dark-mode-transition"
                                title="Tandai Hadir">
                                <div
                                    class="px-4 py-2 bg-green-100 dark:bg-green-900/60 hover:bg-green-200 dark:hover:bg-green-900/80 transition rounded-md dark-mode-transition">
                                    <span>Tandai Hadir</span>
                                </div>
                            </button>
                            <button @click="handleBulkAction('mark-absent')" x-show="selectedStudents.length > 0"
                                class="text-sm text-red-600 dark:text-red-200 hover:text-red-800 dark:hover:text-red-30 transition-colors duration-200 dark-mode-transition"
                                title="Tandai Tidak Hadir">
                                <div
                                    class="px-4 py-2 bg-red-100 dark:bg-red-900/60 hover:bg-red-200 dark:hover:bg-red-900/80 transition    rounded-md dark-mode-transition">
                                    <span>Tandai Tidak Hadir</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border border-transparent dark:border-gray-700 dark-mode-transition">
                        <thead class="bg-gray-100 dark:bg-gray-900/30 dark-mode-transition">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" @change="toggleSelectAll()"
                                        :checked="selectedStudents.length === students.length && students.length > 0"
                                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 dark-mode-transition uppercase tracking-wider">
                                    Mahasiswa</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 dark-mode-transition uppercase tracking-wider">
                                    Semester</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 dark-mode-transition uppercase tracking-wider">
                                    Program Studi</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-200 dark-mode-transition uppercase tracking-wider">
                                    Status Hari Ini</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-200 dark-mode-transition uppercase tracking-wider">
                                    Waktu</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 dark-mode-transition uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 dark-mode-transition">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div
                                                class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 dark:border-blue-800">
                                            </div>
                                            <span class="text-gray-500 dark:text-gray-200">Memuat data...</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="!isLoading && students.length === 0">
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-200 text-center">
                                        Tidak ada data mahasiswa yang ditemukan.
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && students.length > 0">
                                <template x-for="student in students" :key="student.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/85 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" :value="student.id"
                                                @change="toggleStudent(student.id)"
                                                :checked="selectedStudents.includes(student.id)"
                                                class="rounded border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-200 focus:ring-gray-900 dark:focus:ring-gray-200">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-gray-900 dark:bg-gray-800 flex items-center justify-center text-white font-medium"
                                                    x-text="student.initials">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-200"
                                                        x-text="student.name">
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400"
                                                        x-text="student.nim"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200"
                                            x-text="student.semester"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200"
                                            x-text="student.program_studi_name"></td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span
                                                :class="{
                                                    'bg-green-100 dark:bg-green-900/60 text-green-800 dark:text-green-200 px-2 py-1 w-28 items-center justify-center': student
                                                        .status_kehadiran_hari_ini === 'Hadir',
                                                    'bg-red-100 dark:bg-red-900/60 text-red-800 dark:text-red-200 px-2 py-1 w-28 items-center justify-center': student
                                                        .status_kehadiran_hari_ini === 'Tidak Hadir',
                                                    'bg-yellow-100 dark:bg-yellow-900/60 text-yellow-800 dark:text-yellow-200 px-2 py-1 w-28 items-center justify-center': student
                                                        .status_kehadiran_hari_ini === 'Izin/Sakit' || student
                                                        .status_kehadiran_hari_ini === 'Izin' || student
                                                        .status_kehadiran_hari_ini === 'Sakit',
                                                    'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-2 py-1 w-28 items-center justify-center':
                                                        !
                                                        student
                                                        .status_kehadiran_hari_ini
                                                }"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                                <span
                                                    x-text="student.status_kehadiran_hari_ini || 'Belum Presensi'"></span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-200"
                                            x-text="student.waktu_kehadiran_hari_ini || '-'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="showDetail(student)"
                                                class="text-gray-600 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-300 transition-colors duration-200">Detail</button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-900/80 px-6 py-3 dark-mode-transition"
                    x-show="pagination.totalItems > 0 && students.length > 0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2"> <span
                                class="text-sm text-gray-700 dark:text-gray-200">Tampilkan</span>
                            <select x-model="pagination.perPage" @change="applyFilters()"
                                class="border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded px-2 py-1 text-sm dark-mode-transition">
                                <option value="10"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">10
                                </option>
                                <option value="25"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">25
                                </option>
                                <option value="50"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">50
                                </option>
                                <option value="100"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">100
                                </option>
                            </select>
                            <span class="text-sm text-gray-700 dark:text-gray-200 dark-mode-transition">per halaman</span>
                        </div>
                        <div class="flex items-center space-x-1" x-show="pagination.totalPages > 1">
                            <button @click="changePage(pagination.currentPage - 1)" :disabled="pagination.currentPage <= 1"
                                :class="pagination.currentPage <= 1 ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-50 dark:hover:bg-gray-800'"
                                class="px-3 py-1 border border-gray-300 dark:border-gray-700 rounded text-sm text-gray-700 dark:text-gray-200 transition-colors duration-200">
                                &lt;
                            </button>
                            <template x-for="page in paginationPages" :key="page">
                                <button @click="changePage(page)"
                                    :class="page === pagination.currentPage ?
                                        'bg-gray-900 dark:bg-gray-700 text-white dark:text-gray-200' :
                                        'border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 dark-mode-transition'"
                                    class="px-3 py-1 rounded text-sm transition-colors duration-200"
                                    x-text="page"></button>
                            </template>
                            <button @click="changePage(pagination.currentPage + 1)"
                                :disabled="pagination.currentPage >= pagination.totalPages"
                                :class="pagination.currentPage >= pagination.totalPages ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-50 dark:hover:bg-gray-800'"
                                class="px-3 py-1 border border-gray-300 dark:border-gray-700 rounded text-sm text-gray-700 dark:text-gray-200 transition-colors duration-200">
                                &gt;
                            </button>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-300 mt-4 text-right"
                        x-show="pagination.totalItems > 0 && students.length > 0">
                        Menampilkan <span x-text="pagination.from"></span> sampai <span x-text="pagination.to"></span>
                        dari <span x-text="pagination.totalItems"></span> hasil
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-2/3">
                    <div
                        class="bg-white dark:bg-gray-900/80 dark:border-transparent rounded-xl shadow-sm border border-gray-200 p-6 w-full">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200 mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-4 text-blue-600 dark:text-blue-500"></i>
                            Tren Kehadiran
                        </h3>
                        <div class="space-y-4">
                            <div
                                class="h-[430px] w-full bg-gradient-to-br from-blue-50 dark:from-blue-800/60 to-indigo-100 dark:to-indigo-900/60 rounded-lg flex items-center justify-center border border-blue-200 dark:border-transparent">
                                <div class="text-center">
                                    <i class="fas fa-chart-line text-4xl text-blue-500 dark:text-blue-500 mb-3"></i>
                                    <p class="text-lg text-blue-600 dark:text-blue-400 font-medium">Grafik Kehadiran
                                        Mingguan</p>
                                    <p class="text-sm text-blue-400 dark:text-blue-200">(Data real-time - Placeholder untuk
                                        grafik aktual)</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div
                                    class="text-center p-3 bg-green-50 dark:bg-green-900/60 rounded-lg border border-green-200 dark:border-green-700">
                                    <p class="text-2xl font-bold text-green-700 dark:text-green-400"
                                        x-text="stats.persenRataRataHadirMingguan + '%'"></p>
                                    <p class="text-xs text-green-600 dark:text-green-300">Rata-rata hadir minggu ini</p>
                                </div>
                                <div
                                    class="text-center p-3 bg-blue-50 dark:bg-blue-900/60 rounded-lg border border-blue-200 dark:border-blue-700">
                                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-400"
                                        x-text="stats.weeklyGrowthDisplay"></p>
                                    <p class="text-xs text-blue-600 dark:text-blue-300">Dari minggu lalu (Placeholder)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/3 flex flex-col gap-6">
                    <div
                        class="bg-white dark:bg-gray-900/60 dark:border-transparent dark-mode-transition rounded-xl shadow-sm border border-gray-200 overflow-hidden flex-1">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 dark-mode-transition bg-gradient-to-r from-green-50 dark:from-green-900/60 to-teal-50 dark:to-green-800">
                            <h3
                                class="text-md font-semibold text-gray-900 dark:text-gray-200 dark-mode-transition flex items-center gap-3">
                                <i
                                    class="fas fa-trophy text-yellow-500 dark:text-yellow-400 dark-mode-transition text-lg"></i>
                                Kehadiran Terbaik Mahasiswa
                            </h3>
                        </div>
                        <div class="p-4 space-y-2 h-full max-h-60 overflow-y-auto custom-scrollbar">
                            <template x-if="!topStudents || topStudents.length === 0">
                                <p class="text-sm text-gray-500 dark:text-gray-300 text-center py-4">Data kehadiran terbaik
                                    belum tersedia.
                                </p>
                            </template>
                            <template x-for="(student, index) in topStudents" :key="student.id">
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-100/80 dark:hover:bg-gray-900/80 dark-mode-transition rounded-lg transition-colors duration-150 ease-in-out">
                                    <div class="flex items-center">
                                        <span
                                            class="text-xs font-semibold text-gray-500 dark:text-gray-200 dark-mode-transition w-7 text-center mr-2 tabular-nums"
                                            x-text="index + 1 + '.'"></span>
                                        <div class="h-9 w-9 rounded-full bg-teal-600 dark:bg-teal-900 dark-mode-transition flex items-center justify-center text-white text-sm font-semibold mr-3 flex-shrink-0"
                                            x-text="student.initials ? student.initials : (student.name ? student.name.substring(0,1).toUpperCase() : '?')">
                                        </div>
                                        <div class="flex-grow">
                                            <p class="text-sm text-gray-800 dark:text-gray-200 dark-mode-transition font-semibold leading-tight"
                                                x-text="student.name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 dark-mode-transition leading-tight"
                                                x-text="student.nim"></p>
                                            <p class="text-xs text-teal-700 dark:text-teal-500 dark-mode-transition leading-tight"
                                                x-text="'Hadir: ' + student.hadir_count + ' dari ' + student.total_relevant_days + ' hari relevan'">
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-green-600 dark:text-green-400 dark-mode-transition tabular-nums"
                                        x-text="parseFloat(student.attendance_percentage).toFixed(1) + '%'">
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-900/60 rounded-xl shadow-sm border dark:border-transparent border-gray-200 overflow-hidden flex-1 dark-mode-transition">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-transparent bg-gradient-to-r from-purple-50 dark:from-purple-900/60 to-white dark:to-purple-800 dark-mode-transition">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-gray-200 flex items-center gap-3">
                                <i
                                    class="fas fa-history text-purple-600 dark:text-gray-200 text-md dark-mode-transition"></i>
                                Aktivitas Terbaru
                            </h3>
                        </div>

                        <div class="p-4 space-y-2 h-full max-h-60 overflow-y-auto custom-scrollbar">
                            <template x-if="recentActivities.length === 0">
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300 dark-mode-transition text-center items-center justify-center py-4">
                                    Tidak ada aktivitas
                                    terbaru.</p>
                            </template>

                            <template x-for="activity in recentActivities" :key="activity.id">
                                {{-- Konten Aktivitas Terbaru akan dirender di sini jika ada data --}}
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 overflow-y-auto"
            @click.self="closeDetailModal()" style="display: none;" {{-- Tetap gunakan ini untuk mencegah flicker awal --}}>

            {{-- Panel Modal Utama --}}
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[95vh] flex flex-col" {{-- HAPUS x-show="showDetailModal" DARI SINI --}}
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.stop>

                {{-- Header Modal --}}
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Mahasiswa</h3>
                    <button @click="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Konten Modal (Scrollable) --}}
                <div class="p-6 space-y-6 overflow-y-auto flex-grow">
                    {{-- Konten tetap menggunakan x-if untuk memastikan selectedStudent ada datanya --}}
                    <template x-if="selectedStudent">
                        <div> {{-- Wrapper tambahan untuk konten --}}
                            {{-- Info Dasar Mahasiswa --}}
                            <div class="flex items-center space-x-4">
                                <div
                                    class="h-16 w-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                    <span x-text="selectedStudent.initials"></span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900" x-text="selectedStudent.name"></h4>
                                    <p class="text-sm text-gray-600" x-text="selectedStudent.nim"></p>
                                    <p class="text-xs text-gray-500"
                                        x-text="(selectedStudent.program || '') + (selectedStudent.semester ? ' - Semester ' + selectedStudent.semester : '')">
                                    </p>
                                </div>
                            </div>

                            {{-- Informasi Kehadiran & Status Hari Ini (Grid) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                {{-- Informasi Kehadiran (Kiri) --}}
                                <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Informasi Kehadiran</h5>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Hadir:</span>
                                        <span class="font-medium text-gray-800"
                                            x-text="selectedStudent.totalPresent || 0"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Tidak Hadir:</span>
                                        <span class="font-medium text-gray-800"
                                            x-text="selectedStudent.totalAbsent || 0"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Izin/Sakit:</span>
                                        <span class="font-medium text-gray-800"
                                            x-text="selectedStudent.totalExcused || 0"></span>
                                    </div>
                                    <div class="flex justify-between text-sm border-t border-gray-300 pt-2 mt-2">
                                        <span class="text-gray-600">Persentase Kehadiran:</span>
                                        <span class="font-bold text-blue-600"
                                            x-text="(selectedStudent.attendanceRate !== undefined ? selectedStudent.attendanceRate + '%' : '0%')"></span>
                                    </div>
                                </div>

                                {{-- Status Hari Ini (Kanan) --}}
                                <div class="bg-gray-50 rounded-lg p-4 text-center border border-gray-200">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">Status Hari Ini</h5>
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mb-1"
                                        :class="{
                                            'bg-green-100 text-green-700': selectedStudent.status === 'Hadir',
                                            'bg-red-100 text-red-700': selectedStudent.status === 'Tidak Hadir',
                                            'bg-yellow-100 text-yellow-700': selectedStudent.status === 'Izin' ||
                                                selectedStudent.status === 'Sakit' || selectedStudent
                                                .status === 'Izin/Sakit',
                                            'bg-gray-200 text-gray-700': !selectedStudent.status || selectedStudent
                                                .status === 'Belum Presensi'
                                        }">
                                        <i class="mr-1.5 text-xs"
                                            :class="{
                                                'fas fa-check': selectedStudent.status === 'Hadir',
                                                'fas fa-times': selectedStudent.status === 'Tidak Hadir',
                                                'fas fa-exclamation-triangle': selectedStudent.status === 'Izin' ||
                                                    selectedStudent.status === 'Sakit' || selectedStudent
                                                    .status === 'Izin/Sakit',
                                                'fas fa-minus-circle': !selectedStudent.status || selectedStudent
                                                    .status === 'Belum Presensi'
                                            }"></i>
                                        <span x-text="selectedStudent.status || 'Belum Presensi'"></span>
                                    </div>
                                    <p class="text-xs text-gray-500"
                                        x-text="selectedStudent.time ? 'Waktu: ' + selectedStudent.time : ''">
                                    </p>
                                </div>
                            </div>

                            {{-- Riwayat Kehadiran Minggu Ini --}}
                            <div class="mt-6">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2">Riwayat Kehadiran Minggu ini</h5>
                                <div class="flex flex-row gap-2 w-full">
                                    <template x-for="day in selectedStudent.attendanceHistory || []"
                                        :key="day.date">
                                        {{-- Konten riwayat per hari --}}
                                        <div class="text-center p-2 rounded-lg border text-xs flex-1"
                                            :class="{
                                                'bg-green-100 border-green-300 text-green-700': day
                                                    .status === 'Hadir',
                                                'bg-red-100 border-red-300 text-red-700': day.status === 'Tidak Hadir',
                                                'bg-yellow-100 border-yellow-300 text-yellow-700': day
                                                    .status === 'Izin' || day.status === 'Sakit' || day
                                                    .status === 'Izin/Sakit',
                                                'bg-gray-100 border-gray-300 text-gray-500': !day.status || day
                                                    .status === 'Belum Presensi'
                                            }">
                                            <div class="font-medium uppercase" x-text="day.dayName"></div>
                                            <div class="my-1.5">
                                                <i
                                                    :class="{
                                                        'fas fa-check text-base': day.status === 'Hadir',
                                                        'fas fa-times text-base': day.status === 'Tidak Hadir',
                                                        'fas fa-exclamation-triangle text-base': day
                                                            .status === 'Izin' || day.status === 'Sakit' || day
                                                            .status === 'Izin/Sakit',
                                                        'fas fa-minus text-base': !day.status || day
                                                            .status === 'Belum Presensi'
                                                    }"></i>
                                            </div>
                                            <div x-text="day.date"></div>
                                        </div>
                                    </template>
                                    <template
                                        x-if="!selectedStudent.attendanceHistory || selectedStudent.attendanceHistory.length === 0">
                                        <p class="w-full text-center text-gray-500 text-sm py-4">Data riwayat tidak
                                            tersedia.</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                {{-- Akhir Konten Modal --}}
            </div>
        </div>

        <div x-show="showExportPdfModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 overflow-y-auto"
            @click.self="closeExportPdfModal()" style="display: none;">

            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[95vh] flex flex-col"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.stop>

                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-xl font-semibold text-gray-900">Export Rekapitulasi Kehadiran ke Pdf</h3>
                    <button @click="closeExportPdfModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto flex-grow">
                    <div>
                        <label for="exportProgram" class="block text-sm font-medium text-gray-700 mb-2">Program
                            Studi</label>
                        <select id="exportProgram" x-model="exportFilters.program"
                            class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Program Studi</option>
                            <template x-for="prodi in programStudis" :key="prodi.id">
                                <option :value="prodi.id" x-text="prodi.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="exportSemester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select id="exportSemester" x-model="exportFilters.semester" @change="updateMonthRangeOptions()"
                            class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Semester</option>
                            <template x-for="semester in semesters" :key="semester.id">
                                <option :value="semester.id" x-text="semester.display_name"></option>
                            </template>
                        </select>
                    </div>

                    <div x-show="exportFilters.semester">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Bulan</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="exportMonthFrom" class="sr-only">Bulan Mulai</label>
                                <select id="exportMonthFrom" x-model="exportFilters.monthFrom"
                                    class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                                    <option value="">Pilih Bulan Mulai</option>
                                    {{-- Menggunakan filteredMonthOptions --}}
                                    <template x-for="month in filteredMonthOptions" :key="month.value">
                                        <option :value="month.value" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label for="exportMonthTo" class="sr-only">Bulan Akhir</label>
                                <select id="exportMonthTo" x-model="exportFilters.monthTo"
                                    class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                                    <option value="">Pilih Bulan Akhir</option>
                                    {{-- Menggunakan filteredMonthOptions --}}
                                    <template x-for="month in filteredMonthOptions" :key="month.value">
                                        <option :value="month.value" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <p x-show="!exportFilters.semester" class="text-sm text-gray-500 mt-2">Pilih semester terlebih
                            dahulu untuk menentukan rentang bulan.</p>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 flex-shrink-0">
                    <button @click="closeExportPdfModal()"
                        class="px-4 py-2 bg-transparent text-sm text-gray-800 border rounded-lg hover:bg-gray-800 hover:text-white transition-colors">Batal</button>
                    <button @click="triggerExportPdf()"
                        class="px-4 py-2 bg-red-600 text-sm text-white rounded-lg hover:bg-red-700 transition-colors">Export</button>
                </div>
            </div>
        </div>

        <div x-show="showExportExcelModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 overflow-y-auto"
            @click.self="closeExportExcelModal()" style="display: none;">

            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[95vh] flex flex-col"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.stop>

                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0">
                    <h3 class="text-xl font-semibold text-gray-900">Export Rekapitulasi Kehadiran ke Excel</h3>
                    <button @click="closeExportExcelModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto flex-grow">
                    <div>
                        <span class="text-red-800 text-xs mb-2">* Filter export excel
                            tidak bisa
                            menggunakan semua semester.</span>
                    </div>
                    <div>
                        <label for="exportProgram" class="block text-sm font-medium text-gray-700 mb-2">Program
                            Studi</label>
                        <select id="exportProgram" x-model="exportFilters.program"
                            class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Program Studi</option>
                            <template x-for="prodi in programStudis" :key="prodi.id">
                                <option :value="prodi.id" x-text="prodi.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="exportSemester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select id="exportSemester" x-model="exportFilters.semester" @change="updateMonthRangeOptions()"
                            class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                            <template x-for="semester in semesters" :key="semester.id">
                                <option :value="semester.id" x-text="semester.display_name"></option>
                            </template>
                        </select>
                    </div>

                    <div x-show="exportFilters.semester">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Bulan</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="exportMonthFrom" class="sr-only">Bulan Mulai</label>
                                <select id="exportMonthFrom" x-model="exportFilters.monthFrom"
                                    class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                                    <option value="">Pilih Bulan Mulai</option>
                                    <template x-for="month in filteredMonthOptions" :key="month.value">
                                        <option :value="month.value" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label for="exportMonthTo" class="sr-only">Bulan Akhir</label>
                                <select id="exportMonthTo" x-model="exportFilters.monthTo"
                                    class="text-sm w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-200">
                                    <option value="">Pilih Bulan Akhir</option>
                                    <template x-for="month in filteredMonthOptions" :key="month.value">
                                        <option :value="month.value" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <p x-show="!exportFilters.semester" class="text-sm text-gray-500 mt-2">Pilih semester terlebih
                            dahulu untuk menentukan rentang bulan.</p>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 flex-shrink-0">
                    <button @click="closeExportExcelModal()"
                        class="px-4 py-2 bg-transparent border text-sm text-gray-800 rounded-lg hover:bg-gray-800 hover:text-white transition-colors">Batal</button>
                    <button @click="triggerExportExcel()"
                        class="px-4 py-2 bg-green-600 text-sm text-white rounded-lg hover:bg-green-800 transition-colors">Export</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function attendanceData() {
            return {
                isLoading: false,
                showDetailModal: false,
                selectedStudent: null,
                selectedStudents: [], // student IDs
                showExportPdfModal: false,
                showExportExcelModal: false,

                // Data Stats (loaded from PHP initially)
                stats: {
                    totalMahasiswa: 0,
                    totalHadirToday: 0,
                    totalTidakHadirToday: 0,
                    totalIzinToday: 0,
                    persenHadirToday: 0,
                    persenTidakHadirToday: 0,
                    persenIzinToday: 0,
                    persenRataRataHadirMingguan: 85, // default
                    weeklyGrowthDisplay: '+0.0%' // default
                },

                // Filters
                filters: {
                    search: '',
                    program: '',
                    semester: '',
                    dateFrom: '', // Consider setting default, e.g., start of month
                    dateTo: '' // Consider setting default, e.g., today
                },

                exportFilters: {
                    program: '',
                    semester: '',
                    monthFrom: '',
                    monthTo: '',
                },

                // Data Arrays
                students: [], // This will be populated by API
                programStudis: [], // Populated by loadServerData
                semesters: [], // Populated by loadServerData
                topStudents: [], // Placeholder
                recentActivities: [], // Placeholder

                monthNames: [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ],

                // Pagination
                pagination: {
                    currentPage: 1,
                    perPage: 10, // Default per page
                    totalPages: 1,
                    totalItems: 0,
                    from: 0,
                    to: 0
                },

                init() {
                    this.loadInitialData();
                    // Set default dateTo to today and dateFrom to start of current month for better UX
                    const today = new Date();
                    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    this.filters.dateTo = today.toISOString().split('T')[0];
                    this.filters.dateFrom = firstDayOfMonth.toISOString().split('T')[0];
                    this.$watch('showExportExcelModal', (isShown) => {
                        if (isShown) {
                            this.prepareExcelExportFilters();
                        }
                    });
                },

                prepareExcelExportFilters() {
                    const currentSemesterIsValidForExcel = this.semesters.some(s => s.id == this.exportFilters.semester &&
                        this.exportFilters.semester !== "");

                    if (!currentSemesterIsValidForExcel) {
                        if (this.semesters.length > 0) {
                            const semester1 = this.semesters.find(s => s.display_name === 'Semester 1');
                            if (semester1) {
                                this.exportFilters.semester = semester1.id;
                            } else {
                                this.exportFilters.semester = this.semesters[0].id;
                            }
                        } else {
                            this.exportFilters.semester = '';
                        }
                    }
                    this.updateMonthRangeOptions();
                },

                async loadInitialData() {
                    this.isLoading = true;
                    try {
                        this.loadServerData();
                        this.$nextTick(() => {
                            if (this.semesters.length > 0) {
                                const semester1 = this.semesters.find(s => s.display_name === 'Semester 1');

                                if (semester1) {
                                    this.exportFilters.semester = semester1.id;
                                    this
                                        .updateMonthRangeOptions();
                                } else {

                                    this.exportFilters.semester = this.semesters[0].id;
                                    this.updateMonthRangeOptions();
                                }
                            }
                        });
                        await this.loadStudents();
                        // await this.loadTopStudents(); // Placeholder
                        // await this.loadRecentActivities(); // Placeholder
                    } catch (error) {
                        console.error('Error loading initial data:', error);
                        alert('Gagal memuat data awal. Silakan coba lagi.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                loadServerData() {
                    @if (isset($programStudis))
                        this.programStudis = @json($programStudis);
                    @endif

                    @if (isset($semesters))
                        this.semesters = @json($semesters);
                    @endif

                    @if (isset($topStudentsData))
                        this.topStudents = @json($topStudentsData);
                    @else
                        this.topStudents = [];
                    @endif

                    this.stats = {
                        ...this.stats, // Keep any defaults if not overridden
                        totalMahasiswa: {{ $totalMahasiswa ?? 0 }},
                        totalHadirToday: {{ $totalHadirToday ?? 0 }},
                        totalTidakHadirToday: {{ $totalTidakHadirToday ?? 0 }},
                        totalIzinToday: {{ $totalIzinToday ?? 0 }},
                        persenHadirToday: {{ $persenHadirToday ?? 0 }},
                        persenTidakHadirToday: {{ $persenTidakHadirToday ?? 0 }},
                        persenIzinToday: {{ $persenIzinToday ?? 0 }},
                        persenRataRataHadirMingguan: {{ $persenRataRataHadirMingguan ?? 85 }},
                        // weeklyGrowthDisplay can be calculated/passed from PHP if needed
                    };

                    // Set filters from request if they were passed by PHP (e.g., on page reload with query params)
                    @if (isset($filters) && is_array($filters))
                        this.filters = {
                            search: '{{ $filters['search'] ?? '' }}',
                            program: '{{ $filters['program'] ?? '' }}',
                            semester: '{{ $filters['semester'] ?? '' }}',
                            dateFrom: '{{ $filters['dateFrom'] ?? '' }}',
                            dateTo: '{{ $filters['dateTo'] ?? '' }}'
                        };
                    @endif

                    this.exportFilters.program = this.filters.program;
                    this.exportFilters.semester = this.filters.semester;
                    this.updateMonthRangeOptions();
                },

                async loadStudents() {
                    this.isLoading = true;
                    this.selectedStudents = []; // Clear selection on reload

                    try {
                        const params = new URLSearchParams({
                            search: this.filters.search,
                            program: this.filters.program,
                            semester: this.filters.semester,
                            dateFrom: this.filters.dateFrom,
                            dateTo: this.filters.dateTo,
                            perPage: this.pagination.perPage,
                            page: this.pagination.currentPage
                        });

                        //                            IMPORTANT: Replace with your actual route
                        const response = await fetch(`{{ route('admin.rekapitulasi.filter') }}?${params.toString()}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Not typically needed for GET
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Network response was not ok: ${response.statusText}`);
                        }

                        const data = await response.json();

                        this.students = data.students.data.map(student => ({
                            ...student,
                            initials: this.getInitials(student.name),
                        }));

                        this.pagination = {
                            ...this.pagination,
                            currentPage: data.students.current_page,
                            totalPages: data.students.last_page,
                            totalItems: data.students.total,
                            from: data.students.from || 0,
                            to: data.students.to || 0,
                            perPage: parseInt(data.students.per_page) // Ensure perPage is an int
                        };


                    } catch (error) {
                        console.error('Error loading students:', error);
                        alert('Gagal memuat daftar mahasiswa: ' + error.message);
                        this.students = []; // Clear students on error
                        this.pagination.totalItems = 0; // Reset pagination
                    } finally {
                        this.isLoading = false;
                    }
                },
                getInitials(name) {
                    if (!name || typeof name !== 'string') return '??';
                    return name.split(' ')
                        .map(word => word.charAt(0))
                        .join('')
                        .toUpperCase()
                        .substring(0, 2);
                },


                async loadRecentActivities() {
                    // Placeholder - Implement API call if needed
                    this
                        .recentActivities = [{
                            id: 1,
                            description: 'Mahasiswa X ditandai Hadir',
                            time: '10 menit lalu'
                        }, ];
                },

                applyFilters() {
                    this.pagination.currentPage = 1;
                    this.loadStudents();
                },

                changePage(page) {
                    if (page >= 1 && page <= this.pagination.totalPages && page !== this.pagination.currentPage) {
                        this.pagination.currentPage = page;
                        this.loadStudents();
                    }
                },

                get paginationPages() {
                    const pages = [];
                    const total = this.pagination.totalPages;
                    const current = this.pagination.currentPage;
                    const maxPagesToShow = 5;
                    const halfMax = Math.floor(maxPagesToShow / 2);

                    if (total <= maxPagesToShow) {
                        for (let i = 1; i <= total; i++) pages.push(i);
                    } else {
                        let start = Math.max(1, current - halfMax);
                        let end = Math.min(total, current + halfMax);

                        if (current <= halfMax) {
                            end = maxPagesToShow;
                        } else if (current + halfMax >= total) {
                            start = total - maxPagesToShow + 1;
                        }
                        for (let i = start; i <= end; i++) pages.push(i);
                    }
                    return pages;
                },

                toggleSelectAll() {
                    if (this.selectedStudents.length === this.students.length && this.students.length > 0) {
                        this.selectedStudents = [];
                    } else {
                        this.selectedStudents = this.students.map(s => s.id);
                    }
                },

                toggleStudent(studentId) {
                    const index = this.selectedStudents.indexOf(studentId);
                    if (index > -1) {
                        this.selectedStudents.splice(index, 1);
                    } else {
                        this.selectedStudents.push(studentId);
                    }
                },

                handleBulkAction(action) {
                    if (this.selectedStudents.length === 0) {
                        alert('Pilih mahasiswa terlebih dahulu.');
                        return;
                    }

                    let confirmMessage = '';
                    switch (action) {
                        case 'mark-present':
                            confirmMessage =
                                `Tandai ${this.selectedStudents.length} mahasiswa sebagai "Hadir" untuk hari ini?`;
                            break;
                        case 'mark-absent':
                            confirmMessage =
                                `Tandai ${this.selectedStudents.length} mahasiswa sebagai "Tidak Hadir" untuk hari ini?`;
                            break;
                        case 'mark-excused':
                            confirmMessage =
                                `Tandai ${this.selectedStudents.length} mahasiswa sebagai "Izin/Sakit" untuk hari ini?`;
                            break;
                        default:
                            alert('Aksi tidak dikenal.');
                            return;
                    }

                    if (confirm(confirmMessage)) {
                        this.submitBulkAction(action);
                    }
                },

                async submitBulkAction(action) {
                    this.isLoading = true;
                    try {
                        const response = await fetch(`{{ route('admin.rekapitulasi.bulk_action') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                action: action,
                                student_ids: this.selectedStudents
                            })
                        });

                        const result = await response.json();

                        if (!response.ok) {
                            throw new Error(result.message || `Gagal melakukan aksi massal (HTTP ${response.status})`);
                        }

                        if (result.success) {
                            alert(result.message || 'Aksi berhasil dilakukan.');
                            this.selectedStudents = [];
                            await this.loadStudents();

                        } else {
                            alert(result.message || 'Terjadi kesalahan saat melakukan aksi.');
                        }
                    } catch (error) {
                        console.error('Bulk action error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                    } finally {
                        this.isLoading = false;
                    }
                },

                showDetail(student) {

                    this.selectedStudent = student;
                    this.showDetailModal = true;

                },

                closeDetailModal() {
                    this.showDetailModal = false;
                    setTimeout(() => {
                        this.selectedStudent = null;
                    }, 250);
                },

                closeExportPdfModal() {
                    this.showExportPdfModal = false;
                    this.exportFilters.program = ''; // Reset filters when closing
                    this.exportFilters.semester = ''; // Reset filters when closing
                    this.exportFilters.monthFrom = ''; // Reset month filters
                    this.exportFilters.monthTo = ''; // Reset month filters
                },

                closeExportExcelModal() {
                    this.showExportExcelModal = false;
                    setTimeout(() => {
                        this.exportFilters.program = ''; // Reset filters when closing
                        this.exportFilters.semester = ''; // Reset filters when closing
                        this.exportFilters.monthFrom = ''; // Reset month filters
                        this.exportFilters.monthTo = ''; // Reset month filters
                    }, 250);
                },

                updateMonthRangeOptions() {
                    this.exportFilters.monthFrom = '';
                    this.exportFilters.monthTo = '';

                    if (this.exportFilters.semester) {
                        const selectedSemester = this.semesters.find(s => s.id == this.exportFilters.semester);
                        if (selectedSemester && selectedSemester.start_date && selectedSemester.end_date) {
                            const startMonthNum = new Date(selectedSemester.start_date).getMonth() + 1;
                            const endMonthNum = new Date(selectedSemester.end_date).getMonth() + 1;

                            this.exportFilters.monthFrom = startMonthNum;
                            this.exportFilters.monthTo = endMonthNum;
                        }
                    }
                },

                getMinMonth() {
                    if (!this.exportFilters.semester) return 1;
                    const selectedSemester = this.semesters.find(s => s.id == this.exportFilters.semester);
                    // Pastikan start_date ada sebelum mencoba parse
                    return selectedSemester && selectedSemester.start_date ? new Date(selectedSemester.start_date)
                        .getMonth() + 1 : 1;
                },

                getMaxMonth() {
                    if (!this.exportFilters.semester) return 12;
                    const selectedSemester = this.semesters.find(s => s.id == this.exportFilters.semester);
                    // Pastikan end_date ada sebelum mencoba parse
                    return selectedSemester && selectedSemester.end_date ? new Date(selectedSemester.end_date).getMonth() +
                        1 : 12;
                },

                get filteredMonthOptions() {
                    // Jika tidak ada semester yang dipilih di filter export, tampilkan semua bulan
                    if (!this.exportFilters.semester) {
                        return this.monthNames.map((name, index) => ({
                            name: name,
                            value: index + 1
                        }));
                    }

                    const selectedSemesterObj = this.semesters.find(s => s.id == this.exportFilters.semester);
                    // Jika semester yang dipilih tidak ditemukan atau tidak punya tanggal, tampilkan semua bulan sebagai fallback
                    if (!selectedSemesterObj || !selectedSemesterObj.start_date || !selectedSemesterObj.end_date) {
                        return this.monthNames.map((name, index) => ({
                            name: name,
                            value: index + 1
                        }));
                    }

                    const minMonth = new Date(selectedSemesterObj.start_date).getMonth() + 1;
                    const maxMonth = new Date(selectedSemesterObj.end_date).getMonth() + 1;

                    let availableMonths = [];

                    if (minMonth <= maxMonth) {
                        // Kasus 1: Rentang bulan dalam tahun kalender yang sama (misal: Februari - Juli)
                        for (let m = minMonth; m <= maxMonth; m++) {
                            availableMonths.push({
                                name: this.monthNames[m - 1],
                                value: m
                            });
                        }
                    } else {
                        for (let m = minMonth; m <= 12; m++) {
                            availableMonths.push({
                                name: this.monthNames[m - 1],
                                value: m
                            });
                        }
                        // Bulan dari Januari hingga maxMonth
                        for (let m = 1; m <= maxMonth; m++) {
                            // Hindari duplikasi jika sudah ada di loop pertama (seharusnya tidak terjadi dengan minMonth > maxMonth)
                            if (!availableMonths.some(existing => existing.value === m)) {
                                availableMonths.push({
                                    name: this.monthNames[m - 1],
                                    value: m
                                });
                            }
                        }
                    }
                    return availableMonths;
                },


                triggerExportPdf() {
                    const params = new URLSearchParams({
                        program: this.exportFilters.program,
                        semester: this.exportFilters.semester,
                        monthFrom: this.exportFilters.monthFrom,
                        monthTo: this.exportFilters.monthTo,
                    }).toString();

                    window.open(`{{ route('admin.rekapitulasi.export.pdf') }}?${params}`, '_blank');
                    this.closeExportPdfModal();
                },
                triggerExportExcel() {
                    const params = new URLSearchParams({
                        program: this.exportFilters.program,
                        semester: this.exportFilters.semester,
                        monthFrom: this.exportFilters.monthFrom,
                        monthTo: this.exportFilters.monthTo,
                    }).toString();

                    window.open(`{{ route('admin.rekapitulasi.export.excel') }}?${params}`, '_blank');
                    this.closeExportExcelModal();
                },
            }
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
        }

        .animate-slide-in {
            animation: slide-in 0.8s ease-out forwards;
        }

        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection
