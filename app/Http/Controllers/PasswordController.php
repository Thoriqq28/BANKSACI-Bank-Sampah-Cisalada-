<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Nasabah; // <-- Tambahan Model Nasabah

class PasswordController extends Controller
{
    // ==========================================
    // 👑 SEKTOR ADMIN & PETUGAS
    // ==========================================
    public function showGantiPasswordAdmin()
    {
        return view('admin.ganti-password');
    }

    public function updatePasswordAdmin(Request $request)
    {
        $this->validatePassword($request);

        $user = Auth::user();
        if (!Hash::check($request->password_sekarang, $user->password)) {
            return back()->withErrors(['password_sekarang' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return redirect('/admin/ganti-password')->with('success', 'Password staf berhasil diperbarui!');
    }

    // ==========================================
    // 👤 SEKTOR NASABAH / USER
    // ==========================================
    public function showGantiPasswordUser()
    {
        $user = Auth::user();
        
        // Ambil data nasabah berdasarkan user yang sedang login
        // (Pastikan nama kolom 'user_id' sesuai dengan database kamu)
        $nasabah = Nasabah::where('user_id', $user->id)->first();

        // Mengirimkan variabel $nasabah ke halaman blade
        return view('user.ganti-password', compact('nasabah', 'user'));
    }

    public function updatePasswordUser(Request $request)
    {
        $this->validatePassword($request);

        $user = Auth::user();
        if (!Hash::check($request->password_sekarang, $user->password)) {
            return back()->withErrors(['password_sekarang' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return redirect('/user/ganti-password')->with('success', 'Password akun Anda berhasil diperbarui!');
    }

    // Helper Validasi reusable
    private function validatePassword(Request $request)
    {
        $request->validate([
            'password_sekarang' => 'required',
            'password_baru' => 'required|string|min:8|confirmed',
        ], [
            'password_sekarang.required' => 'Password saat ini wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal harus 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);
    }
}