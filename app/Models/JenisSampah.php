<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    use HasFactory;

    protected $table = 'jenis_sampah';
    protected $fillable = ['kategori_id', 'nama', 'harga_per_kg', 'satuan'];

    public function kategori()
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_id');
    }
}
