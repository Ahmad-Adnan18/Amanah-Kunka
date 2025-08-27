<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SantriExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Santri::with('kelas')->get();
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Kelas', 'Rayon'];
    }

    public function map($santri): array
    {
        return [
            $santri->nis,
            $santri->nama,
            $santri->kelas->nama_kelas ?? 'N/A',
            $santri->rayon,
        ];
    }
}
