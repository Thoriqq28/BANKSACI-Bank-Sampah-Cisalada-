<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\SaldoNasabah;
use App\Models\Setoran;
use App\Models\Penarikan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

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
        foreach ($nasabahs as $nasabah) {
            // 1. Hitung total setoran
            $totalSetoran = Setoran::where('nasabah_id', $nasabah->id)->sum('total_harga');

            // 2. Hitung total penarikan yang statusnya HANYA 'selesai'
            $totalPenarikanSelesai = Penarikan::where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['selesai'])
                ->sum('jumlah');

            // 3. Hitung saldo riil
            $saldoRealtime = max(0, $totalSetoran - $totalPenarikanSelesai);

            // 4. Jika nilai di DB berbeda, update DB
            if ($nasabah->saldo != $saldoRealtime) {
                $nasabah->saldo = $saldoRealtime;
                $nasabah->save();
            }

            // Update ke tabel SaldoNasabah jika terhubung
            SaldoNasabah::updateOrCreate(
                ['nasabah_id' => $nasabah->id],
                ['saldo' => $saldoRealtime]
            );
        }

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
        $nasabah = Nasabah::findOrFail($id);

        $totalSetoran = DB::table('setorans')
            ->where('nasabah_id', $nasabah->id)
            ->orWhere('user_id', $nasabah->user_id ?? $nasabah->id)
            ->sum('total_harga');

        $totalPenarikanSelesai = 0;
        if (Schema::hasTable('penarikans')) {
            $totalPenarikanSelesai = DB::table('penarikans')
                ->where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['selesai'])
                ->sum('jumlah') ?? 0;
        }

        $saldoSaatIni = max(0, $totalSetoran - $totalPenarikanSelesai);

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
     * Menyimpan data nasabah baru ke database (PERBAIKAN LENGKAP)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama'   => 'required|string|max:255',
            'no_hp'  => 'required|string|max:20',
            'rt'     => 'required|string|max:10',
            'rw'     => 'required|string|max:10',
            'alamat' => 'required|string',
        ]);

        // 2. Auto Generate kode_nasabah (Format BS-0016 dst)
        $lastNasabah = Nasabah::where('kode_nasabah', 'LIKE', 'BS-%')
                             ->orderBy('id', 'desc')
                             ->first();

        if ($lastNasabah) {
            $lastNumber = intval(substr($lastNasabah->kode_nasabah, 3)); 
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $kode = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 3. Tangkap nilai input dari Form
        $namaInput       = $request->nama ?? 'Warga Tanpa Nama';
        $noHpInput       = $request->no_hp ?? '-';
        $alamatInput     = $request->alamat ?? '-';
        $rtInput         = $request->rt ?? '00';
        $rwInput         = $request->rw ?? '00';
        $nomorRumahInput = $request->nomor_rumah ?? null;

        // 4. Buatkan User Account otomatis agar foreign key `user_id` terisi
        $user = User::create([
            'name'     => $namaInput,
            'email'    => strtolower(str_replace(' ', '', $namaInput)) . rand(100, 999) . '@banksaci.com',
            'password' => Hash::make('password123'),
            'role'     => 'nasabah',
        ]);

        // 5. Simpan ke database sesuai dengan kolom phpMyAdmin
        $nasabah = new Nasabah();
        $nasabah->user_id      = $user->id;
        $nasabah->kode_nasabah = $kode;
        $nasabah->nama         = $namaInput;
        $nasabah->alamat       = $alamatInput;
        $nasabah->no_hp        = $noHpInput; 
        $nasabah->rt           = $rtInput; 
        $nasabah->rw           = $rwInput;
        $nasabah->nomor_rumah  = $nomorRumahInput;
        $nasabah->saldo        = 0;
        $nasabah->save();

        // 6. Inisialisasi saldo di tabel SaldoNasabah
        SaldoNasabah::create([
            'nasabah_id' => $nasabah->id,
            'saldo'      => 0
        ]);

        // 7. Redirect Sukses
        return redirect('/nasabah-ui')->with('success', 'Nasabah baru ' . $namaInput . ' (' . $kode . ') berhasil ditambahkan.');
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