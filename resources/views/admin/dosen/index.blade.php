@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Dosen</h1>
        <div class="flex items-center justify-center gap-4">
            <form class="flex items-center justify-start" action="#" method="GET">
                <input type="text" name="search" placeholder="Cari Dosen berdasarkan NIP...."
                    value="{{ old('search', request('search')) }}"
                    class="px-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-gray-700 focus:border-transparent transition w-2xs">
                <button type="submit"
                    class="flex items-center justify-center ml-2 text-sm px-4 py-2 bg-gray-800 text-white font-semibold rounded-full shadow-xl transition hover:bg-black cursor-pointer">
                    Cari
                </button>
            </form>
            <div class="flex items-center justify-start"><a href="{{ route('admin.dosen.create') }}"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black">
                    <span>Tambah Dosen</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Table Mahasiswa -->
    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow mb-2">
            <form action="{{ route('admin.dosen.index') }}" method="GET">
                <div class="mx-auto px-6 mb-4 flex space-x-4 mt-4">
                    <!-- Filter Program Studi -->
                    <div class="flex-1">
                        <label for="program_studi" class="block text-sm font-semibold text-gray-700">Program Studi</label>
                        <select name="id_prodi" id="program_studi" onchange="this.form.submit()"
                            class="mt-1 block px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition w-fit">
                            <option value="">Semua Program Studi</option>
                            @foreach ($programStudi as $program)
                                <option value="{{ $program->id }}"
                                    {{ old('id_prodi', request('id_prodi')) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">NIP</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Program Studi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Alamat</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-4 py-2 text-left">
                                {{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}.</td>
                            <td class="px-4 py-2 text-left">{{ $data->name }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->email }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->nip }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->programStudi->name }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->alamat }}</td>
                            <td class="px-4 py-2 text-center flex gap-2 items-center justify-center">
                                <a href="{{ route('admin.dosen.edit', $data->id) }}"
                                    class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 hover:bg-black transition font-medium cursor-pointer">Edit</a>
                                <button type="button" id="btnDeleteModal{{ $data->id }}"
                                    class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus
                                </button>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteDosen{{ $data->id }}">
                            <div
                                class="absolute p-6 py-10 bg-white top-[200px] right-1/2 translate-x-1/2 shadow z-20 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500 mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus dosen
                                        <span class="font-bold">{{ $data->name }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.dosen.destroy', $data->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm text-red-500 bg-transparent border-red-500 border-2 py-2 w-18 rounded-md hover:bg-red-500 hover:text-white transition cursor-pointer font-medium">Hapus
                                        </button>
                                        <button type="button" id="btnCloseDeleteModal{{ $data->id }}"
                                            class="text-sm
                                            text-white bg-gray-700 border py-2 w-18 rounded-md hover:bg-gray-800
                                             transition cursor-pointer font-medium">Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gray-900/50 z-10">

                            </div>
                        </div>

                        <script>
                            const btnDeleteModal{{ $data->id }} = document.getElementById("btnDeleteModal{{ $data->id }}");
                            const modalDeleteDosen{{ $data->id }} = document.getElementById("modalDeleteDosen{{ $data->id }}");
                            const btnCloseDeleteModal{{ $data->id }} = document.getElementById("btnCloseDeleteModal{{ $data->id }}");

                            btnDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteDosen{{ $data->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteDosen{{ $data->id }}.classList.toggle("hidden");
                            });
                        </script>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Data Dosen tidak
                                ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>
    <div class="px-8 py-2">
        {{ $datas->links() }}
    </div>
@endsection
