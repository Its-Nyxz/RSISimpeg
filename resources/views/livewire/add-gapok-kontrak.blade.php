<div>
    <table class="w-full border-separate border-spacing-y-4">

        <tr>
            <td>
                <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-900">
                    Kategori Jabatan<span class="text-sm text-red-500">*</span></label>
            </td>
            <td class="relative">
                <input id="jabatan" type="text" wire:model.live="jabatan"
                    wire:focus="fetchSuggestions('jabatan', $event.target.value)"
                    wire:input="fetchSuggestions('jabatan', $event.target.value)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                    placeholder="Cari Jabatan..." wire:blur="hideSuggestions('jabatan')" autocomplete="off">

                @if (!empty($suggestions['jabatan']))
                    <ul
                        class="absolute z-20 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                        @foreach ($suggestions['jabatan'] as $tunjangan => $list)
                            <li class="bg-gray-100 px-4 py-2 font-bold text-gray-600 uppercase">
                                {{ ucfirst($tunjangan) }}</li>
                            @foreach ($list as $suggestion)
                                <li wire:click="selectSuggestion('jabatan', '{{ $suggestion }}')"
                                    class="px-4 py-2 hover:bg-success-700 hover:text-white cursor-pointer transition duration-200">
                                    {{ $suggestion }}
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                @endif
            </td>
        </tr>

        <tr>
            <td>
                <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900">
                    Kategori Pendidikan<span class="text-sm text-red-500">*</span></label>
            </td>
            <td class="relative">
                <input id="pendidikan" type="text" wire:model.live="pendidikan"
                    wire:focus="fetchSuggestions('pendidikan', $event.target.value)"
                    wire:input="fetchSuggestions('pendidikan', $event.target.value)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                    placeholder="Cari pendidikan..." wire:blur="hideSuggestions('pendidikan')" autocomplete="off">

                @if (!empty($suggestions['pendidikan']))
                    <ul
                        class="absolute z-20 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                        @foreach ($suggestions['pendidikan'] as $suggestion)
                            <li wire:click="selectSuggestion('pendidikan', '{{ $suggestion }}')"
                                class="px-4 py-2 hover:bg-success-700 hover:text-white cursor-pointer transition duration-200">
                                {{ $suggestion }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </td>
        </tr>

        <tr>
            <td>
                <label for="min_masa_kerja">Minimal Masa Kerja</label>
            </td>
            <td>
                <input type="number" id="min_masa_kerja" wire:model.live="min_masa_kerja"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                    min="0" placeholder="Masukkan Minimal Masa Kerja" required />
                @error('min_masa_kerja')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="max_masa_kerja">Maximal Masa Kerja</label>
            </td>
            <td>
                <input type="number" id="max_masa_kerja" wire:model.live="max_masa_kerja"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                    min="0" placeholder="Masukkan Maximal Masa Kerja" required />
                @error('max_masa_kerja')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="nominal">Nominal Gaji Pokok</label>
            </td>
            <td>
                <div id="nominal"
                    class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    Rp {{ number_format($nominal, 0, ',', '.') }}
                </div>
            </td>
        </tr>
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="mb-4 p-4 bg-blue-100 text-blue-800 rounded-lg">
                {{ session('info') }}
            </div>
        @endif

        @if (session()->has('warning'))
            <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                {{ session('warning') }}
            </div>
        @endif
    </table>
    <div class="flex justify-end">
        @if ($tipe)
            <button type="button" wire:click="openPenyesuaianModal"
                class="text-indigo-900 bg-indigo-100 hover:bg-indigo-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Penyesuaian UMK
            </button>
        @endif
        @if ($tipe)
            <button type="button"
                onclick="confirmRemove('Apakah Anda yakin ingin menghapus Gaji Pokok Kontrak ini?', () => @this.call('removeKontrak'))"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveKontrak"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
    @if ($showPenyesuaianModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h2 class="text-xl font-bold mb-4">Penyesuaian Gaji</h2>

                <div class="mb-4">
                    <label for="penyesuaian_tanggal" class="block mb-1">Tanggal Berlaku</label>
                    <input type="date" id="penyesuaian_tanggal" wire:model.live="penyesuaian_tanggal"
                        class="w-full border border-gray-300 rounded p-2" />
                    @error('penyesuaian_tanggal')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="penyesuaian_nominal" class="block mb-1">Nominal Baru</label>
                    <input type="number" id="penyesuaian_nominal" wire:model.live="penyesuaian_nominal"
                        class="w-full border border-gray-300 rounded p-2" />
                    @error('penyesuaian_nominal')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="penyesuaian_keterangan" class="block mb-1">Keterangan (opsional)</label>
                    <textarea id="penyesuaian_keterangan" wire:model.live="penyesuaian_keterangan"
                        class="w-full border border-gray-300 rounded p-2 text-sm" rows="2"
                        placeholder="Contoh: Penyesuaian UMK tahun 2025"></textarea>
                    @error('penyesuaian_keterangan')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" wire:click="closePenyesuaianModal"
                        class="text-gray-600 bg-gray-100 hover:bg-gray-200 rounded px-4 py-2 mr-2">Batal</button>
                    <button type="button" wire:click="savePenyesuaian"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 rounded px-4 py-2">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($penyesuaianList) && $tipe)
        <div class="mt-6">
            <h3 class="text-lg font-bold mb-2">Riwayat Penyesuaian Gaji</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Tanggal Berlaku</th>
                            <th class="px-4 py-2">Nominal Baru</th>
                            <th class="px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penyesuaianList as $index => $item)
                            <tr
                                class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($item['tanggal_berlaku'])->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2">
                                    Rp {{ number_format($item['nominal_baru'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item['keterangan'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif


</div>
