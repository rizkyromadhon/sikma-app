<x-layout>
    <x-slot:title>Riwayat Pengajuan Izin/Sakit</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-20 md:mb-0">
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Pengajuan Anda</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lacak status semua pengajuan izin atau sakit
                    yang pernah Anda buat.</p>
            </div>
            <a href="{{ route('mahasiswa.izin.create') }}"
                class="w-full sm:w-auto flex-shrink-0 text-sm text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-plus mr-2"></i>Buat Pengajuan Baru
            </a>
        </div>

        {{-- Daftar Riwayat --}}
        <div class="space-y-4">
            @forelse ($riwayatPengajuan as $pengajuan)
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border dark:border-slate-700 p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        {{-- Info Pengajuan --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-3">
                                <span @class([
                                    'text-xs font-semibold px-3 py-1 rounded-full',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' =>
                                        $pengajuan->status == 'Baru',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' =>
                                        $pengajuan->status == 'Disetujui',
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' =>
                                        $pengajuan->status == 'Ditolak',
                                ])>
                                    {{ $pengajuan->status }}
                                </span>
                                <span class="text-xs text-gray-700 dark:text-gray-200">
                                    Diajukan pada {{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-700 dark:text-gray-200">
                                Pengajuan <strong>{{ $pengajuan->tipe_pengajuan }}</strong> untuk mata kuliah
                                <strong>{{ optional($pengajuan->jadwalKuliah->mataKuliah)->name }}</strong> pada
                                tanggal
                                <strong>{{ $pengajuan->tanggal_izin->translatedFormat('l, d F Y') }}</strong>.
                            </p>

                            <div
                                class="mt-3 text-sm text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-slate-700/50 p-3 rounded-md border-l-4 dark:border-slate-600">
                                <p class="font-semibold mb-1">Alasan:</p>
                                <p class="whitespace-pre-wrap">{{ $pengajuan->pesan }}</p>
                            </div>

                            @if ($pengajuan->file_bukti)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $pengajuan->file_bukti) }}" target="_blank"
                                        class="text-xs text-blue-500 hover:underline"><i
                                            class="fas fa-paperclip mr-1"></i>Lihat File Bukti</a>
                                </div>
                            @endif
                        </div>

                        {{-- Catatan Dosen (jika ada) --}}
                        @if ($pengajuan->status != 'Baru' && $pengajuan->catatan_dosen)
                            <div class="w-full sm:w-1/3 flex-shrink-0 bg-blue-50 dark:bg-blue-900/30 p-3 rounded-md">
                                <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 mb-1">Catatan dari
                                    Dosen:</p>
                                <p class="text-sm text-blue-700 dark:text-blue-400 italic">
                                    "{{ $pengajuan->catatan_dosen }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="text-center py-16 px-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Belum Ada Riwayat Pengajuan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Anda belum pernah membuat pengajuan izin
                        atau sakit.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Links --}}
        <div class="mt-8">
            {{ $riwayatPengajuan->links() }}
        </div>
    </div>
</x-layout>
