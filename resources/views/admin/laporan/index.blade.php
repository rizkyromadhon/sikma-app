@extends('admin.dashboard')

@section('admin-content')
    <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4">
        <h1 class="text-xl font-semibold text-gray-800">Daftar Laporan Mahasiswa</h1>
    </div>

    <div class="px-6">
        <div x-data="{ activeModal: null }" class="overflow-x-auto bg-white rounded-xl shadow mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr class="border-b-2 border-gray-200">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">No.</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 uppercase">NIM</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Prodi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Pesan</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
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
                                        class="inline-block bg-red-200 text-red-500 px-4 py-2 rounded-full text-sm font-medium">Belum
                                        Ditangani</span>
                                @elseif ($item->status == 'Sedang Diproses')
                                    <span
                                        class="bg-yellow-200 text-yellow-600 px-4 py-2 rounded-full text-sm font-medium">Sedang
                                        Diproses</span>
                                @elseif ($item->status == 'Selesai')
                                    <span
                                        class="bg-green-200 text-green-600 px-4 py-2 rounded-full text-sm font-medium">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-2 text-center flex gap-2 items-center justify-center">
                                @if ($item->status == 'Belum Ditangani')
                                    {{-- Tombol Proses --}}
                                    <button @click="activeModal='proses{{ $item->id }}'"
                                        class="px-3 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 cursor-pointer text-sm">
                                        Proses
                                    </button>
                                    <!-- Modal Proses -->
                                    <div x-show="activeModal==='proses{{ $item->id }}'"
                                        class="fixed inset-0 bg-gray-900/50 flex items-center justify-center"
                                        x-transition.opacity.duration.200>
                                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full"
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
                                                    <textarea name="balasan" id="balasan{{ $item->id }}" rows="4" class="w-full border rounded p-2" required
                                                        placeholder="Laporan sedang diproses."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Kirim</button>
                                                    <button type="button" @click="activeModal=null"
                                                        class="px-4 py-2 bg-transparent text-black rounded hover:bg-gray-900 border hover:text-white">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif ($item->status == 'Sedang Diproses')
                                    {{-- Tombol Proses --}}
                                    <button @click="activeModal='selesai{{ $item->id }}'"
                                        class="px-3 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 cursor-pointer text-sm">
                                        Selesai
                                    </button>
                                    <!-- Modal Proses -->
                                    <div x-show="activeModal==='selesai{{ $item->id }}'"
                                        class="fixed inset-0 bg-gray-900/50 flex items-center justify-center"
                                        x-transition.opacity.duration.200>
                                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full"
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
                                                    <textarea name="balasan" id="balasan{{ $item->id }}" rows="4" class="w-full border rounded p-2" required
                                                        placeholder="Kartu RFID sudah jadi, silahkan ambil di admin program studi."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Kirim</button>
                                                    <button type="button" @click="activeModal=null"
                                                        class="px-4 py-2 bg-transparent text-black rounded hover:bg-gray-900 border hover:text-white">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                <button type="button" id="btnDeleteModal{{ $item->id }}"
                                    class="text-sm text-gray-800 bg-transparent border py-2 w-18 rounded-md hover:bg-gray-800 hover:text-white transition cursor-pointer font-medium">Hapus</button>
                            </td>
                        </tr>
                        <div class="hidden" id="modalDeleteLaporan{{ $item->id }}">
                            <div
                                class="absolute p-6 py-10 bg-white top-[200px] right-1/2 translate-x-1/2 shadow z-20 w-full max-w-xl rounded">
                                <div class="mb-6 text-center">
                                    <i class="fa-solid fa-triangle-exclamation text-6xl text-red-500 mb-4"></i>
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
                                            class="text-sm text-red-500 bg-transparent border-red-500 border-2 py-2 w-18 rounded-md hover:bg-red-500 hover:text-white transition cursor-pointer font-medium">Hapus
                                        </button>
                                        <button type="button" id="btnCloseDeleteModal{{ $item->id }}"
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
