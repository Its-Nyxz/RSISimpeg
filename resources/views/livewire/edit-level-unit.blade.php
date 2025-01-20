<div>
    <form wire:submit.prevent="updateLevelUnit">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group">
                <label for="unit_id" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <select id="unit_id" wire:model="unit_id" class="form-control @error('unit_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih unit Kerja</option>
                    @foreach($unitkerja as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('unit_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="level_id" class="block text-sm font-medium text-green-900">Level Point</label>
                <select id="level_id" wire:model="level_id" class="form-control @error('level_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">Pilih Level Point</option>
                    @foreach($levelpoint as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }} - {{ $item->point }}</option>
                    @endforeach
                </select>
                @error('level_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>