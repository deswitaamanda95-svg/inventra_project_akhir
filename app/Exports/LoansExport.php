<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoansExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil semua data dengan relasi agar Excel lengkap
        return Loan::with(['item', 'user', 'admin'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Aset',
            'ID Barang',
            'Nama Peminjam',
            'Waktu Pinjam',
            'Deadline Kembali',
            'Status',
            'Disetujui Oleh'
        ];
    }

    public function map($loan): array
    {
        return [
            '#L-' . str_pad($loan->id, 4, '0', STR_PAD_LEFT),
            $loan->item->name,
            $loan->item->id,
            $loan->user->name,
            $loan->loan_date ? $loan->loan_date->format('d M Y') : '-',
            $loan->due_date ? $loan->due_date->format('d M Y') : '-',
            strtoupper($loan->status),
            $loan->admin->name ?? 'System'
        ];
    }
}