<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="{{ 6 + count($kategoriList) }}" style="font-size: 14px; font-weight: bold; text-align: center;">
                    LAPORAN REKAPITULASI TRANSAKSI BANKSACI
                </th>
            </tr>
            <tr>
                <th colspan="{{ 6 + count($kategoriList) }}" style="text-align: center;">
                    Periode Bulan: {{ $bulan }} | Tahun: {{ $tahun }}
                </th>
            </tr>
            <tr></tr>
            <tr style="background-color: #0B4C8C; color: #ffffff; font-weight: bold;">
                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle;">NO</th>
                <th rowspan="2" style="border: 1px solid #000; text-align: left; vertical-align: middle;">NAMA NASABAH</th>
                <th rowspan="2" style="border: 1px solid #000; text-align: center; vertical-align: middle;">NO. INDUK</th>
                <th colspan="{{ count($kategoriList) }}" style="border: 1px solid #000; text-align: center; vertical-align: middle;">SAMPAH TERKUMPUL (KG)</th>
                <th rowspan="2" style="border: 1px solid #000; text-align: right; vertical-align: middle;">PEMASUKAN (RP)</th>
                <th rowspan="2" style="border: 1px solid #000; text-align: right; vertical-align: middle;">PENARIKAN (RP)</th>
                <th rowspan="2" style="border: 1px solid #000; text-align: right; vertical-align: middle;">SALDO SAAT INI (RP)</th>
            </tr>
            <tr style="background-color: #0B4C8C; color: #ffffff; font-weight: bold;">
                @foreach($kategoriList as $kat)
                    <th style="border: 1px solid #000; text-align: center;">{{ $kat }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rekapData as $index => $row)
                @php $nasabah = $row['nasabah']; @endphp
                <tr>
                    <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000; text-align: left;">{{ $nasabah->nama ?? '-' }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $nasabah->no_induk ?? $nasabah->kode_nasabah ?? '-' }}</td>
                    
                    @foreach($kategoriList as $kat)
                        @php $berat = $row['berat_kategori'][$kat] ?? 0; @endphp
                        <td style="border: 1px solid #000; text-align: center;">{{ $berat > 0 ? $berat : 0 }}</td>
                    @endforeach

                    <td style="border: 1px solid #000; text-align: right;">{{ $row['total_pemasukan'] ?? 0 }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ $row['total_penarikan'] ?? 0 }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ $row['saldo'] ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 6 + count($kategoriList) }}" style="border: 1px solid #000; text-align: center;">
                        Tidak ada data transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #e6e6e6;">
                <td colspan="3" style="border: 1px solid #000; text-align: center;">TOTAL KESELURUHAN</td>
                @foreach($kategoriList as $kat)
                    <td style="border: 1px solid #000; text-align: center;">{{ $grandTotalKategori[$kat] ?? 0 }}</td>
                @endforeach
                <td style="border: 1px solid #000; text-align: right;">{{ $grandTotalPemasukan ?? 0 }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $grandTotalPenarikan ?? 0 }}</td>
                <td style="border: 1px solid #000; text-align: right;">{{ $grandSaldo ?? 0 }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>