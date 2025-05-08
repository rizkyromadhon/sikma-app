@php
    use Carbon\Carbon;
@endphp

@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.mata-kuliah.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-semibold text-gray-800">Tambah Mata Kuliah</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
            <form action="{{ route('admin.mata-kuliah.store') }}" method="post" class="flex flex-col gap-4">
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
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Mata Kuliah</label>
                    <input type="text" id="name" name="name"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow" placeholder="Pemrograman Web">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="kode" class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                    <input type="text" name="kode" name="kode"
                        class="text-sm px-4 py-2 rounded border border-gray-300 shadow uppercase"
                        placeholder="Pemrograman Web = PW" />
                </div>
                <button type="submit"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">Tambah</button>
            </form>
        </div>
    </div>
@endsection
