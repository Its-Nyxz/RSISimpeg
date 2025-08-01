<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Tambah Data Proposionalitas Point</h1>
        <a href="{{ route('proposionalitas.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">

            <!-- Pilih Nama Master -->
            <div class="col-span-2 relative">
                <label for="proposable_id" class="block text-sm font-medium text-success-900">Nama Master</label>
                <input type="text" id="proposable_input" wire:model="proposable_nama"
                    wire:focus="fetchSuggestions('proposable', $event.target.value)"
                    wire:input="fetchSuggestions('proposable', $event.target.value)"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                    placeholder="Cari Nama Master..." autocomplete="off">

                <!-- Dropdown -->
                @if (!empty($proposableOptions))
                    <ul
                        class="absolute w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 z-50 max-h-40 overflow-y-auto">
                        @foreach ($proposableOptions as $item)
                            <li wire:click="selectItem('proposable', '{{ $item->id }}', '{{ $item->kategorijabatan->nama ?? $item->name }}')"
                                class="p-2 cursor-pointer hover:bg-success-200">
                                {{ $item->kategorijabatan->nama ?? $item->name }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @error('proposable_id')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pilih Unit Kerja -->
            <div class="col-span-2 relative">
                <label for="unit_id" class="block text-sm font-medium text-success-900">Unit Kerja</label>
                <input type="text" id="unit_input" wire:model="unit_nama"
                    wire:focus="fetchSuggestions('unit', $event.target.value)"
                    wire:input="fetchSuggestions('unit', $event.target.value)"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                    placeholder="Cari Unit Kerja..." autocomplete="off">

                <!-- Dropdown -->
                @if (!empty($unitOptions))
                    <ul
                        class="absolute w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 z-50 max-h-40 overflow-y-auto">
                        @foreach ($unitOptions as $unit)
                            <li wire:click="selectItem('unit', '{{ $unit->id }}', '{{ $unit->nama }}')"
                                class="p-2 cursor-pointer hover:bg-success-200">
                                {{ $unit->nama }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @error('unit_id')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Level Point -->
            <div class="col-span-2">
                <label for="point" class="block text-sm font-medium text-success-900">Level Point</label>
                <input type="number" id="point" wire:model.defer="point" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white p-2.5"
                    placeholder="Masukkan Point" />
                @error('point')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group flex justify-end mt-6">
            <button type="submit"
                class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>
