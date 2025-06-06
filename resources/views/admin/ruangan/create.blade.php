@php
    use Carbon\Carbon;
@endphp

@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.ruangan.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Tambah Ruangan</h1>
        </div>

        <div class="px-4 rounded-md">
            <div class="bg-white dark:bg-black dark:border dark:border-gray-700 shadow-md rounded-lg p-6 w-fit">
                <form action="{{ route('admin.ruangan.store') }}" method="post" class="flex flex-col gap-4 max-w-md">
                    @csrf
                    @method('POST')
                    @if ($errors->any())
                        <div
                            class="p-4 bg-red-100 dark:bg-red-900/50 border-2 text-red-500 dark:text-red-600 rounded flex items-center gap-2 w-full mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-500 dark:text-red-100 text-sm ml-4 py-1">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col gap-2">
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Nama
                            Ruangan</label>

                        <input type="text" id="name" name="name"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                            placeholder="Lab Arsitektur Jaringan Komputer">
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex flex-col gap-2">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex-1">
                                    <label for="lantai"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Lantai</label>
                                    <input type="number" name="lantai" id="lantai"
                                        class="mt-1 block px-4 py-2 w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none transition text-sm placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                        placeholder="1">
                                </div>
                                <div class="flex-1">
                                    <label for="no_ruangan"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">No.
                                        Ruangan</label>
                                    <input type="number" name="no_ruangan" id="no_ruangan"
                                        class="mt-1 block px-4 py-2 w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none transition text-sm placeholder-gray-600/50 dark:placeholder-gray-400/50"
                                        placeholder="01">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="flex items-center gap-6">
                        <div>
                            <h1 class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">
                                Pilih Tipe Ruangan</h1>

                            <div class="flex flex-cols gap-4 items-center mt-1">
                                <label for="praktikum"
                                    class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50  flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md w-32">
                                    <input type="radio" name="type" id="praktikum" value="praktikum"
                                        class="hidden peer" />
                                    <div
                                        class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600 dark:peer-checked:border-blue-600">
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-200 peer-checked:text-indigo-600 dark:peer-checked:text-blue-600">Praktikum</span>
                                </label>

                                <label for="teori"
                                    class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50  flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md w-32">
                                    <input type="radio" name="type" id="teori" value="teori"
                                        class="hidden peer" />
                                    <div
                                        class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600 dark:peer-checked:border-blue-600">
                                    </div>
                                    <span
                                        class="text-sm font-medium text-gray-700 dark:text-gray-200 peer-checked:text-indigo-600 dark:peer-checked:text-blue-600">Teori</span>
                                </label>


                            </div>

                        </div>
                        <div>
                            <h1 class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Kode
                            </h1>
                            <input type="text" name="kode_ruangan" id="kode_ruangan" readonly
                                class="mt-1 bg-gray-100 dark:bg-black dark:border-gray-700 text-gray-600 dark:text-gray-200 border border-gray-300 rounded-lg px-3 py-2 text-sm w-32 cursor-not-allowed" />
                        </div>
                    </div>

                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const lantaiInput = document.getElementById('lantai');
        const inputNoRuangan = document.getElementById('no_ruangan');
        let inputKodeRuangan = document.getElementById('kode_ruangan');
        inputKodeRuangan.value = "JTI";

        function updateKodeRuangan() {
            const baseCode = "JTI";
            const lantai = lantaiInput.value;
            const no = inputNoRuangan.value;
            if (lantai < 0) lantaiInput.value = '';
            if (no < 0) inputNoRuangan.value = '';
            inputKodeRuangan.value = `${baseCode}${lantaiInput.value}${inputNoRuangan.value}`;

        }

        lantaiInput.addEventListener('input', updateKodeRuangan);
        inputNoRuangan.addEventListener('input', updateKodeRuangan);
    </script>
@endsection
