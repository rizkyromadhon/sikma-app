@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <a href="{{ route('admin.mata-kuliah.index') }}">
                <i
                    class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
            </a>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Edit Mata Kuliah
                {{ $datas->name }}</h1>
        </div>

        <div class="px-4 rounded-md">
            <div class="bg-white dark:bg-black dark:border dark:border-gray-700 p-4 shadow w-full max-w-md">
                <form action="{{ route('admin.mata-kuliah.update', $datas->id) }}" method="post" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
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
                            Mata Kuliah</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $datas->name) }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50"
                            placeholder="Pemrograman Web">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="kode"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 dark-mode-transition">Kode
                            Mata Kuliah</label>
                        <input type="text" name="kode" name="kode" value="{{ old('kode', $datas->kode) }}"
                            class="text-sm px-4 py-2 rounded border border-gray-300 dark:border-gray-700 shadow placeholder-gray-600/50 dark:placeholder-gray-400/50 uppercase"
                            placeholder="Pemrograman Web = PW" />
                    </div>
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
