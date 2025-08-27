<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8" x-data="{ selectedIds: [], checkAll: false, toggleSelectAll() { this.checkAll = !this.checkAll; if (this.checkAll) { this.selectedIds = {{ $perizinans->pluck('id') }}; } else { this.selectedIds = []; } } }">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Daftar Santri Izin Aktif</h1>
                    <p class="mt-1 text-slate-600">Berikut adalah daftar santri yang sedang tidak berada di pondok atau tidak mengikuti KBM.</p>
                </div>

                <!-- Tombol Aksi Massal -->
                @can('delete', App\Models\Perizinan::newModelInstance())
                <div x-show="selectedIds.length > 0" class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 flex items-center justify-between" x-cloak>
                    <p class="text-sm font-medium"><span x-text="selectedIds.length"></span> data dipilih</p>
                    <form action="{{ route('perizinan.bulkDestroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Hapus yang Dipilih</button>
                    </form>
                </div>
                @endcan

                <!-- Kontainer Tabel -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    @can('delete', App\Models\Perizinan::newModelInstance())
                                    <th class="pl-4 pr-3 py-3.5">
                                        <input type="checkbox" x-model="checkAll" @change="toggleSelectAll()" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                    </th>
                                    @endcan
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                    <th scope="col" class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($perizinans as $izin)
                                    <tr class="hover:bg-slate-50">
                                        @can('delete', $izin)
                                        <td class="pl-4 pr-3 py-4">
                                            <input type="checkbox" x-model="selectedIds" value="{{ $izin->id }}" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                        </td>
                                        @endcan
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $izin->santri->foto ? Storage::url($izin->santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($izin->santri->nama).'&background=FBBF24&color=fff&font-size=0.4' }}" alt="Avatar">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-slate-900">{{ $izin->santri->nama }}</div>
                                                    <div class="text-sm text-slate-500">{{ $izin->santri->nis }} / {{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-900">{{ $izin->jenis_izin }}</div>
                                            <div class="text-xs text-slate-500 truncate max-w-xs">{{ $izin->keterangan }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            Mulai: {{ $izin->tanggal_mulai->format('d M Y') }} <br>
                                            @if($izin->tanggal_akhir)
                                                Kembali: {{ $izin->tanggal_akhir->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $izin->pembuat->name }} ({{ ucwords($izin->kategori) }})</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('delete', $izin)
                                            <form action="{{ route('perizinan.destroy', $izin) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                            Tidak ada santri yang sedang izin saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($perizinans->hasPages())
                        <div class="p-4 border-t border-slate-200 bg-slate-50">
                            {{ $perizinans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
