<x-layout>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-gray-200">Silahkan
                Ganti Password</h2>
        </div>

        <div class="top-1/2 left-1/2 transform sm:mx-auto sm:w-full sm:max-w-sm mt-6">
            <form class="space-y-4" action="{{ route('update-password') }}" method="POST">
                @csrf
                @method('POST')
                @if ($errors->any())
                    <div
                        class="p-4 bg-red-100 dark:bg-red-900/50 border-2 text-red-500 dark:text-red-300 rounded flex items-center gap-2 w-full mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-500 text-xs ml-2 py-1">{{ $error }}</p>
                            @endforeach
                        </div>

                    </div>
                @endif
                <div>
                    <input type="password" name="oldPassword" id="oldPassword" required
                        class="block w-full bg-white dark:bg-gray-900 px-3 py-3 text-base text-gray-900 dark:text-gray-200 border-l-1 border-r-1 border-t-1 border-b-1 rounded-t-md border-gray-300 dark:border-gray-700 outline-gray-300 placeholder:text-gray-600/50 dark:placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                        placeholder="Password lama">
                    <div x-data="{ showPassword: false }" class="flex">
                        <div class="w-5/6">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                autocomplete="current-password" required
                                class="block w-full bg-white dark:bg-gray-900 px-3 py-3 text-base text-gray-900 dark:text-gray-200 border-l-1 border-gray-300 dark:border-gray-700 outline-gray-300 placeholder:text-gray-600/50 dark:placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                                placeholder="Password baru">

                        </div>
                        <div
                            class="w-1/6 flex items-center justify-center bg-white dark:bg-gray-900 border-r-1 border-gray-300 dark:border-gray-700">
                            <button type="button" @click="showPassword = !showPassword"
                                class="text-gray-500 hover:text-gray-700">
                                <svg x-cloak x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>

                                <svg x-cloak x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0112 20c-7 0-11-8-11-8a18.1 18.1 0 013.22-3.95M22 12s-4-8-11-8a10.94 10.94 0 00-5.66 1.5M9.9 9.9a3 3 0 014.2 4.2M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="block w-full bg-white dark:bg-gray-900 px-3 py-3 text-base text-gray-900 dark:text-gray-200 border-r-1 border-l-1 border-b-1 border-t-1 border-gray-300 dark:border-gray-700 rounded-bl-md placeholder:text-gray-600/50 dark:placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 sm:text-sm/6"
                        placeholder="Konfirmasi password baru">
                </div>

                <div class="mt-5">
                    <button type="submit" x-on:click="loading = true"
                        class="flex w-full justify-center rounded-md bg-gray-800 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-900 focus-visible:outline-2 focus-visible:outline-offset-2 cursor-pointer">Ganti
                        Password</button>
                </div>
            </form>


        </div>


    </div>

</x-layout>
