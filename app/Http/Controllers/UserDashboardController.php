<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Nasabah;
use App\Models\Setoran;
use App\Models\Penarikan;
use Illuminate\Support\Facades\DB; 

class UserDashboardController extends Controller
{
    /**
     * Helper privat untuk mengambil data Nasabah yang sedang login
     */
    private function getNasabahLogIn()
{
    $user = Auth::user();

    if (!$user) {
        return null;
    }

    // Karena user_id sudah ada di tabel nasabah,
    // cukup cari berdasarkan relasi ini.
    return Nasabah::where('user_id', $user->id)->first();
}

    /**
     * Halaman Utama User Dashboard
     */
    public function index()
    {
        $nasabah = $this->getNasabahLogIn();

        if (!$nasabah) {
            return view('user.dashboard', [
                'nasabah' => (object)[
                    'nama' => Auth::user()->name ?? 'User', 
                    'kode_nasabah' => 'Belum Terelasi', 
                    'tingkatan' => 'WARGA PEDULI',
                    'alamat' => '-',
                    'no_hp' => '-'
                ],
                'totalSampahKg' => 0,
                'targetBerikutnya' => 15,
                'jenisSampah' => collect([])
            ]);
        }

        $totalSampahKg = Setoran::where('nasabah_id', $nasabah->id)->sum('total_berat') ?? 0;

        $targetBerikutnya = 15;
        if ($totalSampahKg >= 15 && $totalSampahKg < 50) {
            $targetBerikutnya = 50; 
        } elseif ($totalSampahKg >= 50) {
            $targetBerikutnya = 100;
        }

        try {
            $rawJenis = DB::table('jenis_sampah')->get();
            if ($rawJenis->isNotEmpty()) {
                $jenisSampah = $rawJenis->map(function ($item) {
                    return (object)[
                        'id' => $item->id,
                        'nama_jenis' => $item->nama_jenis ?? $item->nama_sampah ?? $item->nama ?? 'Sampah',
                        'harga_per_kg' => $item->harga_per_kg ?? $item->harga ?? 1500
                    ];
                });
            } else {
                $jenisSampah = collect([]);
            }
        } catch (\Exception $e) {
            $jenisSampah = collect([]);
        }

        if ($jenisSampah->isEmpty()) {
            $jenisSampah = collect([
                (object)['id' => 1, 'nama_jenis' => 'Plastik Botol', 'harga_per_kg' => 3000],
                (object)['id' => 2, 'nama_jenis' => 'Kertas / Kardus', 'harga_per_kg' => 2500],
                (object)['id' => 3, 'nama_jenis' => 'Besi / Logam', 'harga_per_kg' => 4000],
            ]);
        }

        return view('user.dashboard', compact('nasabah', 'totalSampahKg', 'targetBerikutnya', 'jenisSampah'));
    }

