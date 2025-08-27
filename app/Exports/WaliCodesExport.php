<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WaliCodesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Santri::with('kelas')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Santri',
            'Kelas',
            'Kode Registrasi Wali',
        ];
    }

    public function map($santri): array
    {
        return [
            $santri->nis,
            $santri->nama,
            $santri->kelas->nama_kelas ?? 'N/A',
            $santri->kode_registrasi_wali ?? 'Belum Dibuat',
        ];
    }
}
