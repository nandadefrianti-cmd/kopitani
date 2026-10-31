<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\KopiTaniNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Menampilkan halaman registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // Memproses registrasi pengguna
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:petani,kedai'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Role ditetapkan secara terpisah agar pengguna tidak bisa
        // menaikkan hak aksesnya melalui formulir registrasi.
        $user->role = $validated['role'];
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard')->with('success', 'Registrasi berhasil!');
    }

    // Mengajukan verifikasi akun Petani
    public function submitVerification()
    {
        $user = Auth::user();

        abort_unless($user->role === 'petani', 403);

        if ($user->verification_status === 'verified') {
            return back()->with('success', 'Akun kamu sudah terverifikasi.');
        }

        if ($user->verification_status === 'pending') {
            return back()->with('success', 'Pengajuan verifikasi kamu sedang diproses.');
        }

        $user->update([
            'verification_status' => 'pending',
            'verification_rejection_reason' => null,
        ]);

        // Mengirim notifikasi kepada seluruh Admin
        User::where('role', 'admin')->each(function ($admin) use ($user) {
            $admin->notify(new KopiTaniNotification(
                'Pengajuan Verifikasi Akun',
                $user->name . ' mengajukan verifikasi akun Petani.',
                url('/admin/verifications'),
                'verification'
            ));
        });

        return back()->with('success', 'Pengajuan verifikasi akun berhasil dikirim.');
    }

    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('success', 'Berhasil masuk!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Kamu berhasil keluar.');
    }
}
