<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'setoran';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'nasabah_id',
        'sampah_id',
        'user_id',
        'tanggal',
        'total_berat',
        'total_harga',
    ];

    /**
     * Relasi ke model Nasabah (Pemilik setoran).
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    /**
     * Relasi ke model User (Petugas/Admin yang menginput).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Sampah / KategoriSampah.
     * Menggantikan 'details' karena data sampah disimpan langsung di tabel setoran.
     * 
     * Catatan: Jika nama model sampah kamu adalah 'Sampah', 
     * ubah KategoriSampah::class menjadi Sampah::class.
     */
    public function sampah()
    {
        return $this->belongsTo(KategoriSampah::class, 'sampah_id');
    }
}