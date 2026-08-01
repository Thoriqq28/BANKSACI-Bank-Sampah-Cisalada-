<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Nasabah;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Login untuk Admin (PENTING untuk Login Admin)
        User::updateOrCreate(
            ['email' => 'admin@banksampah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // Password login Admin
                'role' => 'admin',
            ]
        );

        // 2. Buat User Login untuk Warga / Nasabah
        $user = User::updateOrCreate(
            ['email' => 'warga@gmail.com'],
            [
                'name' => 'Warga Testing',
                'password' => Hash::make('warga123'), // Password login Warga
                'role' => 'nasabah',
            ]
        );

        // 3. Daftarkan/Hubungkan ke tabel Nasabah
        Nasabah::updateOrCreate(
            ['user_id' => $user->id],
            [
                'kode_nasabah' => 'BS-0008',
                'nama' => 'Warga Testing',
                'alamat' => 'Jl. Sukamaju No. 12',
                'rt' => '00',
                'rw' => '00',
                'no_hp' => '081234567890',
            ]
        );
    }
}