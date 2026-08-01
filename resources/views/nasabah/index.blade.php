@extends('layouts.app')

@section('title', 'Data Nasabah - BANKSACI')
@section('page_name', 'Data Nasabah')

@section('content')

    <!-- Header & Fitur Pencarian + Tombol Tambah Nasabah -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                Master Data Nasabah <span class="text-xl">👥</span>
            </h1>
            <p class="text-sm text-gray-500">Kelola informasi profil warga dan pantau sisa saldo tabungan.</p>
        </div>
        
        <!-- Area Kanan: Form Search & Tombol Tambah -->
        <div class="flex items-center gap-3 self-end md:self-center">
            <!-- Input Search -->
            <div class="relative">
                <input type="text" 
                       id="searchNasabah" 
                       placeholder="Cari nama atau ID..." 
                       class="w-56 pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#00a877] focus:ring-1 focus:ring-[#00a877] transition-all shadow-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
            </div>

            <!-- Tombol Tambah Nasabah -->
            <a href="/nasabah/create" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#008f64] text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 whitespace-nowrap">
                <i class="fas fa-plus"></i> Tambah Nasabah
            </a>
        </div>
    </div>

    <!-- Card Tabel Data Nasabah -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-4 px-6 text-center w-12">NO</th>
                        <th class="py-4 px-4">ID / NO. REKENING</th>
                        <th class="py-4 px-4">NAMA LENGKAP</th>
                        <th class="py-4 px-4">ALAMAT RUMAH</th>
                        <th class="py-4 px-4 text-right">SALDO SAAT INI</th>
                        <th class="py-4 px-6 text-center w-48">AKSI</th>
                    </tr>
                </thead>
                <!-- Diberi ID bodyNasabah agar dibaca JavaScript -->
                <tbody id="bodyNasabah" class="divide-y divide-gray-100 text-sm text-gray-600 font-medium">
                    @forelse($nasabahs ?? $nasabahList ?? $nasabah ?? [] as $index => $item)
                        <tr class="transition-colors duration-200 hover:bg-emerald-50/40">
                            <!-- 1. Nomor Urut -->
                            <td class="py-4 px-6 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                            
                            <!-- 2. ID / No Rekening -->
                            <td class="py-4 px-4 font-bold text-[#00a877] whitespace-nowrap">
                                {{ $item->kode_nasabah ?? $item->id_nasabah ?? '#'.$item->id }}
                            </td>
                            
                            <!-- 3. Nama Lengkap -->
                            <td class="py-4 px-4 font-bold text-gray-800 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-emerald-100 text-[#00a877] rounded-full flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($item->nama ?? 'N', 0, 1)) }}
                                    </div>
                                    <span>{{ $item->nama }}</span>
                                </div>
                            </td>
                            
                            <!-- 4. Alamat Rumah -->
                            <td class="py-4 px-4 text-gray-500 max-w-xs truncate">
                                {{ $item->alamat ?? '-' }}
                            </td>
                            
                            <!-- 5. Saldo Saat Ini -->
                            <td class="py-4 px-4 text-right font-extrabold text-emerald-600 whitespace-nowrap">
                                Rp {{ number_format($item->saldo ?? 0, 0, ',', '.') }}
                            </td>
                            
                            <!-- 6. Aksi CRUD -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    <a href="/nasabah/{{ $item->id }}" class="bg-emerald-50 text-[#00a877] hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 hover:scale-105">
                                        Detail
                                    </a>
                                    <a href="/nasabah/{{ $item->id }}/edit" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 hover:scale-105">
                                        Edit
                                    </a>
                                    
                                    <form action="/nasabah/{{ $item->id }}" method="POST" class="inline m-0" onsubmit="return confirm('Yakin ingin menghapus nasabah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-[#fef2f2] text-rose-500 hover:bg-rose-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200 hover:scale-105 cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Tampilan Kosong -->
                        <tr id="emptyRow">
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i class="fas fa-users-slash text-3xl mb-2 block opacity-50"></i>
                                <span class="text-sm font-medium">Belum ada data nasabah di database.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script JavaScript untuk Fitur Live Search -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchNasabah');
        const tableBody = document.getElementById('bodyNasabah');

        if (searchInput && tableBody) {
            searchInput.addEventListener('keyup', function () {
                const filter = this.value.toLowerCase().trim();
                const rows = tableBody.getElementsByTagName('tr');

                Array.from(rows).forEach(function (row) {
                    if (row.id === 'emptyRow') return;

                    const text = row.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>

@endsection