<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shipping Label - {{ $order->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111; margin: 0; padding: 10px; }
        .label-container { border: 2px solid #000; border-radius: 8px; padding: 16px; max-width: 500px; margin: 0 auto; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 12px; display: table; width: 100%; }
        .logo { font-size: 16px; font-weight: 900; letter-spacing: 1px; display: table-cell; vertical-align: middle; }
        .courier-badge { display: table-cell; text-align: right; vertical-align: middle; font-size: 14px; font-weight: bold; text-transform: uppercase; background: #eee; padding: 4px 8px; border: 1px solid #000; }
        .barcode-section { text-align: center; border-bottom: 2px dashed #000; padding: 10px 0 14px 0; margin-bottom: 12px; }
        .tracking-number { font-size: 18px; font-weight: bold; letter-spacing: 2px; font-family: monospace; }
        .order-number { font-size: 11px; color: #444; margin-top: 4px; }
        .section-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .section-table td { vertical-align: top; padding: 6px; border: 1px solid #ccc; width: 50%; }
        .sec-title { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #555; margin-bottom: 4px; }
        .item-summary { background: #f9f9f9; padding: 8px; border: 1px solid #ddd; font-size: 11px; margin-bottom: 12px; }
        .footer { font-size: 10px; color: #666; text-align: center; border-top: 1px solid #ddd; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="label-container">
        <div class="header">
            <div class="logo">WORKSHOP INDONESIA MAJU</div>
            <div class="courier-badge">
                {{ $order->shipment->courier ?? $order->shipping_method ?? 'REGULER' }}
                @if($order->shipment && $order->shipment->service)
                    - {{ $order->shipment->service }}
                @endif
            </div>
        </div>

        <div class="barcode-section">
            <div class="tracking-number">
                {{ $order->shipment->tracking_number ?? $order->awb_number ?? 'NO RESI: ' . $order->invoice_number }}
            </div>
            <div class="order-number">
                No. Pesanan: <strong>{{ $order->invoice_number }}</strong> &bull; Tgl: {{ $order->created_at->format('d/m/Y') }}
            </div>
        </div>

        <table class="section-table">
            <tr>
                <td>
                    <div class="sec-title">PENGIRIM</div>
                    <strong>WIM Bookstore</strong><br>
                    Jl. Percetakan Negara No. 1<br>
                    Jakarta Pusat, DKI Jakarta 10560<br>
                    Telp: 0812-3456-7890
                </td>
                <td>
                    <div class="sec-title">PENERIMA</div>
                    <strong>{{ $order->shippingAddress->recipient_name ?? $order->user->name }}</strong><br>
                    Telp: <strong>{{ $order->shippingAddress->phone ?? $order->user->phone }}</strong><br>
                    @if($order->shippingAddress)
                        {{ $order->shippingAddress->address }}<br>
                        {{ $order->shippingAddress->subdistrict_name ?? $order->shippingAddress->district_name }}, 
                        {{ $order->shippingAddress->city_name ?? $order->shippingAddress->regency_name }}<br>
                        {{ $order->shippingAddress->province_name }} {{ $order->shippingAddress->postal_code }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>

        <div class="item-summary">
            <strong>Daftar Isi Paket ({{ $order->items->sum('quantity') }} Buku):</strong><br>
            @foreach($order->items as $item)
                &bull; {{ $item->book->title ?? 'Buku' }} ({{ $item->quantity }}x)<br>
            @endforeach
            @if($order->notes)
                <div style="margin-top: 4px; font-style: italic; color: #555;">
                    Instruksi: {{ $order->notes }}
                </div>
            @endif
        </div>

        <div class="footer">
            Dicetak oleh Workshop Indonesia Maju Fulfillment Center &bull; Waktu: {{ date('d M Y H:i') }} WIB
        </div>
    </div>
</body>
</html>
