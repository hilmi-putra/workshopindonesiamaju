<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->display_code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 2px solid #DB8B00; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 18px; font-weight: bold; color: #171512; }
        .invoice-title { font-size: 20px; font-weight: bold; color: #DB8B00; text-transform: uppercase; float: right; }
        .info-section { width: 100%; margin-bottom: 20px; }
        .info-section td { width: 50%; vertical-align: top; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; color: #171512; }
        .text-right { text-align: right !important; }
        .grand-total { font-size: 14px; color: #DB8B00; font-weight: bold; }
        .footer { text-align: center; color: #888; font-size: 10px; margin-top: 40px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="logo">WORKSHOP INDONESIA MAJU</span>
        <span class="invoice-title">INVOICE</span>
    </div>

    <table class="info-section">
        <tr>
            <td>
                <strong>Diterbitkan oleh:</strong><br>
                Workshop Indonesia Maju<br>
                WhatsApp: {{ config('services.whatsapp.number', '6285724693474') }}<br>
                admin@workshopindonesiamaju.co.id
            </td>
            <td>
                <strong>Untuk:</strong><br>
                {{ $order->customer_name ?: ($order->user?->name ?? 'Pelanggan') }}<br>
                {{ $order->customer_address ?: ($order->user?->address ?? '-') }}<br>
                Telp/WA: {{ $order->customer_phone ?: ($order->user?->phone ?? '-') }}
            </td>
        </tr>
    </table>

    <table class="info-section">
        <tr>
            <td>
                <strong>Kode Pesanan:</strong> {{ $order->display_code }}<br>
                <strong>Tanggal Transaksi:</strong> {{ $order->created_at?->format('d M Y H:i') ?? '-' }}
            </td>
            <td>
                <strong>Status Pesanan:</strong> {{ $order->status_label }}<br>
                <strong>Konfirmasi &amp; Pembayaran:</strong> WhatsApp Admin
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->display_title }}</td>
                    <td class="text-right">Rp {{ number_format($item->display_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->display_qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right grand-total">Total Harga Buku</td>
                <td class="text-right grand-total">Rp {{ number_format($order->display_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($order->notes)
        <p><strong>Catatan:</strong> {{ $order->notes }}</p>
    @endif

    <div class="footer">
        Terima kasih telah berbelanja di WIM Bookstore — Workshop Indonesia Maju.
    </div>
</body>
</html>
