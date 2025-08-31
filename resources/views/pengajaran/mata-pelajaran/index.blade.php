<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Mata Pelajaran</h1>
                            <p class="mt-1 text-slate-600">Kelola daftar mata pelajaran yang diajarkan di pondok.</p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.create') }}" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                            Tambah Mata Pelajaran
                        </a>
                    </div>
                    
                    <!-- TAMBAHAN: Filter berdasarkan tingkatan -->
                    <div class="mt-6 flex flex-wrap gap-4">
                        <span class="text-sm font-medium text-gray-700">Filter berdasarkan tingkatan:</span>
                        <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('tingkatan') == '' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                            Semua
                        </a>
                        @foreach(['1', '2', '3', '4', '5', '6', 'Umum'] as $tingkat)
                            <a href="{{ route('pengajaran.mata-pelajaran.index', ['tingkatan' => $tingkat]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ request('tingkatan') == $tingkat ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $tingkat == 'Umum' ? 'Umum' : 'Kelas ' . $tingkat }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                        @if($mapel->tingkatan == 'Umum')
                                            Umum
                                        @else
                                            Kelas {{ $mapel->tingkatan }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $mapel->kategori }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-600 text-center">{{ $mapel->duration_jp }}</td>
                                    <td class="px-6 py-4 text-slate-600">
                                        @forelse ($mapel->teachers as $teacher)
                                            <span class="inline-block bg-slate-100 text-slate-700 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
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
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">Tidak ada data mata pelajaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $mataPelajarans->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>