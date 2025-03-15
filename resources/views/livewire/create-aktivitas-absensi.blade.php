<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Aktivitas Absensi</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <!-- Nama -->
            <div class="form-group col-span-2 relative">
                <label for="user_name" class="block text-sm font-medium text-green-900">Nama</label>
                <input type="text" id="user_name" wire:model.lazy="user_name"
                    wire:focus="fetchSuggestions('user' , $event.target.value)"
                    wire:input="fetchSuggestions('user' , $event.target.value)"
                    placeholder="Cari Nama..."
                    class="form-control mt-1 block w-full rounded-lg bg-white focus:ring-green-500 p-2.5"
                    autocomplete="off" />

                <!-- Dropdown untuk nama -->
                <ul id="namaDropdown" class="absolute w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 z-10 {{ empty($suggestions) ? 'hidden' : '' }}">
                    @foreach ($suggestions as $suggestion)
                        <li class="p-2 hover:bg-green-200 cursor-pointer" wire:click="selectUser('{{ $suggestion['id'] }}', '{{ $suggestion['name'] }}')">
                            {{ $suggestion['name'] }}
                        </li>
                    @endforeach
                </ul>

                @error('user_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Waktu Masuk -->
            <div class="form-group col-span-2">
                <label for="time_in" class="block text-sm font-medium text-green-900">Waktu Masuk</label>
                <input type="time" id="time_in" wire:model="time_in" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('time_in')<span class="text-danger text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Waktu Pulang -->
            <div class="form-group col-span-2">
                <label for="time_out" class="block text-sm font-medium text-green-900">Waktu Pulang</label>
                <input type="time" id="time_out" wire:model="time_out" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('time_out')<span class="text-danger text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Rencana Kerja -->
            <div class="form-group col-span-2">
                <label for="deskripsi_in" class="block text-sm font-medium text-green-900">Rencana Kerja</label>
                <input type="text" id="deskripsi_in" wire:model="deskripsi_in" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('deskripsi_in')<span class="text-danger text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Laporan Kerja -->
            <div class="form-group col-span-2">
                <label for="deskripsi_out" class="block text-sm font-medium text-green-900">Laporan Kerja</label>
                <input type="text" id="deskripsi_out" wire:model="deskripsi_out" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('deskripsi_out')<span class="text-danger text-sm">{{ $message }}</span>@enderror
            </div>

            <!-- Deskripsi Lembur -->
            <div class="form-group col-span-2">
                <label for="deskripsi_lembur" class="block text-sm font-medium text-green-900">Deskripsi Lembur</label>
                <input type="text" id="deskripsi_lembur" wire:model="deskripsi_lembur" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" />
                @error('deskripsi_lembur')<span class="text-danger text-sm">{{ $message }}</span>@enderror
            </div>


            <!-- Keterangan -->
            <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-green-900">Keterangan</label>
                <textarea id="keterangan" wire:model="keterangan" class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('keterangan')<span class="text-danger text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>
</div>
