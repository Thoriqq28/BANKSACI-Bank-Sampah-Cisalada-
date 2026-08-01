<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Nasabah;
use App\Models\SaldoNasabah;
use App\Models\Penarikan;
use App\Models\AutoDebit;
use App\Notifications\PeringatanPotonganSaldoNotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PotongSaldoBulanan extends Command
{
    protected $signature = 'app:potong-saldo-bulanan';
    protected $description = 'Memproses pemotongan saldo otomatis (iuran / administrasi bulanan) nasabah';

    public function handle()
    {
        $today = Carbon::today();
        $processedCount = 0;

        // 1. Cek apakah ada aturan khusus di tabel auto_debits yang aktif
        $autoDebits = AutoDebit::with('nasabah.saldoNasabah')
            ->where('is_active', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('last_executed_at')
                      ->orWhereDate('last_executed_at', '<', $today->copy()->startOfMonth());
            })
            ->get();

        if ($autoDebits->count() > 0) {
            foreach ($autoDebits as $debit) {
                $nasabah = $debit->nasabah;
                $saldoRecord = $nasabah?->saldoNasabah;

                if ($saldoRecord && $saldoRecord->saldo >= $debit->nominal) {
                    DB::transaction(function () use ($nasabah, $saldoRecord, $debit) {
                        // Potong saldo di tabel saldo_nasabah
                        $saldoRecord->decrement('saldo', $debit->nominal);

                        // Potong saldo di tabel nasabah (jika ada simpanan ganda)
                        if (isset($nasabah->saldo)) {
                            $nasabah->decrement('saldo', $debit->nominal);
                        }

                        // Catat ke riwayat Penarikan
                        Penarikan::create([
                            'nasabah_id' => $nasabah->id,
                            'nominal'    => $debit->nominal,
                            'keterangan' => '[AUTO DEBIT] ' . $debit->keterangan,
                            'status'     => 'selesai',
                        ]);

                        $debit->update(['last_executed_at' => Carbon::now()]);

                        // 🔔 KIRIM NOTIFIKASI KE NASABAH
                        if ($nasabah) {
                            $nasabah->notify(new PeringatanPotonganSaldoNotification(
                                $debit->nominal,
                                Carbon::now()->isoFormat('D MMMM YYYY'),
                                $debit->keterangan ?? 'Biaya Administrasi Bulanan'
                            ));
                        }
                    });
                    $processedCount++;
                }
            }
        } else {
            // 2. Fallback: Jika tabel auto_debits belum terisi, jalankan logika dasar (Potong Rp 15.000)
            $saldoList = SaldoNasabah::where('saldo', '>=', 15000)->get();

            foreach ($saldoList as $saldoRecord) {
                DB::transaction(function () use ($saldoRecord) {
                    $saldoRecord->decrement('saldo', 15000);

                    // Catat riwayat penarikan
                    Penarikan::create([
                        'nasabah_id' => $saldoRecord->nasabah_id,
                        'nominal'    => 15000,
                        'keterangan' => '[AUTO DEBIT] Biaya Administrasi Bulanan',
                        'status'     => 'selesai',
                    ]);

                    // 🔔 KIRIM NOTIFIKASI KE NASABAH
                    $nasabah = Nasabah::find($saldoRecord->nasabah_id);
                    if ($nasabah) {
                        $nasabah->notify(new PeringatanPotonganSaldoNotification(
                            15000,
                            Carbon::now()->isoFormat('D MMMM YYYY'),
                            'Biaya Administrasi Bulanan'
                        ));
                    }
                });
                $processedCount++;
            }
        }

        $this->info("Pemotongan saldo bulanan sukses diproses untuk {$processedCount} nasabah.");
        return Command::SUCCESS;
    }
}