<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Data Master Mata Pelajaran</h1>
                            <p class="mt-1 text-slate-600">Kelola semua mata pelajaran yang ada di pondok.</p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Mata Pelajaran</span>
                        </a>
                    </div>
                </div>

                <!-- Tabel -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Mata Pelajaran</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                                <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($mataPelajarans as $mapel)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $mapel->nama_pelajaran }}</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $mapel->kategori == 'Diniyah' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ $mapel->kategori }}</span></td>
                                    <td class="px-6 py-4 text-right space-x-4">
                                        <a href="{{ route('pengajaran.mata-pelajaran.edit', $mapel) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                        <form action="{{ route('pengajaran.mata-pelajaran.destroy', $mapel) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-12 text-center text-slate-500">Belum ada data mata pelajaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
