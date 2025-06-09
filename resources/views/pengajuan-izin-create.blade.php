<x-layout>
    <x-slot:title>Buat Pengajuan Izin/Sakit</x-slot:title>

    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800/50 shadow-lg rounded-xl border-t-4 border-blue-500 dark:border-blue-400">
            <div class="p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-slate-100">Formulir Pengajuan Izin/Sakit</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Silakan isi form di bawah ini untuk mengajukan izin tidak masuk kuliah.
                </p>
            </div>

            <form action="{{ route('mahasiswa.izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 md:p-8 space-y-6 border-t dark:border-slate-700">
                    <div>
                        <label for="jadwal_kuliah_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Mata Kuliah</label>
                        <select id="jadwal_kuliah_id" name="jadwal_kuliah_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Pilih jadwal kuliah</option>
                            @foreach ($jadwalMahasiswa as $jadwal)
                                <option value="{{ $jadwal->id }}">{{ $jadwal->mataKuliah->name }}
                                    ({{ $jadwal->hari }},
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }})
                                    -
                                    {{ $jadwal->dosen->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_izin"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Izin</label>
                            <input type="date" name="tanggal_izin" id="tanggal_izin" required
                                value="{{ old('tanggal_izin', now()->format('Y-m-d')) }}"
                                class="mt-1 px-4 py-2 block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm sm:text-sm dark:bg-slate-700 dark:text-white dark:[&::-webkit-calendar-picker-indicator]:invert">
                        </div>
                        <div>
                            <label for="tipe_pengajuan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe
                                Pengajuan</label>
                            <select id="tipe_pengajuan" name="tipe_pengajuan" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option>Izin</option>
                                <option>Sakit</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="pesan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan
                            / Pesan</label>
                        <textarea id="pesan" name="pesan" rows="4" required
                            class="mt-1 px-4 py-2 block w-full rounded-md border-gray-300 dark:border-slate-600 shadow-sm sm:text-sm dark:bg-slate-700 dark:text-white">{{ old('pesan') }}</textarea>
                    </div>

                    <div>
                        <label for="file_bukti" class="block text-sm font-medium text-gray-700 dark:text-gray-300">File
                            Bukti (Opsional)</label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Contoh: Surat dokter. Tipe file: JPG,
                            PNG, PDF. Maks: 2MB.</p>

                        </p>
                        <input
                            class="block px-4 mb-1 w-fit text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400"
                            id="file_bukti" name="file_bukti" type="file">
                        <p class="text-xs text-red-600 mb-2">* sakit wajib melampirkan surat keterangan
                            sakit dari dokter/klinik
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800/50 text-right rounded-b-xl">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
