<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{
            selectedKelas: '{{ request('kelas_id') }}',
            mapelList: [],
            isLoading: false,
            fetchMapel() {
                if (!this.selectedKelas) {
                    this.mapelList = [];
                    return;
                }
                this.isLoading = true;
                fetch(`/akademik/kurikulum/${this.selectedKelas}/mapel-json`)
                    .then(response => response.json())
                    .then(data => {
                        this.mapelList = data;
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                        // Handle error if needed
                    });
            }
        }" x-init="fetchMapel()">

            <div class="space-y-8">
                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Input Nilai Santri</h1>
                    <p class="mt-1 text-slate-600">Pilih kelas, mata pelajaran, dan sesi penilaian untuk memulai.</p>
                </div>

                <!-- Form Filter -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('akademik.nilai.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                        {{-- Kelas --}}
                        <div class="lg:col-span-2">
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas_id" id="kelas_id" x-model="selectedKelas" @change="fetchMapel()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Mata Pelajaran (Dinamis) --}}
                        <div class="lg:col-span-2">
                            <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" :disabled="isLoading || !selectedKelas" required>
                                <template x-if="isLoading"><option>Memuat...</option></template>
                                <template x-if="!isLoading && selectedKelas && mapelList.length > 0"><option value="">-- Pilih Mapel --</option></template>
                                <template x-for="mapel in mapelList" :key="mapel.id">
                                    <option :value="mapel.id" x-text="mapel.nama_pelajaran" :selected="mapel.id == '{{ request('mata_pelajaran_id') }}'"></option>
                                </template>
                                <template x-if="!isLoading && selectedKelas && mapelList.length == 0"><option value="">-- Kurikulum kosong --</option></template>
                                <template x-if="!selectedKelas"><option value="">-- Pilih kelas dulu --</option></template>
                            </select>
                        </div>
                        {{-- Semester --}}
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester" id="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                <option value="Ganjil" @selected(request('semester') == 'Ganjil')>Ganjil</option>
                                <option value="Genap" @selected(request('semester') == 'Genap')>Genap</option>
                            </select>
                        </div>
                        {{-- Tahun Ajaran --}}
                        <div>
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ request('tahun_ajaran', date('Y').'/'.(date('Y')+1)) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        </div>
                        {{-- Jenis Penilaian --}}
                        <div class="lg:col-span-4">
                            <label for="jenis_penilaian" class="block text-sm font-medium text-gray-700">Jenis Penilaian</label>
                            <select name="jenis_penilaian" id="jenis_penilaian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                <option value="">-- Pilih Jenis Nilai --</option>
                                <option value="nilai_tugas" @selected(request('jenis_penilaian') == 'nilai_tugas')>Nilai Tugas</option>
                                <option value="nilai_uts" @selected(request('jenis_penilaian') == 'nilai_uts')>Nilai UTS</option>
                                <option value="nilai_uas" @selected(request('jenis_penilaian') == 'nilai_uas')>Nilai UAS</option>
                            </select>
                        </div>
                        {{-- Tombol Submit --}}
                        <div class="lg:col-span-2">
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Tampilkan Santri
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Input Nilai -->
                @if ($santris->isNotEmpty())
                <form action="{{ route('akademik.nilai.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
                    <input type="hidden" name="mata_pelajaran_id" value="{{ request('mata_pelajaran_id') }}">
                    <input type="hidden" name="semester" value="{{ request('semester') }}">
                    <input type="hidden" name="tahun_ajaran" value="{{ request('tahun_ajaran') }}">
                    <input type="hidden" name="jenis_penilaian" value="{{ request('jenis_penilaian') }}">

                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <!-- Header Tabel & Tombol Export -->
                        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Santri</h3>
                            <a href="{{ route('akademik.nilai.export', request()->query()) }}" class="inline-flex items-center justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                Export Leger
                            </a>
                        </div>

                        <!-- Tabel -->
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama Santri</th>
                                    <th class="w-48 px-4 py-3.5 text-center text-xs font-semibold text-slate-500 uppercase">
                                        {{ str_replace('_', ' ', Str::ucfirst(request('jenis_penilaian'))) }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach ($santris as $index => $santri)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <input type="hidden" name="nilais[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                        <div class="font-medium text-slate-900">{{ $santri->nama }}</div>
                                        <div class="text-sm text-slate-500">{{ $santri->nis }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        @php $jenisNilai = request('jenis_penilaian'); @endphp
                                        <input type="number" name="nilais[{{ $index }}][nilai]" class="block w-full text-center rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" min="0" max="100" value="{{ optional($santri->nilai->first())->$jenisNilai }}" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Footer Aksi Form -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Nilai
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
