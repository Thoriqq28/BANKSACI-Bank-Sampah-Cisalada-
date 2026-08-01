<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Nasabah;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Login untuk Admin
        User::updateOrCreate(
            ['email' => 'admin@banksampah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Buat User Login untuk Warga / Nasabah
        $user = User::updateOrCreate(
            ['email' => 'warga@gmail.com'],
            [
                'name' => 'Warga Testing',
                'password' => Hash::make('warga123'),
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

        // 4. Seed Kategori Sampah (Diubah: nama_kategori -> nama)
        DB::table('kategori_sampah')->updateOrInsert(
            ['id' => 1],
            [
                'nama' => 'Anorganik',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Seed Jenis Sampah
        $jenisSampahData = [
            [
                'id' => 1,
                'kategori_id' => 1,
                'nama' => 'Kertas / Kardus',
                'harga_beli' => 1500,
                'harga_per_kg' => 2000,
                'satuan' => 'Kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7, // Disesuaikan agar transaksi ID 7 tidak lagi error
                'kategori_id' => 1,
                'nama' => 'Botol Plastik',
                'harga_beli' => 2500,
                'harga_per_kg' => 3200,
                'satuan' => 'Kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($jenisSampahData as $data) {
            DB::table('jenis_sampah')->updateOrInsert(
                ['id' => $data['id']],
                $data
            );
        }
    }
}