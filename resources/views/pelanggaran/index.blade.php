<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Catatan Pelanggaran Santri</h1>
                            <p class="mt-1 text-slate-600">Daftar semua catatan pelanggaran yang telah diinput.</p>
                        </div>
                        @can('create', App\Models\Pelanggaran::class)
                        <a href="{{ route('pelanggaran.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Catatan</span>
                        </a>
                        @endcan
                    </div>
                </div>

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                    </div>
                @endif

                <div class="space-y-4 md:hidden">
                    @forelse ($pelanggarans as $pelanggaran)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                    <a href="{{ route('santri.profil.show', $pelanggaran->santri) }}" class="text-sm font-medium text-red-700 hover:text-red-900 mt-1 block">
                                        {{ $pelanggaran->santri->nama }}
                                    </a>
                                    <div class="mt-3 pt-3 border-t border-slate-200 text-sm text-slate-500 space-y-1">
                                       <p>Tanggal: {{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</p>
                                       <p>Dicatat oleh: {{ $pelanggaran->dicatat_oleh }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2 flex-shrink-0">
                                    @can('update', $pelanggaran)
                                    <a href="{{ route('pelanggaran.edit', $pelanggaran) }}" class="font-medium text-slate-600 hover:text-red-700 p-1">Edit</a>
                                    @endcan
                                    @can('delete', $pelanggaran)
                                    <form action="{{ route('pelanggaran.destroy', $pelanggaran) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-900 p-1">Hapus</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center text-slate-500">
                            <p>Belum ada data pelanggaran.</p>
                        </div>
                    @endforelse
                </div>

                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Pelanggaran</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($pelanggarans as $pelanggaran)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('santri.profil.show', $pelanggaran->santri) }}" class="font-medium text-slate-900 hover:text-red-700">{{ $pelanggaran->santri->nama }}</a>
                                        <div class="text-sm text-slate-500">{{ $pelanggaran->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $pelanggaran->dicatat_oleh }}</td>
                                    <td class="px-6 py-4 text-right space-x-4">
                                        @can('update', $pelanggaran)
                                        <a href="{{ route('pelanggaran.edit', $pelanggaran) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                        @endcan
                                        @can('delete', $pelanggaran)
                                        <form action="{{ route('pelanggaran.destroy', $pelanggaran) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data pelanggaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>