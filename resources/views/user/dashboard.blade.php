<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Warga - BANKSACI</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #059669; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800 relative min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav class="bg-[#033c2e] text-white shadow-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex justify-between items-center">
            
            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 group">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                    <span class="font-black text-xl tracking-wider text-white">BANK<span class="text-[#52d69b]">SACI</span></span>
                </a>

                @php
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
                        $badgeText = 'WARGA PEDULI';
                        $badgeClass = 'bg-emerald-800/80 text-emerald-300 border-emerald-700';
                        $targetBerikutnya = 15;
                    }
                    $persenProgress = min(($berat / $targetBerikutnya) * 100, 100);
                @endphp

                <span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase ml-2 border {{ $badgeClass }}">
                    {{ $badgeText }}
                </span>
            </div>

            <!-- KANAN NAVBAR -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-white">{{ $nasabah->nama ?? (Auth::user()->name ?? 'korma') }}</p>
                    <p class="text-[10px] text-emerald-300">ID: {{ $nasabah->kode_nasabah ?? 'BS-0004' }}</p>
                </div>

                <!-- LOGOUT -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Keluar" class="bg-emerald-900/60 hover:bg-emerald-800 text-emerald-200 hover:text-white p-2 rounded-xl transition cursor-pointer border border-emerald-700/50">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="max-w-6xl mx-auto px-4 py-6 space-y-5 flex-grow w-full">
        
        <!-- ALERT WELCOME -->
        <div class="w-full bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-3.5 text-xs flex items-center gap-2 shadow-2xs font-semibold">
            <i class="fas fa-check-circle text-emerald-600"></i>
            <span>Selamat datang kembali!</span>
        </div>
        
        <!-- HERO BANNER -->
        <div class="relative bg-[#033c2e] text-white rounded-3xl overflow-hidden shadow-md h-[220px] flex items-center">
            <div class="relative z-10 max-w-xl px-8 space-y-2">
                <span class="bg-emerald-500/20 text-emerald-300 text-[9px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-500/30">
                    INOVASI DESA CISALADA
                </span>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight">Ubah Sampah Jadi Berkah!</h1>
                <p class="text-xs text-emerald-200/80 leading-relaxed">
                    Pilah sampahmu dari rumah, setorkan ke BANKSACI, dan nikmati saldo e-wallet yang langsung cair.
                </p>
                <div class="flex gap-1.5 pt-2">
                    <span class="w-6 h-1.5 bg-emerald-400 rounded-full"></span>
                    <span class="w-1.5 h-1.5 bg-emerald-800 rounded-full"></span>
                </div>
            </div>
        </div>

        <!-- TAB NAVIGASI & TOMBOL CEK HARGA -->
        <div class="w-full bg-white rounded-2xl border border-slate-200/80 shadow-2xs px-6 py-2 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 py-3 text-xs font-black text-emerald-700 border-b-2 border-emerald-600">
                    <i class="fas fa-info-circle"></i> Informasi Desa
                </a>
                <a href="{{ route('user.tabungan') }}" class="flex items-center gap-2 py-3 text-xs font-semibold text-slate-400 hover:text-slate-600 border-b-2 border-transparent">
                    <i class="fas fa-wallet"></i> E-Wallet & Tabungan
                </a>
            </div>

            <a href="{{ route('user.katalog') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition flex items-center gap-2">
                <i class="fas fa-tag"></i> Cek Harga Sampah
            </a>
        </div>

        <!-- CONTENT GRID (KOLOM TERPISAH PRESISI) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch">
            
            <!-- KOLOM KIRI (LEBAR: 2 SPAN) - KHUSUS SIMULASI KALKULATOR PENGHASILAN -->
            <div class="md:col-span-2 bg-[#033c2e] text-white rounded-2xl p-5 shadow-md flex flex-col justify-between" 
                 x-data="{ 
                    berat: 0, 
                    hargaPerKg: 2000,
                    listSampah: [
                        @forelse($jenisSampah ?? [] as $item)
                            { id: '{{ $item->id }}', nama: '{{ $item->nama_jenis }}', harga: {{ $item->harga_per_kg }} },
                        @empty
                            { id: '1', nama: 'Sampah Umum / Campuran', harga: 2000 },
                            { id: '2', nama: 'Plastik Botol Clean', harga: 3500 },
                            { id: '3', nama: 'Besi / Logam', harga: 5000 }
                        @endforelse
                    ],
                    pilihSampah(e) {
                        let selectedId = e.target.value;
                        let item = this.listSampah.find(i => i.id == selectedId);
                        this.hargaPerKg = item ? Number(item.harga) : 0;
                    }
                 }">
                
                <div>
                    <h3 class="text-xs font-black uppercase tracking-wider text-emerald-300 flex items-center gap-1.5">
                        <i class="fas fa-calculator"></i> SIMULASI KALKULATOR PENGHASILAN
                    </h3>
                    <p class="text-[10px] text-emerald-200/70 mt-0.5">Cek estimasi rupiah yang didapat sebelum diserahkan ke petugas.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end pt-4">
                    
                    <!-- SELECT JENIS SAMPAH -->
                    <div>
                        <label class="block font-bold text-emerald-200 mb-1 text-[10px] uppercase">JENIS SAMPAH</label>
                        <select @change="pilihSampah($event)" class="w-full bg-emerald-950/70 border border-emerald-700/80 rounded-xl p-2.5 text-white font-medium text-xs focus:outline-none focus:border-emerald-400">
                            <template x-for="item in listSampah" :key="item.id">
                                <option :value="item.id" x-text="item.nama + ' (Rp ' + Intl.NumberFormat('id-ID').format(item.harga) + '/Kg)'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- INPUT PERKIRAAN BERAT -->
                    <div>
                        <label class="block font-bold text-emerald-200 mb-1 text-[10px] uppercase">PERKIRAAN BERAT</label>
                        <div class="relative flex items-center">
                            <input type="number" min="0" step="0.5" x-model.number="berat" placeholder="0" 
                                   class="w-full bg-emerald-950/70 border border-emerald-700/80 rounded-xl p-2.5 pr-8 text-white font-bold text-xs focus:outline-none focus:border-emerald-400">
                            <span class="absolute right-3 text-xs text-emerald-400 font-bold">Kg</span>
                        </div>
                    </div>

                    <!-- BOX ESTIMASI SALDO -->
                    <div class="bg-emerald-950/80 border border-emerald-800 rounded-xl p-2.5 text-center flex flex-col justify-center min-h-[58px]">
                        <span class="block text-[8px] text-emerald-300 font-bold uppercase tracking-wider">ESTIMASI SALDO</span>
                        <span class="text-base font-black text-emerald-400" x-text="'Rp ' + Intl.NumberFormat('id-ID').format(berat * hargaPerKg)"></span>
                        <button type="button" @click="berat = 0" class="text-[8px] text-emerald-400/60 hover:text-emerald-300 underline mt-0.5">Reset Hitungan</button>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN (SEMPIT: 1 SPAN) - KHUSUS DETAIL PROFIL WARGA -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4 text-xs flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center border-b border-slate-100 pb-2.5 mb-3">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <i class="fas fa-user-circle text-emerald-600"></i> Detail Profil Warga
                        </span>
                        <span class="bg-emerald-100 text-emerald-700 text-[9px] px-2 py-0.5 rounded-full font-black">Aktif</span>
                    </div>
                    
                    <div class="space-y-1.5 text-slate-600">
                        <p><strong class="text-slate-800">Nama:</strong> {{ $nasabah->nama ?? (Auth::user()->name ?? 'korma') }}</p>
                        <p><strong class="text-slate-800">Alamat:</strong> {{ $nasabah->alamat ?? 'rumah dusun 1' }} (RT {{ $nasabah->rt ?? '00' }}/RW {{ $nasabah->rw ?? '00' }})</p>
                        <p><strong class="text-slate-800">No. HP:</strong> {{ $nasabah->no_hp ?? '09372575873' }}</p>
                    </div>
                </div>

                <!-- TARGET LEVEL -->
                <div class="border-t border-slate-100 pt-3 space-y-1.5">
                    <div class="flex justify-between text-[10px] font-bold text-slate-500 uppercase">
                        <span>TARGET LEVEL BERIKUTNYA</span>
                        <span class="text-emerald-600 font-black">{{ number_format($berat, 1) }} / {{ $targetBerikutnya }} KG</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $persenProgress }}%"></div>
                    </div>
                    <p class="text-[9px] text-slate-400 italic">Kumpulkan {{ max(0, $targetBerikutnya - $berat) }} Kg sampah lagi untuk naik pangkat!</p>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init();
        });
    </script>
</body>
</html>