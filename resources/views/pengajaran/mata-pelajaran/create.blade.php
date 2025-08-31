<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

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
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.mata-pelajaran.store') }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">
                            
                            <div>
                                <label for="nama_pelajaran" class="block text-sm font-medium text-gray-700">Nama Pelajaran</label>
                                <input type="text" name="nama_pelajaran" id="nama_pelajaran" value="{{ old('nama_pelajaran') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <x-input-error class="mt-2" :messages="$errors->get('nama_pelajaran')" />
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                {{-- [PERBAIKAN] Mengganti input teks menjadi dropdown --}}
                                <select name="kategori" id="kategori" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Umum" {{ old('kategori') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="Diniyah" {{ old('kategori') == 'Diniyah' ? 'selected' : '' }}>Diniyah</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
                            </div>

                            <div>
                                <label for="duration_jp" class="block text-sm font-medium text-gray-700">Durasi (Jam Pelajaran)</label>
                                <input type="number" name="duration_jp" id="duration_jp" value="{{ old('duration_jp') }}" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <x-input-error class="mt-2" :messages="$errors->get('duration_jp')" />
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="requires_special_room" name="requires_special_room" type="checkbox" value="1" {{ old('requires_special_room') ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="requires_special_room" class="font-medium text-gray-700">Butuh Ruang Khusus?</label>
                                    <p class="text-gray-500">Centang jika pelajaran ini harus diadakan di laboratorium atau ruang khusus lainnya.</p>
                                </div>
                            </div>

                            <div>
                                <label for="teacher_ids" class="block text-sm font-medium text-gray-700">Guru Pengampu</label>
                                <select name="teacher_ids[]" id="teacher_ids" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ in_array($teacher->id, old('teacher_ids', [])) ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Anda bisa memilih lebih dari satu guru (tahan Ctrl atau Cmd saat klik).</p>
                                <x-input-error class="mt-2" :messages="$errors->get('teacher_ids')" />
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

