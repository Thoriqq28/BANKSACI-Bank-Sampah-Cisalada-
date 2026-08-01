<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BANKSACI - Solusi Cerdas Bank Sampah Digital</title>

    <!-- Tailwind CSS v3 CDN (Stabil) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome & AlpineJS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS (Animate On Scroll) CSS & JS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        /* CSS Animasi Melayang (Floating) */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

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
        <!-- KANAN: TOMBOL LOGIN -->
<div class="flex items-center justify-end min-w-[200px] gap-3">
    <!-- PERUBAHAN/PENAMBAHAN PADA ATRIBUT LINK DI BAWAH INI -->
    <a href="{{ route('login') }}" 
       @click.prevent="isLoading = true; setTimeout(() => { window.location.href = '{{ route('login') }}' }, 600)"
       class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-emerald-600/30 flex items-center gap-2 cursor-pointer group whitespace-nowrap">
        <i class="fas fa-sign-in-alt group-hover:translate-x-0.5 transition-transform"></i> Masuk
    </a>

    ...
</div>
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 overflow-x-hidden" x-data="{ mobileMenu: false }">

    <!-- BACKGROUND ABSTRACT BLOBS -->
    <div class="absolute top-0 right-0 -z-10 w-[500px] h-[500px] bg-emerald-200/40 rounded-full filter blur-3xl opacity-70 translate-x-20 -translate-y-20 pointer-events-none animate-pulse"></div>
    <div class="absolute top-[25%] left-[-10%] -z-10 w-[400px] h-[400px] bg-teal-200/40 rounded-full filter blur-3xl opacity-60 pointer-events-none animate-pulse" style="animation-delay: 2s;"></div>

    <!-- NAVBAR UTAMA -->
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            
            <!-- KIRI: LOGO & BRAND -->
            <div class="flex items-center min-w-[200px]">
                <a href="#beranda" class="flex items-center gap-3 group">
                    <img src="/images/logo.PNG" alt="Logo BANKSACI" class="w-9 h-9 object-contain group-hover:rotate-12 transition-transform duration-300" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                    <span class="font-black text-xl tracking-wider text-slate-900">
                        BANK<span class="text-[#2bb876]">SACI</span>
                    </span>
                </a>
            </div>

       <!-- LINK NAVIGASI DESKTOP -->
        <div id="desktop-nav" x-data="{ activeTab: 'beranda' }" class="hidden md:flex items-center justify-center gap-10 text-sm font-semibold text-slate-600">
                <!-- BERANDA -->
                <a href="#beranda" 
                @click="activeTab = 'beranda'"
                :class="activeTab === 'beranda' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'"
                class="nav-link transition-colors py-2 relative group whitespace-nowrap">
                    Beranda
                    <span :class="activeTab === 'beranda' ? 'w-full' : 'w-0 group-hover:w-full'" 
                        class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-emerald-600 rounded-full transition-all duration-300"></span>
                </a>

                <!-- TENTANG KAMI -->
                <a href="#tentang-kami" 
                @click="activeTab = 'tentang-kami'"
                :class="activeTab === 'tentang-kami' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'"
                class="nav-link transition-colors py-2 relative group whitespace-nowrap">
                    Tentang Kami
                    <span :class="activeTab === 'tentang-kami' ? 'w-full' : 'w-0 group-hover:w-full'" 
                        class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-emerald-600 rounded-full transition-all duration-300"></span>
                </a>

                <!-- LAYANAN -->
                <a href="#layanan" 
                @click="activeTab = 'layanan'"
                :class="activeTab === 'layanan' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'"
                class="nav-link transition-colors py-2 relative group whitespace-nowrap">
                    Layanan
                    <span :class="activeTab === 'layanan' ? 'w-full' : 'w-0 group-hover:w-full'" 
                        class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-emerald-600 rounded-full transition-all duration-300"></span>
                </a>

                <!-- ARTIKEL -->
                <a href="#artikel" 
                @click="activeTab = 'artikel'"
                :class="activeTab === 'artikel' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'"
                class="nav-link transition-colors py-2 relative group whitespace-nowrap">
                    Artikel
                    <span :class="activeTab === 'artikel' ? 'w-full' : 'w-0 group-hover:w-full'" 
                        class="nav-indicator absolute bottom-0 left-0 h-0.5 bg-emerald-600 rounded-full transition-all duration-300"></span>
                </a>
            </div>

            <!-- SCRIPT OBSERVER UNTUK SPY SCROLL Halaman -->
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Ambil elemen section berdasarkan ID nya
                    const sectionIds = ['beranda', 'tentang-kami', 'layanan', 'artikel'];
                    const sections = sectionIds.map(id => document.getElementById(id)).filter(Boolean);
                    const navContainer = document.getElementById('desktop-nav');

                    // Gunakan IntersectionObserver untuk mendeteksi posisi scroll
                    const observerOptions = {
                        root: null,
                        rootMargin: '-20% 0px -60% 0px', // Aktif ketika section berada di area atas-tengah layar
                        threshold: 0
                    };

                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const activeId = entry.target.id;
                                // Update state Alpine.js secara otomatis saat scroll
                                if (navContainer && navContainer._x_dataStack) {
                                    navContainer._x_dataStack[0].activeTab = activeId;
                                }
                            }
                        });
                    }, observerOptions);

                    sections.forEach(section => observer.observe(section));
                });
            </script>

            <!-- KANAN: TOMBOL LOGIN -->
            <div class="flex items-center justify-end min-w-[200px] gap-3">
                <a href="{{ route('login') }}" class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-emerald-600/30 flex items-center gap-2 cursor-pointer group whitespace-nowrap">
                    <i class="fas fa-sign-in-alt group-hover:translate-x-0.5 transition-transform"></i> Masuk
                </a>

                <!-- Hamburger Button (Mobile) -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none transition">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

        </div>

        <!-- MOBILE MENU DRAWER -->
        <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-b border-slate-200 px-6 py-4 flex flex-col gap-3 font-semibold text-sm text-slate-600 shadow-lg">
            <a @click="mobileMenu = false" href="#beranda" class="hover:text-emerald-600 transition py-1">Beranda</a>
            <a @click="mobileMenu = false" href="#tentang-kami" class="hover:text-emerald-600 transition py-1">Tentang Kami</a>
            <a @click="mobileMenu = false" href="#layanan" class="hover:text-emerald-600 transition py-1">Layanan</a>
            <a @click="mobileMenu = false" href="#artikel" class="hover:text-emerald-600 transition py-1">Artikel</a>
        </div>
    </nav>

    <!-- SECTION: BERANDA (HERO) -->
    <header id="beranda" class="scroll-mt-24 max-w-7xl mx-auto px-6 py-12 md:py-20 min-h-[calc(100vh-80px)] flex items-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center w-full">
            
            <!-- LEFT COLUMN -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 bg-emerald-100/80 border border-emerald-300/60 rounded-full px-4 py-1.5 text-xs font-bold text-emerald-800 mx-auto lg:mx-0 shadow-2xs hover:scale-105 transition-transform">
                    <i class="fas fa-rocket text-[11px] text-emerald-600 animate-bounce"></i> Inovasi Digital Desa Cisalada Masa Depan
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.15] tracking-tight">
                    Rancangan Masa Depan <br class="hidden md:block">
                    <span class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 bg-clip-text text-transparent">Desa Cisalada Clean & Green</span>
                </h1>
                
                <p class="text-sm md:text-base text-slate-600 leading-relaxed max-w-xl mx-auto lg:mx-0 font-normal">
                    BANKSACI dirancang sebagai sistem ekosistem digital terpadu untuk memfasilitasi tabungan warga berbasis pemilahan sampah anorganik. Bersiaplah menjadi bagian dari perubahan lingkungan desa kita!
                </p>
                
                <div class="pt-2 flex flex-col sm:flex-row justify-center lg:justify-start items-center gap-4">
                    <!-- TARGET LINK KE SECTION FITUR DIGITAL -->
                    <a href="#fitur-sistem" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-bold text-sm px-8 py-4 rounded-2xl transition-all shadow-lg hover:shadow-xl hover:shadow-slate-900/20 flex items-center justify-center gap-2 group cursor-pointer">
                        Pelajari Fitur Digital
                        <i class="fas fa-arrow-down text-xs group-hover:translate-y-1 transition-transform"></i>
                    </a>
                    <a href="#panduan-pilah" class="w-full sm:w-auto bg-white hover:bg-slate-100 text-slate-700 font-bold text-sm px-6 py-4 rounded-2xl border border-slate-300/80 transition-all text-center shadow-xs hover:border-slate-400">
                        Panduan Pemilahan
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="lg:col-span-5 space-y-6 flex flex-col justify-center items-center lg:items-end" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                
                <!-- CARD TARGET PROGRAM -->
                <div class="animate-float w-full max-w-[380px] bg-gradient-to-br from-emerald-800 via-emerald-900 to-slate-950 text-white rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden transition-all duration-500 hover:scale-[1.03] hover:shadow-emerald-900/40 border border-emerald-600/30 group">
                    <div class="absolute -right-10 -top-10 w-36 h-36 bg-emerald-500/10 rounded-full group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                    
                    <div class="flex justify-between items-center mb-8">
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-400">Target Pelaksanaan</span>
                            <p class="text-[11px] text-slate-300 font-medium">Desa Cisalada Digital</p>
                        </div>
                        <div class="bg-emerald-700/40 p-2 rounded-2xl border border-emerald-500/30 shadow-inner flex items-center justify-center group-hover:rotate-12 transition-transform duration-300">
                            <img src="/images/logo1.PNG" alt="Logo BANKSACI" class="w-7 h-7 object-contain" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                        </div>
                    </div>
                    
                    <div class="space-y-1 relative z-10 mb-6">
                        <span class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider">Proyeksi Capaian Awal</span>
                        <h3 class="text-3xl font-black tracking-tight flex items-baseline gap-2">
                            <span>100% Digital</span>
                        </h3>
                        <p class="text-xs text-slate-300 font-light pt-1">Transparansi penuh dari penimbangan hingga pencatatan saldo.</p>
                    </div>

                    <div class="pt-4 border-t border-emerald-700/60 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-xs shrink-0 border border-emerald-400/30">
                            <i class="fas fa-bullhorn animate-pulse"></i>
                        </div>
                        <p class="text-[11px] text-emerald-100/90 leading-tight">
                            Persiapan sosialisasi & registrasi awal warga Desa Cisalada segera dimulai!
                        </p>
                    </div>
                </div>

                <!-- INFO STATS TARGET -->
                <div class="w-full max-w-[380px] bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs grid grid-cols-2 gap-3 text-center text-xs">
                    <div class="p-3 bg-slate-50/80 rounded-xl flex flex-col justify-center items-center gap-1 border border-slate-100 hover:border-emerald-300 transition-colors">
                        <i class="fas fa-bullseye text-emerald-600 text-base mb-0.5"></i>
                        <span class="block font-black text-slate-900 text-base">Target RT</span>
                        <span class="text-[11px] text-slate-500 font-medium">Seluruh Wilayah Desa</span>
                    </div>
                    <div class="p-3 bg-slate-50/80 rounded-xl flex flex-col justify-center items-center gap-1 border border-slate-100 hover:border-teal-300 transition-colors">
                        <i class="fas fa-shield-alt text-teal-600 text-base mb-0.5"></i>
                        <span class="block font-black text-slate-900 text-base">Aman & Akurat</span>
                        <span class="text-[11px] text-slate-500 font-medium">Sistem Terintegrasi</span>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- SECTION: TENTANG KAMI -->
    <section id="tentang-kami" class="scroll-mt-24 max-w-7xl mx-auto px-6 py-12" data-aos="fade-up" data-aos-duration="800">
        <div class="bg-white rounded-[2.5rem] p-8 md:p-14 shadow-sm border border-slate-200/80 text-center space-y-4 relative overflow-hidden group hover:border-emerald-300/80 transition-colors">
            <div class="absolute top-0 right-0 w-36 h-36 bg-emerald-50 rounded-full translate-x-16 -translate-y-16 pointer-events-none group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <span class="inline-block bg-emerald-100/70 text-emerald-800 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider mb-3">
                    Mengenal Rancangan Sistem
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Apa itu <span class="text-emerald-600">BANKSACI?</span></h2>
                <p class="text-slate-600 max-w-3xl mx-auto leading-relaxed text-sm md:text-base font-normal">
                    <strong class="text-slate-800">BANKSACI (Bank Sampah Cisalada)</strong> adalah perancangan sistem platform digital modern untuk warga Desa Cisalada. Platform ini dirancang untuk mempermudah tata kelola pemilahan sampah anorganik, mengedukasi masyarakat, serta mengonversi aksi menjaga lingkungan menjadi nilai tabungan yang transparan dan akuntabel.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION: GALERI LINGKUNGAN KITA (3 CARD GENERAL) -->
    <section class="max-w-7xl mx-auto px-6 py-12">
        <div class="text-center max-w-2xl mx-auto mb-10" data-aos="fade-up">
            <span class="bg-emerald-100/70 text-emerald-800 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">
                LINGKUNGAN KITA
            </span>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 mt-3">
                Membangun Cisalada yang Bersih & Hijau
            </h2>
            <p class="text-slate-500 text-xs md:text-sm mt-2 leading-relaxed">
                Mewujudkan pengelolaan sampah terpadu yang berkelanjutan untuk mendukung kebersihan dan kesejahteraan masyarakat Desa Cisalada.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- CARD 1: FOTO PLANG DESA -->
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="100">
                <img src="/images/plang-desa.jpeg" alt="Sinergi Pemerintahan Desa" class="w-full h-48 object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400/10b981/white?text=Sinergi+Desa'">
                <div class="p-5">
                    <h3 class="font-bold text-slate-900 text-base">Sinergi Pemerintahan Desa</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Dukungan dan kolaborasi dengan pemerintah desa dalam menciptakan lingkungan yang bersih dan teratur.
                    </p>
                </div>
            </div>

            <!-- CARD 2: FOTO TIM BANKSACI -->
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="200">
                <img src="/images/tim-banksaci.jpeg" alt="Kolaborasi & Pengelolaan" class="w-full h-48 object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400/0d9488/white?text=Tim+BANKSACI'">
                <div class="p-5">
                    <h3 class="font-bold text-slate-900 text-base">Kolaborasi & Pengelolaan</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Dikelola secara terstruktur melalui kerja sama tim penggerak program dan perangkat Desa Cisalada.
                    </p>
                </div>
            </div>

            <!-- CARD 3: FOTO WARGA DI SAWAH -->
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg transition duration-300" data-aos="fade-up" data-aos-delay="300">
                <img src="/images/warga-sawah.jpeg" alt="Pemberdayaan Lingkungan" class="w-full h-48 object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400/059669/white?text=Pemberdayaan+Lingkungan'">
                <div class="p-5">
                    <h3 class="font-bold text-slate-900 text-base">Pemberdayaan Lingkungan</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Mendorong peran aktif masyarakat dalam menjaga keasrian serta kelestarian ekosistem lokal.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- KELOMPOK SECTION: LAYANAN -->
    <div id="layanan" class="scroll-mt-24">

        <!-- 1. SECTION: FITUR DIGITAL SISTEM (TERHUBUNG TOMBOL HERO) -->
        <section id="fitur-sistem" class="scroll-mt-24 max-w-7xl mx-auto px-6 py-16">
            <div class="text-center mb-12 space-y-2" data-aos="fade-up">
                <span class="inline-block bg-emerald-100/70 text-emerald-800 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Teknologi Yang Disiapkan
                </span>
                <h2 class="text-3xl font-black text-slate-900">Rancangan Fitur Digital System</h2>
                <p class="text-slate-500 text-sm">Memberikan kemudahan akses bagi warga dan pengurus desa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-center space-y-4 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-2xl mx-auto group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Buku Tabungan Digital</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Setiap warga akan memiliki akun tabungan digital. Tidak perlu khawatir buku tabungan fisik hilang atau rusak.
                    </p>
                </div>

                <!-- Fitur 2 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-center space-y-4 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-teal-100 text-teal-700 rounded-2xl flex items-center justify-center text-2xl mx-auto group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Kalkulasi Otomatis</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Petugas hanya memasukkan berat timbangan material, dan sistem otomatis menghitung nominal Rupiah secara akurat.
                    </p>
                </div>

                <!-- Fitur 3 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-2 transition-all duration-300 text-center space-y-4 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center text-2xl mx-auto group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Riwayat Transaksi Real-time</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Seluruh riwayat penimbangan dan penarikan dana tercatat rapi di database serta dapat diakses kapan saja.
                    </p>
                </div>
            </div>
        </section>

        <!-- 2. SECTION: PANDUAN PEMILAHAN SAMPAH -->
        <section id="panduan-pilah" class="scroll-mt-24 max-w-7xl mx-auto px-6 py-16">
            <div class="text-center mb-12 space-y-2" data-aos="fade-up">
                <span class="inline-block bg-emerald-100/70 text-emerald-800 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Edukasi Masyarakat
                </span>
                <h2 class="text-3xl font-black text-slate-900">Kategori Sampah Yang Diterima</h2>
                <p class="text-slate-500 text-sm">Persiapan bagi warga untuk memilah sampah dari rumah sebelum disetorkan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Kategori Plastik -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="zoom-in" data-aos-delay="100">
                    <div class="bg-emerald-600 text-white p-4 font-bold text-center flex items-center justify-center gap-2">
                        <i class="fas fa-bottle-water"></i> Kategori Plastik
                    </div>
                    <div class="p-6 space-y-3 text-xs text-slate-600">
                        <div class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i> Botol & Gelas Air Mineral</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i> Botol Shampo / Bumbu Plastik</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-emerald-500"></i> Ember & Baskom Plastik Bekas</div>
                    </div>
                </div>

                <!-- Kategori Kertas & Kardus -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <div class="bg-amber-600 text-white p-4 font-bold text-center flex items-center justify-center gap-2">
                        <i class="fas fa-box"></i> Kategori Kertas & Dus
                    </div>
                    <div class="p-6 space-y-3 text-xs text-slate-600">
                        <div class="flex items-center gap-2"><i class="fas fa-check text-amber-500"></i> Kardus Cokelat Tebal & Kering</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-amber-500"></i> Kertas HVS, Buku, & Majalah</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-amber-500"></i> Koran Bekas</div>
                    </div>
                </div>

                <!-- Kategori Logam & Besi -->
                <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="zoom-in" data-aos-delay="300">
                    <div class="bg-slate-700 text-white p-4 font-bold text-center flex items-center justify-center gap-2">
                        <i class="fas fa-cubes"></i> Kategori Logam
                    </div>
                    <div class="p-6 space-y-3 text-xs text-slate-600">
                        <div class="flex items-center gap-2"><i class="fas fa-check text-slate-500"></i> Kaleng Minuman Alumunium</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-slate-500"></i> Besi Tua & Seng Atap</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-slate-500"></i> Tembaga & Kabel Bekas</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. SECTION: CEK HARGA ESTIMASI -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="text-center mb-12 space-y-2" data-aos="fade-up">
                <span class="inline-block bg-emerald-100/70 text-emerald-800 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Daftar Nilai Tukar (Acuan)
                </span>
                <h2 class="text-3xl font-black text-slate-900">Estimasi Patokan Harga</h2>
                <p class="text-slate-500 text-sm">Patokan kisaran harga per kg saat sistem resmi diluncurkan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Item 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-amber-100/70 text-amber-700 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Kertas & Kardus</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Kardus tebal, kertas HVS, koran.</p>
                    <div class="text-emerald-600 font-black text-xl pt-2 border-t border-slate-100">
                        Rp 1.500 <span class="text-xs font-semibold text-slate-400">/ kg</span>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-blue-100/70 text-blue-700 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-wine-bottle"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Plastik PET Bening</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Botol air mineral, gelas plastik.</p>
                    <div class="text-emerald-600 font-black text-xl pt-2 border-t border-slate-100">
                        Rp 3.000 <span class="text-xs font-semibold text-slate-400">/ kg</span>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-slate-200/70 text-slate-700 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Besi & Seng</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Kaleng minuman, besi tua.</p>
                    <div class="text-emerald-600 font-black text-xl pt-2 border-t border-slate-100">
                        Rp 4.500 <span class="text-xs font-semibold text-slate-400">/ kg</span>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-12 h-12 bg-emerald-100/70 text-emerald-700 rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Logam & Tembaga</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Kabel bekas, kuningan, alumunium.</p>
                    <div class="text-emerald-600 font-black text-xl pt-2 border-t border-slate-100">
                        Rp 12.000 <span class="text-xs font-semibold text-slate-400">/ kg</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. SECTION: LOKASI & MAPS -->
        <section class="max-w-7xl mx-auto px-6 py-16" data-aos="fade-up" data-aos-duration="1000">
            <div class="text-center mb-12 space-y-2">
                <span class="inline-block bg-emerald-100/70 text-emerald-800 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Lokasi Operasional
                </span>
                <h2 class="text-3xl font-black text-slate-900">Lokasi Penyetoran Bank Sampah Cisalada</h2>
                <p class="text-slate-500 text-sm">Pusat layanan penimbangan, penyetoran, dan pengelolaan Bank Sampah BANKSACI.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-200/80 shadow-xs items-stretch">
                <div class="lg:col-span-4 flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold shrink-0">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Kantor & Pusat Penyetoran BANKSACI</h3>
                                <p class="text-xs text-slate-500">Desa Cisalada</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1 hover:border-emerald-200 transition-colors">
                                <p class="font-bold text-slate-800 uppercase tracking-wider text-[10px] text-emerald-600">Alamat Lengkap</p>
                                <p class="text-slate-600 leading-relaxed font-medium">
                                    Jln Lapang Olahraga Kp. Cijambe, Desa Cisalada, Kec. Jatiluhur, Kabupaten Purwakarta, Jawa Barat 41152.
                                </p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1 hover:border-emerald-200 transition-colors">
                                <p class="font-bold text-slate-800 uppercase tracking-wider text-[10px] text-emerald-600">Jadwal Layanan</p>
                                <p class="text-slate-600 font-medium">Sabtu & Minggu</p>
                                <p class="text-slate-400">Pukul 08.00 - 14.00 WIB</p>
                            </div>
                        </div>
                    </div>

                    <a href="https://maps.app.goo.gl/wG9xUkjpnBFA3CvYA" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs px-5 py-4 rounded-2xl transition-all shadow-md hover:shadow-lg hover:shadow-emerald-600/30 flex items-center justify-center gap-2 cursor-pointer group">
                        <i class="fas fa-directions text-sm group-hover:rotate-12 transition-transform"></i> Buka Petunjuk Arah (Google Maps)
                    </a>
                </div>

                <div class="lg:col-span-8 rounded-2xl overflow-hidden border border-slate-200 shadow-inner bg-slate-100 relative min-h-[350px] lg:min-h-[400px]">
                    <iframe 
                        class="w-full h-full absolute inset-0 border-0"
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d305.3376388481833!2d107.4420999371244!3d-6.583942571289438!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690fa2ef93215f%3A0x2dc8a3d0ca10d25a!2sLapangan%20Sepakbola%20Cisalada!5e1!3m2!1sid!2sid!4v1784988619150!5m2!1sid!2sid"
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>

    </div>

    <!-- SECTION: ARTIKEL & EDUKASI -->
    <section id="artikel" class="scroll-mt-24 max-w-7xl mx-auto px-6 py-16">
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-2" data-aos="fade-up">
            <span class="inline-block bg-emerald-100/70 text-emerald-800 font-bold text-xs px-3.5 py-1 rounded-full uppercase tracking-wider">
                📚 Edukasi & Berita
            </span>
            <h2 class="text-3xl font-black text-slate-900">
                Artikel Terkini Seputar Bank Sampah
            </h2>
            <p class="text-xs md:text-sm text-slate-600">
                Pelajari tips pengelolaan lingkungan, daur ulang kreatif, dan kabar perkembangan lingkungan di Desa Cisalada.
            </p>
        </div>

        <!-- Grid Artikel -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Artikel 1 -->
            <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-aos="fade-up" data-aos-delay="100">
                <div class="relative overflow-hidden h-48 bg-emerald-950">
                    <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=600&q=80" 
                         alt="Memilah Sampah" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                    <span class="absolute top-3 left-3 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase shadow-md">
                        Edukasi
                    </span>
                </div>
                <div class="p-6 flex flex-col flex-grow justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-[11px] text-slate-400">
                            <span><i class="far fa-calendar-alt text-emerald-600 mr-1"></i> 24 Juli 2026</span>
                            <span>•</span>
                            <span><i class="far fa-clock text-emerald-600 mr-1"></i> 3 mnt baca</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base group-hover:text-emerald-600 transition-colors leading-snug">
                            Cara Efektif Memilah Sampah Anorganik dari Rumah Tangga
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            Langkah sederhana memisahkan sampah plastik, kertas, dan logam agar nilai jualnya tetap tinggi saat disetorkan ke BANKSACI.
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 gap-1.5 pt-2 group-hover:translate-x-1 transition-transform">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </article>

            <!-- Artikel 2 -->
            <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-aos="fade-up" data-aos-delay="200">
                <div class="relative overflow-hidden h-48 bg-emerald-950">
                    <img src="https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&w=600&q=80" 
                         alt="Ekonomi Sirkular" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                    <span class="absolute top-3 left-3 bg-teal-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase shadow-md">
                        Lingkungan
                    </span>
                </div>
                <div class="p-6 flex flex-col flex-grow justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-[11px] text-slate-400">
                            <span><i class="far fa-calendar-alt text-emerald-600 mr-1"></i> 18 Juli 2026</span>
                            <span>•</span>
                            <span><i class="far fa-clock text-emerald-600 mr-1"></i> 4 mnt baca</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base group-hover:text-emerald-600 transition-colors leading-snug">
                            Menjaga Ekosistem Desa dengan Tabungan Berbasis Sampah
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            Bagaimana kebiasaan menabung sampah dapat mengurangi jumlah limbah yang masuk ke TPA sekaligus menambah nilai ekonomi warga.
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 gap-1.5 pt-2 group-hover:translate-x-1 transition-transform">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </article>

            <!-- Artikel 3 -->
            <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-aos="fade-up" data-aos-delay="300">
                <div class="relative overflow-hidden h-48 bg-emerald-950">
                    <!-- 🟢 Gambar diganti ke tema Dashboard Data / Transparansi Digital -->
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80" 
                        alt="Transparansi Digital BANKSACI" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
                    <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase shadow-md">
                        Inovasi
                    </span>
                </div>
                <div class="p-6 flex flex-col flex-grow justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-[11px] text-slate-400">
                            <span><i class="far fa-calendar-alt text-emerald-600 mr-1"></i> 10 Juli 2026</span>
                            <span>•</span>
                            <span><i class="far fa-clock text-emerald-600 mr-1"></i> 5 mnt baca</span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base group-hover:text-emerald-600 transition-colors leading-snug">
                            Transparansi Digital: Catatan Saldo Real-time untuk Nasabah
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            Mengenal teknologi di balik platform BANKSACI yang memudahkan pencatatan timbangan sampah secara akurat dan tepercaya.
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 gap-1.5 pt-2 group-hover:translate-x-1 transition-transform">
                        <span>Baca Selengkapnya</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </article>

        </div>
    </section>

    <!-- FOOTER UTAMA -->
    <footer class="bg-slate-900 text-white border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-8 border-b border-slate-800">
                <div class="md:col-span-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="/images/logo.PNG" alt="Logo BANKSACI" class="w-8 h-8 object-contain" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                        <span class="font-black text-xl tracking-wider">
                            BANK<span class="text-[#2bb876]">SACI</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 max-w-sm leading-relaxed">
                        Sistem Informasi Pengelolaan & Tabungan Bank Sampah Cerdas Islami Desa Cisalada. Mengubah limbah menjadi berkah.
                    </p>
                </div>
                <div class="md:col-span-3 space-y-3">
                    <h4 class="font-bold text-sm text-slate-200">Navigasi Cepat</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#beranda" class="hover:text-emerald-400 transition">Beranda</a></li>
                        <li><a href="#tentang-kami" class="hover:text-emerald-400 transition">Tentang Kami</a></li>
                        <li><a href="#layanan" class="hover:text-emerald-400 transition">Layanan & Fitur</a></li>
                        <li><a href="#artikel" class="hover:text-emerald-400 transition">Artikel & Edukasi</a></li>
                    </ul>
                </div>
                <div class="md:col-span-3 space-y-3">
                    <h4 class="font-bold text-sm text-slate-200">Kontak Pengelola</h4>
                    <p class="text-xs text-slate-400">Desa Cisalada, Kec. Jatiluhur, Purwakarta</p>
                    <p class="text-xs text-emerald-400 font-semibold"><i class="fas fa-envelope mr-1"></i> info@banksaci.id</p>
                </div>
            </div>
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; 2026 BANKSACI Desa Cisalada. Hak Cipta Dilindungi.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-emerald-400 transition"><i class="fab fa-facebook text-base"></i></a>
                    <a href="#" class="hover:text-emerald-400 transition"><i class="fab fa-instagram text-base"></i></a>
                    <a href="#" class="hover:text-emerald-400 transition"><i class="fab fa-whatsapp text-base"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Inisialisasi AOS Animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                once: true,
                duration: 800,
                easing: 'ease-out-cubic'
            });
        });
    </script>
</body>
</html>