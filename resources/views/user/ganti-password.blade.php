<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - BANKSACI</title>
    <!-- Tailwind CSS v3 (Stabil) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f2f7f5] font-sans antialiased min-h-screen text-gray-800">

    <!-- ======================================================== -->
    <!-- TOP NAVBAR (KONSISTEN DENGAN DASHBOARD USER) -->
    <!-- ======================================================== -->
    <nav class="bg-[#064e3b] text-white px-4 md:px-8 py-3.5 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo & Badge -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain">
                    <span class="font-black text-xl tracking-wider text-white">BANK<span class="text-[#34d399]">SACI</span></span>
                </a>
                <span class="hidden sm:inline-block bg-[#10b981]/20 text-[#34d399] text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-widest border border-[#10b981]/30">
                    PAHLAWAN HIJAU
                </span>
            </div>

            <!-- Profil Ringkas & Action Button Header -->
            <div class="flex items-center gap-3 md:gap-5">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold leading-tight">{{ $nasabah->nama ?? auth()->user()->name }}</p>
                    <p class="text-[10px] text-emerald-200">ID: {{ $nasabah->no_rekening ?? $nasabah->nik ?? 'BS-0001' }}</p>
                </div>
                
                <a href="/" class="bg-[#047857] hover:bg-[#059669] text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-home"></i>
                    <span class="hidden md:inline">Web Utama</span>
                </a>

                <form action="/logout" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 text-red-200 text-xs p-2.5 rounded-xl transition cursor-pointer" title="Keluar / Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- ======================================================== -->
    <!-- MAIN CONTENT CONTAINER -->
    <!-- ======================================================== -->
    <main class="max-w-7xl mx-auto px-4 md:px-8 py-8">
        
        <!-- Breadcrumb / Tombol Kembali -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('dashboard.user') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#064e3b] hover:text-[#047857] bg-white px-4 py-2.5 rounded-xl border border-emerald-100 shadow-sm transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <span class="text-xs text-gray-500 font-medium">Pengaturan Keamanan Akun</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- FORM CARD GANTI PASSWORD (2 Kolom Utama) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-emerald-50">
                <div class="border-b border-gray-100 pb-5 mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-50 text-[#064e3b] rounded-2xl flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">Ubah Password Akun</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Pastikan password baru kamu terdiri dari kombinasi yang aman.</p>
                    </div>
                </div>

                <!-- Alert Sukses -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('password.user.update') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Saat Ini</label>
                        <input type="password" name="password_sekarang" required 
                               placeholder="Masukkan password lama kamu"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#064e3b] text-sm transition">
                        @error('password_sekarang')
                            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password Baru</label>
                        <input type="password" name="password_baru" required 
                               placeholder="Minimal 8 karakter"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#064e3b] text-sm transition">
                        @error('password_baru')
                            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" required 
                               placeholder="Ulangi password baru kamu"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-[#064e3b] text-sm transition">
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="bg-[#10b981] hover:bg-[#059669] text-white font-bold px-6 py-3 rounded-xl text-xs transition shadow-md hover:shadow-lg cursor-pointer flex items-center gap-2">
                            <i class="fas fa-shield-alt"></i> Update Password Now
                        </button>
                        <a href="{{ route('dashboard.user') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold px-6 py-3 rounded-xl text-xs transition text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- CARD DETAIL PROFIL & INFORMASI TAMBAHAN (1 Kolom Samping) -->
            <div class="space-y-6">
                <!-- Card Detail Profil Singkat -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-emerald-50">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                        <h3 class="font-bold text-xs text-gray-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fas fa-user-circle text-[#10b981]"></i> Informasi Akun
                        </h3>
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Aktif</span>
                    </div>
                    
                    <div class="space-y-3 text-xs">
                        <div>
                            <p class="text-gray-400">Nama Lengkap:</p>
                            <p class="font-bold text-gray-800">{{ $nasabah->nama ?? auth()->user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Email Akun:</p>
                            <p class="font-bold text-gray-800">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">No. Handphone:</p>
                            <p class="font-bold text-gray-800">{{ $nasabah->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Banner Tips Keamanan -->
                <div class="bg-[#064e3b] text-white rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <i class="fas fa-lock absolute -bottom-4 -right-4 text-7xl text-white/5"></i>
                    <h4 class="font-bold text-sm mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#34d399]"></i> Tips Keamanan
                    </h4>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        Jangan pernah bagikan kata sandi kamu kepada siapapun, termasuk petugas Bank Sampah. Selalu perbarui password secara berkala.
                    </p>
                </div>
            </div>

        </div>
    </main>

</body>
</html>