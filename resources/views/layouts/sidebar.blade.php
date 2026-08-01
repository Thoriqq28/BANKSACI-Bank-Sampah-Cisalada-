<!-- SIDEBAR UTAMA STAF (DIKUNCI LEBAR MUTLAK) -->
<aside style="width: 270px !important; min-width: 270px !important; max-width: 270px !important;" class="bg-[#004e38] text-white h-screen p-5 flex flex-col justify-between hidden md:flex shrink-0 select-none z-20 overflow-x-hidden">
    <!-- Bagian Atas: Logo & Menus -->
    <div class="flex flex-col h-full overflow-y-auto overflow-x-hidden">
        
        <!-- Logo & Brand -->
        <div class="flex items-center gap-3 mb-8 px-2 pt-2 group cursor-pointer">
            <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0 sidebar-logo">
            <span class="font-bold text-xl tracking-wider text-white">
                BANK<span class="text-[#52d69b]">SACI</span>
            </span>
        </div>
        
        <!-- Navigasi Menu Dinamis -->
        <nav class="space-y-2">
            
            <!-- Dashboard -->
            <a href="/dashboard-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('dashboard*') ? 'active' : '' }}">
                @if(request()->is('dashboard*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                @endif
                <i class="fas fa-th-large w-5 text-center text-lg"></i>
                <span>Dashboard</span>
            </a>

            <!-- Data Nasabah -->
            <a href="/nasabah-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('nasabah*') ? 'active' : '' }}">
                @if(request()->is('nasabah*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                @endif
                <i class="fas fa-users w-5 text-center text-lg"></i>
                <span>Data Nasabah</span>
            </a>

            <!-- Kategori Sampah -->
            <a href="/sampah-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('sampah*') || request()->is('jenis*') || request()->routeIs('jenis.*') || (isset($activeMenu) && $activeMenu == 'sampah') ? 'active' : '' }}">
                @if(request()->is('sampah*') || request()->is('jenis*') || request()->routeIs('jenis.*') || (isset($activeMenu) && $activeMenu == 'sampah'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                @endif
                <i class="fas fa-box w-5 text-center text-lg"></i>
                <span>Kategori Sampah</span>
            </a>

            <!-- Setoran Sampah -->
            <a href="/setoran-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('setoran*') ? 'active' : '' }}">
                @if(request()->is('setoran*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                @endif
                <i class="fas fa-arrow-down w-5 text-center text-lg icon-arrow"></i>
                <span>Setoran Sampah</span>
            </a>

            <!-- Penarikan Saldo -->
            <a href="/penarikan-ui" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('penarikan*') ? 'active' : '' }}">
                @if(request()->is('penarikan*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                @endif
                <i class="fas fa-money-bill-wave w-5 text-center text-lg"></i>
                <span>Penarikan Saldo</span>
            </a>

            <!-- Laporan Menyeluruh -->
            <a href="/laporan-menyeluruh" class="sidebar-item relative flex items-center gap-3.5 px-4 py-3 rounded-xl font-medium {{ request()->is('laporan*') ? 'active' : '' }}">
                @if(request()->is('laporan*'))
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#52d69b] rounded-r-full shadow-sm animate-pulse"></span>
                @endif
                <i class="fas fa-file-invoice-dollar w-5 text-center text-lg"></i>
                <span>Laporan Menyeluruh</span>
            </a>

        </nav>

        <!-- Bagian Bawah: Web Utama & Logout -->
        <div class="mt-auto pt-4 border-t border-[#005d43] space-y-2 pb-2">
            <a href="/" target="_blank" class="sidebar-item-web flex items-center gap-3.5 text-[#52d69b] text-base px-4 py-2.5 rounded-xl font-medium">
                <i class="fas fa-globe w-5 text-center text-lg icon-globe"></i> 
                <span>Lihat Web Utama</span>
            </a>
            
            <form action="/logout" method="POST" class="m-0">
                @csrf
                <button type="submit" class="sidebar-item-logout w-full flex items-center gap-3.5 text-[#ffa3a3] text-base px-4 py-2.5 rounded-xl font-medium text-left cursor-pointer">
                    <i class="fas fa-sign-out-alt w-5 text-center rotate-180 text-lg icon-logout"></i> 
                    <span>Keluar / Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- CSS PERBAIKAN TOTAL -->
<style>
    /* 1. Menghilangkan Scrollbar Horizontal & Mengatur Tampilan */
    aside, aside div {
        overflow-x: hidden !important;
        -ms-overflow-style: none;  /* Edge / IE */
        scrollbar-width: none;  /* Firefox */
    }
    
    aside::-webkit-scrollbar, aside div::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }

    /* 2. Styling Dasar Item Menu */
    .sidebar-item {
        color: #d1d5db;
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out !important;
    }
    
    /* State Aktif (Tanpa TranslateX agar sejajar) */
    .sidebar-item.active {
        background-color: #005e44 !important;
        color: #ffffff !important;
        transform: none !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Hover hanya untuk item tidak aktif */
    .sidebar-item:not(.active):hover {
        background-color: rgba(0, 94, 68, 0.6) !important;
        color: #ffffff !important;
    }

    /* Animasi Ikon Membesar */
    .sidebar-item i {
        transition: transform 0.25s ease-in-out, color 0.25s ease-in-out !important;
    }
    .sidebar-item:hover i {
        transform: scale(1.15) !important;
        color: #52d69b !important;
    }

    /* Web Utama Hover */
    .sidebar-item-web {
        transition: all 0.25s ease-in-out !important;
    }
    .sidebar-item-web i.icon-globe {
        transition: transform 0.25s ease-in-out !important;
    }
    .sidebar-item-web:hover {
        background-color: rgba(0, 94, 68, 0.4) !important;
        color: #ffffff !important;
    }

    /* Logout Hover */
    .sidebar-item-logout {
        transition: all 0.25s ease-in-out !important;
    }
    .sidebar-item-logout i.icon-logout {
        transition: transform 0.25s ease-in-out !important;
    }
    .sidebar-item-logout:hover {
        background-color: rgba(239, 68, 68, 0.15) !important;
        color: #fca5a5 !important;
    }

    /* Logo Hover */
    .sidebar-logo {
        transition: transform 0.3s ease-in-out !important;
    }
    .group:hover .sidebar-logo {
        transform: scale(1.1) rotate(-5deg) !important;
    }
</style>