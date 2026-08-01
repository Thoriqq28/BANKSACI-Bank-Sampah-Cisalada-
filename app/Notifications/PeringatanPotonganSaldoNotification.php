<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PeringatanPotonganSaldoNotification extends Notification
{
    use Queueable;

    protected $jumlah;
    protected $tanggal;
    protected $namaLayanan;

    public function __construct($jumlah, $tanggal, $namaLayanan)
    {
        $this->jumlah      = $jumlah;
        $this->tanggal      = $tanggal;
        $this->namaLayanan  = $namaLayanan;
    }

    public function via($notifiable)
    {
        return ['database']; // Tersimpan di tabel notifications
    }

    public function toArray($notifiable)
    {
        return [
            'title'        => 'Peringatan Pemotongan Saldo ⚠️',
            'jumlah'       => $this->jumlah,
            'tanggal'      => $this->tanggal,
            'nama_layanan' => $this->namaLayanan,
            'message'      => "Saldo Anda telah dipotong sebesar Rp " . number_format($this->jumlah, 0, ',', '.') . " pada {$this->tanggal} untuk pembayaran {$this->namaLayanan}.",
            'url'          => url('/nasabah/dashboard'), // Menggunakan URL relatif agar lebih aman
        ];
    }
}