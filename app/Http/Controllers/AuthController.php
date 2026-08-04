<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        // Jika user sudah login, langsung arahkan ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi login.
     */
    /**
     * Memproses autentikasi login.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari User Berdasarkan Email
        $user = User::where('email', $request->email)->first();

        // Pengecekan A: Email Tidak Ditemukan
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email ini belum terdaftar di sistem kami.']);
        }

        // Pengecekan B: Password Tidak Cocok
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Kata sandi yang Anda masukkan salah.']);
        }

        // 3. Eksekusi Otentikasi Login
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Redirect aman: Bersihkan POST request & alihkan berdasarkan role
            return $this->redirectByUserRole(Auth::user());
        }

        // Fallback jika attempt gagal
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Gagal melakukan otentikasi login. Silakan coba lagi.']);
    }

    /**
     * Menentukan halaman tujuan berdasarkan Role Pengguna.
     */
    protected function redirectByUserRole($user)
{
    $role = strtolower(trim($user->role ?? ''));

    // Menggunakan redirect()->to() dengan status HTTP 303 (See Other)
    // Status 303 MEMAKSA browser mengubah POST menjadi GET
    switch ($role) {
        case 'admin':
            return redirect()->to(route('dashboard.admin'), 303);

        case 'petugas':
            return redirect()->to(route('dashboard.petugas'), 303);

        case 'nasabah':
        case 'user':
        case 'warga':
        default:
            return redirect()->to(route('dashboard.user'), 303);
    }
}
    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi user baru (Nasabah).
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Wajib di-hash bcrypt
            'role'     => 'nasabah', // Default role untuk pendaftar baru
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.user')->with('success', 'Pendaftaran berhasil! Selamat datang.');
    }

    /**
     * Memproses Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}