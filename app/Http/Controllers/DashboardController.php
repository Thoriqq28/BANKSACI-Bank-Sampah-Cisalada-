<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\Setoran;
use App\Models\Penarikan;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    /**
     * Halaman Utama Dashboard Admin / Staf
     */
    public function index()
    {
        // 1. CEK AUTENTIKASI (Pencegahan Error 500 saat belum/habis login)
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        $role = $user->role ?? 'staf'; // Default ke 'staf' jika role bernilai null
        
        // Ambil Bulan & Tahun Berjalan (Realtime)
        $bulanIni = date('m');
        $tahunIni = date('Y');

        // 2. QUERY DATABASE DENGAN ERROR HANDLING
        try {
            // Statistik Utama
            $totalNasabah = Nasabah::count();
            $totalSaldo   = Nasabah::sum('saldo') ?? 0;

            // Total Berat Sampah Bulan Ini (Membaca dari tabel setoran_detail & setoran)
            $totalSampah = DB::table('setoran_detail')
                ->join('setoran', 'setoran_detail.setoran_id', '=', 'setoran.id')
                ->whereMonth('setoran.tanggal', $bulanIni)
                ->whereYear('setoran.tanggal', $tahunIni)
                ->sum('setoran_detail.berat') ?? 0;
            
            // Total Pemasukan Kas dari Setoran Sampah (Bulan Ini)
            $totalPemasukan = Setoran::whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->sum('total_harga') ?? 0;
            
            // Total Penarikan Saldo oleh Nasabah (Bulan Ini & Status Selesai)
            $totalPenarikan = Penarikan::whereMonth('tanggal', $bulanIni)
                ->whereYear('tanggal', $tahunIni)
                ->where(function($q) {
                    $q->where('status', 'selesai')
                      ->orWhere('status', 'Selesai');
                })
                ->sum('jumlah') ?? 0;

            // Kategori Sampah
            $kategoriSampah = KategoriSampah::all();

            // Data Grafik Setoran Bulanan (Tahun Ini)
            $chartData = DB::table('setoran_detail')
                ->join('setoran', 'setoran_detail.setoran_id', '=', 'setoran.id')
                ->selectRaw('MONTH(setoran.tanggal) as month, SUM(setoran_detail.berat) as total_berat')
                ->whereYear('setoran.tanggal', $tahunIni)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

        } catch (\Exception $e) {
            // Fallback Nilai Default jika ada kendala query database
            $totalNasabah   = 0;
            $totalSaldo     = 0;
            $totalSampah    = 0;
            $totalPemasukan = 0;
            $totalPenarikan = 0;
            $kategoriSampah = collect([]);
            $chartData      = collect([]);
        }

        // 3. FORMAT DATA GRAFIK BULANAN
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $beratData = array_fill(0, 12, 0);

        foreach ($chartData as $data) {
            if (isset($data->month) && $data->month >= 1 && $data->month <= 12) {
                $beratData[$data->month - 1] = (float) $data->total_berat;
            }
        }

        // 4. VARIABEL SIMULASI NOTIFIKASI IURAN
        $statusIuran = 'pengingat'; 
        $notifikasiIuran = 'Hari ini tanggal 1! Tagihan iuran wajib bulan ini sebesar <strong>Rp 20.000</strong> akan otomatis ditarik dari saldo Bank Sampah Anda.';

        // 5. KIRIMKAN DATA KE VIEW
        return view('dashboard', compact(
            'role', 
            'totalNasabah', 
            'totalSaldo', 
            'totalSampah', 
            'totalPemasukan',
            'totalPenarikan',
            'months', 
            'beratData', 
            'kategoriSampah', 
            'statusIuran', 
            'notifikasiIuran'
        ));
    }

    /**
     * Tampilkan daftar permintaan jemput di panel admin
     */
    public function requestJemputIndex()
    {
        $requests = DB::table('request_jemputs')
            ->join('nasabah', 'request_jemputs.nasabah_id', '=', 'nasabah.id')
            ->select(
                'request_jemputs.*', 
                'nasabah.nama as nama_nasabah', 
                'nasabah.no_hp',
                'nasabah.rt',
                'nasabah.rw',
                'nasabah.alamat'
            )
            ->orderBy('request_jemputs.created_at', 'desc')
            ->get();

        return view('jemput.index', compact('requests'));
    }

    /**
     * Update status jemput (Selesai / Batal)
     */
    public function requestJemputUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,selesai,batal'
        ]);

        DB::table('request_jemputs')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Status penjemputan berhasil diperbarui!');
    }
}