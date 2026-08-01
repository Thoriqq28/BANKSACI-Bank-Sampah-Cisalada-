<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Auto Refresh halaman setiap 10 detik agar status penarikan dari Admin terupdate secara Real-time -->
    <meta http-equiv="refresh" content="10">
    <title>Mutasi & Histori Lengkap - BANKSACI</title>
    <!-- Tailwind CSS (Play CDN v4) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 font-sans" x-data="{ openTarikModal: false, jenisPenarikan: 'cash' }">

    <!-- NAVBAR PREMIUM KONSISTEN -->
    <nav class="bg-emerald-900 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0">
                <span class="font-bold text-xl tracking-wider text-white">BANK<span class="text-[#52d69b]">SACI</span></span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase ml-2 bg-emerald-800 text-emerald-300 border border-emerald-700">
                    {{ $nasabah->tingkatan ?? 'WARGA PEDULI' }}
                </span>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold">{{ $nasabah->nama ?? Auth::user()->name }}</p>
                    <p class="text-xs text-emerald-300">ID: {{ $nasabah->kode_nasabah ?? 'BS-0001' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-emerald-800 hover:bg-red-700 p-2.5 rounded-xl transition shadow-sm text-sm flex items-center gap-1 cursor-pointer">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="max-w-6xl mx-auto px-4 py-6 space-y-6">

        <!-- ALERT NOTIFIKASI BACKEND (FLASH MESSAGE) -->
        @if(session('success'))
            <div class="w-full bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-xs flex items-center gap-2 shadow-xs">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="w-full bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-xs flex items-center gap-2 shadow-xs">
                <i class="fas fa-exclamation-circle text-rose-600 text-sm"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- HEADER BANNER -->
        <div class="w-full bg-white rounded-2xl border border-slate-100 shadow-xs p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    Histori Mutasi Lengkap 📑
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Memantau seluruh jejak riwayat masuk dan keluarnya saldo e-wallet Anda secara transparan.
                </p>
            </div>
            <a href="{{ route('user.tabungan') }}" class="border border-emerald-200 hover:border-emerald-600 bg-emerald-50/30 hover:bg-emerald-50 text-emerald-700 font-bold text-xs py-2.5 px-4 rounded-xl transition flex items-center gap-2 cursor-pointer">
                <i class="fas fa-arrow-left"></i> Kembali ke Dompet
            </a>
        </div>

        <!-- SUB-NAVIGATION BAR -->
        <div class="w-full bg-white rounded-2xl border border-slate-100 shadow-xs px-6 py-0 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-6 md:gap-8 w-full md:w-auto overflow-x-auto whitespace-nowrap hide-scrollbar">
                <a href="{{ route('dashboard.user') }}" 
                   class="flex items-center gap-2 py-4 text-xs md:text-sm transition-all duration-200 border-b-2 font-semibold text-slate-400 border-transparent hover:text-slate-600 shrink-0">
                    <i class="fas fa-info-circle text-sm"></i>
                    <span>Informasi Desa</span>
                </a>
                <a href="{{ route('user.tabungan') }}" 
                   class="flex items-center gap-2 py-4 pr-2 text-xs md:text-sm transition-all duration-200 border-b-2 font-black text-emerald-600 border-emerald-600 shrink-0">
                    <i class="fas fa-wallet text-sm"></i>
                    <span>E-Wallet & Tabungan</span>
                </a>
            </div>

            <!-- FITUR AKSI LANGSUNG -->
            <div class="flex items-center gap-3 w-full md:w-auto pb-4 md:pb-0 shrink-0">
                <button @click="openTarikModal = true" 
                        type="button"
                        class="flex-1 md:flex-none bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition duration-200 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap shadow-xs">
                    <i class="fas fa-hand-holding-usd text-xs"></i> 
                    <span>Tarik Saldo</span>
                </button>
            </div>
        </div>

        <!-- WALLET ROW (Grid Info Saldo & Total Transaksi) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card Utama Saldo Terkoneksi -->
            <div class="bg-emerald-600 text-white p-6 rounded-2xl shadow-md flex flex-col justify-between min-h-[140px]">
                <div>
                    <span class="text-[10px] uppercase font-bold text-emerald-100 tracking-wider block mb-1">Saldo Tabungan Saat Ini</span>
                    <h3 class="text-3xl font-black mt-2">
                        Rp {{ number_format($saldo ?? ($nasabah->saldo ?? 0), 0, ',', '.') }}
                    </h3>
                </div>
                <p class="text-[10px] text-emerald-200 mt-2">*Terkoneksi dengan riwayat tabungan Anda.</p>
            </div>

            <!-- Card Total Records Setoran -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs flex flex-col justify-between min-h-[140px]">
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Records Setoran</span>
                        <span class="bg-emerald-50 text-emerald-700 p-1.5 rounded-xl text-xs"><i class="fas fa-arrow-down"></i></span>
                    </div>
                    <h2 class="text-3xl font-black mt-3 text-slate-800">{{ count($setorans ?? []) }} <span class="text-xs font-semibold text-slate-400">Transaksi</span></h2>
                </div>
                <p class="text-[10px] text-slate-400 mt-2">Akumulasi total aktivitas menyetor sampah.</p>
            </div>

            <!-- Card Total Records Penarikan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs flex flex-col justify-between min-h-[140px]">
                <div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Records Penarikan</span>
                        <span class="bg-amber-50 text-amber-700 p-1.5 rounded-xl text-xs"><i class="fas fa-arrow-up"></i></span>
                    </div>
                    <h2 class="text-3xl font-black mt-3 text-slate-800">{{ count($penarikans ?? []) }} <span class="text-xs font-semibold text-slate-400">Pencairan</span></h2>
                </div>
                <p class="text-[10px] text-slate-400 mt-2">Akumulasi pencairan dana menjadi uang tunai.</p>
            </div>
        </div>

        <!-- TABLES GRID (Daftar Detail Riwayat Sebelah-Sebelahan) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- TABEL SETORAN -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-xs space-y-4">
                <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                    <h3 class="text-xs font-black text-slate-800 tracking-wider uppercase flex items-center gap-2">
                        <span class="text-emerald-600">📥</span> Semua Riwayat Setoran
                    </h3>
                    <span class="text-[10px] text-slate-400 font-bold">Data Sampah</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="pb-2">Tanggal</th>
                                <th class="pb-2">Berat</th>
                                <th class="pb-2 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-600 font-medium">
                            @forelse($setorans ?? [] as $setoran)
                                <tr>
                                    <td class="py-3 text-slate-500 whitespace-nowrap">
                                        {{ $setoran->created_at ? \Carbon\Carbon::parse($setoran->created_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3 text-slate-800 font-bold whitespace-nowrap">{{ number_format($setoran->total_berat ?? 0, 1) }} Kg</td>
                                    <td class="py-3 text-right font-black text-emerald-600 whitespace-nowrap">+Rp {{ number_format($setoran->total_harga ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-400 italic bg-slate-50/50 rounded-xl">Belum ada riwayat setoran sampah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL PENARIKAN (TERMASUK BADGE STATUS REALTIME & LENGKAP) -->
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-xs space-y-4">
                <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                    <h3 class="text-xs font-black text-slate-800 tracking-wider uppercase flex items-center gap-2">
                        <span class="text-rose-600">📤</span> Semua Riwayat Penarikan
                    </h3>
                    <span class="text-[10px] text-slate-400 font-bold">Data Keuangan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="pb-2">Tanggal</th>
                                <th class="pb-2">Keterangan</th>
                                <th class="pb-2 text-center">Status</th>
                                <th class="pb-2 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-600 font-medium">
                            @forelse($penarikans ?? [] as $penarikan)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <!-- TANGGAL (Menggunakan 'tanggal' atau 'created_at') -->
                                    <td class="py-3 text-slate-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($penarikan->tanggal ?? $penarikan->created_at)->format('d M Y') }}
                                    </td>

                                    <!-- KETERANGAN METODE PENARIKAN -->
                                    <td class="py-3 text-slate-800">
                                        @if(str_contains(strtolower($penarikan->keterangan ?? ''), 'e-wallet'))
                                            <div class="flex items-center gap-1.5">
                                                <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-bold text-[9px] uppercase border border-blue-100 shrink-0">
                                                    <i class="fas fa-mobile-alt"></i> E-Wallet
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5">
                                                <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md font-bold text-[9px] uppercase border border-emerald-100 shrink-0">
                                                    <i class="fas fa-money-bill-wave"></i> Cash
                                                </span>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- BADGE STATUS PENARIKAN (PENDING / SELSEAI / CANCEL) -->
                                    <td class="py-3 text-center whitespace-nowrap">
                                        @if(($penarikan->status ?? 'pending') == 'pending')
                                            <span class="bg-amber-100 text-amber-700 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1 animate-pulse">
                                                <i class="fas fa-clock text-[9px]"></i> Pending
                                            </span>
                                        @elseif(($penarikan->status ?? '') == 'selesai')
                                            <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                                <i class="fas fa-check-circle text-[9px]"></i> Selesai
                                            </span>
                                        @elseif(($penarikan->status ?? '') == 'cancel')
                                            <span class="bg-rose-100 text-rose-700 border border-rose-200 text-[10px] font-bold px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                                                <i class="fas fa-times-circle text-[9px]"></i> Batal
                                            </span>
                                        @endif
                                    </td>

                                    <!-- NOMINAL PENARIKAN -->
                                    <td class="py-3 text-right font-black whitespace-nowrap {{ ($penarikan->status ?? '') == 'cancel' ? 'text-slate-400 line-through' : 'text-rose-600' }}">
                                        -Rp {{ number_format($penarikan->jumlah ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 italic bg-slate-50/50 rounded-xl">Belum ada riwayat penarikan saldo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL POP-UP: FORM TARIK SALDO TERPADU (CASH / E-WALLET) -->
    <div x-show="openTarikModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-cloak>
        
        <!-- Backdrop Blur -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="openTarikModal = false"></div>

        <!-- Modal Card -->
        <div class="relative bg-white rounded-3xl shadow-xl max-w-md w-full p-6 space-y-4 border border-gray-100 z-10"
             x-show="openTarikModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-2">
                    <span class="text-xl">💸</span>
                    <h3 class="text-base font-black text-gray-950">Tarik Saldo Nasabah</h3>
                </div>
                <button @click="openTarikModal = false" class="text-gray-400 hover:text-gray-600 text-sm p-1 cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('user.tarik_saldo') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <!-- NOMINAL PENARIKAN -->
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nominal Tarik</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 font-bold text-gray-400">Rp</span>
                        <input type="number" name="nominal" required min="10000" placeholder="Contoh: 50000"
                               class="w-full border border-gray-200 rounded-xl p-2.5 pl-9 bg-gray-50 focus:outline-emerald-600 font-semibold text-gray-700 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                    </div>
                </div>

                <!-- SELEKTOR METODE -->
                <div>
                    <label class="block font-bold text-gray-700 mb-1.5">Metode Penarikan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Cash -->
                        <label class="relative flex items-center justify-between p-3 bg-gray-50 border rounded-2xl cursor-pointer select-none transition"
                               :class="jenisPenarikan === 'cash' ? 'border-emerald-600 bg-emerald-50/20 text-emerald-950 font-bold' : 'border-gray-200 text-gray-600'">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-money-bill-wave text-xs" :class="jenisPenarikan === 'cash' ? 'text-emerald-600' : 'text-gray-400'"></i>
                                <span>Cash (Tunai)</span>
                            </div>
                            <input type="radio" name="jenis_penarikan" value="cash" x-model="jenisPenarikan" class="sr-only">
                            <i class="fas fa-check-circle text-emerald-600 text-xs" x-show="jenisPenarikan === 'cash'"></i>
                        </label>

                        <!-- E-Wallet -->
                        <label class="relative flex items-center justify-between p-3 bg-gray-50 border rounded-2xl cursor-pointer select-none transition"
                               :class="jenisPenarikan === 'ewallet' ? 'border-emerald-600 bg-emerald-50/20 text-emerald-950 font-bold' : 'border-gray-200 text-gray-600'">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-xs" :class="jenisPenarikan === 'ewallet' ? 'text-emerald-600' : 'text-gray-400'"></i>
                                <span>E-Wallet</span>
                            </div>
                            <input type="radio" name="jenis_penarikan" value="ewallet" x-model="jenisPenarikan" class="sr-only">
                            <i class="fas fa-check-circle text-emerald-600 text-xs" x-show="jenisPenarikan === 'ewallet'"></i>
                        </label>
                    </div>
                </div>

                <!-- OPSI DINAMIS E-WALLET -->
                <div x-show="jenisPenarikan === 'ewallet'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="space-y-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                    
                    <div>
                        <label class="block font-bold text-gray-600 mb-1">Pilih E-Wallet</label>
                        <select name="jenis_ewallet" :required="jenisPenarikan === 'ewallet'"
                                class="w-full border border-gray-200 rounded-xl p-2 bg-white focus:outline-emerald-600">
                            <option value="">-- Pilih Layanan --</option>
                            <option value="dana">DANA</option>
                            <option value="gopay">GoPay</option>
                            <option value="ovo">OVO</option>
                            <option value="shopeepay">ShopeePay</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-600 mb-1">Nomor HP E-Wallet</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 font-bold text-gray-400">+62</span>
                            <input type="tel" name="nomor_ewallet" :required="jenisPenarikan === 'ewallet'" placeholder="8xxxxxxxxxx"
                                   class="w-full border border-gray-200 rounded-xl p-2 pl-10 bg-white focus:outline-emerald-600">
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="flex gap-2 pt-2">
                    <button type="button" @click="openTarikModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl transition cursor-pointer"> Batal </button>
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-2.5 rounded-xl transition shadow-sm cursor-pointer"> Proses Tarik </button>
                </div>
            </form>
        </div>
    </div>
    
    <footer class="text-center py-8 text-[11px] text-slate-400">
        &copy; 2026 BANKSACI Dev Team. Apps real-time sync.
    </footer>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>