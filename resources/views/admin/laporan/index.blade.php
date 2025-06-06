@extends('admin.dashboard')

@section('admin-content')
    <div
        class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4.5 mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Daftar Laporan Mahasiswa</h1>
    </div>

    <div class="px-6">
        <div x-data="{ activeModal: null }"
            class="overflow-x-auto bg-white dark:bg-black border dark-mode-transition border-gray-200 dark:border-gray-700 rounded-xl shadow mb-4">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark-mode-transition">
                <thead class="bg-gray-100 dark:bg-gray-900/30 dark-mode-transition dark:border-t dark:border-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            No.</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Nama</th>
                        <th
                            class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            NIM</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Prodi</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Email</th>
                        <th
                            class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Pesan</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Status</th>
                        <th
                            class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 dark-mode-transition uppercase">
                            Aksi</th>
                    </tr>
                </thead>

                <tbody
                    class="bg-white dark:bg-gray-900/70 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-800 dark:text-gray-200 dark-mode-transition">
                    @forelse ($laporan as $item)
                        <tr>
                            <td class="px-4 py-3 text-center">
                                {{ ($laporan->currentPage() - 1) * $laporan->perPage() + $loop->iteration }}.</td>
                            <td class="px-6 py-3 text-left">{{ $item->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-left">{{ $item->nim }}</td>
                            <td class="px-6 py-3 text-left">{{ $item->programStudi->name }}</td>
                            <td class="px-6 py-3 text-left">{{ $item->email }}</td>
                            <td class="px-6 py-3 text-left">{{ $item->pesan }}</td>
                            <td class="px-6 py-3 text-center w-54">
                                @if ($item->status == 'Belum Ditangani')
                                    <span
                                        class="inline-block bg-red-200 dark:bg-red-900/60 text-red-500 dark:text-red-200 px-4 py-2 rounded-full text-sm font-medium w-38">Belum
                                        Ditangani</span>
                                @elseif ($item->status == 'Sedang Diproses')
                                    <span
                                        class="inline-block bg-yellow-200 dark:bg-yellow-900/60 text-yellow-600 dark:text-yellow-200 px-4 py-2 rounded-full text-sm font-medium w-38">Sedang
                                        Diproses</span>
                                @elseif ($item->status == 'Selesai')
                                    <span
                                        class="inline-block bg-green-200 dark:bg-green-900/60 text-green-600 dark:text-green-200 px-4 py-2 rounded-full text-sm font-medium w-38">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                @if ($item->status == 'Belum Ditangani')
                                    {{-- Tombol Proses --}}
                                    <button @click="activeModal='proses{{ $item->id }}'"
                                        class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition">
                                        Proses
                                    </button>
                                    <!-- Modal Proses -->
                                    <div x-show="activeModal==='proses{{ $item->id }}'"
                                        class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 dark-mode-transition z-40 flex items-center justify-center"
                                        x-transition.opacity.duration.200>
                                        <div class="bg-white dark:bg-gray-900/60 backdrop-blur-sm p-6 rounded-lg shadow-lg max-w-lg w-full"
                                            @click.away="isOpen = false">
                                            <h2 class="text-xl font-semibold mb-4">Kirim Pesan ke {{ $item->nama_lengkap }}
                                            </h2>
                                            <form
                                                action="{{ route('laporan.aksi', ['id' => $item->id, 'aksi' => 'proses']) }}"
                                                method="POST">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="status" value="Sedang Diproses">
                                                <div class="mb-4">
                                                    <textarea name="balasan" id="balasan{{ $item->id }}" rows="4"
                                                        class="w-full border border-gray-300 dark:border-gray-700 placeholder-gray-600/50 dark:placeholder-gray-400/50 rounded p-2"
                                                        required placeholder="Laporan sedang diproses."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark:hover:bg-gray-900/40 text-white rounded hover:bg-gray-900 transition">Kirim</button>
                                                    <button type="button" @click="activeModal=null"
                                                        class="px-4 py-2 bg-transparent dark:bg-gray-800 text-black dark:text-gray-200 rounded hover:bg-gray-900 dark:hover:bg-gray-900 border dark:border-gray-700 hover:text-white transition">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif ($item->status == 'Sedang Diproses')
                                    {{-- Tombol Proses --}}
                                    <button @click="activeModal='selesai{{ $item->id }}'"
                                        class="text-sm text-white py-2 rounded-md w-18 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 hover:bg-black dark:hover:bg-gray-900 transition font-medium cursor-pointer dark-mode-transition">
                                        Selesai
                                    </button>
                                    <!-- Modal Proses -->
                                    <div x-show="activeModal==='selesai{{ $item->id }}'"
                                        class="absolute inset-0 bg-gray-900/50 dark:bg-black/70 dark-mode-transition z-40 flex items-center justify-center"
                                        x-transition.opacity.duration.200>
                                        <div class="bg-white dark:bg-gray-900/60 backdrop-blur-sm p-6 rounded-lg shadow-lg max-w-lg w-full"
                                            @click.away="isOpen = false">
                                            <h2 class="text-xl font-semibold mb-4">Kirim Pesan ke {{ $item->nama_lengkap }}
                                            </h2>
                                            <form
                                                action="{{ route('laporan.aksi', ['id' => $item->id, 'aksi' => 'selesai']) }}"
                                                method="POST">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="status" value="Selesai">
                                                <div class="mb-4">
                                                    <textarea name="balasan" id="balasan{{ $item->id }}" rows="4"
                                                        class="w-full border border-gray-300 dark:border-gray-700 placeholder-gray-600/50 dark:placeholder-gray-400/50 rounded p-2"
                                                        required placeholder="Kartu RFID sudah jadi, silahkan ambil di admin program studi."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-gray-800 dark:bg-black dark:border dark:border-gray-700 dark:hover:bg-gray-900/40 text-white rounded hover:bg-gray-900 transition">Kirim</button>
                                                    <button type="button" @click="activeModal=null"
                                                        class="px-4 py-2 bg-transparent dark:bg-gray-800 text-black dark:text-gray-200 rounded hover:bg-gray-900 dark:hover:bg-gray-900 border dark:border-gray-700 hover:text-white transition">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                <button type="button" id="btnDeleteModal{{ $item->id }}"
                                    class="text-sm text-gray-800 bg-transparent dark:bg-red-900/70 dark:text-white dark:border-red-800 border py-2 w-18 rounded-md hover:bg-gray-800 dark:hover:bg-red-900 hover:text-white transition cursor-pointer font-medium dark-mode-transition">Hapus</button>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteLaporan{{ $item->id }}">
                            <div
                                class="absolute p-6 py-10 bg-white dark:bg-gray-900/60 dark-mode-transition backdrop-blur-sm top-[200px] right-1/2 translate-x-1/2 shadow z-50 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i
                                        class="fa-solid fa-triangle-exclamation text-6xl text-red-500 dark:text-red-600 dark-mode-transition mb-4"></i>
                                    <h1 class="text-center font-medium">Anda yakin ingin menghapus laporan
                                        <span class="font-bold">{{ $item->nama_lengkap }}</span>
                                        ?
                                    </h1>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.laporan.destroy', $item->id) }}" method="POST"
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
                            const modalDeleteLaporan{{ $item->id }} = document.getElementById("modalDeleteLaporan{{ $item->id }}");
                            const btnCloseDeleteModal{{ $item->id }} = document.getElementById("btnCloseDeleteModal{{ $item->id }}");

                            btnDeleteModal{{ $item->id }}.addEventListener("click", () => {
                                modalDeleteLaporan{{ $item->id }}.classList.toggle("hidden");
                            });

                            btnCloseDeleteModal{{ $item->id }}.addEventListener("click", () => {
                                modalDeleteLaporan{{ $item->id }}.classList.toggle("hidden");
                            });
                        </script>
                    @empty
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada laporan</td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-8 py-2">
        {{ $laporan->links() }}
    </div>
@endsection
