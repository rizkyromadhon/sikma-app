<x-layout hide-navbar>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div id="monitoring-container"
        class="flex flex-col items-center justify-center p-4 bg-white text-slate-800 dark:bg-black dark:text-white custom-scrollbar">

        <a href="{{ route('home') }}"
            class="absolute top-8 left-8 text-2xl font-bold tracking-tighter text-slate-800 dark:text-white/80">
            SIKMA
        </a>
        <div x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => {
            document.documentElement.classList.toggle('dark', val);
            localStorage.setItem('theme', val ? 'dark' : 'light');
        })"
            class="absolute top-8 right-8 flex items-center space-x-3 text-lg font-medium text-slate-800 dark:text-white/80">
            <button @click="darkMode = !darkMode"
                class="w-9 h-9 flex items-center justify-center rounded-full bg-white dark:bg-black text-gray-700 dark:text-gray-100 shadow transition-all mt-1 ">

                <i class="fas fa-sun  transition-all duration-300 ease-in-out hover:rotate-45"
                    :class="darkMode ? 'opacity-0 rotate-180 scale-0' : 'opacity-100 rotate-0 scale-100'"></i>

                <i class="fas fa-moon  absolute transition-all duration-300 ease-in-out hover:-rotate-40"
                    :class="darkMode ? 'opacity-100 rotate-0 scale-100' : 'opacity-0 -rotate-180 scale-0'"></i>
            </button>

            <div id="current-date" class="whitespace-nowrap"></div>
        </div>

        <div id="main-card-container"
            class="w-full max-w-4xl bg-white/80 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-300 dark:border-slate-700/80 rounded-3xl shadow-2xl shadow-black/30 p-8 relative">

            <div id="glow-effect"
                class="absolute -inset-2 rounded-3xl blur-xl transition-all duration-1000 bg-blue-300/60 dark:bg-blue-500/10">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                <div class="flex flex-col items-center text-center md:col-span-1">
                    <div class="relative">
                        <img id="profile-photo" src="{{ asset('img/avatar-default.png') }}" alt="Foto Profil"
                            class="w-40 h-40 rounded-full object-cover border-4 border-slate-700 shadow-lg mb-4">
                        <div id="photo-status-indicator"
                            class="absolute bottom-4 right-4 h-6 w-6 rounded-full border-2 border-slate-800 flex items-center justify-center bg-slate-500">
                            <i id="icon-success" class="fas fa-check text-white text-xs" style="display: none"></i>
                            <i id="icon-error" class="fas fa-times text-white text-xs" style="display: none"></i>
                        </div>
                    </div>
                    <h2 id="student-name" class="text-2xl font-bold text-slate-700 dark:text-white">Menunggu Presensi
                    </h2>
                    <p id="student-nim" class="text-lg text-slate-500 dark:text-slate-400">----------</p>
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        <span id="student-prodi"
                            class="bg-slate-700/80 text-slate-300 text-xs font-medium px-2.5 py-1 rounded-full">-</span>
                        <span id="student-semester"
                            class="bg-slate-700/80 text-slate-300 text-xs font-medium px-2.5 py-1 rounded-full">-</span>
                        <span id="student-golongan"
                            class="bg-slate-700/80 text-slate-300 text-xs font-medium px-2.5 py-1 rounded-full">Gol.
                            -</span>
                    </div>
                </div>

                <div class="flex flex-col justify-center md:col-span-2 space-y-6">
                    <div id="status-box"
                        class="text-center md:text-left p-4 rounded-xl transition-colors duration-500 bg-slate-100 dark:bg-slate-800/50 border border-slate-300 dark:border-slate-700">
                        <h3 id="status-text"
                            class="text-3xl font-black tracking-wider uppercase text-slate-700 dark:text-slate-300">
                            Status
                        </h3>
                        <p id="status-message" class="text-slate-500 dark:text-slate-400 mt-1">Silakan lakukan presensi
                            dengan menempelkan
                            kartu RFID Anda.</p>
                    </div>

                    <div class="text-center md:text-left">
                        <p class="text-sm uppercase font-semibold text-blue-600 dark:text-blue-400 tracking-widest">
                            Mata Kuliah Saat Ini
                        </p>
                        <h4 id="course-subject" class="text-2xl font-extrabold text-slate-700 dark:text-white mt-1">
                            Tidak Ada Jadwal</h4>
                        <div
                            class="flex items-center justify-center md:justify-start space-x-4 mt-2 text-slate-600 dark:text-slate-400 text-lg">
                            <span class="flex items-center"><i class="fas fa-clock mr-2 opacity-70"></i><span
                                    id="course-time">--:-- - --:--</span></span>
                            <span class="flex items-center"><i class="fas fa-map-marker-alt mr-2 opacity-70"></i><span
                                    id="course-room">-</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full max-w-4xl mt-8">
            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 px-2">
                RIWAYAT TERAKHIR</h3>
            <div id="history-container" class="space-y-2 custom-scrollbar">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dom = {
                mainCard: document.getElementById('main-card-container'),
                glowEffect: document.getElementById('glow-effect'),
                photo: document.getElementById('profile-photo'),
                photoStatus: document.getElementById('photo-status-indicator'),
                iconSuccess: document.getElementById('icon-success'),
                iconError: document.getElementById('icon-error'),
                name: document.getElementById('student-name'),
                nim: document.getElementById('student-nim'),
                prodi: document.getElementById('student-prodi'),
                semester: document.getElementById('student-semester'),
                golongan: document.getElementById('student-golongan'),
                statusBox: document.getElementById('status-box'),
                statusText: document.getElementById('status-text'),
                statusMessage: document.getElementById('status-message'),
                subject: document.getElementById('course-subject'),
                time: document.getElementById('course-time'),
                room: document.getElementById('course-room'),
                currentDate: document.getElementById('current-date'),
                historyContainer: document.getElementById('history-container')
            };

            const MAX_HISTORY = 5;

            function updateDate() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                dom.currentDate.textContent = now.toLocaleDateString('id-ID', options);
            }
            updateDate();
            setInterval(updateDate, 60000);

            function updateDisplay(eventData) {
                const {
                    status,
                    data
                } = eventData;
                const isSuccess = status === 'success';
                const isNotFound = status === 'not_found';
                const isExists = status === 'sudah_presensi';

                dom.name.textContent = data.nama || 'User tidak terdaftar';
                dom.nim.textContent = data.nim || (data.uid || '-------');
                if (data.foto && data.foto.trim() !== '') {
                    if (data.foto.startsWith('http')) {
                        dom.photo.src = data.foto;
                    } else {
                        dom.photo.src = `/storage/${data.foto.replace(/^\/?storage\//, '')}`;
                    }
                } else {
                    dom.photo.src = "{{ asset('img/avatar-default.png') }}";
                };
                dom.prodi.textContent = data.prodi || '-';
                dom.semester.textContent = data.semester || '-';
                dom.golongan.textContent = `Gol. ${data.golongan || '-'}`;

                if (data.jadwal) {
                    dom.subject.textContent = data.jadwal.mata_kuliah || 'Nama Matkul?';
                    dom.time.textContent = `${data.jadwal.jam_mulai} - ${data.jadwal.jam_selesai}`;
                    dom.room.textContent = data.ruangan || '-';
                } else {
                    dom.subject.textContent = 'Tidak Ada Jadwal';
                    dom.time.textContent = '--:-- - --:--';
                    dom.room.textContent = '-';
                }

                dom.statusMessage.textContent = data.message || 'Tidak ada pesan.';
                dom.statusText.textContent = isSuccess ? 'Presensi Berhasil' : 'Presensi Gagal';

                dom.glowEffect.className =
                    'absolute -inset-2 rounded-3xl blur-xl transition-all duration-1000';
                dom.photoStatus.className =
                    'absolute bottom-4 right-4 h-6 w-6 rounded-full border-2 border-slate-400 dark:border-slate-800 flex items-center justify-center';
                dom.statusBox.className = 'text-center md:text-left p-4 rounded-xl transition-colors duration-500';
                dom.statusText.className = 'text-3xl font-black tracking-wider uppercase';
                dom.iconSuccess.style.display = 'none';
                dom.iconError.style.display = 'none';

                if (isSuccess) {
                    dom.glowEffect.classList.add('bg-green-300/60', 'dark:bg-green-500/20');
                    dom.photoStatus.classList.add('bg-green-400', 'dark:bg-green-500');
                    dom.statusBox.classList.add('bg-green-100/80', 'dark:bg-green-500/10', 'border',
                        'border-green-300', 'dark:border-green-500/30');
                    dom.statusText.classList.add('text-green-700', 'dark:text-green-400');
                    dom.iconSuccess.style.display = 'inline-block';
                } else if (isNotFound) {
                    dom.glowEffect.classList.add('bg-yellow-200/60', 'dark:bg-yellow-500/20');
                    dom.photoStatus.classList.add('bg-yellow-400', 'dark:bg-yellow-500');
                    dom.statusBox.classList.add('bg-yellow-100/80', 'dark:bg-yellow-500/10', 'border',
                        'border-yellow-300', 'dark:border-yellow-500/30');
                    dom.statusText.classList.add('text-yellow-700', 'dark:text-yellow-400');
                    dom.iconError.style.display = 'inline-block';
                } else if (isExists) {
                    dom.glowEffect.classList.add('bg-blue-200/60', 'dark:bg-blue-500/20');
                    dom.photoStatus.classList.add('bg-blue-400', 'dark:bg-blue-500');
                    dom.statusBox.classList.add('bg-blue-100/80', 'dark:bg-blue-500/10', 'border',
                        'border-blue-300', 'dark:border-blue-500/30');
                    dom.statusText.classList.add('text-blue-700', 'dark:text-blue-400');
                    dom.iconError.style.display = 'inline-block';
                } else {
                    dom.glowEffect.classList.add('bg-red-300/60', 'dark:bg-red-500/20');
                    dom.photoStatus.classList.add('bg-red-400', 'dark:bg-red-500');
                    dom.statusBox.classList.add('bg-red-100/80', 'dark:bg-red-500/10', 'border', 'border-red-300',
                        'dark:border-red-500/30');
                    dom.statusText.classList.add('text-red-700', 'dark:text-red-400');
                    dom.iconError.style.display = 'inline-block';
                }

                const now = new Date();
                const statusClass = isSuccess ?
                    'bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400' :
                    'bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400';
                const statusText = isSuccess ? 'Sukses' : 'Gagal';

                const newHistoryItemHTML = `
                    <div class="history-item bg-slate-700 dark:bg-slate-900/50 border border-slate-600 dark:border-slate-800 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="${dom.photo.src}" class="w-8 h-8 rounded-full object-cover mr-4">
                            <div>
                                <p class="font-medium text-slate-200">${dom.name.textContent}</p>
                                <p class="text-xs text-slate-300 dark:text-slate-500">${now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold uppercase px-2 py-1 rounded-md ${statusClass}">
                            ${statusText}
                        </span>
                    </div>
                `;

                dom.historyContainer.insertAdjacentHTML('afterbegin', newHistoryItemHTML);

                if (dom.historyContainer.children.length > MAX_HISTORY) {
                    dom.historyContainer.lastElementChild.remove();
                }

                dom.mainCard.classList.remove('card-flash');
                void dom.mainCard.offsetWidth;
                dom.mainCard.classList.add('card-flash');
            }

            if (window.Echo) {
                window.Echo.channel('monitoring-channel')
                    .listen('PresensiUntukMonitoring', (e) => {
                        console.log('EVENT DITERIMA:', e);
                        updateDisplay(e);
                    });
                console.log("Mendengarkan event di channel 'monitoring-channel'...");
            } else {
                console.error('Laravel Echo tidak terdefinisi! Pastikan sudah dikonfigurasi.');
            }
        });
    </script>

    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        #monitoring-container {
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: start;
            padding-top: 2rem;
            align-items: center;
            flex-direction: column;
        }

        #history-container {
            max-height: 200px;
            overflow-y: auto;
            scrollbar-gutter: stable;
        }

        .card-flash {
            animation: card-flash-animation 0.7s ease-out;
        }

        @keyframes card-flash-animation {
            from {
                opacity: 0.5;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .history-item {
            animation: history-item-fade-in 0.5s ease-out forwards;
        }

        @keyframes history-item-fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #photo-status-indicator i {
            transition: opacity 0.3s ease;
        }
    </style>
</x-layout>
