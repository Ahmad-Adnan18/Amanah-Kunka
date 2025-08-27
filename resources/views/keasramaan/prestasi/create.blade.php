<x-app-layout>
    <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border p-8">
                <h1 class="text-2xl font-bold">Tambah Prestasi untuk {{ $santri->nama }}</h1>
                <form action="{{ route('keasramaan.prestasi.store', $santri) }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="nama_prestasi" value="Nama Prestasi/Pencapaian" />
                        <x-text-input id="nama_prestasi" name="nama_prestasi" type="text" class="mt-1 block w-full" :value="old('nama_prestasi')" required />
                    </div>
                    <div>
                        <x-input-label for="poin" value="Poin" />
                        <x-text-input id="poin" name="poin" type="number" class="mt-1 block w-full" :value="old('poin')" required />
                    </div>
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', now()->format('Y-m-d'))" required />
                    </div>
                    <div>
                        <x-input-label for="keterangan" value="Keterangan (Opsional)" />
                        <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('keterangan') }}</textarea>
                    </div>
                    <div class="flex items-center justify-end gap-x-6">
                        <a href="{{ route('santri.profil.show', $santri) }}" class="text-sm font-semibold">Batal</a>
                        <x-primary-button>Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
