<div>
    <form wire:submit.prevent="updateKhusus">
        <!-- Form Input -->
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <!-- Nama -->
            <div class="form-group col-span-2">
                <label for="nama" class="block text-sm font-medium text-success-900">Nama Tunjangan Khusus</label>
                <input type="text" id="nama" wire:model="nama"
                    class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="form-group col-span-2">
                <label for="nominal" class="block text-sm font-medium text-success-900">Nominal</label>
                <input type="number" id="nominal" wire:model="nominal"
                    class="form-control @error('nominal') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nominal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="form-group col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-success-900">Deskripsi</label>
                <textarea id="deskripsi" wire:model="deskripsi"
                    class="form-control @error('deskripsi') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"></textarea>
                @error('deskripsi')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-success-200 text-success-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>
