<x-app-layout>
    {{-- Link ke CSS untuk Tom-Select, agar pilihan guru lebih modern --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Mata Pelajaran</h1>
                            <p class="mt-1 text-slate-600">Perbarui detail untuk mata pelajaran: <span class="font-semibold">{{ $mataPelajaran->nama_pelajaran }}</span></p>
                        </div>
                        <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 flex-shrink-0">
                            Kembali
                        </a>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('pengajaran.mata-pelajaran.update', $mataPelajaran->id) }}" method="POST" id="edit-form">
                        @csrf
                        @method('PUT')
                        <div class="p-6 space-y-6">
                            
                            {{-- Baris 1: Nama Pelajaran & Tingkatan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nama_pelajaran" class="block text-sm font-medium text-gray-700">Nama Pelajaran</label>
                                    <input type="text" name="nama_pelajaran" id="nama_pelajaran" value="{{ old('nama_pelajaran', $mataPelajaran->nama_pelajaran) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <x-input-error class="mt-2" :messages="$errors->get('nama_pelajaran')" />
                                </div>
                                <div>
                                    <label for="tingkatan" class="block text-sm font-medium text-gray-700">Tingkatan</label>
                                    <select name="tingkatan" id="tingkatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">-- Pilih Tingkatan --</option>
                                        <option value="1" {{ old('tingkatan', $mataPelajaran->tingkatan) == '1' ? 'selected' : '' }}>Kelas 1</option>
                                        <option value="2" {{ old('tingkatan', '2') == '2' ? 'selected' : '' }}>Kelas 2</option>
                                        <option value="3" {{ old('tingkatan', '3') == '3' ? 'selected' : '' }}>Kelas 3</option>
                                        <option value="4" {{ old('tingkatan', '4') == '4' ? 'selected' : '' }}>Kelas 4</option>
                                        <option value="5" {{ old('tingkatan', '5') == '5' ? 'selected' : '' }}>Kelas 5</option>
                                        <option value="6" {{ old('tingkatan', '6') == '6' ? 'selected' : '' }}>Kelas 6</option>
                                        <option value="Umum" {{ old('tingkatan', $mataPelajaran->tingkatan) == 'Umum' ? 'selected' : '' }}>Umum</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('tingkatan')" />
                                </div>
                            </div>
                            
                            {{-- Baris 2: Kategori & Durasi --}}
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select name="kategori" id="kategori" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="Umum" {{ old('kategori', $mataPelajaran->kategori) == 'Umum' ? 'selected' : '' }}>Umum</option>
                                        <option value="Diniyah" {{ old('kategori', $mataPelajaran->kategori) == 'Diniyah' ? 'selected' : '' }}>Diniyah</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('kategori')" />
                                </div>
                                <div>
                                    <label for="duration_jp" class="block text-sm font-medium text-gray-700">Durasi (Jam Pelajaran)</label>
                                    <input type="number" name="duration_jp" id="duration_jp" value="{{ old('duration_jp', $mataPelajaran->duration_jp) }}" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <x-input-error class="mt-2" :messages="$errors->get('duration_jp')" />
                                </div>
                            </div>

                            {{-- [PENYESUAIAN UTAMA] Penjelasan Logika Ruangan --}}
                            <div class="space-y-4 rounded-lg bg-slate-50 p-4 border border-slate-200">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="requires_special_room" name="requires_special_room" type="checkbox" value="1" {{ old('requires_special_room', $mataPelajaran->requires_special_room) ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="requires_special_room" class="font-medium text-gray-900">Butuh Ruang Khusus?</label>
                                    </div>
                                </div>
                                <div class="relative rounded-lg border-l-4 bg-blue-50 p-4 border-blue-400">
                                    <p class="text-sm text-blue-800">
                                        Opsi ini menentukan bagaimana jadwal akan dialokasikan:
                                    </p>
                                    <ul class="mt-2 list-disc list-inside text-sm text-blue-700 space-y-1">
                                        <li>Jika <strong>TIDAK DICENTANG</strong>, pelajaran ini akan dijadwalkan di <strong>Ruangan Induk (Home Room)</strong> yang telah ditetapkan untuk setiap kelas.</li>
                                        <li>Jika <strong>DICENTANG</strong>, pelajaran ini akan dijadwalkan di salah satu <strong>Ruangan Khusus</strong> (misal: Laboratorium) yang tersedia dan akan <strong>MENGABAIKAN</strong> Ruangan Induk kelas.</li>
                                    </ul>
                                </div>
                            </div>
                            
                            {{-- Pilihan Guru Pengampu --}}
                            <div>
                                <label for="teacher_ids" class="block text-sm font-medium text-gray-700">Guru Pengampu</label>
                                <select name="teacher_ids[]" id="teacher_ids" multiple placeholder="Cari dan pilih guru..." autocomplete="off" class="mt-1">
                                    @php
                                        $assignedTeacherIds = old('teacher_ids', $mataPelajaran->teachers->pluck('id')->toArray());
                                    @endphp
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ in_array($teacher->id, $assignedTeacherIds) ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Anda bisa memilih lebih dari satu guru.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('teacher_ids')" />
                            </div>

                        </div>
                    </form>
                    
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                        <div>
                            <form action="{{ route('pengajaran.mata-pelajaran.destroy', $mataPelajaran->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus mata pelajaran ini? Tindakan ini tidak dapat diurungkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-transparent px-4 py-2 text-sm font-semibold text-red-700 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                    Hapus Pelajaran
                                </button>
                            </form>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('pengajaran.mata-pelajaran.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" form="edit-form" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#teacher_ids', {
                plugins: ['remove_button'],
            });
        });
    </script>
</x-app-layout>