<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BestSellersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Judul Buku',
            'Kategori',
            'Penerbit',
            'Total Terjual',
            'Total Pendapatan',
            'Jumlah Pembeli',
            'Sisa Stok',
        ];
    }

    public function map($item): array
    {
        $categories = $item->book->categories->pluck('name')->implode(', ');

        return [
            $item->book->title,
            $categories,
            $item->book->publisher ? $item->book->publisher->name : '-',
            $item->total_sold,
            $item->total_revenue,
            $item->total_buyers,
            $item->book->stock,
        ];
    }
}
