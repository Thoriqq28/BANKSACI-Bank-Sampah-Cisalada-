<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Nasabah;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Otomatis membuat record di tabel `nasabah` saat User baru dibuat.
     */
    public function created(User $user): void
    {
        // Jalankan hanya jika user yang baru dibuat memiliki role 'nasabah'
        if ($user->role === 'nasabah') {
            
            // Generate Kode Nasabah otomatis (Format: BS-0001, BS-0002, dst.)
            $lastNasabah = Nasabah::latest('id')->first();
            $nextNumber = $lastNasabah ? ((int) str_replace('BS-', '', $lastNasabah->kode_nasabah)) + 1 : 1;
            $kodeNasabah = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Tangkap input dari request/form jika ada, berikan fallback default jika kosong
            $alamat     = request('alamat') ?? '-';
            $noHp       = request('no_hp') ?? request('telepon') ?? null;
            $rt         = request('rt') ?? null;
            $rw         = request('rw') ?? null;
            $nomorRumah = request('nomor_rumah') ?? null;

            // Buat record baru di tabel `nasabah`
            Nasabah::create([
                'user_id'      => $user->id,
                'kode_nasabah' => $kodeNasabah,
                'nama'         => $user->name,
                'alamat'       => $alamat,
                'no_hp'        => $noHp,
                'rt'           => $rt,
                'rw'           => $rw,
                'nomor_rumah'  => $nomorRumah,
                'saldo'        => 0,
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     * Otomatis memperbarui nama di tabel `nasabah` jika nama di `users` diubah.
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('name')) {
            Nasabah::where('user_id', $user->id)->update([
                'nama' => $user->name,
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     * Otomatis menghapus data nasabah terkait jika User dihapus.
     */
    public function deleted(User $user): void
    {
        Nasabah::where('user_id', $user->id)->delete();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Nasabah::where('user_id', $user->id)->forceDelete();
    }
}