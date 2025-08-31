<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Generate Jadwal Pelajaran</h1>
                    <p class="mt-1 text-slate-600">Sistem akan secara otomatis menyusun jadwal berdasarkan semua aturan yang telah ditetapkan.</p>
                </div>

                <!-- Panel Notifikasi -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('warning'))
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-md" role="alert">{{ session('warning') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md" role="alert">{{ session('error') }}</div>
                @endif

                <!-- [BARU] Panel untuk Mapel yang Gagal Ditempatkan -->
                @if (session('unplaced_subjects'))
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                        <h3 class="text-lg font-bold text-yellow-800">Mata Pelajaran Gagal Ditempatkan:</h3>
                        <ul class="mt-2 list-disc list-inside text-yellow-700">
                            @foreach (session('unplaced_subjects') as $subject)
                                <li>{{ $subject }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 text-center">
                    <p class="text-slate-600 mb-4">Pastikan semua data master (kelas, guru, mapel, ruangan) dan aturan sudah benar sebelum memulai.</p>
                    <form action="{{ route('admin.generator.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-red-600">
                            Mulai Generate Jadwal
                        </button>
                    </form>
                </div>

                <!-- [BARU] Panel untuk Log Debugging -->
                @if (session('log'))
                    <div class="bg-gray-800 text-white rounded-2xl shadow-lg border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-slate-200 mb-4">Log Proses Generator:</h3>
                        <div class="font-mono text-sm overflow-x-auto h-64 bg-gray-900 p-4 rounded-lg">
                            @foreach (session('log') as $line)
                                <div><span class="text-gray-500 mr-2">></span>{{ $line }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

