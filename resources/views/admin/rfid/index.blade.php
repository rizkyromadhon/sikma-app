@extends('admin.dashboard')

@section('admin-content')
    <script>
        // Deklarasi Echo sekali saja diluar loop
        const echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });
    </script>
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3.5 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Manajemen RFID</h1>
        <div class="flex items-center justify-center gap-4">
            <form class="flex items-center justify-start" action="#" method="GET">
                <input type="text" name="search" placeholder="Cari Mahasiswa berdasarkan NIM...."
                    value="{{ old('search', request('search')) }}"
                    class="px-4 py-2 text-sm rounded-full border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring focus:ring-gray-700 dark:focus:ring-gray-400 focus:border-transparent transition w-2xs placeholder-gray-700 dark:placeholder-gray-200 dark-mode-transition">
                <button type="submit"
                    class="flex items-center justify-center ml-2 text-sm px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 text-white font-semibold rounded-full shadow-xl transition hover:bg-black dark:hover:bg-gray-900 cursor-pointer dark-mode-transition">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <div class="px-6">
        <div
            class="overflow-x-auto bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow mb-2">
            <form action="{{ route('admin.rfid.index') }}" method="GET">
                <div class="mx-auto px-6 mb-4 flex space-x-4 mt-4">
                    <!-- Filter Semester -->
                    <div class="flex-1">
                        <label for="semester"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Semester</label>
                        <select name="semester" id="semester" @change="$store.loading.value = true; $el.form.submit()"
                            class="mt-1 block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                            onchange="this.form.submit()">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Semester</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    {{ old('semester', request('semester')) == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Program Studi -->
                    <div class="flex-1">
                        <label for="program_studi"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                            Studi</label>
                        <select name="program_studi" id="program_studi"
                            @change="$store.loading.value = true; $el.form.submit()"
                            class="mt-1 block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                            onchange="this.form.submit()">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Program Studi</option>
                            @foreach ($programStudiData as $program)
                                <option value="{{ $program->id }}"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    {{ old('program_studi', request('program_studi')) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Golongan -->
                    <div class="flex-1">
                        <label for="golongan"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Golongan</label>
                        <select name="golongan" id="golongan" @change="$store.loading.value = true; $el.form.submit()"
                            class="mt-1 block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                            onchange="this.form.submit()">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Golongan</option>
                            @foreach ($golonganData as $golongan)
                                <option value="{{ $golongan }}"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    {{ old('golongan', request('golongan')) == $golongan ? 'selected' : '' }}>
                                    {{ $golongan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark-mode-transition">
                <thead class="bg-gray-100 dark:bg-gray-900/30 dark-mode-transition dark:border-t dark:border-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            UID</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            NIM</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Nama</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Semester</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Program Studi</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Golongan</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Status</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody
                    class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200 dark-mode-transition"
                    id="student-table-body">
                    @forelse ($mahasiswa as $student)
                        <tr class="student-row" data-program-studi="{{ $student->programStudi->name }}">
                            <td class="px-6 py-2 text-center">{{ $student->uid ?? '-' }}</td>
                            <td class="px-6 py-2 text-left">{{ $student->nim }}</td>
                            <td class="px-6 py-2 text-left">{{ $student->name }}</td>
                            <td class="px-6 py-2 text-center">
                                {{ $student->semester->display_name ? explode(' ', $student->semester->display_name)[1] : '-' }}
                            <td class="px-6 py-2 text-left">{{ $student->programStudi->name }}</td>
                            </td>
                            <td class="px-6 py-2 text-center">{{ $student->golongan->nama_golongan }}</td>
                            <td class="px-6 py-2 text-center w-54">
                                @if ($student->uid)
                                    <span
                                        class="bg-green-200 dark:bg-green-900/60 text-green-500 dark:text-green-200 px-4 py-2 text-s font-medium rounded-full w-fit inline-block">
                                        Sudah Registrasi</span>
                                @else
                                    <span
                                        class="bg-red-200 dark:bg-red-900/60 text-red-500 dark:text-red-200 px-4 py-2 text-s font-medium rounded-full w-fit inline-block">
                                        Belum Registrasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                @if ($student->uid)
                                    <a href="{{ route('admin.rfid.edit', $student->id) }}"
                                        class="text-sm text-white py-2 rounded-md bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition w-24">Edit</a>
                                @else
                                    <a href="{{ route('admin.rfid.registrasi', $student->id) }}"
                                        class="text-sm text-white py-2 rounded-md bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition w-24">Registrasi</a>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data mahasiswa</td>
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
