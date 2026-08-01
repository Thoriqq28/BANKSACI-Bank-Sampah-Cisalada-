@extends('layouts.app')

@section('title', 'Tambah Jenis Sampah - BANKSACI')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Tambah Jenis Sampah Baru</h1>
            <p class="text-xs text-gray-500">Masukkan kategori utama, nama jenis sampah, dan harga konversi.</p>
        </div>
        <a href="/sampah-ui" class="text-xs text-gray-500 hover:text-gray-700 font-semibold">&larr; Kembali</a>
    </div>

    <form action="{{ route('jenis.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kategori Utama</label>
            <select name="kategori_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-xs md:text-sm focus:outline-none focus:border-emerald-500">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama ?? $kat->kategori_utama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Nama Jenis Sampah <span class="text-red-500">*</span></label>
            <input type="text" name="nama" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-xs md:text-sm focus:outline-none focus:border-emerald-500" placeholder="Contoh: Kaleng / Botol Plastik" required>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Harga Konversi (Per Kg) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_beli" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-xs md:text-sm focus:outline-none focus:border-emerald-500" placeholder="Contoh: 9000" required>
        </div>

        <div class="pt-4 flex justify-end gap-2">
            <a href="/sampah-ui" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition">Batal</a>
            <button type="submit" class="px-4 py-2.5 bg-[#00a877] hover:bg-[#008f64] text-white rounded-xl text-xs font-bold transition">Simpan Jenis Sampah</button>
        </div>
    </form>
</div>
@endsection