@extends('admin.dashboard')

@section('admin-content')
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3.5 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Daftar Jadwal Kuliah</h1>
        <div class="flex items-center justify-start"><a href="{{ route('admin.jadwal-kuliah.create') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark-mode-transition text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black dark:hover:bg-gray-900">
                <span>Tambah Jadwal Kuliah</span>
            </a>
        </div>
    </div>

    <!-- Table Mahasiswa -->
    <div class="px-6">
        <div
            class="overflow-x-auto bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow mb-2">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark-mode-transition">
                <thead class="bg-gray-100 dark:bg-gray-900/30 dark-mode-transition dark:border-t dark:border-gray-700">
                    <tr>
                        <th
                            class="px-2 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            No.</th>
                        <th
                            class="px-2 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase ">
                            Hari</th>
                        <th
                            class="px-2 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Mata Kuliah</th>
                        <th
                            class="px-2 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Dosen</th>
                        <th
                            class="px-2 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Jam</th>
                        <th
                            class="px-2 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Ruangan</th>
                        <th
                            class="px-2 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Golongan</th>
                        <th
                            class="px-2 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Program Studi</th>
                        <th
                            class="px-2 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Semester</th>
                        <th
                            class="px-2 py-4 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody
                    class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200 dark-mode-transition">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-4 py-2 text-left">
                                {{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}.</td>
                            <td class="px-4 py-2 text-center">{{ $data->hari }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->mataKuliah->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->dosen->name }}</td>

                            <td class="px-4 py-2 text-center">{{ substr($data->jam_mulai, 0, 5) }}
                                -{{ substr($data->jam_selesai, 0, 5) }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $data->ruangan->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->golongan->nama_golongan }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->prodi->name }}</td>
                            <td class="px-4 py-2 text-center">
                                {{ $data->semester->display_name ? explode(' ', $data->semester->display_name)[1] : '-' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.jadwal-kuliah.edit', $data->id) }}"
                                        class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition">Edit</a>
                                    <button type="button" id="btnDeleteModal{{ $data->id }}"
                                        class="text-sm text-gray-800 bg-transparent dark:bg-red-900/70 dark:text-white dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteJadwal{{ $data->id }}">
                            <div
                                class="absolute p-6 py-10 bg-white dark:bg-gray-900/60 dark-mode-transition backdrop-blur-sm top-[200px] right-1/2 translate-x-1/2 shadow z-50 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i
                                        class="fa-solid fa-triangle-exclamation text-6xl text-red-500 dark:text-red-600 dark-mode-transition mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus Jadwal Kuliah
                                        <span class="font-bold">{{ $data->mataKuliah->name }} - Semester
                                            {{ $data->semester->display_name ? explode(' ', $data->semester->display_name)[1] : '-' }}
                                            - Program Studi
                                            {{ $data->prodi->name }} - Golongan
                                            {{ $data->golongan->nama_golongan }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 items-center justify-center">
                                    <form action="{{ route('admin.jadwal-kuliah.destroy', $data->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm text-gray-800 bg-transparent dark:bg-transparent dark:text-red-700 dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 dark:hover:text-white hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus
                                        </button>
                                        <button type="button" id="btnCloseDeleteModal{{ $data->id }}"
                                            class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-white dark:text-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-300 transition font-medium cursor-pointer dark-mode-transition">Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 dark-mode-transition z-40"></div>
                        </div>

                        <script>
                            const btnDeleteModal{{ $data->id }} = document.getElementById("btnDeleteModal{{ $data->id }}");
                            const modalDeleteJadwal{{ $data->id }} = document.getElementById("modalDeleteJadwal{{ $data->id }}");
                            const btnCloseDeleteModal{{ $data->id }} = document.getElementById("btnCloseDeleteModal{{ $data->id }}");

                            btnDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteJadwal{{ $data->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteJadwal{{ $data->id }}.classList.toggle("hidden");
                            });
                        </script>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">Data Jadwal Kuliah tidak
                                ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-8 py-3">
        {{ $datas->links() }}
    </div>
@endsection
