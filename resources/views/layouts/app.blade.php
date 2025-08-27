<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amanah') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="font-sans antialiased">
    {{-- [PERBAIKAN] min-h-screen dan background diletakkan di sini --}}
    <div class="min-h-screen bg-slate-50">
        <!-- Sidebar (dari file navigation.blade.php) -->
        @include('layouts.navigation')

        <!-- Area Konten Utama -->
        <div class="md:ml-64">

            <!-- Header Mobile (hanya muncul di layar kecil) -->
            <header class="p-4 bg-white shadow-md md:hidden flex justify-between items-center sticky top-0 z-10">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Pondok" class="block h-9 w-auto">
                </a>

                <button @click.stop="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </header>

            <!-- Konten Halaman (Slot) -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
