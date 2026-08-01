<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Warga - BANKSACI</title>
    <!-- Tailwind CSS v4 Browser Setup -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome untuk Ikon Modern -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js untuk Fitur Intip Sandi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<!-- BACKGROUND MENGGUNAKAN CLASS TAILWIND & HELPER ASSET -->
<body class="font-sans antialiased min-h-screen flex items-center justify-center p-4 bg-cover bg-center bg-no-repeat bg-fixed bg-[url('{{ asset('images/background1.png') }}')] relative">

    <!-- OVERLAY GELAP & BLUR -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-[2px] -z-0"></div>

    <!-- CONTAINER CARD RAMPING (max-w-md) CENTERED -->
    <div class="relative z-10 max-w-md w-full bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border overflow-hidden my-auto">
        
        <!-- HEADER IDENTITAS RINGKAS -->
        <div class="bg-gradient-to-b from-emerald-900 to-emerald-950 text-white py-6 px-6 text-center relative overflow-hidden">
            <!-- Dekorasi Background Header -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-800/40 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-20 h-20 bg-emerald-700/30 rounded-full blur-lg pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <!-- Logo didalam Header Card -->
                <img src="{{ asset('images/logo.PNG') }}" alt="Logo BANKSACI" class="w-12 h-12 object-contain mb-3 shrink-0">
                
                <!-- Judul -->
                <h1 class="font-black text-xl tracking-wider uppercase mb-1">Daftar Akun Warga</h1>
                
                <!-- Subtitle -->
                <p class="text-emerald-200/80 text-[10px] font-medium tracking-wide max-w-[260px] mx-auto">
                    Silakan lengkapi data diri Anda untuk menjadi nasabah
                </p>
            </div>
        </div>

        <!-- FORM REGISTRASI -->
        <div class="p-6 space-y-4">
            
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-3 py-2 rounded-xl text-[11px] font-semibold flex items-start gap-2 shadow-xs">
                    <i class="fas fa-exclamation-circle mt-0.5 text-rose-500"></i>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- SEKSYEN 1: DATA PERSONAL -->
                <div class="space-y-2">
                    <div class="border-b border-slate-100 pb-0.5">
                        <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider block">1. Informasi Akun</span>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-user text-[11px]"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full pl-9 pr-4 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                            placeholder="Nama Lengkap sesuai KTP">
                    </div>

                    <!-- Alamat Email & No HP Berdampingan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-envelope text-[11px]"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-9 pr-3 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                                placeholder="nama@email.com">
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fas fa-phone text-[11px]"></i>
                            </span>
                            <input type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                                class="w-full pl-9 pr-3 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- SEKSYEN 2: DATA ALAMAT -->
                <div class="space-y-2">
                    <div class="border-b border-slate-100 pb-0.5">
                        <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider block">2. Detail Alamat Rumah</span>
                    </div>

                    <!-- Alamat Jalan -->
                    <div class="relative">
                        <span class="absolute top-2.5 left-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-[11px]"></i>
                        </span>
                        <textarea name="alamat" rows="1" required
                            class="w-full pl-9 pr-4 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200 resize-none"
                            placeholder="Alamat Jalan / Dusun / Kampung">{{ old('alamat') }}</textarea>
                    </div>

                    <!-- Blok Rumah (DUSUN / RW / RT) -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="relative flex items-center">
                            <span class="absolute left-2.5 text-[10px] font-bold text-slate-400 pointer-events-none">Dusun</span>
                            <input type="text" name="dusun" value="{{ old('dusun') }}" required placeholder="Mekar"
                                class="w-full text-right pr-2.5 pl-12 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
                        </div>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[10px] font-bold text-slate-400 pointer-events-none">RW</span>
                            <input type="text" name="rw" value="{{ old('rw') }}" required placeholder="002"
                                class="w-full text-right pr-3 pl-8 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
                        </div>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-[10px] font-bold text-slate-400 pointer-events-none">RT</span>
                            <input type="text" name="rt" value="{{ old('rt') }}" required placeholder="003"
                                class="w-full text-right pr-3 pl-8 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- SEKSYEN 3: KATA SANDI -->
                <div class="space-y-2">
                    <div class="border-b border-slate-100 pb-0.5">
                        <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider block">3. Proteksi Akun</span>
                    </div>

                    <!-- Password -->
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-lock text-[11px]"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-9 pr-9 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                            placeholder="Kata Sandi (Min. 6 karakter)">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-emerald-600 cursor-pointer">
                            <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                            <i class="fas fa-shield-alt text-[11px]"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                            class="w-full pl-9 pr-9 py-2 bg-slate-50/90 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                            placeholder="Ulangi Kata Sandi">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-emerald-600 cursor-pointer">
                            <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Kirim -->
                <div class="pt-1">
                    <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2.5 rounded-xl transition duration-200 shadow-sm shadow-emerald-600/20 hover:shadow-md cursor-pointer flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus text-[10px]"></i> Mendaftar Sekarang
                    </button>
                </div>
            </form>

            <!-- Link Kembali ke Login -->
            <div class="text-center text-[11px] font-medium text-slate-400 border-t border-slate-100 pt-3">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition">
                    Masuk di sini
                </a>
            </div>
        </div>

    </div>

</body>
</html>