<x-app-layout>
    <div class="bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Akun Pengguna</h1>
                            <p class="mt-1 text-slate-600">Kelola semua akun yang terdaftar di sistem.</p>
                        </div>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                            <span>Tambah Akun</span>
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-2xl shadow-sm flex justify-between items-center" role="alert">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                        <button @click="show = false" class="text-green-600 hover:text-green-800">&times;</button>
                    </div>
                @endif

                <div class="space-y-4 md:hidden">
                    @forelse ($users as $user)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-4">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>
                                    <div class="mt-3">
                                        @php
                                            $roleClass = match($user->role) {
                                                'admin' => 'bg-red-50 text-red-700 ring-red-600/20',
                                                'wali_santri' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                'pengajaran' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                                'keasramaan' => 'bg-green-50 text-green-700 ring-green-600/20',
                                                default => 'bg-slate-50 text-slate-600 ring-slate-500/20',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $roleClass }}">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2 flex-shrink-0">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-slate-600 hover:text-red-700 px-2 py-1">Edit</a>
                                    @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900 px-2 py-1">Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center text-slate-500">
                            <p>Tidak ada data user.</p>
                        </div>
                    @endforelse
                </div>

                <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-slate-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Nama</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                                <th class="relative px-6 py-3.5"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-slate-500">
                                        @php
                                            $roleClass = match($user->role) {
                                                'admin' => 'bg-red-50 text-red-700 ring-red-600/20',
                                                'wali_santri' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                'pengajaran' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                                'keasramaan' => 'bg-green-50 text-green-700 ring-green-600/20',
                                                default => 'bg-slate-50 text-slate-600 ring-slate-500/20',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $roleClass }}">
                                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-4">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-slate-600 hover:text-red-700">Edit</a>
                                        @if(Auth::id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">Tidak ada data user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>