<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Edit Data Proposionalitas Point</h1>   
        <a href="{{ route('proposionalitas.index') }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="updateProposionalitas">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <!-- Pilih Nama Master -->
            <div class="col-span-2 relative">
                <label for="proposable_id" class="block text-sm font-medium text-green-900">Nama Master</label>
                <input type="text" id="proposable_search" oninput="filterDropdown('proposable')" onclick="toggleDropdown('proposable')"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" 
                    placeholder="Cari Nama Master..." value="{{ $proposable_name }}">
                <ul id="proposableDropdown" class="dropdown absolute bg-white border border-gray-300 rounded-lg shadow-lg w-full hidden z-10">
                    @foreach($proposables as $item)
                        <li class="dropdown-item px-4 py-2 hover:bg-gray-100 cursor-pointer"
                            onclick="selectItem('proposable', '{{ $item->kategorijabatan->nama ?? $item->name }}', '{{ $item->id }}')">
                            {{ $item->kategorijabatan->nama ?? $item->name }}
                        </li>
                    @endforeach
                </ul>
                <input type="hidden" id="proposable_id" wire:model.defer="proposable_id">
                @error('proposable_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Pilih Unit Kerja -->
            <div class="col-span-2 relative">
                <label for="unit_id" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <input type="text" id="unit_search" oninput="filterDropdown('unit')" onclick="toggleDropdown('unit')"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" 
                    placeholder="Cari Unit Kerja..." value="{{ $unitkerja_name }}">
                <ul id="unitDropdown" class="dropdown absolute bg-white border border-gray-300 rounded-lg shadow-lg w-full hidden z-10">
                    @foreach($unitkerjas as $unit)
                        <li class="dropdown-item px-4 py-2 hover:bg-gray-100 cursor-pointer"
                            onclick="selectItem('unit', '{{ $unit->nama }}', '{{ $unit->id }}')">
                            {{ $unit->nama }}
                        </li>
                    @endforeach
                </ul>
                <input type="hidden" id="unit_id" wire:model.defer="unit_id">
                @error('unit_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Input Level Point -->
            <div class="col-span-2">
                <label for="point" class="block text-sm font-medium text-green-900">Level Point</label>
                <input type="number" id="point" wire:model.defer="point" required
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white p-2.5" placeholder="Masukkan Point" />
                @error('point') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group flex justify-end mt-6">
                <button type="submit"
                    class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Perbarui
                </button>
        </div>
    </form>

    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <script>
        // Toggle dropdown visibility
        function toggleDropdown(id) {
            document.getElementById(id + 'Dropdown').classList.toggle('hidden');
        }

        // Filter dropdown items based on search input
        function filterDropdown(id) {
            const input = document.getElementById(id + '_search').value.toLowerCase();
            const dropdown = document.getElementById(id + 'Dropdown');
            const items = dropdown.getElementsByTagName('li');

            dropdown.classList.remove('hidden');

            for (let item of items) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(input) ? '' : 'none';
            }
        }

        // Select an item from dropdown
        function selectItem(field, name, id) {
            document.getElementById(field + '_search').value = name;
            document.getElementById(field + '_id').value = id;
            @this.set(field + '_id', id);
            document.getElementById(field + 'Dropdown').classList.add('hidden');
        }
    </script>
</div>
