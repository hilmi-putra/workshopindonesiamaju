<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RevenueExport implements FromArray, WithHeadings
{
    protected $dailyBreakdown;

    public function __construct(array $dailyBreakdown)
    {
        $this->dailyBreakdown = $dailyBreakdown;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->dailyBreakdown as $row) {
            $rows[] = [
                $row['date'],
                $row['orders_count'],
                $row['gross_sales'],
                $row['discount'],
                $row['shipping_fee'],
                $row['total_revenue'],
                $row['refunds'],
                $row['net_revenue'],
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jumlah Transaksi',
            'Gross Sales (Subtotal)',
            'Diskon',
            'Ongkos Kirim',
            'Total Diterima',
            'Refund',
            'Net Revenue',
        ];
    }
}
