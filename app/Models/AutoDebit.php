<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoDebit extends Model
{
    use HasFactory;

    protected $table = 'auto_debits';

    protected $fillable = [
        'nasabah_id',
        'keterangan',
        'nominal',
        'tanggal_eksekusi',
        'is_active',
        'last_executed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_executed_at' => 'datetime',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}