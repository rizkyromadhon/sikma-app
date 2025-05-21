@extends('admin.dashboard')

@section('admin-content')
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
    </div>

    <div class="container mx-auto p-6">
        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
                <h2 class="text-sm text-gray-500 mb-2">Jumlah Mahasiswa</h2>
                <p class="text-2xl font-bold text-blue-600">{{ $jumlahMahasiswa }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
                <h2 class="text-sm text-gray-500 mb-2">Jumlah Dosen</h2>
                <p class="text-2xl font-bold text-blue-600">{{ $jumlahDosen }}</p>
            </div>
        </div>

        <!-- Chart dan Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Mahasiswa per Semester -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold text-lg mb-4 text-gray-700">Jumlah Mahasiswa per Semester</h3>
                <div id="chartSemester" class="h-48 rounded-lg flex items-center justify-center pr-4">
                </div>
            </div>

            <!-- Grafik Mahasiswa per Program Studi -->
            <div class="bg-white rounded-lg shadow">
                <h3 class="font-semibold text-lg mb-4 text-gray-700 p-4">Jumlah Mahasiswa per Program Studi</h3>
                <div class="flex items-center justify-center w-full">
                    <div id="chartProdi" class="h-48 pl-6"></div>
                    <div id="pie-chart" class="h-48"></div>
                </div>
            </div>
        </div>

        <!-- Data Dosen dan Program Studi -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="font-semibold text-lg mb-4 text-gray-700">Jumlah Dosen per Program Studi</h3>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <table class="w-full divide-y divide-gray-200 border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Program
                                    Studi</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Jumlah Dosen
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                            @forelse ($tabelDosenPerProdi as $item)
                                <tr>
                                    <td class="px-6 py-3 text-center">{{ $item->programStudi->name }}</td>
                                    <td class="px-6 py-3 text-center">{{ $item->total }}</td>
                                </tr>
                            @empty
                                <div>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">Tidak ada data dosen</td>
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
        const semesterCategories = @json($jumlahMahasiswaPerSemester->keys()->toArray());
        const mahasiswaDataSemester = @json($jumlahMahasiswaPerSemester->values()->toArray());
        const prodiCategories = @json($jumlahMahasiswaPerProdi->keys()->toArray());
        const mahasiswaDataProdi = @json($jumlahMahasiswaPerProdi->values()->toArray());
        const dosenCategories = @json($jumlahDosenPerProdi->keys()->toArray());
        const dosenDataProdi = @json($jumlahDosenPerProdi->values()->toArray());

        const genderPersentase = [<?php echo number_format($persentaseLaki, 1); ?>, <?php echo number_format($persentasePerempuan, 1); ?>];

        const optionSemester = {
            chart: {
                type: 'area',
                height: 200,
                fontFamily: "Inter, sans-serif",
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',

            },
            colors: ['#3B82F6'],
            series: [{
                name: 'Jumlah Mahasiswa',
                data: mahasiswaDataSemester
            }, ],
            xaxis: {
                categories: semesterCategories,
                labels: {
                    style: {
                        colors: '#374151' // warna teks axis
                    }
                },
                tickAmount: 8 // jumlah label sesuai dengan data
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#374151'
                    }
                },
                min: 0
            },
            legend: {
                position: 'bottom',
                labels: {
                    colors: '#374151'
                }
            },
            grid: {
                borderColor: 'rgba(55, 65, 81, 0.2)',
                padding: {
                    left: 2,
                    right: 2,
                    top: -20
                },
            },
            tooltip: {
                x: {
                    formatter: function(val) {
                        return 'Semester ' + val; // Tambahkan kata "Semester"
                    }
                }
            },
        };

        const optionProdi = {
            chart: {
                type: 'bar',
                width: "100%",
                height: 200,
                fontFamily: "Inter, sans-serif",
                toolbar: {
                    show: false
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
                    borderRadius: 4,
                    dataLabels: {
                        position: "top",
                    },
                },
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#3B82F6'],
            series: [{
                name: 'Jumlah Mahasiswa',
                data: mahasiswaDataProdi,
            }, ],
            xaxis: {
                categories: prodiCategories,
                tickAmount: prodiCategories.length - 1, // jumlah tick sesuai kategori
                labels: {
                    show: true,
                    formatter: function(val) {
                        return Math.round(val); // pastikan tampil angka bulat
                    },
                    style: {
                        colors: '#374151'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#374151'
                    }
                },
                min: 0,
            },
            legend: {
                position: 'bottom',
                labels: {
                    colors: '#374151'
                }
            },
            grid: {
                show: true,
                padding: {
                    left: 6,
                    right: 2,
                    top: -25
                },
            },

        };

        const optionProdiGender = {
            series: genderPersentase,
            colors: ["#1C64F2", "#16BDCA"],
            chart: {
                height: 180,
                width: "100%",
                type: "pie",
            },
            stroke: {
                colors: ["white"],
                lineCap: "",
            },
            plotOptions: {
                pie: {
                    labels: {
                        show: true,
                    },
                    size: "100%",
                    dataLabels: {
                        offset: -25
                    }
                },
            },
            labels: ["Laki-laki", "Perempuan"],
            dataLabels: {
                enabled: true,
                style: {
                    fontFamily: "Inter, sans-serif",
                },
            },
            legend: {
                position: "bottom",
                fontFamily: "Inter, sans-serif",
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return value + "%"
                    },
                },
            },
            xaxis: {
                labels: {
                    formatter: function(value) {
                        return value + "%"
                    },
                },
                axisTicks: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
            },
        }

        const optionDosen = {
            chart: {
                type: 'bar',
                width: "100%",
                height: 230,
                fontFamily: "Inter, sans-serif",
                toolbar: {
                    show: false
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
                    borderRadius: 6,
                    dataLabels: {
                        position: "top",
                    },
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',

            },
            colors: ['#3B82F6'],
            series: [{
                name: 'Jumlah Dosen',
                data: dosenDataProdi,
            }, ],
            xaxis: {
                categories: dosenCategories,
                tickAmount: dosenCategories.length - 1, // jumlah tick sesuai kategori
                labels: {
                    show: true,
                    formatter: function(val) {
                        return Math.round(val); // pastikan tampil angka bulat
                    },
                    style: {
                        colors: '#374151'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#374151'
                    }
                },
            },
            legend: {
                position: 'bottom',
                labels: {
                    colors: '#374151'
                }
            },
            grid: {
                show: true,
                padding: {
                    left: 2,
                    right: 2,
                    top: -30
                },
            },

        };

        const chartSemester = new ApexCharts(document.querySelector("#chartSemester"), optionSemester);
        const chartProdi = new ApexCharts(document.querySelector("#chartProdi"), optionProdi);
        const chartProdiGender = new ApexCharts(document.querySelector("#pie-chart"), optionProdiGender);
        const chartDosen = new ApexCharts(document.querySelector("#chartDosen"), optionDosen);
        chartSemester.render();
        chartProdi.render();
        chartProdiGender.render();
        chartDosen.render();
    </script>
@endsection
