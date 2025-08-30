<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Profil Santri & Aksi -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row items-start gap-6">
                            <div class="flex-shrink-0">
                                <img class="h-28 w-28 rounded-full object-cover ring-4 ring-red-100" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}" alt="Foto Santri">
                            </div>
                            <div class="flex-grow">
                                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $santri->nama }}</h1>
                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-slate-600">
                                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                                            <path fill-rule="evenodd" d="M12.25 4.75a.75.75 0 01.75.75v8.5a.75.75 0 01-1.5 0v-8.5a.75.75 0 01.75-.75zM7.25 4.75a.75.75 0 01.75.75v8.5a.75.75 0 01-1.5 0v-8.5a.75.75 0 01.75-.75zM4.75 6.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM12 6.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" /></svg> {{ $santri->nis }}</span>
                                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                                            <path d="M3.75 3A1.75 1.75 0 002 4.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0018 15.25V4.75A1.75 1.75 0 0016.25 3H3.75zM9 7.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 019 7.5zm3.75-1.5a.75.75 0 00-1.5 0v7.5a.75.75 0 001.5 0v-7.5z" /></svg> {{ $santri->kelas->nama_kelas ?? 'N/A' }}</span>
                                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                                            <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v.25a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM5.133 4.352a.75.75 0 01.936 1.204l-.793.616a.75.75 0 01-.936-1.204l.793-.616zM13.93 5.556a.75.75 0 01.936-1.204l.793.616a.75.75 0 01-.936 1.204l-.793-.616zM10 8a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM12.5 8a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a4.5 4.5 0 100-9 4.5 4.5 0 000 9zM15 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM10 18a.75.75 0 00.75-.75v-.25a.75.75 0 00-1.5 0v.25c0 .414.336.75.75.75z" clip-rule="evenodd" /></svg> {{ $santri->jenis_kelamin }}</span>
                                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                                            <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.979.577.75.75 0 00.018.008l.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg> {{ $santri->rayon }}</span>
                                </div>
                                @if($namaWaliKelas)
                                <p class="mt-3 text-sm text-gray-800 bg-green-100 inline-block px-3 py-1 rounded-full">
                                    <span class="font-semibold">Wali Kelas:</span> {{ $namaWaliKelas }}
                                </p>
                                @endif
                            </div>
                            <!-- Tombol Aksi Cepat -->
                            <div class="flex-shrink-0 w-full sm:w-auto mt-4 md:mt-0" x-data="{ open: false }">
                                <div class="relative flex justify-center md:justify-end">
                                    <button @click="open = !open" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                        <span>Aksi Cepat</span>
                                        <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition class="absolute z-10 mt-2 w-48 sm:w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" :class="{'right-0 top-full': !$isMobile(), 'left-0 bottom-full': $isMobile()}" x-cloak>
                                        <div class="py-1">
                                            @can('create', App\Models\Perizinan::class)
                                            <a href="{{ route('perizinan.create', $santri) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Buat Izin</a>
                                            @endcan
                                            @can('create', [App\Models\CatatanHarian::class, $santri])
                                            <a href="{{ route('keasramaan.catatan.create', $santri) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tambah Catatan</a>
                                            @endcan
                                            @can('create', [App\Models\Prestasi::class, $santri])
                                            <a href="{{ route('keasramaan.prestasi.create', $santri) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tambah Prestasi</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @can('update', $santri)
                <div class="bg-slate-50 border-t border-slate-200 px-6 py-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Kode Registrasi Wali</h3>
                            <p class="mt-1 text-sm text-slate-600">Bagikan kode ini kepada wali santri.</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if ($santri->kode_registrasi_wali)
                            <div class="flex items-center gap-2">
                                <span class="px-4 py-2 bg-slate-200 text-slate-800 font-mono text-lg rounded-md">{{ $santri->kode_registrasi_wali }}</span>
                                <form action="{{ route('santri.profil.generate_wali_code', $santri) }}" method="POST" onsubmit="return confirm('Yakin ingin membuat kode baru? Kode lama akan hangus.')">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Buat Ulang</button>
                                </form>
                            </div>
                            @else
                            <form action="{{ route('santri.profil.generate_wali_code', $santri) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Generate Kode
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endcan
            </div>

            <!-- Sistem Tab -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200" x-data="{ activeTab: 'perizinan' }">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto px-6" aria-label="Tabs">
                        <button @click="activeTab = 'perizinan'" :class="{ 'border-red-500 text-red-600': activeTab === 'perizinan', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'perizinan' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Riwayat Perizinan</button>
                        <button @click="activeTab = 'pelanggaran'" :class="{ 'border-red-500 text-red-600': activeTab === 'pelanggaran', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'pelanggaran' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Riwayat Pelanggaran</button>
                        <button @click="activeTab = 'catatan'" :class="{ 'border-red-500 text-red-600': activeTab === 'catatan', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'catatan' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Catatan Harian</button>
                        <button @click="activeTab = 'prestasi'" :class="{ 'border-red-500 text-red-600': activeTab === 'prestasi', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'prestasi' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Prestasi</button>
                        <button @click="activeTab = 'akademik'" :class="{ 'border-red-500 text-red-600': activeTab === 'akademik', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'akademik' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Rapor Akademik</button>
                    </nav>
                </div>
                <div class="p-6">

                    <!-- Konten Tab -->
                    <div class="mt-6">
                        <!-- Tab Riwayat Perizinan -->
                        <div x-show="activeTab === 'perizinan'" x-cloak>
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Izin</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->perizinans as $izin)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900">{{ $izin->jenis_izin }}</div>
                                                <div class="text-sm text-slate-500">{{ $izin->keterangan }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-500">
                                                {{ $izin->tanggal_mulai->format('d M Y') }}
                                                @if($izin->tanggal_akhir) - {{ $izin->tanggal_akhir->format('d M Y') }} @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $izin->status == 'aktif' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($izin->status) }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-500">Tidak ada riwayat perizinan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Riwayat Pelanggaran -->
                        <div x-show="activeTab === 'pelanggaran'" x-cloak>
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis Pelanggaran</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal Kejadian</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->pelanggarans as $pelanggaran)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900">{{ $pelanggaran->jenis_pelanggaran }}</div>
                                                <div class="text-sm text-slate-500">{{ $pelanggaran->keterangan }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-500">{{ $pelanggaran->dicatat_oleh }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-500">Tidak ada riwayat pelanggaran.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- KONTEN TAB BARU: Catatan Harian -->
                        <div x-show="activeTab === 'catatan'" x-cloak>
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Catatan</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                            <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->catatanHarians as $catatan)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-slate-500">{{ $catatan->tanggal->format('d M Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-800">{{ $catatan->catatan }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-500">{{ $catatan->pencatat->name }}</td>
                                            <td class="px-6 py-4 text-right space-x-4">
                                                @can('update', $catatan)
                                                <a href="{{ route('keasramaan.catatan.edit', $catatan) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                                @endcan
                                                @can('delete', $catatan)
                                                <form action="{{ route('keasramaan.catatan.destroy', $catatan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada catatan harian.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- KONTEN TAB BARU: Prestasi -->
                        <div x-show="activeTab === 'prestasi'" x-cloak>
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Prestasi</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Poin</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                                            <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($santri->prestasis as $prestasi)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-slate-500">{{ $prestasi->tanggal->format('d M Y') }}</td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900">{{ $prestasi->nama_prestasi }}</div>
                                                <div class="text-sm text-slate-500">{{ $prestasi->keterangan }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold text-green-600">+{{ $prestasi->poin }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-500">{{ $prestasi->pencatat->name }}</td>
                                            <td class="px-6 py-4 text-right space-x-4">
                                                @can('update', $prestasi)
                                                <a href="{{ route('keasramaan.prestasi.edit', $prestasi) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                                @endcan
                                                @can('delete', $prestasi)
                                                <form action="{{ route('keasramaan.prestasi.destroy', $prestasi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">Tidak ada catatan prestasi.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Rapor Akademik -->
                        <div x-show="activeTab === 'akademik'" x-cloak>
                            <div x-data="{
                            raporData: {{ json_encode($nilaiAkademik) }},
                            tahunAjaranList: Object.keys({{ json_encode($nilaiAkademik) }}).sort().reverse(),
                            selectedTahun: '{{ $nilaiAkademik->keys()->first() ?? '' }}',
                            selectedSemester: '{{ $nilaiAkademik->isNotEmpty() ? $nilaiAkademik->first()->keys()->first() ?? '' : '' }}',
                            get semesterList() {
                                return this.selectedTahun ? Object.keys(this.raporData[this.selectedTahun]).sort() : [];
                            },
                            get selectedRapor() {
                                if (this.selectedTahun && this.selectedSemester) {
                                    return this.raporData[this.selectedTahun][this.selectedSemester] || [];
                                }
                                return [];
                            },
                            calculateAverage(tugas, uts, uas) {
                                const values = [tugas, uts, uas].filter(v => v !== null);
                                if (values.length === 0) return '-';
                                const sum = values.reduce((a, b) => a + b, 0);
                                return Math.round(sum / values.length);
                            },
                            getPredicate(avg) {
                                if (avg >= 90) return { text: 'Mumtaz', class: 'bg-blue-100 text-blue-800' };
                                if (avg >= 80) return { text: 'Jayyid Jiddan', class: 'bg-green-100 text-green-800' };
                                if (avg >= 70) return { text: 'Jayyid', class: 'bg-yellow-100 text-yellow-800' };
                                if (avg >= 60) return { text: 'Maqbul', class: 'bg-orange-100 text-orange-800' };
                                if (avg > 0) return { text: 'Rasib', class: 'bg-red-100 text-red-800' };
                                return { text: '-', class: 'bg-gray-100 text-gray-800' };
                            }
                        }" x-init="if (!selectedSemester && semesterList.length > 0) { selectedSemester = semesterList[0]; }">

                                <!-- Filter Rapor & Tombol Export -->
                                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4 mb-6">
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                        {{-- Dropdown Filter --}}
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <label for="tahun_ajaran_filter" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                                                <select x-model="selectedTahun" @change="selectedSemester = semesterList[0] || ''" id="tahun_ajaran_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                    <template x-for="tahun in tahunAjaranList" :key="tahun">
                                                        <option :value="tahun" x-text="tahun"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="semester_filter" class="block text-sm font-medium text-gray-700">Semester</label>
                                                <select x-model="selectedSemester" :disabled="!selectedTahun" id="semester_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                    <template x-for="semester in semesterList" :key="semester">
                                                        <option :value="semester" x-text="semester"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- Tombol Export Baru --}}
                                        <div>
                                            <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester" x-show="selectedTahun && selectedSemester" class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                                Export Excel
                                            </a>
                                        </div>
                                        {{-- [DROPDOWN BARU] untuk PDF --}}
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="inline-flex items-center justify-center rounded-md bg-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                                <span>Download PDF</span>
                                                <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" x-cloak>
                                                <div class="py-1">
                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rapor Lengkap</a>
                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_tugas'" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nilai Tugas Saja</a>
                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_uts'" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nilai UTS Saja</a>
                                                    <a :href="'{{ url('/santri/'.$santri->id.'/profil/rapor/export-pdf') }}?tahun_ajaran=' + selectedTahun + '&semester=' + selectedSemester + '&jenis_penilaian=nilai_uas'" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nilai UAS Saja</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabel Rapor -->
                                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Tugas</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">UTS</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">UAS</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Nilai Akhir</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Predikat</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-200">
                                            <template x-if="selectedRapor.length > 0">
                                                <template x-for="nilai in selectedRapor" :key="nilai.id">
                                                    <tr>
                                                        <td class="px-6 py-4 font-medium text-slate-900" x-text="nilai.mata_pelajaran.nama_pelajaran"></td>
                                                        <td class="px-6 py-4 text-center text-slate-500" x-text="nilai.nilai_tugas || '-'"></td>
                                                        <td class="px-6 py-4 text-center text-slate-500" x-text="nilai.nilai_uts || '-'"></td>
                                                        <td class="px-6 py-4 text-center text-slate-500" x-text="nilai.nilai_uas || '-'"></td>
                                                        <td class="px-6 py-4 text-center font-semibold text-slate-900" x-text="calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)"></td>
                                                        <td class="px-6 py-4 text-center">
                                                            <span class="px-2 py-1 text-xs font-semibold leading-5 rounded-full" :class="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).class" x-text="getPredicate(calculateAverage(nilai.nilai_tugas, nilai.nilai_uts, nilai.nilai_uas)).text">
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                            <template x-if="selectedRapor.length === 0">
                                                <tr>
                                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">Tidak ada data nilai untuk periode ini.</td>
                                                </tr>
                                            </template>
                                            <script>
                                                document.addEventListener('alpine:init', () => {
                                                    Alpine.magic('isMobile', () => {
                                                        return () => window.innerWidth < 768;
                                                    });
                                                });

                                            </script>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
