<div class="p-4 bg-white shadow rounded-lg">
    <h1 class="text-xl font-bold mb-4 text-gray-800">Slip Gaji Tahun {{ $tahun }}</h1>

    <div class="mb-4">
        <select wire:model.live="tahun" class="border rounded px-3 py-1 bg-white text-gray-800">
            @foreach (range(now()->year - 3, now()->year + 1) as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    @forelse ($slips as $slip)
        <div class="border rounded shadow-sm p-4 mb-3 bg-white text-gray-800 flex justify-between items-start">
            <div>
                <p><strong>Bulan:</strong> {{ $slip->bruto?->bulan_penggajian }}/{{ $slip->bruto?->tahun_penggajian }}
                </p>
                <p><strong>Total Netto:</strong> Rp {{ number_format($slip->total_netto, 0, ',', '.') }}</p>
                <p><strong>Tanggal Transfer:</strong>
                    {{ \Carbon\Carbon::parse($slip->tanggal_transfer)->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <a href="{{ route('slipgaji.download', $slip->id) }}" target="_blank"
                    class="inline-flex items-center mt-3 px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                    <i class="fas fa-file-pdf mr-1"></i> Unduh PDF
                </a>
                <button wire:click="showDetail({{ $slip->id }})"
                    class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    <i class="fas fa-eye mr-1"></i> Detail
                </button>
            </div>
        </div>
    @empty
        <p class="text-gray-500">Tidak ada slip gaji ditemukan untuk tahun ini.</p>
    @endforelse


    @if ($selectedSlip)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-xl overflow-y-auto max-h-[90vh] relative">
                <button wire:click="closeDetail"
                    class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-lg">
                    &times;
                </button>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 text-center">Detail Slip Gaji</h2>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <strong>Bulan/Tahun</strong>
                        <span>{{ $selectedSlip->bruto?->bulan_penggajian }}/{{ $selectedSlip->bruto?->tahun_penggajian }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Gaji Pokok</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_gapok ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Jabatan</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_jabatan ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Fungsipnal</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_fungsi ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Umum</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_umum ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Makan</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_makan ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Transport</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_transport ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tunjangan Khusus</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_khusus ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Lainnya</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->nom_lainnya ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between">
                        <strong>Total Bruto</strong>
                        <span>Rp {{ number_format($selectedSlip->bruto?->total_bruto ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div>
                        <strong class="block mb-1">Potongan:</strong>
                        @forelse ($selectedSlip->bruto->potongan as $p)
                            <div class="flex justify-between text-sm">
                                <span>{{ $p->masterPotongan->nama }}</span>
                                <span>Rp {{ number_format($p->nominal, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500">Tidak ada potongan.</p>
                        @endforelse

                        @if ($selectedSlip->bruto?->potongan->count())
                            <hr class="my-2">
                            <div class="flex justify-between font-semibold text-sm">
                                <span>Total Potongan</span>
                                <span>Rp
                                    {{ number_format($selectedSlip->bruto->potongan->sum('nominal'), 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                    <hr>
                    <div class="flex justify-between font-bold mt-2">
                        <span>Total Netto</span>
                        <span>Rp {{ number_format($selectedSlip->total_netto ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
