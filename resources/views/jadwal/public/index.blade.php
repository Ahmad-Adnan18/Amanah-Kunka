<x-app-layout>
    {{-- [PERBAIKAN FINAL] Menyesuaikan style cetak agar pas dalam satu halaman A4 --}}
    <style>
        @media print {
            @page {
                size: A4 portrait;
                /* Bisa ganti 'landscape' kalau tabel lebar */
                margin: 15mm;
                /* Aman biar gak kepotong */
            }

            html,
            body {
                width: 210mm;

                margin: 0 auto;
                font-size: 9pt;
                /* Sedikit lebih besar dari 7.5pt biar kebaca */
            }

            #print-area {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }


            .no-print {
                display: none;
            }

            /* === Layout Grid === */
            .printable-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }

            /* Hindari kartu hari terpotong */
            .day-card {
                page-break-inside: avoid;
            }

            /* Jarak antar isi jadwal */
            .day-card-content {
                --tw-space-y-reverse: 0;
                margin-top: calc(0.5rem * calc(1 - var(--tw-space-y-reverse)));
                margin-bottom: calc(0.5rem * var(--tw-space-y-reverse));
            }

            .day-card-content> :not([hidden])~ :not([hidden]) {
                --tw-space-y-reverse: 0;
                margin-top: calc(0.5rem * calc(1 - var(--tw-space-y-reverse)));
                margin-bottom: calc(0.5rem * var(--tw-space-y-reverse));
            }

            /* Blok tanda tangan */
            .signature-block {
                margin-top: 2.5rem !important;
            }

            .signature-space {
                height: 4rem !important;
            }

            /* Header cetak */
            .print-header h2 {
                font-size: 1.5rem !important;
            }

            .print-header h3 {
                font-size: 1.25rem !important;
            }

            /* Ringkasan jam mengajar */
            .teaching-summary {
                margin-top: 1.5rem;
                font-size: 9pt;
                display: table !important;
            }

            .summary-table {
                width: 100%;
                border-collapse: collapse;
            }

            .summary-table th,
            .summary-table td {
                border: 1px solid #000;
                padding: 3px 5px;
                text-align: center;
            }

            .day-card:last-child {
                page-break-after: auto;
            }
        }

    </style>

    {{-- ================= MAIN WRAPPER ================= --}}
    <div class="bg-slate-50 min-h-screen" x-data="{
            activeTab: 'kelas',
            selectedClass: '',
            selectedTeacher: '',
            schedules: {{ json_encode($scheduleData) }},

            get scheduleToShow() {
                if (this.activeTab === 'kelas' && this.selectedClass) {
                    return this.schedules.byClass[this.selectedClass] || {};
                }
                if (this.activeTab === 'guru' && this.selectedTeacher) {
                    return this.schedules.byTeacher[this.selectedTeacher] || {};
                }
                return {};
            },

            get selectedTeacherName() {
                if (this.selectedTeacher) {
                    const select = document.getElementById('guru_select');
                    return select.options[select.selectedIndex].text;
                }
                return '';
            },

            // [TAMBAHAN] Ringkasan jumlah jam mengajar
            get teachingHoursSummary() {
                const teacherId = this.selectedTeacher;
                if (teacherId && this.schedules.teachingHours && this.schedules.teachingHours[teacherId]) {
                    return this.schedules.teachingHours[teacherId];
                }
                return { sabtu: 0, ahad: 0, senin: 0, selasa: 0, rabu: 0, kamis: 0, total: 0 };
            }
        }">

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

            {{-- ================= HEADER ================= --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-8 no-print">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Lihat Jadwal Pelajaran</h1>
                <p class="mt-1 text-slate-600">Pilih untuk menampilkan jadwal berdasarkan kelas atau guru.</p>
            </div>

            {{-- ================= TAB MENU ================= --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 no-print">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'kelas'" :class="{ 'border-red-500 text-red-600': activeTab === 'kelas', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'kelas' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Jadwal per Kelas
                        </button>

                        <button @click="activeTab = 'guru'" :class="{ 'border-red-500 text-red-600': activeTab === 'guru', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'guru' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Jadwal per Guru
                        </button>
                    </nav>
                </div>

                {{-- Dropdown per Kelas --}}
                <div class="p-6">
                    <div x-show="activeTab === 'kelas'" x-cloak>
                        <div class="max-w-xs">
                            <label for="kelas_select" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
                            <select x-model="selectedClass" id="kelas_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">-- Silakan Pilih --</option>
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Dropdown per Guru --}}
                    <div x-show="activeTab === 'guru'" x-cloak>
                        <div class="flex items-end gap-4">
                            <div class="max-w-xs flex-grow">
                                <label for="guru_select" class="block text-sm font-medium text-gray-700">Pilih Guru</label>
                                <select x-model="selectedTeacher" id="guru_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Silakan Pilih --</option>
                                    @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button @click="window.print()" x-show="selectedTeacher" class="inline-flex items-center justify-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600">
                                Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= JADWAL PER KELAS ================= --}}
            <div x-show="activeTab === 'kelas' && selectedClass" class="mt-8 overflow-x-auto bg-white rounded-2xl shadow-lg border border-slate-200 p-4" x-cloak>
                <h3 class="text-xl font-bold text-slate-800 mb-4" x-text="'Jadwal Kelas ' + document.getElementById('kelas_select').options[document.getElementById('kelas_select').selectedIndex].text">
                </h3>

                <table class="min-w-full border-collapse">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-sm font-semibold text-slate-900 border border-slate-200 w-28">Jam Ke-</th>
                            @foreach ($days as $dayName)
                            <th class="px-3 py-3 text-center text-sm font-semibold text-slate-900 border border-slate-200">{{ $dayName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($timeSlots as $timeSlot)
                        <tr>
                            <td class="px-3 py-3 text-sm font-medium text-slate-900 border border-slate-200 bg-slate-50 text-center">{{ $timeSlot }}</td>
                            @foreach ($days as $dayKey => $dayName)
                            <td class="px-2 py-2 text-sm text-slate-500 border border-slate-200 align-top h-24">
                                <template x-if="scheduleToShow[{{ $dayKey }}] && scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}]">
                                    <div class="p-2 bg-amber-50 border border-amber-200 rounded-lg text-left h-full flex flex-col justify-between text-xs">
                                        <div>
                                            <div class="font-semibold text-slate-900" x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].subject"></div>
                                            <div class="mt-1" x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].teacher"></div>
                                        </div>
                                        {{--<div class="text-slate-400 italic mt-2 text-right" x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].room"></div>--}}
                                    </div>
                                </template>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ================= JADWAL PER GURU (PRINTABLE) ================= --}}
            <div id="print-area">
                <div x-show="activeTab === 'guru' && selectedTeacher" class="mt-8 max-w-5xl mx-auto bg-white p-8" x-cloak>

                    {{-- Header Cetak --}}
                    <div class="text-center mb-8 print-header">
                        <h2 class="text-2xl font-bold" style="font-family: 'Times New Roman', serif;">جَدْوَلُ التَدْرِيْس</h2>
                        <h3 class="text-xl font-semibold" x-text="'Al Ustadz/ah ' + selectedTeacherName"></h3>
                    </div>

                    {{-- Jadwal Harian Guru --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-12 printable-grid">
                        @foreach ($days as $dayKey => $dayName)
                        <div class="p-4 day-card">
                            <h4 class="text-center font-bold text-lg mb-4">{{ strtoupper($dayName) }}</h4>
                            <div class="space-y-3 day-card-content">
                                @foreach ($timeSlots as $timeSlot)
                                <div class="flex items-center text-sm">
                                    <span class="w-6 font-mono">{{ $timeSlot }}.</span>
                                    <div class="flex-1 border-b border-dotted border-slate-400 pb-1">
                                        <template x-if="scheduleToShow[{{ $dayKey }}] && scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}]">
                                            <span class="flex justify-between">
                                                <span x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].subject"></span>
                                                <span class="font-semibold" x-text="scheduleToShow[{{ $dayKey }}][{{ $timeSlot }}].class"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan Jam Mengajar --}}
                    <div class="teaching-summary mt-6">
                        <h5 class="font-bold mb-3 text-center" style="font-size: 10pt;">REKAPITULASI JAM MENGAJAR</h5>
                        <table class="summary-table">
                            <thead>
                                <tr>
                                    <th>HARI</th>
                                    <th>SABTU</th>
                                    <th>AHAD</th>
                                    <th>SENIN</th>
                                    <th>SELASA</th>
                                    <th>RABU</th>
                                    <th>KAMIS</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-semibold">JUMLAH JAM</td>
                                    <td x-text="teachingHoursSummary.sabtu"></td>
                                    <td x-text="teachingHoursSummary.ahad"></td>
                                    <td x-text="teachingHoursSummary.senin"></td>
                                    <td x-text="teachingHoursSummary.selasa"></td>
                                    <td x-text="teachingHoursSummary.rabu"></td>
                                    <td x-text="teachingHoursSummary.kamis"></td>
                                    <td x-text="teachingHoursSummary.total" class="total-cell"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Tanda Tangan dan Waktu KBM --}}
                    <div class="mt-16 flex justify-between items-end signature-block">

                        {{-- Jadwal Waktu KBM --}}
                        <div class="bg-white p-6">
                            <h5 class="font-bold mb-3 text-sm text-gray-700">JADWAL WAKTU KBM</h5>
                            <table class="text-xs border-collapse border border-slate-500 w-full">
                                <thead class="bg-slate-100">
                                    <tr>
                                        <th class="border border-slate-400 px-2 py-2 w-1/4">Jam ke-</th>
                                        <th class="border border-slate-400 px-2 py-2 w-1/4">Waktu</th>
                                        <th class="border border-slate-400 px-2 py-2 w-1/4">Jam ke-</th>
                                        <th class="border border-slate-400 px-2 py-2 w-1/4">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-slate-400 px-2 py-2 text-center">1</td>
                                        <td class="border border-slate-400 px-2 py-2">07:00 - 07:45</td>
                                        <td colspan="2" class="border border-slate-400 px-2 py-2 text-center">Istirahat</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-slate-400 px-2 py-2 text-center">2</td>
                                        <td class="border border-slate-400 px-2 py-2">07:45 - 08:30</td>
                                        <td class="border border-slate-400 px-2 py-2 text-center">5</td>
                                        <td class="border border-slate-400 px-2 py-2">11:00 - 11:45</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="border border-slate-400 px-2 py-2 text-center">Istirahat</td>
                                        <td class="border border-slate-400 px-2 py-2 text-center">6</td>
                                        <td class="border border-slate-400 px-2 py-2">11:45 - 12:30</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-slate-400 px-2 py-2 text-center">3</td>
                                        <td class="border border-slate-400 px-2 py-2">09:00 - 09:45</td>
                                        <td colspan="2" class="border border-slate-400 px-2 py-2 text-center">Istirahat</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-slate-400 px-2 py-2 text-center">4</td>
                                        <td class="border border-slate-400 px-2 py-2">09:45 - 10:30</td>
                                        <td class="border border-slate-400 px-2 py-2 text-center">7</td>
                                        <td class="border border-slate-400 px-2 py-2">14:15 - 15:00</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="footer-note mt-4 p-3 rounded-md">
                                <p class="text-xs text-gray-700">
                                    <span class="font-bold">NB.</span> Apabila terjadi kekeliruan harap segera menghubungi Bag. Pengajaran Pondok.
                                </p>
                            </div>
                        </div>

                        {{-- Blok Tanda Tangan --}}
                        <div class="text-center text-sm">
                            <p>Ciekek Hilir, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            <p>An. Pemimpin Pondok Pesantren Kun Karima</p>
                            <p>Direktur II</p>
                            <div class="h-20 signature-space"></div>
                            <p class="font-bold underline">Al Ustadz Dzikri Adzkiya Arief, B.A.</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ================= END JADWAL PER GURU ================= --}}

        </div>
    </div>
</x-app-layout>
