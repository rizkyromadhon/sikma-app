@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 mb-4 flex items-center gap-4">
            <h1 class="text-xl font-bold text-gray-800">
                <a href="{{ route('admin.semester.index') }}" class="text-black hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </h1>
            <h1 class="text-xl font-medium text-gray-800">Edit RFID <strong>{{ $mahasiswa->name }}</strong></h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 w-fit">
            <form action="{{ route('admin.rfid.update', $mahasiswa->id) }}" method="post"
                class="flex flex-col gap-4 max-w-md">
                @csrf
                @method('PUT')
                @livewire('rfid-form', ['mahasiswaId' => $mahasiswa->id])
                <button type="submit"
                    class="flex items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black">
                    {{ $mahasiswa->uid ? 'Update' : 'Tambah' }}
                </button>
            </form>
        </div>
    </div>
@endsection
