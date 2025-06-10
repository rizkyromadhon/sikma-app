@extends('dosen.layouts')

@section('dosen-content')
    <!-- Modern Header with Gradient Background -->
    <header
        class="bg-white/95 dark:bg-slate-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Pusat Pengumuman</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kelola dan bagikan informasi penting.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content with Modern Layout -->
    <main class="min-h-screen">
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-8">

                <!-- Form Pengumuman - Enhanced Design -->
                <div class="xl:col-span-3 order-2 xl:order-1">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border-0 overflow-hidden">
                        <!-- Form Content -->
                        <form action="{{ route('dosen.pengumuman.store') }}" method="POST" enctype="multipart/form-data"
                            class="p-8 space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="space-y-3">
                                    <label for="semester"
                                        class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Semester
                                    </label>
                                    <select name="semester" id="semester" required
                                        class="w-full px-4 py-3 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-all">
                                        <option value="all">Semua Semester</option>
                                        @foreach ($semesterOptions as $semester)
                                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-3">
                                    <label for="prodi"
                                        class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.75 2.524z" />
                                        </svg>
                                        Program Studi
                                    </label>
                                    <select name="prodi" id="prodi" required
                                        class="w-full px-4 py-3 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-all">
                                        <option value="all">Semua Program Studi</option>
                                        @foreach ($prodiOptions as $prodi)
                                            <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-3">
                                    <label for="golongan"
                                        class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                        </svg>
                                        Golongan
                                    </label>
                                    <select name="golongan" id="golongan" required
                                        class="w-full px-4 py-3 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-all">
                                        <option value="all">Semua Golongan</option>
                                        @foreach ($golonganOptions as $golongan)
                                            <option value="{{ $golongan->id }}">{{ $golongan->nama_golongan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Info Target Mahasiswa -->
                            <div id="target-info"
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Target Penerima</p>
                                        <p id="jumlah-mahasiswa" class="text-xs text-blue-600 dark:text-blue-300">
                                            Memuat...
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tipe Pengumuman dengan Icon -->
                            <div class="space-y-3">
                                <label for="tipe"
                                    class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Kategori Pengumuman
                                </label>
                                <select name="tipe" id="tipe"
                                    class="w-full px-4 py-3 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-all">
                                    <option value="Informasi Umum">Informasi Umum</option>
                                    <option value="Tugas Baru">Tugas Baru</option>
                                    <option value="Materi Tambahan">Materi Tambahan</option>
                                    <option value="Perkuliahan Ditiadakan">Perkuliahan Ditiadakan</option>
                                </select>
                            </div>
                            <div id="jadwal-kuliah-container" class="space-y-3 hidden">
                                <label for="jadwal_kuliah_ditiadakan"
                                    class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Pilih Jadwal yang Ditiadakan
                                </label>
                                <select name="jadwal_kuliah_ditiadakan" id="jadwal_kuliah_ditiadakan"
                                    class="w-full px-4 py-3 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white transition-all">
                                    <option value="">Pilih Jadwal</option>
                                    @foreach ($jadwalOptions as $jadwal)
                                        {{-- TAMBAHKAN atribut data-short-name di bawah ini --}}
                                        <option value="{{ $jadwal->display_name }}"
                                            data-short-name="{{ $jadwal->short_name }}">
                                            {{ $jadwal->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Konten Pengumuman -->
                            <div class="space-y-3">
                                <label for="konten"
                                    class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Isi Pengumuman
                                </label>
                                <textarea name="konten" id="konten" rows="6" required
                                    class="w-full px-4 py-3 text-sm border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-700 dark:text-white resize-none transition-all placeholder:text-gray-400 placeholder:dark:text-slate-400"
                                    placeholder="Tulis pengumuman Anda di sini..."></textarea>
                                <div class="flex justify-between items-center text-xs text-gray-400">
                                    <span>Minimum 10 karakter</span>
                                    <span id="char-count">0 karakter</span>
                                </div>
                            </div>

                            <!-- File Upload dengan Drag & Drop Style -->
                            <div class="space-y-3">
                                <label for="file_lampiran"
                                    class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Lampiran File (Opsional)
                                </label>
                                <div class="relative">
                                    <input type="file" name="file_lampiran" id="file_lampiran" class="sr-only">
                                    <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl p-8 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer"
                                        onclick="document.getElementById('file_lampiran').click()">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 48 48">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" />
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-300 font-medium">Klik untuk upload file</p>
                                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, PNG, JPG (Max: 10MB)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit"
                                    class="group relative w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-xl">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2 group-hover:animate-pulse" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Kirim Pengumuman
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar: Riwayat & Quick Actions -->
                <div class="col-span-2 order-2 space-y-8">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden h-[59rem]">
                        <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Terbaru</h4>
                                <span
                                    class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full">
                                    {{ $riwayatPengumuman->count() }} Total
                                </span>
                            </div>
                        </div>

                        <div class="max-h-[50rem] overflow-y-auto custom-scrollbar">
                            @forelse ($riwayatPengumuman as $riwayat)
                                <div
                                    class="p-4 border-b border-gray-50 dark:border-slate-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center mb-1">
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    <span class="font-bold text-gray-700 dark:text-gray-200">Anda</span>
                                                    mengirim ke:
                                                </p>
                                                <p class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                                    {{-- Menggunakan Carbon::parse() karena masih dari Query Builder --}}
                                                    {{ \Carbon\Carbon::parse($riwayat->created_at)->diffForHumans() }}
                                                </p>
                                            </div>

                                            {{-- Tampilkan detail target yang sudah kita buat --}}
                                            @if ($riwayat->target_detail_text)
                                                <p class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">
                                                    {{ $riwayat->target_detail_text }} ({{ $riwayat->jumlah_penerima }}
                                                    Mahasiswa)
                                                </p>
                                            @endif

                                            <p
                                                class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed line-clamp-2">
                                                {{ $riwayat->konten }}
                                            </p>

                                            @if ($riwayat->tipe)
                                                <span
                                                    class="inline-block mt-2 px-2 py-1 bg-gray-100 dark:bg-slate-600 text-gray-600 dark:text-gray-300 text-xs rounded-md">
                                                    {{ $riwayat->tipe }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4c0-1.313.253-2.566.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <h3 class="text-gray-500 dark:text-gray-400 font-medium mb-2">Belum ada pengumuman</h3>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Mulai bagikan informasi kepada
                                        mahasiswa Anda</p>
                                </div>
                            @endforelse
                        </div>

                        @if ($riwayatPengumuman->count() >= 5)
                            <div class="p-4 border-t border-gray-100 dark:border-slate-700">
                                <a href="#"
                                    class="w-full text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium block">
                                    Lihat Semua Riwayat
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const semesterSelect = document.getElementById('semester');
            const prodiSelect = document.getElementById('prodi');
            const golonganSelect = document.getElementById('golongan');
            const textarea = document.getElementById('konten');
            const charCount = document.getElementById('char-count');
            const templateButtons = document.querySelectorAll('.template-btn');
            const tipeSelect = document.getElementById('tipe');
            const kontenTextarea = document.getElementById('konten');
            const jadwalContainer = document.getElementById('jadwal-kuliah-container');
            const jadwalSelect = document.getElementById('jadwal_kuliah_ditiadakan');
            const jumlahMahasiswaEl = document.getElementById('jumlah-mahasiswa');
            const charCountEl = document.getElementById('char-count');

            function toggleJadwalDropdown() {
                if (tipeSelect.value === 'Perkuliahan Ditiadakan') {
                    jadwalContainer.classList.remove('hidden');
                } else {
                    jadwalContainer.classList.add('hidden');
                    if (kontenTextarea.value.includes(
                            "Diberitahukan kepada seluruh mahasiswa bahwa perkuliahan untuk")) {
                        kontenTextarea.value = '';
                        kontenTextarea.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                }
            }

            function updateJadwalOptions() {
                const semester = semesterSelect.value;
                const prodi = prodiSelect.value;

                jadwalSelect.innerHTML = '<option value="">Memuat jadwal...</option>';

                fetch('{{ route('dosen.pengumuman.getJadwalOptions') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCSRFToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            semester,
                            prodi
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';
                        if (data.success && data.jadwal) {
                            data.jadwal.forEach(jadwal => {
                                const option = new Option(jadwal.display_name, jadwal.display_name);
                                option.dataset.shortName = jadwal.short_name;
                                jadwalSelect.add(option);
                            });
                        }
                    })
                    .catch(error => console.error('Error fetching jadwal options:', error));
            }

            tipeSelect.addEventListener('change', toggleJadwalDropdown);

            jadwalSelect.addEventListener('change', function() {
                if (this.value) { // Jika ada jadwal yang dipilih
                    const jadwalText = this.options[this.selectedIndex].dataset.shortName;
                    const newKonten =
                        `Diberitahukan kepada seluruh mahasiswa bahwa perkuliahan untuk '${jadwalText}' pada hari ini ditiadakan. Terima kasih.`;
                    kontenTextarea.value = newKonten;

                    // Trigger event input agar character counter ter-update
                    kontenTextarea.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                }
            });

            templateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tipe = this.dataset.tipe;
                    const konten = this.dataset.konten;

                    // Set nilai pada form
                    tipeSelect.value = tipe;
                    kontenTextarea.value = konten;

                    // Memicu event 'input' agar character counter ikut terupdate
                    kontenTextarea.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));

                    // Scroll ke form agar terlihat
                    kontenTextarea.focus();
                });
            });

            if (textarea && charCount) {
                textarea.addEventListener('input', function() {
                    const count = this.value.length;
                    charCount.textContent = count + ' karakter';

                    if (count < 10) {
                        charCount.classList.add('text-red-500');
                        charCount.classList.remove('text-gray-400');
                    } else {
                        charCount.classList.remove('text-red-500');
                        charCount.classList.add('text-gray-400');
                    }
                });
            }

            // File upload preview
            const fileInput = document.getElementById('file_lampiran');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const uploadArea = this.parentElement.querySelector('div');
                        uploadArea.innerHTML = `
                    <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-600 font-medium">${file.name}</p>
                    <p class="text-xs text-gray-400 mt-1">File berhasil dipilih</p>
                `;
                    }
                });
            }

            // Get CSRF token
            function getCSRFToken() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error(
                        'CSRF token not found. Make sure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your HTML head.'
                    );
                    return null;
                }
                return csrfToken.getAttribute('content');
            }

            // --- FUNGSI UNTUK UPDATE JUMLAH MAHASISWA ---
            function updateJumlahMahasiswa() {
                const semesterSelect = document.getElementById('semester');
                const prodiSelect = document.getElementById('prodi');
                const golonganSelect = document.getElementById('golongan');
                const jumlahMahasiswaEl = document.getElementById('jumlah-mahasiswa');

                if (!semesterSelect || !prodiSelect || !golonganSelect || !jumlahMahasiswaEl) {
                    console.error('Required elements not found');
                    return;
                }

                const semester = semesterSelect.value;
                const prodi = prodiSelect.value;
                const golongan = golonganSelect.value;

                console.log('Updating mahasiswa count with:', {
                    semester,
                    prodi,
                    golongan
                });

                jumlahMahasiswaEl.textContent = 'Memuat...';

                const csrfToken = getCSRFToken();
                if (!csrfToken) {
                    jumlahMahasiswaEl.textContent = 'Error: CSRF token tidak ditemukan';
                    return;
                }

                // Check if route exists (you need to make sure this route is defined in your Laravel routes)
                const getMahasiswaUrl = '{{ route('dosen.pengumuman.getMahasiswa') }}';

                fetch(getMahasiswaUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            semester: semester,
                            prodi: prodi,
                            golongan: golongan
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        if (data.success) {
                            jumlahMahasiswaEl.textContent = data.detail ||
                                `${data.count || 0} mahasiswa akan menerima pengumuman`;
                        } else {
                            jumlahMahasiswaEl.textContent = data.message || 'Error memuat data';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching student count:', error);
                        jumlahMahasiswaEl.textContent = 'Error memuat data mahasiswa';

                        // Fallback: Show static message
                        setTimeout(() => {
                            jumlahMahasiswaEl.textContent = 'Semua mahasiswa akan menerima pengumuman';
                        }, 2000);
                    });
            }

            // --- FUNGSI UNTUK UPDATE OPSI GOLONGAN SECARA DINAMIS ---
            function updateGolonganOptions() {
                const semesterSelect = document.getElementById('semester');
                const prodiSelect = document.getElementById('prodi');
                const golonganSelect = document.getElementById('golongan');

                if (!semesterSelect || !prodiSelect || !golonganSelect) {
                    console.error('Required select elements not found');
                    return;
                }

                const semester = semesterSelect.value;
                const prodi = prodiSelect.value;

                console.log('Updating golongan options with:', {
                    semester,
                    prodi
                });

                const currentSelectedGolongan = golonganSelect.value;

                // Reset dropdown golongan
                golonganSelect.innerHTML = '<option value="all">Memuat golongan...</option>';
                golonganSelect.disabled = true;

                const csrfToken = getCSRFToken();
                if (!csrfToken) {
                    golonganSelect.innerHTML = '<option value="all">Error: CSRF token tidak ditemukan</option>';
                    golonganSelect.disabled = false;
                    return;
                }

                // Check if route exists
                const getGolonganUrl = '{{ route('dosen.pengumuman.getGolonganOptions') }}';

                fetch(getGolonganUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            semester: semester,
                            prodi: prodi
                        })
                    })
                    .then(response => {
                        console.log('Golongan response status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received golongan data:', data);

                        // Reset dropdown
                        golonganSelect.innerHTML = '<option value="all">Semua Golongan</option>';
                        golonganSelect.disabled = false;

                        // Jika ada data golongan
                        if (data.success && data.golongan && data.golongan.length > 0) {
                            data.golongan.forEach(golongan => {
                                const option = document.createElement('option');
                                option.value = golongan.id;
                                option.textContent = golongan.nama_golongan || golongan.name;
                                if (golongan.id == currentSelectedGolongan) {
                                    option.selected = true;
                                }
                                golonganSelect.appendChild(option);
                            });
                        }

                        // Update jumlah mahasiswa setelah dropdown terisi
                        updateJumlahMahasiswa();
                    })
                    .catch(error => {
                        console.error('Error fetching golongan options:', error);
                        golonganSelect.innerHTML = '<option value="all">Semua Golongan</option>';
                        golonganSelect.disabled = false;

                        // Still update student count even if golongan fetch fails
                        updateJumlahMahasiswa();
                    });
            }

            // --- Event listeners untuk filter ---
            semesterSelect.addEventListener('change', function() {
                updateGolonganOptions();
                updateJadwalOptions(); // Panggil update jadwal di sini
            });

            prodiSelect.addEventListener('change', function() {
                updateGolonganOptions();
                updateJadwalOptions(); // Panggil update jadwal di sini juga
            });

            golonganSelect.addEventListener('change', updateJumlahMahasiswa);

            if (semesterSelect) {
                semesterSelect.addEventListener('change', function() {
                    console.log('Semester changed to:', this.value);
                    updateGolonganOptions();
                });
            }

            if (prodiSelect) {
                prodiSelect.addEventListener('change', function() {
                    console.log('Prodi changed to:', this.value);
                    updateGolonganOptions();
                });
            }

            if (golonganSelect) {
                golonganSelect.addEventListener('change', function() {
                    console.log('Golongan changed to:', this.value);
                    updateJumlahMahasiswa();
                });
            }

            // --- Load initial data ---
            console.log('Loading initial data...');

            // Add delay to ensure DOM is fully loaded
            setTimeout(() => {
                updateGolonganOptions();
            }, 100);

            toggleJadwalDropdown();
            updateJadwalOptions(); // Panggil sekali saat dimuat
            updateGolonganOptions();
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
