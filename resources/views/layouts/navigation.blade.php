<div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false" x-cloak></div>

<aside class="fixed inset-y-0 left-0 z-30 flex w-64 transform flex-col bg-white text-gray-900 border-r border-slate-200 transition-transform duration-300 ease-in-out md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
    <div class="flex h-20 flex-shrink-0 items-center justify-center border-b border-slate-200 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo-kunka-merah.png') }}" alt="Logo Pondok" class="block h-9 w-auto">
            <span class="text-xl font-bold tracking-wider text-gray-800" style="font-family: 'Inter', sans-serif;">{{ config('app.name', 'Amanah') }}</span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6" x-data="{
        isAkademikOpen: {{ request()->routeIs('pengajaran.*', 'admin.santri-management.*', 'akademik.*') ? 'true' : 'false' }},
        isAdministrasiOpen: {{ request()->routeIs('admin.users.*', 'pengajaran.jabatan.*', 'laporan.*') ? 'true' : 'false' }}
    }">
        @php
            $baseClasses = 'flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-all duration-200 border-l-4';
            $activeClasses = 'bg-red-50 text-red-700 font-semibold border-red-600';
            $inactiveClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-transparent';
            
            $childBase = 'flex items-center gap-3 rounded-md px-3 py-2 text-sm transition-all duration-200';
            $childActive = 'bg-slate-100 text-slate-900 font-medium';
            $childInactive = 'text-slate-500 hover:bg-slate-100 hover:text-slate-800';
        @endphp

        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="{{ $baseClasses }} {{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>{{ __('Dashboard') }}</span>
            </a>
            @if (Auth::user()->role !== 'wali_santri')
                <a href="{{ route('perizinan.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('perizinan.*') ? $activeClasses : $inactiveClasses }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>{{ __('Daftar Izin') }}</span>
                </a>
                <a href="{{ route('pelanggaran.index') }}" class="{{ $baseClasses }} {{ request()->routeIs('pelanggaran.*') ? $activeClasses : $inactiveClasses }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ __('Pelanggaran Santri') }}</span>
                </a>
            @endif
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'pengajaran']))
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isAkademikOpen = !isAkademikOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-800 hover:bg-slate-100">
                <span>Akademik</span>
                <svg class="h-5 w-5 transform transition-transform" :class="{'rotate-180': isAkademikOpen}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <div x-show="isAkademikOpen" x-collapse class="mt-1 space-y-1 pl-4">
                <a href="{{ route('admin.santri-management.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.santri-management.*') ? $childActive : $childInactive }}">Data Santri</a>
                <a href="{{ route('pengajaran.kelas.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.kelas.*') ? $childActive : $childInactive }}">Data Kelas</a>
                <a href="{{ route('akademik.placement.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.placement.*') ? $childActive : $childInactive }}">Penempatan Kelas</a>
                <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.mata-pelajaran.*') ? $childActive : $childInactive }}">Mata Pelajaran</a>
                <a href="{{ route('akademik.kurikulum.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.kurikulum.*') ? $childActive : $childInactive }}">Atur Kurikulum</a>
                <a href="{{ route('akademik.nilai.index') }}" class="{{ $childBase }} {{ request()->routeIs('akademik.nilai.*') ? $childActive : $childInactive }}">Input Nilai</a>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'admin')
        <div class="pt-4 mt-4 border-t border-slate-200">
            <button @click="isAdministrasiOpen = !isAdministrasiOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold text-slate-800 hover:bg-slate-100">
                <span>Administrasi</span>
                <svg class="h-5 w-5 transform transition-transform" :class="{'rotate-180': isAdministrasiOpen}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <div x-show="isAdministrasiOpen" x-collapse class="mt-1 space-y-1 pl-4">
                <a href="{{ route('laporan.index') }}" class="{{ $childBase }} {{ request()->routeIs('laporan.*') ? $childActive : $childInactive }}">Laporan</a>
                <a href="{{ route('pengajaran.jabatan.index') }}" class="{{ $childBase }} {{ request()->routeIs('pengajaran.jabatan.*') ? $childActive : $childInactive }}">Manajemen Jabatan</a>
                <a href="{{ route('admin.users.index') }}" class="{{ $childBase }} {{ request()->routeIs('admin.users.*') ? $childActive : $childInactive }}">Manajemen User</a>
            </div>
        </div>
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
            <div x-show="open" x-transition class="absolute bottom-full mb-2 w-56 origin-bottom-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" @click.outside="open = false" x-cloak>
                <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Log Out</a>
                </form>
            </div>
        </div>
    </div>
</aside>