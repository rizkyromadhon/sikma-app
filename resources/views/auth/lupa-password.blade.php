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
            <input type="email" name="email" required placeholder="Masukkan email Gmail anda."
                class="block w-full rounded-md bg-white px-3 py-3 text-base text-gray-900 border-1 border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 mb-4">

            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Kirim Link Reset Password
            </button>
        </form>
    </div>
</x-layout>
