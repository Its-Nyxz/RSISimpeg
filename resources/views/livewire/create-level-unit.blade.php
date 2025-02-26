<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Level Unit</h1>   
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <!-- Unit Kerja -->
            <div class="col-span-2 relative">
                <label for="unitkerja" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <input type="text" id="unitkerja" wire:model="unit_kerja" 
                    wire:focus="fetchSuggestions('unit_kerja', $event.target.value)"
                    wire:input="fetchSuggestions('unit_kerja', $event.target.value)"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" placeholder="Cari Unit Kerja..." />

                <!-- Dropdown -->
                @if(!empty($unitKerjaOptions))
                    <ul class="absolute w-full bg-white shadow-md rounded-lg mt-1 overflow-hidden z-10">
                        @foreach ($unitKerjaOptions as $unit)
                            <li class="p-2 hover:bg-green-200 cursor-pointer" wire:click="selectUnitKerja('{{ $unit->id }}', '{{ $unit->nama }}')">
                                {{ $unit->nama }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @error('unit_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Level Point -->
            <div class="col-span-2 relative">
                <label for="levelpoint" class="block text-sm font-medium text-green-900">Level Point</label>
                <input type="text" id="levelpoint" wire:model="level_point" 
                    wire:focus="fetchSuggestions('level_point', $event.target.value)"
                    wire:input="fetchSuggestions('level_point', $event.target.value)"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" placeholder="Cari Level Point..." />

                <!-- Dropdown -->
                @if(!empty($levelPointOptions))
                    <ul class="absolute w-full bg-white shadow-md rounded-lg mt-1 overflow-hidden z-10">
                        @foreach ($levelPointOptions as $level)
                            <li class="p-2 hover:bg-green-200 cursor-pointer" wire:click="selectLevelPoint('{{ $level->id }}', '{{ $level->nama }}')">
                                {{ $level->nama }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @error('level_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>
