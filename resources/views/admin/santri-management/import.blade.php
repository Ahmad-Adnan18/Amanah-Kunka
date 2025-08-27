<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <!-- Header Halaman -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Import Data Santri</h1>
                    <p class="mt-1 text-slate-600">Gunakan fitur ini untuk mengunggah data santri baru dalam jumlah besar. Pastikan file Anda sesuai dengan template.</p>
                </div>

                <!-- Notifikasi Error Validasi -->
                @if (session('import_errors'))
                <div class="rounded-2xl bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat error pada file yang Anda unggah:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    @foreach (session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Form Upload -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <form action="{{ route('admin.santri-management.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-6 space-y-6">
                            <!-- Langkah 1: Download Template -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Langkah 1: Download Template</h3>
                                <p class="mt-1 text-sm text-slate-500">Isi data sesuai dengan kolom yang ada di template. Pastikan nama kelas dan rayon sudah ada di sistem.</p>
                                <div class="mt-3">
                                    <a href="{{ asset('templates/template_import_santri.xlsx') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.7a.75.75 0 00-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Download Template Import Santri.xlsx</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Langkah 2: Upload File -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Langkah 2: Unggah File</h3>
                                <p class="mt-1 text-sm text-slate-500">Pilih file Excel yang sudah Anda isi.</p>
                                <div class="mt-3">
                                    <label for="file" class="sr-only">Pilih file</label>
                                    <input type="file" name="file" id="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer" required>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Aksi Form -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-4">
                            <a href="{{ route('admin.santri-management.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
