 <x-app-layout>
     <div class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8">
         <div class="max-w-3xl mx-auto">
             <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                 <div class="p-6 sm:p-8 border-b border-slate-200">
                     {{-- Style: Warna judul disesuaikan --}}
                     <h1 class="text-2xl font-bold tracking-tight text-red-700">Tambah Santri Baru ke Kelas: {{ $kelas->nama_kelas }}</h1>
                     <p class="mt-1 text-slate-600">Isi formulir di bawah ini untuk mendaftarkan santri baru.</p>
                 </div>
                 <div class="p-6 sm:p-8">
                     <form action="{{ route('pengajaran.santris.store') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                         <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                         <div class="space-y-6">
                             <!-- NIS -->
                             <div>
                                 <x-input-label for="nis" :value="__('NIS (Opsional)')" />
                                 {{-- Style: Gaya fokus input disesuaikan --}}
                                 {{-- PERUBAHAN: Atribut 'required' dihapus agar tidak wajib diisi. --}}
                                 {{-- Untuk mewajibkan kembali, tambahkan 'required' di bawah ini. --}}
                                 <x-text-input id="nis" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nis" :value="old('nis')" />
                                 <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                             </div>

                             <!-- Nama -->
                             <div>
                                 <x-input-label for="nama" :value="__('Nama Lengkap')" />
                                 {{-- PERUBAHAN: Atribut 'autofocus' dipindahkan ke sini karena ini field pertama yang wajib diisi. --}}
                                 <x-text-input id="nama" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="nama" :value="old('nama')" required autofocus />
                                 <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                             </div>

                             <!-- Jenis Kelamin (TAMBAHAN BARU) -->
                             <div>
                                 <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                                 <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                     <option value="Putra" @selected(old('jenis_kelamin')=='Putra' )>Putra</option>
                                     <option value="Putri" @selected(old('jenis_kelamin')=='Putri' )>Putri</option>
                                 </select>
                             </div>

                             <!-- Rayon -->
                             <div>
                                 <x-input-label for="rayon" :value="__('Rayon (Opsional)')" />
                                 {{-- PERUBAHAN: Atribut 'required' dihapus agar tidak wajib diisi. --}}
                                 {{-- Untuk mewajibkan kembali, tambahkan 'required' di bawah ini. --}}
                                 <x-text-input id="rayon" class="block mt-1 w-full focus:ring-red-600 focus:border-red-600" type="text" name="rayon" :value="old('rayon')" />
                                 <x-input-error :messages="$errors->get('rayon')" class="mt-2" />
                             </div>

                             <!-- Foto -->
                             <div>
                                 <x-input-label for="foto" :value="__('Foto (Opsional)')" />
                                 {{-- Style: Gaya input file disesuaikan --}}
                                 <input id="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-colors duration-200" type="file" name="foto">
                                 <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                             </div>

                             <div class="flex items-center justify-end gap-4 pt-4">
                                 {{-- Style: Tombol disesuaikan --}}
                                 <a href="{{ route('pengajaran.santris.index', $kelas) }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</a>
                                 <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                     {{ __('Simpan') }}
                                 </button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </x-app-layout>
