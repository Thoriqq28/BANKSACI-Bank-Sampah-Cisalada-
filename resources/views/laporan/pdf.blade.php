<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Menyeluruh - BANKSACI</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 8mm;
        }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 9px; 
            color: #2d3748; 
            line-height: 1.2;
        }
        .header { 
            text-align: center; 
            margin-bottom: 12px; 
            border-bottom: 2px solid #00a877;
            padding-bottom: 6px;
        }
        .header h2 { 
            margin: 0; 
            color: #004d38; 
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p { 
            margin: 2px 0 0 0; 
            color: #4a5568; 
            font-size: 10px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        th, td { 
            border: 1px solid #cbd5e0; 
            padding: 4px 3px; 
            vertical-align: middle;
        }
        th { 
            background-color: #f7fafc; 
            font-weight: bold; 
            text-align: center; 
            color: #2d3748;
            font-size: 8.5px;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .pemasukan-text { color: #00a877; font-weight: bold; }
        .penarikan-text { color: #e53e3e; font-weight: bold; }
        .saldo-text { color: #2b6cb0; font-weight: bold; }
        
        .total-row { 
            background-color: #edf2f7; 
            font-weight: bold; 
        }
        .total-row td {
            border-top: 2px solid #a0aec0;
        }
        code {
            font-family: monospace;
            background-color: #edf2f7;
            padding: 1px 3px;
            border-radius: 3px;
            font-size: 8.5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN REKAPITULASI TRANSAKSI</h2>
        <p><strong>BANK SAMPAH BANKSACI</strong></p>
        <p>Periode: <strong>{{ DateTime::createFromFormat('!m', (int)($bulan ?? date('m')))->format('F') }} {{ $tahun ?? date('Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%">NO</th>
                <th rowspan="2" style="width: 16%">NAMA NASABAH</th>
                <th rowspan="2" style="width: 9%">NO. INDUK</th>
                <th colspan="{{ count($kategoriList) }}" class="text-center">SAMPAH TERKUMPUL (KG)</th>
                <th rowspan="2" style="width: 10%">PEMASUKAN</th>
                <th rowspan="2" style="width: 10%">PENARIKAN</th>
                <th rowspan="2" style="width: 10%">SALDO SAAT INI</th>
            </tr>
            <tr>
                @foreach($kategoriList as $kat)
                    <th>{{ $kat }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rekapData as $index => $row)
                @php $nasabah = $row['nasabah']; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $nasabah->nama ?? '-' }}</strong></td>
                    <td class="text-center"><code>{{ $nasabah->no_induk ?? $nasabah->kode_nasabah ?? '-' }}</code></td>
                    
                    @foreach($kategoriList as $kat)
                        @php $berat = $row['berat_kategori'][$kat] ?? 0; @endphp
                        <td class="text-center">
                            {{ $berat > 0 ? number_format($berat, 1, ',', '.') : '-' }}
                        </td>
                    @endforeach

                    <td class="text-right pemasukan-text">
                        Rp {{ number_format($row['total_pemasukan'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right penarikan-text">
                        Rp {{ number_format($row['total_penarikan'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right saldo-text">
                        Rp {{ number_format($row['saldo'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 6 + count($kategoriList) }}" class="text-center" style="padding: 15px; color: #a0aec0;">
                        Tidak ada data transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                
                @foreach($kategoriList as $kat)
                    @php $totalKat = $grandTotalKategori[$kat] ?? 0; @endphp
                    <td class="text-center">
                        {{ $totalKat > 0 ? number_format($totalKat, 1, ',', '.') : '-' }}
                    </td>
                @endforeach

                <td class="text-right pemasukan-text">
                    Rp {{ number_format($grandTotalPemasukan ?? 0, 0, ',', '.') }}
                </td>
                <td class="text-right penarikan-text">
                    Rp {{ number_format($grandTotalPenarikan ?? 0, 0, ',', '.') }}
                </td>
                <td class="text-right saldo-text">
                    Rp {{ number_format($grandSaldo ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>