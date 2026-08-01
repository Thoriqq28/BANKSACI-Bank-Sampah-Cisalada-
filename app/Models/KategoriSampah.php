<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSampah extends Model
{
    use HasFactory;

    protected $table = 'kategori_sampah';

    protected $fillable = [
        'nama',
        'nama_kategori',
        'jenis_sampah',
        'harga_per_kg',
    ];

    public function jenisSampah()
    {
        return $this->hasMany(JenisSampah::class, 'kategori_sampah_id');
    }
}