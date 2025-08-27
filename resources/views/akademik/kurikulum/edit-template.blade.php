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
                </div>

                <!-- Form Kurikulum -->
                <form action="{{ route('akademik.kurikulum.template.update', $template) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                        <!-- Daftar Mata Pelajaran -->
                        <div class="p-6 min-h-[300px] max-h-[60vh] overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @forelse ($allMataPelajaran as $mapel)
                                    <label class="flex items-center p-3 rounded-lg hover:bg-slate-50 cursor-pointer border border-slate-200 has-[:checked]:bg-red-50 has-[:checked]:border-red-300 transition-colors duration-200">
                                        <input type="checkbox" 
                                               name="mata_pelajaran_ids[]" 
                                               value="{{ $mapel->id }}"
                                               @if(in_array($mapel->id, $assignedMapelIds)) checked @endif
                                               class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="ml-3 text-sm font-medium text-slate-800">{{ $mapel->nama_pelajaran }}</span>
                                    </label>
                                @empty
                                    <div class="col-span-full text-center text-slate-500 py-12">
                                        <p class="font-semibold">Tidak ada data mata pelajaran.</p>
                                        <p class="text-sm mt-1">Silakan tambahkan di menu "Manajemen Mata Pelajaran" terlebih dahulu.</p>
                                    </div>
                                @endforelse
                            </div>
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
</x-app-layout>