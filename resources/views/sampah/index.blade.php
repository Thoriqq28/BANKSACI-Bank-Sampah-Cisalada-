@extends('layouts.app')

@section('title', 'Master Kategori Sampah - BANKSACI')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <nav class="flex text-xs text-gray-400 mb-1">
            <a href="/dashboard-ui" class="hover:text-gray-600">Dashboard</a>
            <span class="mx-2">/</span>
            <span class="text-emerald-600 font-semibold">Kategori Sampah</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            Master Kategori Sampah ♻️
        </h1>
        <p class="text-xs text-gray-400 mt-1">Kelola jenis sampah dan harga konversi per kilogram.</p>
    </div>
    <a href="/jenis/create" class="px-4 py-2 bg-[#00a877] hover:bg-[#008f64] text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
        + Tambah Jenis
    </a>
</div>

<!-- Alert Flash Message -->
@if(session('success'))
<div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl font-bold">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left text-xs md:text-sm">
        <thead class="bg-gray-50/50 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
            <tr>
                <th class="py-4 px-6 font-bold w-16 text-center">NO</th>
                <th class="py-4 px-6 font-bold">KATEGORI UTAMA</th>
                <th class="py-4 px-6 font-bold">JENIS / NAMA SAMPAH</th>
                <th class="py-4 px-6 font-bold text-right">HARGA KONVERSI (PER KG)</th>
                <th class="py-4 px-6 font-bold text-center">AKSI</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700">
            @forelse($kategori as $index => $item)
                @php
                    // Parsing aman untuk string format "KATEGORI | NAMA | HARGA"
                    $parts = explode('|', $item->nama);
                    
                    if (count($parts) >= 3) {
                        $kategoriUtama = strtoupper(trim($parts[0]));
                        $namaJenis     = trim($parts[1]);
                        $hargaBeli     = (int) trim($parts[2]);
                    } elseif (count($parts) == 2) {
                        $kategoriUtama = strtoupper(trim($parts[0]));
                        $namaJenis     = trim($parts[1]);
                        $hargaBeli     = 0;
                    } else {
                        $kategoriUtama = 'UMUM';
                        $namaJenis     = $item->nama;
                        $hargaBeli     = $item->harga_beli ?? 0;
                    }
                @endphp
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-4 px-6 text-center font-semibold text-gray-400">{{ $index + 1 }}</td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-bold uppercase tracking-wide">
                            {{ $kategoriUtama }}
                        </span>
                    </td>
                    <td class="py-4 px-6 font-bold text-gray-800">{{ $namaJenis }}</td>
                    <td class="py-4 px-6 text-right font-extrabold text-emerald-600">
                        Rp {{ number_format($hargaBeli, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('jenis.edit', $item->id) }}" class="px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold transition">Edit</a>
                            <form action="{{ route('jenis.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis sampah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-bold transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400 font-medium">Belum ada data jenis sampah. Silakan tambah baru.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection