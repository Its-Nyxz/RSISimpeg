<div>
    <x-card title="FORMULIR KPI">
        {{-- Header Bar & Navigasi --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 mb-5 border-b border-gray-200">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 font-medium">Sistem Informasi Manajemen Pegawai RSI Banjarnegara</p>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mt-0.5">Penilaian Key Performance Indicator (KPI)</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('detailkaryawan.show', $userId) }}"
                    class="bg-success-700 hover:bg-success-800 text-white font-semibold rounded-lg px-4 py-2.5 transition inline-flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                    class="bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 font-semibold rounded-lg px-4 py-2.5 transition inline-flex items-center gap-2 text-sm shadow-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Draft
                </button>
                <button type="button" wire:click="save('selesai')" wire:loading.attr="disabled"
                    class="bg-success-600 hover:bg-success-700 text-white font-semibold rounded-lg px-4 py-2.5 transition inline-flex items-center gap-2 text-sm shadow-sm">
                    <i class="fa-solid fa-check-circle"></i> Simpan & Selesai
                </button>
            </div>
        </div>

        {{-- Bar Periode & Tanggal (Tinggi / Height diperbesar & lebih proporsional) --}}
        <div class="bg-success-50 border border-success-300 p-4 sm:p-5 rounded-xl flex flex-wrap items-center justify-between gap-4 mb-6 shadow-xs">
            <div class="flex flex-wrap items-center gap-5 sm:gap-6">
                <div class="flex items-center gap-2.5">
                    <label class="text-sm font-bold text-success-950 uppercase tracking-tight">Periode Bulan:</label>
                    <select wire:model.live="periodeBulan" class="text-sm py-2 px-3 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white font-medium shadow-xs">
                        @foreach ($bulanList as $num => $namaBln)
                            <option value="{{ $num }}">{{ $namaBln }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2.5">
                    <label class="text-sm font-bold text-success-950 uppercase tracking-tight">Tahun:</label>
                    <input type="number" wire:model.live="periodeTahun" class="text-sm py-2 px-3 w-28 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white font-medium shadow-xs" min="2020" max="2099">
                </div>
                <div class="flex items-center gap-2.5">
                    <label class="text-sm font-bold text-success-950 uppercase tracking-tight">Tanggal Penilaian:</label>
                    <input type="date" wire:model.live="tanggalPenilaian" class="text-sm py-2 px-3 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white font-medium shadow-xs">
                </div>
            </div>
            <div class="text-sm text-success-800 font-medium flex items-center">
                <i class="fa-solid fa-circle-info mr-1.5 text-success-700"></i> Data diisi sesuai bagan struktur organisasi RSI Banjarnegara.
            </div>
        </div>

        {{-- BAGIAN 1: IDENTITAS & PIHAK TERKAIT --}}
        <div class="border border-success-300 rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-success-400 text-success-950 font-bold uppercase border-b border-success-300 text-sm">
                        <tr>
                            <th class="px-4 py-3 w-14 text-center border-r border-success-300">NO</th>
                            <th class="px-4 py-3 w-72 border-r border-success-300">KATEGORI & RINCIAN</th>
                            <th class="px-4 py-3">KETERANGAN / NILAI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-success-200">
                        {{-- 1. YANG DINILAI --}}
                        <tr class="bg-success-100">
                            <td rowspan="5" class="px-4 py-3 text-center font-bold text-base text-success-900 align-top border-r border-success-200">1</td>
                            <td colspan="2" class="px-4 py-3 font-extrabold text-success-950 uppercase bg-success-200 border-b border-success-300 tracking-wider text-sm sm:text-base">
                                YANG DINILAI
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">a. Nama</td>
                            <td class="px-4 py-2.5 font-bold text-gray-900 text-sm sm:text-base">{{ $nama }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">b. Posisi</td>
                            {{-- Posisi statis (tidak bisa diedit) sesuai data master karyawan --}}
                            <td class="px-4 py-2.5 font-bold text-gray-900 text-sm sm:text-base">
                                {{ $posisi ?: '-' }}
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">c. Unit Kerja</td>
                            {{-- Unit Kerja statis (tidak bisa diedit) sesuai data master karyawan --}}
                            <td class="px-4 py-2.5 font-bold text-gray-900 text-sm sm:text-base">
                                {{ $unitKerja ?: '-' }}
                            </td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">d. Gaji / e. Bonus</td>
                            <td class="px-4 py-2.5 flex flex-wrap gap-5 items-center text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-700 font-bold">Gaji: Rp</span>
                                    <input type="number" wire:model.defer="gaji" class="text-sm py-2 px-3 rounded-lg border-gray-300 w-44 bg-white font-medium focus:ring focus:ring-success-200 focus:border-success-400">
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-700 font-bold">Bonus: Rp</span>
                                    <input type="number" wire:model.defer="bonus" class="text-sm py-2 px-3 rounded-lg border-gray-300 w-44 bg-white font-medium focus:ring focus:ring-success-200 focus:border-success-400">
                                </div>
                            </td>
                        </tr>

                        {{-- 2. PEJABAT PENILAI --}}
                        <tr class="bg-success-100 border-t-2 border-success-300">
                            <td rowspan="5" class="px-4 py-3 text-center font-bold text-base text-success-900 align-top border-r border-success-200">2</td>
                            <td colspan="2" class="px-4 py-3 font-extrabold text-success-950 uppercase bg-success-200 border-b border-success-300 tracking-wider text-sm sm:text-base">
                                PEJABAT PENILAI
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">a. Nama</td>
                            <td class="px-4 py-2.5 font-bold text-gray-900 text-sm sm:text-base">{{ $penilaiNama }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">b. NIK</td>
                            <td class="px-4 py-2.5 font-medium text-gray-800 text-sm">{{ $penilaiNik }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">c. Jabatan</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-900 text-sm">{{ $penilaiJabatan }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">d. Unit Kerja</td>
                            <td class="px-4 py-2.5 font-medium text-gray-800 text-sm">{{ $penilaiUnitKerja }}</td>
                        </tr>

                        {{-- 3. ATASAN PEJABAT PENILAI --}}
                        <tr class="bg-success-100 border-t-2 border-success-300">
                            <td rowspan="5" class="px-4 py-3 text-center font-bold text-base text-success-900 align-top border-r border-success-200">3</td>
                            <td colspan="2" class="px-4 py-3 font-extrabold text-success-950 uppercase bg-success-200 border-b border-success-300 tracking-wider text-sm sm:text-base">
                                ATASAN PEJABAT PENILAI
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">a. Nama</td>
                            <td class="px-4 py-2.5">
                                <select wire:model.live="atasanPenilaiId" class="text-sm py-2 px-3 rounded-lg border-gray-300 bg-white font-medium text-gray-900 w-full max-w-md focus:ring focus:ring-success-200 focus:border-success-400 shadow-xs">
                                    <option value="">-- Pilih Atasan Pejabat Penilai --</option>
                                    @foreach ($listAtasan as $atasan)
                                        <option value="{{ $atasan->id }}">
                                            {{ $atasan->name }} - {{ $atasan->kategorijabatan?->nama ?? $atasan->roles->first()?->name ?? 'Pimpinan' }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">b. NIK</td>
                            <td class="px-4 py-2.5 font-medium text-gray-800 text-sm">{{ $atasanPenilaiNik ?: '-' }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">c. Jabatan</td>
                            <td class="px-4 py-2.5 font-semibold text-gray-900 text-sm">{{ $atasanPenilaiJabatan ?: '-' }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-4 py-2.5 font-semibold text-gray-700 border-r border-success-200 text-sm">d. Unit Kerja</td>
                            <td class="px-4 py-2.5 font-medium text-gray-800 text-sm">{{ $atasanPenilaiUnitKerja ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BAGIAN 2: TABEL PARAMETER KEY PERFORMANCE INDICATOR --}}
        <div class="border border-success-300 rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="px-5 py-3.5 bg-success-100 border-b border-success-300 flex justify-between items-center">
                <h2 class="text-sm sm:text-base font-bold uppercase text-success-950 tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-success-800"></i> Parameter Key Performance Indicator
                </h2>
                <button type="button" wire:click="addItem"
                    class="px-4 py-2 text-sm font-semibold bg-success-600 hover:bg-success-700 text-white rounded-lg transition shadow-sm inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Tambah Baris KPI
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse min-w-[1150px]">
                    <thead class="uppercase bg-success-400 text-success-950 font-bold tracking-tight text-center border-b border-success-300 text-sm">
                        <tr>
                            <th class="px-3 py-3 w-12 border-r border-success-300">No</th>
                            <th class="px-3 py-3 w-40 min-w-[140px] border-r border-success-300 text-left">Perspektif</th>
                            <th class="px-3 py-3 w-44 min-w-[150px] border-r border-success-300 text-left">Sasaran Kinerja</th>
                            <th class="px-3 py-3 border-r border-success-300 text-left min-w-[260px]">Parameter Key Performance Indicator</th>
                            <th class="px-2.5 py-3 w-28 min-w-[95px] border-r border-success-300">Bobot KPI</th>
                            <th class="px-2.5 py-3 w-28 min-w-[95px] border-r border-success-300">Target</th>
                            <th class="px-2.5 py-3 w-28 min-w-[95px] border-r border-success-300">Realisasi</th>
                            <th class="px-3 py-3 w-32 min-w-[120px] border-r border-success-300 bg-success-200 text-success-950 font-black text-sm">Skor</th>
                            <th class="px-3 py-3 w-52 min-w-[190px] border-r border-success-300 text-left">Formula / Keterangan</th>
                            <th class="px-3 py-3 w-14 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-success-200">
                        @forelse ($items as $index => $item)
                            <tr class="hover:bg-success-100 transition {{ $index % 2 == 0 ? 'bg-white' : 'bg-success-50/50' }}">
                                {{-- No --}}
                                <td class="px-3 py-2.5 text-center font-bold text-gray-700 border-r border-success-200 text-sm">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Perspektif --}}
                                <td class="px-2.5 py-2.5 border-r border-success-200">
                                    <input type="text" wire:model.live.debounce.400ms="items.{{ $index }}.perspektif"
                                        class="w-full text-sm py-2 px-2.5 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 font-medium"
                                        placeholder="e.g. Finansial">
                                </td>

                                {{-- Sasaran Kinerja --}}
                                <td class="px-2.5 py-2.5 border-r border-success-200">
                                    <input type="text" wire:model.live.debounce.400ms="items.{{ $index }}.sasaran_kinerja"
                                        class="w-full text-sm py-2 px-2.5 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 font-medium"
                                        placeholder="e.g. Lembur">
                                </td>

                                {{-- Parameter KPI --}}
                                <td class="px-2.5 py-2.5 border-r border-success-200">
                                    <textarea rows="2" wire:model.live.debounce.400ms="items.{{ $index }}.parameter_kpi"
                                        class="w-full text-sm py-2 px-2.5 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 leading-relaxed font-normal"
                                        placeholder="Rincian parameter KPI..."></textarea>
                                </td>

                                {{-- Bobot KPI --}}
                                <td class="px-2 py-2.5 border-r border-success-200 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.bobot"
                                        class="w-full text-sm py-2 px-2 text-center font-bold rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </td>

                                {{-- Target --}}
                                <td class="px-2 py-2.5 border-r border-success-200 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.target"
                                        class="w-full text-sm py-2 px-2 text-center rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </td>

                                {{-- Realisasi --}}
                                <td class="px-2 py-2.5 border-r border-success-200 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.realisasi"
                                        class="w-full text-sm py-2 px-2 text-center rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </td>

                                {{-- Skor --}}
                                <td class="px-2.5 py-2.5 border-r border-success-200 bg-success-50 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.skor"
                                        class="w-full text-base py-2 px-2 text-center font-black rounded-lg border-2 border-success-500 bg-white text-success-950 shadow-sm focus:ring focus:ring-success-200 focus:border-success-600 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        placeholder="0">
                                </td>

                                {{-- Keterangan / Formula --}}
                                <td class="px-2.5 py-2.5 border-r border-success-200">
                                    <input type="text" wire:model.live.debounce.400ms="items.{{ $index }}.keterangan"
                                        class="w-full text-sm py-2 px-2.5 rounded-lg border-gray-300 text-gray-700 italic focus:ring focus:ring-success-200 focus:border-success-400 font-medium"
                                        placeholder="e.g. ≤ 2 Regulasi">
                                </td>

                                {{-- Tombol Hapus Baris --}}
                                <td class="px-2.5 py-2.5 text-center">
                                    <button type="button" wire:click="removeItem({{ $index }})"
                                        class="w-8 h-8 inline-flex items-center justify-center text-danger-600 hover:text-white hover:bg-danger-600 rounded-lg transition"
                                        title="Hapus baris ini">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-6 text-gray-500 font-medium text-sm">
                                    Belum ada baris indikator KPI. Klik tombol <strong>+ Tambah Baris KPI</strong> di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    {{-- Baris Total --}}
                    <tfoot>
                        <tr class="bg-success-200 font-extrabold text-success-950 border-t-2 border-success-400 text-center text-sm">
                            <td colspan="4" class="px-4 py-3 text-right uppercase tracking-wider font-bold border-r border-success-300">
                                Total
                            </td>
                            <td class="px-3 py-3 border-r border-success-300 text-base text-success-950 font-black">
                                {{ $totalBobot }}
                            </td>
                            <td colspan="2" class="px-3 py-3 border-r border-success-300"></td>
                            <td class="px-3 py-3 border-r border-success-300 bg-success-300 text-success-950 text-base font-black">
                                {{ $totalSkor }}
                            </td>
                            <td colspan="2" class="px-4 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- BAGIAN 3 & 4: KRITERIA NILAI SKOR & KESIMPULAN PENERIMAAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start mb-6">
            {{-- Tabel Kriteria Nilai Skor --}}
            <div class="border border-success-300 rounded-xl overflow-hidden shadow-sm">
                <div class="px-4 py-3 bg-success-400 border-b border-success-300 font-bold text-sm uppercase tracking-wider text-success-950">
                    Aturan & Kriteria Nilai Skor
                </div>
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-success-100 text-success-950 font-bold border-b border-success-200">
                            <th class="px-3.5 py-2.5 w-12 text-center border-r border-success-200">No</th>
                            <th class="px-3.5 py-2.5 w-32 border-r border-success-200 font-bold text-center">NILAI SKOR</th>
                            <th class="px-3.5 py-2.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-success-200">
                        <tr class="{{ $totalSkor <= 30 ? 'bg-success-200 font-bold text-success-950' : 'bg-white' }}">
                            <td class="px-3.5 py-2.5 text-center border-r border-success-200 font-medium">1</td>
                            <td class="px-3.5 py-2.5 font-bold text-center border-r border-success-200">≤ 30</td>
                            <td class="px-3.5 py-2.5 font-medium">30% dari anggaran tunjangan kinerja</td>
                        </tr>
                        <tr class="{{ $totalSkor > 30 && $totalSkor <= 120 ? 'bg-success-200 font-bold text-success-950' : 'bg-white' }}">
                            <td class="px-3.5 py-2.5 text-center border-r border-success-200 font-medium">2</td>
                            <td class="px-3.5 py-2.5 font-bold text-center border-r border-success-200">31 - 120</td>
                            <td class="px-3.5 py-2.5 font-medium">Prosentase tunjangan kinerja sesuai nilai skor ({{ $totalSkor }}%)</td>
                        </tr>
                        <tr class="{{ $totalSkor > 120 ? 'bg-success-200 font-bold text-success-950' : 'bg-white' }}">
                            <td class="px-3.5 py-2.5 text-center border-r border-success-200 font-medium">3</td>
                            <td class="px-3.5 py-2.5 font-bold text-center border-r border-success-200">&gt; 120</td>
                            <td class="px-3.5 py-2.5 font-medium">120% dari tunjangan kinerja</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Tabel Kesimpulan Penerimaan --}}
            <div class="border border-success-300 rounded-xl overflow-hidden shadow-sm">
                <div class="px-4 py-3 bg-success-400 text-success-950 font-bold text-sm uppercase tracking-wider flex items-center justify-between border-b border-success-300">
                    <span>KESIMPULAN PENERIMAAN</span>
                    <span class="text-xs font-semibold bg-success-100 text-success-900 px-2.5 py-1 rounded-full">Kalkulasi Otomatis</span>
                </div>
                <div class="p-4 sm:p-5 bg-success-50 space-y-3.5">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 pb-2.5 border-b border-success-200">
                        <label class="text-sm font-bold text-gray-800">Tunjangan jabatan yang dianggarkan (Rp):</label>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-bold text-gray-700">Rp</span>
                            <input type="number" wire:model.live.debounce.300ms="anggaranTunjangan"
                                class="text-sm font-bold w-44 py-2 px-3 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 text-right bg-white shadow-xs">
                        </div>
                    </div>

                    <div class="flex justify-between items-center pb-2.5 border-b border-success-200">
                        <span class="text-sm font-bold text-gray-800">Prosentasi Tunjangan Jabatan:</span>
                        <span class="text-base font-black text-success-950 bg-success-200 px-3 py-1.5 rounded-lg border border-success-300">
                            {{ $persentaseTunjangan }} %
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-1 bg-success-200 p-3.5 rounded-lg border border-success-300 shadow-xs">
                        <span class="text-sm font-extrabold text-success-950 uppercase tracking-tight">Tunjangan Jabatan Diterima:</span>
                        <span class="text-lg sm:text-xl font-black text-success-950">
                            Rp {{ number_format($tunjanganDiterima, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Catatan Penilai --}}
                    <div class="pt-2">
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Catatan Tambahan Penilai:</label>
                        <textarea rows="2" wire:model.defer="catatan" placeholder="Tambahkan evaluasi atau catatan khusus..."
                            class="w-full text-sm p-3 rounded-lg border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white font-normal"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi di Bawah --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('detailkaryawan.show', $userId) }}"
                class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Batal
            </a>
            <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 transition shadow-sm">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Draft
            </button>
            <button type="button" wire:click="save('selesai')" wire:loading.attr="disabled"
                class="px-6 py-2.5 text-sm font-semibold rounded-lg bg-success-600 hover:bg-success-700 text-white transition inline-flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-check"></i> Simpan & Selesai
            </button>
        </div>
    </x-card>
</div>
