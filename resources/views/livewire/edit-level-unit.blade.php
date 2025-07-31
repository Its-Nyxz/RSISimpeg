<div>
    <form wire:submit.prevent="updateLevelUnit">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <!-- Unit Kerja Dropdown -->
            <div class="form-group">
                <label for="unit_id" class="block text-sm font-medium text-green-900">Unit Kerja</label>
                <div class="relative">
                    <input type="text" id="unit_id" wire:model="unit_id_nama" placeholder="Cari Unit Kerja..." autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                        oninput="filterUnitDropdown()" onclick="toggleUnitDropdown()" />
                    
                    <ul id="unitDropdown" class="dropdown hidden">
                        @foreach($unitkerja as $item)
                            <li class="dropdown-item" onclick="selectUnitKerja('{{ $item->nama }}', '{{ $item->id }}')">
                                {{ $item->nama }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('unit_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Level Point Dropdown -->
            <div class="form-group">
                <label for="level_id" class="block text-sm font-medium text-green-900">Level Point</label>
                <div class="relative">
                    <input type="text" id="level_id" wire:model="level_id_nama" placeholder="Cari Level Point..." autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                        oninput="filterLevelDropdown()" onclick="toggleLevelDropdown()" />
                    
                    <ul id="levelDropdown" class="dropdown hidden">
                        @foreach($levelpoint as $item)
                            <li class="dropdown-item" onclick="selectLevelPoint('{{ $item->nama }} - {{ $item->point }}', '{{ $item->id }}')">
                                {{ $item->nama }} - {{ $item->point }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('level_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="form-group col-span-2 flex justify-end mt-4">
            <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

<!-- Script untuk inisialisasi Unit Kerja dan Level Point Dropdown -->
<script>
    // Fungsi untuk toggle tampilan dropdown Unit Kerja
    function toggleUnitDropdown() {
        document.getElementById('unitDropdown').classList.toggle('hidden');
    }

    // Fungsi untuk filter Unit Kerja berdasarkan input
    function filterUnitDropdown() {
        const input = document.getElementById('unit_id').value.toLowerCase();
        const dropdown = document.getElementById('unitDropdown');
        const items = dropdown.getElementsByTagName('li');

        dropdown.classList.remove('hidden');

        for (let item of items) {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(input) ? '' : 'none';
        }
    }

    // Fungsi untuk memilih Unit Kerja
    function selectUnitKerja(name, id) {
        document.getElementById('unit_id').value = name;
        @this.set('unit_id', id);
        document.getElementById('unitDropdown').classList.add('hidden');
    }

    // Fungsi untuk toggle tampilan dropdown Level Point
    function toggleLevelDropdown() {
        document.getElementById('levelDropdown').classList.toggle('hidden');
    }

    // Fungsi untuk filter Level Point berdasarkan input
    function filterLevelDropdown() {
        const input = document.getElementById('level_id').value.toLowerCase();
        const dropdown = document.getElementById('levelDropdown');
        const items = dropdown.getElementsByTagName('li');

        dropdown.classList.remove('hidden');

        for (let item of items) {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(input) ? '' : 'none';
        }
    }

    // Fungsi untuk memilih Level Point
    function selectLevelPoint(name, id) {
        document.getElementById('level_id').value = name;
        @this.set('level_id', id);
        document.getElementById('levelDropdown').classList.add('hidden');
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