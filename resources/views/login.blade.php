<x-layout>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Silahkan Login</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            @if ($errors->login->has('email'))
                @php
                    $message = $errors->login->first('email');
                    $isWarning = str_contains($message, 'belum terdaftar');
                @endphp

                <div
                    class="{{ $isWarning ? 'bg-yellow-100 border-yellow-300 text-yellow-800' : 'bg-red-100 border-red-300 text-red-800' }}
                border px-4 py-3 rounded-md shadow-md mb-5">
                    <strong>{{ $isWarning ? 'Peringatan' : 'Error' }}:</strong> {{ $message }}
                </div>
            @endif

            <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" autocomplete="email" required
                            class="block w-full rounded-tl-md rounded-tr-md bg-white px-3 py-3 text-base text-gray-900 border-r-1 border-l-1 border-t-1 border-b-1 border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                            placeholder="Email address">

                    </div>

                    <div x-data="{ showPassword: false }" class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                            autocomplete="current-password" required
                            class="block w-full rounded-bl-md rounded-br-md bg-white px-3 py-3 text-base text-gray-900 border-l-1 border-r-1 border-b-1 border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                            placeholder="Password">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a18.1 18.1 0 013.22-3.95M22 12s-4-8-11-8a10.94 10.94 0 00-5.66 1.5M9.9 9.9a3 3 0 014.2 4.2M3 3l18 18" />
                            </svg>
                        </button>
                    </div>

                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" x-on:click="loading = true"
                        class="font-semibold text-gray-700 hover:text-gray-900">Lupa
                        password?</a>
                </div>

                <div>
                    <button type="submit" x-on:click="loading = true"
                        class="flex w-full justify-center rounded-md bg-gray-800 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 cursor-pointer">Sign
                        in</button>
                </div>
            </form>

            <a href="/auth/google" x-on:click="loading = true"
                class="mt-5 mx-auto flex justify-center items-center gap-2 w-80 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-100 shadow-sm transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-5 w-5">
                Login dengan akun Google
            </a>

            <p class="mt-6 text-center text-sm/6 text-gray-500">
                Belum punya akun?
                <a href="/register" x-on:click="loading = true"
                    class="font-semibold text-gray-700 hover:text-gray-900">Daftar disini.</a>
            </p>
        </div>
    </div>

</x-layout>
