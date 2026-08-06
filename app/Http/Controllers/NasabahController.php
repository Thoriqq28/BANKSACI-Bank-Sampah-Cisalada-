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
        $nasabahs = Nasabah::orderBy('created_at', 'desc')->get(); 
        
        // AUTO-SYNC REAL-TIME SALDO
        foreach ($nasabahs as $nasabah) {
            $totalSetoran = Setoran::where('nasabah_id', $nasabah->id)->sum('total_harga');

            $totalPenarikanSelesai = Penarikan::where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['selesai'])
                ->sum('jumlah');

            $saldoRealtime = max(0, $totalSetoran - $totalPenarikanSelesai);

            if ($nasabah->saldo != $saldoRealtime) {
                $nasabah->saldo = $saldoRealtime;
                $nasabah->save();
            }

            SaldoNasabah::updateOrCreate(
                ['nasabah_id' => $nasabah->id],
                ['saldo' => $saldoRealtime]
            );
        }

        return view('nasabah.index', compact('nasabahs'));
    }

    /**
     * Fitur Export Excel
     */
    public function exportExcel()
    {
        $fileName = 'Laporan_Nasabah_BankSampah_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new NasabahExport, $fileName);
    }

    /**
     * Menampilkan detail informasi nasabah
     */
    public function show($id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $totalSetoran = DB::table('setoran')
            ->where('nasabah_id', $nasabah->id)
            ->sum('total_harga');

        $totalPenarikanSelesai = 0;
        if (Schema::hasTable('penarikan')) {
            $totalPenarikanSelesai = DB::table('penarikan')
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
     * Menyimpan data nasabah baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'nama'   => 'required|string|max:255',
            'no_hp'  => 'required|string|max:20',
            'rt'     => 'required|string|max:10',
            'rw'     => 'required|string|max:10',
            'alamat' => 'required|string',
        ]);

        $namaInput       = $request->nama;
        $noHpInput       = $request->no_hp;
        $alamatInput     = $request->alamat;
        $rtInput         = $request->rt;
        $rwInput         = $request->rw;
        $nomorRumahInput = $request->nomor_rumah ?? null;

        try {
            $kodeTerpakai = null;

            DB::transaction(function () use ($namaInput, $noHpInput, $alamatInput, $rtInput, $rwInput, $nomorRumahInput, &$kodeTerpakai) {
                
                // Cek nama tabel yang digunakan di DB (nasabah / nasabahs)
                $tableName = Schema::hasTable('nasabah') ? 'nasabah' : 'nasabahs';

                // Hitung urutan angka tertinggi dari kode_nasabah
                $maxNumber = DB::table($tableName)
                    ->where('kode_nasabah', 'LIKE', 'BS-%')
                    ->lockForUpdate()
                    ->selectRaw("MAX(CAST(SUBSTRING(kode_nasabah, 4) AS UNSIGNED)) as max_no")
                    ->value('max_no');

                $nextNumber = ($maxNumber ? (int)$maxNumber : 0) + 1;
                $kode = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                while (DB::table($tableName)->where('kode_nasabah', $kode)->exists()) {
                    $nextNumber++;
                    $kode = 'BS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                }

                $kodeTerpakai = $kode;

                // Buat Email Unik untuk Akun User Login Nasabah
                $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $namaInput));
                $uniqueEmail = ($cleanName ?: 'nasabah') . time() . rand(10, 99) . '@banksaci.com';

                // 1. Tambah ke tabel Users
                $user = User::create([
                    'name'     => $namaInput,
                    'email'    => $uniqueEmail,
                    'password' => Hash::make('password123'),
                    'role'     => 'nasabah',
                ]);

                // 2. Tambah ke tabel Nasabah
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

                // 3. Inisialisasi Saldo
                SaldoNasabah::create([
                    'nasabah_id' => $nasabah->id,
                    'saldo'      => 0
                ]);
            });

            return redirect('/nasabah-ui')->with('success', 'Nasabah baru ' . $namaInput . ' (' . $kodeTerpakai . ') berhasil ditambahkan.');

        } catch (\Exception $e) {
            // JIKA GAGAL: Kembalikan pesan error ASLI dari database agar kita tahu kendalanya
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Form edit nasabah
     */
    public function edit(Nasabah $nasabah)
    {
        return view('nasabah.edit', compact('nasabah')); 
    }

    /**
     * Update data nasabah
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

        return redirect('/nasabah-ui')->with('success', 'Data Nasabah berhasil diupdate.');
    }

    /**
     * Hapus data nasabah
     */
    public function destroy(Nasabah $nasabah)
    {
        $nasabah->delete();

        return redirect('/nasabah-ui')->with('success', 'Nasabah berhasil dihapus.');
    }
}