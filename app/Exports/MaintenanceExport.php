<?php

namespace App\Exports;

use App\Models\MaintenanceLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection() {
        return MaintenanceLog::with('item')->latest()->get();
    }

    public function headings(): array {
        return ['ID LOG', 'NAMA ASET', 'DETAIL KERUSAKAN', 'TEKNISI', 'TGL MULAI', 'TGL SELESAI', 'BIAYA (IDR)', 'STATUS'];
    }

    public function map($log): array {
        return [
            '#MNT-' . str_pad($log->id, 4, '0', STR_PAD_LEFT),
            $log->item->name,
            $log->issue_detail,
            $log->technician_name ?? '-',
            $log->start_date->format('d/m/Y'),
            $log->completion_date ? $log->completion_date->format('d/m/Y') : '-',
            number_format($log->cost, 0, ',', '.'),
            strtoupper($log->status)
        ];
    }
}