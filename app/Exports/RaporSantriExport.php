<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class RaporSantriExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithEvents
{
    protected $santri;
    protected $raporData;
    protected $tahunAjaran;
    protected $semester;

    public function __construct($santri, Collection $raporData, $tahunAjaran, $semester)
    {
        $this->santri = $santri;
        $this->raporData = $raporData;
        $this->tahunAjaran = $tahunAjaran;
        $this->semester = $semester;
    }

    public function collection()
    {
        return $this->raporData;
    }

    public function title(): string
    {
        return 'Rapor ' . $this->semester . ' ' . str_replace('/', '-', $this->tahunAjaran);
    }

    public function headings(): array
    {
        // Header untuk informasi santri dan tabel nilai
        return [
            ['Nama Santri', $this->santri->nama],
            ['NIS', $this->santri->nis],
            ['Kelas', $this->santri->kelas->nama_kelas ?? 'N/A'],
            ['Tahun Ajaran', $this->tahunAjaran],
            ['Semester', $this->semester],
            [], // Baris kosong sebagai pemisah
            [
                'Mata Pelajaran',
                'Kategori',
                'Nilai Tugas',
                'Nilai UTS',
                'Nilai UAS',
                'Nilai Akhir',
                'Predikat',
            ]
        ];
    }

    public function map($nilai): array
    {
        $avg = $this->calculateAverage($nilai->nilai_tugas, $nilai->nilai_uts, $nilai->nilai_uas);
        $predikat = $this->getPredicate($avg);

        return [
            $nilai->mataPelajaran->nama_pelajaran,
            $nilai->mataPelajaran->kategori,
            $nilai->nilai_tugas ?? '-',
            $nilai->nilai_uts ?? '-',
            $nilai->nilai_uas ?? '-',
            $avg,
            $predikat['text'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Styling header
                $event->sheet->getDelegate()->mergeCells('B1:G1');
                $event->sheet->getDelegate()->mergeCells('B2:G2');
                $event->sheet->getDelegate()->mergeCells('B3:G3');
                $event->sheet->getDelegate()->mergeCells('B4:G4');
                $event->sheet->getDelegate()->mergeCells('B5:G5');
                $event->sheet->getStyle('A1:A5')->getFont()->setBold(true);
                $event->sheet->getStyle('A7:G7')->getFont()->setBold(true);
                $event->sheet->getStyle('A7:G7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('F3F4F6');
            },
        ];
    }

    // Helper functions
    private function calculateAverage($tugas, $uts, $uas)
    {
        $values = collect([$tugas, $uts, $uas])->filter(fn($v) => $v !== null);
        return $values->isNotEmpty() ? round($values->avg()) : '-';
    }

    private function getPredicate($avg)
    {
        if ($avg >= 90)
            return ['text' => 'Mumtaz'];
        if ($avg >= 80)
            return ['text' => 'Jayyid Jiddan'];
        if ($avg >= 70)
            return ['text' => 'Jayyid'];
        if ($avg >= 60)
            return ['text' => 'Maqbul'];
        if ($avg > 0)
            return ['text' => 'Rasib'];
        return ['text' => '-'];
    }
}
