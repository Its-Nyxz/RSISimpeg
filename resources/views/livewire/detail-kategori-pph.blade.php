<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-success-800">Detail Kategori PPh</h2>
        <a href="{{ route('pph.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 text-sm">
            Kembali
        </a>
    </div>

    {{-- Info Kategori --}}
    <div class="mb-6 space-y-1">
        <p><strong>Nama:</strong> {{ $kategori->nama }}</p>
        <p><strong>Keterangan:</strong> {{ $kategori->keterangan ?? '-' }}</p>
    </div>

    {{-- Subkategori --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-success-700 mb-2">Subkategori</h3>
        <ul class="list-disc list-inside space-y-1">
            @forelse ($kategori->children as $child)
                <li class="flex items-center space-x-1">
                    <span class="font-medium">{{ $child->nama }}</span>
                    <span class="text-sm text-gray-500">({{ $child->keterangan ?? '-' }})</span>
                </li>
            @empty
                <li class="text-gray-500 italic">Tidak ada subkategori.</li>
            @endforelse
        </ul>
    </div>

    {{-- Tabel Pajak --}}
    <div>
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2 gap-2">
            <h3 class="text-lg font-semibold text-success-700">Detail Pajak</h3>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                <input type="text" wire:model.live="search" placeholder="Cari batas atas / persen"
                    class="rounded-md px-3 py-2 border text-sm border-gray-300 focus:ring-success-500 focus:border-success-500 w-full sm:w-56" />

                <button type="button" wire:click="openTaxBracketForm"
                    class="flex items-center gap-2 bg-success-100 hover:bg-success-600 text-success-900 hover:text-white font-medium px-4 py-1.5 rounded-lg text-sm transition">
                    <i class="fa fa-plus"></i> Tambah
                </button>
            </div>
        </div>


        @if ($kategori->taxBrackets->isEmpty())
            <p class="text-gray-500 italic">Tidak ada data Pajak.</p>
        @else
            <div class="max-h-96 overflow-y-auto overflow-x-auto border rounded-md">
                <table class="min-w-full border text-sm text-left text-gray-800">
                    <thead class="bg-success-200 text-success-900 uppercase">
                        <tr>
                            <th class="px-4 py-2 border">Batas Bawah</th>
                            <th class="px-4 py-2 border">Batas Atas</th>
                            <th class="px-4 py-2 border">Persentase (%)</th>
                            <th class="px-4 py-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $batasBawah = 0; @endphp
                        @foreach ($kategori->taxBrackets as $tax)
                            <tr class="odd:bg-white even:bg-gray-50 border-t">
                                <td class="px-4 py-2 border">Rp {{ number_format($batasBawah, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 border">Rp {{ number_format($tax->upper_limit, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 border">{{ number_format($tax->persentase * 100, 2, ',', '.') }}%
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <button type="button" wire:click="editTaxBracket({{ $tax->id }})"
                                        class="text-success-700 hover:text-white hover:bg-success-500 px-3 py-1 border rounded-md text-sm transition">
                                        <i class="fa fa-pen"></i>
                                    </button>

                                    <button type="button"
                                        onclick="confirmRemove('Apakah Anda yakin ingin menghapus Pajak PPh ini?', () => @this.call('deleteTaxBracket', {{ $tax->id }}))"
                                        class="text-red-600 hover:text-white hover:bg-red-500 px-3 py-1 border rounded-md text-sm transition"
                                        title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @php $batasBawah = $tax->upper_limit + 1; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <!-- Modal Tambah/Edit Tax Bracket -->
    <div x-data="{ show: @entangle('showForm') }">
        <div x-show="show" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative">
                <h2 class="text-lg font-semibold text-success-700 mb-4">
                    {{ $editTaxId ? 'Edit' : 'Tambah' }} Tax Bracket
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Batas Atas (Rp)</label>
                        <input type="number" wire:model.defer="upper_limit"
                            class="w-full rounded-md border px-3 py-2 focus:ring-success-500 focus:border-success-500" />
                        @error('upper_limit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Persentase (%)</label>
                        <input type="number" wire:model.defer="persentase"
                            class="w-full rounded-md border px-3 py-2 focus:ring-success-500 focus:border-success-500" />
                        @error('persentase')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="resetForm"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm text-gray-700">
                        Batal
                    </button>
                    <button type="button" wire:click="saveTaxBracket"
                        class="px-4 py-2 bg-success-600 hover:bg-success-700 rounded-md text-white text-sm">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
