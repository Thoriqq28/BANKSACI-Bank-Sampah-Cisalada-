<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Setoran - BANKSACI</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js untuk Responsif Mobile Sidebar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f4f7f6] font-sans antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- ==================== SIDEBAR UTAMA STAF ==================== -->
    <aside class="w-68 bg-[#004e38] text-white h-screen p-5 flex flex-col justify-between fixed md:relative z-50 shrink-0 select-none overflow-x-hidden transform transition-transform duration-300 ease-in-out md:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <!-- Bagian Atas: Logo & Menus -->
        <div class="flex flex-col h-full overflow-y-auto overflow-x-hidden">
            
            <!-- Logo & Brand Header -->
            <div class="flex items-center justify-between mb-8 px-2 pt-2">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0 sidebar-logo">
                    <span class="font-bold text-xl tracking-wider text-white">
                        BANK<span class="text-[#52d69b]">SACI</span>
                    </span>
                </div>
                <!-- Tombol Close Sidebar (Khusus Layar HP) -->
                <button @click="sidebarOpen = false" class="md:hidden text-emerald-200 hover:text-white cursor-pointer">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Navigasi Menu Dinamis -->
            <nav class="space-y-2">
                
                <!-- Dashboard -->
                <a href="/dashboard-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('dashboard-ui*') ? 'active' : '' }}">
                    @if(request()->is('dashboard-ui*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    @endif
                    <i class="fas fa-th-large w-5 text-center text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Data Nasabah -->
                <a href="/nasabah-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('nasabah-ui*') ? 'active' : '' }}">
                    @if(request()->is('nasabah-ui*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    @endif
                    <i class="fas fa-users w-5 text-center text-lg"></i>
                    <span>Data Nasabah</span>
                </a>

                <!-- Kategori Sampah -->
                <a href="/sampah-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('sampah*') ? 'active' : '' }}">
                    @if(request()->is('sampah*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    @endif
                    <i class="fas fa-box w-5 text-center text-lg"></i>
                    <span>Kategori Sampah</span>
                </a>

                <!-- Setoran Sampah (Halaman Aktif Saat Ini) -->
                <a href="/setoran-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium active">
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    <i class="fas fa-arrow-down w-5 text-center text-lg icon-arrow"></i>
                    <span>Setoran Sampah</span>
                </a>

                <!-- Penarikan Saldo -->
                <a href="/penarikan-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('penarikan-ui*') ? 'active' : '' }}">
                    @if(request()->is('penarikan-ui*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    @endif
                    <i class="fas fa-money-bill-wave w-5 text-center text-lg"></i>
                    <span>Penarikan Saldo</span>
                </a>

                <!-- Laporan Menyeluruh -->
                <a href="/laporan-menyeluruh" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('laporan*') ? 'active' : '' }}">
                    @if(request()->is('laporan*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    @endif
                    <i class="fas fa-file-invoice-dollar w-5 text-center text-lg"></i>
                    <span>Laporan Menyeluruh</span>
                </a>

            </nav>

            <!-- Bagian Bawah: Web Utama & Logout -->
            <div class="mt-auto pt-4 border-t border-[#005d43] space-y-2 pb-2">
                <a href="/" target="_blank" class="sidebar-item-web flex items-center gap-3.5 text-[#52d69b] text-base px-4 py-2.5 rounded-xl font-medium">
                    <i class="fas fa-globe w-5 text-center text-lg icon-globe"></i> 
                    <span>Lihat Web Utama</span>
                </a>
                
                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="sidebar-item-logout w-full flex items-center gap-3.5 text-[#ffa3a3] text-base px-4 py-2.5 rounded-xl font-medium text-left cursor-pointer">
                        <i class="fas fa-sign-out-alt w-5 text-center rotate-180 text-lg icon-logout"></i> 
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Overlay Transparan untuk Tampilan Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs md:hidden"
         x-transition></div>

    <!-- ==================== KONTEN UTAMA ==================== -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Breadcrumb Navigation Bar -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3 text-sm text-gray-500 font-medium truncate">
                    <!-- Tombol Hamburger Mobile -->
                    <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-[#00895c] p-1.5 rounded-lg border border-gray-200 bg-gray-50 cursor-pointer shrink-0">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex items-center gap-2 truncate">
                        <a href="/dashboard-ui" class="hover:text-[#004e38]">Dashboard</a> 
                        <span class="text-gray-300">/</span> 
                        <a href="/setoran-ui" class="hover:text-[#004e38]">Setoran Sampah</a> 
                        <span class="text-gray-300">/</span> 
                        <span class="text-gray-800 font-bold truncate">Input Baru</span>
                    </div>
                </div>
            </div>

            <!-- Pesan Alert Jika Validasi Error -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-xs md:text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card Timbangan Sampah -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Form Header -->
                <div class="bg-[#00895c] text-white p-5 md:p-6 flex justify-between items-center gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold flex items-center gap-2">
                            <i class="fas fa-weight-hanging"></i> Form Timbangan Sampah
                        </h2>
                        <p class="text-xs text-emerald-100 mt-1">Pastikan timbangan akurat sebelum menyimpan data.</p>
                    </div>
                    <span class="bg-[#00704b] text-[10px] md:text-xs px-3 py-1.5 rounded-full font-medium tracking-wide whitespace-nowrap">
                        Mode Database
                    </span>
                </div>

                <!-- Form Body -->
                <form action="/setoran-ui/tambah" method="POST" class="p-5 md:p-6 space-y-5">
                    @csrf

                    <!-- 1. Pilih Nasabah -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fas fa-user text-gray-400"></i> Pilih Nasabah (Warga)
                        </label>
                        <select name="nasabah_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs md:text-sm text-gray-700 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition-all" required>
                            <option value="">-- Cari Nama Warga --</option>
                            @foreach($nasabahs as $nasabah)
                                <option value="{{ $nasabah->id }}" {{ old('nasabah_id') == $nasabah->id ? 'selected' : '' }}>
                                    {{ $nasabah->kode_nasabah ?? ('NSB-' . sprintf('%03d', $nasabah->id)) }} - {{ $nasabah->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Grid Jenis Sampah & Berat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="fas fa-recycle text-gray-400"></i> Jenis Sampah Disetor
                            </label>
                            <select id="sampah_select" name="sampah_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs md:text-sm text-gray-700 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition-all" required>
                                <option value="">-- Pilih Jenis Sampah --</option>
                                @foreach($jenisSampahs as $sampah)
                                    @php
                                        // Peta harga default jika database kosong
                                        $hargaAcuanMap = [
                                            'kertas'  => 1500, 'kardus'  => 1500,
                                            'plastik' => 3000, 'pet'     => 3000, 'bening' => 3000,
                                            'besi'    => 4500, 'seng'    => 4500,
                                            'logam'   => 12000, 'tembaga' => 12000,
                                        ];

                                        // Prioritas 1: Ambil harga langsung dari field database
                                        $hargaBeli = $sampah->harga_per_kg ?? $sampah->harga ?? 0;
                                        $namaTampil = $sampah->nama;

                                        // Prioritas 2: Deteksi jika menggunakan pemisah pipe (|)
                                        if (str_contains(strtolower($sampah->nama), '|')) {
                                            $pecah = explode('|', $sampah->nama);
                                            $namaTampil = count($pecah) >= 2 ? trim($pecah[1]) : $sampah->nama;
                                            $hargaBeli = count($pecah) >= 3 ? (int) trim($pecah[2]) : $hargaBeli;
                                        }

                                        // Prioritas 3: Pencocokan kata kunci acuan jika harga masih 0
                                        if ($hargaBeli <= 0) {
                                            $namaLower = strtolower($sampah->nama);
                                            foreach ($hargaAcuanMap as $key => $h) {
                                                if (str_contains($namaLower, $key)) {
                                                    $hargaBeli = $h;
                                                    break;
                                                }
                                            }
                                        }

                                        // Fallback default
                                        if ($hargaBeli <= 0) {
                                            $hargaBeli = 1500;
                                        }
                                    @endphp
                                    <option value="{{ $sampah->id }}" data-harga="{{ $hargaBeli }}" {{ old('sampah_id') == $sampah->id ? 'selected' : '' }}>
                                        {{ $namaTampil }} (Rp {{ number_format($hargaBeli, 0, ',', '.') }}/kg)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="fas fa-balance-scale text-gray-400"></i> Berat Timbangan
                            </label>
                            <div class="relative">
                                <input id="berat_input" type="number" step="0.1" min="0.1" name="berat" value="{{ old('berat') }}" placeholder="0.0" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs md:text-sm text-gray-700 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition-all pr-12" required>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs md:text-sm font-semibold text-gray-400">Kg</span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Box Estimasi Saldo Real-Time -->
                    <div class="bg-[#e8f8f2] rounded-xl p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                        <div>
                            <p class="text-xs font-semibold text-[#00895c]">Estimasi Saldo Diterima</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span id="label_harga">Rp 0</span> x <span id="label_berat">0</span> kg
                            </p>
                        </div>
                        <div id="total_kalkulasi" class="text-xl md:text-2xl font-bold text-[#00895c]">
                            Rp 0
                        </div>
                    </div>

                    <!-- 4. Tombol Aksi -->
                    <div class="flex justify-end items-center gap-3 pt-3 border-t border-gray-100">
                        <a href="/setoran-ui" class="px-5 py-2.5 rounded-xl text-xs md:text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs md:text-sm font-semibold text-white bg-[#00895c] hover:bg-[#00704b] transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-check"></i> Simpan Setoran
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </main>

    <!-- ==================== STYLING SIDEBAR ==================== -->
    <style>
        /* Menghilangkan Scrollbar Horizontal Browser */
        aside, aside div {
            overflow-x: hidden !important;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        aside::-webkit-scrollbar, aside div::-webkit-scrollbar {
            display: none;
        }

        /* Item Sidebar Navigasi */
        .sidebar-item {
            color: #d1d5db;
            transition: all 0.25s ease-in-out !important;
        }
        
        /* State Aktif */
        .sidebar-item.active {
            background-color: #005e44 !important;
            color: #ffffff !important;
            transform: translateX(8px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Animasi Hover Item Menu */
        .sidebar-item:hover {
            background-color: rgba(0, 94, 68, 0.6) !important;
            color: #ffffff !important;
            transform: translateX(8px) !important;
        }

        /* Animasi Ikon */
        .sidebar-item i {
            transition: transform 0.25s ease-in-out, color 0.25s ease-in-out !important;
        }
        .sidebar-item:hover i {
            transform: scale(1.25) !important;
            color: #52d69b !important;
        }
        .sidebar-item:hover i.icon-arrow {
            transform: scale(1.25) translateY(3px) !important;
        }

        /* Web Utama & Logout Button Hover */
        .sidebar-item-web, .sidebar-item-logout {
            transition: all 0.25s ease-in-out !important;
        }
        .sidebar-item-web:hover {
            background-color: rgba(0, 94, 68, 0.4) !important;
            color: #ffffff !important;
            transform: translateX(6px) !important;
        }
        .sidebar-item-web:hover i.icon-globe {
            transform: rotate(45deg) scale(1.2) !important;
        }
        .sidebar-item-logout:hover {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #fca5a5 !important;
            transform: translateX(6px) !important;
        }

        /* Animasi Logo Header */
        .sidebar-logo {
            transition: transform 0.3s ease-in-out !important;
        }
        .group:hover .sidebar-logo {
            transform: scale(1.15) rotate(-10deg) !important;
        }
    </style>

    <!-- ==================== JAVASCRIPT KALKULASI REAL-TIME ==================== -->
    <script>
        const sampahSelect = document.getElementById('sampah_select');
        const beratInput = document.getElementById('berat_input');
        const labelHarga = document.getElementById('label_harga');
        const labelBerat = document.getElementById('label_berat');
        const totalKalkulasi = document.getElementById('total_kalkulasi');

        function hitungTotal() {
            const selectedOption = sampahSelect.options[sampahSelect.selectedIndex];
            if (!selectedOption || selectedOption.value === "") {
                labelHarga.innerText = 'Rp 0';
                labelBerat.innerText = '0';
                totalKalkulasi.innerText = 'Rp 0';
                return;
            }
            
            const harga = parseInt(selectedOption.getAttribute('data-harga')) || 0;
            const berat = parseFloat(beratInput.value) || 0;
            const total = harga * berat;

            labelHarga.innerText = 'Rp ' + harga.toLocaleString('id-ID');
            labelBerat.innerText = berat;
            totalKalkulasi.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        sampahSelect.addEventListener('change', hitungTotal);
        beratInput.addEventListener('input', hitungTotal);
        
        // Menangani auto-calculate saat reload/kembali dari error validation
        window.addEventListener('DOMContentLoaded', hitungTotal);
    </script>

</body>
</html>