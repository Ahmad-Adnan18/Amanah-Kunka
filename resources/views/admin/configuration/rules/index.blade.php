<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Aturan Penjadwalan</h1>
                        <p class="mt-1 text-slate-600">Atur waktu terlarang secara global dan tentukan prioritas jam untuk setiap kategori mata pelajaran.</p>
                    </div>
                </div>

                <!-- [MODIFIKASI] Panel Notifikasi -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm" role="alert">
                        <p class="font-bold">Berhasil</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                     <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm" role="alert">
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif


                <form action="{{ route('admin.rules.store') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        <!-- Panel Waktu Terblokir -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-gray-800">Waktu Terblokir (Global)</h2>
                                <p class="mt-1 text-slate-600">Tandai jam di mana <span class="font-semibold">tidak boleh ada kegiatan belajar mengajar</span> untuk semua kelas (contoh: upacara, istirahat).</p>
                            </div>
                            <div class="p-6 overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 border border-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Hari</th>
                                            @foreach ($timeSlots as $slot)
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Jam Ke-{{ $slot }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach ($days as $dayId => $dayName)
                                            <tr>
                                                <td class="px-4 py-3 font-medium text-slate-800">{{ $dayName }}</td>
                                                @foreach ($timeSlots as $slot)
                                                    <td class="px-4 py-3 text-center">
                                                        <input type="checkbox" name="blocked_times[{{ $dayId }}][{{ $slot }}]" 
                                                               value="1" 
                                                               @checked(isset($blockedTimes[$dayId . '-' . $slot]))
                                                               class="h-5 w-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Panel Prioritas Jam -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                             <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-gray-800">Prioritas Jam per Kategori Mapel</h2>
                                <p class="mt-1 text-slate-600">Tandai jam-jam yang <span class="font-semibold">diizinkan</span> untuk setiap kategori mata pelajaran. Jika tidak ada yang ditandai untuk satu kategori, maka kategori tersebut bisa dijadwalkan di jam mana pun.</p>
                            </div>
                            <div class="p-6 space-y-8">
                                @forelse ($subjectCategories as $category)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-700 mb-3">{{ $category }}</h3>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-slate-200 border border-slate-200">
                                                <thead class="bg-slate-50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Hari</th>
                                                        @foreach ($timeSlots as $slot)
                                                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Jam Ke-{{ $slot }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-slate-200">
                                                    @foreach ($days as $dayId => $dayName)
                                                        <tr>
                                                            <td class="px-4 py-3 font-medium text-slate-800">{{ $dayName }}</td>
                                                            @foreach ($timeSlots as $slot)
                                                                <td class="px-4 py-3 text-center">
                                                                    <input type="checkbox" name="priorities[{{ $category }}][{{ $dayId }}][{{ $slot }}]" 
                                                                           value="1" 
                                                                           @checked(isset($hourPriorities[$category . '-' . $dayId . '-' . $slot]))
                                                                           class="h-5 w-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-500">Belum ada kategori mata pelajaran yang dibuat. Silakan tambahkan kategori pada menu Manajemen Mata Pelajaran terlebih dahulu.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                            <div class="px-6 py-4 flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                    Simpan Semua Aturan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

