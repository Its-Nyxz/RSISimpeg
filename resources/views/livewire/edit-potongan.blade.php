<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-900">Edit Data Potongan</h1>
        <a href="{{ route('potongan.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form wire:submit.prevent="updatePotongan">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-green-50 border border-green-200 rounded-lg shadow p-6">

            <!-- Nama Potongan -->
            <div>
                <label for="nama" class="block mb-1 text-sm font-semibold text-green-900">Nama Potongan</label>
                <input type="text" id="nama" wire:model.defer="nama"
                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-200"
                    placeholder="Contoh: bpjs_tk" />
                @error('nama')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal -->
            <div>
                <label for="nominal" class="block mb-1 text-sm font-semibold text-green-900">
                    Nominal Potongan (Opsional)
                </label>
                <input type="number" id="nominal" wire:model.defer="nominal" min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-200"
                    placeholder="Masukkan nominal default" />
                @error('nominal')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Wajib? -->
            <div class="md:col-span-2 flex items-center space-x-2 mt-2">
                <input type="checkbox" wire:model.defer="is_wajib" id="is_wajib" class="form-checkbox text-green-600">
                <label for="is_wajib" class="text-sm font-semibold text-green-900">Wajib? (BPJS, dll)</label>
            </div>

            <!-- Tombol -->
            <div class="md:col-span-2 flex justify-end mt-4">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition duration-150">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
</div>
