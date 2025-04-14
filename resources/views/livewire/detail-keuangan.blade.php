<div>
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-success-900">Keuangan</h1>
            {{-- <p class="text-base text-success-900 flex items-center">
            <i class="fa-solid fa-caret-right mr-2"></i> Data Karyawan
        </p>
        <p class="text-base text-success-900 flex items-center">
            <i class="fa-solid fa-caret-right mr-2"></i> Detail Data Karyawan
        </p> --}}
            <select wire:model.live="bulan"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="tahun"
                class="rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600">
                @foreach (range(now()->year - 5, now()->year) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex space-x-3 justify-end">
            <a href="{{ route('keuangan.index') }}"
                class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Kembali
            </a>
            <a href="{{ route('keuangan.potongan', [$user->id, $bulan, $tahun]) }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                <i class="fa-solid fa-chart-pie"></i> Add Potongan
            </a>
        </div>
    </div>


    <!-- Slip Gaji dalam Card -->
    <x-card title="{{ $user->nip ?? '-' }}" class="mb-6 text-success-900">
        <!-- Header -->
        <div class="text-center text-green-900 font-bold text-lg mb-4">
            SLIP PENERIMAAN GAJI KARYAWAN
        </div>

        <!-- Informasi Karyawan -->
        <div class="flex flex-col items-center gap-2 mb-6">
            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>No Urut</strong>
                <span>{{ $user->id ?? '-' }}</span>
            </div>
            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>Nama</strong>
                <span>{{ $user->name ?? '-' }}</span>
            </div>
            @if ($gajiBruto && $potonganData)
                <!-- Tombol Toggle -->
                <div class="text-right w-full max-w-md mx-auto mb-2" x-data="{ showDetail: false }">
                    <button @click="showDetail = !showDetail" class="text-sm text-success-700 hover:underline">
                        <span x-show="!showDetail">Lihat detail Gaji Bruto ⬇</span>
                        <span x-show="showDetail">Sembunyikan detail ⬆</span>
                    </button>

                    <!-- Detail Gaji Bruto -->
                    <div x-show="showDetail" x-transition
                        class="w-full max-w-md mx-auto space-y-2 text-sm bg-gray-50 p-4 rounded shadow mt-2">
                        <div class="flex justify-between">
                            <strong>Bulan Penggajian</strong>
                            <span>{{ $gajiBruto->bulan_penggajian ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <strong>Tahun Penggajian</strong>
                            <span>{{ $gajiBruto->tahun_penggajian ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <strong>Nominal Jabatan</strong>
                            <span>Rp {{ number_format($gajiBruto->nom_jabatan ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <strong>Nominal Khusus</strong>
                            <span>Rp {{ number_format($gajiBruto->nom_khusus ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <strong>Nominal Lainnya</strong>
                            <span>Rp {{ number_format($gajiBruto->nom_lainnya ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <strong>Total Bruto</strong>
                            <span>Rp {{ number_format($gajiBruto->total_bruto ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <hr class="border-green-700 mb-6 w-1/2 mx-auto">

        <!-- Bagian Potongan -->
        <div class="text-center">
            <h2 class="text-lg font-semibold mb-4">Potongan-Potongan</h2>

            @php
                $fields = [
                    'simpanan_wajib' => 'Simpanan Wajib',
                    'simpanan_pokok' => 'Simpanan Pokok',
                    'ibi' => 'IBI',
                    'idi' => 'IDI',
                    'ppni' => 'PPNI',
                    'pinjam_kop' => 'Pinjaman Koperasi',
                    'obat' => 'Obat',
                    'a_b' => 'Angsuran Bank',
                    'a_p' => 'Angsuran Perum',
                    'dansos' => 'Dansos',
                    'dplk' => 'DPLK',
                    'bpjs_tk' => 'BPJS Tenaga Kerja',
                    'bpjs_kes' => 'BPJS Kesehatan',
                    'rek_bpjs_kes' => 'Rekonsiliasi BPJS Kesehatan',
                    'bpjs_tambahan' => 'BPJS Kesehatan Ortu/Tambahan',
                    'pph_21' => 'PPH 21',
                    'pph_kurang' => 'Kurangan PPH 21 Tahun 2000?',
                    'angsuran_kurban' => 'Angsuran Kurban',
                    'amaliah' => 'Amaliah Ramadhan',
                    'ranap' => 'Ranap',
                    'potongan_selisih' => 'Potongan Selisih',
                    'perkasi' => 'Iuran Perkasi',
                    'lain_lain' => 'Lain-lain',
                ];
            @endphp

            <div class="space-y-2">
                @foreach ($fields as $key => $label)
                    <div class="flex justify-between items-center w-full max-w-md mx-auto">
                        <label class="font-semibold w-1/2 text-left">{{ $label }}</label>
                        <span class="text-right block w-1/2">
                            Rp {{ number_format($$key ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <hr class="border-green-700 my-6 w-1/2 mx-auto">

            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>Jumlah Potongan</strong>
                <span>
                    Rp {{ number_format($this->totalPotongan, 0, ',', '.') }}
                </span>
            </div>

            <div class="flex justify-between w-full max-w-md mx-auto mt-2">
                <strong>Gaji Netto</strong>
                <span>
                    Rp {{ number_format(($gajiBruto->total_bruto ?? 0) - $this->totalPotongan, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </x-card>
</div>
