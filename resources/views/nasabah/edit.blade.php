<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nasabah - BANKSACI</title>
    <!-- Menggunakan Tailwind CSS v4 sesuai bawaan project Anda -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen">

    <!-- Flex container utama untuk membagi Sidebar dan Konten Utama secara berdampingan -->
    <div class="flex">
        
        <!-- ======================================================== -->
        <!-- SIDEBAR UTAMA STAF (100% KONSISTEN) -->
        <!-- ======================================================== -->
        <aside class="w-68 bg-[#004e38] text-white min-h-screen p-5 flex flex-col justify-between hidden md:flex shrink-0 select-none">
            <div>
                <!-- Logo & Brand (Ikon Tunas Daun Sesuai Gambar) -->
                <div class="flex items-center gap-3 mb-8 px-2 pt-2">
                    <i class="fas fa-seedling text-[#52d69b] text-2xl"></i>
                    <span class="font-bold text-xl tracking-wider text-white">BANK<span class="text-[#52d69b]">SACI</span></span>
                </div>
                
                <!-- Navigasi Menu -->
                <nav class="space-y-2">
                    <a href="/dashboard-ui" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-300 hover:bg-[#005d43] hover:text-white transition-all duration-200">
                        <i class="fas fa-chart-pie w-5 text-lg text-center opacity-80"></i> 
                        <span>Dashboard</span>
                    </a>
                    
                    <!-- MENU AKTIF: Data Nasabah -->
                    <a href="/nasabah-ui" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium bg-[#005d43] text-white shadow-inner transition-all duration-200">
                        <i class="fas fa-users w-5 text-lg text-center"></i> 
                        <span>Data Nasabah</span>
                    </a>
                    
                    <a href="/sampah-ui" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-300 hover:bg-[#005d43] hover:text-white transition-all duration-200">
                        <i class="fas fa-boxes w-5 text-lg text-center opacity-80"></i> 
                        <span>Kategori Sampah</span>
                    </a>
                    
                    <a href="/setoran-ui" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-300 hover:bg-[#005d43] hover:text-white transition-all duration-200">
                        <i class="fas fa-arrow-down w-5 text-lg text-center opacity-80"></i> 
                        <span>Setoran Sampah</span>
                    </a>
                    
                    <a href="/penarikan-ui" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-300 hover:bg-[#005d43] hover:text-white transition-all duration-200">
                        <i class="fas fa-money-bill-wave w-5 text-lg text-center opacity-80"></i> 
                        <span>Penarikan Saldo</span>
                    </a>
                </nav>
            </div>
            
            <!-- Bagian Bawah: Web Utama & Logout -->
            <div class="pt-4 border-t border-[#005d43] space-y-2 mb-2">
                <a href="/" target="_blank" class="flex items-center gap-3 text-[#52d69b] hover:text-white text-base px-4 py-2 rounded-lg transition-colors font-medium">
                    <i class="fas fa-globe w-5 text-center"></i> 
                    <span>Lihat Web Utama</span>
                </a>
                
                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 text-[#ffa3a3] hover:text-red-300 text-base px-4 py-2 rounded-lg transition-colors cursor-pointer font-medium text-left bg-transparent border-none outline-none">
                        <i class="fas fa-sign-out-alt w-5 text-center rotate-180"></i> 
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ======================================================== -->
        <!-- KONTEN UTAMA -->
        <!-- ======================================================== -->
        <main class="flex-1 p-4 md:p-8 min-h-screen overflow-y-auto">
            <div class="max-w-3xl mx-auto space-y-6">
                
                <!-- Topbar -->
                <header class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-xs border border-gray-100">
                    <div class="text-sm text-gray-500 font-medium">
                        <span class="text-gray-400">Dashboard</span> / <a href="/nasabah-ui" class="text-gray-400 hover:text-gray-600">Data Nasabah</a> / <span class="text-gray-700 font-semibold">Edit Data</span>
                    </div>
                    <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-full border border-gray-100">
                        <div class="w-8 h-8 bg-[#00a877] text-white rounded-full flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name ?? 'Admin Desa' }}</span>
                    </div>
                </header>

                <!-- Judul Halaman -->
                <div class="flex flex-col gap-1">
                    <h1 class="text-2xl font-bold text-gray-800">Ubah Data Nasabah</h1>
                    <p class="text-sm text-gray-500">Perbarui profil informasi dan data tempat tinggal nasabah.</p>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 md:p-8">
                    <form action="{{ route('nasabah-ui.update', $nasabah->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Kode Nasabah (Read-only) -->
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">ID / No. Rekening</label>
                            <div class="bg-gray-50 text-[#00a877] font-semibold px-4 py-3 rounded-xl border border-gray-100 select-all cursor-not-allowed">
                                {{ $nasabah->kode_nasabah ?? 'BS-XXXX' }}
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" 
                                class="w-full bg-slate-50/50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00a877] focus:ring-1 focus:ring-[#00a877] transition-all font-medium text-gray-800 @error('nama') border-red-500 @enderror" 
                                value="{{ old('nama', $nasabah->nama) }}" required>
                            @error('nama')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No. HP -->
                        <div>
                            <label for="no_hp" class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">No. Handphone / WA</label>
                            <input type="text" name="no_hp" id="no_hp" 
                                class="w-full bg-slate-50/50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00a877] focus:ring-1 focus:ring-[#00a877] transition-all font-medium text-gray-800 @error('no_hp') border-red-500 @enderror" 
                                value="{{ old('no_hp', $nasabah->no_hp) }}" required>
                            @error('no_hp')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat Rumah -->
                        <div>
                            <label for="alamat" class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Alamat Rumah</label>
                            <textarea name="alamat" id="alamat" rows="3" 
                                class="w-full bg-slate-50/50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00a877] focus:ring-1 focus:ring-[#00a877] transition-all font-medium text-gray-800 @error('alamat') border-red-500 @enderror" 
                                required>{{ old('alamat', $nasabah->alamat) }}</textarea>
                            @error('alamat')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- RT / RW Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="rt" class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Rukun Tetangga (RT)</label>
                                <input type="text" name="rt" id="rt" placeholder="Contoh: 03"
                                    class="w-full bg-slate-50/50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00a877] focus:ring-1 focus:ring-[#00a877] transition-all font-medium text-gray-800 @error('rt') border-red-500 @enderror" 
                                    value="{{ old('rt', $nasabah->rt) }}" required>
                                @error('rt')
                                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="rw" class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Rukun Warga (RW)</label>
                                <input type="text" name="rw" id="rw" placeholder="Contoh: 09"
                                    class="w-full bg-slate-50/50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00a877] focus:ring-1 focus:ring-[#00a877] transition-all font-medium text-gray-800 @error('rw') border-red-500 @enderror" 
                                    value="{{ old('rw', $nasabah->rw) }}" required>
                                @error('rw')
                                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Submit & Kembali -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="/nasabah-ui" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition text-sm font-semibold text-center cursor-pointer">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#00a877] text-white hover:bg-[#008f64] transition text-sm font-semibold shadow-xs cursor-pointer">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

</body>
</html>