<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoyaltyExport implements FromArray, WithHeadings
{
    protected $royalties;

    public function __construct(array $royalties)
    {
        $this->royalties = $royalties;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->royalties as $royalty) {
            $data[] = [
                $royalty['entity_type'],
                $royalty['entity_name'],
                $royalty['books_count'],
                $royalty['total_revenue'],
                $royalty['total_earned'],
                $royalty['total_paid'],
                $royalty['total_pending'],
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Tipe',
            'Nama (Penulis/Penerbit)',
            'Jumlah Buku',
            'Total Omzet',
            'Total Royalti',
            'Royalti Dibayarkan',
            'Royalti Tertunda',
        ];
    }
}
