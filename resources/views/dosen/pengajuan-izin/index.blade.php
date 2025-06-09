@extends('dosen.layouts')

@section('dosen-content')
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-medical text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Pengajuan Izin & Sakit</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Validasi pengajuan dari mahasiswa.</p>
                </div>
            </div>
        </div>
    </header>

    <main class="p-4 sm:p-6 lg:p-8">
        {{-- Tabs Filter --}}
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                <a href="{{ route('dosen.izin.index', ['status' => 'Baru']) }}" @class([
                    'whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm',
                    'border-blue-500 text-blue-600 dark:text-blue-400' =>
                        $currentStatus == 'Baru',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' =>
                        $currentStatus != 'Baru',
                ])> Baru </a>
                <a href="{{ route('dosen.izin.index', ['status' => 'Disetujui']) }}" @class([
                    'whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm',
                    'border-green-500 text-green-600 dark:text-green-400' =>
                        $currentStatus == 'Disetujui',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' =>
                        $currentStatus != 'Disetujui',
                ])> Disetujui
                </a>
                <a href="{{ route('dosen.izin.index', ['status' => 'Ditolak']) }}" @class([
                    'whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm',
                    'border-red-500 text-red-600 dark:text-red-400' =>
                        $currentStatus == 'Ditolak',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200' =>
                        $currentStatus != 'Ditolak',
                ])> Ditolak
                </a>
            </nav>
        </div>

        <div class="space-y-4">
            @forelse ($pengajuanIzin as $pengajuan)
                <div
                    class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-5">
                    <div class="flex items-start gap-4">
                        <img src="{{ optional($pengajuan->users)->foto ? asset('storage/' . $pengajuan->users->foto) : asset('img/avatar-default.png') }}"
                            alt="Foto" class="w-12 h-12 rounded-full object-cover">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ optional($pengajuan->users)->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ optional($pengajuan->users)->nim }}</p>
                                </div>
                                <span
                                    class="text-xs font-semibold px-4 py-2 rounded-full {{ $pengajuan->tipe_pengajuan == 'Izin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ $pengajuan->tipe_pengajuan }}
                                </span>
                            </div>

                            <div
                                class="mt-3 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 p-3 rounded-md">
                                <p class="font-semibold">Pesan Pengajuan:</p>
                                <p class="whitespace-pre-wrap">{{ $pengajuan->pesan }}</p>
                            </div>
                            @if ($pengajuan->file_bukti)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $pengajuan->file_bukti) }}" target="_blank"
                                        class="text-xs text-blue-500 hover:underline"><i
                                            class="fas fa-paperclip mr-1"></i>Lihat File Bukti</a>
                                </div>
                            @endif

                            <div
                                class="mt-3 text-xs text-gray-500 dark:text-gray-400 space-y-1 border-t dark:border-slate-700 pt-2">
                                <p><i class="fas fa-book fa-fw mr-2"></i>Mata Kuliah: <span
                                        class="font-medium text-gray-700 dark:text-gray-300">{{ optional($pengajuan->jadwalKuliah->mataKuliah)->name }}</span>
                                </p>
                                <p><i class="fas fa-calendar-day fa-fw mr-2"></i>Tanggal Izin: <span
                                        class="font-medium text-gray-700 dark:text-gray-300">{{ $pengajuan->tanggal_izin->translatedFormat('l, d F Y') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($currentStatus == 'Baru')
                        <form action="{{ route('dosen.izin.updateStatus', $pengajuan) }}" method="POST">
                            @csrf
                            <div
                                class="mt-4 pt-4 border-t dark:border-slate-600 flex flex-col sm:flex-row justify-end gap-3">
                                <div class="flex-1">
                                    <label for="catatan_dosen" class="sr-only">Catatan Dosen</label>
                                    <input type="text" name="catatan_dosen" placeholder="Tambahkan catatan (opsional)..."
                                        class="w-full px-4 py-2 text-sm rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                                </div>
                                <div class="flex gap-3">
                                    <button type="submit" name="status" value="Ditolak"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-800 hover:bg-red-900 dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-900/50 rounded-lg transition-colors">Tolak</button>
                                    <button type="submit" name="status" value="Disetujui"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-800 hover:bg-green-900 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-900/50 rounded-lg transition-colors">Setujui</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            @empty
                <div
                    class="text-center py-16 px-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope-open-text text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tidak Ada Pengajuan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tidak ada pengajuan dengan status
                        '{{ $currentStatus }}' saat ini.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $pengajuanIzin->withQueryString()->links() }}
            </div>
        </div>
    </main>
@endsection
