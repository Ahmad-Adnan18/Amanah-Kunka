<x-guest-layout>
    <div class="flex min-h-screen bg-white">

        <div class="hidden lg:flex w-1/2 items-center justify-center bg-red-800 p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: url('https://placehold.co/1000x1200/B91C1C/FFFFFF/png?text=Visual+Branding'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-red-700 to-red-900/80"></div>
            <div class="relative z-10 text-center space-y-8">
                <a href="/">
                    <img src="{{ asset('images/logo-kunka.png') }}" alt="Logo Kun Karima" class="mx-auto mb-8 h-24 w-auto drop-shadow-lg">
                </a>
                <h1 class="text-4xl font-bold tracking-tight text-white">
                    Lupa Password?
                </h1>
                <p class="mt-4 text-lg text-red-100 max-w-lg mx-auto">
                    Tidak masalah. Cukup masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                </p>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 items-center justify-center bg-slate-50 p-8 sm:p-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden text-center mb-10">
                    <a href="/">
                        <img src="{{ asset('images/logo-kunka.png') }}" alt="Logo Kun Karima" class="mx-auto mb-8 h-24 w-auto drop-shadow-lg">
                    </a>
                </div>

                <div class="text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Reset Password</h2>
                    <p class="mt-2 text-gray-600">
                        Masukkan email Anda untuk menerima tautan reset password.
                    </p>
                </div>

                <x-auth-session-status class="mt-6 mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" /><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>
                            </div>
                            <x-text-input id="email" class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-red-600 focus:ring-2 focus:ring-red-600 sm:text-sm" type="email" name="email" :value="old('email')" required autofocus />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <a href="{{ route('login') }}" class="font-medium text-sm text-red-700 hover:text-red-600">
                            Kembali ke login
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-800 px-4 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-700 transition-colors">
                            {{ __('Kirim Tautan Reset') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
