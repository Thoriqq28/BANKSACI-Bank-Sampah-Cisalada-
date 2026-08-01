<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - BANKSACI</title>
    <!-- Tailwind CSS v4 Browser Setup -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome untuk Ikon Modern -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-2xl">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-b from-emerald-900 to-emerald-950 text-white p-8 text-center relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-800/40 rounded-full blur-xl"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-emerald-700/30 rounded-full blur-lg"></div>
            
            <div class="flex flex-col items-center text-center p-4">
                <div class="flex items-center justify-center gap-3 mb-2">
                    <img src="/images/logo.PNG" alt="Logo" class="w-10 h-10 object-contain shrink-0">
                    <span class="font-bold text-2xl tracking-wider text-white">BANK<span class="text-[#52d69b]">SACI</span></span>
                </div>
                <p class="text-xs text-gray-200 opacity-90">Pemulihan Akses Akun Tabungan Warga</p>
            </div>
        </div>

        <!-- FORM UTAMA -->
        <div class="p-8 space-y-5">
            <div>
                <h2 class="text-lg font-black text-slate-800 mb-1">Lupa Kata Sandi?</h2>
                <p class="text-xs text-slate-400 leading-relaxed">Jangan panik! Masukkan alamat email yang terdaftar, kami akan mengirimkan instruksi untuk menyetel ulang kata sandi Anda.</p>
            </div>

            <!-- Notifikasi Sukses dari Backend -->
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs font-semibold flex items-center gap-2 shadow-xs">
                    <i class="fas fa-check-circle text-emerald-500 shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Notifikasi Error Validasi Email -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-start gap-2 shadow-xs">
                    <i class="fas fa-exclamation-circle mt-0.5 text-rose-500 shrink-0"></i>
                    <div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form Mengarah ke Route POST password.email -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-wider">Alamat Email Terdaftar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-sm shadow-emerald-600/20 hover:shadow-md cursor-pointer text-xs">
                    <i class="fas fa-paper-plane text-xs"></i> Kirim Link Pemulihan
                </button>
            </form>

            <!-- Tautan Kembali -->
            <div class="text-center text-[11px] font-medium text-slate-400 border-t border-slate-50 pt-4">
                Sudah ingat kata sandi Anda? 
                <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition ml-0.5">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>

</body>
</html>