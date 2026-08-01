<?php

namespace App\Http\Controllers;

use App\Models\Penarikan;
use App\Models\Nasabah;
use App\Models\SaldoNasabah;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenarikanController extends Controller
{
    /**
     * Helper privat untuk menghitung & menyinkronkan saldo riil Nasabah ke Database
     */
    private function sinkronkanSaldoNasabah($nasabahId)
    {
        // 1. Total Semua Setoran
        $totalSetoran = Setoran::where('nasabah_id', $nasabahId)->sum('total_harga');

        // 2. Total Penarikan yang SUDAH SELESAI
        $totalPenarikanSelesai = Penarikan::where('nasabah_id', $nasabahId)
            ->whereRaw('LOWER(status) = ?', ['selesai'])
            ->sum('jumlah');

        // 3. Saldo Riil (Setoran - Penarikan Selesai)
        $saldoAkhir = max(0, $totalSetoran - $totalPenarikanSelesai);

        // Update ke tabel SaldoNasabah (jika tabel terpisah digunakan)
        SaldoNasabah::updateOrCreate(
            ['nasabah_id' => $nasabahId],
            ['saldo' => $saldoAkhir]
        );

        // Update ke kolom saldo utama pada tabel Nasabah
        $nasabah = Nasabah::find($nasabahId);
        if ($nasabah && \Schema::hasColumn('nasabahs', 'saldo')) {
            $nasabah->saldo = $saldoAkhir;
            $nasabah->save();
        }

        return $saldoAkhir;
    }

    /**
     * Menampilkan daftar penarikan dengan filter status tab
     */
    public function index(Request $request)
    {
        $query = Penarikan::with('nasabah')->orderBy('created_at', 'desc');

        if ($request->has('status') && !empty($request->status)) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        $penarikans = $query->get();

        return view('penarikan.index', compact('penarikans'));
    }

    /**
     * Form pembuatan penarikan baru oleh Admin
     */
    public function create()
    {
        $nasabahs = Nasabah::orderBy('nama', 'asc')->get();

        foreach ($nasabahs as $nasabah) {
            $totalSetoran = Setoran::where('nasabah_id', $nasabah->id)->sum('total_harga');

            $totalPenarikanSelesai = Penarikan::where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['selesai'])
                ->sum('jumlah');

            $totalPenarikanPending = Penarikan::where('nasabah_id', $nasabah->id)
                ->whereRaw('LOWER(status) = ?', ['pending'])
                ->sum('jumlah');

            // Saldo riil yang aman & bebas dari antrean penarikan pending
            $nasabah->saldo_aktif = max(0, $totalSetoran - $totalPenarikanSelesai - $totalPenarikanPending);
        }

        return view('penarikan.create', compact('nasabahs'));
    }

    /**
     * Menyimpan data penarikan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'jumlah'     => 'required|numeric|min:1000',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        // Hitung batas Saldo Tersedia
        $totalSetoran = Setoran::where('nasabah_id', $request->nasabah_id)->sum('total_harga');
        
        $totalPenarikanSelesai = Penarikan::where('nasabah_id', $request->nasabah_id)
            ->whereRaw('LOWER(status) = ?', ['selesai'])
            ->sum('jumlah');

        $totalPenarikanPending = Penarikan::where('nasabah_id', $request->nasabah_id)
            ->whereRaw('LOWER(status) = ?', ['pending'])
            ->sum('jumlah');

        $saldoTersedia = max(0, $totalSetoran - $totalPenarikanSelesai - $totalPenarikanPending);

        // Validasi Ketersediaan Saldo
        if ($request->jumlah > $saldoTersedia) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan ini. Saldo tersedia: Rp ' . number_format($saldoTersedia, 0, ',', '.'));
        }

        DB::beginTransaction();

        try {
            // 1. Simpan Penarikan Baru dengan status 'pending'
            Penarikan::create([
                'nasabah_id' => $request->nasabah_id,
                'user_id'    => auth()->id(),
                'jumlah'     => $request->jumlah,
                'tanggal'    => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status'     => 'pending',
            ]);

            // 2. Pastikan Saldo Nasabah Tetap Sinkron
            $this->sinkronkanSaldoNasabah($request->nasabah_id);

            DB::commit();

            return redirect()->route('penarikan.index')->with('success', 'Penarikan berhasil diajukan dan menunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui status penarikan (Pending -> Selesai / Cancel)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,selesai,cancel'
        ]);

        $penarikan = Penarikan::findOrFail($id);
        $statusLama = strtolower($penarikan->status);
        $statusBaru = strtolower($request->status);

        if ($statusLama === $statusBaru) {
            return redirect()->back();
        }

        DB::beginTransaction();

        try {
            // 1. Update status transaksi penarikan
            $penarikan->status = $statusBaru;
            $penarikan->save();

            // 2. SINKRONKAN SALDO NASABAH
            // Ketika status diubah ke 'cancel' atau 'selesai', fungsi ini otomatis 
            // menghitung ulang saldo tanpa menghitung penarikan 'cancel'
            $this->sinkronkanSaldoNasabah($penarikan->nasabah_id);

            DB::commit();

            return redirect()->back()->with('success', 'Status penarikan berhasil diperbarui menjadi ' . ucfirst($statusBaru) . '!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}