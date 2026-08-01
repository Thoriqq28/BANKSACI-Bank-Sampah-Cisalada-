<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisSampahController extends Controller
{
    /**
 * Menampilkan daftar jenis sampah
 */
public function index()
{
    $jenisSampahs = JenisSampah::with('kategori')->get();
    
    // UBAH 'jenis.index' MENJADI 'sampah.index'
    return view('sampah.index', compact('jenisSampahs'));
}
    /**
     * Menampilkan form create (mengarahkan ke view sampah.create dengan variabel $nasabahs)
     */
   public function create()
{
    $kategoris = KategoriSampah::all();
    
    // Arahkan ke file jenis/create.blade.php yang baru dibuat
    return view('jenis.create', compact('kategoris'));
}

    /**
     * Menyimpan data jenis sampah baru
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

    $kategori = new \App\Models\KategoriSampah();
    $kategori->nama = "{$kategoriUtama} | {$namaJenis} | {$hargaBeli}";
    $kategori->save();

    return redirect('/sampah-ui')->with('success', 'Jenis sampah berhasil ditambahkan!');
}

    /**
     * Menampilkan form edit
     */
    public function edit(JenisSampah $jeni)
    {
        $kategoris = KategoriSampah::all();
        return view('jenis.edit', compact('jeni', 'kategoris'));
    }

    /**
     * Memperbarui data jenis sampah
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama'       => 'required|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $jenisSampah = JenisSampah::findOrFail($id);

        // Update data
        $jenisSampah->update([
            'nama'       => $request->nama,
            'harga_beli' => $request->harga_beli,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Data jenis sampah dan harga berhasil diperbarui!');
    }

    /**
     * Menghapus data jenis sampah
     */
    public function destroy(JenisSampah $jeni)
    {
        $jeni->delete();
        return redirect()->route('jenis.index')->with('success', 'Jenis Sampah berhasil dihapus.');
    }
}