@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Program Studi</h1>
        <div class="flex items-center justify-start"><button id="btnCreateModal"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 rounded-full cursor-pointer transition hover:bg-black">
                <span>Tambah Program Studi</span>
            </button>
        </div>
    </div>

    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-6 py-2 text-left">{{ $loop->iteration }}.</td>
                            <td class="px-6 py-2 text-left">{{ $data->name }}</td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                <button id="btnEditModal{{ $data->id }}"
                                    class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 hover:bg-black transition font-medium cursor-pointer">Edit</button>

                                <button type="button" id="btnDeleteModal{{ $data->id }}"
                                    class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus
                                </button>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteProdi{{ $data->id }}">
                            <div
                                class="p-6 py-10 bg-white fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow z-20 w-full max-w-xl rounded"">
                                <div class="mb-6 text-center">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500 mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus program studi
                                        <span class="font-bold">{{ $data->name }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.prodi.destroy', $data->id) }}" method="POST"
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
                            <div class="absolute inset-0 bg-gray-900/50 z-10">

                            </div>
                        </div>

                        <script>
                            const btnDeleteModal{{ $data->id }} = document.getElementById("btnDeleteModal{{ $data->id }}");
                            const modalDeleteProdi{{ $data->id }} = document.getElementById("modalDeleteProdi{{ $data->id }}");
                            const btnCloseDeleteModal{{ $data->id }} = document.getElementById("btnCloseDeleteModal{{ $data->id }}");

                            btnDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteProdi{{ $data->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteProdi{{ $data->id }}.classList.toggle("hidden");
                            });
                        </script>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Data Program Studi tidak
                                ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="hidden" id="modalCreateProdi">
        <div
            class="p-6 py-10 bg-white fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow z-20 w-full max-w-md rounded">
            <div class="mb-6">
                <h1 class="text-center uppercase font-semibold">Tambah Program Studi</h1>
            </div>
            <form action="{{ route('admin.prodi.create') }}" method="post" class="flex flex-col gap-4">
                @csrf
                @method('POST')
                <div class="flex flex-col gap-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Program Studi</label>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <input type="text" id="name" name="name"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="Teknik Komputer">
                </div>
                <div class="flex flex-col gap-2 mt-2">
                    <button type="submit"
                        class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
                    <button type="button" id="btnCloseCreateModal"
                        class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-transparent border text-gray-800 font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-gray-800 hover:text-white">Batal</button>
                </div>
            </form>
        </div>
        <div class="absolute inset-0 bg-gray-900/50 z-10">

        </div>
    </div>

    @foreach ($datas as $data)
        <div class="hidden" id="modalEditProdi{{ $data->id }}">
            <div class="absolute p-6 bg-white top-[200px] right-1/2 translate-x-1/2 shadow z-20 w-full max-w-md rounded">
                <div class="mb-6">
                    <h1 class="text-center uppercase font-semibold">Edit Program Studi {{ $data->name }}</h1>
                </div>
                <form action="{{ route('admin.prodi.update', $data->id) }}" method="post" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col gap-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Program Studi</label>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <input type="text" id="name" name="name" value="{{ $data->name }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="Teknik Komputer">
                    </div>
                    <div class="flex flex-col gap-2 mt-2">
                        <button type="submit"
                            class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black">Simpan</button>
                        <button type="button" id="btnCloseEditModal{{ $data->id }}"
                            class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-transparent border text-gray-800 font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-gray-800 hover:text-white">Batal</button>
                    </div>
                </form>
            </div>
            <div class="absolute inset-0 bg-gray-900/50 z-10">

            </div>
        </div>

        <script>
            const editModal{{ $data->id }} = document.getElementById("modalEditProdi{{ $data->id }}")
            const btnEditModal{{ $data->id }} = document.getElementById("btnEditModal{{ $data->id }}")
            const btnCloseEditModal{{ $data->id }} = document.getElementById("btnCloseEditModal{{ $data->id }}")

            btnEditModal{{ $data->id }}.addEventListener("click", () => {
                editModal{{ $data->id }}.classList.toggle("hidden");
            })
            btnCloseEditModal{{ $data->id }}.addEventListener("click", () => {
                editModal{{ $data->id }}.classList.toggle("hidden");
            })
        </script>
    @endforeach




    <script>
        const createModal = document.getElementById("modalCreateProdi")

        const btnCreateModal = document.getElementById("btnCreateModal")

        const btnCloseCreateModal = document.getElementById("btnCloseCreateModal")


        btnCreateModal.addEventListener("click", () => {
            createModal.classList.toggle("hidden");
        });

        btnCloseCreateModal.addEventListener("click", () => {
            createModal.classList.toggle("hidden");
        })
    </script>
@endsection
