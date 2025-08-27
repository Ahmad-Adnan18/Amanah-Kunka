<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>Rapor Santri - {{ $santri->nama }}</title>
    <style>
        @page { margin: 25px 40px; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header-text { text-align: center; line-height: 1.4; }
        .header-title { font-weight: bold; font-size: 16px; }
        .header-subtitle { font-style: italic; font-size: 11px; }
        .header-address { font-size: 9px; color: #555; }
        .divider { border-top: 1.5px solid #000; margin: 5px 0; }
        .divider-thin { border-top: 0.5px solid #000; margin-top: 2px; margin-bottom: 10px; }
        .content-title { font-size: 14px; font-weight: bold; text-align: center; text-decoration: underline; margin: 20px 0 15px; }
        .info-table { width: 100%; margin-bottom: 20px; font-size: 11px; }
        .info-table td { padding: 2px 0; }
        .info-table td:first-child { width: 120px; }
        .info-table td:nth-child(2) { width: 10px; }
        .nilai-table { width: 100%; border-collapse: collapse; }
        .nilai-table th, .nilai-table td { border: 1px solid #000; padding: 7px; text-align: center; }
        .nilai-table th { background-color: #f2f2f2; font-weight: bold; }
        .nilai-table td.mapel { text-align: left; }
        .signature-box { margin-top: 40px; width: 260px; float: right; text-align: center; font-size: 11px; }
        .wali-kelas { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    {{-- [PERBAIKAN] Struktur HTML diperbaiki, tidak lagi di dalam tabel --}}
    <div class="header-text">
        <div class="header-title">PONDOK PESANTREN KUN KARIMA</div>
        <div class="header-subtitle">"Berdiri di atas dan untuk semua golongan"</div>
        <div class="header-address">Kp. Ciekek Hilir RT/10 Karaton, Majasari, Pandeglang 4221.1 | WA: 081219669792</div>
    </div>
    <div class="divider"></div>
    <div class="divider-thin"></div>

    <div class="content-title">
        @if($jenisPenilaian)
            LAPORAN NILAI {{ strtoupper(str_replace('nilai_', '', $jenisPenilaian)) }}
        @else
            LAPORAN HASIL BELAJAR SANTRI
        @endif
    </div>

    <table class="info-table">
        <tr>
            <td>Nama Santri</td><td>:</td><td><strong>{{ $santri->nama }}</strong></td>
            <td>Kelas</td><td>:</td><td>{{ $santri->kelas->nama_kelas ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>NIS</td><td>:</td><td>{{ $santri->nis ?? 'N/A' }}</td>
            <td>Semester</td><td>:</td><td>{{ ucfirst($semester) }} / {{ $tahunAjaran }}</td>
        </tr>
    </table>

    <table class="nilai-table">
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Mata Pelajaran</th>
                <th colspan="{{ !$jenisPenilaian ? 5 : 1 }}">Nilai</th>
            </tr>
            <tr>
                @if(!$jenisPenilaian || $jenisPenilaian == 'nilai_tugas') <th>Tugas</th> @endif
                @if(!$jenisPenilaian || $jenisPenilaian == 'nilai_uts') <th>UTS</th> @endif
                @if(!$jenisPenilaian || $jenisPenilaian == 'nilai_uas') <th>UAS</th> @endif
                @if(!$jenisPenilaian) <th>Nilai Akhir</th> <th>Predikat</th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($raporData as $nilai)
            <tr>
                <td class="mapel">{{ $nilai->mataPelajaran->nama_pelajaran }}</td>
                @if(!$jenisPenilaian || $jenisPenilaian == 'nilai_tugas') <td>{{ $nilai->nilai_tugas ?? '-' }}</td> @endif
                @if(!$jenisPenilaian || $jenisPenilaian == 'nilai_uts') <td>{{ $nilai->nilai_uts ?? '-' }}</td> @endif
                @if(!$jenisPenilaian || $jenisPenilaian == 'nilai_uas') <td>{{ $nilai->nilai_uas ?? '-' }}</td> @endif
                @if(!$jenisPenilaian)
                    @php
                        $nilaiAngka = collect([$nilai->nilai_tugas, $nilai->nilai_uts, $nilai->nilai_uas])->filter(fn($val) => is_numeric($val));
                        $avg = $nilaiAngka->isNotEmpty() ? round($nilaiAngka->avg()) : null;
                        $predikat = '-';
                        if (!is_null($avg)) {
                            if ($avg >= 90) $predikat = 'Mumtaz';
                            elseif ($avg >= 80) $predikat = 'Jayyid Jiddan';
                            elseif ($avg >= 70) $predikat = 'Jayyid';
                            elseif ($avg >= 60) $predikat = 'Maqbul';
                            else $predikat = 'Rasib';
                        }
                    @endphp
                    <td><strong>{{ $avg ?? '-' }}</strong></td>
                    <td>{{ $predikat }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ !$jenisPenilaian ? 6 : 2 }}" style="padding: 20px;">Tidak ada data nilai untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-box">
        <div>Pandeglang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</div>
        <div>Wali Kelas</div>
        {{-- [PERBAIKAN] Menggunakan variabel $namaWaliKelas yang sudah benar --}}
        <div class="wali-kelas">({{ $namaWaliKelas }})</div>
        <div>Nama Lengkap & Tanda Tangan</div>
    </div>
</body>
</html>
