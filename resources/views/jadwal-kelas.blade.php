<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Jadwal Kelas</h1>
        @auth
            <div class="overflow-x-auto bg-white rounded-xl shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Hari/Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Dosen</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Jam</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                        <tr>
                            <td class="px-6 py-4">Senin, 14-April-2025</td>
                            <td class="px-6 py-4">Pemrograman Web</td>
                            <td class="px-6 py-4">Budi Santosa, M.Kom</td>
                            <td class="px-6 py-4">08:00 - 10:00</td>
                            <td class="px-6 py-4">Lab Pemrograman Web</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Selasa, 15-April-2025</td>
                            <td class="px-6 py-4">Jaringan Komputer</td>
                            <td class="px-6 py-4">Siti Rahma, M.T</td>
                            <td class="px-6 py-4">10:00 - 12:00</td>
                            <td class="px-6 py-4">Lab Sistem Komputer</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Rabu, 16-April-2025</td>
                            <td class="px-6 py-4">Basis Data</td>
                            <td class="px-6 py-4">Arya Teguh, M.T</td>
                            <td class="px-6 py-4">12:00 - 14:00</td>
                            <td class="px-6 py-4">JTI 3.3</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Kamis, 17-April-2025</td>
                            <td class="px-6 py-4">Pendidikan Pancasila</td>
                            <td class="px-6 py-4">Rendy Permana, M.Kom</td>
                            <td class="px-6 py-4">08:00 - 10:00</td>
                            <td class="px-6 py-4">JTI 3.4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endauth

        @guest
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md shadow-md">
                <strong>Info:</strong> Anda harus <a href="{{ route('login') }}"
                    class="text-blue-600 underline">login</a> terlebih dahulu untuk melihat jadwal kelas.
            </div>
        @endguest
    </div>
</x-layout>
