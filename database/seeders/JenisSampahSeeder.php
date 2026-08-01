<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSampahSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Sampah Dulu jika kosong
        $kategoriId = DB::table('kategori_sampah')->insertGetId([
            'nama_kategori' => 'Anorganik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Isi Data Jenis Sampah (Sertakan ID 7 agar sesuai dengan yang ada di web Anda)
        DB::table('jenis_sampah')->insert([
            [
                'id' => 1,
                'kategori_id' => $kategoriId,
                'nama' => 'Kertas / Kardus',
                'harga_beli' => 1500,
                'harga_per_kg' => 2000,
                'satuan' => 'Kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7, // ID 7 disesuaikan dengan permintaan input di form Anda
                'kategori_id' => $kategoriId,
                'nama' => 'Botol Plastik',
                'harga_beli' => 2500,
                'harga_per_kg' => 3200,
                'satuan' => 'Kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}