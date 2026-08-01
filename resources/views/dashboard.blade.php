@extends('layouts.app')

@section('content')
<!-- CSS Dependencies (AOS & FontAwesome) -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    /* Styling Dashboard Card */
    .dash-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .dash-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.06);
    }

    /* Banner Gradient Fixed Background */
    .welcome-banner-bg {
        background: linear-gradient(135deg, #004e38 0%, #00895c 100%) !important;
    }

    /* Pulsing Dot Online */
    .dot-online {
        animation: pulseOnline 2s infinite;
    }
    @keyframes pulseOnline {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }
</style>

<div class="p-6 max-w-7xl mx-auto space-y-6 bg-[#f8fafc]">

    <!-- 1. NOTIFIKASI IURAN (OPSIONAL DARI CONTROLLER) -->
    @if(isset($statusIuran) && $statusIuran)
        <div data-aos="fade-down" data-aos-duration="600" class="p-4 rounded-2xl flex items-center justify-between border shadow-sm transition-all duration-300 {{ $statusIuran == 'nunggak' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $statusIuran == 'nunggak' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600' }}">
                    <i class="fas {{ $statusIuran == 'nunggak' ? 'fa-exclamation-triangle' : 'fa-bell' }} text-lg"></i>
                </div>
                <div class="text-sm">
                    {!! $notifikasiIuran !!}
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- 2. HERO WELCOME BANNER (WARNA DIPERTANAN & TEKS TEGAS) -->
    <div data-aos="fade-down" data-aos-duration="800" class="welcome-banner-bg relative overflow-hidden rounded-3xl p-6 md:p-8 text-white shadow-md">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-[11px] font-bold tracking-wider uppercase text-white">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#52d69b] dot-online"></span>
                    SISTEM OPERASIONAL AKTIF • ROLE: {{ strtoupper($role ?? 'Admin') }}
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white drop-shadow-xs">
                    Selamat Datang, Admin! 👋
                </h1>
                <p class="text-emerald-100 text-xs md:text-sm max-w-xl font-medium leading-relaxed">
                    Ringkasan performa Bank Sampah hari ini. Pantau setoran, nasabah baru, dan arus kas secara realtime.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ url('/setoran-ui/tambah') }}" class="px-5 py-2.5 bg-white text-[#00895c] hover:bg-emerald-50 font-bold text-xs rounded-xl shadow transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-plus-circle text-sm"></i>
                    <span>Input Setoran</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. REALTIME STATISTIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Nasabah Aktif -->
        <div data-aos="zoom-in-up" data-aos-delay="100" class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nasabah Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="fas fa-users text-sm"></i>
                </div>
            </div>
            <h3 id="stat-nasabah" class="text-2xl font-black text-gray-800">0</h3>
            <p class="text-[11px] text-emerald-500 mt-2 font-medium flex items-center gap-1">
                <i class="fas fa-arrow-up text-[9px]"></i>
                <span>Terdaftar di sistem</span>
            </p>
        </div>

        <!-- Card 2: Sampah Terkumpul -->
        <div data-aos="zoom-in-up" data-aos-delay="200" class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Sampah Terkumpul</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <i class="fas fa-recycle text-sm"></i>
                </div>
            </div>
            <h3 id="stat-sampah" class="text-2xl font-black text-gray-800">0 Kg</h3>
            <p class="text-[11px] text-gray-400 mt-2 font-medium">Akumulasi seluruh kategori</p>
        </div>

        <!-- Card 3: Total Pemasukan -->
        <div data-aos="zoom-in-up" data-aos-delay="300" class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pemasukan</span>
                <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center">
                    <i class="fas fa-wallet text-sm"></i>
                </div>
            </div>
            <h3 id="stat-pemasukan" class="text-2xl font-black text-[#00895c]">Rp 0</h3>
            <p class="text-[11px] text-emerald-500 mt-2 font-medium">Kas terkumpul hari ini</p>
        </div>

        <!-- Card 4: Penarikan Saldo -->
        <div data-aos="zoom-in-up" data-aos-delay="400" class="dash-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Penarikan Saldo</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-sm"></i>
                </div>
            </div>
            <h3 id="stat-penarikan" class="text-2xl font-black text-rose-500">Rp 0</h3>
            <p class="text-[11px] text-rose-400 mt-2 font-medium">Permintaan cair saldo</p>
        </div>

    </div>

    <!-- 4. GRAFIK SETORAN BULANAN & AKSI CEPAT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Grafik Setoran Sampah -->
        <div data-aos="fade-right" data-aos-delay="500" class="dash-card lg:col-span-2 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Grafik Setoran Sampah</h2>
                    <p class="text-xs text-gray-400">Tren statistik volume setoran (Kg)</p>
                </div>
                <span class="text-xs font-medium px-3 py-1 bg-gray-100 text-gray-500 rounded-full">Tahun {{ date('Y') }}</span>
            </div>
            <div class="h-64 relative p-1">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <!-- Aksi Cepat & Navigasi -->
        <div data-aos="fade-left" data-aos-delay="600" class="dash-card p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-800 mb-1">Aksi Cepat</h2>
                <p class="text-xs text-gray-400 mb-6">Akses menu utama dalam satu klik</p>

                <div class="space-y-3">
                    <!-- Registrasi Nasabah -->
                    <a href="{{ url('/nasabah-ui/tambah') }}" class="flex items-center justify-between p-3 bg-gray-50/80 hover:bg-emerald-50/50 rounded-2xl transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100/60 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition">
                                <i class="fas fa-user-plus text-xs"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-700 group-hover:text-emerald-600">Registrasi Nasabah</span>
                        </div>
                        <i class="fas fa-chevron-right text-[10px] text-gray-300 group-hover:translate-x-1 transition"></i>
                    </a>

                    <!-- Kelola Harga Sampah -->
                    <a href="{{ url('/sampah-ui') }}" class="flex items-center justify-between p-3 bg-gray-50/80 hover:bg-blue-50/50 rounded-2xl transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-100/60 text-blue-600 flex items-center justify-center group-hover:scale-105 transition">
                                <i class="fas fa-box-open text-xs"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-700 group-hover:text-blue-600">Kelola Harga Sampah</span>
                        </div>
                        <i class="fas fa-chevron-right text-[10px] text-gray-300 group-hover:translate-x-1 transition"></i>
                    </a>

                    <!-- Pencairan Saldo -->
                    <a href="{{ url('/penarikan-ui/tambah') }}" class="flex items-center justify-between p-3 bg-gray-50/80 hover:bg-purple-50/50 rounded-2xl transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-100/60 text-purple-600 flex items-center justify-center group-hover:scale-105 transition">
                                <i class="fas fa-wallet text-xs"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-700 group-hover:text-purple-600">Pencairan Saldo</span>
                        </div>
                        <i class="fas fa-chevron-right text-[10px] text-gray-300 group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400 font-medium">
                <span>BANKSACI v2.0</span>
                <span class="text-emerald-600 font-semibold">System Connected</span>
            </div>
        </div>

    </div>

</div>

<!-- JS Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Inisialisasi Animate On Scroll (AOS)
        AOS.init({ duration: 600, once: true });

        // 2. Animasi Count-Up Realtime
        animateCounter("stat-nasabah", 0, {{ $totalNasabah ?? 0 }}, 1200, "");
        animateCounter("stat-sampah", 0, {{ $totalSampah ?? 0 }}, 1200, " Kg");
        animateCounter("stat-pemasukan", 0, {{ $totalPemasukan ?? 0 }}, 1500, "", true);
        animateCounter("stat-penarikan", 0, {{ $totalPenarikan ?? 0 }}, 1500, "", true);

        // 3. ChartJS Inisialisasi Realtime
        const labelsMonths = {!! json_encode($months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']) !!};
        const dataBerat = {!! json_encode($beratData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};

        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(0, 137, 92, 0.25)');
        gradient.addColorStop(1, 'rgba(0, 137, 92, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsMonths,
                datasets: [{
                    label: 'Setoran (Kg)',
                    data: dataBerat,
                    borderColor: '#00895c',
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#00895c'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                // Padding internal agar lingkaran titik grafik di kanan & atas tidak terpotong
                layout: {
                    padding: {
                        top: 15,
                        right: 20,
                        left: 10,
                        bottom: 5
                    }
                },

                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' Kg';
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        beginAtZero: true 
                    }
                }
            }
        });
    });

    // Helper Animasi Count-Up Angka
    function animateCounter(id, start, end, duration, suffix = "", isRupiah = false) {
        const obj = document.getElementById(id);
        if (!obj) return;

        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeProgress = 1 - Math.pow(1 - progress, 4);
            const currentValue = Math.floor(easeProgress * (end - start) + start);

            if (isRupiah) {
                obj.innerHTML = 'Rp ' + currentValue.toLocaleString('id-ID') + suffix;
            } else {
                obj.innerHTML = currentValue.toLocaleString('id-ID') + suffix;
            }

            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
</script>
@endsection