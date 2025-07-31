<div class="w-full flex justify-left">
    <div class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Form Kategori PPh</h2>
        <table class="w-full border-separate border-spacing-y-4">
            {{-- Pilih Kategori Induk --}}
            <tr>
                <td>
                    <label for="parent">Kategori Induk</label>
                </td>
                <td>
                    <select id="parent" wire:model.live="parent"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5">
                        <option value="">-- Pilih Kategori Induk --</option>
                        @foreach ($parentList as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                    @error('parent')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            {{-- Input Nama Subkategori --}}
            <tr>
                <td>
                    <label for="nama">Nama Subkategori</label>
                </td>
                <td>
                    <input type="text" id="nama" wire:model.live="nama"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                        placeholder="Contoh: PPh Pasal 21 - Honorer" />
                    @error('nama')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            {{-- Textarea Keterangan --}}
            <tr>
                <td>
                    <label for="keterangan">Keterangan</label>
                </td>
                <td>
                    <textarea id="keterangan" wire:model.live="keterangan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                        rows="3" placeholder="Contoh: Berlaku untuk masa kerja di bawah 2 tahun."></textarea>
                    @error('keterangan')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        </table>

        <div class="flex justify-end mt-4">
            @if ($tipe)
                <button type="button"
                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus Kategori PPh ini?', () => @this.call('removepph'))"
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Hapus
                </button>
            @endif
            <button type="button" wire:click="savepph"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Simpan
            </button>
        </div>
    </div>
</div>
