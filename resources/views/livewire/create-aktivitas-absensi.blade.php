<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Tambah Aktivitas Absensi</h1>
        <a href="{{ route('aktivitasabsensi.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">

            <!-- Nama -->
            <div class="form-group col-span-2">
                <label for="user_name" class="block text-sm font-medium text-success-900">Nama</label>
                <input type="text" id="user_name" wire:model.live="user_name"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 focus:ring-success-500 focus:border-success-500 p-2.5"
                    readonly />
            </div>

            <div class="form-group col-span-2">
                <label for="tanggal" class="block text-sm font-medium text-success-900">Tanggal</label>
                <input type="date" id="tanggal" wire:model.live="tanggal"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('tanggal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Waktu Masuk -->
            <div class="form-group col-span-2">
                <label for="time_in" class="block text-sm font-medium text-success-900">Waktu Masuk</label>
                <input type="time" id="time_in" wire:model.live="time_in"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('time_in')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Waktu Pulang -->
            <div class="form-group col-span-2">
                <label for="time_out" class="block text-sm font-medium text-success-900">Waktu Pulang</label>
                <input type="time" id="time_out" wire:model.live="time_out"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('time_out')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Rencana Kerja -->
            <div class="form-group col-span-2">
                <label for="deskripsi_in" class="block text-sm font-medium text-success-900">Rencana Kerja</label>
                <input type="text" id="deskripsi_in" wire:model.live="deskripsi_in"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('deskripsi_in')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Laporan Kerja -->
            <div class="form-group col-span-2">
                <label for="deskripsi_out" class="block text-sm font-medium text-success-900">Laporan Kerja</label>
                <input type="text" id="deskripsi_out" wire:model.live="deskripsi_out"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('deskripsi_out')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi Lembur -->
            <div class="form-group col-span-2">
                <label for="deskripsi_lembur" class="block text-sm font-medium text-success-900">Deskripsi
                    Lembur</label>
                <input type="text" id="deskripsi_lembur" wire:model.live="deskripsi_lembur"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('deskripsi_lembur')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Keterangan -->
            <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-success-900">Keterangan</label>
                <textarea id="keterangan" wire:model.live="keterangan"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"></textarea>
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
</div>
