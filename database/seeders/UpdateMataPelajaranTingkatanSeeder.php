<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class UpdateMataPelajaranTingkatanSeeder extends Seeder
{
    public function run(): void
    {
        // Update semua data existing
        MataPelajaran::where('tingkatan', 'Umum')->update(['tingkatan' => '1']);
        
        // Atau update spesifik berdasarkan nama pelajaran
        $tingkatanMap = [
            '1' => ['Nahwu', 'Shorof', 'Tajwid'],
            '2' => ['Fiqih', 'Aqidah', 'Akhlaq'],
            '3' => ['Tafsir', 'Hadits', 'Sirah'],
            '4' => ['Bahasa Arab', 'Bahasa Inggris'],
            '5' => ['Matematika', 'IPA', 'IPS'],
            '6' => ['Sejarah', 'Geografi', 'Ekonomi'],
        ];
        
        foreach ($tingkatanMap as $tingkatan => $pelajaranArray) {
            foreach ($pelajaranArray as $pelajaran) {
                MataPelajaran::where('nama_pelajaran', 'like', "%{$pelajaran}%")
                    ->update(['tingkatan' => $tingkatan]);
            }
        }
        
        $this->command->info('Data tingkatan mata pelajaran berhasil diupdate!');
    }
}