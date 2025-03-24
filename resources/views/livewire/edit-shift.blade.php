<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Edit Shift</h1>
        <a href="{{ url()->previous() }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="updateShift">
        <div class="grid grid-cols-1 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">

            {{-- <!-- Unit Kerja -->
            <div class="form-group">
                <label for="unitkerja" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <input type="text" id="unitkerja" wire:model.live="unit_kerja"
                    wire:input="fetchSuggestions('unit_kerja', $event.target.value)"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                    placeholder="Cari Unit Kerja..." />

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

                @error('unit_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div> --}}
            <div class="form-group">
                <label for="unitkerja" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <div class="block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-green-900">
                    {{ $unit_kerja ?? 'Belum diatur' }}
                </div>
            </div>

            <!-- Nama Shift -->
            <div class="form-group">
                <label for="nama_shift" class="block text-sm font-medium text-green-900">Kode Shift</label>
                <input type="text" id="nama_shift" wire:model="nama_shift"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nama_shift')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jam Masuk -->
            <div class="form-group">
                <label for="jam_masuk" class="block text-sm font-medium text-green-900">Jam Masuk</label>
                <input type="time" id="jam_masuk" wire:model="jam_masuk"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('jam_masuk')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jam Keluar -->
            <div class="form-group">
                <label for="jam_keluar" class="block text-sm font-medium text-green-900">Jam Keluar</label>
                <input type="time" id="jam_keluar" wire:model="jam_keluar"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('jam_keluar')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="form-group">
                <label for="keterangan" class="block text-sm font-medium text-green-900">Keterangan</label>
                <textarea id="keterangan" wire:model="keterangan" rows="3"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('keterangan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>
</div>
