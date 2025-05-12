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
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                    placeholder="Cari Jabatan..." wire:blur="hideSuggestions('jabatan')" autocomplete="off">

                @if (!empty($suggestions['jabatan']))
                    <ul
                        class="absolute z-20 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                        @foreach ($suggestions['jabatan'] as $tunjangan => $list)
                            <li class="bg-gray-100 px-4 py-2 font-bold text-gray-600 uppercase">
                                {{ ucfirst($tunjangan) }}</li>
                            @foreach ($list as $suggestion)
                                <li wire:click="selectSuggestion('jabatan', '{{ $suggestion }}')"
                                    class="px-4 py-2 hover:bg-green-700 hover:text-white cursor-pointer transition duration-200">
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
                <label for="min_masa_kerja">Minimal Masa Kerja</label>
            </td>
            <td>
                <input type="number" id="min_masa_kerja" wire:model.live="min_masa_kerja"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
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
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
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
                <input type="number" id="nominal" wire:model.live="nominal"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                    min="0" placeholder="Masukkan Nominal" required />
                @error('nominal')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>

    </table>
    <div class="flex justify-end">
        @if ($id)
            <button type="button"
                onclick="confirmRemove('Apakah Anda yakin ingin menghapus Gaji Pokok Kontrak ini?', () => @this.call('removeKontrak'))"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveKontrak"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>
