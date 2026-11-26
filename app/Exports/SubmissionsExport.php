<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubmissionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $submissions) {}

    public function collection()
    {
        return $this->submissions;
    }

    public function headings(): array
    {
        return ['ID', 'Judul Naskah', 'Penulis', 'Email', 'No. HP/WA', 'Genre', 'Status', 'Tanggal Pengajuan'];
    }

    public function map($sub): array
    {
        return [
            $sub->id,
            $sub->title,
            $sub->author_name,
            $sub->email,
            $sub->phone,
            $sub->genre ?: '-',
            $sub->status,
            $sub->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
