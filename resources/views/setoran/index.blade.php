@extends('layouts.app')

@section('title', 'Setoran Sampah - BANKSACI')
@section('page_name', 'Setoran Sampah')

@section('content')

    <!-- Judul Konten & Tombol Aksi Utama -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                Data Transaksi Setoran <span class="text-xl">📥</span>
            </h1>
            <p class="text-sm text-gray-500">Catatan riwayat warga yang menabung sampah.</p>
        </div>
        <div class="self-end sm:self-center">
            <a href="{{ route('setoran.create') }}" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#008f64] text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Input Setoran Baru
            </a>
        </div>
    </div>

    <!-- Card Tabel Setoran Sampah -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-4 px-6">TANGGAL</th>
                        <th class="py-4 px-4">NASABAH</th>
                        <th class="py-4 px-4">JENIS SAMPAH</th>
                        <th class="py-4 px-4 text-center">BERAT (KG)</th>
                        <th class="py-4 px-4 text-right">TOTAL PENDAPATAN</th>
                        <th class="py-4 px-6 text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600 font-medium">
                    {{-- Menggunakan variabel $setorans dari Controller --}}
                    @forelse($setorans ?? [] as $setoran)
                        <tr class="transition-colors duration-200 hover:bg-emerald-50/40">
                            <!-- Tanggal Setor -->
                            <td class="py-4 px-6 font-semibold text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($setoran->tanggal ?? $setoran->created_at)->format('d M Y') }}
                            </td>

                            <!-- Nama Nasabah -->
                            <td class="py-4 px-4 font-bold text-[#004e38] whitespace-nowrap">
                                {{ $setoran->nasabah->nama ?? $setoran->nama_nasabah ?? $setoran->nasabah_nama ?? 'Nasabah Terhapus' }}
                            </td>

                            <!-- JENIS SAMPAH -->
<td class="py-4 px-4 whitespace-nowrap font-medium text-gray-700">
    @php
        // 1. Ambil data berat dan total harga dari row setoran
        $berat = (float) ($setoran->total_berat ?? $setoran->berat ?? 0);
        $harga = (float) ($setoran->total_harga ?? $setoran->total_pendapatan ?? 0);

        $tampilJenis = '';

        // 2. Hitung harga per kg jika berat > 0
        if ($berat > 0 && $harga > 0) {
            $hargaPerKg = (int) round($harga / $berat);

            // 3. Cari data sampah di tabel kategori_sampah yang memiliki harga per kg tersebut
            // String nama berbentuk: "plastik | botol plastik | 3500"
            $dbMaster = \DB::table('kategori_sampah')
                ->where('nama', 'LIKE', "%| {$hargaPerKg}")
                ->orWhere('nama', 'LIKE', "%|{$hargaPerKg}")
                ->first();

            if ($dbMaster) {
                $rawNama = $dbMaster->nama;
            } else {
                // Pengecekan fallback sederhana jika format pipe tidak presisi
                $dbMaster = \DB::table('kategori_sampah')
                    ->where('nama', 'LIKE', "%{$hargaPerKg}%")
                    ->first();
                $rawNama = $dbMaster->nama ?? '';
            }
        } else {
            $rawNama = '';
        }

        // 4. Formatting Tampilan String Pipe ("plastik | botol plastik | 3500")
        if (!empty(trim($rawNama))) {
            $pecah = explode('|', $rawNama);
            if (count($pecah) >= 2) {
                // Menampilkan: Plastik - botol plastik
                $tampilJenis = ucfirst(trim($pecah[0])) . ' - ' . trim($pecah[1]);
            } else {
                $tampilJenis = ucfirst(trim($rawNama));
            }
        } else {
            // Fallback jika tidak ditemukan pencocokan harga
            $tampilJenis = 'Sampah Anorganik';
        }
    @endphp

    <span class="bg-emerald-50 text-emerald-800 px-2.5 py-1 rounded-lg text-xs font-semibold border border-emerald-200">
        {{ $tampilJenis }}
    </span>
</td>

                            <!-- Berat (Kg) -->
                            <td class="py-4 px-4 text-center font-bold text-gray-800">
                                {{ number_format($setoran->total_berat ?? $setoran->berat ?? 0, 2, ',', '.') }} Kg
                            </td>

                            <!-- Total Pendapatan -->
                            <td class="py-4 px-4 text-right font-extrabold text-[#00a877] whitespace-nowrap">
                                Rp {{ number_format($setoran->total_harga ?? $setoran->total_pendapatan ?? $setoran->pendapatan ?? 0, 0, ',', '.') }}
                            </td>

                            <!-- Badge Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="bg-emerald-50 text-[#00a877] px-3 py-1 rounded-full text-xs font-bold border border-emerald-100 shadow-2xs">
                                    {{ $setoran->status ?? 'Berhasil' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 font-normal">
                                Belum ada transaksi setoran sampah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection