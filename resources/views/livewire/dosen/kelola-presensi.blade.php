<div class="space-y-6">
    @forelse ($jadwalDosen as $jadwal)
        <div wire:key="jadwal-{{ $jadwal['id'] }}" data-jadwal-id="{{ $jadwal['id'] }}"
            data-jam-mulai="{{ $jadwal['jam_mulai'] }}" data-jam-selesai="{{ $jadwal['jam_selesai'] }}"
            data-tanggal="{{ $selectedDate->format('Y-m-d') }}"
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                {{ \Carbon\Carbon::parse($jadwal['jam_mulai'])->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($jadwal['jam_selesai'])->format('H:i') }}
                            </p>
                            {{-- Status yang akan diupdate secara realtime --}}
                            <span
                                class="status-kelas text-xs px-2 py-1 rounded-full font-medium
                                @if ($jadwal['status_kelas'] == 'Berlangsung') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 animate-pulse
                                @elseif($jadwal['status_kelas'] == 'Selesai')
                                    bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                @else
                                    bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 @endif">
                                @if ($jadwal['status_kelas'] == 'Berlangsung')
                                    <i class="fas fa-circle fa-xs mr-1"></i> Berlangsung
                                @elseif($jadwal['status_kelas'] == 'Selesai')
                                    Selesai
                                @else
                                    Akan Datang
                                @endif
                            </span>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $jadwal['nama_matkul'] }}</h4>
                        <div
                            class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt fa-fw"></i>
                                {{ $jadwal['nama_ruangan'] }}</span>
                            <span class="flex items-center gap-1.5"><i class="fas fa-university fa-fw"></i>
                                {{ $jadwal['display_semester'] }}</span>
                        </div>
                        <div class="mt-1 flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-layer-group fa-fw"></i>
                            <span>{{ $jadwal['nama_prodi'] }} - Golongan {{ $jadwal['semua_golongan_string'] }}</span>
                            @if ($jadwal['is_kelas_besar'])
                                <span
                                    class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-0.5 rounded-full">Kelas
                                    Besar</span>
                            @endif
                        </div>
                    </div>
                    <div class="w-full md:w-64 flex-shrink-0">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kehadiran</p>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $jadwal['jumlah_hadir'] }} /
                                {{ $jadwal['total_mahasiswa_kelas'] }} <span
                                    class="text-sm font-normal">Mahasiswa</span></p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ round($jadwal['persentase_kehadiran']) }}%</p>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $jadwal['persentase_kehadiran'] }}%"></div>
                        </div>
                        <a href="{{ route('dosen.presensi.detail', ['jadwal' => $jadwal['id'], 'tanggal' => $selectedDate->format('Y-m-d')]) }}"
                            class="mt-4 w-full block text-center bg-gray-800 dark:bg-slate-700 hover:bg-black dark:hover:bg-slate-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                            Lihat & Validasi Presensi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div
            class="text-center py-16 px-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div
                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tidak Ada Jadwal Mengajar</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tidak ada jadwal mengajar pada tanggal
                <strong>{{ $selectedDate->translatedFormat('d F Y') }}</strong>.
            </p>
        </div>
    @endforelse

    {{-- JavaScript yang diperbaiki dengan realtime status update --}}
    <script>
        // ========== REALTIME STATUS UPDATE ==========
        function updateStatusKelas() {
            const now = new Date();
            const jadwalElements = document.querySelectorAll('[data-jadwal-id]');

            jadwalElements.forEach(element => {
                const jamMulai = element.dataset.jamMulai;
                const jamSelesai = element.dataset.jamSelesai;
                const tanggalStr = element.dataset.tanggal;

                // Parse waktu mulai dan selesai
                const waktuMulai = new Date(`${tanggalStr} ${jamMulai}`);
                const waktuSelesai = new Date(`${tanggalStr} ${jamSelesai}`);

                const statusElement = element.querySelector('.status-kelas');
                if (!statusElement) return;

                let statusBaru = '';
                let newClasses = [];

                // Remove all status classes
                const classesToRemove = ['bg-green-100', 'dark:bg-green-900', 'text-green-800',
                    'dark:text-green-200',
                    'bg-gray-100', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200',
                    'bg-blue-100', 'dark:bg-blue-900', 'text-blue-800', 'dark:text-blue-200', 'animate-pulse'
                ];

                statusElement.classList.remove(...classesToRemove);

                if (now >= waktuMulai && now <= waktuSelesai) {
                    // Sedang berlangsung
                    statusBaru = '<i class="fas fa-circle fa-xs mr-1"></i> Berlangsung';
                    statusElement.classList.add('bg-green-100', 'dark:bg-green-900', 'text-green-800',
                        'dark:text-green-200', 'animate-pulse');
                } else if (now > waktuSelesai) {
                    // Sudah selesai
                    statusBaru = 'Selesai';
                    statusElement.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-800',
                        'dark:text-gray-200');
                } else {
                    // Akan datang
                    statusBaru = 'Akan Datang';
                    statusElement.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-800',
                        'dark:text-blue-200');
                }

                statusElement.innerHTML = statusBaru;
            });
        }

        // Cleanup function untuk timer
        function cleanupStatusTimer() {
            if (window.statusTimer) {
                clearInterval(window.statusTimer);
                window.statusTimer = null;
            }
        }

        // Setup timer untuk update status setiap 30 detik
        function setupStatusTimer() {
            cleanupStatusTimer();

            // Update pertama kali
            updateStatusKelas();

            // Setup interval setiap 30 detik
            window.statusTimer = setInterval(updateStatusKelas, 30000);
        }

        // ========== ECHO LISTENERS ==========
        // Cleanup function untuk menghindari multiple listeners
        function cleanupEchoListeners() {
            if (typeof window.echoChannels !== 'undefined') {
                window.echoChannels.forEach(channel => {
                    if (window.Echo) {
                        window.Echo.leave(channel);
                    }
                });
                window.echoChannels = [];
            }
        }

        // Setup Echo listeners
        function setupEchoListeners() {
            if (typeof window.Echo !== 'undefined') {
                // Cleanup existing listeners first
                cleanupEchoListeners();

                let jadwalIds = @json(collect($jadwalDosen)->pluck('id')->toArray());
                window.echoChannels = [];

                console.log('Setting up Echo listeners for jadwal IDs:', jadwalIds);

                jadwalIds.forEach((id) => {
                    let channelName = `kelas.${id}`;
                    window.echoChannels.push(channelName);

                    window.Echo.private(channelName)
                        .listen('.App\\Events\\KehadiranDiperbarui', (e) => {
                            console.log('Received KehadiranDiperbarui event for jadwal:', id, e);

                            // Dispatch event ke Livewire component
                            Livewire.dispatch('kehadiran-diperbarui');
                        })
                        .error((error) => {
                            console.error('Echo error for channel', channelName, ':', error);
                        });
                });
            } else {
                console.warn('Echo not available');
            }
        }

        // ========== EVENT LISTENERS ==========
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setupEchoListeners();
            setupStatusTimer();
        });

        // Re-initialize on Livewire navigation (for SPA)
        document.addEventListener('livewire:navigated', function() {
            setupEchoListeners();
            setupStatusTimer();
        });

        // Re-initialize after Livewire updates
        document.addEventListener('livewire:updated', function() {
            setupEchoListeners();
            setupStatusTimer();
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            cleanupEchoListeners();
            cleanupStatusTimer();
        });

        // Update saat tab menjadi aktif kembali
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateStatusKelas();
            }
        });
    </script>
</div>
