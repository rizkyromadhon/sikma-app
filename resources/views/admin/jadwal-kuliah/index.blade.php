@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Jadwal Kuliah</h1>
        <div class="flex items-center justify-start"><a href="{{ route('admin.jadwal-kuliah.create') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 rounded-full cursor-pointer transition hover:bg-black">
                <span>Tambah Jadwal Kuliah</span>
            </a>
        </div>
    </div>

    <!-- Table Mahasiswa -->
    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-2 py-4 text-center text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-2 py-4 text-center text-sm font-semibold text-gray-700 uppercase ">Hari</th>
                        <th class="px-2 py-4 text-center text-sm font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                        <th class="px-2 py-4 text-center text-sm font-semibold text-gray-700 uppercase">Dosen</th>
                        <th class="px-2 py-4 text-sm font-semibold text-gray-700 uppercase text-center">Jam</th>
                        <th class="px-2 py-4 text-sm font-semibold text-gray-700 uppercase text-center">Ruangan</th>
                        <th class="px-2 py-4 text-sm font-semibold text-gray-700 uppercase text-center">Golongan</th>
                        <th class="px-2 py-4 text-sm font-semibold text-gray-700 uppercase text-center">Program Studi</th>
                        <th class="px-2 py-4 text-sm font-semibold text-gray-700 uppercase text-center">Semester</th>
                        <th class="px-2 py-4 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $loop->iteration }}.</td>
                            <td class="px-4 py-2 text-center">{{ $data->hari }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->mataKuliah->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->dosen->name }}</td>

                            <td class="px-4 py-2 text-center">{{ substr($data->jam_mulai, 0, 5) }}
                                -{{ substr($data->jam_selesai, 0, 5) }}
                            </td>
                            <td class="px-4 py-2 text-center">{{ $data->ruangan->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->golongan->nama_golongan }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->prodi->name }}</td>
                            <td class="px-4 py-2 text-center">{{ explode(' ', $data->semester->semester_name)[1] }}</td>
                            <td class="px-4 py-2 text-center flex gap-2 items-center justify-center">
                                <a href="{{ route('admin.jadwal-kuliah.edit', $data->id) }}"
                                    class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 hover:bg-black transition font-medium">Edit</a>
                                <button type="button" id="btnDeleteModal{{ $data->id }}"
                                    class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus
                                </button>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteJadwal{{ $data->id }}">
                            <div
                                class="p-6 py-10 bg-white fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow z-20 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500 mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus Jadwal Kuliah
                                        <span class="font-bold">{{ $data->mataKuliah->name }} - Semester
                                            {{ explode(' ', $data->semester->semester_name)[1] }} - Program Studi
                                            {{ $data->prodi->name }} - Golongan
                                            {{ $data->golongan->nama_golongan }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.jadwal-kuliah.destroy', $data->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm text-red-500 bg-transparent border-red-500 border-2 py-2 w-18 rounded-md hover:bg-red-500 hover:text-white transition cursor-pointer font-medium">Hapus
                                        </button>
                                        <button type="button" id="btnCloseDeleteModal{{ $data->id }}"
                                            class="text-sm
                                            text-white bg-gray-800 border py-2 w-18 rounded-md hover:bg-gray-900
                                             transition cursor-pointer font-medium">Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="fixed inset-0 bg-gray-900/50 z-10"></div>
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
@endsection
