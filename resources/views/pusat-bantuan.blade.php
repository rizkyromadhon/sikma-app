<x-layout>
    <div class="bg-white dark:bg-black rounded-md text-gray-800 dark:text-gray-100 dark-mode-transition">
        <!-- Header -->
        <header class="text-gray-950 dark:text-gray-100 dark-mode-transition py-10 text-center">
            <h1 class="text-3xl font-bold">Pusat Bantuan</h1>
            <p class="mt-2 text-lg">Temukan Jawaban dan Panduan Seputar Sistem Kehadiran Mahasiswa</p>
        </header>

        <main x-data="{
            showModalAkun: false,
            showModalJadwal: false,
            showModalPanduan: false,
            searchQuery: ''
        }" x-init="$watch('showModalAkun || showModalJadwal || showModalPanduan', value => {
            document.body.classList.toggle('overflow-hidden', value);
        })" class="max-w-5xl mx-auto px-4 mb-20">
            <div class="mb-6">
                <label for="search" class="block mb-2 text-xl font-semibold">Cari Pertanyaan</label>
                <input x-model="searchQuery" id="search" type="text"
                    placeholder="Apa yang saya lakukan apabila Kartu RFID saya hilang?..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-blue-100 dark:focus:ring-gray-700 focus:outline-none dark:placeholder-gray-300 dark-mode-transition">
            </div>
            <!-- Categories -->
            <h2 class="text-xl font-semibold mb-4">Kategori Bantuan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div @click="showModalJadwal = true"
                    class="p-4 py-4 bg-white dark:bg-gray-900/60 rounded shadow hover:shadow-md transition cursor-pointer">
                    📋 Jadwal &
                    Presensi
                </div>
                <div @click="showModalAkun = true"
                    class="p-4 py-4 bg-white dark:bg-gray-900/60 rounded shadow hover:shadow-md transition cursor-pointer">
                    👤 Akun & Profil
                </div>
                <div @click="showModalPanduan = true"
                    class="p-4 py-4 bg-white dark:bg-gray-900/60 rounded shadow hover:shadow-md transition cursor-pointer">
                    📝 Panduan
                    Penggunaan
                </div>
            </div>

            <div class="mx-auto mt-8">
                <h2 class="text-xl font-semibold mb-4">Frequently Asked Questions (FAQ)</h2>
                <div class="mb-6">
                    <div class="space-y-2">
                        <div x-data="{
                            openItems: [],
                            searchQuery: '',
                            get filteredItems() {
                                return [
                                    { id: 1, question: 'Bagaimana cara melakukan presensi?', answer: 'Mahasiswa cukup menempelkan kartu RFID ke alat presensi sesuai jadwal kuliah yang berlaku.' },
                                    { id: 2, question: 'Apa yang terjadi jika saya presensi di luar waktu yang ditentukan?', answer: 'Sistem akan menolak presensi jika dilakukan di luar waktu jadwal kuliah (misalnya sebelum 08.00 atau setelah 10.00).' },
                                    { id: 3, question: 'Bisakah saya presensi lebih dari satu kali dalam satu jadwal?', answer: 'Tidak. Sistem membatasi hanya satu kali presensi per mahasiswa dalam satu rentang waktu jadwal.' },
                                    { id: 4, question: 'Bagaimana saya bisa melihat rekap presensi saya?', answer: 'Silakan login terlebih dahulu, kemudian buka menu Presensi Kuliah untuk melihat detail kehadiran Anda.' },
                                    { id: 5, question: 'Apakah saya bisa mengunduh rekap presensi?', answer: 'Ya. Anda dapat mengunduh laporan dalam format PDF/Excel dari menu Presensi Kuliah.' },
                                    { id: 6, question: 'Apa yang harus dilakukan jika kartu RFID saya hilang?', answer: 'Silakan hubungi admin program studi untuk menghapus kartu lama dan registrasi kartu baru.' },
                                    { id: 7, question: 'Mengapa presensi saya tidak tercatat?', answer: 'Pastikan waktu presensi sesuai jadwal. Jika sudah benar namun tidak tercatat, hubungi admin program studi.' },
                                    { id: 8, question: 'Teknologi apa yang digunakan dalam sistem ini?', answer: 'Sistem kehadiran mahasiswa ini menggunakan ESP32 dengan RFID reader dan Laravel 12 sebagai backend untuk mencatat presensi secara real-time.' },
                                    { id: 9, question: 'Apakah sistem bisa digunakan tanpa internet?', answer: 'Untuk saat ini sistem ini hanya dapat digunakan melalui jaringan lokal.' },
                                    { id: 10, question: 'Apakah saya bisa presensi menggunakan kartu teman saya?', answer: 'Tidak. Sistem mencatat data berdasarkan NIM yang terhubung dengan RFID.' },
                                    { id: 11, question: 'Apa yang terjadi jika saya lupa membawa kartu RFID?', answer: 'Silakan laporkan ke dosen pengampu atau admin program studi. Presensi manual dapat dilakukan hanya dalam kondisi tertentu.' },
                                    { id: 12, question: 'Bagaimana cara mendaftarkan kartu RFID saya?', answer: 'Admin program studi akan melakukan proses pendaftaran kartu RFID Anda melalui alat presensi yang telah disiapkan.' },
                                    { id: 13, question: 'Apakah saya perlu login setiap kali ingin presensi?', answer: 'Tidak perlu. Presensi dilakukan otomatis saat Anda menempelkan kartu ke alat.' },
                                ].filter(i => searchQuery
                                    .toLowerCase()
                                    .split(' ')
                                    .every(word =>
                                        i.question.toLowerCase().includes(word) ||
                                        i.answer.toLowerCase().includes(word)
                                    )
                                );
                            }
                        }" class="space-y-4">
                            <!-- Accordion list -->
                            <template x-if="filteredItems.length">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <div class="rounded">
                                        <button
                                            @click="openItems.includes(item.id) ? openItems = openItems.filter(i => i !== item.id) : openItems.push(item.id)"
                                            class="w-full flex items-center justify-between text-left px-4 py-3 bg-gray-100 dark:bg-gray-900/60 dark:hover:bg-gray-900 hover:bg-gray-200 font-semibold text-md rounded-t-md dark-mode-transition">
                                            <span x-text="item.id + '. ' + item.question"></span>
                                            <svg :class="openItems.includes(item.id) ? 'transform rotate-180' : ''"
                                                class="w-5 h-5 transition-transform duration-200 text-gray-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="openItems.includes(item.id)" x-transition
                                            class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 bg-gray-100/50 dark:bg-gray-900/40 rounded-b-md dark-mode-transition">
                                            <span x-text="item.answer"></span>
                                        </div>
                                    </div>
                                </template>
                            </template>

                            <!-- Jika tidak ditemukan -->
                            <template x-if="filteredItems.length === 0">
                                <div class="text-center text-gray-500 dark:text-gray-200 py-4 italic">
                                    Tidak ada pertanyaan yang cocok dengan pencarian Anda.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Akun -->
            <div x-show="showModalJadwal" x-cloak x-transition.opacity.duration.200
                class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50 dark:bg-black/70 dark-mode-transition"
                    @click="showModalJadwal = false"></div>

                <!-- Modal Box -->
                <div x-show="showModalJadwal" x-transition.scale.duration.200
                    class="relative bg-white dark:bg-gray-900/40 backdrop-blur-sm w-90 md:w-2xl rounded-md shadow min-h-[500px] z-50">

                    <!-- Tombol Tutup -->
                    <button @click="showModalJadwal = false"
                        class="absolute top-4 right-4 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 dark-mode-transition text-2xl z-10">
                        &times;
                    </button>

                    <!-- Judul -->
                    <div class="px-6 pt-10 pb-4">
                        <h1 class="text-center text-xl md:text-2xl font-semibold">Jadwal & Presensi</h1>
                    </div>

                    <!-- Konten scrollable -->
                    <div x-data="{ openItems: [] }"
                        class="custom-scrollbar px-6 pb-10 max-h-[350px] overflow-y-auto space-y-4 mt-8">
                        <!-- Accordion -->
                        <template
                            x-for="item in [
                                { id: 1, question: 'Apa itu jadwal kuliah?', answer: 'Jadwal kuliah adalah waktu yang sudah ditentukan untuk setiap mata kuliah yang harus diikuti oleh mahasiswa.' },
                                { id: 2, question: 'Bagaimana cara melihat jadwal saya?', answer: 'Anda dapat melihat jadwal kuliah melalui dashboard jadwal kuliah.' },
                                { id: 3, question: 'Kapan saya bisa melakukan presensi?', answer: 'Presensi dapat dilakukan saat jadwal kuliah dimulai hingga batas toleransi yang diberikan (15 menit).' },
                                { id: 4, question: 'Apa yang terjadi jika saya terlambat presensi?', answer: 'Jika Anda melakukan presensi setelah batas toleransi, maka presensi dianggap tidak sah atau tercatat sebagai tidak hadir.' },
                                { id: 5, question: 'Apakah saya bisa presensi lebih dari satu kali?', answer: 'Tidak. Sistem hanya mengizinkan satu kali presensi per jadwal per hari.' },
                                { id: 6, question: 'Bagaimana jika tidak ada jadwal tapi saya ingin presensi?', answer: 'Presensi hanya tersedia jika ada jadwal yang sesuai dengan waktu dan hari tersebut.' }
                            ]"
                            :key="item.id">
                            <div class="rounded border border-gray-200 dark:border-gray-700">
                                <button
                                    @click="openItems.includes(item.id) ? openItems = openItems.filter(i => i !== item.id) : openItems.push(item.id)"
                                    class="w-full flex items-center justify-between text-left px-4 py-3 bg-gray-100 dark:bg-gray-900/60 hover:bg-gray-200 dark:hover:bg-gray-700 font-semibold text-md md:text-xl transition">
                                    <span x-text="item.id + '. ' + item.question"></span>
                                    <!-- Chevron -->
                                    <svg :class="openItems.includes(item.id) ? 'transform rotate-180' : ''"
                                        class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0 transition-transform duration-200 text-gray-500 dark:text-gray-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openItems.includes(item.id)" x-transition
                                    class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                    <span x-text="item.answer"></span>
                                </div>
                            </div>
                        </template>

                    </div>
                </div>
            </div>

            <!-- Modal Akun -->
            <div x-show="showModalAkun" x-cloak x-transition.opacity.duration.300
                class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50 dark:bg-black/70 dark-mode-transition"
                    @click="showModalAkun = false"></div>

                <!-- Modal Box -->
                <div x-show="showModalAkun" x-transition.scale.duration.300
                    class="relative bg-white dark:bg-gray-900/40 backdrop-blur-sm w-90 md:w-2xl rounded-md shadow min-h-[500px] z-50">

                    <!-- Tombol Tutup -->
                    <button @click="showModalAkun = false"
                        class="absolute top-4 right-4 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 dark-mode-transition text-2xl z-10">
                        &times;
                    </button>

                    <!-- Judul -->
                    <div class="px-6 pt-10 pb-4">
                        <h1 class="text-center text-xl md:text-2xl font-semibold">Akun & Profil</h1>
                    </div>

                    <!-- Konten scrollable -->
                    <div x-data="{ openItems: [] }"
                        class="custom-scrollbar px-6 pb-10 max-h-[350px] overflow-y-auto space-y-4 mt-8">
                        <!-- Accordion -->
                        <template
                            x-for="item in [
                                { id: 1, question: 'Apa itu akun dan profil?', answer: 'Akun dan profil adalah bagian dari sistem kehadiran mahasiswa yang digunakan untuk mengelola informasi pribadi dan pengaturan akun.' },
                                { id: 2, question: 'Bagaimana cara membuat akun?', answer: 'Untuk membuat akun, Anda dapat mengunjungi halaman daftar pada sistem kehadiran mahasiswa dan mengisi formulir pendaftaran.' },
                                { id: 3, question: 'Bagaimana cara mengedit profil?', answer: 'Untuk mengedit profil, Anda dapat mengunjungi halaman profil pada sistem kehadiran mahasiswa dan mengisi formulir pengeditan profil.' }
                            ]"
                            :key="item.id">
                            <div class="rounded border border-gray-200 dark:border-gray-700"> <button
                                    @click="openItems.includes(item.id) ? openItems = openItems.filter(i => i !== item.id) : openItems.push(item.id)"
                                    class="w-full flex items-center justify-between text-left px-4 py-3 bg-gray-100 dark:bg-gray-900/60 hover:bg-gray-200 dark:hover:bg-gray-700 font-semibold text-md md:text-xl transition">
                                    <span x-text="item.id + '. ' + item.question"></span>
                                    <svg :class="openItems.includes(item.id) ? 'transform rotate-180' : ''"
                                        class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0 transition-transform duration-200 text-gray-500 dark:text-gray-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openItems.includes(item.id)" x-transition
                                    class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200"> <span
                                        x-text="item.answer"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div x-show="showModalPanduan" x-cloak x-transition.opacity.duration.200
                class="fixed inset-0 z-50 flex items-center justify-center">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50 dark:bg-black/70 dark-mode-transition"
                    @click="showModalPanduan = false"></div>

                <!-- Modal Box -->
                <div x-show="showModalPanduan" x-transition.scale.duration.200
                    class="relative bg-white dark:bg-gray-900/40 backdrop-blur-sm w-90 md:w-2xl rounded-md shadow min-h-[500px] z-50">

                    <!-- Tombol Tutup -->
                    <button @click="showModalPanduan = false"
                        class="absolute top-4 right-4 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 dark-mode-transition text-2xl z-10">
                        &times;
                    </button>

                    <!-- Judul -->
                    <div class="px-6 pt-10 pb-4">
                        <h1 class="text-center text-xl md:text-2xl font-semibold">Panduan Penggunaan</h1>
                    </div>

                    <!-- Konten scrollable -->
                    <div x-data="{ openItems: [] }"
                        class="custom-scrollbar px-6 pb-10 max-h-[350px] overflow-y-auto space-y-4 mt-8">
                        <!-- Accordion -->
                        <template
                            x-for="item in [
                                { id: 1, question: 'Bagaimana cara login ke sistem?', answer: 'Untuk login, buka halaman utama kemudian klik menu login pada navigasi, kemudian masukkan email serta password Anda pada form login.' },
                                { id: 2, question: 'Bagaimana cara melakukan presensi?', answer: 'Presensi dapat dilakukan melalui alat presensi yang ada pada ruang lab/kelas kemudian lakukan scan menggunakan rfid card yang telah diberikan oleh admin program studi.' },
                                { id: 3, question: 'Bagaimana cara melihat riwayat presensi?', answer: 'Anda dapat melihat riwayat presensi pada menu navigasi Presensi Kuliah, yang menampilkan daftar kehadiran berdasarkan tanggal dan mata kuliah.' },
                                { id: 4, question: 'Apa yang harus saya lakukan jika lupa password?', answer: 'Klik Lupa Password di halaman login, kemudian ikuti langkah-langkah pemulihan yang dikirimkan melalui email.' },
                                { id: 5, question: 'Bagaimana cara mendaftarkan RFID?', answer: 'Untuk mendaftarkan RFID, Anda harus melakukan laporan ke admin program studi dengan cara mendatangi langsung ke kantor admin program studi.' },
                                { id: 6, question: 'Bagaimana jika kartu RFID saya hilang/rusak?', answer: 'Apabila kartu RFID anda rusak/hilang, Anda bisa menghubungi Admin Program Studi pada menu Hubungi Admin untuk melakukan request penggantian kartu.' }
                            ]"
                            :key="item.id">
                            <div class="rounded border border-gray-200 dark:border-gray-700">
                                <button
                                    @click="openItems.includes(item.id) ? openItems = openItems.filter(i => i !== item.id) : openItems.push(item.id)"
                                    class="w-full flex items-center justify-between text-left px-4 py-3 bg-gray-100 dark:bg-gray-900/60 hover:bg-gray-200 dark:hover:bg-gray-700 font-semibold text-md md:text-xl transition">
                                    <span x-text="item.id + '. ' + item.question"></span>
                                    <!-- Chevron -->
                                    <svg :class="openItems.includes(item.id) ? 'transform rotate-180' : ''"
                                        class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0 transition-transform duration-200 text-gray-500 dark:text-gray-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openItems.includes(item.id)" x-transition
                                    class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                    <span x-text="item.answer"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </main>


        <!-- Footer -->
        <footer class="text-center text-sm text-gray-500 dark:text-gray-200 py-6">
            &copy; 2025 Sistem Kehadiran Mahasiswa. Semua hak dilindungi.
        </footer>
    </div>

    <script>
        const btnOpenAkunModal = document.getElementById('btnOpenAkunModal');
        const btnCloseAkunModal = document.getElementById('btnCloseAkunModal');
        const modalAkun = document.getElementById('modalAkun');

        btnOpenAkunModal.addEventListener('click', () => {
            modalAkun.classList.remove('hidden');
        });

        btnCloseAkunModal.addEventListener('click', () => {
            modalAkun.classList.add('hidden');
        });
    </script>
</x-layout>
