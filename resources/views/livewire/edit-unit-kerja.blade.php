<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Edit Data Unit Kerja</h1>
        <a href="{{ route('unitkerja.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="updateUnitKerja">
        <!-- Form Input -->
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <!-- Nama -->
            <div class="form-group col-span-2">
                <label for="nama" class="block text-sm font-medium text-success-900">Nama Unit Kerja</label>
                <input type="text" id="nama" wire:model="nama"
                    class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-span-2">
                <label for="kode" class="block text-sm font-medium text-success-900">Kode Unit Kerja</label>
                <input type="text" id="kode" wire:model="kode"
                    class="form-control @error('kode') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                @error('kode')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-success-900">Keterangan</label>
                <textarea id="keterangan" wire:model="keterangan"
                    class="form-control @error('keterangan') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"></textarea>
                @error('keterangan')
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
