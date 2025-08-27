    <x-app-layout>
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Header -->
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-red-700">Portal Wali Santri</h1>
                    <p class="mt-1 text-slate-600">Selamat datang, {{ Auth::user()->name }}. Berikut adalah ringkasan perkembangan ananda.</p>
                </div>

                <!-- Profil Santri -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="flex-shrink-0">
                            <img class="h-24 w-24 rounded-full object-cover ring-4 ring-red-100" src="{{ $santri->foto ? Storage::url($santri->foto) : 'https://ui-avatars.com/api/?name='.urlencode($santri->nama).'&background=FBBF24&color=fff&size=128' }}" alt="Foto Santri">
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $santri->nama }}</h2>
                            <p class="mt-1 text-slate-600">
                                <span class="font-semibold">NIS:</span> {{ $santri->nis }} | 
                                <span class="font-semibold">Kelas:</span> {{ $santri->kelas->nama_kelas ?? 'N/A' }} | 
                                <span class="font-semibold">Rayon:</span> {{ $santri->rayon }}
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('santri.profil.show', $santri) }}" class="text-sm font-semibold text-red-600 hover:text-red-800">Lihat Profil Lengkap & Rapor &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Izin & Pelanggaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Riwayat Perizinan Terbaru -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Perizinan Terbaru</h3>
                        </div>
                        <ul class="divide-y divide-slate-200">
                            @forelse ($santri->perizinans->take(5) as $izin)
                                <li class="p-4">
                                    <p class="font-semibold text-slate-800">{{ $izin->jenis_izin }}</p>
                                    <p class="text-sm text-slate-500">{{ $izin->tanggal_mulai->format('d M Y') }} - {{ $izin->status }}</p>
                                </li>
                            @empty
                                <li class="p-6 text-center text-slate-500">Tidak ada riwayat perizinan.</li>
                            @endforelse
                        </ul>
                    </div>
                    <!-- Riwayat Pelanggaran Terbaru -->
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Pelanggaran Terbaru</h3>
                        </div>
                        <ul class="divide-y divide-slate-200">
                            @forelse ($santri->pelanggarans->take(5) as $pelanggaran)
                                <li class="p-4">
                                    <p class="font-semibold text-slate-800">{{ $pelanggaran->jenis_pelanggaran }}</p>
                                    <p class="text-sm text-slate-500">{{ $pelanggaran->tanggal_kejadian->format('d M Y') }}</p>
                                </li>
                            @empty
                                <li class="p-6 text-center text-slate-500">Tidak ada riwayat pelanggaran.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    