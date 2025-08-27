<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Kelola Kelas</h1>
                    <p class="mt-1 text-slate-600">Edit nama kelas dan atur penanggung jawab untuk: <span class="font-semibold text-red-700">{{ $kela->nama_kelas }}</span></p>
                </div>

                <!-- Panel Utama -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <!-- Form Edit Nama Kelas -->
                    <form action="{{ route('pengajaran.kelas.update', $kela) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-6 border-b border-slate-200">
                             <h2 class="text-lg font-semibold text-gray-900">Edit Nama Kelas</h2>
                            <div class="mt-4 flex flex-col sm:flex-row items-end gap-4">
                                <div class="flex-grow w-full">
                                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                                    <input id="nama_kelas" name="nama_kelas" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required />
                                </div>
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 flex-shrink-0">
                                    Update Nama
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Panel Penunjukan Jabatan -->
                    <div>
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-gray-900">Penanggung Jawab Kelas</h2>
                            <p class="mt-1 text-slate-600">Tunjuk ustadz/ustadzah sebagai penanggung jawab untuk kelas ini.</p>
                        </div>

                        <!-- Daftar Penanggung Jawab Saat Ini -->
                        <div class="divide-y divide-slate-200">
                            @forelse ($penanggungJawab as $pj)
                                <div class="p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $pj->user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $pj->jabatan->nama_jabatan }} - TA: {{ $pj->tahun_ajaran }}</p>
                                    </div>
                                    <form action="{{ route('pengajaran.kelas.remove_jabatan', $pj) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jabatan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <p class="p-6 text-center text-slate-500">Belum ada penanggung jawab yang ditunjuk.</p>
                            @endforelse
                        </div>

                        <!-- Form Tambah Penanggung Jawab -->
                        <div class="bg-slate-50 p-6 border-t border-slate-200">
                            <h3 class="font-semibold text-gray-800 mb-4">Tambah Penanggung Jawab Baru</h3>
                            <form action="{{ route('pengajaran.kelas.assign_jabatan', $kela) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Pilih Ustadz/Ustadzah</label>
                                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="jabatan_id" class="block text-sm font-medium text-gray-700">Sebagai</label>
                                    <select name="jabatan_id" id="jabatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                                    <input id="tahun_ajaran" name="tahun_ajaran" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" value="{{ date('Y').'/'.(date('Y')+1) }}" required />
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                        Tunjuk
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                 <!-- Tombol Kembali -->
                <div class="flex justify-start">
                    <a href="{{ route('pengajaran.kelas.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Kembali ke Manajemen Kelas
                    </a>
                </div>
            </div>
        </div>
    </x-app-layout>
