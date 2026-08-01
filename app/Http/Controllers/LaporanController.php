<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\KategoriSampah;
use App\Models\Penarikan;
use App\Exports\NasabahExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama Laporan & Ajax Request
     */
    public function index(Request $request)
    {
        $bulan  = $request->get('bulan', date('m'));
        $tahun  = $request->get('tahun', date('Y'));
        $search = $request->get('search', '');

        $dataRekap = $this->getRekapData($bulan, $tahun, $search);

        if ($request->ajax()) {
            return response()->json([
                'status'              => 'success',
                'rekapData'           => $dataRekap['rekapData'],
                'kategoriList'        => $dataRekap['kategoriList'],
                'grandTotalPemasukan' => $dataRekap['grandTotalPemasukan'],
                'grandTotalPenarikan' => $dataRekap['grandTotalPenarikan'],
                'grandTotalKategori'  => $dataRekap['grandTotalKategori'],
                'grandTotalKg'        => $dataRekap['grandTotalKg'],
                'grandSaldo'          => $dataRekap['grandSaldo'],
            ]);
        }

        return view('laporan.index', array_merge($dataRekap, [
            'bulan'  => $bulan,
            'tahun'  => $tahun,
            'search' => $search,
        ]));
    }

    /**
     * Export Laporan ke Excel
     */
    /**
     * Export Laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            // Naikkan batas memori jika data banyak
            ini_set('memory_limit', '512M');

            $bulan  = $request->get('bulan', date('m'));
            $tahun  = $request->get('tahun', date('Y'));
            $search = $request->get('search', '');

            $fileName = "Laporan_Rekapitulasi_{$bulan}_{$tahun}.xlsx";

           return Excel::download(new NasabahExport($bulan, $tahun, $search), "Laporan_Rekapitulasi_{$bulan}_{$tahun}.xlsx");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export Laporan ke PDF (DomPDF)
     */
    public function exportPdf(Request $request)
    {
        try {
            ini_set('memory_limit', '512M');

            $bulan  = $request->get('bulan', date('m'));
            $tahun  = $request->get('tahun', date('Y'));
            $search = $request->get('search', '');

            $dataRekap = $this->getRekapData($bulan, $tahun, $search);

            $pdf = Pdf::loadView('laporan.pdf', array_merge($dataRekap, [
                'bulan'  => $bulan,
                'tahun'  => $tahun,
                'search' => $search,
            ]))
            ->setPaper('a4', 'landscape')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            // Gunakan download langsung dengan nama file resmi
            return $pdf->download("Laporan_Rekapitulasi_{$bulan}_{$tahun}.pdf");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * Helper Method Private: Mengkalkulasi seluruh data rekapitulasi
     */
    private function getRekapData($bulan, $tahun, $search = null)
    {
        // 1. KATEGORI DINAMIS: Ambil dari Database yang aktif saat ini
        $rawKategori = DB::table('jenis_sampah')->pluck('nama')->toArray();
        if (empty($rawKategori)) {
            $rawKategori = KategoriSampah::pluck('nama')->toArray();
        }
        
        $kategoriList = [];
        foreach ($rawKategori as $namaDb) {
            if (str_contains($namaDb, '|')) {
                $pecah = explode('|', $namaDb);
                $namaDb = count($pecah) >= 2 ? trim($pecah[1]) : $namaDb;
            }
            $kategoriList[] = strtoupper(trim($namaDb));
        }

        // Hapus duplikat dan rapikan urutan index
        $kategoriList = array_values(array_unique(array_filter($kategoriList)));

        // 2. Query Nasabah dengan Eager Loading Setoran
        $queryNasabah = Nasabah::with([
            'setoran' => function($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            }
        ]);

        $search = trim($search ?? '');
        if (!empty($search)) {
            $queryNasabah->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);

                if (Schema::hasColumn('nasabahs', 'no_induk')) {
                    $q->orWhereRaw('LOWER(no_induk) LIKE ?', ['%' . strtolower($search) . '%']);
                }
                if (Schema::hasColumn('nasabahs', 'kode_nasabah')) {
                    $q->orWhereRaw('LOWER(kode_nasabah) LIKE ?', ['%' . strtolower($search) . '%']);
                }
            });
        }

        $nasabahs = $queryNasabah->orderBy('nama', 'asc')->get();

        // Data Penarikan
        $penarikanPerNasabah = Penarikan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereRaw("LOWER(status) = ?", ['selesai'])
            ->selectRaw('nasabah_id, SUM(jumlah) as total_penarikan')
            ->groupBy('nasabah_id')
            ->pluck('total_penarikan', 'nasabah_id');

        // Map Cache jenis_sampah ID -> Nama Jenis Sampah dari Database
        $jenisSampahMap = DB::table('jenis_sampah')->pluck('nama', 'id')->toArray();

        $rekapData = [];
        $grandTotalPemasukan = 0;
        $grandTotalPenarikan = 0;
        $grandTotalKg        = 0;
        $grandTotalKategori  = array_fill_keys($kategoriList, 0);

        // 3. Hitung Data Per Nasabah Berdasarkan Tabel Detail
        foreach ($nasabahs as $nasabah) {
            $beratKategori = array_fill_keys($kategoriList, 0);
            $pemasukan = 0;

            // Ambil ID setoran milik nasabah ini pada bulan/tahun tersebut
            $setoranIds = $nasabah->setoran->pluck('id')->toArray();

            if (!empty($setoranIds)) {
                // Tarik data langsung dari tabel setoran_detail berdasarkan setoran_id
                $details = DB::table('setoran_detail')
                    ->whereIn('setoran_id', $setoranIds)
                    ->get();

                foreach ($details as $detail) {
                    $beratVal = (float) ($detail->berat ?? 0);
                    $jenisId  = $detail->jenis_sampah_id ?? null;
                    
                    $namaSampah = $jenisSampahMap[$jenisId] ?? '';

                    // Petakan berat ke kolom kategori yang sesuai
                    $this->petakanBeratKeKategori($namaSampah, $beratVal, $kategoriList, $beratKategori);
                }
            }

            // Hitung total pemasukan dari total_harga di tabel setoran utama
            foreach ($nasabah->setoran as $setoranItem) {
                $pemasukan += (float) ($setoranItem->total_harga ?? 0);
            }

            $totalBeratKg = array_sum($beratKategori);
            $penarikan    = (float) ($penarikanPerNasabah[$nasabah->id] ?? 0);
            $saldoAktif   = max(0, $pemasukan - $penarikan);

            $grandTotalPemasukan += $pemasukan;
            $grandTotalPenarikan += $penarikan;
            $grandTotalKg        += $totalBeratKg;

            foreach ($kategoriList as $katHeader) {
                $grandTotalKategori[$katHeader] += $beratKategori[$katHeader];
            }

            $rekapData[] = [
                'nasabah'         => $nasabah,
                'berat_kategori'  => $beratKategori,
                'total_berat_kg'  => $totalBeratKg,
                'total_pemasukan' => $pemasukan,
                'total_penarikan' => $penarikan,
                'saldo'           => $saldoAktif,
            ];
        }

        $grandSaldo = array_sum(array_column($rekapData, 'saldo'));

        return [
            'rekapData'           => $rekapData,
            'kategoriList'        => $kategoriList,
            'grandTotalPemasukan' => $grandTotalPemasukan,
            'grandTotalPenarikan' => $grandTotalPenarikan,
            'grandTotalKategori'  => $grandTotalKategori,
            'grandTotalKg'        => $grandTotalKg,
            'grandSaldo'          => $grandSaldo,
        ];
    }

    /**
     * Helper privat pencocokan nama sampah ke header kolom
     */
    private function petakanBeratKeKategori($namaRaw, $beratVal, $kategoriList, &$beratKategori)
    {
        if ($beratVal <= 0 || empty($namaRaw)) return;

        $namaUpper = strtoupper(trim($namaRaw));

        $bagianDepan    = $namaUpper;
        $bagianBelakang = '';

        if (str_contains($namaUpper, '-')) {
            $pecah = explode('-', $namaUpper);
            $bagianDepan    = strtoupper(trim($pecah[0])); 
            $bagianBelakang = strtoupper(trim($pecah[1] ?? '')); 
        }

        $matchedKey = null;

        // PRIORITAS 1: Cocokkan bagian belakang (misal 'KALENG', 'SIKU', 'BOTOL')
        if ($bagianBelakang !== '') {
            foreach ($kategoriList as $katHeader) {
                if ($bagianBelakang === $katHeader || str_contains($katHeader, $bagianBelakang) || str_contains($bagianBelakang, $katHeader)) {
                    $matchedKey = $katHeader;
                    break;
                }
            }
        }

        // PRIORITAS 2: Cocokkan bagian depan (misal 'LOGAM', 'BESI', 'PLASTIK')
        if (!$matchedKey && $bagianDepan !== '') {
            foreach ($kategoriList as $katHeader) {
                if ($bagianDepan === $katHeader || str_contains($katHeader, $bagianDepan) || str_contains($bagianDepan, $katHeader)) {
                    $matchedKey = $katHeader;
                    break;
                }
            }
        }

        // PRIORITAS 3: Full string search
        if (!$matchedKey) {
            foreach ($kategoriList as $katHeader) {
                if (str_contains($namaUpper, $katHeader)) {
                    $matchedKey = $katHeader;
                    break;
                }
            }
        }

        if ($matchedKey && isset($beratKategori[$matchedKey])) {
            $beratKategori[$matchedKey] += $beratVal;
        }
    }
}