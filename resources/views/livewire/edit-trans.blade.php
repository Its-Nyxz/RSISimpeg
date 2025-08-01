<div>
    <form wire:submit.prevent="updateTrans">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <div class="form-group col-span-2">
                <label for="nama" class="block text-sm font-medium text-success-900">Nama</label>
                <input type="text" id="nama" wire:model="nama"
                    class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-span-2">
                <label for="nom_makan" class="block text-sm font-medium text-success-900">Nominal Makan</label>
                <input type="number" id="nom_makan" wire:model="nom_makan"
                    class="form-control @error('nom_makan') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nom_makan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal Transport -->
            <div class="form-group col-span-2">
                <label for="nom_transport" class="block text-sm font-medium text-success-900">Nominal Transport</label>
                <input type="number" id="nom_transport" wire:model="nom_transport"
                    class="form-control @error('nom_transport') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nom_transport')
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
