<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Harga Sampah - BANKSACI</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome & AlpineJS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js (Harus dimuat sebelum script init Alpine) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-700 min-h-screen">

    <!-- Kontainer Utama dengan Alpine.js Data -->
    <div x-data="katalogSampah()" class="max-w-5xl mx-auto px-4 py-8 md:py-12">
        
        <!-- HEADER DENGAN ANIMASI FADE-DOWN -->
        <div class="mb-10 text-center md:text-left flex flex-col md:flex-row md:items-end justify-between gap-4" data-aos="fade-down" data-aos-duration="600">
            <div>
                <a href="javascript:history.back()" class="text-slate-400 hover:text-emerald-600 transition-colors duration-200 text-sm font-semibold mb-4 inline-flex items-center gap-2 group">
                    <i class="fas fa-arrow-left transition-transform duration-200 group-hover:-translate-x-1"></i> Kembali
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Katalog <span class="text-emerald-600">Harga</span></h1>
                <p class="text-slate-500 mt-2 text-sm">Cek estimasi harga per kilogram sampah anorganik Anda hari ini.</p>
            </div>
            
            <!-- Area Pencarian -->
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input x-model="search" type="text" placeholder="Cari jenis sampah..." 
                       class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 shadow-xs transition-all duration-300">
            </div>
        </div>

        <!-- FILTER KATEGORI DENGAN ANIMASI FADE-UP -->
        <div class="flex overflow-x-auto pb-4 mb-6 gap-2 hide-scroll-bar" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
            <template x-for="tab in kategoriTabs" :key="tab.id">
                <button @click="kategori = tab.id"
                        :class="kategori === tab.id ? 'bg-slate-900 text-white shadow-md scale-105' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
                        class="px-5 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition-all duration-300 active:scale-95 flex items-center gap-2 cursor-pointer">
                    <i :class="tab.icon"></i> <span x-text="tab.nama"></span>
                </button>
            </template>
        </div>

        <!-- GRID KATALOG (DENGAN PERBAIKAN ANIMASI TRANSISI) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <template x-for="item in filteredSampah" :key="item.id">
                
                <!-- ELEMEN WRAPPER UNTUK MEMANGGIL x-transition DENGAN BENAR -->
                <div x-show="true"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xs hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group cursor-pointer flex flex-col justify-between">
                    
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <!-- IKON DENGAN EFEK MEMBESAR & ROTASI SAAT HOVER -->
                            <div :class="item.bg + ' ' + item.warna" class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-6 shadow-xs">
                                <i :class="item.icon"></i>
                            </div>
                            <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider" x-text="item.jenis"></span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors duration-200 mb-1" x-text="item.nama"></h3>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-end justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Harga per Kg</span>
                            <div class="text-2xl font-black text-emerald-600 tracking-tight transition-transform duration-200 group-hover:scale-105 origin-left">
                                Rp <span x-text="item.harga.toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </template>

            <!-- State Jika Pencarian Tidak Ditemukan -->
            <div x-show="filteredSampah.length === 0" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="col-span-full py-12 text-center bg-white rounded-[2rem] border border-dashed border-slate-300">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 animate-bounce">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="text-slate-900 font-bold mb-1">Sampah tidak ditemukan</h3>
                <p class="text-slate-500 text-sm">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE JS & AOS INIT -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('katalogSampah', () => ({
                search: '',
                kategori: 'semua',
                
                kategoriTabs: [
                    { id: 'semua', nama: 'Semua', icon: 'fas fa-layer-group' },
                    { id: 'plastik', nama: 'Plastik', icon: 'fas fa-bottle-water' },
                    { id: 'kertas', nama: 'Kertas & Kardus', icon: 'fas fa-box' },
                    { id: 'logam', nama: 'Besi & Logam', icon: 'fas fa-weight-hanging' },
                    { id: 'kaca', nama: 'Kaca', icon: 'fas fa-wine-glass' },
                ],

                sampah: [
                    { id: 1, nama: 'Botol Plastik (PET)', jenis: 'plastik', harga: 2500, icon: 'fas fa-bottle-water', warna: 'text-blue-500', bg: 'bg-blue-50' },
                    { id: 2, nama: 'Kardus Bekas', jenis: 'kertas', harga: 1500, icon: 'fas fa-box', warna: 'text-amber-600', bg: 'bg-amber-50' },
                    { id: 3, nama: 'Besi Padat', jenis: 'logam', harga: 3000, icon: 'fas fa-weight-hanging', warna: 'text-slate-600', bg: 'bg-slate-100' },
                    { id: 4, nama: 'Gelas Plastik (Cup)', jenis: 'plastik', harga: 2000, icon: 'fas fa-coffee', warna: 'text-blue-500', bg: 'bg-blue-50' },
                    { id: 5, nama: 'Kertas HVS/Buku', jenis: 'kertas', harga: 2000, icon: 'fas fa-book', warna: 'text-amber-600', bg: 'bg-amber-50' },
                    { id: 6, nama: 'Kaleng Aluminium', jenis: 'logam', harga: 4000, icon: 'fas fa-bolt', warna: 'text-slate-600', bg: 'bg-slate-100' },
                    { id: 7, nama: 'Botol Kaca/Beling', jenis: 'kaca', harga: 800, icon: 'fas fa-wine-glass', warna: 'text-teal-600', bg: 'bg-teal-50' },
                ],

                get filteredSampah() {
                    return this.sampah.filter(s => {
                        const matchSearch = s.nama.toLowerCase().includes(this.search.toLowerCase());
                        const matchKategori = this.kategori === 'semua' || s.jenis === this.kategori;
                        return matchSearch && matchKategori;
                    });
                }
            }))
        });

        // Inisialisasi AOS (Animate On Scroll)
        AOS.init({
            once: true
        });
    </script>

    <style>
        .hide-scroll-bar::-webkit-scrollbar { display: none; }
        .hide-scroll-bar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</body>
</html>