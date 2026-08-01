@extends('layouts.app', ['activeMenu' => 'sampah'])

@section('title', 'Tambah Jenis Sampah - BANKSACI')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Breadcrumb Topbar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex items-center justify-between">
        <div class="text-xs text-slate-500 font-medium">
            <a href="/dashboard-ui" class="hover:text-emerald-600 transition">Dashboard</a>
            <span class="mx-1.5 text-slate-300">/</span>
            <a href="/sampah-ui" class="hover:text-emerald-600 transition">Kategori Sampah</a>
            <span class="mx-1.5 text-slate-300">/</span>
            <span class="text-slate-800 font-semibold">Tambah Jenis</span>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <!-- Header Card Hijau -->
        <div class="bg-[#00895c] p-6 text-white flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Tambah Jenis Sampah Baru
                </h2>
                <p class="text-xs text-emerald-100 mt-1">Data akan disesuaikan dengan format Laporan Menyeluruh.</p>
            </div>
            <a href="/sampah-ui" class="px-3 py-1.5 bg-[#00704b] hover:bg-[#005e44] text-emerald-100 text-xs font-semibold rounded-lg transition">
                ← Kembali
            </a>
        </div>

        <!-- Form Body -->
        <form action="{{ route('jenis.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Kategori Utama -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                    KATEGORI UTAMA <span class="text-red-500">*</span>
                </label>
                <select name="kategori_utama" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition">
                    <option value="" disabled selected>-- Pilih Kategori Utama --</option>
                    <option value="PLASTIK">PLASTIK</option>
                    <option value="BESI">BESI</option>
                    <option value="LOGAM">LOGAM</option>
                    <option value="KERTAS">KERTAS</option>
                    <option value="KACA">KACA</option>
                    <option value="ORGANIK">ORGANIK</option>
                    <option value="ANORGANIK">ANORGANIK</option>
                </select>
            </div>

            <!-- Nama Jenis Sampah -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                    NAMA JENIS SAMPAH <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_jenis" required placeholder="Contoh: Botol Mineral / Kaleng Soda" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition">
            </div>

            <!-- Harga Konversi -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                    HARGA KONVERSI / BELI (PER KG) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3.5 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="harga_beli" required placeholder="3500" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs md:text-sm rounded-xl p-3 pl-9 focus:outline-none focus:border-[#00895c] focus:bg-white focus:ring-2 focus:ring-[#00895c]/20 transition">
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="/sampah-ui" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#00895c] hover:bg-[#00704b] text-white text-xs font-semibold rounded-xl flex items-center gap-2 shadow-md transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Jenis Sampah
                </button>
            </div>
        </form>
    </div>
</div>
@endsection