<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Ketersediaan Guru</h1>
                        <p class="mt-1 text-slate-600">Pilih nama pengajar untuk mengatur jadwal mengajarnya.</p>
                    </div>
                </div>

                <!-- Daftar Guru -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <ul role="list" class="divide-y divide-slate-200">
                        @forelse ($teachers as $teacher)
                        <li class="p-4 hover:bg-slate-50">
                            <a href="{{ route('admin.teacher-availability.edit', $teacher) }}" class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=FBBF24&color=78350F" alt="Avatar">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $teacher->name }}</p>
                                        
                                        {{-- [PERBAIKAN] Menambahkan logika untuk menampilkan status --}}
                                        @if($unavailabilities->contains($teacher->id))
                                            <p class="text-xs text-green-600 font-medium">Sudah Diatur</p>
                                        @else
                                            <p class="text-xs text-slate-500">Belum Diatur</p>
                                        @endif

                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                        @empty
                        <li class="px-6 py-12 text-center text-slate-500">
                            Tidak ada data pengajar.
                        </li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

