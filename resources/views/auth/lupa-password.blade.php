<x-layout>
    <div class="max-w-md mx-auto mt-24" x-data="formValidation()">
        <h2 class="text-xl font-bold text-center mb-4">Reset Password</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('lupa-password.store') }}">
            @csrf
            @method('POST')
            <div class="mb-4 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIM</label>
                <div class="relative">
                    <input type="text" name="nim" x-model="form.nim" @input="validateNIM()" required
                        placeholder="Inputkan NIM anda..."
                        class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring focus:ring-gray-500 dark:focus:ring-gray-400 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">

                    <!-- Loading indicator -->
                    <div x-show="nimValidation.loading" class="absolute right-2 top-2.5">
                        <div class="animate-spin h-4 w-4 rounded-full"
                            style="border: 2px solid #e5e7eb; border-top: 2px solid #3b82f6;">
                        </div>
                    </div>

                    <!-- Success icon -->
                    <div x-show="nimValidation.valid && !nimValidation.loading"
                        class="absolute right-2 top-2.5 text-green-500 dark:text-green-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>

                    <!-- Error icon -->
                    <div x-show="nimValidation.invalid && !nimValidation.loading"
                        class="absolute right-2 top-2.5 text-red-500 dark:text-red-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div x-show="nimValidation.invalid" class="text-red-500 dark:text-red-400 text-xs mt-1">
                    NIM tidak terdaftar
                </div>
            </div>
            <div class="mb-4 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" x-model="form.nama_lengkap" required readonly
                    placeholder="Nama Lengkap"
                    class="w-full px-2 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-300">
            </div>

            <div class="mb-4 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email"x-model="form.email" required readonly placeholder="Email"
                    class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-300">
            </div>

            <div class="mb-8 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Studi</label>
                <input type="text" name="prodi" x-model="form.prodi" required readonly placeholder="Program Studi"
                    class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-300">
            </div>
            <input type="hidden" name="id_prodi" x-model="form.id_prodi">

            <button
                class="w-full bg-blue-600 dark:bg-blue-900/80 text-white py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-900/60 text-sm md:text-md transition dark-mode-transition">
                Kirim Lupa Password ke Admin
            </button>
        </form>
    </div>
    <script>
        function formValidation() {
            return {
                form: {
                    nama_lengkap: '{{ Auth::user()->name ?? '' }}',
                    nim: '{{ Auth::user()->nim ?? '' }}',
                    email: '{{ Auth::user()->email ?? '' }}',
                    id_prodi: '{{ Auth::user()->id_prodi ?? '' }}',
                    prodi: '{{ Auth::user()->programStudi->name ?? '' }}',
                    pesan: ''
                },
                nimValidation: {
                    valid: {{ Auth::check() ? 'true' : 'false' }},
                    invalid: false,
                    loading: false
                },
                nimTimeout: null,

                get canSubmit() {
                    return this.nimValidation.valid && this.form.pesan;
                },

                validateNIM() {
                    if (this.nimTimeout) {
                        clearTimeout(this.nimTimeout);
                    }

                    this.nimValidation = {
                        valid: false,
                        invalid: false,
                        loading: false
                    };

                    if (!this.form.nim.trim()) {
                        return;
                    }

                    this.nimValidation.loading = true;

                    this.nimTimeout = setTimeout(() => {
                        this.checkNIMInDatabase();
                    }, 500);
                },


                async checkNIMInDatabase() {
                    try {
                        const response = await fetch('/check-nim', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                nim: this.form.nim.trim() // Trim whitespace
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        // Debug logging
                        if (this.debugMode) {
                            console.log('NIM Check Response:', data);
                        }

                        this.nimValidation = {
                            valid: data.exists,
                            invalid: !data.exists,
                            loading: false
                        };

                        if (data.exists && data.user) {
                            this.form.nama_lengkap = data.user.name;
                            this.form.email = data.user.email;
                            this.form.id_prodi = data.user.id_prodi;
                            this.form.prodi = data.user.prodi_name;
                        } else {
                            this.form.nama_lengkap = '';
                            this.form.email = '';
                            this.form.id_prodi = '';
                            this.form.prodi = '';
                        }

                        // Debug info jika tidak ditemukan
                        if (!data.exists && this.debugMode) {
                            console.log('NIM not found:', {
                                input: this.form.nim,
                                trimmed: this.form.nim.trim(),
                                debug: data.debug
                            });
                        }

                    } catch (error) {
                        console.error('Error checking NIM:', error);

                        // Tampilkan error yang lebih spesifik
                        if (error.message.includes('404')) {
                            console.error('API endpoint /check-nim not found. Check your routes.');
                        } else if (error.message.includes('419')) {
                            console.error('CSRF token mismatch. Check your CSRF token.');
                        } else if (error.message.includes('500')) {
                            console.error('Server error. Check your controller and database connection.');
                        }

                        this.nimValidation = {
                            valid: false,
                            invalid: true,
                            loading: false
                        };
                    }
                },

                handleSubmit(e) {
                    if (!this.canSubmit) {
                        e.preventDefault();
                        alert('Mohon lengkapi form dan pastikan NIM terdaftar');
                        return false;
                    }

                    return true;
                },

            }
        }
        document.addEventListener('alpine:init', () => {
            window.addEventListener('load', () => {
                Alpine.store('loading').value = false;
            });
        });

        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => {
                if (Alpine.store('loading')?.value !== false) {
                    Alpine.store('loading').value = false;
                }
            }, 5000); // fallback jika window.load tidak terpanggil (jaga-jaga)
        });

        document.addEventListener('alpine:init', () => {
            Alpine.store('loading', {
                value: true
            });
        });
    </script>
</x-layout>
