@php
    use Carbon\Carbon;
@endphp

@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.semester.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Tambah Ruangan</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 w-fit">
            <form action="{{ route('admin.ruangan.store') }}" method="post" class="flex flex-col gap-4 max-w-md">
                @csrf
                @method('POST')
                @if ($errors->any())
                    <div class="p-4 bg-red-100 border-2 text-red-500 rounded flex items-center gap-2 w-74">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-500 text-xs ml-2 py-1">{{ $error }}</p>
                            @endforeach
                        </div>

                    </div>
                @endif

                <div class="flex flex-col gap-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>

                    <input type="text" id="name" name="name"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow"
                        placeholder="Lab Arsitektur Jaringan Komputer">
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex flex-col gap-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex-1">
                                <label for="lantai" class="text-sm font-medium text-gray-700">Lantai</label>
                                <input type="number" name="lantai" id="lantai"
                                    class="mt-1 block px-2 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                                    placeholder="1">
                            </div>
                            <div class="flex-1">
                                <label for="no_ruangan" class="text-sm font-medium text-gray-700">No. Ruangan</label>
                                <input type="number" name="no_ruangan" id="no_ruangan"
                                    class="mt-1 block px-2 py-2 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none sm:text-sm transition"
                                    placeholder="01">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="flex items-center gap-6">
                    <div>
                        <h1 class="text-sm font-medium text-gray-700">Pilih Tipe Ruangan</h1>

                        <div class="flex flex-cols gap-4 items-center mt-1">
                            <label for="praktikum"
                                class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md w-32">
                                <input type="radio" name="type" id="praktikum" value="praktikum" class="hidden peer" />
                                <div
                                    class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600">
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Praktikum</span>
                            </label>

                            <label for="teori"
                                class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl border border-gray-300 hover:border-indigo-500 transition-all shadow-sm hover:shadow-md w-32">
                                <input type="radio" name="type" id="teori" value="teori" class="hidden peer" />
                                <div
                                    class="w-4 h-4 rounded-full border border-gray-400 peer-checked:border-4 peer-checked:border-indigo-600">
                                </div>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Teori</span>
                            </label>


                        </div>

                    </div>
                    <div>
                        <h1 class="text-sm font-medium text-gray-700">Kode</h1>
                        <input type="text" name="kode_ruangan" id="kode_ruangan" readonly
                            class="mt-1 bg-gray-100 text-gray-600 border border-gray-300 rounded-lg px-3 py-2 text-sm w-32 cursor-not-allowed" />
                    </div>
                </div>

                <button type="submit"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-2 rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
            </form>
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
