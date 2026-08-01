<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabungan & E-Wallet - BANKSACI</title>

    <!-- Tailwind CSS v4 / CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS (Animate On Scroll) CSS & JS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.25; transform: scale(1); }
            50% { opacity: 0.55; transform: scale(1.08); }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulseGlow 6s ease-in-out infinite;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 relative overflow-x-hidden min-h-screen flex flex-col">

    <!-- BACKGROUND GLOW ANIMATED DECORATION -->
    <div class="fixed top-0 right-0 -z-10 w-[420px] h-[420px] bg-emerald-200/30 rounded-full filter blur-3xl animate-pulse-glow pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 -z-10 w-[380px] h-[380px] bg-teal-200/30 rounded-full filter blur-3xl animate-pulse-glow pointer-events-none" style="animation-delay: 3s;"></div>

    <!-- NAVBAR HERO -->
    <nav class="bg-emerald-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex justify-between items-center">
            
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 group">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                    <span class="font-bold text-xl tracking-wider text-white">BANK<span class="text-[#52d69b] inline-block group-hover:translate-x-0.5 transition-transform">SACI</span></span>
                </a>

                @php
                    $berat = $totalSampah ?? 0;
                    if($berat >= 50) {
                        $badgeText = 'Penjaga Ekosistem';
                        $badgeClass = 'bg-amber-500 text-amber-950 border-amber-400';
                    } elseif($berat >= 15) {
                        $badgeText = 'Pahlawan Hijau';
                        $badgeClass = 'bg-emerald-400 text-emerald-950 border-emerald-300';
                    } else {
                        $badgeText = 'WARGA PEDULI';
                        $badgeClass = 'bg-emerald-800 text-emerald-300 border-emerald-700';
                    }
                @endphp

                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase ml-2 border transition-all duration-300 hover:scale-105 shadow-2xs {{ $badgeClass }}">
                    {{ $badgeText }}
                </span>
            </div>

            <!-- BAGIAN KANAN NAVBAR -->
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-white">{{ $nasabah->nama ?? (Auth::user()->name ?? 'Pengguna') }}</p>
                    <p class="text-xs text-emerald-300">ID: {{ $nasabah->kode_nasabah ?? '-' }}</p>
                </div>
                
                <!-- TOMBOL LOGOUT -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar dari sistem" class="bg-[#05583f] hover:bg-[#044537] active:scale-95 text-white w-10 h-10 rounded-2xl flex items-center justify-center transition shadow-xs hover:shadow-md cursor-pointer group">
                        <svg class="w-5 h-5 text-white group-hover:translate-x-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 3a2 2 0 00-2 2v14a2 2 0 002 2h4a1 1 0 100-2H5V5h4a1 1 0 100-2H5z" />
                            <path fill-rule="evenodd" d="M13.293 7.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L15.586 13H9a1 1 0 110-2h6.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="max-w-6xl mx-auto px-4 py-8 space-y-6 flex-grow w-full">
        
        <!-- HEADER BANNER E-WALLET -->
        <div data-aos="zoom-in" data-aos-duration="800" 
             class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    Dompet Digital Anda <span class="animate-float inline-block">💰</span>
                </h1>
                <p class="text-xs md:text-sm text-slate-500 font-normal">
                    Pantau terus perkembangan nilai konversi rupiah dari aksi pemilahan sampah Anda.
                </p>
            </div>

            <a href="{{ route('user.mutasi') }}" 
               class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs px-5 py-3 rounded-2xl border border-emerald-200/80 transition-all duration-200 flex items-center justify-center gap-2 shadow-2xs hover:shadow-xs active:scale-95 group shrink-0">
                <i class="fas fa-exchange-alt group-hover:rotate-180 transition-transform duration-500"></i>
                <span>Lihat Mutasi Lengkap</span>
            </a>
        </div>

        <!-- NAVIGASI SUB-DASHBOARD TAB -->
        <div data-aos="fade-up" class="w-full bg-white rounded-2xl border border-slate-100 shadow-xs px-6 py-2 flex items-center gap-6 md:gap-8 overflow-x-auto whitespace-nowrap hide-scrollbar">
            <!-- TAB 1: INFORMASI DESA -->
            <a href="{{ route('dashboard.user') }}" 
               class="flex items-center gap-2 py-3.5 text-xs md:text-sm transition-all duration-200 border-b-2 font-semibold text-slate-400 border-transparent hover:text-slate-600 shrink-0">
                <i class="fas fa-info-circle text-sm md:text-base"></i>
                <span>Informasi Desa</span>
            </a>
            
            <!-- TAB 2: E-WALLET & TABUNGAN (ACTIVE) -->
            <a href="{{ route('user.tabungan') }}" 
               class="flex items-center gap-2 py-3.5 text-xs md:text-sm transition-all duration-200 border-b-2 font-black text-emerald-600 border-emerald-600 shrink-0">
                <i class="fas fa-wallet text-sm md:text-base"></i>
                <span>E-Wallet & Tabungan</span>
            </a>
        </div>

        <!-- 3 CARDS STATISTIK KEUANGAN & SALDO -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- CARD 1: TOTAL SALDO E-WALLET (DARK CARD) -->
            <div data-aos="fade-up" data-aos-delay="100" 
                 class="bg-[#034032] text-white p-6 rounded-3xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between group">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[10px] font-black tracking-widest text-emerald-300 uppercase">TOTAL SALDO E-WALLET</span>
                        <span class="bg-emerald-900/80 text-emerald-300 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-700/60 flex items-center gap-1">
                            <i class="fas fa-shield-alt text-[9px]"></i> Real-time
                        </span>
                    </div>

                    <!-- PAKAI VARIABEL $saldo REALTIME DARI CONTROLLER -->
                    <div class="text-3xl font-black text-white tracking-tight my-2">
                        Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <p class="text-[10px] text-emerald-200/70 italic mt-4">
                    *Pencairan saldo tunai dapat diproses via petugas.
                </p>
            </div>

            <!-- CARD 2: SAMPAH TERSELAMATKAN -->
            <div data-aos="fade-up" data-aos-delay="200" 
                 class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">SAMPAH TERSELAMATKAN</span>
                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>

                    <!-- PAKAI VARIABEL $totalSampah REALTIME DARI CONTROLLER -->
                    <div class="text-3xl font-black text-slate-900 tracking-tight my-1">
                        {{ number_format($totalSampah ?? 0, 1, ',', '.') }} <span class="text-base font-bold text-slate-500">Kg</span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 font-normal mt-4">
                    Kontribusi nyata Anda menjaga kebersihan ekosistem desa.
                </p>
            </div>

            <!-- CARD 3: DETAIL PROFIL -->
            <div data-aos="fade-up" data-aos-delay="300" 
                 class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between space-y-3">
                
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="font-bold text-xs text-slate-800 flex items-center gap-1.5">
                        <i class="fas fa-info-circle text-emerald-600"></i> Detail Profil
                    </span>
                    <span class="bg-emerald-100 text-emerald-700 text-[9px] font-black px-2 py-0.5 rounded-full">
                        Status: Aktif
                    </span>
                </div>

                <div class="space-y-1.5 text-xs text-slate-600 font-normal">
                    <p><strong class="text-slate-800 font-semibold">Tingkatan:</strong> <span class="text-emerald-600 font-bold">{{ $badgeText }}</span></p>
                    <p><strong class="text-slate-800 font-semibold">Alamat:</strong> {{ $nasabah->alamat ?? '-' }} (RT {{ $nasabah->rt ?? '00' }}/RW {{ $nasabah->rw ?? '00' }})</p>
                </div>

                <div class="pt-1">
                    <span class="text-[10px] text-slate-400 block">Akun terverifikasi oleh Sistem BANKSACI</span>
                </div>
            </div>

        </div>

        <!-- TWO COLUMNS TABLE: RIWAYAT SETORAN & PENARIKAN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            
            <!-- RIWAYAT 5 SETORAN TERAKHIR -->
            <div data-aos="fade-right" data-aos-duration="800" 
                 class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs hover:shadow-md transition-all duration-300 space-y-4">
                
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <span class="text-emerald-600">♻</span> 5 SETORAN TERAKHIR
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400">Data Sampah</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="pb-2 font-bold">TANGGAL</th>
                                <th class="pb-2 font-bold">BERAT</th>
                                <th class="pb-2 font-bold text-right">PENDAPATAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-700">
                            <!-- MENAMPILKAN 5 SETORAN TERAKHIR DARI CONTROLLER -->
                            @forelse(($setorans ?? collect([]))->take(5) as $setoran)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($setoran->created_at ?? $setoran->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 font-bold text-slate-800">
                                        {{ number_format($setoran->total_berat ?? $setoran->berat ?? 0, 1, ',', '.') }} Kg
                                    </td>
                                    <td class="py-3 font-extrabold text-emerald-600 text-right">
                                        + Rp {{ number_format($setoran->total_harga ?? $setoran->nominal ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-400 italic text-[11px]">
                                        Belum ada riwayat setoran sampah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIWAYAT 5 PENARIKAN TERAKHIR -->
            <div data-aos="fade-left" data-aos-duration="800" 
                 class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs hover:shadow-md transition-all duration-300 space-y-4">
                
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <span class="text-rose-500">💰</span> 5 PENARIKAN TERAKHIR
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400">Data Keuangan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="pb-2 font-bold">TANGGAL</th>
                                <th class="pb-2 font-bold">KETERANGAN</th>
                                <th class="pb-2 font-bold text-right">NOMINAL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-700">
                            <!-- MENAMPILKAN 5 PENARIKAN TERAKHIR DARI CONTROLLER -->
                            @forelse(($penarikans ?? collect([]))->take(5) as $penarikan)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 font-medium text-slate-600">
                                        {{ \Carbon\Carbon::parse($penarikan->created_at ?? $penarikan->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 text-slate-700">
                                        {{ $penarikan->keterangan ?? 'Penarikan Tunai' }}
                                    </td>
                                    <td class="py-3 font-extrabold text-rose-600 text-right">
                                        - Rp {{ number_format($penarikan->jumlah ?? $penarikan->nominal ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center text-slate-400 italic text-[11px]">
                                        Belum ada riwayat penarikan saldo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="text-center py-8 text-[11px] text-slate-400 border-t border-slate-200/60 mt-auto bg-white/50">
        &copy; 2026 BANKSACI Dev Team. Apps real-time sync.
    </footer>

    <!-- INITIATE AOS ANIMATION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800,
                easing: 'ease-out-cubic',
                offset: 50
            });
        });
    </script>

</body>
</html>