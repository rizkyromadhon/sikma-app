@extends('admin.dashboard')

@section('admin-content')
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
    </div>

    <!-- Content -->
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-4">Teknik Komputer</h3>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user text-gray-40"></i>
                        <span class="text-lg font-semibold text-gray-700">5</span>
                    </div>
                    <div class="flex items-center space-x-2 text-red-500">
                        <i class="fas fa-user"></i>
                        <span class="text-lg font-semibold">2</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-4">Teknik Informatika</h3>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-lg font-semibold text-gray-700">3</span>
                    </div>
                    <div class="flex items-center space-x-2 text-red-500">
                        <i class="fas fa-user"></i>
                        <span class="text-lg font-semibold">1</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-4">Manajemen Informatika</h3>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-lg font-semibold text-gray-700">4</span>
                    </div>
                    <div class="flex items-center space-x-2 text-red-500">
                        <i class="fas fa-user"></i>
                        <span class="text-lg font-semibold">2</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-4">Bisnis Digital</h3>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user text-gray-400"></i>
                        <span class="text-lg font-semibold text-gray-700">6</span>
                    </div>
                    <div class="flex items-center space-x-2 text-red-500">
                        <i class="fas fa-user"></i>
                        <span class="text-lg font-semibold">1</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Total Kehadiran Mahasiswa hari ini</h2>
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-user text-gray-400 text-xl"></i>
                    <span class="text-2xl font-semibold text-gray-700">18</span>
                </div>
                <div class="flex items-center space-x-3 text-red-500">
                    <i class="fas fa-user text-xl"></i>
                    <span class="text-2xl font-semibold">6</span>
                </div>
            </div>
        </div>
    </div>
@endsection
