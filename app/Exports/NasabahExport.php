<?php

namespace App\Exports;

use App\Models\Nasabah;
use App\Models\KategoriSampah;
use App\Models\Penarikan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NasabahExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $bulan;
    protected $tahun;
    protected $search;

    public function __construct($bulan = null, $tahun = null, $search = null)
    {
        $this->bulan  = $bulan ?? date('m');
        $this->tahun  = $tahun ?? date('Y');
        $this->search = $search;
    }

    public function view(): View
    {
        $bulan  = $this->bulan;
        $tahun  = $this->tahun;
        $search = $this->search;

        // 1. Ambil Kategori/Jenis Sampah MURNI dari Database (Tanpa $kategoriStandar hardcode)
        $rawKategori = \DB::table('jenis_sampah')->pluck('nama')->toArray();
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

        // Hilangkan duplikat nama kategori
        $kategoriList = array_values(array_unique($kategoriList));

        // 2. Query Nasabah dengan Eager Loading Setoran
        $queryNasabah = Nasabah::with([
            'setoran' => function($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            }
        ]);

        $search = trim($search);
        if (!empty($search)) {
            $queryNasabah->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);

                if (\Schema::hasColumn('nasabahs', 'no_induk')) {
                    $q->orWhereRaw('LOWER(no_induk) LIKE ?', ['%' . strtolower($search) . '%']);
                }
                if (\Schema::hasColumn('nasabahs', 'kode_nasabah')) {
                    $q->orWhereRaw('LOWER(kode_nasabah) LIKE ?', ['%' . strtolower($search) . '%']);
                }
            });
        }

        $nasabahs = $queryNasabah->orderBy('nama', 'asc')->get();

        // 3. Data Penarikan Filter Periode & Status 'selesai'
        $penarikanPerNasabah = Penarikan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereRaw("LOWER(status) = ?", ['selesai'])
            ->selectRaw('nasabah_id, SUM(jumlah) as total_penarikan')
            ->groupBy('nasabah_id')
            ->pluck('total_penarikan', 'nasabah_id');

        // Map Cache jenis_sampah ID -> Nama Jenis Sampah dari Database
        $jenisSampahMap = \DB::table('jenis_sampah')->pluck('nama', 'id')->toArray();

        $rekapData = [];
        $grandTotalPemasukan = 0;
        $grandTotalPenarikan = 0;
        $grandTotalKg        = 0;
        $grandTotalKategori  = array_fill_keys($kategoriList, 0);

        // 4. Hitung Data Per Nasabah Berdasarkan Tabel Detail
        foreach ($nasabahs as $nasabah) {
            $beratKategori = array_fill_keys($kategoriList, 0);
            $pemasukan = 0;

            // Ambil ID setoran milik nasabah ini pada bulan/tahun tersebut
            $setoranIds = $nasabah->setoran->pluck('id')->toArray();

            if (!empty($setoranIds)) {
                // Tarik data langsung dari tabel setoran_detail berdasarkan setoran_id
                $details = \DB::table('setoran_detail')
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

        return view('laporan.excel', [
            'rekapData'           => $rekapData,
            'kategoriList'        => $kategoriList,
            'grandTotalPemasukan' => $grandTotalPemasukan,
            'grandTotalPenarikan' => $grandTotalPenarikan,
            'grandTotalKategori'  => $grandTotalKategori,
            'grandTotalKg'        => $grandTotalKg,
            'grandSaldo'          => $grandSaldo,
            'bulan'               => $bulan,
            'tahun'               => $tahun,
        ]);
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

        // PRIORITAS 1: Cocokkan bagian belakang
        if ($bagianBelakang !== '') {
            foreach ($kategoriList as $katHeader) {
                if ($bagianBelakang === $katHeader || str_contains($katHeader, $bagianBelakang) || str_contains($bagianBelakang, $katHeader)) {
                    $matchedKey = $katHeader;
                    break;
                }
            }
        }

        // PRIORITAS 2: Cocokkan bagian depan
        if (!$matchedKey && $bagianDepan !== '') {
            foreach ($kategoriList as $katHeader) {
                if ($bagianDepan === $katHeader || str_contains($katHeader, $bagianDepan) || str_contains($bagianBelakang, $katHeader)) {
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}