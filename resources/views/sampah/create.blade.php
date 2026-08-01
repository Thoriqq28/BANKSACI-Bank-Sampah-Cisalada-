<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Setoran - BANKSACI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-emerald-900 text-white fixed top-0 bottom-0 left-0 p-5 flex flex-col justify-between z-50 shadow-xl transform transition-transform duration-300 ease-in-out md:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div>
            <div class="flex items-center justify-between mb-8 px-2">
                <div class="flex items-center gap-2">
                    <img src="/images/logo.PNG" alt="Logo" class="w-8 h-8 object-contain shrink-0">
                    <span class="font-bold text-xl tracking-wider text-white">BANK<span class="text-[#52d69b]">SACI</span></span>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-emerald-200 hover:text-white cursor-pointer">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <nav class="space-y-2">
                <a href="/dashboard-ui" class="flex items-center gap-3 text-emerald-100 hover:bg-emerald-800 hover:text-white px-4 py-3 rounded-xl font-medium transition">
                    <i class="fas fa-chart-pie w-5"></i> Dashboard
                </a>
                <a href="/nasabah-ui" class="flex items-center gap-3 text-emerald-100 hover:bg-emerald-800 hover:text-white px-4 py-3 rounded-xl font-medium transition">
                    <i class="fas fa-users w-5"></i> Data Nasabah
                </a>
                <a href="/sampah-ui" class="flex items-center gap-3 text-emerald-100 hover:bg-emerald-800 hover:text-white px-4 py-3 rounded-xl font-medium transition">
                    <i class="fas fa-boxes w-5"></i> Kategori Sampah
                </a>
                <a href="/setoran-ui" class="flex items-center gap-3 bg-emerald-800 text-white px-4 py-3 rounded-xl font-medium transition shadow-md">
                    <i class="fas fa-arrow-down w-5"></i> Setoran Sampah
                </a>
                <a href="/penarikan-ui" class="flex items-center gap-3 text-emerald-100 hover:bg-emerald-800 hover:text-white px-4 py-3 rounded-xl font-medium transition">
                    <i class="fas fa-money-bill-wave w-5"></i> Penarikan Saldo
                </a>
            </nav>
        </div>
    </aside>

    <!-- Overlay Backdrop HP -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black/40 backdrop-blur-xs md:hidden"
         style="display: none;"></div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 md:p-8 ml-0 md:ml-64 transition-all duration-300">
        <header class="flex justify-between items-center mb-6 md:mb-8 bg-white p-4 rounded-2xl shadow-sm gap-4">
            <div class="flex items-center gap-3 text-gray-500 text-xs md:text-sm min-w-0">
                <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-emerald-700 p-1.5 rounded-lg border border-gray-200 bg-gray-50 cursor-pointer shrink-0">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center gap-1.5 truncate">
                    <a href="/dashboard-ui" class="hover:text-emerald-600">Dashboard</a> <span>/</span>
                    <a href="/setoran-ui" class="hover:text-emerald-600">Setoran Sampah</a> <span>/</span>
                    <span class="text-gray-800 font-semibold truncate">Input Baru</span>
                </div>
            </div>
        </header>

        <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mx-auto">
            <!-- Header Form -->
            <div class="bg-gradient-to-r from-emerald-700 to-emerald-800 px-6 py-5 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-base md:text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-weight-hanging"></i> Form Timbangan Sampah
                    </h2>
                    <p class="text-[11px] text-emerald-100 mt-0.5">Pastikan timbangan akurat sebelum menyimpan data.</p>
                </div>
                <span class="bg-emerald-900/40 text-emerald-200 text-[10px] font-semibold px-2.5 py-1 rounded-full border border-emerald-500/30">
                    Mode Database
                </span>
            </div>

            <!-- Form Body -->
            <form action="/setoran/store" method="POST" class="p-5 md:p-6 space-y-5">
                @csrf

                <!-- Hidden Input untuk membawa Nama Jenis Sampah asli ke Controller -->
                <input type="hidden" name="jenis_sampah" id="jenis_sampah_hidden" value="{{ old('jenis_sampah') }}">
                <input type="hidden" name="total_pendapatan" id="total_pendapatan_hidden" value="{{ old('total_pendapatan', 0) }}">

                @if ($errors->any())
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl text-xs">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- 1. PILIH NASABAH -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-600 uppercase mb-2">
                        Pilih Nasabah (Warga) <span class="text-red-500">*</span>
                    </label>
                    <select name="nasabah_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-xs md:text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition" required>
    <option value="">-- Cari Nama Warga --</option>
    @php
    $listNasabah = $nasabahs ?? \DB::table('nasabah')->get();
