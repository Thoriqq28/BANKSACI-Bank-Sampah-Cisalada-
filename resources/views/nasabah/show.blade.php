<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nasabah - BANKSACI</title>
    <!-- Menggunakan Tailwind CSS v4 sesuai bawaan project Anda -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen">

    <div class="flex">
        <!-- ======================================================== -->
        <!-- SIDEBAR UTAMA STAF (KONSISTEN 100% DENGAN INDEX) -->
        <!-- ======================================================== -->
        <aside class="w-68 bg-[#004e38] text-white min-h-screen p-5 flex flex-col justify-between hidden md:flex shrink-0 select-none">
            <div>
                <div class="flex items-center gap-3 mb-8 px-2 pt-2">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0">
                    <span class="font-bold text-xl tracking-wider text-white">BANK<span class="text-[#52d69b]">SACI</span></span>
                </div>
                
                <nav class="space-y-2">
                    <a href="/dashboard-ui" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-300 hover:bg-[#005d43] hover:text-white transition-all duration-200">
                        <i class="fas fa-chart-pie w-5 text-lg text-center opacity-80"></i> 
                        <span>Dashboard</span>
                    </a>
                    
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
                        <span class="text-gray-400">Dashboard</span> / <a href="/nasabah-ui" class="text-gray-400 hover:text-gray-600">Data Nasabah</a> / <span class="text-gray-700 font-semibold">Detail Profil</span>
                    </div>
                    <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-full border border-gray-100">
                        <div class="w-8 h-8 bg-[#00a877] text-white rounded-full flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name ?? 'Admin Desa' }}</span>
                    </div>
                </header>

                <!-- Tombol Kembali -->
                <div>
                    <a href="/nasabah-ui" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                        <i class="fas fa-arrow-left"></i> Kembali ke Data Nasabah
                    </a>
                </div>

                <!-- Card Detail Profil -->
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                    <!-- Header Profil (Background Gradasi Hijau Lembut khas BANKSACI) -->
                    <div class="p-6 md:p-8 bg-gradient-to-r from-[#004e38]/10 to-transparent border-b border-gray-100 flex items-center gap-4">
                        <div class="w-16 h-16 bg-[#00a877] text-white rounded-full flex items-center justify-center text-3xl font-bold shadow-xs">
                            {{ strtoupper(substr($nasabah->nama, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $nasabah->nama }}</h2>
                            <p class="text-sm text-[#00a877] font-semibold tracking-wider uppercase mt-0.5">
                                {{ $nasabah->kode_nasabah ?? 'BS-XXXX' }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">
                        <!-- Grid Informasi Data -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Nomor Handphone / WA</span>
                                <span class="text-gray-800 font-semibold text-sm">{{ $nasabah->no_hp ?? '-' }}</span>
                            </div>

                            <!-- SALDO TABUNGAN SAAT INI -->
<div>
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
        SALDO TABUNGAN SAAT INI
    </p>

    @php
        // Ambil saldo langsung dari object nasabah (atau dari properti saldo/saldo_tabungan)
        $saldoValid = $nasabah->saldo 
                   ?? $nasabah->saldo_tabungan 
                   ?? $nasabah->total_saldo 
                   ?? 0;
    @endphp

    <p class="text-xl font-extrabold text-emerald-600">
        Rp {{ number_format($saldoValid, 0, ',', '.') }}
    </p>
</div>

                            <div class="md:col-span-2">
                                <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Alamat Rumah</span>
                                <span class="text-gray-800 font-semibold text-sm block">{{ $nasabah->alamat }}</span>
                                <span class="text-xs text-gray-400 mt-1.5 block">
                                    RT {{ $nasabah->rt ?? '00' }} / RW {{ $nasabah->rw ?? '00' }}
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Edit di Bagian Bawah -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="/nasabah/{{ $nasabah->id }}/edit" class="px-5 py-2.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                                <i class="fas fa-edit"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>