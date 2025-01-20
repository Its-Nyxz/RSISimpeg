<div>
    <form wire:submit.prevent="store">
        <!-- Pilihan Nama Golongan -->
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group">
                <label for="golongan_id" class="block text-sm font-medium text-green-900">Nama Golongan</label>
                <select id="golongan_id" wire:model="golongan_id" class="form-control @error('golongan_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">-- Pilih Golongan --</option>
                    @foreach($golongans as $golongan)
                        <option value="{{ $golongan->id }}">{{ $golongan->nama }}</option>
                    @endforeach
                </select>
                @error('golongan_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal Gaji Pokok -->
            <div class="form-group">
                <label for="nominal_gapok" class="block text-sm font-medium text-green-900">Nominal Gaji Pokok</label>
                <input type="number" id="nominal_gapok" wire:model="nominal_gapok" class="form-control @error('nominal_gapok') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nominal_gapok')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>
