<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Tambah Data Opsi</h1>
        <a href="{{ route('opsi.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <!-- Input Nama Opsi -->
            <div class="form-group col-span-2">
                <label for="name" class="block text-sm font-medium text-success-900">Nama Opsi</label>
                <input type="text" id="name" wire:model="name"
                    class="form-control @error('name') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
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
