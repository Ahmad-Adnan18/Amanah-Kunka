<?php

namespace App\Exports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LegerNilaiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    protected $filters;
    protected $kelas;
    protected $mataPelajaran;

    public function __construct(array $filters, $kelas, $mataPelajaran)
    {
        $this->filters = $filters;
        $this->kelas = $kelas;
        $this->mataPelajaran = $mataPelajaran;
    }

    public function collection()
    {
        // Ambil semua santri dari kelas yang difilter, beserta nilainya yang relevan
        return Santri::where('kelas_id', $this->filters['kelas_id'])
            ->with([
                'nilai' => function ($query) {
                    $query->where('mata_pelajaran_id', $this->filters['mata_pelajaran_id'])
                        ->where('semester', $this->filters['semester'])
                        ->where('tahun_ajaran', $this->filters['tahun_ajaran']);
                }
            ])
            ->orderBy('nama')
            ->get();
    }

    public function title(): string
    {
        // Judul untuk sheet Excel
        return 'Leger ' . $this->kelas->nama_kelas;
    }

    public function headings(): array
    {
        // Judul kolom di file Excel
        $jenisPenilaian = str_replace('_', ' ', \Illuminate\Support\Str::ucfirst($this->filters['jenis_penilaian']));

        return [
            'NIS',
            'Nama Santri',
            $jenisPenilaian,
        ];
    }

    public function map($santri): array
    {
        // Memetakan data setiap santri ke baris di Excel
        $jenisNilai = $this->filters['jenis_penilaian'];
        $nilai = $santri->nilai->first();

        return [
            $santri->nis,
            $santri->nama,
            $nilai ? $nilai->$jenisNilai : '-', // Tampilkan nilai jika ada, jika tidak tampilkan '-'
        ];
    }
}
