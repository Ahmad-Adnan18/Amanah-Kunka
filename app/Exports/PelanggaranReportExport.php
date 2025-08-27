<?php

namespace App\Exports;

use App\Models\Pelanggaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PelanggaranReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pelanggaran::with('santri.kelas');

        if (!empty($this->filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_kejadian', '>=', $this->filters['tanggal_mulai']);
        }
        if (!empty($this->filters['tanggal_akhir'])) {
            $query->whereDate('tanggal_kejadian', '<=', $this->filters['tanggal_akhir']);
        }
        if (!empty($this->filters['kelas_id'])) {
            $query->whereHas('santri', function ($q) {
                $q->where('kelas_id', $this->filters['kelas_id']);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['NIS', 'Nama Santri', 'Kelas', 'Rayon', 'Jenis Pelanggaran', 'Tanggal Kejadian', 'Keterangan', 'Dicatat Oleh'];
    }

    public function map($pelanggaran): array
    {
        return [
            $pelanggaran->santri->nis,
            $pelanggaran->santri->nama,
            $pelanggaran->santri->kelas->nama_kelas ?? 'N/A',
            $pelanggaran->santri->rayon,
            $pelanggaran->jenis_pelanggaran,
            $pelanggaran->tanggal_kejadian->format('d-m-Y'),
            $pelanggaran->keterangan,
            $pelanggaran->dicatat_oleh,
        ];
    }
}
