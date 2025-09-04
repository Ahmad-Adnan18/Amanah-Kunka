<x-app-layout>
    <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                <div class="p-6 sm:p-8 border-b border-slate-200">
                    <h1 class="text-2xl font-bold tracking-tight text-red-700">Tambah Kelas Baru</h1>
                    <p class="mt-1 text-slate-600">Isi formulir di bawah untuk mendaftarkan kelas baru.</p>
                </div>
                <div class="p-8">
                    <form action="{{ route('pengajaran.kelas.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        {{-- Field Nama Kelas --}}
                        <div>
                            <x-input-label for="nama_kelas" value="Nama Kelas" />
                            <x-text-input id="nama_kelas" name="nama_kelas" type="text" class="mt-1 block w-full focus:ring-red-600 focus:border-red-600" required autofocus :value="old('nama_kelas')" />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_kelas')" />
                        </div>

                        {{-- [TAMBAHAN] Field Ruangan Induk --}}
                        <div>
                            <x-input-label for="room_id" value="Ruangan Induk (Home Room)" />
                            <select id="room_id" name="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-600 focus:ring-red-600">
                                <option value="">-- Pilih Ruangan (Opsional) --</option>
                                {{-- Variabel $rooms harus dikirim dari Controller --}}
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} (Tipe: {{ $room->type }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-gray-500">Pilih ruangan utama. Wajib diisi agar kelas bisa dijadwalkan secara otomatis.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('room_id')" />
                        </div>

                        {{-- [TAMBAHAN] Toggle Status untuk Penjadwalan --}}
                        <div>
                            <x-input-label value="Status Penjadwalan" />
                            <div class="mt-2 flex items-center">
                                <input type="hidden" name="is_active_for_scheduling" value="0">
                                <input id="is_active_for_scheduling" name="is_active_for_scheduling" type="checkbox" value="1" 
                                       class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500" 
                                       {{ old('is_active_for_scheduling', true) ? 'checked' : '' }}>
                                <label for="is_active_for_scheduling" class="ml-3 block text-sm text-gray-900">
                                    Aktifkan untuk Penjadwalan Otomatis
                                </label>
                            </div>
                             <p class="mt-1 text-sm text-gray-500">Jika aktif, kelas ini akan langsung disertakan saat generator jadwal dijalankan.</p>
                             <x-input-error class="mt-2" :messages="$errors->get('is_active_for_scheduling')" />
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-6 flex items-center justify-end gap-x-4">
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