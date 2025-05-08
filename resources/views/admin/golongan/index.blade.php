@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Golongan</h1>
        <div class="flex items-center justify-start"><button id="btnCreateModal"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 rounded-full cursor-pointer transition hover:bg-black">
                <span>Tambah Golongan</span>
            </button>
        </div>
    </div>

    <div class="px-6">
        <div class="overflow-x-auto bg-white rounded-xl shadow mb-2">
            <form action="{{ route('admin.golongan.index') }}" method="GET">
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
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Program Studi</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Golongan</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-6 py-2 text-left">{{ $loop->iteration }}.</td>
                            <td class="px-6 py-2 text-left">{{ $data->programStudi->name }}</td>
                            <td class="px-6 py-2 text-center">{{ $data->nama_golongan }}</td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                <form
                                    action="{{ route('admin.golongan.destroy', [$data->id]) . '?' . http_build_query(request()->only('id_prodi')) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-sm text-white bg-gray-800 border py-2 w-18 rounded-md hover:bg-gray-900 transition cursor-pointer font-medium">Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Data Golongan tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="hidden" id="modalCreateGolongan">
        <div class="absolute p-6 bg-white top-[200px] right-1/2 translate-x-1/2 shadow z-20 w-full max-w-md rounded">
            <div class="mb-6">
                <h1 class="text-center uppercase font-semibold">Tambah Golongan</h1>
            </div>
            <form action="{{ route('admin.golongan.create') . '?' . http_build_query(request()->only('id_prodi')) }}"
                method="post" class="flex flex-col gap-4">
                @csrf
                @method('POST')
                <div class="flex flex-col gap-2">
                    <label for="id_prodi" class="block text-sm font-semibold text-gray-700">Program Studi</label>
                    <select name="id_prodi" id="id_prodi"
                        class="mt-1 block w-full px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition">
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}"
                                {{ old('program_studi', request('program_studi')) == $prodi->name ? 'selected' : '' }}>
                                {{ $prodi->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="nama_golongan" class="block text-sm font-medium text-gray-700">Nama Golongan</label>
                    <input type="text" id="nama_golongan" name="nama_golongan"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow uppercase" placeholder="A">
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

    <div class="px-8 py-2">{{ $datas->links() }}</div>

    <script>
        const createModal = document.getElementById("modalCreateGolongan")

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
