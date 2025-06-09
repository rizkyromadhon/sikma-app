<div wire:poll.20s="loadJadwal"
    class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-slate-700">

    {{-- Definisikan variabel waktu saat ini di sini agar bisa dipakai berulang kali --}}
    @php
        $now = now();
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fas fa-calendar-day text-blue-500 mr-2"></i> Jadwal Hari Ini
        </h3>
        @if ($jadwalHariIni->count() > 0)
            <span
                class="text-sm text-white bg-blue-900 dark:bg-blue-900/60 px-2 py-1 rounded-full">{{ $jadwalHariIni->count() }}
                kelas</span>
        @endif
    </div>

    <div class="space-y-4">
        @forelse ($jadwalHariIni as $jadwal)
            @php
                // Logika untuk menentukan status kelas
                $jamMulai = \Carbon\Carbon::parse($jadwal->jam_mulai);
                $jamSelesai = \Carbon\Carbon::parse($jadwal->jam_selesai);
                $status = '';
                $statusColor = '';
                $isBlinking = false;

                if ($now->between($jamMulai, $jamSelesai)) {
                    $status = 'Berlangsung';
                    $isBlinking = true;
                    $statusColor = 'green';
                } elseif ($now->lt($jamMulai)) {
                    $menujuKelas = (int) ceil($now->diffInMinutes($jamMulai, false));

                    if ($menujuKelas >= 0 && $menujuKelas <= 1) {
                        // Dalam 10 menit atau kurang
                        $status = 'Segera Dimulai';
                        $statusColor = 'yellow';
                        $isBlinking = true; // Aktifkan blinking!
                    } elseif ($menujuKelas > 1 && $menujuKelas <= 60) {
                        // Antara 11 - 60 menit
                        $status = 'Dalam ' . $menujuKelas . ' menit';
                        $statusColor = 'yellow';
                    } else {
                        // Lebih dari 1 jam lagi
                        $status = 'Akan Datang';
                        $statusColor = 'gray';
                    }
                } else {
                    $status = 'Selesai';
                    $statusColor = 'gray';
                }

                $jadwal->status_text = $status;
                $jadwal->status_color = $statusColor;
                $jadwal->is_blinking = $isBlinking;
            @endphp

            {{-- Kartu untuk setiap jadwal --}}
            <div @class([
                'flex items-start space-x-4 p-4 rounded-lg',
                'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' =>
                    $status == 'Berlangsung',
                'bg-gray-50 dark:bg-slate-700/50' => $status != 'Berlangsung',
            ])>
                <div @class([
                    'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0',
                    'bg-blue-100 dark:bg-blue-800' => $status == 'Berlangsung',
                    'bg-gray-100 dark:bg-slate-700' => $status != 'Berlangsung',
                ])>
                    <i @class([
                        'fas',
                        'fa-laptop-code',
                        'text-blue-600 dark:text-blue-400' => $status == 'Berlangsung',
                        'text-gray-500 dark:text-gray-400' => $status != 'Berlangsung',
                    ])></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $jadwal->mataKuliah->name }}</h4>
                        @if ($jadwal->status_text)
                            <span @class([
                                'text-xs font-semibold px-2 py-1 rounded-full',
                                // Kelas warna dinamis
                                'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' =>
                                    $jadwal->status_color == 'green',
                                'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' =>
                                    $jadwal->status_color == 'yellow',
                                'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200' =>
                                    $jadwal->status_color == 'gray',
                                // Tambahkan kelas animate-pulse jika is_blinking true
                                'animate-pulse' => $jadwal->is_blinking,
                            ])>{{ $jadwal->status_text }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $jamMulai->format('H:i') }} - {{ $jamSelesai->format('H:i') }} • <i
                            class="fas fa-location-dot fa-fw"></i>
                        {{ optional($jadwal->ruangan)->name ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <i class="fas fa-university fa-fw mr-1"></i>
                        {{ optional($jadwal->semester)->display_name }} - {{ optional($jadwal->prodi)->name }} -
                        <span class="font-medium">Gol. {{ $jadwal->nama_golongan_grup }}</span>
                    </p>
                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span><i class="fas fa-users mr-1"></i>{{ $jadwal->total_mahasiswa }} mahasiswa</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 px-6">
                <div
                    class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-check text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tidak Ada Jadwal</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Anda tidak memiliki jadwal mengajar hari ini.
                </p>
            </div>
        @endforelse
    </div>
</div>
