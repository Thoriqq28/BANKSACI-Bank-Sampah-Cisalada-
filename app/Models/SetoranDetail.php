<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use App\Models\JenisSampah; // Atau KategoriSampah (sesuaikan nama model)
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades::DB;
use Illuminate\Support\Facades::Auth;

class SetoranController extends Controller
{
    /**
     * Menampilkan form input setoran sampah baru.
     */
    public function create()
    {
        $nasabahs = Nasabah::all();
        // Sesuaikan jika model kamu JenisSampah atau KategoriSampah
        $jenisSampahs = JenisSampah::all(); 

        return view('setoran.create', compact('nasabahs', 'jenisSampahs'));
    }

    /**
     * Menyimpan transaksi setoran ke database (Single Table).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'nasabah_id' => 'required|exists:nasabah,id',
            'sampah_id'  => 'required|exists:jenis_sampah,id', // Sesuai name="sampah_id" di View
            'berat'      => 'required|numeric|min:0.1',
        ]);

        // Gunakan DB Transaction agar eksekusi data aman
        DB::beginTransaction();

        try {
            // 2. Ambil Data Jenis Sampah untuk Hitung Total Harga
            $sampah = JenisSampah::findOrFail($request->sampah_id);
            
            // Mengambil harga dari kolom yang tersedia
            $hargaPerKg = $sampah->harga ?? $sampah->harga_per_kg ?? 0;
            $totalHarga = $request->berat * $hargaPerKg;

            // 3. Simpan Langsung ke Tabel 'setoran' (Sesuai $fillable model Setoran kamu)
            $setoran = Setoran::create([
                'nasabah_id'  => $request->nasabah_id,
                'sampah_id'   => $request->sampah_id,
                'user_id'     => Auth::id() ?? 1, // Menyimpan ID Petugas/Admin yang login
                'tanggal'     => now(),
                'total_berat' => $request->berat,
                'total_harga' => $totalHarga,
            ]);

            // 4. Tambahkan Saldo ke Nasabah (Opsional - Jika ada kolom 'saldo' di tabel nasabah)
            if ($setoran->nasabah) {
                $setoran->nasabah->increment('saldo', $totalHarga);
            }

            DB::commit();

            return redirect()->route('setoran.index')
                             ->with('success', 'Transaksi setoran berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}