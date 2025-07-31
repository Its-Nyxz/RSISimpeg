<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-success-900">Tambah Data Potongan</h1>
        <a href="{{ route('potongan.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-success-50 border border-success-200 rounded-lg shadow p-6">

            <!-- Nama Potongan -->
            <div>
                <label for="nama" class="block mb-1 text-sm font-semibold text-success-900">Nama Potongan</label>
                <input type="text" id="nama" wire:model="nama"
                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-success-200"
                    placeholder="Contoh: bpjs_tk" />
                @error('nama')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal Potongan -->
            <div>
                <label for="nominal" class="block mb-1 text-sm font-semibold text-success-900">Nominal Potongan
                    (Opsional)</label>
                <input type="number" id="nominal" wire:model="nominal" min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-success-200"
                    placeholder="Masukkan nominal default (jika ada)" />
                @error('nominal')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <!-- Checkbox: Is Wajib -->
            <div class="md:col-span-2 flex items-center space-x-2 mt-2">
                <input type="checkbox" wire:model="is_wajib" id="is_wajib" class="form-checkbox text-success-600">
                <label for="is_wajib" class="text-sm font-semibold text-success-900">Wajib? (BPJS, dll)</label>
            </div>

            <!-- Tombol Submit -->
            <div class="md:col-span-2 mt-4">
                <button type="submit"
                    class="bg-success-600 text-white px-6 py-2 rounded hover:bg-success-700 transition duration-150">
                    Simpan
                </button>
            </div>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="mt-4 p-3 bg-success-100 text-success-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
</div>
