<?php

namespace App\Exports;

use App\Models\Perizinan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerizinanReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Perizinan::with('santri.kelas', 'pembuat');

        if (!empty($this->filters['tanggal_mulai'])) {
            $query->whereDate('tanggal_mulai', '>=', $this->filters['tanggal_mulai']);
        }
        if (!empty($this->filters['tanggal_akhir'])) {
            $query->whereDate('tanggal_mulai', '<=', $this->filters['tanggal_akhir']);
        }
        if (!empty($this->filters['kategori'])) {
            $query->where('kategori', $this->filters['kategori']);
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
        return ['NIS', 'Nama Santri', 'Kelas', 'Rayon', 'Jenis Izin', 'Kategori', 'Keterangan', 'Tanggal Mulai', 'Tanggal Kembali', 'Dicatat Oleh'];
    }

    public function map($izin): array
    {
        return [
            $izin->santri->nis,
            $izin->santri->nama,
            $izin->santri->kelas->nama_kelas ?? 'N/A',
            $izin->santri->rayon,
            $izin->jenis_izin,
            $izin->kategori,
            $izin->keterangan,
            $izin->tanggal_mulai->format('d-m-Y'),
            $izin->tanggal_akhir ? $izin->tanggal_akhir->format('d-m-Y') : '-',
            $izin->pembuat->name,
        ];
    }
}
