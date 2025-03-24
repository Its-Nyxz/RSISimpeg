<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Shift</h1>
        <a href="{{ route('shift.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <!-- Unit Kerja -->
            <div class="col-span-2 relative">
                <label for="unitkerja" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                @if ($unit_id)
                    {{-- Jika unit_id dari user tersedia (non-editable) --}}
                    <input type="text" id="unitkerja" value="{{ $unit_kerja }}"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5"
                        readonly />
                @else
                    {{-- Jika unit_id tidak tersedia, tampilkan dropdown untuk memilih unit --}}
                    <input type="text" id="unitkerja" wire:model.live="unit_kerja"
                        wire:focus="fetchSuggestions('unit_kerja', $event.target.value)"
                        wire:input="fetchSuggestions('unit_kerja', $event.target.value)"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                        placeholder="Cari Unit Kerja..." />

                    {{-- Dropdown --}}
                    @if (!empty($unitKerjaOptions))
                        <ul class="absolute w-full bg-white shadow-md rounded-lg mt-1 overflow-hidden z-10">
                            @foreach ($unitKerjaOptions as $unit)
                                <li class="p-2 hover:bg-green-200 cursor-pointer"
                                    wire:click="selectUnitKerja('{{ $unit->id }}', '{{ $unit->nama }}')">
                                    {{ $unit->nama }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif

                @error('unit_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Shift -->
            <div class="form-group col-span-2">
                <label for="nama_shift" class="block text-sm font-medium text-green-900">Kode Shift</label>
                <input type="text" id="nama_shift" wire:model="nama_shift"
                    class="form-control @error('nama_shift') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('nama_shift')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jam Masuk -->
            <div class="form-group col-span-2">
                <label for="jam_masuk" class="block text-sm font-medium text-green-900">Jam Masuk</label>
                <input type="time" id="jam_masuk" wire:model="jam_masuk"
                    class="form-control @error('jam_masuk') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('jam_masuk')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Jam Keluar -->
            <div class="form-group col-span-2">
                <label for="jam_keluar" class="block text-sm font-medium text-green-900">Jam Keluar</label>
                <input type="time" id="jam_keluar" wire:model="jam_keluar"
                    class="form-control @error('jam_keluar') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('jam_keluar')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Keterangan -->
            <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-green-900">Keterangan</label>
                <input type="text" id="keterangan" wire:model="keterangan"
                    class="form-control @error('keterangan') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('keterangan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
            </button>
        </div>
    </form>
</div>
