<div>
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-success-900">
                Keuangan Potongan {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}
            </h1>
        </div>
        <div class="flex space-x-3 justify-end">
            <a href="{{ route('detailkeuangan.show', $user->id) }}"
                class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    {{-- ✅ Notifikasi error (global) --}}
    @if ($showNotif)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-4 px-4 py-3 rounded bg-red-100 text-red-700 border border-red-300 shadow max-w-md mx-auto">
            {{ $notifMessage }}
        </div>
    @endif


    <!-- Slip Gaji dalam Card -->
    <x-card title="{{ $user->nip ?? '-' }}" class="mb-6 text-success-900">
        <!-- Header -->
        <div class="text-center text-green-900 font-bold text-lg mb-4">
            SLIP PENERIMAAN GAJI KARYAWAN
        </div>

        <!-- Informasi Karyawan -->
        <div class="flex flex-col items-center gap-2 mb-6" x-data="{ showDetail: false }">
            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>No Urut</strong>
                <span>{{ $user->id ?? '-' }}</span>
            </div>

            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>Nama</strong>
                <span>{{ $user->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>Jenis Karyawan</strong>
                <span>{{ ucfirst(strtolower($user->jenis->nama ?? '-')) }}</span>
            </div>

            <!-- Tombol Toggle -->
            <div class="text-right w-full max-w-md mx-auto mb-2">
                <button @click="showDetail = !showDetail" class="text-sm text-success-700 hover:underline">
                    <span x-show="!showDetail">Lihat detail Gaji Bruto ⬇</span>
                    <span x-show="showDetail">Sembunyikan detail ⬆</span>
                </button>
            </div>
            <!-- Detail Gaji Bruto (hidden by default) -->
            <div x-show="showDetail" x-transition
                class="w-full max-w-md mx-auto space-y-2 text-sm bg-gray-50 p-4 rounded shadow">

                <div class="flex justify-between">
                    <strong>Bulan Penggajian</strong>
                    <span>{{ $this->gajiBruto->bulan_penggajian ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <strong>Tahun Penggajian</strong>
                    <span>{{ $this->gajiBruto->tahun_penggajian ?? '-' }}</span>
                </div>
                <!-- Gaji Pokok (Gapok) -->
                <div class="flex justify-between items-center">
                    <strong>Gaji Pokok (Gapok)</strong>
                    <span class="text-right">
                        @if (!$isKaryawanTetap)
                            @if ($jenisKaryawan === 'kontrak')
                                Rp {{ number_format($gapok, 0, ',', '.') }}
                            @else
                                <input type="number" wire:model.live="gapok"
                                    class="border border-gray-300 px-2 py-1 rounded text-sm w-32 text-right"
                                    placeholder="Gapok" />
                            @endif
                        @else
                            Rp {{ number_format($gapok, 0, ',', '.') }}
                        @endif
                    </span>
                </div>


                <div class="flex justify-between">
                    <strong>Masa Kerja</strong>
                    <span>
                        @if (!is_null($user->masa_kerja))
                            @if (strtolower($user->jenis?->nama) === 'kontrak')
                                {{ $masaKerjaTahun }} bulan
                            @else
                                {{ floor($masaKerjaTahun) }} tahun
                            @endif
                        @else
                            <em class="text-gray-500">-</em>
                        @endif
                    </span>
                </div>
                @if ($isKaryawanTetap || $jenisKaryawan === 'part time')
                    <div class="flex justify-between">
                        <strong>Tunjangan Jabatan Struktural</strong>
                        <span>Rp {{ number_format($this->gajiBruto->nom_jabatan ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Jabatan Fungsional</strong>
                        <span>Rp {{ number_format($this->gajiBruto->nom_fungsi ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Jabatan Umum</strong>
                        <span>Rp {{ number_format($this->gajiBruto->nom_umum ?? 0, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <strong>Tunjangan Transport</strong>
                    <span>Rp {{ number_format($nom_transport ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <strong>Tunjangan Makan</strong>
                    <span>Rp {{ number_format($nom_makan ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <strong>Tunjangan Khusus</strong>
                    <span>Rp {{ number_format($this->nom_khusus ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <strong>Lainnya</strong>
                    <span>Rp {{ number_format($this->gajiBruto->nom_lainnya ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Total Bruto -->
            <div class="flex justify-between w-full max-w-md mx-auto">
                <strong>Total Bruto</strong>
                <span>Rp {{ number_format($this->gajiBruto->total_bruto, 0, ',', '.') }}</span>
            </div>
        </div>

        <hr class="border-green-700 mb-6 w-1/2 mx-auto">

        <!-- Bagian Potongan -->
        <!-- Potongan Inputs -->
        <div class="text-center space-y-3">
            <h2 class="text-lg font-semibold mb-4">Potongan-Potongan</h2>

            @foreach ($masterPotongans as $potongan)
                <div class="flex justify-between items-center w-full max-w-md mx-auto">
                    <label class="font-semibold w-1/2 text-left">
                        {{ strtoupper(str_replace('_', ' ', $potongan->nama)) }}
                        @if ($potongan->is_wajib)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input type="number" wire:model.live="potonganInputs.{{ $potongan->id }}"
                        class="border border-gray-300 rounded px-3 py-1 w-1/2 text-right" />
                    @error('potonganInputs.' . $potongan->id)
                        <div class="text-red-500 text-sm mt-1 w-full">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach

            <strong>Jumlah Potongan</strong>
            <span>Rp {{ number_format($this->totalPotongan, 0, ',', '.') }}</span>
            <div class="text-center mt-6">
                <button wire:click="simpan"
                    class="bg-success-600 hover:bg-success-700 text-white px-6 py-2 rounded-lg font-semibold">
                    Simpan Potongan
                </button>
            </div>
        </div>
    </x-card>
</div>