    /**
     * Halaman E-Wallet & Tabungan (/user/tabungan)
     */
    public function tabungan()
    {
        $nasabah = $this->getNasabahLogIn();

        if (!$nasabah) {
            return redirect()->route('dashboard.user')->with('error', 'Profil Nasabah Anda belum terdaftar.');
        }

        // Ambil riwayat khusus nasabah login
        $setorans = Setoran::where('nasabah_id', $nasabah->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $penarikans = Penarikan::where('nasabah_id', $nasabah->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Total Pemasukan dari Setoran
        $totalPemasukan = $setorans->sum(function($item) {
            return $item->total_harga ?? $item->nominal ?? 0;
        });

        // 🟢 PERBAIKAN: Hanya hitung penarikan yang statusnya 'selesai'
        // Penarikan dengan status 'pending' atau 'cancel' TIDAK MEMOTONG saldo!
        $totalPenarikanSelesai = $penarikans->where('status', 'selesai')->sum('jumlah');
        
        $saldo = $totalPemasukan - $totalPenarikanSelesai;
        $totalSampah = $setorans->sum('total_berat');

        return view('user.tabungan', compact('nasabah', 'saldo', 'totalSampah', 'setorans', 'penarikans'));
    }

    /**
     * Halaman Histori Mutasi Lengkap Warga (/user/mutasi)
     */
    public function mutasi()
    {
        $nasabah = $this->getNasabahLogIn();

        if (!$nasabah) {
            return redirect()->route('dashboard.user')->with('error', 'Profil Nasabah Anda belum terdaftar.');
        }

        // Ambil riwayat khusus nasabah login
        $setorans = Setoran::where('nasabah_id', $nasabah->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $penarikans = Penarikan::where('nasabah_id', $nasabah->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Total Pemasukan dari Setoran
        $totalPemasukan = $setorans->sum(function($item) {
            return $item->total_harga ?? $item->nominal ?? 0;
        });

        $totalPenarikanSelesai = $penarikans->where('status', 'selesai')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPenarikanSelesai;

        return view('user.mutasi', compact('nasabah', 'saldo', 'setorans', 'penarikans'));
    }

    /**
     * Aksi Request Jemput Sampah
     */
    public function requestJemput(Request $request)
    {
        $request->validate([
            'berat' => 'required|numeric|min:1',
            'catatan' => 'nullable|string|max:255'
        ]);

        $nasabah = $this->getNasabahLogIn();

        if (!$nasabah) {
            return redirect()->back()->with('error', 'Gagal mengajukan jemput. Akun Anda belum terverifikasi sebagai nasabah.');
        }

        DB::table('request_jemputs')->insert([
            'nasabah_id' => $nasabah->id,
            'perkiraan_berat' => $request->berat,
            'catatan' => $request->catatan,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan jemput sampah berhasil dikirim!');
    }

    /**
     * Aksi Tarik Saldo (Pengajuan dari User)
     */
    public function tarikSaldo(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'jenis_penarikan' => 'required|in:cash,ewallet',
        ]);

        $nasabah = $this->getNasabahLogIn();

        if (!$nasabah) {
            return back()->with('error', 'Profil Nasabah Anda tidak ditemukan.');
        }

        // Hitung Saldo Riil Saat Ini (Pemasukan - Penarikan Selesai)
        $totalPemasukan = Setoran::where('nasabah_id', $nasabah->id)->get()->sum(function($item) {
            return $item->total_harga ?? $item->nominal ?? 0;
        });
        
        $totalPenarikanSelesai = Penarikan::where('nasabah_id', $nasabah->id)
                                          ->where('status', 'selesai')
                                          ->sum('jumlah');

        $saldoAktif = $totalPemasukan - $totalPenarikanSelesai;

        // 1. Cek Apakah Saldo Cukup
        if ($saldoAktif < $request->nominal) {
            return back()->with('error', 'Saldo Anda tidak mencukupi untuk melakukan penarikan sebesar Rp ' . number_format($request->nominal, 0, ',', '.'));
        }

        // Format Keterangan
        $keterangan = ($request->jenis_penarikan === 'ewallet') 
            ? 'Tarik E-Wallet (' . strtoupper($request->jenis_ewallet ?? 'E-Wallet') . ' - ' . ($request->nomor_ewallet ?? '') . ')'
            : 'Tarik Saldo Cash (Tunai)';

        // 2. Simpan Data Penarikan Berstatus 'pending'
        // (Sangat Penting: JANGAN potong saldo nasabah di sini. Potong saldo hanya saat Admin meng-Approve ke 'selesai')
        Penarikan::create([
            'nasabah_id' => $nasabah->id,
            'user_id'    => Auth::id(),
            'jumlah'     => $request->nominal,
            'status'     => 'pending',
            'tanggal'    => now()->toDateString(),
            'keterangan' => $keterangan,
        ]);

        return back()->with('success', 'Pengajuan penarikan berhasil dikirim! Menunggu konfirmasi admin.');
    }
}