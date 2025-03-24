<div>
    <x-card title="{{ $judul }}">
        <p class="text-gray-600 mb-4">{{ $deskripsi }}</p>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if ($tipe === 'cuti')
            <!-- Jenis Cuti -->
            <div class="mb-4">
                <label for="jenis_cuti" class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                <select wire:model.live="jenis_cuti_id" id="jenis_cuti"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-400">
                    <option value="">Pilih Jenis Cuti</option>
                    @foreach ($jenis_cutis as $cuti)
                        <option value="{{ $cuti->id }}">{{ $cuti->nama_cuti }}</option>
                    @endforeach
                </select>
                @error('jenis_cuti_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- ✅ Tanggal Mulai -->
            <div class="mb-4">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" wire:model.live="tanggal_mulai"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-400">
                @error('tanggal_mulai')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- ✅ Tanggal Selesai -->
            <div class="mb-4">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" wire:model.live="tanggal_selesai"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-400">
                @error('tanggal_selesai')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @else
            <!-- ✅ Tanggal (Untuk Ijin dan Tukar Jadwal) -->
            <div class="mb-4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="tanggal" wire:model.live="tanggal"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-400">
                @error('tanggal')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- ✅ Pilih Shift -->
            @if ($shifts)
                <div class="mb-4">
                    <label for="shift" class="block text-sm font-medium text-gray-700">Pilih Shift</label>
                    <select wire:model.live="shift_id" id="shift"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-400">
                        <option value="">Pilih Shift</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->nama_shift }} ({{ $shift->jam_masuk }} - {{ $shift->jam_keluar }})
                            </option>
                        @endforeach
                    </select>
                    @error('shift_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endif
        @endif


        <!-- Keterangan -->
        <div class="mb-4">
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea id="keterangan" wire:model.live="keterangan"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-green-200 focus:border-green-400"></textarea>
            @error('keterangan')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end mb-3">
            <button type="button" wire:click="save"
                class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md hover:bg-green-400 transition duration-300">
                Kirim Pengajuan
            </button>
        </div>
    </x-card>
</div>
