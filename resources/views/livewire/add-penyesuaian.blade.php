<div class="w-full flex justify-left">
    <div class="w-full md:w-1/2 bg-white p-6 rounded-lg shadow-md">
        <table class="w-full border-separate border-spacing-y-4">
            {{-- Pendidikan Awal --}}
            <tr>
                <td>
                    <label for="pendidikan_awal">Pendidikan Awal</label>
                </td>
                <td>
                    <select id="pendidikan_awal" wire:model.live="pendidikan_awal"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5">
                        <option value="">-- Pilih Pendidikan Awal --</option>
                        @foreach ($pendidikans as $pendidikan)
                            <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
                        @endforeach
                    </select>
                    @error('pendidikan_awal')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            {{-- Pendidikan Penyesuaian --}}
            <tr>
                <td>
                    <label for="pendidikan_penyesuaian">Pendidikan Penyesuaian</label>
                </td>
                <td>
                    <select id="pendidikan_penyesuaian" wire:model.live="pendidikan_penyesuaian"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5">
                        <option value="">-- Pilih Pendidikan Penyesuaian --</option>
                        @foreach ($pendidikans as $pendidikan)
                            <option value="{{ $pendidikan->id }}">{{ $pendidikan->nama }}</option>
                        @endforeach
                    </select>
                    @error('pendidikan_penyesuaian')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            {{-- Masa Kerja --}}
            <tr>
                <td>
                    <label for="masa_kerja">Masa Kerja (yang dikurangi)</label>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <input type="text" id="masa_kerja" wire:model.live="masa_kerja"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-success-500 focus:border-success-500 block w-full p-2.5"
                            placeholder="Contoh: 2" />
                        <span class="text-gray-700 text-sm">tahun</span>
                    </div>
                    @error('masa_kerja')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

        </table>
        <div class="flex justify-end">
            <button type="button" wire:click="savePenyesuaian"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

        </div>
    </div>
</div>
