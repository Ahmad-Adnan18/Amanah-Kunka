<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SantriImport implements ToModel, WithHeadingRow, WithValidation
{
    // ===================================================================
    // PENGINGAT UNTUK PENGEMBANGAN SELANJUTNYA
    // ===================================================================
    // Saat sistem sudah berjalan stabil dan pendataan sudah lengkap,
    // kembalikan aturan validasi untuk 'nis' dan 'rayon' menjadi 'required'
    // untuk memastikan integritas data jangka panjang.
    // Cukup hapus 'nullable' dari aturan di bawah ini.
    // ===================================================================

    private $kelasMap;

    public function __construct()
    {
        $this->kelasMap = Kelas::pluck('id', 'nama_kelas');
    }

    public function model(array $row)
    {
        $kelas_id = $this->kelasMap->get($row['nama_kelas']);
        if (!$kelas_id) {
            return null;
        }

        return new Santri([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'rayon' => $row['rayon'], // Rayon sekarang opsional
            'kelas_id' => $kelas_id,
        ]);
    }

    public function rules(): array
    {
        return [
            // PERUBAHAN: 'required' diubah menjadi 'nullable'
            'nis' => 'nullable|unique:santris,nis',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:Putra,Putri',
            // PERUBAHAN: 'required' diubah menjadi 'nullable'
            'rayon' => 'nullable|string',
            'nama_kelas' => 'required|exists:kelas,nama_kelas',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.unique' => 'NIS :input sudah ada di database.',
            'nama_kelas.exists' => 'Kelas :input tidak ditemukan di Data Master Kelas.',
        ];
    }
}
