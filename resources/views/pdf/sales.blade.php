<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { border-bottom: 2px solid #DB8B00; padding-bottom: 8px; margin-bottom: 12px; }
        .title { font-size: 16px; font-weight: bold; color: #171512; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px 6px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .summary-box { width: 100%; margin-bottom: 15px; border: 1px solid #e5e7eb; background: #fafafa; padding: 8px; }
        .summary-table { width: 100%; border: none; margin: 0; }
        .summary-table td { border: none; padding: 3px 6px; font-size: 10px; }
        .footer { text-align: center; color: #888; font-size: 9px; margin-top: 25px; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="title">MEGA PRESS - LAPORAN PENJUALAN</span><br>
        <small style="color: #666;">Periode: {{ $startDate ?? '-' }} s/d {{ $endDate ?? '-' }} &middot; Dicetak: {{ now()->format('d M Y H:i') }}</small>
    </div>

    @if(isset($summary))
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td><strong>Total Pesanan:</strong> {{ $summary['total_orders'] ?? count($orders) }}</td>
                <td><strong>Lunas/Selesai:</strong> {{ $summary['paid_orders'] ?? 0 }}</td>
                <td><strong>Total Buku Terjual:</strong> {{ $summary['total_items'] ?? 0 }} eks</td>
                <td><strong>Pendapatan Lunas:</strong> <strong style="color: #DB8B00;">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Item Buku</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td><strong>{{ $order->display_code }}</strong></td>
                <td>{{ $order->customer_name ?: ($order->user?->name ?? 'Guest') }}</td>
                <td>
                    @foreach($order->items as $item)
                        {{ $item->display_title }} (x{{ $item->display_qty }})<br>
                    @endforeach
                </td>
                <td class="text-right">{{ $order->items->sum(fn ($i) => $i->display_qty) }}</td>
                <td class="text-right" style="font-weight: bold;">Rp {{ number_format($order->display_total, 0, ',', '.') }}</td>
                <td>{{ $order->status_label }}</td>
                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Mega Press Nusantara &copy; {{ date('Y') }}.
    </div>
</body>
</html>
