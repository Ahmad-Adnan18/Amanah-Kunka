<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Amanah') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
    
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: false }" class="font-sans antialiased">
    <div class="min-h-screen bg-slate-50">
        @include('layouts.navigation')

        <div class="transition-all duration-300 ease-in-out md:ml-64">

            <header class="sticky top-0 z-10 flex items-center justify-between bg-white/80 p-4 shadow-md backdrop-blur-sm md:hidden">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Pondok" class="block h-9 w-auto">
                </a>

                <button @click.stop="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </header>

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
    
    {{-- [PERBAIKAN] Memuat skrip Livewire TERLEBIH DAHULU --}}
    @livewireScripts
    
    {{-- Baru kemudian memuat skrip utama aplikasi dan plugin Alpine --}}
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    
</body>
</html>

