<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class WaliRegistrationController extends Controller
{
    /**
     * Menampilkan form registrasi untuk wali santri.
     */
    public function create()
    {
        return view('auth.wali-register');
    }

    /**
     * Menangani permintaan registrasi dari wali santri.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kode_registrasi_wali' => ['required', 'string', 'exists:santris,kode_registrasi_wali'],
        ]);

        // Cari santri berdasarkan kode registrasi
        $santri = Santri::where('kode_registrasi_wali', $request->kode_registrasi_wali)->first();

        // Cek apakah santri tersebut sudah memiliki wali yang tertaut
        if ($santri && $santri->wali_id) {
            return back()->withErrors([
                'kode_registrasi_wali' => 'Kode registrasi ini sudah digunakan dan tertaut dengan akun wali lain.',
            ]);
        }

        // Buat user baru untuk wali
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'wali_santri', // Set role secara otomatis
        ]);

        // Tautkan user wali yang baru dibuat ke data santri
        $santri->wali_id = $user->id;
        $santri->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
