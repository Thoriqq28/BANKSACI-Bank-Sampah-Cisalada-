<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoNasabah extends Model
{
    use HasFactory;

    protected $table = 'saldo_nasabah';
    protected $fillable = [
    'nasabah_id',
    'saldo',
];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}
