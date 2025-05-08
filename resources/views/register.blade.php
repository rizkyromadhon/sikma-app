<x-layout>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Silahkan Daftar</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

            @if ($errors->register->any())
                <div class="mb-4">
                    @php
                        $warningErrors = [];
                        $otherErrors = [];
                        $errorEmailTerdaftar = null;

                        foreach ($errors->register->all() as $error) {
                            if (Str::contains($error, 'Email sudah terdaftar')) {
                                $errorEmailTerdaftar = $error;
                            } elseif (
                                Str::contains($error, 'Gmail') ||
                                Str::contains($error, 'Password terlalu mudah ditebak') ||
                                Str::contains($error, 'Password minimal 6 karakter') ||
                                Str::contains($error, 'Konfirmasi password tidak cocok') ||
                                Str::contains($error, 'Format email tidak valid')
                            ) {
                                $warningErrors[] = $error;
                            } else {
                                $otherErrors[] = $error;
                            }
                        }
                    @endphp

                    {{-- Jika ada error "akun sudah terdaftar", tampilkan hanya itu --}}
                    @if ($errorEmailTerdaftar)
                        <div class="mt-2 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md shadow-md">
                            <strong>Gagal:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                <li>{{ $errorEmailTerdaftar }}</li>
                            </ul>
                        </div>

                        {{-- Jika tidak ada error akun sudah terdaftar, baru tampilkan warning & error lainnya --}}
                    @else
                        @if (count($warningErrors) > 0)
                            <div
                                class="mt-2 bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md shadow-md">
                                <strong>Perhatian:</strong>
                                <ul class="mt-2 list-disc list-inside text-sm text-yellow-700">
                                    @foreach ($warningErrors as $warning)
                                        <li>{{ $warning }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @foreach ($otherErrors as $error)
                            <div
                                class="mt-2 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md shadow-md">
                                <strong>Gagal:</strong>
                                <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                    <li>{{ $error }}</li>
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif

            <form class="space-y-4" action="/register" method="POST">
                @csrf
                <div>
                    <div class="mt-2">
                        <input type="text" name="fullname" id="fullname" required
                            class="block w-full rounded-tl-md rounded-tr-md bg-white px-3 py-3 text-base text-gray-900 border-l-1 border-t-1 border-r-1 border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                            placeholder="Nama lengkap">
                    </div>
                    <div>
                        <input type="email" name="email" id="email" autocomplete="email" required
                            class="block w-full bg-white px-3 py-3 text-base text-gray-900 border-l-1 border-r-1 border-t-1 border-b-1 border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                            placeholder="Email address">
                    </div>

                    <div x-data="{ showPassword: false }" class="flex">
                        <div class="w-5/6">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                autocomplete="current-password" required
                                class="block w-full bg-white px-3 py-3 text-base text-gray-900 border-l-1 border-r-1 border-gray-300 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                                placeholder="Password">
                            <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                                id="password_confirmation" required
                                class="block w-full bg-white px-3 py-3 text-base text-gray-900 border-r-1 border-l-1 border-b-1 border-t-1 border-gray-300 rounded-bl-md placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                                placeholder="Konfirmasi password">
                        </div>

                        <div
                            class="w-1/6 flex  rounded-br-md items-center justify-center border-r-1 border-b-1 border-gray-300">
                            <button type="button" @click="showPassword = !showPassword"
                                class="text-gray-500 hover:text-gray-700">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>

                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a18.1 18.1 0 013.22-3.95M22 12s-4-8-11-8a10.94 10.94 0 00-5.66 1.5M9.9 9.9a3 3 0 014.2 4.2M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" x-on:click="loading = true"
                        class="flex w-full justify-center rounded-md bg-gray-800 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-900 focus-visible:outline-2 focus-visible:outline-offset-2 cursor-pointer">Daftar
                        sekarang</button>
                </div>
            </form>

            <a href="/auth/google" x-on:click="loading = true"
                class="mt-5 mx-auto flex justify-center items-center gap-2 w-80 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100 shadow-sm transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-5 w-5">
                Daftar dengan akun Google
            </a>

            <p class="mt-6 text-center text-sm/6 text-gray-500">
                Sudah punya akun?
                <a href="/login" x-on:click="loading = true"
                    class="font-semibold text-gray-700 hover:text-gray-900">Login disini.</a>
            </p>
        </div>


    </div>

</x-layout>
