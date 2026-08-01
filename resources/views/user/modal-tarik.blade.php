<!-- MODAL TARIK SALDO INTEGRASI (Cash & E-Wallet) -->
<div x-show="openTarik" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
     x-transition
     x-cloak>
    
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden transform transition-all"
         @click.away="openTarik = false">
        
        <!-- Header Modal -->
        <div class="p-5 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">💸</span>
                <h3 class="font-black text-slate-800 text-sm tracking-wide uppercase">Tarik Saldo Nasabah</h3>
            </div>
            <button @click="openTarik = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>

        <!-- Form Utama (Ganti route sesuai backend Anda) -->
        <form action="{{ route('transaksi.tarik') }}" method="POST" class="p-5 space-y-4" x-data="{ jenisPenarikan: 'cash', eWalletPlatform: '' }">
            @csrf
            
            <!-- INPUT 1: NOMINAL UTAMA -->
            <div class="space-y-1.5">
                <label for="nominal" class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Nominal Tarik</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-xs font-bold">Rp</span>
                    <input type="number" id="nominal" name="nominal" required min="10000"
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-600/30 transition duration-200"
                           placeholder="Contoh: 50000">
                </div>
            </div>

            <!-- INPUT 2: PILIHAN METODE PENARIKAN (CASH / E-WALLET) -->
            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Metode Penarikan</label>
                <div class="grid grid-cols-2 gap-3">
                    
                    <!-- Opsi Cash -->
                    <label class="relative flex items-center justify-between p-3 bg-slate-50 border rounded-2xl cursor-pointer select-none transition"
                           :class="jenisPenarikan === 'cash' ? 'border-emerald-600 bg-emerald-50/20 text-emerald-900 font-bold' : 'border-slate-200 text-slate-600'">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-xs" :class="jenisPenarikan === 'cash' ? 'text-emerald-600' : 'text-slate-400'"></i>
                            <span class="text-xs">Cash (Tunai)</span>
                        </div>
                        <input type="radio" name="jenis_penarikan" value="cash" x-model="jenisPenarikan" class="sr-only">
                        <i class="fas fa-check-circle text-emerald-600 text-xs" x-show="jenisPenarikan === 'cash'"></i>
                    </label>

                    <!-- Opsi E-Wallet -->
                    <label class="relative flex items-center justify-between p-3 bg-slate-50 border rounded-2xl cursor-pointer select-none transition"
                           :class="jenisPenarikan === 'ewallet' ? 'border-emerald-600 bg-emerald-50/20 text-emerald-900 font-bold' : 'border-slate-200 text-slate-600'">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-mobile-alt text-xs" :class="jenisPenarikan === 'ewallet' ? 'text-emerald-600' : 'text-slate-400'"></i>
                            <span class="text-xs">E-Wallet</span>
                        </div>
                        <input type="radio" name="jenis_penarikan" value="ewallet" x-model="jenisPenarikan" class="sr-only">
                        <i class="fas fa-check-circle text-emerald-600 text-xs" x-show="jenisPenarikan === 'ewallet'"></i>
                    </label>

                </div>
            </div>

            <!-- INPUT DINAMIS E-WALLET (Hanya meluncur jika opsi E-Wallet dipilih) -->
            <div x-show="jenisPenarikan === 'ewallet'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="space-y-3 bg-slate-50 p-3 rounded-2xl border border-slate-100">
                
                <!-- Pilih Layanan E-Wallet -->
                <div class="space-y-1">
                    <label for="wallet_type" class="block text-[9px] font-black text-slate-500 uppercase tracking-wider">Pilih E-Wallet</label>
                    <select id="wallet_type" name="jenis_ewallet" x-model="eWalletPlatform" :required="jenisPenarikan === 'ewallet'"
                            class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600">
                        <option value="">-- Pilih E-Wallet --</option>
                        <option value="dana">DANA</option>
                        <option value="gopay">GoPay</option>
                        <option value="ovo">OVO</option>
                        <option value="shopeepay">ShopeePay</option>
                    </select>
                </div>

                <!-- Nomor HP E-Wallet -->
                <div class="space-y-1">
                    <label for="wallet_number" class="block text-[9px] font-black text-slate-500 uppercase tracking-wider">Nomor HP E-Wallet</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-[11px] font-bold">+62</span>
                        <input type="tel" id="wallet_number" name="nomor_ewallet" :required="jenisPenarikan === 'ewallet'"
                               class="w-full pl-10 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-emerald-600"
                               placeholder="8xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Akhir -->
            <div class="flex items-center gap-3 pt-2">
                <button type="button" @click="openTarik = false" 
                        class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs py-2.5 rounded-xl transition cursor-pointer text-center">
                    Batal
                </button>
                <button type="submit" 
                        class="w-1/2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2.5 rounded-xl transition shadow-sm shadow-emerald-600/20 text-center cursor-pointer">
                    Proses Tarik
                </button>
            </div>

        </form>
    </div>
</div>