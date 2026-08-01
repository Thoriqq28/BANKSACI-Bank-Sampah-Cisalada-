@extends('layouts.app')

@section('content')
<!-- Import AOS CSS -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<div class="p-4 sm:p-6 max-w-[100rem] mx-auto space-y-6">

    <!-- 1. HEADER & GLOBAL FILTER SECTION -->
    <div data-aos="fade-down" data-aos-duration="600" class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Laporan Rekapitulasi Transaksi</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Rekapitulasi setoran sampah dan penarikan saldo nasabah BANKSACI</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-start lg:justify-end">
            <!-- Filter Bulan & Tahun -->
            <div class="flex items-center gap-2 m-0">
                <!-- Dropdown Bulan -->
                <select id="filterBulan" onchange="fetchRealtimeLaporan()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs sm:text-sm rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00a877]/20 transition cursor-pointer font-medium">
                    @foreach([
                        '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', 
                        '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', 
                        '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
                    ] as $key => $month)
                        <option value="{{ $key }}" {{ ($bulan ?? date('m')) == $key ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>

                <!-- Dropdown Tahun -->
                <select id="filterTahun" onchange="fetchRealtimeLaporan()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs sm:text-sm rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#00a877]/20 transition cursor-pointer font-medium">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ ($tahun ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Export Buttons (Menggunakan Onclick JavaScript yang Sudah Diperbaiki) -->
            <button type="button" 
                    onclick="exportLaporan('excel')" 
                    class="flex items-center justify-center gap-1.5 bg-[#00a877] hover:bg-[#008f64] text-white px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition duration-200 shadow-sm hover:shadow-md cursor-pointer">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </button>

            <button type="button" 
                    onclick="exportLaporan('pdf')" 
                    class="flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium transition duration-200 shadow-sm hover:shadow-md cursor-pointer">
                <i class="fas fa-file-pdf"></i>
                <span>Export PDF</span>
            </button>
        </div>
    </div>

    @php
        $grandPemasukan     = $grandTotalPemasukan ?? 0;
        $grandPenarikan     = $grandTotalPenarikan ?? 0;
        $grandSaldo         = $grandSaldo ?? array_sum(array_column($rekapData ?? [], 'saldo'));
        $grandBeratKategori = $grandTotalKategori ?? [];
        $grandTotalKg       = $grandTotalKg ?? 0;
    @endphp

    <!-- 2. SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pemasukan</span>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-[#00a877] flex items-center justify-center">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
            <h3 id="stat-pemasukan" class="text-xl sm:text-2xl font-extrabold text-[#00a877]">Rp {{ number_format($grandPemasukan, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-1">Hasil setoran sampah periode ini</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="200" class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Penarikan</span>
                <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <h3 id="stat-penarikan" class="text-xl sm:text-2xl font-extrabold text-rose-500">Rp {{ number_format($grandPenarikan, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-1">Penarikan saldo oleh nasabah</p>
        </div>

        <div data-aos="fade-up" data-aos-delay="300" class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Saldo Nasabah</span>
                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <h3 id="stat-saldo" class="text-xl sm:text-2xl font-extrabold text-blue-600">Rp {{ number_format($grandSaldo, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-1">Total tabungan seluruh nasabah</p>
        </div>
    </div>

    <!-- 3. TABEL DATA REKAPITULASI -->
    <div data-aos="zoom-in" data-aos-delay="400" class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        
        <!-- Table Toolbar Header -->
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white">
            <div>
                <h2 class="text-base font-bold text-gray-800">Rincian Rekapitulasi Nasabah</h2>
                <p class="text-xs text-gray-400">Daftar akumulasi sampah dan saldo per nasabah</p>
            </div>
            
            <!-- Input Search Real-time -->
            <div class="relative w-full sm:w-64">
                <input type="text" 
                       id="filterSearch" 
                       name="search" 
                       value="{{ $search ?? '' }}" 
                       placeholder="Cari nama / No. Induk..." 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-xl pl-9 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#00a877]/20 transition font-medium">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Tabel Wrapper -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-xs text-left text-gray-600 border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-[11px]">
                        <th class="px-2 py-2.5 font-bold text-gray-700 text-center w-8">NO</th>
                        <th class="px-3 py-2.5 font-bold text-gray-700 min-w-[120px]">NAMA NASABAH</th>
                        
                        @foreach($kategoriList as $kategori)
                            <th class="px-1.5 py-2.5 text-center font-semibold text-gray-600 text-[10px] uppercase leading-tight max-w-[65px] break-words">{{ $kategori }}</th>
                        @endforeach

                        <th class="px-2 py-2.5 text-center font-bold text-emerald-800 bg-emerald-100/50 uppercase whitespace-nowrap">TOTAL (KG)</th>
                        <th class="px-3 py-2.5 text-right font-bold text-emerald-600 whitespace-nowrap">PEMASUKAN</th>
                        <th class="px-3 py-2.5 text-right font-bold text-rose-600 whitespace-nowrap">PENARIKAN</th>
                        <th class="px-3 py-2.5 text-right font-bold text-blue-600 whitespace-nowrap">SALDO SAAT INI</th>
                    </tr>
                </thead>
                <tbody id="tabelLaporanBody">
                    @forelse($rekapData as $index => $row)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition">
                            <td class="px-2 py-2 text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 font-semibold text-gray-900 truncate max-w-[150px]">{{ $row['nasabah']->nama ?? '-' }}</td>

                            @foreach($kategoriList as $kat)
                                @php $val = $row['berat_kategori'][$kat] ?? 0; @endphp
                                <td class="px-1.5 py-2 text-center font-mono text-[11px]">
                                    {{ $val > 0 ? number_format($val, 1, ',', '.') : '-' }}
                                </td>
                            @endforeach

                            <td class="px-2 py-2 text-center font-bold font-mono text-emerald-700 bg-emerald-50/30 whitespace-nowrap">
                                {{ ($row['total_berat_kg'] ?? 0) > 0 ? number_format($row['total_berat_kg'], 1, ',', '.') . ' Kg' : '-' }}
                            </td>

                            <td class="px-3 py-2 text-right font-semibold text-emerald-600 whitespace-nowrap">
                                Rp {{ number_format($row['total_pemasukan'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2 text-right font-semibold text-rose-600 whitespace-nowrap">
                                Rp {{ number_format($row['total_penarikan'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-2 text-right font-bold text-blue-600 whitespace-nowrap">
                                Rp {{ number_format($row['saldo'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($kategoriList) + 6 }}" class="text-center py-6 text-gray-400">Tidak ada data rekapitulasi.</td>
                        </tr>
                    @endforelse
                </tbody>
                
                <tfoot id="tabelLaporanFooter">
                    <tr class="bg-gray-100/80 font-bold text-gray-800 text-xs">
                        <td colspan="2" class="px-3 py-2.5 text-center uppercase tracking-wider text-[11px]">TOTAL AKUMULASI</td>
                        
                        @foreach($kategoriList as $kat)
                            @php $katVal = $grandTotalKategori[$kat] ?? 0; @endphp
                            <td class="px-1.5 py-2.5 text-center font-mono text-[11px]">
                                {{ $katVal > 0 ? number_format($katVal, 1, ',', '.') : '-' }}
                            </td>
                        @endforeach

                        <td class="px-2 py-2.5 text-center font-mono font-bold text-emerald-800 bg-emerald-200/50 whitespace-nowrap">
                            {{ number_format($grandTotalKg, 1, ',', '.') }} Kg
                        </td>

                        <td class="px-3 py-2.5 text-right text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($grandTotalPemasukan, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2.5 text-right text-rose-600 whitespace-nowrap">
                            Rp {{ number_format($grandTotalPenarikan, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2.5 text-right text-blue-600 whitespace-nowrap">
                            Rp {{ number_format($grandSaldo, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- JavaScript Dependencies -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let searchTimer = null;

    document.addEventListener('DOMContentLoaded', function () {
        AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });
        
        $(document).on('input keyup', '#filterSearch', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                fetchRealtimeLaporan();
            }, 400);
        });
    });

    // FUNGSI EKSPOR DIJALANKAN DENGAN BROWSER DIRECT DOWNLOAD
    function exportLaporan(type) {
        let bulan  = $('#filterBulan').val() || '';
        let tahun  = $('#filterTahun').val() || '';
        let search = $('#filterSearch').val() || '';

        // Tentukan URL Route Laravel Resmi
        let baseUrl = (type === 'excel') 
            ? "{{ route('laporan.excel') }}" 
            : "{{ route('laporan.pdf') }}";

        let url = `${baseUrl}?bulan=${bulan}&tahun=${tahun}&search=${encodeURIComponent(search)}`;

        // Ekspor langsung via window.location agar Session/Auth tidak terputus/kelempar
        window.location.href = url;
    }

    function formatRupiah(number) {
        let val = parseFloat(number);
        if (isNaN(val) || val === 0) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
    }

    function formatKg(number) {
        let val = parseFloat(number);
        return (!isNaN(val) && val > 0) 
            ? new Intl.NumberFormat('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }).format(val) 
            : '-';
    }

    function fetchRealtimeLaporan() {
        let bulan  = $('#filterBulan').val() || '';
        let tahun  = $('#filterTahun').val() || '';
        let search = $('#filterSearch').val() || '';

        $.ajax({
            url: "{{ route('laporan.index') }}",
            type: "GET",
            data: { bulan: bulan, tahun: tahun, search: search },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                if (response.status === 'success') {
                    // Update Summary Cards
                    $('#stat-pemasukan').text(formatRupiah(response.grandTotalPemasukan));
                    $('#stat-penarikan').text(formatRupiah(response.grandTotalPenarikan));
                    $('#stat-saldo').text(formatRupiah(response.grandSaldo));

                    let bodyHtml = '';
                    let kategoriList = response.kategoriList || [];

                    if (!response.rekapData || response.rekapData.length === 0) {
                        let totalCols = 6 + kategoriList.length;
                        bodyHtml = `
                            <tr>
                                <td colspan="${totalCols}" class="px-6 py-10 text-center text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300 block"></i>
                                    Tidak ditemukan nasabah atau data transaksi yang sesuai.
                                </td>
                            </tr>
                        `;
                    } else {
                        response.rekapData.forEach(function(row, index) {
                            let nasabah = row.nasabah || {};
                            let totalKg = parseFloat(row.total_berat_kg) || 0;
                            
                            bodyHtml += `
                                <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition">
                                    <td class="px-2 py-2 text-center text-gray-500">${index + 1}</td>
                                    <td class="px-3 py-2 font-semibold text-gray-900 truncate max-w-[150px]">${nasabah.nama || '-'}</td>
                            `;

                            kategoriList.forEach(function(kat) {
                                let berat = (row.berat_kategori && row.berat_kategori[kat]) ? row.berat_kategori[kat] : 0;
                                bodyHtml += `
                                    <td class="px-1.5 py-2 text-center font-mono text-[11px]">
                                        ${formatKg(berat)}
                                    </td>
                                `;
                            });

                            bodyHtml += `
                                    <td class="px-2 py-2 text-center font-bold font-mono text-emerald-700 bg-emerald-50/30 whitespace-nowrap">
                                        ${totalKg > 0 ? formatKg(totalKg) + ' Kg' : '-'}
                                    </td>
                                    <td class="px-3 py-2 text-right font-semibold text-emerald-600 whitespace-nowrap">${formatRupiah(row.total_pemasukan)}</td>
                                    <td class="px-3 py-2 text-right font-semibold text-rose-600 whitespace-nowrap">${formatRupiah(row.total_penarikan)}</td>
                                    <td class="px-3 py-2 text-right font-bold text-blue-600 whitespace-nowrap">${formatRupiah(row.saldo)}</td>
                                </tr>
                            `;
                        });
                    }

                    $('#tabelLaporanBody').html(bodyHtml);

                    // Update Footer Table
                    let grandKg = parseFloat(response.grandTotalKg) || 0;
                    let footerHtml = `
                        <tr class="bg-gray-100/80 font-bold text-gray-800 text-xs">
                            <td colspan="2" class="px-3 py-2.5 text-center uppercase tracking-wider text-[11px]">TOTAL AKUMULASI</td>
                    `;

                    kategoriList.forEach(function(kat) {
                        let totalKat = (response.grandTotalKategori && response.grandTotalKategori[kat]) ? response.grandTotalKategori[kat] : 0;
                        footerHtml += `
                            <td class="px-1.5 py-2.5 text-center font-mono text-[11px]">
                                ${formatKg(totalKat)}
                            </td>
                        `;
                    });

                    footerHtml += `
                            <td class="px-2 py-2.5 text-center font-mono font-bold text-emerald-800 bg-emerald-200/50 whitespace-nowrap">
                                ${formatKg(grandKg)} Kg
                            </td>
                            <td class="px-3 py-2.5 text-right text-emerald-600 whitespace-nowrap">${formatRupiah(response.grandTotalPemasukan)}</td>
                            <td class="px-3 py-2.5 text-right text-rose-600 whitespace-nowrap">${formatRupiah(response.grandTotalPenarikan)}</td>
                            <td class="px-3 py-2.5 text-right text-blue-600 whitespace-nowrap">${formatRupiah(response.grandSaldo)}</td>
                        </tr>
                    `;

                    $('#tabelLaporanFooter').html(footerHtml);
                }
            }
        });
    }
</script>
@endsection