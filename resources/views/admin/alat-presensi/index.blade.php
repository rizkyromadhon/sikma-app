@extends('admin.dashboard')

@section('admin-content')
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-3.5 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Daftar Alat Presensi</h1>
        <div class="flex items-center justify-start"><a href="{{ route('admin.alat-presensi.create') }}"
                class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark-mode-transition text-white font-semibold shadow-xl rounded-full cursor-pointer transition hover:bg-black dark:hover:bg-gray-900">
                <span>Tambah Alat Presensi</span>
            </a>
        </div>
    </div>

    <div class="px-6">
        <div
            class="overflow-x-auto bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow mb-4">
            @foreach ($alatPresensi as $item)
                @livewire('alat-presensi-table')
                <div class="hidden" id="modalDeleteAlat{{ $item->id }}">
                    <div
                        class="absolute p-6 py-10 bg-white dark:bg-gray-900/60 dark-mode-transition backdrop-blur-sm top-[200px] right-1/2 translate-x-1/2 shadow z-50 w-full max-w-xl rounded">
                        <div class="mb-6 text-center">
                            <i
                                class="fa-solid fa-triangle-exclamation text-6xl text-red-500 dark:text-red-600 dark-mode-transition mb-4"></i>
                            <h1 class="text-center font-medium">Anda yakin ingin menghapus alat presensi
                                <span class="font-bold">{{ $item->name }}</span>
                                ?
                            </h1>
                        </div>
                        <div class="flex gap-2 justify-center">
                            <form action="{{ route('admin.alat-presensi.destroy', $item->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-sm text-gray-800 bg-transparent dark:bg-transparent dark:text-red-700 dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 dark:hover:text-white hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus
                                </button>
                                <button type="button" id="btnCloseDeleteModal{{ $item->id }}"
                                    class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-white dark:text-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-300 transition font-medium cursor-pointer dark-mode-transition">Batal
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 dark-mode-transition z-40">

                    </div>
                </div>
                <script>
                    const btnDeleteModal{{ $item->id }} = document.getElementById("btnDeleteModal{{ $item->id }}");
                    const modalDeleteAlat{{ $item->id }} = document.getElementById("modalDeleteAlat{{ $item->id }}");
                    const btnCloseDeleteModal{{ $item->id }} = document.getElementById("btnCloseDeleteModal{{ $item->id }}");

                    btnDeleteModal{{ $item->id }}.addEventListener("click", () => {
                        modalDeleteAlat{{ $item->id }}.classList.toggle("hidden");
                    });

                    btnCloseDeleteModal{{ $item->id }}.addEventListener("click", () => {
                        modalDeleteAlat{{ $item->id }}.classList.toggle("hidden");
                    });
                </script>
            @endforeach

        </div>
    </div>
@endsection
