<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AutoDebit;
use App\Models\Penarikan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessAutoDebit extends Command
{
    /**
     * Nama perintah terminal (disesuaikan dengan routes/console.php)
     */
    protected $signature = 'app:potong-saldo-bulanan';

    /**
     * Deskripsi perintah
     */
    protected $description = 'Memproses pemotongan saldo otomatis untuk iuran bulanan nasabah';

    public function handle()
    {
        $today = Carbon::today();

        // Cari aturan auto debit yang aktif
        $schedules = AutoDebit::with('nasabah')
            ->where('is_active', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('last_executed_at')
                      ->orWhereDate('last_executed_at', '<', $today->copy()->startOfMonth());
            })
            ->get();

        $processedCount = 0;

        foreach ($schedules as $schedule) {
            $nasabah = $schedule->nasabah;

            if (!$nasabah) continue;

            // Cek apakah saldo nasabah mencukupi
            if ($nasabah->saldo >= $schedule->nominal) {
                DB::transaction(function () use ($nasabah, $schedule) {
                    // 1. Potong Saldo Nasabah
                    $nasabah->decrement('saldo', $schedule->nominal);

                    // 2. Catat ke tabel Penarikan / Mutasi
                    Penarikan::create([
                        'nasabah_id' => $nasabah->id,
                        'nominal'    => $schedule->nominal,
                        'keterangan' => '[IURAN BULANAN] ' . $schedule->keterangan,
                        'status'     => 'selesai',
                    ]);

                    // 3. Update waktu eksekusi terakhir
                    $schedule->update([
                        'last_executed_at' => Carbon::now(),
                    ]);
                });

                $processedCount++;
            }
        }

        $this->info("Berhasil memproses iuran bulanan untuk {$processedCount} nasabah.");
    }
}