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

        // 4. Seed Kategori Sampah
        DB::table('kategori_sampah')->updateOrInsert(
            ['id' => 1],
            [
                'nama' => 'Anorganik',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Seed Lengkap Jenis Sampah
        $jenisSampahData = [
            ['id' => 1, 'kategori_id' => 1, 'nama' => 'Besi', 'harga_beli' => 3500, 'harga_per_kg' => 4500, 'satuan' => 'Kg'],
            ['id' => 2, 'kategori_id' => 1, 'nama' => 'Kaleng', 'harga_beli' => 1500, 'harga_per_kg' => 2000, 'satuan' => 'Kg'],
            ['id' => 3, 'kategori_id' => 1, 'nama' => 'Buku', 'harga_beli' => 800, 'harga_per_kg' => 1200, 'satuan' => 'Kg'],
            ['id' => 4, 'kategori_id' => 1, 'nama' => 'Kardus', 'harga_beli' => 1200, 'harga_per_kg' => 1800, 'satuan' => 'Kg'],
            ['id' => 5, 'kategori_id' => 1, 'nama' => 'Kuningan', 'harga_beli' => 80000, 'harga_per_kg' => 100000, 'satuan' => 'Kg'],
            ['id' => 6, 'kategori_id' => 1, 'nama' => 'Tembaga', 'harga_beli' => 150000, 'harga_per_kg' => 180000, 'satuan' => 'Kg'],
            ['id' => 7, 'kategori_id' => 1, 'nama' => 'Aqua Gelas dan Semacamnya', 'harga_beli' => 2200, 'harga_per_kg' => 3000, 'satuan' => 'Kg'],
            ['id' => 8, 'kategori_id' => 1, 'nama' => 'Botol Plastik', 'harga_beli' => 2500, 'harga_per_kg' => 3200, 'satuan' => 'Kg'],
            ['id' => 9, 'kategori_id' => 1, 'nama' => 'Ember', 'harga_beli' => 1800, 'harga_per_kg' => 2500, 'satuan' => 'Kg'],
        ];

        foreach ($jenisSampahData as $data) {
            DB::table('jenis_sampah')->updateOrInsert(
                ['id' => $data['id']],
                array_merge($data, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}