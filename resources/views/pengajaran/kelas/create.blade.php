<x-app-layout>
    <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 sm:p-8 border-b border-slate-200">
                    {{-- Style: Warna judul disesuaikan --}}
                    <h1 class="text-2xl font-bold tracking-tight text-red-700">Tambah Kelas Baru</h1>
                    <p class="mt-1 text-slate-600">Isi formulir di bawah untuk mendaftarkan kelas baru.</p>
                </div>
                <div class="p-8">
                    <form action="{{ route('pengajaran.kelas.store') }}" method="POST" class="mt-6">
                        @csrf
                        <div>
                            <x-input-label for="nama_kelas" value="Nama Kelas" />
                            {{-- Style: Gaya fokus input disesuaikan --}}
                            <x-text-input id="nama_kelas" name="nama_kelas" type="text" class="mt-1 block w-full focus:ring-red-600 focus:border-red-600" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_kelas')" />
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-4">
                            {{-- Style: Tombol disesuaikan --}}
                            <a href="{{ route('pengajaran.kelas.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
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