@endphp
@foreach($listNasabah as $nasabah)
    <option value="{{ $nasabah->id }}" {{ old('nasabah_id') == $nasabah->id ? 'selected' : '' }}>
        {{ $nasabah->kode_nasabah ?? 'NSB-'.$nasabah->id }} - {{ $nasabah->nama }}
    </option>
@endforeach
</select>
                </div>

                <!-- 2. JENIS SAMPAH & BERAT -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-2 flex items-center gap-1.5">
                            <i class="fas fa-recycle text-gray-400"></i> Jenis Sampah Disetor <span class="text-red-500">*</span>
                        </label>
                        <select id="sampah_select" name="sampah_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-xs md:text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition" required>
                            <option value="">-- Pilih Jenis Sampah --</option>
                            @php
    $listJenisSampah = $jenisSampahs ?? \DB::table('kategori_sampah')->get();
@endphp
@foreach($listJenisSampah as $sampah)
    @php
        $pecah = explode('|', $sampah->nama);
        
        $kategori  = count($pecah) >= 1 ? trim($pecah[0]) : '';
        $namaJenis = count($pecah) >= 2 ? trim($pecah[1]) : $sampah->nama;
        $hargaBeli = count($pecah) >= 3 ? (int) trim($pecah[2]) : ($sampah->harga_per_kg ?? $sampah->harga_beli ?? 0);

        // Nama lengkap yang akan disimpan & tampil di option
        $namaLengkap = count($pecah) >= 2 ? "{$kategori} - {$namaJenis}" : $sampah->nama;
    @endphp
    <option value="{{ $sampah->id }}" data-harga="{{ $hargaBeli }}">
        {{ $namaLengkap }} (Rp {{ number_format($hargaBeli, 0, ',', '.') }}/kg)
    </option>
@endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-600 uppercase mb-2">
                            Berat Timbangan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0.01" id="berat_input" name="berat" value="{{ old('berat') }}" placeholder="0.0" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-xs md:text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition" required>
                            <span class="absolute right-3 top-3 text-xs font-bold text-gray-400">Kg</span>
                        </div>
                    </div>
                </div>

                <!-- 3. HIGHLIGHT TOTAL PENDAPATAN -->
                <div class="bg-emerald-50/70 border border-emerald-100 rounded-2xl p-4 flex justify-between items-center">
                    <div>
                        <span class="text-[11px] font-semibold text-emerald-800 block">Estimasi Pendapatan Nasabah</span>
                        <span id="label_detail_hitung" class="text-[10px] text-emerald-600">Pilih sampah dan masukkan berat timbangan</span>
                    </div>
                    <div id="display_total" class="text-xl md:text-2xl font-black text-emerald-700">
                        Rp 0
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="/setoran-ui" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition text-xs md:text-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-medium hover:bg-emerald-700 transition shadow-md hover:shadow-emerald-600/20 text-xs md:text-sm cursor-pointer flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Setoran
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- SCRIPT KALKULASI OTOMATIS & SET VALUE HIDDEN -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sampahSelect = document.getElementById('sampah_select');
            const beratInput = document.getElementById('berat_input');
            const displayTotal = document.getElementById('display_total');
            const labelDetailHitung = document.getElementById('label_detail_hitung');
            const jenisSampahHidden = document.getElementById('jenis_sampah_hidden');
            const totalPendapatanHidden = document.getElementById('total_pendapatan_hidden');

            function hitungTotal() {
                const selectedOption = sampahSelect.options[sampahSelect.selectedIndex];
                const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                const nama = selectedOption.getAttribute('data-nama') || '';
                const berat = parseFloat(beratInput.value) || 0;

                const total = harga * berat;

                // Simpan nilai nama & total ke hidden input untuk dikirim ke controller
                jenisSampahHidden.value = nama;
                totalPendapatanHidden.value = total;

                // Update tampilan harga & detail
                displayTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');

                if (harga > 0 && berat > 0) {
                    labelDetailHitung.innerText = `${berat} Kg x Rp ${harga.toLocaleString('id-ID')}/Kg`;
                } else {
                    labelDetailHitung.innerText = 'Pilih sampah dan masukkan berat timbangan';
                }
            }

            sampahSelect.addEventListener('change', hitungTotal);
            beratInput.addEventListener('input', hitungTotal);

            // Jalankan sekali saat load jika ada nilai lama (old input)
            hitungTotal();
        });
    </script>
</body>
</html>