<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\KategoriSampahController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController; // <-- 🔔 Tambahan Import

// Models
use App\Models\Nasabah;
use App\Models\Setoran; 
use App\Models\Penarikan;
use App\Models\KategoriSampah;

/*
|--------------------------------------------------------------------------
| Halaman Utama & Autentikasi Publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

// Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Fitur Lupa Password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    return back()->with('status', 'Link pemulihan kata sandi telah dikirim ke email Anda!');
})->name('password.email');


/*
|--------------------------------------------------------------------------
| 👑 GRUP 1: HANYA UNTUK ADMIN & PETUGAS (MANAJEMEN & UI STAF)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    
    // Rute Dashboard Internal
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware('role:admin')->name('dashboard.admin');
    Route::get('/petugas/dashboard', [DashboardController::class, 'index'])->middleware('role:petugas')->name('dashboard.petugas');
    Route::get('/dashboard-ui', [DashboardController::class, 'index'])->name('dashboard.staf');

    // 🚚 Fitur Request Penjemputan Sampah
    Route::get('/request-jemput', [DashboardController::class, 'requestJemputIndex'])->name('request-jemput.index');
    Route::post('/request-jemput/{id}', [DashboardController::class, 'requestJemputUpdate'])->name('request-jemput.update');

    // ♻️ Master Kategori & Jenis Sampah (Resource)
    Route::resource('kategori', KategoriSampahController::class);
    Route::resource('jenis', KategoriSampahController::class);

    // 👥 Manajemen Nasabah & Transaksi (Resource)
    Route::get('/nasabah/tambah', [NasabahController::class, 'create']);
    Route::resource('nasabah', NasabahController::class);
    Route::resource('setoran', SetoranController::class);
    
    // Rute Update Status Penarikan
    Route::patch('/penarikan/{id}/update-status', [PenarikanController::class, 'updateStatus'])->name('penarikan.update-status');
    Route::resource('penarikan', PenarikanController::class);

    // 🔑 Fitur Ganti Password Staf
    Route::get('/admin/ganti-password', [PasswordController::class, 'showGantiPasswordAdmin'])->name('password.admin.edit');
    Route::post('/admin/ganti-password', [PasswordController::class, 'updatePasswordAdmin'])->name('password.admin.update');

    // ----------------------------------------------------------------------
    // 👤 UI Data Nasabah (Custom Endpoints)
    // ----------------------------------------------------------------------
    Route::get('/nasabah-ui', [NasabahController::class, 'index'])->name('nasabah-ui.index');
    Route::get('/nasabah-ui/tambah', [NasabahController::class, 'create'])->name('nasabah-ui.create');
    Route::post('/nasabah-ui/tambah', [NasabahController::class, 'store'])->name('nasabah-ui.store');
    Route::get('/nasabah-ui/{nasabah}/edit', [NasabahController::class, 'edit'])->name('nasabah-ui.edit');
    Route::put('/nasabah-ui/{nasabah}', [NasabahController::class, 'update'])->name('nasabah-ui.update');
    Route::delete('/nasabah-ui/{nasabah}', [NasabahController::class, 'destroy'])->name('nasabah-ui.destroy');
    Route::get('/nasabah-ui/delete/{nasabah}', [NasabahController::class, 'destroy']); 

    // ----------------------------------------------------------------------
    // 🗑️ UI Master Sampah / Kategori
    // ----------------------------------------------------------------------
    Route::get('/sampah', [KategoriSampahController::class, 'index'])->name('sampah.index');
    Route::get('/sampah/create', [KategoriSampahController::class, 'create'])->name('sampah.create');
    Route::post('/sampah', [KategoriSampahController::class, 'store'])->name('sampah.store');
    
    Route::get('/sampah-ui', [KategoriSampahController::class, 'index'])->name('sampah-ui.index');
    Route::get('/sampah-ui/tambah', [KategoriSampahController::class, 'create'])->name('sampah-ui.create');
    Route::post('/sampah-ui/tambah', [KategoriSampahController::class, 'store'])->name('sampah-ui.store');
    Route::get('/sampah-ui/{id}/edit', [KategoriSampahController::class, 'edit'])->name('sampah-ui.edit');
    Route::put('/sampah-ui/{id}', [KategoriSampahController::class, 'update'])->name('sampah-ui.update');

    // ----------------------------------------------------------------------
    // 💰 UI Transaksi Setoran
    // ----------------------------------------------------------------------
    Route::get('/setoran-ui', function () {
        $setorans = Setoran::with('nasabah')->orderBy('created_at', 'desc')->get();
        return view('setoran.index', compact('setorans'));
    })->name('setoran-ui.index');

    Route::get('/setoran-ui/tambah', function () {
        $nasabahs = Nasabah::orderBy('nama', 'asc')->get();
        $jenisSampahs = KategoriSampah::orderBy('nama', 'asc')->get(); 
        return view('setoran.create', compact('nasabahs', 'jenisSampahs'));
    })->name('setoran-ui.create');

    // POS PEMBAHARUAN INPUT SETORAN UI
    Route::post('/setoran-ui/tambah', function (Request $req) {
        $req->validate([
            'nasabah_id' => 'required',
            'sampah_id'  => 'required', 
            'berat'      => 'required|numeric|min:0.1',
        ]);

        $sampahData = KategoriSampah::find($req->sampah_id);
        if (!$sampahData) { 
            return redirect()->back()->withErrors(['sampah_id' => 'Jenis sampah tidak ditemukan.'])->withInput(); 
        }

        // Peta Harga Acuan Resmi Dashboard User
        $hargaAcuanMap = [
            'kertas'  => 1500,
            'kardus'  => 1500,
            'plastik' => 3000,
            'pet'     => 3000,
            'bening'  => 3000,
            'besi'    => 4500,
            'seng'    => 4500,
            'logam'   => 12000,
            'tembaga' => 12000,
        ];

        // 1. Ambil harga dari Database jika bernilai positif
        $hargaPerKg = $sampahData->harga_per_kg ?? $sampahData->harga ?? 0;

        // 2. Ekstrak string jika nama berisi format pipa ("Kertas|Kardus|1500") atau cocokkan kata kunci
        $namaLower = strtolower($sampahData->nama);
        if (str_contains($namaLower, '|')) {
            $parts = explode('|', $namaLower);
            $hargaPerKg = (int) trim(end($parts));
        } elseif ($hargaPerKg <= 0) {
            foreach ($hargaAcuanMap as $key => $harga) {
                if (str_contains($namaLower, $key)) {
                    $hargaPerKg = $harga;
                    break;
                }
            }
        }

        // Fallback default jika tidak ada pencocokan
        if ($hargaPerKg <= 0) {
            $hargaPerKg = 1500;
        }

        $totalHarga = $hargaPerKg * $req->berat;

        DB::beginTransaction();
        try {
            // 1. Simpan Transaksi Utama Setoran (tabel: setoran)
            $setoranId = DB::table('setoran')->insertGetId([
                'nasabah_id'  => $req->nasabah_id,
                'user_id'     => auth()->id() ?? 1,
                'tanggal'     => now()->toDateString(),
                'total_berat' => $req->berat,
                'total_harga' => $totalHarga,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // 2. Simpan Detail Setoran ke tabel `setoran_detail`
            DB::table('setoran_detail')->insert([
                'setoran_id'      => $setoranId,
                'jenis_sampah_id' => $req->sampah_id,
                'berat'           => $req->berat,
                'harga'           => $hargaPerKg,
                'subtotal'        => $totalHarga,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 3. Update Saldo Nasabah
            $nasabah = Nasabah::find($req->nasabah_id);
            if ($nasabah) { 
                $nasabah->saldo += $totalHarga; 
                $nasabah->save(); 
            }

            DB::commit();

            return redirect('/setoran-ui')->with('success', 'Setoran sampah berhasil ditambahkan dengan kalkulasi harga Rp ' . number_format($totalHarga, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()])->withInput();
        }
    })->name('setoran-ui.store');

    // ----------------------------------------------------------------------
    // 💸 UI Transaksi Penarikan
    // ----------------------------------------------------------------------
    Route::get('/penarikan-ui', function () {
        $penarikans = Penarikan::with('nasabah')->orderBy('created_at', 'desc')->get();
        return view('penarikan.index', compact('penarikans'));
    })->name('penarikan-ui.index');

    Route::get('/penarikan-ui/tambah', function () {
        $nasabahs = Nasabah::orderBy('nama', 'asc')->get();
        return view('penarikan.create', compact('nasabahs'));
    })->name('penarikan-ui.create');

    Route::post('/penarikan-ui/tambah', function (Request $req) {
        $req->validate([
            'nasabah_id' => 'required',
            'nominal'    => 'required|numeric|min:1000',
        ]);
        
        $nasabah = Nasabah::find($req->nasabah_id);
        if (!$nasabah || $nasabah->saldo < $req->nominal) {
            return redirect()->back()->withErrors(['nominal' => 'Maaf, saldo nasabah tidak mencukupi!'])->withInput();
        }
        
        $penarikan = new Penarikan();
        $penarikan->nasabah_id = $req->nasabah_id;
        $penarikan->user_id    = auth()->id() ?? 1;
        $penarikan->jumlah     = $req->nominal; 
        $penarikan->tanggal    = now()->toDateString();
        $penarikan->status     = 'selesai'; 
        $penarikan->keterangan = 'Tarik tunai mandiri via UI'; 
        $penarikan->save();

        $nasabah->saldo -= $req->nominal;
        $nasabah->save();

        return redirect('/penarikan-ui')->with('success', 'Pencairan uang berhasil diproses!');
    })->name('penarikan-ui.store');

    // ----------------------------------------------------------------------
    // 📊 LAPORAN MENYELURUH & EKSPOR
    // ----------------------------------------------------------------------
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan-menyeluruh', [LaporanController::class, 'index'])->name('laporan.index');
    
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf']);
    Route::get('/laporan-menyeluruh/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel']);
    Route::get('/laporan-menyeluruh/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
});


/*
|--------------------------------------------------------------------------
| 👤 GRUP 2: KHUSUS WARGA / NASABAH / USER
|--------------------------------------------------------------------------
*/
// Mengizinkan 'nasabah' maupun 'user' agar tidak tertahan middleware saat login
Route::middleware(['auth', 'role:nasabah,user'])->group(function () {
    
    Route::get('/dashboard-user', [UserDashboardController::class, 'index'])->name('user.dashboard-alternatif');
    Route::get('/user-dashboard-ui', [UserDashboardController::class, 'index'])->name('dashboard.user');
    
    Route::get('/user/tabungan', [UserDashboardController::class, 'tabungan'])->name('user.tabungan');
    Route::get('/user/mutasi', [UserDashboardController::class, 'mutasi'])->name('user.mutasi');
    Route::post('/user/tarik-saldo', [UserDashboardController::class, 'tarikSaldo'])->name('user.tarik_saldo');
    Route::get('/user/ganti-password', [PasswordController::class, 'showGantiPasswordUser'])->name('password.user.edit');
    Route::post('/user/ganti-password', [PasswordController::class, 'updatePasswordUser'])->name('password.user.update');

    // Katalog Sampah Sisi Warga
    Route::get('/katalog-sampah', [KategoriSampahController::class, 'katalog'])->name('user.katalog');

    // 🔔 Fitur Lonceng Notifikasi (Menandai Semua Notifikasi Dibaca)
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});