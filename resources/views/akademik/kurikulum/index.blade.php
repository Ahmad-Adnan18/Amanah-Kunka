<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Kurikulum Terpadu</h1>
                <p class="mt-1 text-slate-600">Atur template kurikulum dan terapkan ke kelas-kelas yang sesuai.</p>
            </div>

            @if (session('success'))
                <div class="mt-6 bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div x-data="{ activeTab: 'apply' }" class="mt-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                        <button @click="activeTab = 'apply'" :class="{ 'border-red-500 text-red-600': activeTab === 'apply', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'apply' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Terapkan Template</button>
                        <button @click="activeTab = 'manage'" :class="{ 'border-red-500 text-red-600': activeTab === 'manage', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'manage' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Manajemen Template</button>
                    </nav>
                </div>

                <div class="mt-6">
                    <div x-show="activeTab === 'apply'" x-cloak>
                        <form action="{{ route('akademik.kurikulum.apply') }}" method="POST">
                            @csrf
                            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 space-y-6">
                                <div>
                                    <label for="template_id" class="block text-sm font-medium text-gray-700">1. Pilih Template Kurikulum</label>
                                    <select name="template_id" id="template_id" class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                        <option value="">-- Pilih Template --</option>
                                        @foreach ($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->nama_template }} ({{ $template->mata_pelajarans_count }} Mapel)</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">2. Pilih Kelas yang Akan Diterapkan</label>
                                    
                                    <div class="mt-2">
                                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">Kelas yang Belum Diatur</p>
                                        <div class="border border-slate-200 rounded-xl p-4 max-h-48 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                            @forelse ($kelasList->whereNull('kurikulum_template_id') as $kelas)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <span class="ml-3 text-sm text-gray-700">{{ $kelas->nama_kelas }}</span>
                                            </label>
                                            @empty
                                            <p class="col-span-full text-sm text-center text-gray-400 py-4">Semua kelas sudah memiliki kurikulum.</p>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase">Kelas yang Sudah Diatur (Pilih untuk menimpa)</p>
                                        <div class="border border-slate-200 rounded-xl p-4 max-h-48 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                            @forelse ($kelasList->whereNotNull('kurikulum_template_id') as $kelas)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <div class="ml-3">
                                                    <span class="text-sm text-gray-700">{{ $kelas->nama_kelas }}</span>
                                                    <span class="block text-xs text-green-600">({{ $kelas->kurikulumTemplate->nama_template }})</span>
                                                </div>
                                            </label>
                                            @empty
                                            <p class="col-span-full text-sm text-center text-gray-400 py-4">Belum ada kelas yang diatur.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-200">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                        Terapkan ke Kelas Terpilih
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div x-show="activeTab === 'manage'" x-cloak>
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                            <form action="{{ route('akademik.kurikulum.template.store') }}" method="POST" class="p-6 border-b border-slate-200 bg-slate-50">
                                @csrf
                                <label for="nama_template" class="block text-sm font-medium text-gray-900">Buat Template Baru</label>
                                <div class="mt-1 flex flex-col sm:flex-row gap-3">
                                    <input id="nama_template" name="nama_template" type="text" class="block w-full flex-grow rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Kurikulum Tingkat 1" required />
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                                        Buat Template
                                    </button>
                                </div>
                            </form>
                            <ul class="divide-y divide-slate-200">
                                @forelse ($templates as $template)
                                    <li class="px-6 py-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $template->nama_template }}</p>
                                            <p class="text-sm text-slate-500">{{ $template->mata_pelajarans_count }} Mata Pelajaran</p>
                                        </div>
                                        <div class="flex items-center space-x-4 flex-shrink-0">
                                            <a href="{{ route('akademik.kurikulum.template.edit', $template) }}" class="font-medium text-slate-600 hover:text-red-700">Atur Mapel</a>
                                            <form action="{{ route('akademik.kurikulum.template.destroy', $template) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </li>
                                @empty
                                    <li class="px-6 py-12 text-center text-slate-500">Belum ada template kurikulum yang dibuat.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>