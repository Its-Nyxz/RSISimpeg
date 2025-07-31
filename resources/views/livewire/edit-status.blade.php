<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Edit Status</h1>
        <a href="{{ route('status.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="updateStatus">
        <!-- Card Container -->
        <div class="bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">

            <!-- Nama Status -->
            <div class="form-group mb-4">
                <label for="nama" class="block text-sm font-medium text-success-900">Nama Status</label>
                <input type="text" id="nama" wire:model="nama"
                    class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                    placeholder="Masukkan Nama Status" required>
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Keterangan -->
            <div class="form-group mb-4">
                <label for="keterangan" class="block text-sm font-medium text-success-900">Keterangan</label>
                <textarea id="keterangan" wire:model="keterangan"
                    class="form-control @error('keterangan') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                    rows="4" placeholder="Masukkan Keterangan"></textarea>
                @error('keterangan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="form-group flex justify-end">
                <button type="submit"
                    class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                    <i class="fa-solid fa-pen mr-2"></i> Simpan
                </button>
            </div>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

</div>
