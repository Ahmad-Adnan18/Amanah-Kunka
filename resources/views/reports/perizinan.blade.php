<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Laporan Perizinan</h1>
                            <p class="mt-1 text-slate-600">Filter dan ekspor rekap data perizinan santri.</p>
                        </div>
                        <a href="{{ route('laporan.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 flex-shrink-0">
                            Kembali
                        </a>
                    </div>
                </div>
                
                <!-- Form Filter -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('laporan.perizinan') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" id="kategori" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Semua Kategori</option>
                                <option value="Pengasuhan" @selected(request('kategori') == 'Pengasuhan')>Pengasuhan</option>
                                <option value="Kesehatan" @selected(request('kategori') == 'Kesehatan')>Kesehatan</option>
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 rounded-md text-white bg-red-700 hover:bg-red-600 shadow-sm">Filter</button>
                            <a href="{{ route('laporan.perizinan.export', request()->query()) }}" class="w-full inline-flex justify-center py-2 px-4 rounded-md text-white bg-green-600 hover:bg-green-500 shadow-sm">Export</a>
                        </div>
                    </form>
                </div>

                <!-- Tabel Hasil -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Santri</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($perizinans as $izin)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">{{ $izin->santri->nama }}</div>
                                            <div class="text-sm text-slate-500">{{ $izin->santri->kelas->nama_kelas ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900">{{ $izin->jenis_izin }}</div>
                                            <div class="text-sm text-slate-500">{{ $izin->kategori }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 text-sm">
                                            Mulai: {{ $izin->tanggal_mulai->format('d M Y') }} <br>
                                            @if($izin->tanggal_akhir)
                                                Kembali: {{ $izin->tanggal_akhir->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">{{ $izin->pembuat->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada data yang cocok dengan filter Anda.</td></tr>
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
