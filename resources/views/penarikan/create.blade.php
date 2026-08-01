<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penarikan - BANKSACI</title>
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

                <!-- Setoran Sampah -->
                <a href="/setoran-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('setoran-ui*') ? 'active' : '' }}">
                    @if(request()->is('setoran-ui*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                    @endif
                    <i class="fas fa-arrow-down w-5 text-center text-lg icon-arrow"></i>
                    <span>Setoran Sampah</span>
                </a>

                <!-- Penarikan Saldo (Halaman Aktif Saat Ini) -->
                <a href="/penarikan-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium active">
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
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
                        <a href="/penarikan-ui" class="hover:text-[#004e38]">Penarikan Saldo</a> 
                        <span class="text-gray-300">/</span> 
                        <span class="text-gray-800 font-bold truncate">Pencairan Tunai</span>
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

            <!-- Form Card Penarikan Saldo -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Form Header -->
                <div class="bg-[#00895c] text-white p-5 md:p-6 flex justify-between items-center gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold flex items-center gap-2">
                            <i class="fas fa-hand-holding-usd"></i> Form Pencairan Saldo
                        </h2>
                        <p class="text-xs text-emerald-100 mt-1">Konversi saldo tabungan sampah warga menjadi uang tunai secara real-time.</p>
                    </div>
                    <span class="bg-[#00704b] text-[10px] md:text-xs px-3 py-1.5 rounded-full font-medium tracking-wide whitespace-nowrap">
                        Mode Database
                    </span>
                </div>

                <!-- Form Body -->
                <form action="/penarikan-ui/tambah" method="POST" class="p-5 md:p-6 space-y-5">
                    @csrf

                    <!-- 1. Pilih Nasabah -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fas fa-user text-gray-400"></i> Pilih Nasabah (Warga)
                        </label>
                        <select id="nasabah_select" name="nasabah_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs md:text-sm text-gray-700 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition-all" required>
                            <option value="">-- Pilih Warga --</option>
                            @foreach($nasabahs as $nasabah)
                                @php
                                    $saldoAktif = $nasabah->saldo_aktif ?? $nasabah->saldo ?? 0;
                                @endphp
                                <option value="{{ $nasabah->id }}" data-saldo="{{ $saldoAktif }}" {{ old('nasabah_id') == $nasabah->id ? 'selected' : '' }}>
                                    {{ $nasabah->kode_nasabah ?? ('NSB-' . sprintf('%03d', $nasabah->id)) }} - {{ $nasabah->nama }} (Saldo: Rp {{ number_format($saldoAktif, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Nominal Penarikan -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <i class="fas fa-wallet text-gray-400"></i> Nominal Yang Ditarik
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs md:text-sm font-semibold text-gray-400">Rp</span>
                            <input id="jumlah_input" type="number" min="1000" step="1000" name="jumlah" value="{{ old('jumlah') }}" placeholder="0" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs md:text-sm text-gray-700 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition-all pl-12" required>
                        </div>
                    </div>

                    <!-- 3. Box Estimasi Sisa Saldo Real-Time -->
                    <div class="bg-[#e8f8f2] rounded-xl p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                        <div>
                            <p class="text-xs font-semibold text-[#00895c]">Informasi Rekening Nasabah</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Saldo Awal: <span id="label_saldo_awal" class="font-semibold text-gray-700">Rp 0</span>
                            </p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Estimasi Sisa Saldo</p>
                            <div id="total_sisa_saldo" class="text-xl md:text-2xl font-bold text-[#00895c]">
                                Rp 0
                            </div>
                        </div>
                    </div>

                    <!-- Warning Pesan Jika Nominal Melebihi Saldo -->
                    <div id="warning_saldo" class="hidden bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-xs flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-amber-500 text-sm shrink-0"></i>
                        <span>Nominal penarikan melebihi saldo aktif yang dimiliki nasabah!</span>
                    </div>

                    <!-- 4. Tombol Aksi -->
                    <div class="flex justify-end items-center gap-3 pt-3 border-t border-gray-100">
                        <a href="/penarikan-ui" class="px-5 py-2.5 rounded-xl text-xs md:text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Batal
                        </a>
                        <button type="submit" id="btn_submit" class="px-6 py-2.5 rounded-xl text-xs md:text-sm font-semibold text-white bg-[#00895c] hover:bg-[#00704b] transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-check"></i> Simpan Penarikan
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

    <!-- ==================== JAVASCRIPT KALKULASI & VALIDASI REAL-TIME ==================== -->
    <script>
        const nasabahSelect = document.getElementById('nasabah_select');
        const jumlahInput = document.getElementById('jumlah_input');
        const labelSaldoAwal = document.getElementById('label_saldo_awal');
        const totalSisaSaldo = document.getElementById('total_sisa_saldo');
        const warningSaldo = document.getElementById('warning_saldo');
        const btnSubmit = document.getElementById('btn_submit');

        function hitungSisaSaldo() {
            const selectedOption = nasabahSelect.options[nasabahSelect.selectedIndex];
            if (!selectedOption || selectedOption.value === "") {
                labelSaldoAwal.innerText = 'Rp 0';
                totalSisaSaldo.innerText = 'Rp 0';
                totalSisaSaldo.className = 'text-xl md:text-2xl font-bold text-[#00895c]';
                warningSaldo.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }
            
            const saldoAwal = parseInt(selectedOption.getAttribute('data-saldo')) || 0;
            const nominalTarik = parseInt(jumlahInput.value) || 0;
            const sisaSaldo = saldoAwal - nominalTarik;

            labelSaldoAwal.innerText = 'Rp ' + saldoAwal.toLocaleString('id-ID');
            totalSisaSaldo.innerText = 'Rp ' + sisaSaldo.toLocaleString('id-ID');

            // Cek jika penarikan melebihi saldo aktif
            if (sisaSaldo < 0) {
                totalSisaSaldo.className = 'text-xl md:text-2xl font-bold text-rose-600';
                warningSaldo.classList.remove('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                totalSisaSaldo.className = 'text-xl md:text-2xl font-bold text-[#00895c]';
                warningSaldo.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        nasabahSelect.addEventListener('change', hitungSisaSaldo);
        jumlahInput.addEventListener('input', hitungSisaSaldo);
        
        // Menangani auto-calculate saat reload/kembali dari error validation
        window.addEventListener('DOMContentLoaded', hitungSisaSaldo);
    </script>

</body>
</html>