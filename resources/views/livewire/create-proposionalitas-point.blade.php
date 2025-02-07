<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Proposionalitas Point</h1>   
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            
            <!-- Pilih Nama Master -->
            <div class="col-span-2 relative">
                <label for="proposable_id" class="block text-sm font-medium text-green-900">Nama Master</label>
                <input type="text" id="proposable_input" oninput="filterDropdown('proposable')" onclick="toggleDropdown('proposable')" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" placeholder="Cari Nama Master...">
                <ul id="proposableDropdown" class="dropdown hidden">
                    @foreach($proposables as $item)
                        <li class="dropdown-item" onclick="selectItem('proposable', '{{ $item->kategorijabatan->nama ?? $item->name }}', '{{ $item->id }}')">
                            {{ $item->kategorijabatan->nama ?? $item->name }}
                        </li>
                    @endforeach
                </ul>
                <input type="hidden" wire:model.defer="proposable_id" id="proposable">
                @error('proposable_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Pilih Unit Kerja -->
            <div class="col-span-2 relative">
                <label for="unit_id" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <input type="text" id="unit_input" oninput="filterDropdown('unit')" onclick="toggleDropdown('unit')" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" placeholder="Cari Unit Kerja...">
                <ul id="unitDropdown" class="dropdown hidden">
                    @foreach($unitkerjas as $unit)
                        <li class="dropdown-item" onclick="selectItem('unit', '{{ $unit->nama }}', '{{ $unit->id }}')">
                            {{ $unit->nama }}
                        </li>
                    @endforeach
                </ul>
                <input type="hidden" wire:model.defer="unit_id" id="unit">
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
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <script>
        function toggleDropdown(id) {
            document.getElementById(id + 'Dropdown').classList.toggle('hidden');
        }

        function filterDropdown(id) {
            const input = document.getElementById(id + '_input').value.toLowerCase();
            const dropdown = document.getElementById(id + 'Dropdown');
            const items = dropdown.getElementsByTagName('li');

            dropdown.classList.remove('hidden');

            for (let item of items) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(input) ? '' : 'none';
            }
        }

        function selectItem(field, name, id) {
            document.getElementById(field + '_input').value = name;
            document.getElementById(field).value = id;
            @this.set(field + '_id', id);
            document.getElementById(field + 'Dropdown').classList.add('hidden');
        }
    </script>

    <style>
        .dropdown {
            position: absolute;
            z-index: 10;
            background: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 150px;
            overflow-y: auto;
            width: 100%;
        }

        .dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #d1fae5;
        }

        .dropdown::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown::-webkit-scrollbar-thumb {
            background-color: #a3d9a5;
            border-radius: 4px;
        }
    </style>
</div>
