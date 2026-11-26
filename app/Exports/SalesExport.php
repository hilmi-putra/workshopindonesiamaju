<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Kode Pesanan',
            'Pelanggan',
            'No. HP/WA',
            'Daftar Buku',
            'Jumlah Item',
            'Total Harga',
            'Status Pesanan',
            'Tanggal Transaksi',
        ];
    }

    public function map($order): array
    {
        $books = $order->items->map(function ($item) {
            return $item->display_title . ' (x' . $item->display_qty . ')';
        })->implode(', ');

        $totalItems = $order->items->sum(fn ($item) => $item->display_qty);

        return [
            $order->display_code,
            $order->customer_name ?: ($order->user?->name ?? 'Guest'),
            $order->customer_phone ?: ($order->user?->phone ?? '-'),
            $books,
            $totalItems,
            $order->display_total,
            $order->status_label,
            $order->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
