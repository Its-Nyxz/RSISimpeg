<div class="p-4 bg-white shadow rounded-lg">
    <h1 class="text-xl font-bold mb-4 text-gray-800">Peringatan Karyawan Tahun {{ $tahun }}</h1>

    <div class="mb-4">
        <select wire:model="tahun" class="border rounded px-3 py-1 bg-white text-gray-800">
            @foreach (range(now()->year - 3, now()->year + 1) as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    @forelse ($peringatans as $peringatan)
        <div class="border rounded shadow-sm p-4 mb-3 bg-white text-gray-800 flex justify-between items-start">
            <div>
                <p><strong>Tingkat:</strong>
                    {{ $peringatan->tingkat }}
                    @if ($peringatan->tingkat === 'II')
                        (Surat Peringatan 1)
                    @elseif ($peringatan->tingkat === 'III')
                        (Surat Peringatan 2)
                    @endif
                </p>
                <p><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($peringatan->tanggal_peringatan)->translatedFormat('d F Y') }}</p>
                <p><strong>Keterangan:</strong> {{ Str::limit($peringatan->keterangan, 50) }}</p>
            </div>
            <div class="space-x-2">
                @if ($peringatan->file_sp)
                    <a href="{{ Storage::url($peringatan->file_sp) }}" target="_blank"
                        class="inline-flex items-center px-3 py-1 bg-success-600 text-white text-sm rounded hover:bg-success-700">
                        <i class="fas fa-file-pdf mr-1"></i> Unduh
                    </a>
                @endif
                <button wire:click="showDetail({{ $peringatan->id }})"
                    class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    <i class="fas fa-eye mr-1"></i> Detail
                </button>
            </div>
        </div>
    @empty
        <p class="text-gray-500">Tidak ada data peringatan untuk tahun ini.</p>
    @endforelse

    @if ($selectedPeringatan)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-xl overflow-y-auto max-h-[90vh] relative">
                <button wire:click="closeDetail"
                    class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-lg">&times;</button>

                <h2 class="text-lg font-semibold text-gray-700 mb-4 text-center">Detail Peringatan</h2>

                <div class="space-y-3 text-sm text-gray-800">
                    <div class="flex justify-between">
                        <strong>Nama</strong>
                        <span>{{ $selectedPeringatan->user?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tingkat</strong>
                        <span>
                            {{ $selectedPeringatan->tingkat }}
                            @if ($selectedPeringatan->tingkat === 'II')
                                (Surat Peringatan 1)
                            @elseif ($selectedPeringatan->tingkat === 'III')
                                (Surat Peringatan 2)
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <strong>Tanggal</strong>
                        <span>{{ \Carbon\Carbon::parse($selectedPeringatan->tanggal_peringatan)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>
                        <strong class="block mb-1">Keterangan:</strong>
                        <p class="text-justify">{{ $selectedPeringatan->keterangan }}</p>
                    </div>
                    @if ($selectedPeringatan->dokumen)
                        <div>
                            <strong>Dokumen:</strong>
                            <a href="{{ Storage::url($selectedPeringatan->dokumen) }}" target="_blank"
                                class="text-blue-600 underline">Lihat File</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
