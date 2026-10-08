<div>
    <x-card title="FORMULIR KPI">
        {{-- Header Bar & Navigasi --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 mb-5 border-b border-gray-200">
            <div>
                <p class="text-xs text-gray-500 font-medium">Sistem Informasi Manajemen Pegawai RSI Banjarnegara</p>
                <h2 class="text-lg font-bold text-gray-900">Penilaian Key Performance Indicator (KPI)</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('detailkaryawan.show', $userId) }}"
                    class="bg-success-700 hover:bg-success-800 text-white font-medium rounded-lg px-4 py-2 transition inline-flex items-center gap-2 text-xs sm:text-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                    class="bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 font-medium rounded-lg px-4 py-2 transition inline-flex items-center gap-2 text-xs sm:text-sm shadow-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Draft
                </button>
                <button type="button" wire:click="save('selesai')" wire:loading.attr="disabled"
                    class="bg-success-600 hover:bg-success-700 text-white font-medium rounded-lg px-4 py-2 transition inline-flex items-center gap-2 text-xs sm:text-sm shadow-sm">
                    <i class="fa-solid fa-check-circle"></i> Simpan & Selesai
                </button>
            </div>
        </div>

        {{-- Bar Periode & Tanggal --}}
        <div class="bg-success-50 border border-success-300 p-3.5 rounded-lg flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-success-900 uppercase">Periode Bulan:</label>
                    <select wire:model.live="periodeBulan" class="text-xs rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white">
                        @foreach ($bulanList as $num => $namaBln)
                            <option value="{{ $num }}">{{ $namaBln }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-success-900 uppercase">Tahun:</label>
                    <input type="number" wire:model.live="periodeTahun" class="text-xs w-24 rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white" min="2020" max="2099">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-success-900 uppercase">Tanggal Penilaian:</label>
                    <input type="date" wire:model.live="tanggalPenilaian" class="text-xs rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white">
                </div>
            </div>
            <div class="text-xs text-success-800 font-medium">
                <i class="fa-solid fa-circle-info mr-1"></i> Data diisi sesuai bagan struktur organisasi RSI Banjarnegara.
            </div>
        </div>

        {{-- BAGIAN 1: IDENTITAS & PIHAK TERKAIT --}}
        <div class="border border-success-300 rounded-lg overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-success-400 text-success-900 font-bold uppercase border-b border-success-300">
                        <tr>
                            <th class="px-3 py-2.5 w-12 text-center border-r border-success-300">NO</th>
                            <th class="px-3 py-2.5 w-64 border-r border-success-300">KATEGORI & RINCIAN</th>
                            <th class="px-3 py-2.5">KETERANGAN / NILAI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-success-200">
                        {{-- 1. YANG DINILAI --}}
                        <tr class="bg-success-100">
                            <td rowspan="5" class="px-3 py-2 text-center font-bold text-sm text-success-900 align-top border-r border-success-200">1</td>
                            <td colspan="2" class="px-3 py-2 font-bold text-success-950 uppercase bg-success-200 border-b border-success-300 tracking-wider">
                                YANG DINILAI
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">a. Nama</td>
                            <td class="px-3 py-2 font-bold text-gray-900">{{ $nama }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">b. Posisi</td>
                            <td class="px-3 py-2">
                                <input type="text" wire:model.defer="posisi" class="w-full text-xs py-1.5 px-2.5 rounded-md border-gray-300 bg-white font-medium text-gray-800 focus:ring focus:ring-success-200 focus:border-success-400">
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">c. Unit Kerja</td>
                            <td class="px-3 py-2">
                                <input type="text" wire:model.defer="unitKerja" class="w-full text-xs py-1.5 px-2.5 rounded-md border-gray-300 bg-white text-gray-800 focus:ring focus:ring-success-200 focus:border-success-400">
                            </td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">d. Gaji / e. Bonus</td>
                            <td class="px-3 py-2 flex flex-wrap gap-4 items-center">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-gray-600 font-medium">Gaji: Rp</span>
                                    <input type="number" wire:model.defer="gaji" class="text-xs py-1.5 px-2.5 rounded-md border-gray-300 w-36 bg-white focus:ring focus:ring-success-200 focus:border-success-400">
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-gray-600 font-medium">Bonus: Rp</span>
                                    <input type="number" wire:model.defer="bonus" class="text-xs py-1.5 px-2.5 rounded-md border-gray-300 w-36 bg-white focus:ring focus:ring-success-200 focus:border-success-400">
                                </div>
                            </td>
                        </tr>

                        {{-- 2. PEJABAT PENILAI --}}
                        <tr class="bg-success-100 border-t-2 border-success-300">
                            <td rowspan="5" class="px-3 py-2 text-center font-bold text-sm text-success-900 align-top border-r border-success-200">2</td>
                            <td colspan="2" class="px-3 py-2 font-bold text-success-950 uppercase bg-success-200 border-b border-success-300 tracking-wider">
                                PEJABAT PENILAI
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">a. Nama</td>
                            <td class="px-3 py-2 font-bold text-gray-900">{{ $penilaiNama }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">b. NIK</td>
                            <td class="px-3 py-2 font-medium text-gray-700">{{ $penilaiNik }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">c. Jabatan</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $penilaiJabatan }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">d. Unit Kerja</td>
                            <td class="px-3 py-2 font-medium text-gray-700">{{ $penilaiUnitKerja }}</td>
                        </tr>

                        {{-- 3. ATASAN PEJABAT PENILAI --}}
                        <tr class="bg-success-100 border-t-2 border-success-300">
                            <td rowspan="5" class="px-3 py-2 text-center font-bold text-sm text-success-900 align-top border-r border-success-200">3</td>
                            <td colspan="2" class="px-3 py-2 font-bold text-success-950 uppercase bg-success-200 border-b border-success-300 tracking-wider">
                                ATASAN PEJABAT PENILAI
                            </td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">a. Nama</td>
                            <td class="px-3 py-2">
                                <select wire:model.live="atasanPenilaiId" class="text-xs py-1.5 px-2.5 rounded-md border-gray-300 bg-white font-medium text-gray-900 w-full max-w-md focus:ring focus:ring-success-200 focus:border-success-400">
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
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">b. NIK</td>
                            <td class="px-3 py-2 font-medium text-gray-700">{{ $atasanPenilaiNik ?: '-' }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">c. Jabatan</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $atasanPenilaiJabatan ?: '-' }}</td>
                        </tr>
                        <tr class="bg-success-50/50">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-success-200">d. Unit Kerja</td>
                            <td class="px-3 py-2 font-medium text-gray-700">{{ $atasanPenilaiUnitKerja ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BAGIAN 2: TABEL PARAMETER KEY PERFORMANCE INDICATOR --}}
        <div class="border border-success-300 rounded-lg overflow-hidden shadow-sm mb-6">
            <div class="px-4 py-3 bg-success-100 border-b border-success-300 flex justify-between items-center">
                <h2 class="text-sm font-bold uppercase text-success-900 tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i> Parameter Key Performance Indicator
                </h2>
                <button type="button" wire:click="addItem"
                    class="px-3.5 py-1.5 text-xs font-medium bg-success-600 hover:bg-success-700 text-white rounded-lg transition shadow-sm inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Tambah Baris KPI
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse min-w-[1100px]">
                    <thead class="uppercase bg-success-400 text-success-900 font-bold tracking-tight text-center border-b border-success-300">
                        <tr>
                            <th class="px-2 py-3 w-10 border-r border-success-300">No</th>
                            <th class="px-3 py-3 w-36 min-w-[130px] border-r border-success-300 text-left">Perspektif</th>
                            <th class="px-3 py-3 w-40 min-w-[140px] border-r border-success-300 text-left">Sasaran Kinerja</th>
                            <th class="px-3 py-3 border-r border-success-300 text-left min-w-[240px]">Parameter Key Performance Indicator</th>
                            <th class="px-2 py-3 w-24 min-w-[85px] border-r border-success-300">Bobot KPI</th>
                            <th class="px-2 py-3 w-24 min-w-[85px] border-r border-success-300">Target</th>
                            <th class="px-2 py-3 w-24 min-w-[85px] border-r border-success-300">Realisasi</th>
                            <th class="px-3 py-3 w-28 min-w-[110px] border-r border-success-300 bg-success-200 text-success-950 font-black text-sm">Skor</th>
                            <th class="px-3 py-3 w-48 min-w-[180px] border-r border-success-300 text-left">Formula / Keterangan</th>
                            <th class="px-2 py-3 w-12 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-success-200">
                        @forelse ($items as $index => $item)
                            <tr class="hover:bg-success-100 transition {{ $index % 2 == 0 ? 'bg-white' : 'bg-success-50/50' }}">
                                {{-- No --}}
                                <td class="px-2 py-2 text-center font-bold text-gray-500 border-r border-success-200">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Perspektif --}}
                                <td class="px-2 py-2 border-r border-success-200">
                                    <input type="text" wire:model.live.debounce.400ms="items.{{ $index }}.perspektif"
                                        class="w-full text-xs p-1.5 rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 font-medium"
                                        placeholder="e.g. Finansial">
                                </td>

                                {{-- Sasaran Kinerja --}}
                                <td class="px-2 py-2 border-r border-success-200">
                                    <input type="text" wire:model.live.debounce.400ms="items.{{ $index }}.sasaran_kinerja"
                                        class="w-full text-xs p-1.5 rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 font-medium"
                                        placeholder="e.g. Lembur">
                                </td>

                                {{-- Parameter KPI --}}
                                <td class="px-2 py-2 border-r border-success-200">
                                    <textarea rows="2" wire:model.live.debounce.400ms="items.{{ $index }}.parameter_kpi"
                                        class="w-full text-xs p-1.5 rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 leading-relaxed"
                                        placeholder="Rincian parameter KPI..."></textarea>
                                </td>

                                {{-- Bobot KPI --}}
                                <td class="px-2 py-2 border-r border-success-200 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.bobot"
                                        class="w-full text-xs py-1.5 px-2 text-center font-bold rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </td>

                                {{-- Target --}}
                                <td class="px-2 py-2 border-r border-success-200 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.target"
                                        class="w-full text-xs py-1.5 px-2 text-center rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </td>

                                {{-- Realisasi --}}
                                <td class="px-2 py-2 border-r border-success-200 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.realisasi"
                                        class="w-full text-xs py-1.5 px-2 text-center rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </td>

                                {{-- Skor --}}
                                <td class="px-2.5 py-2 border-r border-success-200 bg-success-50 text-center">
                                    <input type="number" step="any" wire:model.live.debounce.300ms="items.{{ $index }}.skor"
                                        class="w-full text-sm py-1.5 px-2 text-center font-black rounded-md border-2 border-success-500 bg-white text-success-950 shadow-sm focus:ring focus:ring-success-200 focus:border-success-600 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        placeholder="0">
                                </td>

                                {{-- Keterangan / Formula --}}
                                <td class="px-2 py-2 border-r border-success-200">
                                    <input type="text" wire:model.live.debounce.400ms="items.{{ $index }}.keterangan"
                                        class="w-full text-xs p-1.5 rounded-md border-gray-300 text-gray-700 italic focus:ring focus:ring-success-200 focus:border-success-400"
                                        placeholder="e.g. ≤ 2 Regulasi">
                                </td>

                                {{-- Tombol Hapus Baris --}}
                                <td class="px-2 py-2 text-center">
                                    <button type="button" wire:click="removeItem({{ $index }})"
                                        class="w-7 h-7 inline-flex items-center justify-center text-danger-600 hover:text-white hover:bg-danger-600 rounded transition"
                                        title="Hapus baris ini">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-6 text-gray-500 font-medium">
                                    Belum ada baris indikator KPI. Klik tombol <strong>+ Tambah Baris KPI</strong> di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    {{-- Baris Total --}}
                    <tfoot>
                        <tr class="bg-success-200 font-extrabold text-success-950 border-t-2 border-success-400 text-center">
                            <td colspan="4" class="px-3 py-2.5 text-right uppercase tracking-wider font-bold border-r border-success-300">
                                Total
                            </td>
                            <td class="px-2 py-2.5 border-r border-success-300 text-sm text-success-900 font-black">
                                {{ $totalBobot }}
                            </td>
                            <td colspan="2" class="px-2 py-2.5 border-r border-success-300"></td>
                            <td class="px-2.5 py-2.5 border-r border-success-300 bg-success-300 text-success-950 text-sm font-black">
                                {{ $totalSkor }}
                            </td>
                            <td colspan="2" class="px-3 py-2.5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- BAGIAN 3 & 4: KRITERIA NILAI SKOR & KESIMPULAN PENERIMAAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start mb-6">
            {{-- Tabel Kriteria Nilai Skor --}}
            <div class="border border-success-300 rounded-lg overflow-hidden shadow-sm">
                <div class="px-4 py-2.5 bg-success-400 border-b border-success-300 font-bold text-xs uppercase tracking-wider text-success-900">
                    Aturan & Kriteria Nilai Skor
                </div>
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-success-100 text-success-900 font-bold border-b border-success-200">
                            <th class="px-3 py-2 w-10 text-center border-r border-success-200">No</th>
                            <th class="px-3 py-2 w-28 border-r border-success-200 font-bold text-center">NILAI SKOR</th>
                            <th class="px-3 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-success-200">
                        <tr class="{{ $totalSkor <= 30 ? 'bg-success-200 font-bold text-success-950' : 'bg-white' }}">
                            <td class="px-3 py-2 text-center border-r border-success-200">1</td>
                            <td class="px-3 py-2 font-bold text-center border-r border-success-200">≤ 30</td>
                            <td class="px-3 py-2">30% dari anggaran tunjangan kinerja</td>
                        </tr>
                        <tr class="{{ $totalSkor > 30 && $totalSkor <= 120 ? 'bg-success-200 font-bold text-success-950' : 'bg-white' }}">
                            <td class="px-3 py-2 text-center border-r border-success-200">2</td>
                            <td class="px-3 py-2 font-bold text-center border-r border-success-200">31 - 120</td>
                            <td class="px-3 py-2">Prosentase tunjangan kinerja sesuai nilai skor ({{ $totalSkor }}%)</td>
                        </tr>
                        <tr class="{{ $totalSkor > 120 ? 'bg-success-200 font-bold text-success-950' : 'bg-white' }}">
                            <td class="px-3 py-2 text-center border-r border-success-200">3</td>
                            <td class="px-3 py-2 font-bold text-center border-r border-success-200">&gt; 120</td>
                            <td class="px-3 py-2">120% dari tunjangan kinerja</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Tabel Kesimpulan Penerimaan --}}
            <div class="border border-success-300 rounded-lg overflow-hidden shadow-sm">
                <div class="px-4 py-2.5 bg-success-400 text-success-900 font-bold text-xs uppercase tracking-wider flex items-center justify-between border-b border-success-300">
                    <span>KESIMPULAN PENERIMAAN</span>
                    <span class="text-[11px] font-semibold bg-success-100 text-success-900 px-2 py-0.5 rounded">Kalkulasi Otomatis</span>
                </div>
                <div class="p-4 bg-success-50 space-y-3">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-1.5 pb-2 border-b border-success-200">
                        <label class="text-xs font-semibold text-gray-700">Tunjangan jabatan yang dianggarkan (Rp):</label>
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-bold text-gray-600">Rp</span>
                            <input type="number" wire:model.live.debounce.300ms="anggaranTunjangan"
                                class="text-xs font-bold w-40 rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 text-right bg-white">
                        </div>
                    </div>

                    <div class="flex justify-between items-center pb-2 border-b border-success-200">
                        <span class="text-xs font-semibold text-gray-700">Prosentasi Tunjangan Jabatan:</span>
                        <span class="text-sm font-black text-success-900 bg-success-200 px-2.5 py-1 rounded-md border border-success-300">
                            {{ $persentaseTunjangan }} %
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-1 bg-success-200 p-3 rounded-md border border-success-300">
                        <span class="text-xs font-extrabold text-success-950 uppercase">Tunjangan Jabatan Diterima:</span>
                        <span class="text-base font-black text-success-950">
                            Rp {{ number_format($tunjanganDiterima, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Catatan Penilai --}}
                    <div class="pt-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan Penilai:</label>
                        <textarea rows="2" wire:model.defer="catatan" placeholder="Tambahkan evaluasi atau catatan khusus..."
                            class="w-full text-xs rounded-md border-gray-300 focus:ring focus:ring-success-200 focus:border-success-400 bg-white"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi di Bawah --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('detailkaryawan.show', $userId) }}"
                class="px-5 py-2.5 text-xs sm:text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Batal
            </a>
            <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                class="px-5 py-2.5 text-xs sm:text-sm font-medium rounded-lg bg-yellow-100 text-yellow-900 border border-yellow-300 hover:bg-yellow-500 hover:text-white active:bg-yellow-600 active:text-white focus:bg-yellow-100 focus:text-yellow-900 transition shadow-sm">
                <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Draft
            </button>
            <button type="button" wire:click="save('selesai')" wire:loading.attr="disabled"
                class="px-6 py-2.5 text-xs sm:text-sm font-medium rounded-lg bg-success-600 hover:bg-success-700 text-white transition inline-flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-check"></i> Simpan & Selesai
            </button>
        </div>
    </x-card>
</div>
