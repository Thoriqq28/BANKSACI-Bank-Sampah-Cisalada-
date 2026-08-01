<?php

namespace App\Http\Controllers;

use App\Models\KategoriSampah;
use Illuminate\Http\Request;

class KategoriSampahController extends Controller
{
    /**
     * Menampilkan semua kategori sampah di Dashboard Admin
     */
    public function index()
    {
        // Ambil semua data kategori/jenis sampah dari database
        $kategori = KategoriSampah::orderBy('nama', 'asc')->get(); 
        
        // Kirim variabel bernama 'kategori' ke view index
        return view('sampah.index', compact('kategori'));
    }

    /**
     * Menampilkan form tambah kategori (Dashboard Admin)
     */
    public function create()
    {
        return view('jenis.create');
    }

    /**
     * Menyimpan kategori baru (Format Gabungan String Pipa untuk Laporan Menyeluruh)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_utama' => 'required|string|max:255',
            'nama_jenis'     => 'required|string|max:255',
            'harga_beli'     => 'required|numeric|min:0',
        ]);

        $kategoriUtama = strtoupper(trim($request->kategori_utama));
        $namaJenis     = trim($request->nama_jenis);
        $hargaBeli     = (int) $request->harga_beli;

        // Gunakan pengisian manual agar AMAN dari error MassAssignment / $fillable
        $kategori = new KategoriSampah();
        $kategori->nama = "{$kategoriUtama} | {$namaJenis} | {$hargaBeli}";
        $kategori->save();

        return redirect('/sampah-ui')->with('success', 'Kategori & Jenis sampah baru berhasil disimpan!');
    }

    /**
     * Menampilkan form edit kategori sampah
     */
    public function edit($id)
    {
        $sampah = KategoriSampah::findOrFail($id); 
        
        return view('sampah.edit', compact('sampah'));
    }

    /**
     * Memperbarui data kategori sampah (Mendukung Format Pipa & Redirect ke Tabel UI)
     */
    /**
     * Memperbarui data kategori sampah (Preserve Format Pipa & Direct Update)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $sampah = KategoriSampah::findOrFail($id);

        $kategoriUtama = 'UMUM';
        $namaJenis = trim($request->nama);
        $hargaBeli = (int) $request->harga_beli;

        // Jika data lama berformat "KATEGORI | NAMA | HARGA"
        if (str_contains($sampah->nama, '|')) {
            $pecah = explode('|', $sampah->nama);
            if (isset($pecah[0])) {
                $kategoriUtama = strtoupper(trim($pecah[0]));
            }
            // Rakit kembali string pipa dengan HARGA BARU dari Web
            $namaBaru = "{$kategoriUtama} | {$namaJenis} | {$hargaBeli}";
        } else {
            // Jika data lama belum berformat pipa, buatkan format pipanya!
            $namaBaru = "UMUM | {$namaJenis} | {$hargaBeli}";
        }

        // Simpan ke database
        $sampah->nama = $namaBaru;
        
        // Simpan juga ke kolom fisik jika kolomnya tersedia di database
        if (\Schema::hasColumn('kategori_sampah', 'harga_beli')) {
            $sampah->harga_beli = $hargaBeli;
        }
        if (\Schema::hasColumn('kategori_sampah', 'harga_per_kg')) {
            $sampah->harga_per_kg = $hargaBeli;
        }
        if (\Schema::hasColumn('kategori_sampah', 'harga')) {
            $sampah->harga = $hargaBeli;
        }

        $sampah->save();

        return redirect('/sampah-ui')->with('success', 'Data jenis & harga sampah berhasil diperbarui!');
    }

    /**
     * Menghapus kategori sampah
     */
    public function destroy($id)
    {
        $kategori = KategoriSampah::findOrFail($id);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori sampah berhasil dihapus.');
    }

    /**
     * MENYAMBUNGKAN KE HALAMAN KATALOG HARGA (Sisi Nasabah/Publik)
     */
    public function katalog()
    {
        $kategori = KategoriSampah::all();
        $katalogSampah = [];

        foreach ($kategori as $item) {
            $pecahData = explode('|', $item->nama);

            if (count($pecahData) >= 3) {
                $katalogSampah[] = [
                    'kategori' => strtoupper(trim($pecahData[0])), 
                    'nama'     => trim($pecahData[1]),             
                    'harga'    => (int) trim($pecahData[2])        
                ];
            } else {
                // Fallback jika ada data lama yang berformat normal
                $katalogSampah[] = [
                    'kategori' => 'UMUM',
                    'nama'     => $item->nama,
                    'harga'    => 0
                ];
            }
        }

        return view('user.katalog', compact('katalogSampah')); 
    }
}