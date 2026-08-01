@extends('dashboard') {{-- Menggunakan master layout dashboard utama Anda --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb / Navigasi Atas -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Jenis Sampah</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah informasi nama dan penyesuaian harga beli sampah dinamis.</p>
    </div>

    <!-- Card Form Edit -->
    <div class="max-w-xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-lg font-semibold text-gray-800">Form Edit Jenis & Harga Sampah</h2>
        </div>

        <div class="p-6">
            <!-- Tampilkan Error Validasi Jika Ada -->
            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan input:</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @php
                // Memecah teks lama "kategori | jenis | harga" berdasarkan karakter pipa
                $pecahData = explode('|', $sampah->nama);
                
                // Mengambil nama sampah asli (indeks ke-1 jika ada format gabungan, jika tidak ada pakai nama mentah)
                $namaSampahClean = isset($pecahData[1]) ? trim($pecahData[1]) : trim($sampah->nama);
                
                // Mengambil harga dari database, jika masih 0, ambil dari teks ujung string (indeks ke-2)
                $hargaBeliClean = $sampah->harga_beli;
                if ((!$hargaBeliClean || $hargaBeliClean == 0) && isset($pecahData[2])) {
                    $hargaBeliClean = (int) trim($pecahData[2]);
                }
            @endphp

            <!-- Form Action -->
            <form action="{{ url('/sampah-ui/' . $sampah->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- 1. Input Nama Sampah (Otomatis Bersih) -->
                <div class="mb-5">
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori / Jenis Sampah</label>
                    <input type="text" name="nama" id="nama" 
                           value="{{ old('nama', $namaSampahClean) }}" 
                           class="block w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-white" 
                           placeholder="Contoh: Plastik Bening / Kardus" required autocomplete="off">
                </div>

                <!-- 2. Input Harga Beli Dinamis (Otomatis Mendeteksi Nilai Aslinya) -->
                <div class="mb-6">
                    <label for="harga_beli" class="block text-sm font-semibold text-gray-700 mb-2">Harga Beli Nasabah (Per Kg)</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <span class="text-gray-400 text-sm font-medium">Rp</span>
                        </div>
                        <input type="number" name="harga_beli" id="harga_beli" 
                               value="{{ old('harga_beli', $hargaBeliClean) }}" 
                               class="block w-full rounded-xl border border-gray-200 pl-12 pr-4 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 font-medium text-gray-900" 
                               placeholder="Masukkan nominal tanpa titik (Contoh: 3000)" min="0" required>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Harga ini akan menjadi pengali otomatis saat petugas menginput setoran nasabah.</p>
                </div>

                <!-- 3. Tombol Aksi -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ url('/sampah-ui') }}" 
                       class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition bg-white">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition shadow-sm shadow-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection