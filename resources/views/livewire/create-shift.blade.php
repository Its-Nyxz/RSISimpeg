<div>

    <h1 class="text-2xl font-bold text-success-900" style="margin-bottom: 20px;">Tambah Shift</h1>
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <!-- Nama Shift -->

            <div class="form-group col-span-2">
                <label for="nama_shift" class="block text-sm font-medium text-green-900">Nama Shift</label>
                <input type="text" id="nama_shift" wire:model="nama_shift" class="form-control @error('nama_shift') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('nama_shift')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jam Masuk -->
            <div class="form-group col-span-2">
                <label for="jam_masuk" class="block text-sm font-medium text-green-900">Jam Masuk</label>
                @error('jam_masuk')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Jam Keluar -->
            <div class="form-group col-span-2">
                <label for="jam_keluar" class="block text-sm font-medium text-green-900">Jam Keluar</label>
                <input type="time" id="jam_keluar" wire:model="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('jam_keluar')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Keterangan -->
            <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-green-900">Keterangan</label>
                <input type="text" id="keterangan" wire:model="keterangan" class="form-control @error('keterangan') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('keterangan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
</div>