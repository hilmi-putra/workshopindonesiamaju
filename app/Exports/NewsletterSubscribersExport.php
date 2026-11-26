<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class NewsletterSubscribersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $subscribers) {}

    public function collection()
    {
        return $this->subscribers;
    }

    public function headings(): array
    {
        return ['ID', 'Email', 'Status', 'Tanggal Berlangganan'];
    }

    public function map($sub): array
    {
        return [
            $sub->id,
            $sub->email,
            $sub->status,
            $sub->subscribed_at?->format('Y-m-d H:i:s') ?: $sub->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
