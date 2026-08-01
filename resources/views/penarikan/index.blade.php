@extends('layouts.app')

@section('title', 'Penarikan Saldo - BANKSACI')
@section('page_name', 'Penarikan Saldo')

@section('content')

    <!-- Judul Konten & Tombol Aksi Utama -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                Riwayat Penarikan Saldo <span class="text-xl">💸</span>
            </h1>
            <p class="text-sm text-gray-500">Catatan transaksi pencairan uang tunai milik nasabah.</p>
        </div>
        <div class="self-end sm:self-center">
            <a href="/penarikan/create" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#008f64] text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Buat Penarikan Baru
            </a>
        </div>
    </div>

    <!-- Alert Success Notification -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Filter Status Tabs -->
    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-1">
        <a href="{{ route('penarikan.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ !request('status') ? 'bg-[#00a877] text-white shadow-xs' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-100' }}">
            Semua
        </a>
        <a href="{{ route('penarikan.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-100' }}">
            ⏳ Pending
        </a>
        <a href="{{ route('penarikan.index', ['status' => 'selesai']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ request('status') == 'selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-100' }}">
            ✓ Selesai
        </a>
        <a href="{{ route('penarikan.index', ['status' => 'cancel']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 whitespace-nowrap {{ request('status') == 'cancel' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-gray-500 hover:bg-gray-50 border border-gray-100' }}">
            ✕ Dibatalkan
        </a>
    </div>

    <!-- Card Tabel Penarikan Saldo -->
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-xs font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-4 px-6 text-center w-12">NO</th>
                        <th class="py-4 px-4">TANGGAL</th>
                        <th class="py-4 px-4">NAMA NASABAH</th>
                        <th class="py-4 px-4 text-center">STATUS / AKSI</th>
                        <th class="py-4 px-6 text-right">JUMLAH TARIK TUNAI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600 font-medium">
                    @forelse($penarikans ?? [] as $index => $item)
                        <tr class="transition-colors duration-200 hover:bg-emerald-50/40">
                            <!-- Nomor Urut -->
                            <td class="py-4 px-6 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                            
                            <!-- Tanggal -->
                            <td class="py-4 px-4 font-semibold text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            
                            <!-- Nama Nasabah -->
                            <td class="py-4 px-4 font-bold text-[#004e38] whitespace-nowrap">
                                {{ $item->nasabah->nama ?? 'Nasabah Terhapus' }}
                            </td>

                            <!-- PERBAIKAN DILAKUKAN DI KOLOM AKSI / STATUS INI -->
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @php $status = strtolower($item->status ?? 'pending'); @endphp

                                @if($status === 'pending')
                                    <div class="inline-flex items-center gap-1.5 bg-amber-50/80 p-1 rounded-xl border border-amber-200">
                                        <!-- Badge Indicator -->
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold text-amber-700 bg-amber-100/80">
                                            ⏳ Pending
                                        </span>

                                        <!-- Tombol Approve Selesai -->
                                        <form action="{{ route('penarikan.update-status', $item->id) }}" method="POST" onsubmit="return confirm('Selesaikan penarikan saldo ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer" title="Setujui Penarikan">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>

                                        <!-- Tombol Cancel -->
                                        <form action="{{ route('penarikan.update-status', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan penarikan saldo ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancel">
                                            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer" title="Batalkan Transaksi">
                                                <i class="fas fa-times"></i> Batal
                                            </button>
                                        </form>
                                    </div>

                                @elseif($status === 'cancel' || $status === 'dibatalkan')
                                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 px-3 py-1.5 rounded-full text-xs font-extrabold border border-rose-200">
                                        <i class="fas fa-times-circle text-rose-500"></i> Dibatalkan
                                    </span>

                                @else
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-extrabold border border-emerald-200">
                                        <i class="fas fa-check-circle text-emerald-500"></i> Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- Jumlah Penarikan -->
                            <td class="py-4 px-6 text-right font-extrabold text-rose-600 whitespace-nowrap">
                                - Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                                Tidak ada data penarikan saldo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection