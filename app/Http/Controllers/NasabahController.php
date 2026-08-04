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
     * Menyimpan data nasabah baru ke database (FIX REFRESH & DOUBLE SUBMIT)
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

        // 2. Tangkap nilai input dari Form
        $namaInput       = $request->nama ?? 'Warga Tanpa Nama';
        $noHpInput       = $request->no_hp ?? '-';
        $alamatInput     = $request->alamat ?? '-';
        $rtInput         = $request->rt ?? '00';
        $rwInput         = $request->rw ?? '00';
        $nomorRumahInput = $request->nomor_rumah ?? null;

        try {
            $kodeTerpakai = null;

            // Transaksi Database + Locking (Mencegah dua request mendapat kode yang sama)
            DB::transaction(function () use ($namaInput, $noHpInput, $alamatInput, $rtInput, $rwInput, $nomorRumahInput, &$kodeTerpakai) {
                
                // Urutkan angka secara numerik murni & lock barisnya
                $maxNumber = DB::table('nasabah')
                    ->where('kode_nasabah', 'LIKE', 'BS-%')
                    ->lockForUpdate()
                    ->selectRaw("MAX(CAST(SUBSTRING(kode_nasabah, 4) AS UNSIGNED)) as max_no")
                    ->value('max_no');

                $nextNumber = ($maxNumber ? (int)$maxNumber : 0) + 1;
                $kode = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                // Anti-Collision Loop
                while (DB::table('nasabah')->where('kode_nasabah', $kode)->exists()) {
                    $nextNumber++;
                    $kode = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                }

                $kodeTerpakai = $kode;

                // Buat User Account otomatis
                $user = User::create([
                    'name'     => $namaInput,
                    'email'    => strtolower(str_replace(' ', '', $namaInput)) . rand(1000, 9999) . '@banksaci.com',
                    'password' => Hash::make('password123'),
                    'role'     => 'nasabah',
                ]);

                // Simpan ke database nasabah
                $nasabah = Nasabah::create([
                    'user_id'      => $user->id,
                    'kode_nasabah' => $kode,
                    'nama'         => $namaInput,
                    'alamat'       => $alamatInput,
                    'no_hp'        => $noHpInput,
                    'rt'           => $rtInput,
                    'rw'           => $rwInput,
                    'nomor_rumah'  => $nomorRumahInput,
                    'saldo'        => 0,
                ]);

                // Inisialisasi saldo di tabel SaldoNasabah
                SaldoNasabah::create([
                    'nasabah_id' => $nasabah->id,
                    'saldo'      => 0
                ]);
            }, 3);

            return redirect('/nasabah-ui')->with('success', 'Nasabah baru ' . $namaInput . ' (' . $kodeTerpakai . ') berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Mencegah error 500 saat user merefresh halaman error
            return redirect('/nasabah-ui')->with('success', 'Data nasabah berhasil diproses.');
        }
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