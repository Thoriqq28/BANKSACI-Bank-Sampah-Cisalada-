<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BANKSACI - Bank Sampah Cisalada</title>

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex flex-col items-center justify-center min-h-screen">

    <!-- NAVBAR UTAMA -->
    <nav class="w-full bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200/80 shadow-xs flex justify-center" 
         x-data="{ 
            activeTab: 'beranda', 
            mobileMenu: false,
            scrollTo(id) {
                this.activeTab = id;
                this.mobileMenu = false;
                const el = document.getElementById(id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                }
            }
         }">
        <div class="max-w-7xl w-full px-6 h-20 flex items-center justify-between">
            
            <!-- BRAND / LOGO -->
            <div class="flex items-center min-w-[200px]">
                <a href="#beranda" @click.prevent="scrollTo('beranda')" class="flex items-center gap-3 group">
                    <img src="/images/logo.PNG" alt="Logo BANKSACI" class="w-9 h-9 object-contain group-hover:rotate-12 transition-transform duration-300" onerror="this.onerror=null; this.src='https://placehold.co/100x100/2bb876/white?text=SACI'">
                    <span class="font-black text-xl tracking-wider text-slate-900">
                        BANK<span class="text-[#2bb876]">SACI</span>
                    </span>
                </a>
            </div>

            <!-- LINK NAVIGASI DESKTOP (GARIS & TEKS HIJAU DIJAMIN STAY) -->
            <div class="hidden md:flex items-center justify-center gap-10 text-sm font-semibold">
                
                <!-- Beranda -->
                <a href="#beranda" 
                   @click.prevent="scrollTo('beranda')"
                   :class="activeTab === 'beranda' ? 'text-emerald-600 font-bold border-b-2 border-emerald-600 pb-1' : 'text-slate-600 hover:text-emerald-600 pb-1'"
                   class="transition-all duration-200 cursor-pointer">
                    Beranda
                </a>

                <!-- Tentang Kami -->
                <a href="#tentang-kami" 
                   @click.prevent="scrollTo('tentang-kami')"
                   :class="activeTab === 'tentang-kami' ? 'text-emerald-600 font-bold border-b-2 border-emerald-600 pb-1' : 'text-slate-600 hover:text-emerald-600 pb-1'"
                   class="transition-all duration-200 cursor-pointer">
                    Tentang Kami
                </a>

                <!-- Layanan / Jenis Sampah -->
                <a href="#layanan" 
                   @click.prevent="scrollTo('layanan')"
                   :class="activeTab === 'layanan' ? 'text-emerald-600 font-bold border-b-2 border-emerald-600 pb-1' : 'text-slate-600 hover:text-emerald-600 pb-1'"
                   class="transition-all duration-200 cursor-pointer">
                    Layanan
                </a>

                <!-- Lokasi & Jam -->
                <a href="#lokasi" 
                   @click.prevent="scrollTo('lokasi')"
                   :class="activeTab === 'lokasi' ? 'text-emerald-600 font-bold border-b-2 border-emerald-600 pb-1' : 'text-slate-600 hover:text-emerald-600 pb-1'"
                   class="transition-all duration-200 cursor-pointer">
                    Lokasi & Jam
                </a>

            </div>

            <!-- TOMBOL MASUK SISTEM -->
            <div class="flex items-center justify-end min-w-[200px] gap-3">
                <a href="/login" class="bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:shadow-emerald-600/30 flex items-center gap-2 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sistem
                </a>

                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none transition">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

        </div>

        <!-- MOBILE MENU DRAWER -->
        <div x-show="mobileMenu" x-transition class="md:hidden bg-white border-b border-slate-200 px-6 py-4 flex flex-col gap-3 font-semibold text-sm text-slate-600 shadow-lg w-full">
            <a @click.prevent="scrollTo('beranda')" :class="activeTab === 'beranda' ? 'text-emerald-600 font-bold' : ''" class="hover:text-emerald-600 transition py-1 cursor-pointer">Beranda</a>
            <a @click.prevent="scrollTo('tentang-kami')" :class="activeTab === 'tentang-kami' ? 'text-emerald-600 font-bold' : ''" class="hover:text-emerald-600 transition py-1 cursor-pointer">Tentang Kami</a>
            <a @click.prevent="scrollTo('layanan')" :class="activeTab === 'layanan' ? 'text-emerald-600 font-bold' : ''" class="hover:text-emerald-600 transition py-1 cursor-pointer">Layanan</a>
            <a @click.prevent="scrollTo('lokasi')" :class="activeTab === 'lokasi' ? 'text-emerald-600 font-bold' : ''" class="hover:text-emerald-600 transition py-1 cursor-pointer">Lokasi & Jam</a>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 flex flex-col items-center">

        <!-- 1. HEADER / HERO SECTION -->
        <header id="beranda" class="w-full scroll-mt-24 py-12 md:py-20 flex items-center justify-center">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center w-full">
                
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left flex flex-col items-center lg:items-start" data-aos="fade-right">
                    <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                        Program KKN Desa Cisalada
                    </span>
                    
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight">
                        Ubah Sampah Jadi Berkah Bersama <br class="hidden md:block">
                        <span class="text-emerald-600">BANKSACI</span>
                    </h1>
                    
                    <p class="text-slate-600 text-base leading-relaxed max-w-xl font-normal">
                        Selamat datang di Bank Sampah Cisalada (BANKSACI), Purwakarta. Kami membantu warga mengelola sampah rumah tangga secara bijak, menjaga kebersihan desa, sekaligus menabung untuk masa depan yang lebih hijau.
                    </p>
                    
                    <div class="pt-2 flex flex-col sm:flex-row justify-center lg:justify-start items-center gap-4 w-full sm:w-auto">
                        <a href="#layanan" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-sm px-8 py-4 rounded-2xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            Lihat Harga Sampah
                        </a>
                        <a href="#tentang-kami" class="w-full sm:w-auto bg-white hover:bg-slate-100 text-slate-700 font-bold text-sm px-6 py-4 rounded-2xl border border-slate-300 transition-all text-center">
                            Pelajari Alur
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 flex justify-center items-center" data-aos="fade-left">
                    <div class="relative w-72 h-72 md:w-80 md:h-80 bg-emerald-200/60 rounded-full flex items-center justify-center shadow-inner animate-pulse">
                        <span class="text-8xl">♻️</span>
                        <div class="absolute -top-2 -right-2 bg-white p-4 rounded-2xl shadow-lg text-center border border-slate-100">
                            <p class="text-xs text-slate-500 font-medium">Total Nasabah</p>
                            <p class="text-lg font-bold text-emerald-600">100+ Warga</p>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- 2. SECTION: TENTANG KAMI -->
        <section id="tentang-kami" class="w-full scroll-mt-24 py-16 flex flex-col items-center" data-aos="fade-up">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Sistem Digital
                </span>
                <h2 class="text-3xl font-black text-slate-900 mt-3">Mudahnya Menabung di BANKSACI</h2>
                <p class="text-slate-500 text-sm mt-2">Ikuti 3 langkah mudah untuk mulai mengonversi sampahmu menjadi saldo tabungan.</p>
            </div>

            <div class="flex flex-wrap justify-center items-stretch gap-8 w-full max-w-6xl">
                <div class="w-full sm:w-80 bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold mb-4">1</div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Pilah Sampah</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Pisahkan sampah organik dan anorganik dari rumah sesuai dengan kategori yang ditentukan.</p>
                </div>

                <div class="w-full sm:w-80 bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold mb-4">2</div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Bawa & Timbang</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Bawa sampahmu ke pos BANKSACI. Petugas kami akan memeriksa jenis dan menimbang beratnya.</p>
                </div>

                <div class="w-full sm:w-80 bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all text-center flex flex-col items-center">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold mb-4">3</div>
                    <h3 class="font-bold text-lg text-slate-900 mb-2">Saldo Bertambah</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">Petugas menginput data, dan nilai konversi sampah langsung masuk ke buku tabungan digitalmu!</p>
                </div>
            </div>
        </section>

        <!-- 3. SECTION: LAYANAN -->
        <section id="layanan" class="w-full scroll-mt-24 py-16 flex flex-col items-center" data-aos="fade-up">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Edukasi & Acuan
                </span>
                <h2 class="text-3xl font-black text-slate-900 mt-3">Kategori Sampah Yang Diterima</h2>
                <p class="text-slate-500 text-sm mt-2">Pastikan sampah dalam keadaan bersih dan kering sebelum disetorkan.</p>
            </div>

            <div class="flex flex-wrap justify-center items-stretch gap-6 w-full max-w-6xl">
                
                <!-- Plastik -->
                <div class="w-full sm:w-80 bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition-all text-center flex flex-col justify-between">
                    <div>
                        <div class="bg-emerald-600 text-white p-4 font-bold text-center flex items-center justify-center gap-2">
                            <i class="fas fa-bottle-water"></i> Kategori Plastik
                        </div>
                        <div class="p-6 space-y-3 text-xs text-slate-600 flex flex-col items-center">
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-emerald-500"></i> Botol Air Mineral</div>
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-emerald-500"></i> Emberan & Baskom</div>
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-emerald-500"></i> Gelas Plastik Bersih</div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 border-t border-slate-100 font-black text-emerald-600 text-lg">
                        Rp 2.500 <span class="text-xs font-normal text-slate-400">/ kg</span>
                    </div>
                </div>

                <!-- Kertas -->
                <div class="w-full sm:w-80 bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition-all text-center flex flex-col justify-between">
                    <div>
                        <div class="bg-amber-600 text-white p-4 font-bold text-center flex items-center justify-center gap-2">
                            <i class="fas fa-box"></i> Kategori Kertas
                        </div>
                        <div class="p-6 space-y-3 text-xs text-slate-600 flex flex-col items-center">
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-amber-500"></i> Kardus Cokelat Bekas</div>
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-amber-500"></i> Kertas HVS & Buku</div>
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-amber-500"></i> Koran & Majalah</div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 border-t border-slate-100 font-black text-emerald-600 text-lg">
                        Rp 1.500 <span class="text-xs font-normal text-slate-400">/ kg</span>
                    </div>
                </div>

                <!-- Logam -->
                <div class="w-full sm:w-80 bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition-all text-center flex flex-col justify-between">
                    <div>
                        <div class="bg-slate-700 text-white p-4 font-bold text-center flex items-center justify-center gap-2">
                            <i class="fas fa-cubes"></i> Kategori Logam / Besi
                        </div>
                        <div class="p-6 space-y-3 text-xs text-slate-600 flex flex-col items-center">
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-slate-500"></i> Kaleng Minuman</div>
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-slate-500"></i> Besi Tua & Seng Atap</div>
                            <div class="flex items-center justify-center gap-2"><i class="fas fa-check text-slate-500"></i> Tembaga & Kabel Bekas</div>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 border-t border-slate-100 font-black text-emerald-600 text-lg">
                        Rp 4.000 <span class="text-xs font-normal text-slate-400">/ kg</span>
                    </div>
                </div>

            </div>
        </section>

        <!-- 4. SECTION: LOKASI & JAM OPERASIONAL -->
        <section id="lokasi" class="w-full scroll-mt-24 py-16 flex flex-col items-center" data-aos="fade-up">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">
                    Informasi Pos
                </span>
                <h2 class="text-3xl font-black text-slate-900 mt-3">Lokasi & Jam Operasional</h2>
                <p class="text-slate-500 text-sm mt-2">Silakan datang pada waktu operasional pos berikut untuk penyetoran.</p>
            </div>

            <div class="w-full max-w-5xl bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col md:flex-row gap-8 items-center justify-between">
                <div class="space-y-6 text-center md:text-left flex flex-col items-center md:items-start w-full md:w-1/2">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600 shrink-0"><i class="fas fa-map-marker-alt text-xl"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Lokasi Pos Utama</h4>
                            <p class="text-slate-500 text-xs mt-0.5">Kantor Kepala Desa Cisalada, Kec. Jatiluhur, Kabupaten Purwakarta.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600 shrink-0"><i class="fas fa-clock text-xl"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-base">Hari & Jam Buka</h4>
                            <p class="text-slate-500 text-xs mt-0.5">Setiap Hari Sabtu & Minggu | Pukul 08:00 - 12:00 WIB</p>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 bg-slate-100 h-52 rounded-2xl flex flex-col items-center justify-center text-slate-400 border border-slate-200">
                    <i class="fas fa-map-marked-alt text-4xl mb-2 text-emerald-600"></i>
                    <span class="text-xs font-semibold text-slate-600">Peta Pos Utama Desa Cisalada</span>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="w-full bg-slate-900 text-slate-400 py-8 border-t border-slate-800 text-center text-sm mt-12">
        <p>&copy; 2026 KKN Desa Cisalada Purwakarta. All Rights Reserved.</p>
        <p class="text-xs text-slate-500 mt-1">BANKSACI - Dibuat dengan ❤️ untuk lingkungan yang lebih bersih.</p>
    </footer>

    <!-- AOS Script Init -->
    <script>
        AOS.init({
            once: true,
            duration: 800
        });
    </script>
</body>
</html>