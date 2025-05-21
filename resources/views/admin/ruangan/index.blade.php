@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Ruangan</h1>
        <div class="flex items-center justify-start"><a href="{{ route('admin.ruangan.create') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 rounded-full cursor-pointer transition hover:bg-black">
                <span>Tambah Ruangan</span>
            </a>
        </div>
    </div>

    <!-- Table Mahasiswa -->
    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Kode</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Nama</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Tipe Ruangan</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-6 py-2 text-center">
                                {{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}.</td>
                            <td class="px-6 py-2 text-center">{{ $data->kode }}</td>
                            <td class="px-6 py-2 text-center">{{ $data->name }}</td>
                            <td class="px-6 py-2 text-center capitalize">{{ $data->type }}</td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                <a href="{{ route('admin.ruangan.edit', $data->id) }}"
                                    class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 hover:bg-black transition font-medium">Edit</a>
                                <button type="button" id="btnDeleteModal{{ $data->id }}"
                                    class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus
                                </button>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteRuangan{{ $data->id }}">
                            <div
                                class="p-6 py-10 bg-white fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow z-20 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500 mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus Ruangan
                                        <span class="font-bold">{{ $data->name }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.ruangan.destroy', $data->id) }}" method="POST"
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
                            const modalDeleteRuangan{{ $data->id }} = document.getElementById("modalDeleteRuangan{{ $data->id }}");
                            const btnCloseDeleteModal{{ $data->id }} = document.getElementById("btnCloseDeleteModal{{ $data->id }}");

                            btnDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteRuangan{{ $data->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteRuangan{{ $data->id }}.classList.toggle("hidden");
                            });
                        </script>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Data ruangan tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>
    <div class="px-8 py-2">{{ $datas->links() }}</div>
@endsection
