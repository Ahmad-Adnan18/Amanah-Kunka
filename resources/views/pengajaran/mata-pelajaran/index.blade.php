<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Mata Pelajaran</h1>
                            <p class="mt-1 text-slate-600">Kelola "katalog" mata pelajaran dan alokasi jam pelajaran per tingkatan.</p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.create') }}" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                            Tambah Mata Pelajaran
                        </a>
                    </div>
                </div>

                <!-- [TAMPILAN BARU] Dasbor Kalkulator JP per Tingkatan -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-4">Dasbor Alokasi Jam Pelajaran (JP) per Minggu</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Kartu Acuan -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="text-sm text-blue-600 font-semibold uppercase">Jam Efektif Tersedia</div>
                            <div class="text-3xl font-bold text-blue-800 mt-1">{{ $jamEfektif }} JP</div>
                            <p class="text-xs text-blue-500 mt-1">Kapasitas setelah dikurangi jam terblokir.</p>
                        </div>

                        <!-- Kartu per Tingkatan -->
                        @foreach ($jpPerTingkat as $tingkat => $totalJp)
                            @php
                                $isPas = ($totalJp == $jamEfektif);
                                $isLebih = ($totalJp > $jamEfektif);
                            @endphp
                             <div class="p-4 rounded-lg border" 
                                :class="{
                                    'bg-green-50 border-green-200': {{ $isPas ? 'true' : 'false' }},
                                    'bg-red-50 border-red-200': {{ $isLebih ? 'true' : 'false' }},
                                    'bg-yellow-50 border-yellow-200': !{{ $isPas || $isLebih }}
                                }">
                                <div class="text-sm font-semibold uppercase"
                                    :class="{
                                        'text-green-600': {{ $isPas ? 'true' : 'false' }},
                                        'text-red-600': {{ $isLebih ? 'true' : 'false' }},
                                        'text-yellow-600': !{{ $isPas || $isLebih }}
                                    }">
                                    Tingkat {{ $tingkat }}
                                </div>
                                <div class="text-3xl font-bold mt-1"
                                    :class="{
                                        'text-green-800': {{ $isPas ? 'true' : 'false' }},
                                        'text-red-800': {{ $isLebih ? 'true' : 'false' }},
                                        'text-yellow-800': !{{ $isPas || $isLebih }}
                                    }">
                                    {{ $totalJp }} JP
                                </div>
                                <p class="text-xs mt-1"
                                     :class="{
                                        'text-green-500': {{ $isPas ? 'true' : 'false' }},
                                        'text-red-500': {{ $isLebih ? 'true' : 'false' }},
                                        'text-yellow-500': !{{ $isPas || $isLebih }}
                                    }">
                                     @if($isPas)
                                        Alokasi Sempurna!
                                     @elseif($isLebih)
                                        Kelebihan {{ $totalJp - $jamEfektif }} JP
                                     @else
                                        Kekurangan {{ $jamEfektif - $totalJp }} JP
                                     @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <!-- Filter -->
                     <div class="p-6 border-b border-slate-200">
                        <span class="text-sm font-medium text-gray-700">Filter berdasarkan tingkatan:</span>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('tingkatan') == '' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                Semua
                            </a>
                            @foreach($jpPerTingkat->keys()->sort() as $tingkat)
                                <a href="{{ route('pengajaran.mata-pelajaran.index', ['tingkatan' => $tingkat]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('tingkatan') == $tingkat ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    Tingkat {{ $tingkat }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- Tabel -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tingkatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Durasi (JP)</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Guru Pengampu</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($mataPelajarans as $mapel)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ $mapel->nama_pelajaran }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $mapel->tingkatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $mapel->kategori }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 text-center">{{ $mapel->duration_jp }}</td>
                                        <td class="px-6 py-4 text-slate-600 max-w-xs">
                                            @forelse ($mapel->teachers as $teacher)
                                                <span class="inline-block bg-slate-100 text-slate-700 text-xs font-medium mr-2 mb-1 px-2.5 py-0.5 rounded-full">
                                                    {{ $teacher->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-red-500 italic">Belum ada</span>
                                            @endforelse
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <a href="{{ route('pengajaran.mata-pelajaran.edit', $mapel) }}" class="text-red-600 hover:text-red-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Tidak ada data mata pelajaran untuk filter ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $mataPelajarans->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

