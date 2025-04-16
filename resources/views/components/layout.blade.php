<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="h-full pt-16">
    <x-navbar></x-navbar>
    {{-- <x-header>{{ $title }}</x-header> --}}

    <main>
        <div class="mx-auto max-w-11/12 py-3 sm:px-6 lg:px-2">
            {{ $slot }}
        </div>
    </main>
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-6 z-50 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-md shadow-lg">
            <strong>Sukses.</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
            class="fixed top-20 right-6 z-50 bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-2 rounded-md shadow-lg">
            <strong>Info.</strong> {{ session('info') }}
        </div>
    @endif
</body>

</html>
