<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Atur Mata Pelajaran untuk Template</h1>
                    <p class="mt-1 text-slate-600">
                        Template: <span class="font-semibold text-red-700">{{ $template->nama_template }}</span>
                    </p>
                    <p class="mt-1 text-sm text-slate-500">Centang semua mata pelajaran yang termasuk dalam kurikulum ini.</p>
                    
                    <!-- Filter berdasarkan tingkatan -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="text-sm font-medium text-gray-700">Filter:</span>
                        <button type="button" onclick="filterMapel('all')" class="filter-btn active px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Semua
                        </button>
                        @foreach(['1', '2', '3', '4', '5', '6', 'Umum'] as $tingkat)
                            <button type="button" onclick="filterMapel('{{ $tingkat }}')" class="filter-btn px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800" data-tingkatan="{{ $tingkat }}">
                                {{ $tingkat == 'Umum' ? 'Umum' : 'Kelas ' . $tingkat }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Form Kurikulum -->
                <form action="{{ route('akademik.kurikulum.template.update', $template) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <!-- Daftar Mata Pelajaran -->
                        <div class="p-6 min-h-[300px] max-h-[60vh] overflow-y-auto">
                            @php
                                // [PERBAIKAN] Gunakan variable yang sesuai dari Controller
                                $mapelList = $allMataPelajaran ?? $allMataPelajaran ?? collect();
                                
                                // Group mata pelajaran by tingkatan
                                $groupedMapel = $mapelList->groupBy('tingkatan');
                            @endphp
                            
                            @forelse ($groupedMapel as $tingkatan => $mataPelajarans)
                                <div class="mb-6 mapel-group" data-tingkatan="{{ $tingkatan }}">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3">
                                        {{ $tingkatan == 'Umum' ? 'Mata Pelajaran Umum' : 'Kelas ' . $tingkatan }}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach ($mataPelajarans as $mapel)
                                            <label class="flex items-center p-3 rounded-lg hover:bg-slate-50 cursor-pointer border border-slate-200 has-[:checked]:bg-red-50 has-[:checked]:border-red-300 transition-colors duration-200">
                                                <input type="checkbox" 
                                                       name="mata_pelajaran_ids[]" 
                                                       value="{{ $mapel->id }}"
                                                       @if(in_array($mapel->id, $assignedMapelIds)) checked @endif
                                                       class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <span class="ml-3 text-sm font-medium text-slate-800">{{ $mapel->nama_pelajaran }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center text-slate-500 py-12">
                                    <p class="font-semibold">Tidak ada data mata pelajaran.</p>
                                    <p class="text-sm mt-1">Silakan tambahkan di menu "Manajemen Mata Pelajaran" terlebih dahulu.</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <!-- Footer Aksi Form -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                            <a href="{{ route('akademik.kurikulum.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Kembali
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Template
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterMapel(tingkatan) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.getAttribute('data-tingkatan') === tingkatan || (tingkatan === 'all' && btn.textContent.includes('Semua'))) {
                    btn.classList.add('bg-red-100', 'text-red-800');
                    btn.classList.remove('bg-gray-100', 'text-gray-800');
                } else {
                    btn.classList.remove('bg-red-100', 'text-red-800');
                    btn.classList.add('bg-gray-100', 'text-gray-800');
                }
            });

            // Show/hide mapel groups
            document.querySelectorAll('.mapel-group').forEach(group => {
                if (tingkatan === 'all' || group.getAttribute('data-tingkatan') === tingkatan) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
        }

        // Inisialisasi filter saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            filterMapel('all');
        });
    </script>
</x-app-layout>