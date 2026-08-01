<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - BANKSACI</title>
    <!-- Tailwind CSS v4 Browser Setup -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome untuk Ikon Modern -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<!-- BACKGROUND DENGAN GAMBAR BACKGROUND2 -->
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4 bg-cover bg-center bg-no-repeat bg-fixed bg-[url('{{ asset('images/background4.png') }}')] relative"
      x-data="{ isLoading: false }">

    <!-- Tombol Kembali ke Landing Page (Pojok Kiri Atas) -->
    <a href="{{ url('/') }}" class="fixed top-5 left-5 inline-flex items-center gap-2 px-4 py-2 bg-white/80 hover:bg-white text-slate-700 hover:text-emerald-600 text-xs font-semibold rounded-full shadow-md backdrop-blur-md transition-all duration-200 z-40">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali Web Utama
    </a>

    <!-- OVERLAY GELAP & BLUR AGAR FORM MUDAH DIBACA -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-[3px] -z-0"></div>

    <!-- CONTAINER CARD LOGIN -->
    <div class="relative z-10 max-w-sm w-full bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden my-auto">
        
        <!-- HEADER CARD -->
        <div class="bg-gradient-to-b from-emerald-900 to-emerald-950 text-white py-8 px-6 text-center relative overflow-hidden">
            <!-- Dekorasi Efek Cahaya -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-800/40 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-20 h-20 bg-emerald-700/30 rounded-full blur-lg pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <!-- Logo Aplikasi -->
                <img src="{{ asset('images/logo1.PNG') }}" alt="Logo BANKSACI" class="w-14 h-14 object-contain mb-3 shrink-0">
                
                <!-- Judul -->
                <h1 class="font-black text-2xl tracking-wider uppercase mb-1">Masuk Akun</h1>
                
                <!-- Subtitle -->
                <p class="text-emerald-200/80 text-xs font-medium tracking-wide max-w-[240px] mx-auto">
                    Selamat datang kembali di sistem layanan BANKSACI
                </p>
            </div>
        </div>

        <!-- ISIAN FORM LOGIN -->
        <div class="p-6 space-y-5">
            
            <!-- Pesan Error / Status Notifikasi -->
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-xs">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-3 py-2 rounded-xl text-xs font-semibold flex items-start gap-2 shadow-xs">
                    <i class="fas fa-exclamation-circle mt-0.5 text-rose-500"></i>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" @submit="isLoading = true" class="space-y-4">
                @csrf

                <!-- Input Email / Username -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block ml-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-9 pr-4 py-2.5 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <!-- Input Kata Sandi -->
                <div class="space-y-1" x-data="{ show: false }">
                    <div class="relative">
                        <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block">Kata Sandi</label>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-9 pr-10 py-2.5 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                            placeholder="Masukkan kata sandi">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-emerald-600 cursor-pointer">
                            <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>

                    <!-- Remember Me & Lupa Sandi -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition">
                            <span class="text-xs text-slate-500 font-medium">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] text-emerald-600 hover:underline font-semibold">Lupa sandi?</a>
                        @endif
                    </div>
                </div>

                <!-- Tombol Masuk -->
                <div class="pt-2">
                    <button type="submit" 
                        :disabled="isLoading"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-3 rounded-xl transition duration-200 shadow-md shadow-emerald-600/20 hover:shadow-lg cursor-pointer flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed">
                        
                        <template x-if="!isLoading">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-sign-in-alt text-xs"></i> Masuk Sekarang
                            </span>
                        </template>

                        <template x-if="isLoading">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-spinner fa-spin text-xs"></i> Memproses Login...
                            </span>
                        </template>
                    </button>
                </div>
            </form>

            <!-- Link ke Halaman Registrasi -->
            <div class="text-center text-xs font-medium text-slate-400 border-t border-slate-100 pt-4">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition ml-1">
                    Daftar Warga
                </a>
            </div>
        </div>

    </div>

    <!-- 🌟 OVERLAY LOADING PAGE LOGO TONG SAMPAH 🌟 -->
    <div x-show="isLoading" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 bg-emerald-950/90 backdrop-blur-md flex flex-col items-center justify-center text-white p-4"
         style="display: none;">
        
        <!-- Center Logo Container dengan Ring Loading Putar -->
        <div class="relative flex items-center justify-center mb-6">
            
            <!-- Ring Spinner Putar (Neon Emerald) -->
            <div class="w-28 h-28 border-4 border-emerald-500/20 border-t-emerald-400 rounded-full animate-spin"></div>
            
            <!-- Logo Tong Sampah Daur Ulang -->
            <div class="absolute flex items-center justify-center p-3 bg-emerald-900/50 rounded-full backdrop-blur-sm animate-pulse">
                <img src="{{ asset('images/logo1.PNG') }}" alt="Logo BANKSACI" class="w-14 h-14 object-contain filter drop-shadow-[0_0_12px_rgba(52,211,153,0.6)]">
            </div>

        </div>

        <!-- Text Indicator -->
        <h3 class="font-black text-xl tracking-wider uppercase mb-1 text-center">
            BANK<span class="text-emerald-400">SACI</span>
        </h3>
        <p class="text-emerald-200/80 text-xs font-medium text-center max-w-xs animate-pulse">
            Memverifikasi data akun... Silakan tunggu sebentar.
        </p>
    </div>

</body>
</html>