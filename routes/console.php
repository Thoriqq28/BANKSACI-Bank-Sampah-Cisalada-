<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes & Task Scheduling
|--------------------------------------------------------------------------
*/

// Perintah bawaan Laravel untuk menampilkan kutipan inspiratif
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 🟢 SISTEM OTOMATIS JADWAL IURAN BULANAN NASABAH
// Menjalankan perintah pemotongan saldo secara otomatis setiap tanggal 1 jam 00:00 dini hari
Schedule::command('app:potong-saldo-bulanan')->monthlyOn(1, '00:00');