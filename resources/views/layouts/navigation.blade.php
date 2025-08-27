<!-- Overlay untuk background gelap saat sidebar mobile terbuka -->
<div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false" x-cloak></div>

<!-- Sidebar -->
{{-- [MODIFIKASI] Mengubah total skema warna menjadi terang/putih --}}
<aside class="fixed inset-y-0 left-0 z-30 flex w-64 transform flex-col bg-white text-gray-900 border-r border-slate-200 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }" x-cloak>
    <!-- Logo dan Nama Aplikasi -->
<div class="flex h-20 flex-shrink-0 items-center justify-center border-b border-slate-200">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4">
        <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Pondok" class="block h-9 w-auto">
        {{-- [MODIFIKASI] Font diubah menjadi Inter --}}
        <span class="text-xl font-bold tracking-wider text-gray-800" style="font-family: 'Inter', sans-serif;">
            {{ config('app.name', 'Amanah') }}
        </span>
    </a>
</div>

    <!-- Link Navigasi Utama -->
    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
        @php
            // [MODIFIKASI] Class baru untuk gaya sidebar terang
            $baseClasses = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 border-l-4';
            $activeClasses = 'bg-red-50 text-red-700 font-semibold border-red-600';
            $inactiveClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-transparent';
        @endphp

        <a href="{{ route('dashboard') }}" class="{{ $baseClasses }} {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>{{ __('Dashboard') }}</span>
        </a>

        @if (Auth::user()->role !== 'wali_santri')
        <a href="{{ route('perizinan.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('perizinan.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span>{{ __('Daftar Izin') }}</span>
        </a>

        <a href="{{ route('pelanggaran.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pelanggaran.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ __('Pelanggaran Santri') }}</span>
        </a>

        <a href="{{ route('laporan.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('laporan.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>{{ __('Laporan') }}</span>
        </a>
        @endif

        @can('viewAny', App\Models\Kelas::class)
        <a href="{{ route('pengajaran.kelas.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pengajaran.kelas.*') || request()->routeIs('pengajaran.santris.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-1a6 6 0 00-5.176-5.973M15 21H9m6-12a4 4 0 00-4-4m0 5.292A4 4 0 0111 4.354m-6 8.487A4 4 0 015 11m6 0A4 4 0 0115 11m-6 0v-1.293A4 4 0 009 5.646m-2.5 5.707A4 4 0 013 11"></path></svg>
            <span>{{ __('Data Kelas & Santri') }}</span>
        </a>
        @endcan

        @if(in_array(Auth::user()->role, ['admin', 'pengajaran']))
        <a href="{{ route('admin.santri-management.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('admin.santri-management.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0z"></path></svg>
            <span>{{ __('Manajemen Santri') }}</span>
        </a>
        @endif

        @can('viewAny', App\Models\Kelas::class)
        <a href="{{ route('akademik.placement.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('akademik.placement.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            <span>{{ __('Penempatan Kelas') }}</span>
        </a>
        @endcan

        @can('viewAny', App\Models\MataPelajaran::class)
        <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pengajaran.mata-pelajaran.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span>{{ __('Mata Pelajaran') }}</span>
        </a>
        @endcan

        @can('viewAny', App\Models\Kelas::class)
        <a href="{{ route('akademik.kurikulum.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('akademik.kurikulum.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <span>{{ __('Atur Kurikulum') }}</span>
        </a>
        @endcan

        @can('viewAny', App\Models\Nilai::class)
        <a href="{{ route('akademik.nilai.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('akademik.nilai.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span>{{ __('Input Nilai') }}</span>
        </a>
        @endcan

        @if (Auth::user()->role === 'admin')
        <a href="{{ route('pengajaran.jabatan.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pengajaran.jabatan.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0z"></path></svg>
            <span>{{ __('Manajemen Jabatan') }}</span>
        </a>
        @endif

        @if (Auth::user()->role === 'admin')
        <a href="{{ route('admin.users.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('admin.users.*') ? $activeClasses : $inactiveClasses }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm-3 5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span>{{ __('Manajemen User') }}</span>
        </a>
        @endif
    </nav>

    <div class="border-t border-slate-200 p-4">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex w-full items-center rounded-lg p-2 text-left transition-colors duration-200 hover:bg-slate-100 focus:outline-none">
                <img class="mr-3 h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FBBF24&color=78350F" alt="Avatar">
                <div class="flex-1">
                    <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ ucwords(str_replace('_', ' ', Auth::user()->role)) }}</div>
                </div>
                <svg class="h-5 w-5 fill-current text-slate-500 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute bottom-full mb-2 w-56 origin-bottom-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" @click.outside="open = false" x-cloak>
                <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                    {{ __('Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</aside>
