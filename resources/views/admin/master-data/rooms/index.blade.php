<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Ruangan</h1>
                            <p class="mt-1 text-slate-600">Daftar semua ruangan yang tersedia untuk penjadwalan.</p>
                        </div>
                        <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                            Tambah Ruangan Baru
                        </a>
                    </div>
                </div>

                <!-- Daftar Ruangan -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Nama Ruangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tipe</th>
                                <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($rooms as $room)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $room->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $room->type == 'Khusus' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">{{ $room->type }}</span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-4">
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-500">Belum ada ruangan yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

