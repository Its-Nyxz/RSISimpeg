<div>
    <table class="w-full border-separate border-spacing-y-4">
        <tr>
            <td>
                <label for="tahun">Tahun</label>
            </td>
            <td>
                <input type="number" id="tahun" wire:model.live="tahun"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                    min="2020" placeholder="Masukkan Tahun" required />
                @error('tahun')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="jumlah_cuti">Jumlah Cuti</label>
            </td>
            <td>
                <input type="number" id="jumlah_cuti" wire:model.live="jumlah_cuti"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                    min="0" placeholder="Masukkan Jumlah Cuti" required />
                @error('jumlah_cuti')
                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>


    </table>
    <div class="flex justify-end">
        @if ($tipe)
            <button type="button"
                onclick="confirmRemove('Apakah Anda yakin ingin menghapus Jatah Cuti Tahunan ini?', () => @this.call('removeCuti'))"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif
        <button type="button" wire:click="saveCuti"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>
