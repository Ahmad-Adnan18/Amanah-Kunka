<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Tambah Mata Pelajaran Baru</h1>
                            <p class="mt-1 text-slate-600">Isi detail mata pelajaran di bawah ini.</p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 flex-shrink-0">
                            Kembali
                        </a>
                    </div>
                </div>
                
                <!-- Form -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.mata-pelajaran.store') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="nama_pelajaran" class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran</label>
                                <input type="text" name="nama_pelajaran" id="nama_pelajaran" value="{{ old('nama_pelajaran') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <x-input-error class="mt-2" :messages="$errors->get('nama_pelajaran')" />
                            </div>
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="kategori" id="kategori" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="Umum" @selected(old('kategori') == 'Umum')>Umum</option>
                                    <option value="Diniyah" @selected(old('kategori') == 'Diniyah')>Diniyah</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                            <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
