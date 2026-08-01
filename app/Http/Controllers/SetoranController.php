<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    public function index()
    {
        $setorans = DB::table('setoran')
            ->join('nasabah', 'setoran.nasabah_id', '=', 'nasabah.id')
            ->select('setoran.*', 'nasabah.nama as nama_nasabah')
            ->orderBy('setoran.created_at', 'desc')
            ->get();

        return view('setoran.index', compact('setorans'));
    }

    public function create()
    {
        $nasabahs = DB::table('nasabah')->get();
        // Mengambil data dari kategori_sampah sesuai blade
        $jenisSampahs = DB::table('kategori_sampah')->get();

        return view('setoran.create', compact('nasabahs', 'jenisSampahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required',
            'sampah_id'  => 'required',
            'berat'      => 'required|numeric|min:0.1',
        ]);

        // 1. Ambil data pilihan dari tabel kategori_sampah
        $sampah = DB::table('kategori_sampah')->where('id', $request->sampah_id)->first();

        $hargaPerKg = 0;

        if ($sampah) {
            // Pecah string format "KATEGORI | NAMA | HARGA"
            if (str_contains($sampah->nama, '|')) {
                $pecah = explode('|', $sampah->nama);
                if (isset($pecah[2]) && is_numeric(trim($pecah[2]))) {
                    $hargaPerKg = (int) trim($pecah[2]);
                }
            } else {
                $hargaPerKg = $sampah->harga_per_kg ?? $sampah->harga ?? $sampah->harga_beli ?? 0;
            }
        }

        $totalPendapatan = $request->berat * $hargaPerKg;

        DB::beginTransaction();
        try {
            // A. Simpan ke Tabel `setoran`
            $setoranId = DB::table('setoran')->insertGetId([
                'nasabah_id'  => $request->nasabah_id,
                'user_id'     => auth()->id() ?? 1,
                'tanggal'     => now()->toDateString(),
                'total_berat' => $request->berat,
                'total_harga' => $totalPendapatan,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // B. Simpan ke Tabel `setoran_detail`
            // jenis_sampah_id diisi sesuai ID kategori yang dipilih agar tidak terkunci di angka 1 lagi!
            DB::table('setoran_detail')->insert([
                'setoran_id'      => $setoranId,
                'jenis_sampah_id' => $request->sampah_id,
                'berat'           => $request->berat,
                'harga'           => $hargaPerKg,
                'subtotal'        => $totalPendapatan,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // C. Tambah Saldo Nasabah
            DB::table('nasabah')->where('id', $request->nasabah_id)->increment('saldo', $totalPendapatan);

            DB::commit();

            return redirect('/laporan-menyeluruh')->with('success', 'Setoran berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}