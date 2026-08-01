<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- 1. Import Trait Notifiable

class Nasabah extends Model
{
    use HasFactory, Notifiable; // <-- 2. Gunakan Trait Notifiable di sini

    // Menentukan nama tabel secara eksplisit
    protected $table = 'nasabah';

    // Kolom fillable yang aman dan lengkap
    protected $fillable = [
        'user_id', 
        'kode_nasabah', 
        'nama', 
        'alamat', 
        'no_hp', 
        'rt', 
        'rw', 
        'nomor_rumah',
        'saldo'
    ];

    /**
     * Relasi ke tabel saldo_nasabah
     */
    public function saldoNasabah()
    {
        return $this->hasOne(SaldoNasabah::class, 'nasabah_id');
    }

    /**
     * Hubungan ke data user login
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hubungan ke riwayat setoran
     */
    public function setoran()
    {
        return $this->hasMany(Setoran::class, 'nasabah_id');
    }

    /**
     * Alias dengan urutan terbaru (Order By Descending)
     */
    public function setorans()
    {
        return $this->hasMany(Setoran::class, 'nasabah_id')->latest();
    }

    /**
     * Hubungan ke riwayat penarikan
     */
    public function penarikan()
    {
        return $this->hasMany(Penarikan::class, 'nasabah_id');
    }

    /**
     * Alias dengan urutan terbaru (Order By Descending)
     */
    public function penarikans()
    {
        return $this->hasMany(Penarikan::class, 'nasabah_id')->latest();
    }

    /**
     * Hubungan ke jadwal auto debit / pemotongan rutin
     */
    public function autoDebits()
    {
        return $this->hasMany(AutoDebit::class, 'nasabah_id');
    }
}