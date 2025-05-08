<x-layout>
    <div class="max-w-md mx-auto mt-24">
        <h2 class="text-xl font-bold text-center mb-4">Buat Password Baru</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <input type="email" name="email" value="{{ old('email', $request->email) }}"
                class="block w-full rounded-tl-md rounded-tr-md bg-white px-3 py-3 text-base text-gray-900 border-r-1 border-l-1 border-b-1 border-t-1 border-gray-300 placeholder:text-gray-400 sm:text-sm/6 focus:outline-gray-200"
                readonly placeholder="Email">

            <div x-data="{ showPassword: false }" class="flex mb-4">
                <div class="w-5/6">
                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                        autocomplete="current-password" required
                        class="block w-full bg-white px-3 py-3 text-base text-gray-900 border-l-1 border-r-1 border-gray-300 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:text-sm/6"
                        placeholder="Password Baru">
                    <input :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                        id="password_confirmation" required
                        class="block w-full bg-white px-3 py-3 text-base text-gray-900 border-r-1 border-l-1 border-b-1 border-t-1 border-gray-300 rounded-bl-md placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:text-sm/6"
                        placeholder="Konfirmasi password Baru">
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

            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Reset Password
            </button>
        </form>
    </div>
</x-layout>
