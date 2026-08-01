<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Nasabah - BANKSACI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- SIDEBAR DARI FILE MASTER (SAMA DENGAN PAGE INDEX) -->
    @include('layouts.sidebar')

    <!-- Overlay Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs md:hidden"
         style="display: none;"></div>

    <!-- MAIN CONTENT (SAMA DENGAN MASTER DATA NASABAH) -->
    <main class="flex-1 p-6 md:p-8 min-w-0 transition-all duration-300">
        
        <!-- HEADER BREADCRUMB -->
        <header class="flex justify-between items-center mb-6 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-3 text-slate-500 text-xs md:text-sm">
                <button @click="sidebarOpen = true" class="md:hidden text-slate-600 hover:text-emerald-600">
                    <i class="fas fa-bars text-base"></i>
                </button>
                <div class="flex items-center gap-2">
                    <a href="/dashboard-ui" class="hover:text-emerald-600 transition">Dashboard</a>
                    <span class="text-slate-300">/</span>
                    <a href="/nasabah-ui" class="hover:text-emerald-600 transition">Data Nasabah</a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-800 font-semibold">Tambah</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-xs">A</div>
                <span class="text-xs font-semibold text-slate-700 hidden sm:inline">Admin Desa</span>
            </div>
        </header>

        <!-- CARD FORM TAMBAH NASABAH -->
        <div class="max-w-3xl bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden mx-auto">
            <!-- Header Card Hijau Solid -->
            <div class="bg-emerald-700 p-6 text-white flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-lg flex items-center gap-2">
                        <i class="fas fa-user-plus text-lg"></i> Formulir Nasabah Baru
                    </h2>
                    <p class="text-emerald-100 text-xs mt-1">Silakan isi data diri warga Desa Cisalada dengan lengkap.</p>
                </div>
                <a href="/nasabah-ui" class="px-3 py-1.5 bg-emerald-800/60 hover:bg-emerald-800 text-emerald-100 text-xs font-semibold rounded-lg transition">
                    ← Kembali
                </a>
            </div>

            <!-- Form Body -->
            <form action="/nasabah-ui" method="POST" class="p-6 md:p-8 space-y-5">
                @csrf
                
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                        NAMA LENGKAP NASABAH <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" required placeholder="Contoh: Ahmad Subagja"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>

                <!-- Grid: No HP & ID Rekening -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                            NO. HANDPHONE / WA <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_hp" required placeholder="Contoh: 0812xxxx"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                            ID REKENING (OTOMATIS)
                        </label>
                        <input type="text" disabled placeholder="SACI-XXX (Otomatis Sistem)"
                               class="w-full bg-slate-100 border border-slate-200 text-slate-400 text-xs md:text-sm font-mono rounded-xl p-3 cursor-not-allowed">
                    </div>
                </div>

                <!-- Grid 3 Kolom: RT, RW, No Rumah -->
                <div class="grid grid-cols-3 gap-3 md:gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                            RT <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="rt" required placeholder="01"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white transition text-center">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                            RW <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="rw" required placeholder="02"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white transition text-center">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                            NO. RUMAH
                        </label>
                        <input type="text" name="nomor_rumah" placeholder="12A (Opsional)"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white transition text-center">
                    </div>
                </div>

                <!-- Nama Kampung / Jalan -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                        NAMA KAMPUNG / JALAN <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" rows="2" required placeholder="Contoh: Kp. Margaluyu"
                              class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white transition"></textarea>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="/nasabah-ui" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl flex items-center gap-2 shadow-md transition cursor-pointer">
                        <i class="fas fa-save"></i> Simpan Data Warga
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>