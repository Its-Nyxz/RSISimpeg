<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Potongan</h1>
        <a href="{{ route('potongan.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-green-50 border border-green-200 rounded-lg shadow p-6">

            <!-- Nama Potongan -->
            <div>
                <label class="block mb-1 font-semibold text-green-900">Nama Potongan</label>
                <input type="text" wire:model.live="nama"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5"
                    placeholder="Contoh: bpjs_tk" />
                @error('nama')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <!-- Jenis Potongan -->
            <div>
                <label class="block mb-1 font-semibold text-green-900">Jenis Potongan</label>
                <select wire:model.live="jenis"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5">
                    <option value="nominal">Nominal</option>
                    <option value="persentase">Persentase</option>
                </select>
                @error('jenis')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <!-- Is Wajib -->
            <div class="col-span-1 md:col-span-2 flex items-center space-x-2">
                <input type="checkbox" wire:model.live="is_wajib" id="is_wajib" class="form-checkbox">
                <label for="is_wajib" class="font-semibold text-green-900">Wajib? (BPJS, dll)</label>
            </div>

            <!-- Tombol Submit -->
            <div class="col-span-1 md:col-span-2">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition duration-150">
                    Simpan
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
