<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Edit Aktivitas Absensi</h1>
        <a href="{{ route('aktivitasabsensi.index') }}"
            class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="updateFeedback">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Nama Pegawai</label>
                <input type="text" wire:model="user_name" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>
            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Tanggal</label>
                <input type="text" wire:model="tanggal" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>

            <div class="form-group col-span-1">
                <label class="block text-sm font-medium text-green-900">Jam Masuk</label>
                <input type="text" wire:model="time_in" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>

            <div class="form-group col-span-1">
                <label class="block text-sm font-medium text-green-900">Jam Keluar</label>
                <input type="text" wire:model="time_out" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>

            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Rencana Kerja</label>
                <input type="text" wire:model="deskripsi_in" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>

            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Laporan Kerja</label>
                <input type="text" wire:model="deskripsi_out" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>

            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Keterangan</label>
                <input type="text" wire:model="keterangan" disabled
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-gray-200 p-2.5" />
            </div>

            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Feedback</label>
                <textarea wire:model="feedback"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('feedback')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-span-2">
                <label class="block text-sm font-medium text-green-900">Persetujuan Lembur</label>
                <div class="mt-2">
                    <button type="button" wire:click="setApproval(true)" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Ya
                    </button>
                    <button type="button" wire:click="setApproval(false)" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 ml-2">
                        Tidak
                    </button>
                </div>

                <!-- Notifikasi langsung di bawah tombol -->
                @if (session()->has('approval_message'))
                    <div class="mt-2 p-2 bg-green-200 text-green-800 rounded-lg">
                        {{ session('approval_message') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
            </button>
        </div>
    </form>

</div>
