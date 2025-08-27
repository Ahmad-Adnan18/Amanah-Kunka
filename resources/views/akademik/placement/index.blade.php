<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Alat Penempatan Kelas</h1>
                    <p class="mt-1 text-slate-600">Pindahkan santri dari kelas lama ke kelas baru untuk tahun ajaran berikutnya.</p>
                </div>

                <!-- Notifikasi Sukses -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                    </div>
                @endif

                <!-- Form Filter Kelas Asal -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200">
                    <form action="{{ route('akademik.placement.index') }}" method="GET">
                        <label for="source_kelas_id" class="block text-lg font-semibold text-gray-900">Langkah 1: Pilih Kelas Asal Santri</label>
                        <div class="mt-2 flex flex-col sm:flex-row items-end gap-4">
                            <div class="flex-grow w-full">
                                <select name="source_kelas_id" id="source_kelas_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" @selected(request('source_kelas_id') == $kelas->id)>{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Tampilkan Santri
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Panel Penempatan -->
                @if ($santris->isNotEmpty())
                <div x-data="{ selectedSantri: [], checkAll: false, toggleSelectAll() { this.checkAll = !this.checkAll; if (this.checkAll) { this.selectedSantri = {{ $santris->pluck('id') }}; } else { this.selectedSantri = []; } } }">
                    <form action="{{ route('akademik.placement.place') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <!-- Panel Kiri: Daftar Santri -->
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                <div class="p-4 bg-slate-50 border-b border-slate-200">
                                    <h2 class="font-semibold text-gray-800">Langkah 2: Pilih Santri yang Akan Dipindahkan</h2>
                                </div>
                                <div class="p-4">
                                    <label class="flex items-center border-b border-slate-200 pb-2 mb-2">
                                        <input type="checkbox" @change="toggleSelectAll()" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                        <span class="ml-2 font-medium text-sm text-slate-800">Pilih Semua</span>
                                    </label>
                                    <div class="max-h-96 overflow-y-auto space-y-1 pr-2">
                                        @foreach ($santris as $santri)
                                            <label class="flex items-center p-2 rounded-md hover:bg-slate-50 cursor-pointer">
                                                <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" x-model="selectedSantri" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                                <span class="ml-3 text-sm text-slate-900">{{ $santri->nama }} <span class="text-slate-500">({{ $santri->nis }})</span></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Panel Kanan: Kelas Tujuan -->
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                                 <div class="p-4 bg-slate-50 border-b border-slate-200">
                                    <h2 class="font-semibold text-gray-800">Langkah 3: Pilih Kelas Tujuan</h2>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div>
                                        <label for="target_kelas_id" class="block text-sm font-medium text-slate-800">Pindahkan <strong x-text="selectedSantri.length" class="text-red-700"></strong> santri terpilih ke:</label>
                                        <select name="target_kelas_id" id="target_kelas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                            <option value="">-- Pilih Kelas Tujuan --</option>
                                            @foreach($kelasList as $kelas)
                                                @if(request('source_kelas_id') != $kelas->id)
                                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" :disabled="selectedSantri.length === 0" class="w-full inline-flex justify-center py-2 px-4 rounded-md text-white bg-red-700 hover:bg-red-800 disabled:bg-red-300 disabled:cursor-not-allowed">
                                        Tempatkan Santri
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
