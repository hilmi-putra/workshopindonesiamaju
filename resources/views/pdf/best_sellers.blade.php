<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk Terlaris</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { border-bottom: 2px solid #DB8B00; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 16px; font-weight: bold; color: #171512; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px 6px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .footer { text-align: center; color: #888; font-size: 9px; margin-top: 25px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="title">MEGA PRESS - LAPORAN PRODUK TERLARIS</span><br>
        <small style="color: #666;">Dicetak pada: {{ now()->format('d M Y H:i') }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th>Judul Buku</th>
                <th>Penulis / Penerbit</th>
                <th class="text-right">Unit Terjual</th>
                <th class="text-right">Total Penjualan</th>
                <th class="text-right">Harga Rata-rata (ASP)</th>
                <th class="text-right">Total Pembeli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->book?->title ?? 'Buku #' . $item->book_id }}</strong></td>
                <td>{{ $item->book?->author?->name ?? ($item->book?->publisher?->name ?? '-') }}</td>
                <td class="text-right" style="font-weight: bold;">{{ $item->total_sold }}</td>
                <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->average_price ?? ($item->total_sold > 0 ? $item->total_revenue / $item->total_sold : 0), 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->total_buyers }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Mega Press Publishing Platform &copy; {{ date('Y') }}. Data berdasarkan pesanan yang telah lunas dan berhasil.
    </div>
</body>
</html>
