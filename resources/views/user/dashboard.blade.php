<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Warga - BANKSACI</title>

    <!-- Tailwind CSS v4 / CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js untuk Slider, Dropdown Search, Lonceng Notif, & Interaksi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS (Animate On Scroll) CSS & JS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.08); }
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
    <div class="fixed top-0 right-0 -z-10 w-[400px] h-[400px] bg-emerald-200/30 rounded-full filter blur-3xl animate-pulse-glow pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 -z-10 w-[350px] h-[350px] bg-teal-200/30 rounded-full filter blur-3xl animate-pulse-glow pointer-events-none" style="animation-delay: 3s;"></div>

    <!-- NAVBAR HERO -->
    <nav class="bg-emerald-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex justify-between items-center">
            
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 group">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                    <span class="font-bold text-xl tracking-wider text-white">BANK<span class="text-[#52d69b] inline-block group-hover:translate-x-0.5 transition-transform">SACI</span></span>
                </a>

                @php
                    // Menerima data totalSampahKg dari controller
                    $berat = $totalSampahKg ?? 0;
                    if($berat >= 50) {
                        $badgeText = 'Penjaga Ekosistem';
                        $badgeClass = 'bg-amber-500 text-amber-950 border-amber-400';
                        $targetBerikutnya = 100; 
                    } elseif($berat >= 15) {
                        $badgeText = 'Pahlawan Hijau';
                        $badgeClass = 'bg-emerald-400 text-emerald-950 border-emerald-300';
                        $targetBerikutnya = 50;
                    } else {
                        $badgeText = 'Warga Peduli';
                        $badgeClass = 'bg-emerald-800 text-emerald-300 border-emerald-700';
                        $targetBerikutnya = 15;
                    }
                    $persenProgress = min(($berat / $targetBerikutnya) * 100, 100);
                @endphp

                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase ml-2 border transition-all duration-300 hover:scale-105 shadow-2xs {{ $badgeClass }}">
                    {{ $badgeText }}
                </span>
            </div>

            <!-- BAGIAN KANAN NAVBAR -->
            <div class="flex items-center gap-2 md:gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-white">{{ $nasabah->nama ?? (Auth::user()->name ?? 'Warga') }}</p>
                    <p class="text-xs text-emerald-300">ID: {{ $nasabah->kode_nasabah ?? 'Belum Terelasi' }}</p>
                </div>
                
                <!-- 🔔 KOMPONEN LONCENG NOTIFIKASI -->
                @php
                    $unreadCount = auth()->user()?->unreadNotifications->count() ?? 0;
                    $notifications = auth()->user()?->notifications->take(8) ?? [];
                @endphp
                <div class="relative" x-data="{ openNotif: false }" @click.outside="openNotif = false">
                    <!-- Tombol Lonceng -->
                    <button @click="openNotif = !openNotif" 
                            title="Notifikasi"
                            class="relative bg-emerald-800/80 hover:bg-emerald-700 text-emerald-100 hover:text-white p-2.5 rounded-2xl flex items-center justify-center transition shadow-xs hover:shadow-md cursor-pointer border border-emerald-700/60 active:scale-95">
                        <i class="fas fa-bell text-sm"></i>

                        <!-- Titik/Badge Notifikasi Belum Dibaca -->
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[9px] font-black text-white bg-rose-500 rounded-full animate-bounce shadow-xs border-2 border-emerald-900">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown List Notifikasi -->
                    <div x-show="openNotif" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                         class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 overflow-hidden text-slate-800">
                        
                        <!-- Header Dropdown -->
                        <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/80">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-800">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <span class="bg-rose-100 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        {{ $unreadCount }} Baru
                                    </span>
                                @endif
                            </div>

                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-emerald-600 hover:text-emerald-700 hover:underline font-semibold cursor-pointer">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- List Notifikasi -->
                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-50">
                            @forelse ($notifications as $notification)
                                <a href="{{ $notification->data['url'] ?? '#' }}" 
                                   class="block px-4 py-3 hover:bg-slate-50 transition border-l-4 {{ $notification->read_at ? 'border-transparent opacity-60' : 'border-emerald-600 bg-emerald-50/30' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="shrink-0 mt-0.5">
                                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-emerald-100 text-emerald-700 text-xs shadow-2xs">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }}"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-900 leading-snug">
                                                {{ $notification->data['title'] ?? 'Pemberitahuan' }}
                                            </p>
                                            <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            <span class="text-[9px] text-slate-400 mt-1 block font-medium">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-8 text-center text-xs text-slate-400 flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-bell-slash text-2xl text-slate-300"></i>
                                    <span>Belum ada notifikasi saat ini.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- TOMBOL KEMBALI KE WEB UTAMA -->
                <a href="{{ url('/') }}" title="Kembali ke Web Utama" 
                   class="bg-emerald-800/80 hover:bg-emerald-700 active:scale-95 text-emerald-100 hover:text-white px-3 py-2 rounded-2xl flex items-center gap-2 transition shadow-xs hover:shadow-md cursor-pointer border border-emerald-700/60 text-xs font-semibold group">
                    <i class="fas fa-home text-sm group-hover:-translate-y-0.5 transition-transform"></i>
                    <span class="hidden md:inline">Web Utama</span>
                </a>

                <!-- TOMBOL LOGOUT -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar dari sistem" class="bg-[#05583f] hover:bg-[#044537] active:scale-95 text-white w-10 h-10 rounded-2xl flex items-center justify-center transition shadow-xs hover:shadow-md cursor-pointer group">
                        <!-- SVG Ikon Solid Log Out -->
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
        
        <!-- ALERT FLASH NOTIFIKASI BACKEND -->
        @if(session('success'))
            <div data-aos="fade-down" class="w-full bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs flex items-center gap-2 shadow-2xs">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div data-aos="fade-down" class="w-full bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 text-xs flex items-center gap-2 shadow-2xs">
                <i class="fas fa-exclamation-circle text-rose-600 text-sm"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif
        
        <!-- HERO INTERACTIVE SLIDER WITH ANIMATION -->
        <div data-aos="zoom-in" data-aos-duration="800"
             x-data="{ 
                activeSlide: 1, 
                slides: [
                    { id: 1, title: 'Ubah Sampah Jadi Berkah!', desc: 'Pilah sampahmu dari rumah, setorkan ke BANKSACI, dan nikmati saldo e-wallet yang langsung cair.', img: 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=1200&q=80' },
                    { id: 2, title: 'Cisalada Menuju Bebas Sampah', desc: 'Setiap kilogram botol plastik dan kertas yang Anda bawa membantu menyelamatkan ekosistem hijau desa kita.', img: 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&w=1200&q=80' }
                ] 
             }" 
             x-init="setInterval(() => { activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1 }, 6000)"
             class="relative bg-emerald-950 text-white rounded-3xl overflow-hidden shadow-xl h-[260px] md:h-[300px] flex items-center group">
            
            <template x-for="slide in slides" :key="slide.id">
                <div x-show="activeSlide === slide.id" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-105"
                     class="absolute inset-0 w-full h-full">
                    <img :src="slide.img" class="w-full h-full object-cover opacity-25 object-center absolute inset-0 group-hover:scale-105 transition-transform duration-1000" alt="Banner Background">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-950 via-emerald-900/80 to-transparent"></div>
                    
                    <div class="relative z-10 max-w-2xl h-full flex flex-col justify-center px-6 md:px-12 space-y-3">
                        <span class="bg-emerald-500/30 text-emerald-300 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest w-max border border-emerald-400/30 shadow-xs">
                            <i class="fas fa-sparkles text-amber-300 mr-1 animate-pulse"></i> Inovasi Desa Cisalada
                        </span>
                        <h1 class="text-xl md:text-3xl font-black tracking-tight leading-tight" x-text="slide.title"></h1>
                        <p class="text-xs md:text-sm text-emerald-200/90 leading-relaxed font-light" x-text="slide.desc"></p>
                    </div>
                </div>
            </template>

            <div class="absolute bottom-4 left-6 md:left-12 z-20 flex gap-2">
                <template x-for="slide in slides" :key="slide.id">
                    <button @click="activeSlide = slide.id" 
                            :class="activeSlide === slide.id ? 'bg-emerald-400 w-8' : 'bg-emerald-700/80 w-2 hover:bg-emerald-500'" 
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                </template>
            </div>
        </div>

        <!-- NAVIGASI SUB-DASHBOARD & AKSI CEPAT -->
        <div data-aos="fade-up" class="w-full bg-white rounded-2xl border border-slate-100 shadow-xs px-6 py-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="flex items-center gap-6 md:gap-8 w-full md:w-auto overflow-x-auto whitespace-nowrap hide-scrollbar">
                <!-- TAB 1: INFORMASI DESA -->
                <a href="{{ route('dashboard.user') }}" 
                   class="flex items-center gap-2 py-4 text-xs md:text-sm transition-all duration-200 border-b-2 {{ request()->is('user-dashboard-ui') || request()->is('dashboard/user') ? 'font-black text-emerald-600 border-emerald-600' : 'font-semibold text-slate-400 border-transparent hover:text-slate-600' }} shrink-0">
                    <i class="fas fa-info-circle text-sm md:text-base"></i>
                    <span>Informasi Desa</span>
                </a>
                
                <!-- TAB 2: E-WALLET & TABUNGAN -->
                <a href="{{ route('user.tabungan') }}" 
                   class="flex items-center gap-2 py-4 pr-2 text-xs md:text-sm transition-all duration-200 border-b-2 {{ request()->is('user/tabungan') ? 'font-black text-emerald-600 border-emerald-600' : 'font-semibold text-slate-400 border-transparent hover:text-slate-600' }} shrink-0">
                    <i class="fas fa-wallet text-sm md:text-base"></i>
                    <span>E-Wallet & Tabungan</span>
                </a>
            </div>

            <!-- AKSI CEPAT (Hanya Cek Harga) -->
            <div class="flex items-center gap-3 w-full md:w-auto pb-2 md:pb-0 shrink-0">
                <a href="{{ route('user.katalog') }}" 
                   class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition duration-200 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap shadow-xs hover:shadow-md hover:shadow-emerald-600/20 group">
                    <i class="fas fa-tags text-xs group-hover:rotate-12 transition-transform"></i> 
                    <span>Cek Harga Sampah</span>
                </a>
            </div>
        </div>

        <!-- CONTENT GRID -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            
            <!-- LEFT AREA: KALKULATOR & KONTEN UTAMA -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Simulasi Kalkulator Penghasilan -->
                <div data-aos="fade-right" data-aos-duration="900"
                     class="bg-[#044537] text-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 space-y-4 relative overflow-hidden" 
                     x-data="{ 
                        berat: 1, 
                        hargaPerKg: 3000,
                        search: '',
                        openDropdown: false,
                        pilihanNama: 'Pilih Jenis Sampah...',
                        listSampah: [
                            @forelse($jenisSampah ?? [] as $item)
                                { id: '{{ $item->id }}', nama: '{{ $item->nama_jenis }}', harga: {{ $item->harga_per_kg }} },
                            @empty
                                { id: 'plastik', nama: 'Plastik Botol', harga: 3000 },
                                { id: 'kertas', nama: 'Kertas/Kardus', harga: 2000 },
                                { id: 'logam', nama: 'Besi/Logam', harga: 6000 }
                            @endforelse
                        ],
                        get filteredSampah() {
                            return this.listSampah.filter(i => i.nama.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        pilih(item) {
                            this.pilihanNama = item.nama + ' (Rp ' + Intl.NumberFormat('id-ID').format(item.harga) + '/Kg)';
                            this.hargaPerKg = Number(item.harga) || 0;
                            this.openDropdown = false;
                            this.search = '';
                        },
                        get totalEstimasi() {
                            return (Number(this.berat) || 0) * this.hargaPerKg;
                        }
                     }"
                     x-init="if(listSampah.length > 0) { pilih(listSampah[0]); }">
                    
                    <!-- HEADER KALKULATOR -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider text-emerald-300 flex items-center gap-1.5">
                                <span>🧮</span> Simulasi Kalkulator Penghasilan
                            </h3>
                            <p class="text-[10px] text-emerald-200/70 mt-0.5">Cek estimasi rupiah yang didapat sebelum diserahkan ke petugas.</p>
                        </div>
                    </div>

                    <!-- FORM INPUT & DROPDOWN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start text-xs pt-1">
                        
                        <!-- 1. CUSTOM SEARCHABLE DROPDOWN -->
                        <div class="relative z-50">
                            <label class="block font-bold text-emerald-200 mb-1 text-[11px]">JENIS SAMPAH</label>
                            <button type="button" @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                                    class="w-full bg-emerald-800/50 border border-emerald-700/80 rounded-xl p-2.5 text-left text-white flex justify-between items-center focus:outline-none focus:border-emerald-400 hover:bg-emerald-800/80 cursor-pointer text-xs transition">
                                <span x-text="pilihanNama" class="truncate mr-2"></span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 shrink-0" :class="openDropdown ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- DROPDOWN LIST -->
                            <div x-show="openDropdown" x-transition 
                                 class="absolute z-50 mt-1 w-full bg-emerald-900 border border-emerald-700 rounded-xl shadow-xl max-h-56 overflow-y-auto p-2 space-y-1 text-xs">
                                <div class="sticky top-0 bg-emerald-900 pb-1.5">
                                    <input type="text" x-model="search" placeholder="Cari nama sampah..." 
                                           class="w-full bg-emerald-950/80 border border-emerald-700 rounded-lg p-2 text-white placeholder-emerald-400/60 focus:outline-none focus:border-emerald-400 text-xs">
                                </div>
                                <div class="space-y-0.5">
                                    <template x-for="item in filteredSampah" :key="item.id">
                                        <button type="button" @click="pilih(item)" 
                                                class="w-full text-left p-2 hover:bg-emerald-800 rounded-lg transition text-emerald-100 flex justify-between items-center cursor-pointer text-xs">
                                            <span x-text="'📦 ' + item.nama" class="truncate mr-2"></span>
                                            <span class="font-bold text-emerald-300 text-[11px] shrink-0" x-text="'Rp ' + Intl.NumberFormat('id-ID').format(item.harga)"></span>
                                        </button>
                                    </template>
                                    <div x-show="filteredSampah.length === 0" class="text-center py-3 text-emerald-400/60 italic text-[11px]">
                                        Sampah tidak ditemukan...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. INPUT BERAT SAMPAH (KG) -->
                        <div>
                            <label class="block font-bold text-emerald-200 mb-1 text-[11px]">ESTIMASI BERAT (KG)</label>
                            <div class="relative">
                                <input type="number" min="0.1" step="0.1" x-model="berat" placeholder="Contoh: 2.5"
                                       class="w-full bg-emerald-800/50 border border-emerald-700/80 rounded-xl p-2.5 text-white font-bold placeholder-emerald-400/50 focus:outline-none focus:border-emerald-400 text-xs transition pr-10">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-emerald-300">Kg</span>
                            </div>
                        </div>
                    </div>

                    <!-- HASIL ESTIMASI PENAGIHAN / PENDAPATAN -->
                    <div class="mt-4 pt-3 border-t border-emerald-700/60 flex items-center justify-between bg-emerald-900/40 p-3 rounded-xl border border-emerald-800/50">
                        <div>
                            <span class="text-[10px] text-emerald-300/80 uppercase font-bold tracking-wider block">Estimasi Pendapatan:</span>
                            <span class="text-xs text-emerald-200" x-text="berat + ' Kg × Rp ' + Intl.NumberFormat('id-ID').format(hargaPerKg)"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-amber-300" x-text="'Rp ' + Intl.NumberFormat('id-ID').format(totalEstimasi)"></span>
                        </div>
                    </div>
                </div>

                <!-- Tentang BankSaci -->
                <div data-aos="fade-up" data-aos-duration="900" 
                     class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md hover:border-emerald-200 transition-all duration-300 space-y-3">
                    <h2 class="text-base font-black text-gray-950 flex items-center gap-2">
                        <span class="text-emerald-600">🏢</span> Tentang BANKSACI
                    </h2>
                    <p class="text-xs text-gray-600 leading-relaxed font-normal">
                        <strong class="text-gray-800">BANKSACI (Bank Sampah Cisalada)</strong> adalah program inovasi desa yang digerakkan untuk mengedukasi masyarakat mengenai pentingnya memilah sampah dari rumah. Platform ini mendigitalisasi tabungan warga agar setiap kilogram sampah anorganik yang Anda kumpulkan bernilai ekonomis secara instan dan transparan.
                    </p>
                </div>
            </div>

            <!-- RIGHT AREA: INFO PROFILE & TARGET BULANAN -->
            <div class="space-y-6">
                <!-- PROFILE CARD -->
                <div data-aos="fade-left" data-aos-duration="900" 
                     class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md hover:border-emerald-200 transition-all duration-300 space-y-4 text-xs">
                    
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="font-bold text-gray-800 flex items-center gap-1.5"><i class="fas fa-user-circle text-emerald-600 text-sm"></i> Detail Profil Warga</span>
                        <span class="bg-emerald-100 text-emerald-700 text-[9px] px-2 py-0.5 rounded-full font-black">Aktif</span>
                    </div>
                    
                    <div class="space-y-2 text-gray-500">
                        <p><strong class="text-gray-700">Nama:</strong> {{ $nasabah->nama ?? (Auth::user()->name ?? 'Warga') }}</p>
                        <p><strong class="text-gray-700">Alamat:</strong> {{ $nasabah->alamat ?? '-' }} (RT {{ $nasabah->rt ?? '00' }}/RW {{ $nasabah->rw ?? '00' }})</p>
                        <p><strong class="text-gray-700">No. HP:</strong> {{ $nasabah->no_hp ?? '-' }}</p>
                    </div>

                    <!-- PROGRESS TARGET LEVEL -->
                    <div class="border-t border-gray-100 pt-3 space-y-2">
                        <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase">
                            <span>Target Level Berikutnya</span>
                            <span class="text-emerald-600 font-extrabold">{{ number_format($berat, 1) }} / {{ $targetBerikutnya }} Kg</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-700 shadow-xs" style="width: {{ $persenProgress }}%"></div>
                        </div>
                        <p class="text-[9px] text-gray-400 italic">Kumpulkan {{ max(0, $targetBerikutnya - $berat) }} Kg sampah lagi untuk naik pangkat!</p>
                    </div>

                    <a href="/user/ganti-password" class="block text-center bg-slate-50 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 font-bold py-2.5 rounded-xl border border-slate-200 hover:border-emerald-300 transition-all text-[11px] active:scale-98">
                        <i class="fas fa-key mr-1 text-slate-400"></i> Ganti Password Akun
                    </a>
                </div>

                <!-- Jam Operasional -->
                <div data-aos="fade-left" data-aos-duration="900" data-aos-delay="150"
                     class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md hover:border-emerald-200 transition-all duration-300 space-y-3 text-xs">
                    <h3 class="font-black text-gray-900 border-b border-gray-100 pb-2 flex items-center gap-1.5">
                        <i class="fas fa-clock text-emerald-600"></i> Jadwal Penyetoran Sampah
                    </h3>
                    <ul class="space-y-2 text-gray-600 font-normal">
                        <li class="flex justify-between items-center"><span>Senin - Kamis:</span> <strong class="text-gray-900 bg-slate-100 px-2 py-0.5 rounded-md">08:00 - 14:00</strong></li>
                        <li class="flex justify-between items-center"><span>Sabtu:</span> <strong class="text-gray-900 bg-slate-100 px-2 py-0.5 rounded-md">08:00 - 12:00</strong></li>
                        <li class="text-rose-500 font-semibold mt-1 flex items-center gap-1 text-[11px]">*Hari Jumat & Minggu Libur</li>
                    </ul>
                </div>
            </div>

        </div>
    </main>
   
    <footer class="text-center py-8 text-[11px] text-gray-400 border-t border-slate-200/60 mt-auto bg-white/50">
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