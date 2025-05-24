@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Mahasiswa</h1>
        <div class="flex items-center justify-center gap-4">
            <form class="flex items-center justify-start" action="#" method="GET">
                <input type="text" name="search" placeholder="Cari Mahasiswa berdasarkan NIM...."
                    value="{{ old('search', request('search')) }}"
                    class="px-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-gray-700 focus:border-transparent transition w-2xs">
                <button type="submit"
                    class="flex items-center justify-center ml-2 text-sm px-4 py-2 bg-gray-800 text-white font-semibold rounded-full shadow-xl transition hover:bg-black cursor-pointer">
                    Cari
                </button>
            </form>
            <div class="flex items-center justify-start"><a href="{{ route('admin.mahasiswa.create') }}"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black">
                    <span>Tambah Mahasiswa</span>
                </a>
            </div>
        </div>
    </div>

    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <form action="{{ route('admin.mahasiswa.index') }}" method="GET">
                <div class="mx-auto px-6 mb-4 flex space-x-4 mt-4">
                    <!-- Filter Semester -->
                    <div class="flex-1">
                        <label for="semester" class="block text-sm font-semibold text-gray-700">Semester</label>
                        <select name="semester" id="semester" @change="$store.loading.value = true; $el.form.submit()"
                            class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                            onchange="this.form.submit()">
                            <option value="">Semua Semester</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}"
                                    {{ old('semester', request('semester')) == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->semester_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Program Studi -->
                    <div class="flex-1">
                        <label for="program_studi" class="block text-sm font-semibold text-gray-700">Program Studi</label>
                        <select name="program_studi" id="program_studi"
                            @change="$store.loading.value = true; $el.form.submit()"
                            class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                            onchange="this.form.submit()">
                            <option value="">Semua Program Studi</option>
                            @foreach ($programStudiData as $program)
                                <option value="{{ $program->id }}"
                                    {{ old('program_studi', request('program_studi')) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Golongan -->
                    <div class="flex-1">
                        <label for="golongan" class="block text-sm font-semibold text-gray-700">Golongan</label>
                        <select name="golongan" id="golongan" @change="$store.loading.value = true; $el.form.submit()"
                            class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                            onchange="this.form.submit()">
                            <option value="">Semua Golongan</option>
                            @foreach ($golonganData as $golongan)
                                <option value="{{ $golongan }}"
                                    {{ old('golongan', request('golongan')) == $golongan ? 'selected' : '' }}>
                                    {{ $golongan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">NIM</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Gender</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Program Studi</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Semester</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Golongan</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800" id="student-table-body">
                    @forelse ($mahasiswa as $student)
                        @if ($student->id_prodi != null)
                            <tr class="student-row" data-program-studi="{{ $student->programStudi->name }}">
                                <td class="px-6 py-2 text-left">{{ $student->nim }}</td>
                                <td class="px-6 py-2 text-left">{{ $student->name }}</td>
                                <td class="px-6 py-2 text-left">{{ $student->gender ?: '-' }}</td>
                                <td class="px-6 py-2 text-left">{{ $student->programStudi->name }}</td>
                                <td class="px-6 py-2 text-center">{{ explode(' ', $student->semester->semester_name)[1] }}
                                </td>
                                <td class="px-6 py-2 text-center">{{ $student->golongan->nama_golongan }}</td>
                                <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                    <a href="{{ route('admin.mahasiswa.edit', [$student->id, 'page' => request()->get('page')]) }}"
                                        class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 hover:bg-black transition font-medium">Edit</a>
                                    <button type="button" id="btnDeleteModal{{ $student->id }}"
                                        class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus</button>
                                </td>
                            </tr>
                        @endif
                        <div class="hidden" id="modalDeleteMahasiswa{{ $student->id }}">
                            <div
                                class="p-6 py-10 bg-white fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow z-20 w-full max-w-xl rounded"">
                                <div class="mb-6 text-center">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500 mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus Mahasiswa
                                        <span class="font-bold">{{ $student->name }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.mahasiswa.destroy', $student->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm text-red-500 bg-transparent border-red-500 border-2 py-2 w-18 rounded-md hover:bg-red-500 hover:text-white transition cursor-pointer font-medium">Hapus
                                        </button>
                                        <button type="button" id="btnCloseDeleteModal{{ $student->id }}"
                                            class="text-sm
                                            text-white bg-gray-800 border py-2 w-18 rounded-md hover:bg-gray-900
                                             transition cursor-pointer font-medium">Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gray-900/50 z-10">

                            </div>
                        </div>

                        <script>
                            const btnDeleteModal{{ $student->id }} = document.getElementById("btnDeleteModal{{ $student->id }}");
                            const modalDeleteMahasiswa{{ $student->id }} = document.getElementById(
                                "modalDeleteMahasiswa{{ $student->id }}");
                            const btnCloseDeleteModal{{ $student->id }} = document.getElementById("btnCloseDeleteModal{{ $student->id }}");

                            btnDeleteModal{{ $student->id }}.addEventListener("click", () => {
                                modalDeleteMahasiswa{{ $student->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $student->id }}.addEventListener("click", () => {
                                modalDeleteMahasiswa{{ $student->id }}.classList.toggle("hidden");
                            });
                        </script>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data mahasiswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-8 py-3">
        {{ $mahasiswa->links() }}
    </div>
@endsection
