@extends('admin.dashboard')

@section('admin-content')
    <div class="container mx-auto">
        <div
            class="bg-white dark:bg-black shadow-sm border-b border-gray-200 dark:border-gray-700 dark-mode-transition px-8 py-4 mb-4 flex items-center gap-4">
            <form action="{{ route('admin.rfid.resetMode') }}" method="POST" style="display:inline;">
                @csrf
                @method('POST')
                <button type="submit">
                    <i
                        class="fas fa-arrow-left text-black dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 dark-mode-transition transition"></i>
                </button>
            </form>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 dark-mode-transition">Registrasi RFID -
                <strong>{{ $mahasiswa->name }}</strong>
            </h1>
        </div>

        <div class="px-4 rounded-md">
            <div class="bg-white dark:bg-black dark:border dark:border-gray-700 p-4 shadow w-fit">
                <form action="{{ route('admin.rfid.store', $mahasiswa->id) }}" method="post"
                    class="flex flex-col gap-4 max-w-md">
                    @csrf
                    @method('POST')
                    @if ($errors->any())
                        <div class="p-4 bg-red-100 border-2 text-red-500 rounded flex items-center gap-2 w-full">
                            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-500 text-xs ml-2 py-1">{{ $error }}</p>
                                @endforeach
                            </div>

                        </div>
                    @endif
                    <span class="text-sm bg-yellow-500 dark:bg-yellow-900/60 dark:text-yellow-200 px-4 py-2 rounded-md"><i
                            class="fa-solid fa-circle-info mr-2"></i>Tempelkan kartu
                        RFID ke Alat
                        presensi.</span>
                    @livewire('rfid-form', ['mahasiswaId' => $mahasiswa->id])
                    <button type="submit"
                        class="flex w-full items-center gap-3 text-sm justify-center px-4 py-2 bg-gray-800 dark:bg-gray-900/80 dark:border dark:border-gray-700 dark:hover:bg-gray-900 text-white font-semibold shadow-xl mb-2 mt-4 rounded-full cursor-pointer transition hover:bg-black dark-mode-transition">Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function resetRfidMode() {
            navigator.sendBeacon("{{ route('admin.rfid.resetMode') }}");
        }

        // Saat meninggalkan halaman (normal)
        window.addEventListener('beforeunload', resetRfidMode);

        // Saat kembali dari bfcache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                resetRfidMode();
            }
        });
    </script>

@endsection
