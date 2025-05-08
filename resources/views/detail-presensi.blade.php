<x-layout>
    <div class="px-6 py-4">
        <h1 class="text-xl font-bold text-gray-800 mb-4">
            <a href="{{ route('home') }}" class="text-black hover:text-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </h1>
        <h1 class="text-2xl font-medium text-gray-800 mb-6">
            Detail Presensi - <strong>Program Studi {{ $program_studi }}</strong>
        </h1>

        <div class="flex items-center justify-between">
            <div>
                <form method="GET" action="{{ route('detail.presensi', $program_studi) }}" class="mb-4">
                    <div class="flex space-x-4">
                        <!-- Semester -->
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester" id="semester" onchange="this.form.submit()"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Semua Semester</option>
                                @foreach ($semesterOptions as $semester)
                                    <option value="{{ $semester->id }}"
                                        {{ request('semester') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->semester_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Golongan -->
                        <div>
                            <label for="golongan" class="block text-sm font-medium text-gray-700">Golongan</label>
                            <select name="golongan" id="golongan" onchange="this.form.submit()"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Semua Golongan</option>
                                @foreach ($golonganOptions as $golongan)
                                    <option value="{{ $golongan->id }}"
                                        {{ request('golongan') == $golongan->id ? 'selected' : '' }}>
                                        {{ $golongan->nama_golongan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ruangan -->
                        <div>
                            <label for="ruangan" class="block text-sm font-medium text-gray-700">Ruangan</label>
                            <select name="ruangan" id="ruangan" onchange="this.form.submit()"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Semua Ruangan</option>
                                @foreach ($ruanganOptions as $ruangan)
                                    <option value="{{ $ruangan->id }}"
                                        {{ request('ruangan') == $ruangan->id ? 'selected' : '' }}>
                                        {{ $ruangan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mata Kuliah -->
                        <div>
                            <label for="mata_kuliah" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                            <select name="mata_kuliah" id="mata_kuliah" onchange="this.form.submit()"
                                class="mt-1 block w-50 px-2 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Semua Mata Kuliah</option>
                                @foreach ($mataKuliahOptions as $mataKuliah)
                                    <option value="{{ $mataKuliah->id }}"
                                        {{ request('mata_kuliah') == $mataKuliah->id ? 'selected' : '' }}>
                                        {{ $mataKuliah->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="mt-1">
                <form class="flex items-center justify-start" action="{{ route('detail.presensi', $program_studi) }}"
                    method="GET">
                    <input type="text" name="search" placeholder="Cari Mahasiswa berdasarkan NIM...."
                        value="{{ old('search', request('search')) }}"
                        class="px-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-gray-700 focus:border-transparent transition w-2xs">
                    <button type="submit"
                        class="flex items-center justify-center ml-2 text-sm px-4 py-2 bg-gray-800 text-white font-semibold rounded-full shadow-xl transition hover:bg-black cursor-pointer">
                        Cari
                    </button>
                </form>
            </div>
        </div>





        <div class="overflow-x-auto bg-white rounded-xl shadow mb-5">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">NIM</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Semester</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Program Studi
                        </th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Golongan</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Ruangan</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Waktu Presensi
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($presensi as $items)
                        @foreach ($items as $index => $item)
                            <tr>
                                @if ($index === 0)
                                    <!-- No. -->
                                    <td class="px-4 py-2 text-center" rowspan="{{ $items->count() }}">
                                        {{ $loop->parent->iteration }}.
                                    </td>
                                    <!-- Nama -->
                                    <td class="px-4 py-2 text-center" rowspan="{{ $items->count() }}">
                                        {{ $item->user->name }}
                                    </td>
                                    <!-- NIM -->
                                    <td class="px-4 py-2 text-center" rowspan="{{ $items->count() }}">
                                        {{ $item->user->nim }}
                                    </td>
                                @endif

                                <!-- Semester -->
                                <td class="px-4 py-2 text-center">
                                    {{ explode(' ', $item->jadwalKuliah->semester->semester_name)[1] }}
                                </td>
                                <!-- Program Studi -->
                                <td class="px-4 py-2 text-center">{{ $item->user->programStudi->name }}</td>
                                <!-- Golongan -->
                                <td class="px-4 py-2 text-center">
                                    {{ $item->jadwalKuliah->golongan->nama_golongan }}
                                </td>
                                <!-- Mata Kuliah -->
                                <td class="px-4 py-2 text-center">{{ $item->mataKuliah->name }}</td>
                                <!-- Ruangan -->
                                <td class="px-4 py-2 text-center">{{ $item->jadwalKuliah->ruangan->name }}</td>
                                <!-- Waktu Presensi -->
                                <td class="px-4 py-2 text-center">
                                    {{ \Carbon\Carbon::parse($item->waktu_presensi)->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">Tidak ada data presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</x-layout>
