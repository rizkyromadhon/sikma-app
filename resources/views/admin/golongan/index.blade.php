@extends('admin.dashboard')

@section('admin-content')
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3.5 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Daftar Golongan</h1>
        <div class="flex items-center justify-start"><button id="btnCreateModal"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark-mode-transition text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black dark:hover:bg-gray-900">
                <span>Tambah Golongan</span>
            </button>
        </div>
    </div>

    <div class="px-6">
        <div
            class="overflow-x-auto bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 shadow mb-4">
            <form action="{{ route('admin.golongan.index') }}" method="GET">
                <div class="mx-auto px-6 mb-4 flex space-x-4 mt-4">
                    <!-- Filter Program Studi -->
                    <div class="flex-1">
                        <label for="program_studi"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                            Studi</label>
                        <select name="id_prodi" id="program_studi" onchange="this.form.submit()"
                            class="mt-2 block px-2 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none sm:text-sm transition w-fit">
                            <option value=""
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition">Semua
                                Program Studi</option>
                            @foreach ($programStudi as $program)
                                <option value="{{ $program->id }}"
                                    class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                    {{ old('id_prodi', request('id_prodi')) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
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
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            No.</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Program Studi</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Golongan</th>
                        <th
                            class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase text-center">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody
                    class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200 dark-mode-transition">
                    @forelse ($datas as $data)
                        <tr>
                            <td class="px-6 py-2 text-left">
                                {{ ($datas->currentPage() - 1) * $datas->perPage() + $loop->iteration }}.</td>
                            <td class="px-6 py-2 text-left">{{ $data->programStudi->name }}</td>
                            <td class="px-6 py-2 text-center">{{ $data->nama_golongan }}</td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                <button type="button" id="btnDeleteModal{{ $data->id }}"
                                    class="text-sm text-white bg-gray-800 dark:bg-red-900/70 dark:text-white dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-900 dark:hover:bg-red-900 transition cursor-pointer font-medium dark-mode-transition">Hapus
                                </button>
                            </td>
                        </tr>

                        <div class="hidden" id="modalDeleteGolongan{{ $data->id }}">
                            <div
                                class="absolute p-6 py-10 bg-white dark:bg-gray-900/60 dark-mode-transition backdrop-blur-sm top-[200px] right-1/2 translate-x-1/2 shadow z-50 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i
                                        class="fa-solid fa-triangle-exclamation text-6xl text-red-500 dark:text-red-600 dark-mode-transition mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus golongan
                                        <span class="font-bold">{{ $data->nama_golongan }}</span> dari
                                        <strong>{{ $data->programStudi->name }}</strong>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.golongan.destroy', $data->id) }}" method="POST"
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
                            <div class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 dark-mode-transition z-40">

                            </div>
                        </div>
                        <script>
                            const btnDeleteModal{{ $data->id }} = document.getElementById("btnDeleteModal{{ $data->id }}");
                            const modalDeleteGolongan{{ $data->id }} = document.getElementById("modalDeleteGolongan{{ $data->id }}");
                            const btnCloseDeleteModal{{ $data->id }} = document.getElementById("btnCloseDeleteModal{{ $data->id }}");

                            btnDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteGolongan{{ $data->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $data->id }}.addEventListener("click", () => {
                                modalDeleteGolongan{{ $data->id }}.classList.toggle("hidden");
                            });
                        </script>
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
        <div
            class="absolute p-6 py-10 bg-white dark:bg-gray-900/60 dark-mode-transition backdrop-blur-sm top-[200px] right-1/2 translate-x-1/2 shadow z-50 w-full max-w-xl rounded">
            <div class="mb-6">
                <h1 class="text-center uppercase font-semibold text-gray-700 dark:text-gray-200">Tambah Golongan</h1>
            </div>
            <form action="{{ route('admin.golongan.create') . '?' . http_build_query(request()->only('id_prodi')) }}"
                method="post" class="flex flex-col gap-4">
                @csrf
                @method('POST')
                <div class="flex flex-col gap-2">
                    <label for="id_prodi"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Program
                        Studi</label>
                    <select name="id_prodi" id="id_prodi"
                        class="mt-1 block w-full px-2 py-2 border border-gray-300 dark:border-gray-700 dark-mode-transition rounded-md shadow-sm focus:outline-none sm:text-sm transition">
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}"
                                class="dark:text-gray-200 dark:bg-black/90 backdrop-blur-xs dark-mode-transition"
                                {{ old('program_studi', request('program_studi')) == $prodi->name ? 'selected' : '' }}>
                                {{ $prodi->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="nama_golongan"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                        Golongan</label>
                    <input type="text" id="nama_golongan" name="nama_golongan"
                        class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow uppercase placeholder-gray-600/50 dark:placeholder-gray-400/50"
                        placeholder="A">
                </div>
                <div class="flex flex-col gap-2 mt-2">
                    <button type="submit"
                        class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark:hover:bg-black/20 text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
                    <button type="button" id="btnCloseCreateModal"
                        class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-transparent dark:bg-gray-800 border dark:border-gray-700 text-gray-800 dark:text-gray-200 font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-gray-800 dark:hover:bg-gray-900/70 hover:text-white">Batal</button>
                </div>
            </form>
        </div>
        <div class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 dark-mode-transition z-40">

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
