<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\SaldoNasabah;
use App\Models\Setoran;
use App\Models\Penarikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// IMPORT UNTUK EXCEL
use App\Exports\NasabahExport;
use Maatwebsite\Excel\Facades\Excel;

class NasabahController extends Controller
{
    /**
     * Menampilkan daftar nasabah dengan Sinkronisasi Saldo Real-Time
     */
    public function index()
    {
        // Mengambil semua data nasabah
        $nasabahs = Nasabah::orderBy('created_at', 'desc')->get(); 
        
        // AUTO-SYNC REAL-TIME:
        // Memastikan nilai kolom `saldo` di database 100% akurat sebelum dikirim ke view
        foreach ($nasabahs as $nasabah) {
            // 1. Hitung total setoran
            $totalSetoran = Setoran::where('nasabah_id', $nasabah->id)->sum('total_harga');

            // 2. Hitung total penarikan yang statusnya HANYA 'selesai'
            $totalPenarikanSelesai = Penarikan::where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['selesai'])
                ->sum('jumlah');

            // 3. Hitung saldo riil (Pending & Cancel DIABAIKAN)
            $saldoRealtime = max(0, $totalSetoran - $totalPenarikanSelesai);

            // 4. Jika nilai di DB berbeda, update DB secara otomatis
            if ($nasabah->saldo != $saldoRealtime) {
                $nasabah->saldo = $saldoRealtime;
                $nasabah->save();
            }

            // Juga update ke tabel SaldoNasabah jika terhubung
            SaldoNasabah::updateOrCreate(
                ['nasabah_id' => $nasabah->id],
                ['saldo' => $saldoRealtime]
            );
        }

        // Diarahkan ke view nasabah/index.blade.php
        return view('nasabah.index', compact('nasabahs'));
    }

    /**
     * Fitur Export Excel via Controller
     */
    public function exportExcel()
    {
        $fileName = 'Laporan_Nasabah_BankSampah_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new NasabahExport, $fileName);
    }

    /**
     * Menampilkan detail informasi nasabah beserta kalkulasi saldo
     */
    public function show($id)
    {
        // 1. Ambil data nasabah
        $nasabah = Nasabah::findOrFail($id);

        // 2. Hitung total setoran nasabah ini
        $totalSetoran = DB::table('setorans')
            ->where('nasabah_id', $nasabah->id)
            ->orWhere('user_id', $nasabah->user_id ?? $nasabah->id)
            ->sum('total_harga');

        // 3. Hitung total penarikan saldo yang SUDAH SELESAI
        $totalPenarikanSelesai = 0;
        if (Schema::hasTable('penarikans')) {
            $totalPenarikanSelesai = DB::table('penarikans')
                ->where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['selesai'])
                ->sum('jumlah') ?? 0;
        }

        // 4. Kalkulasi Saldo Real-Time
        $saldoSaatIni = max(0, $totalSetoran - $totalPenarikanSelesai);

        // Update nilai saldo nasabah jika ada perbedaan
        if ($nasabah->saldo != $saldoSaatIni) {
            $nasabah->saldo = $saldoSaatIni;
            $nasabah->save();
        }

        return view('nasabah.show', compact('nasabah', 'saldoSaatIni'));
    }
    
    /**
     * Menampilkan form tambah nasabah
     */
    public function create()
    {
        return view('nasabah.create');
    }

    /**
     * Menyimpan data nasabah baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'alamat' => 'nullable|string',
        ]);

        // 1. Auto generate kode_nasabah secara urut berdasarkan kode terakhir (BS-xxxx)
        $lastNasabah = Nasabah::where('kode_nasabah', 'LIKE', 'BS-%')
                             ->orderBy('kode_nasabah', 'desc')
                             ->first();

        if ($lastNasabah) {
            $lastNumber = intval(substr($lastNasabah->kode_nasabah, 3)); 
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $kode = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 2. DETEKSI ATRIBUT INPUT FORM
        $namaInput   = $request->nama ?? $request->nama_lengkap ?? $request->nama_nasabah ?? 'Warga Tanpa Nama';
        $noHpInput   = $request->nohp ?? $request->no_hp ?? $request->no_telp ?? $request->handphone ?? '-';
        $alamatInput = $request->alamat ?? $request->alamat_kampung ?? '-';

        // 3. Simpan data ke database
        $nasabah = new Nasabah();
        $nasabah->kode_nasabah = $kode;
        $nasabah->nama         = $namaInput;
        $nasabah->alamat       = $alamatInput;
        $nasabah->no_hp        = $noHpInput; 
        $nasabah->saldo        = 0; // Saldo awal 0
        $nasabah->rt           = '00'; 
        $nasabah->rw           = '00';
        $nasabah->save();

        // 4. Inisialisasi saldo awal nasabah di tabel SaldoNasabah
        SaldoNasabah::create([
            'nasabah_id' => $nasabah->id,
            'saldo'      => 0
        ]);

        // 5. Kembalikan ke halaman data nasabah
        return redirect('/nasabah-ui')->with('success', 'Nasabah baru atas nama ' . $namaInput . ' berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit nasabah
     */
    public function edit(Nasabah $nasabah)
    {
        return view('nasabah.edit', compact('nasabah')); 
    }

    /**
     * Mengupdate data nasabah
     */
    public function update(Request $request, Nasabah $nasabah)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt'     => 'required|string|max:10',
            'rw'     => 'required|string|max:10',
            'no_hp'  => 'required|string|max:20',
        ]);

        $nasabah->update($request->all());

        if ($request->header('referer') && str_contains($request->header('referer'), 'nasabah-ui')) {
            return redirect('/nasabah-ui')->with('success', 'Data Nasabah berhasil diupdate.');
        }

        return redirect()->route('nasabah.index')->with('success', 'Data Nasabah berhasil diupdate.');
    }

    /**
     * Menghapus data nasabah
     */
    public function destroy(Nasabah $nasabah)
    {
        $nasabah->delete();

        if (request()->header('referer') && str_contains(request()->header('referer'), 'nasabah-ui')) {
            return redirect('/nasabah-ui')->with('success', 'Nasabah berhasil dihapus.');
        }

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil dihapus.');
    }
}