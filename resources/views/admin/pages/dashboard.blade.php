@extends('admin.dashboard')

@section('admin-content')
    <!-- Header -->
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 px-8 py-4.5 dark-mode-transition">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Dashboard</h1>
    </div>

    <div class="container mx-auto p-6 bg-white dark:bg-black dark-mode-transition">
        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div
                class="bg-white dark:bg-gray-900/80 dark-mode-transition rounded-lg shadow p-4 hover:shadow-lg transition flex items-center gap-6 border-t-blue-300 dark:border-t-blue-600/40 border-t-4">
                <div class="px-4.5 py-4 rounded-full bg-blue-100 dark:bg-blue-600/40 dark-mode-transition">
                    <i class="fa-solid fa-user-graduate text-3xl text-blue-500 dark:text-blue-400 dark-mode-transition"></i>
                </div>
                <div>
                    <h2 class="text-md text-gray-500 dark:text-gray-200 dark-mode-transition mb-2">Total Mahasiswa</h2>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-500">{{ $jumlahMahasiswa }}</p>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-900/80 rounded-lg shadow p-4 hover:shadow-lg transition flex items-center gap-6 border-t-green-300 dark:border-t-green-600/40 border-t-4 dark-mode-transition">
                <div class="px-4.5 py-4 rounded-full bg-green-100 dark:bg-green-600/40 dark-mode-transition">
                    <i class="fa-solid fa-user-tie text-3xl text-green-500 dark:text-green-400 dark-mode-transition"></i>
                </div>
                <div>
                    <h2 class="text-md text-gray-500 dark:text-gray-200 dark-mode-transition mb-2">Total Dosen</h2>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-500 dark-mode-transition">
                        {{ $jumlahDosen }}</p>
                </div>
            </div>
        </div>

        <!-- Chart dan Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Mahasiswa per Semester -->
            <div
                class="bg-white dark:bg-gray-900/80 rounded-lg shadow p-4 hover:shadow-lg transition border-t-blue-300 dark:border-t-blue-600/40 border-t-4">
                <h3 class="font-semibold text-lg mb-4 text-gray-700 dark:text-gray-300 dark-mode-transition">Jumlah
                    Mahasiswa per Semester</h3>
                <div id="chartSemester" class="h-48 rounded-lg flex items-center justify-center pr-4">
                </div>
            </div>

            <!-- Grafik Mahasiswa per Program Studi -->
            <div
                class="bg-white dark:bg-gray-900/80 rounded-lg shadow hover:shadow-lg transition border-t-blue-300 dark:border-t-blue-600/40 border-t-4">
                <h3 class="font-semibold text-lg mb-4 text-gray-700 dark:text-gray-300 dark-mode-transition p-4">Jumlah
                    Mahasiswa per Program Studi</h3>
                <div class="flex items-center justify-center w-full">
                    <div id="chartProdi" class="h-48 pl-6"></div>
                    <div id="pie-chart" class="h-48"></div>
                </div>
            </div>
        </div>

        <!-- Data Dosen dan Program Studi -->
        <div
            class="bg-white dark:bg-gray-900/80 rounded-lg shadow p-6 mb-8 hover:shadow-lg transition border-t-green-300 dark:border-t-green-600/40 border-t-4 dark-mode-transition">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 dark:text-gray-300 dark-mode-transition">Jumlah Dosen per
                Program Studi</h3>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <table
                        class="w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 dark-mode-transition">
                        <thead class="bg-gray-100 dark:bg-gray-900/80 dark-mode-transition">
                            <tr>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300 dark-mode-transition uppercase">
                                    Program
                                    Studi</th>
                                <th
                                    class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300 dark-mode-transition uppercase">
                                    Jumlah Dosen
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white dark:bg-gray-800/80 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-300 dark-mode-transition">
                            @forelse ($tabelDosenPerProdi as $item)
                                <tr>
                                    <td class="px-6 py-3 text-center">{{ $item->programStudi->name }}</td>
                                    <td class="px-6 py-3 text-center">{{ $item->total }}</td>
                                </tr>
                            @empty
                                <div>
                                    <td colspan="2"
                                        class="px-6 py-4 text-center text-gray-500 dark:text-gray-300 dark-mode-transition">
                                        Tidak ada data dosen</td>
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex-1">
                    <div id="chartDosen" class="h-48 pl-2"></div>
                </div>
            </div>
        </div>


        <!-- Footer / Keterangan -->
        <div class="text-center text-sm text-gray-500 mt-8">
            &copy; 2025 Sistem Kehadiran Mahasiswa. All rights reserved.
        </div>
    </div>
    <script>
        // 1. Mengambil data dari Laravel Blade (Struktur ini sudah benar)
        const semesterCategories = @json($jumlahMahasiswaPerSemester->keys()->toArray());
        const mahasiswaDataSemester = @json($jumlahMahasiswaPerSemester->values()->toArray());
        const prodiCategories = @json($jumlahMahasiswaPerProdi->keys()->toArray());
        const mahasiswaDataProdi = @json($jumlahMahasiswaPerProdi->values()->toArray());
        const dosenCategories = @json($jumlahDosenPerProdi->keys()->toArray());
        const dosenDataProdi = @json($jumlahDosenPerProdi->values()->toArray());
        const genderPersentase = [<?php echo number_format($persentaseLaki, 1); ?>, <?php echo number_format($persentasePerempuan, 1); ?>];

        // 2. Fungsi Helper untuk tema (Struktur ini sudah benar)
        function getTextColor() {
            return document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151';
        }

        function getGridColor() {
            return document.documentElement.classList.contains('dark') ? 'rgba(229, 231, 235, 0.2)' :
                'rgba(55, 65, 81, 0.2)';
        }

        function getTooltipTheme() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        }

        // 3. Fungsi utama untuk membuat semua konfigurasi chart
        function createChartOptions() {
            const textColor = getTextColor();
            const gridColor = getGridColor();
            const tooltipTheme = getTooltipTheme();

            // Konfigurasi Chart 1: Mahasiswa per Semester
            const optionSemester = {
                chart: {
                    type: 'area',
                    height: 200,
                    fontFamily: "Inter, sans-serif",
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 300, // Kecepatan animasi dibuat konsisten & ringan
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ['#3B82F6'],
                series: [{
                    name: 'Jumlah Mahasiswa',
                    data: mahasiswaDataSemester
                }],
                xaxis: {
                    categories: semesterCategories,
                    labels: {
                        style: {
                            colors: textColor
                        }
                    },
                    tickAmount: 8
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    },
                    min: 0
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: textColor
                    }
                },
                grid: {
                    borderColor: gridColor,
                    padding: {
                        left: 2,
                        right: 2,
                        top: -20
                    }
                },
                tooltip: {
                    theme: tooltipTheme,
                    x: {
                        formatter: (val) => 'Semester ' + val
                    }
                },
            };

            // Konfigurasi Chart 2: Mahasiswa per Prodi
            const optionProdi = {
                chart: {
                    type: 'bar',
                    width: "100%",
                    height: 200,
                    fontFamily: "Inter, sans-serif",
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        speed: 300
                    }
                },
                fill: {
                    opacity: 1
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        columnWidth: "100%",
                        borderRadiusApplication: "end",
                        borderRadius: 4
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#3B82F6'],
                series: [{
                    name: 'Jumlah Mahasiswa',
                    data: mahasiswaDataProdi
                }],
                xaxis: {
                    categories: prodiCategories,
                    tickAmount: prodiCategories.length > 1 ? prodiCategories.length - 1 : 1,
                    labels: {
                        formatter: (val) => Math.round(val),
                        style: {
                            colors: textColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    },
                    min: 0
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: textColor
                    }
                },
                grid: {
                    show: true,
                    borderColor: gridColor,
                    padding: {
                        left: 6,
                        right: 2,
                        top: -25
                    }
                },
                tooltip: {
                    theme: tooltipTheme
                },
            };

            // Konfigurasi Chart 3: Gender Mahasiswa (Pie Chart)
            const optionProdiGender = {
                series: genderPersentase,
                colors: ["#1C64F2", "#16BDCA"],
                chart: {
                    height: 180,
                    width: "100%",
                    type: "pie",
                    animations: {
                        enabled: true,
                        speed: 300
                    }
                },
                stroke: {
                    colors: [document.documentElement.classList.contains('dark') ? '#111827' : '#FFFFFF'],
                    width: 2
                }, // Stroke disesuaikan dengan background
                plotOptions: {
                    pie: {
                        animateScale: true,
                        dataLabels: {
                            offset: -25
                        }
                    }
                },
                labels: ["Laki-laki", "Perempuan"],
                dataLabels: {
                    enabled: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        colors: ["#ffffff"],
                        fontSize: '14px'
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                    }
                },
                legend: {
                    position: "bottom",
                    fontFamily: "Inter, sans-serif",
                    labels: {
                        colors: textColor
                    }
                },
                tooltip: {
                    theme: tooltipTheme,
                    y: {
                        formatter: (value) => value + "%"
                    }
                },
            };

            // Konfigurasi Chart 4: Dosen per Prodi
            const optionDosen = {
                chart: {
                    type: 'bar',
                    width: "100%",
                    height: 230,
                    fontFamily: "Inter, sans-serif",
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        speed: 300
                    }
                },
                fill: {
                    opacity: 1
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        columnWidth: "100%",
                        borderRadiusApplication: "end",
                        borderRadius: 6
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#00C941'],
                series: [{
                    name: 'Jumlah Dosen',
                    data: dosenDataProdi
                }],
                xaxis: {
                    categories: dosenCategories,
                    tickAmount: dosenCategories.length > 1 ? dosenCategories.length - 1 : 1,
                    labels: {
                        formatter: (val) => Math.round(val),
                        style: {
                            colors: textColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: textColor
                    }
                },
                grid: {
                    show: true,
                    borderColor: gridColor,
                    padding: {
                        left: 4,
                        right: 2,
                        top: -30
                    }
                },
                tooltip: {
                    theme: tooltipTheme
                },
            };

            return {
                optionSemester,
                optionProdi,
                optionProdiGender,
                optionDosen
            };
        }

        // 4. Logika untuk inisialisasi dan update chart (disempurnakan)
        let chartSemester, chartProdi, chartProdiGender, chartDosen;

        function initCharts() {
            const options = createChartOptions();
            chartSemester = new ApexCharts(document.querySelector("#chartSemester"), options.optionSemester);
            chartProdi = new ApexCharts(document.querySelector("#chartProdi"), options.optionProdi);
            chartProdiGender = new ApexCharts(document.querySelector("#pie-chart"), options.optionProdiGender);
            chartDosen = new ApexCharts(document.querySelector("#chartDosen"), options.optionDosen);

            chartSemester.render();
            chartProdi.render();
            chartProdiGender.render();
            chartDosen.render();
        }

        // Fungsi update tema dengan metode bertahap (staggering) untuk transisi mulus
        function updateChartsTheme() {
            const options = createChartOptions();

            // Memberi jeda antar update agar tidak membebani browser
            setTimeout(() => {
                if (chartSemester) chartSemester.updateOptions(options.optionSemester);
            }, 0);
            setTimeout(() => {
                if (chartProdi) chartProdi.updateOptions(options.optionProdi);
            }, 75);
            setTimeout(() => {
                if (chartProdiGender) chartProdiGender.updateOptions(options.optionProdiGender);
            }, 150);
            setTimeout(() => {
                if (chartDosen) chartDosen.updateOptions(options.optionDosen);
            }, 225);
        }

        // 5. Observer untuk mendeteksi perubahan tema (Struktur ini sudah benar)
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    updateChartsTheme();
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Inisialisasi utama saat dokumen siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }
    </script>
@endsection
