<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Tambah Data Masa Kerja</h1>
        <a href="{{ route('masakerja.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <div class="form-group col-span-2">
                <label for="nama" class="block text-sm font-medium text-success-900">Nama Masa Kerja</label>
                <input type="text" id="nama" wire:model="nama"
                    class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-span-2">
                <label for="point" class="block text-sm font-medium text-success-900">Point</label>
                <input type="text" id="point" wire:model="point"
                    class="form-control @error('point') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('point')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="form-group col-span-2 flex justify-end mt-4">
                <button type="submit"
                    class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Save
                </button>
            </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <!-- Ganti 'message' dengan 'success' jika sesuai dengan session name -->
        <div class="alert alert-success mt-3 p-4 bg-success-200 text-success-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>
