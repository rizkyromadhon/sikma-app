@extends('admin.dashboard')

@section('admin-content')
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Daftar Semester</h1>
        <div class="flex items-center justify-start"><a href="{{ route('admin.semester.create') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark-mode-transition text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black dark:hover:bg-gray-900">
                <span>Tambah Semester</span>
            </a>
        </div>
    </div>

    <div class="px-6">
        <div
            class="overflow-x-auto bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow mb-4">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark-mode-transition">
                <thead class="bg-gray-100 dark:bg-gray-900/30 dark-mode-transition dark:border-t dark:border-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Kode</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Tipe</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Semester</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Mulai</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Berakhir</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Status</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody
                    class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200 dark-mode-transition">
                    @forelse ($groupedSemesters as $code => $semestersInGroup)
                        @foreach ($semestersInGroup as $index => $semester)
                            <tr>
                                @if ($loop->first)
                                    <td class="px-6 py-2 text-center align-middle"
                                        rowspan="{{ $semestersInGroup->count() }}">
                                        {{ $semester->semester_code ?? '-' }}
                                    </td>
                                    <td class="px-6 py-2 text-center align-middle"
                                        rowspan="{{ $semestersInGroup->count() }}">
                                        {{ $semester->semester_type }}
                                    </td>
                                @endif
                                <td class="px-6 py-2 text-center">{{ $semester->display_name }}</td> {{-- Ini akan unik per baris --}}
                                <td class="px-6 py-2 text-center">
                                    {{ $semester->start_date ? \Carbon\Carbon::parse($semester->start_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-2 text-center">
                                    {{ $semester->end_date ? \Carbon\Carbon::parse($semester->end_date)->format('d M Y') : '-' }}
                                </td>
                                @if ($loop->first)
                                    <td class="px-6 py-2 text-center align-middle"
                                        rowspan="{{ $semestersInGroup->count() }}">
                                        {{ $semester->is_currently_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </td>
                                @endif
                                <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                    <a href="{{ route('admin.semester.edit', $semester->id) }}"
                                        class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition">Edit</a>
                                    <button type="button" id="btnDeleteModal{{ $semester->id }}"
                                        class="text-sm text-gray-800 bg-transparent dark:bg-red-900/70 dark:text-white dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @foreach ($semestersInGroup as $semester)
                            <div class="hidden" id="modalDeleteSemester{{ $semester->id }}">
                                <div
                                    class="absolute p-6 py-10 bg-white dark:bg-gray-900/60 dark-mode-transition backdrop-blur-sm top-[200px] right-1/2 translate-x-1/2 shadow z-50 w-full max-w-xl rounded">
                                    <div class="mb-6 text-center">
                                        <i
                                            class="fa-solid fa-triangle-exclamation text-6xl text-red-500 dark:text-red-600 dark-mode-transition mb-4"></i>
                                        <h1 class="text-center font-medium">Anda yakin ingin menghapus <span
                                                class="font-bold">{{ $semester->display_name }}</span>?</h1>
                                    </div>
                                    <div class="flex gap-2 justify-center">
                                        <form action="{{ route('admin.semester.destroy', $semester->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm text-gray-800 bg-transparent dark:bg-transparent dark:text-red-700 dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 dark:hover:text-white hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus</button>
                                            <button type="button" id="btnCloseDeleteModal{{ $semester->id }}"
                                                class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-white dark:text-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-300 transition font-medium cursor-pointer dark-mode-transition">Batal</button>
                                        </form>
                                    </div>
                                </div>
                                <div
                                    class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 z-40 modal-overlay-{{ $semester->id }} hidden">
                                </div> {{-- Tambahkan class hidden awal --}}
                            </div>
                            <script>
                                (function() {
                                    const btnDeleteModal = document.getElementById("btnDeleteModal{{ $semester->id }}");
                                    const modalDeleteSemester = document.getElementById("modalDeleteSemester{{ $semester->id }}");
                                    const btnCloseDeleteModal = document.getElementById("btnCloseDeleteModal{{ $semester->id }}");
                                    const overlay = document.querySelector(".modal-overlay-{{ $semester->id }}");

                                    if (btnDeleteModal && modalDeleteSemester && btnCloseDeleteModal && overlay) {
                                        const toggleModal = () => {
                                            modalDeleteSemester.classList.toggle("hidden");
                                            overlay.classList.toggle("hidden");
                                        };
                                        btnDeleteModal.addEventListener("click", toggleModal);
                                        btnCloseDeleteModal.addEventListener("click", toggleModal);
                                    }
                                })
                                ();
                            </script>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Data semester tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
