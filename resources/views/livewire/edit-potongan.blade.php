<div>
    <form wire:submit.prevent="updatePotongan">
        <!-- Pilihan Nama Fungsi -->
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group">
                <label for="fungsi_id" class="block text-sm font-medium text-green-900">Nama Fungsi</label>
                <select id="fungsi_id" wire:model="fungsi_id" class="form-control @error('fungsi_id') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                    <option value="">-- Pilih Fungsi --</option>
                    @foreach($fungsis as $fungsi)
                        <option value="{{ $fungsi->id }}">{{ $fungsi->nama }}</option>
                    @endforeach
                </select>
                @error('fungsi_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Potongan -->
            <div class="form-group">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama Potongan</label>
                <input type="text" id="nama" wire:model="nama" class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="form-group">
                <label for="nominal" class="block text-sm font-medium text-green-900">Nominal</label>
                <input type="number" id="nominal" wire:model="nominal" class="form-control @error('nominal') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nominal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="form-group col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-green-900">Deskripsi</label>
                <textarea id="deskripsi" wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('deskripsi')
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
