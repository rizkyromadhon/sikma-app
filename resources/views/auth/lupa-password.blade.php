<x-layout>
    <div class="max-w-md mx-auto mt-24">
        <h2 class="text-xl font-bold text-center mb-4">Reset Password</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" required placeholder="Masukkan email anda."
                class="block w-full rounded-md bg-white dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 px-3 py-3 text-sm md:text-md text-gray-900 border-r-1 border-l-1 border-t-1 border-b-1 border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-gray-800 dark:focus:outline-gray-500 dark-mode-transition mb-4">

            <button
                class="w-full bg-blue-600 dark:bg-blue-900/80 text-white py-2 rounded hover:bg-blue-700 dark:hover:bg-blue-900/60 text-sm md:text-md transition dark-mode-transition">
                Kirim Link Reset Password
            </button>
        </form>
    </div>
</x-layout>
