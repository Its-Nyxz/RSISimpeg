<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Potongan</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <div class="bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <!-- Pilihan Nama Fungsi -->
            <div class="form-group mb-4 relative">
                <label for="fungsi_nama" class="block text-sm font-medium text-green-900">Nama Fungsi</label>
                <input type="text" id="fungsi_nama" wire:model.lazy="fungsi_nama" placeholder="Cari Nama Fungsi..."
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                    oninput="filterDropdown('fungsi_nama')" onclick="toggleDropdown('fungsi_nama')" autocomplete="off" />

                <ul id="fungsi_namaDropdown" class="dropdown hidden">
                    @foreach($fungsis as $fungsi)
                        <li class="dropdown-item" onclick="selectItem('fungsi_nama', '{{ $fungsi->nama }}', '{{ $fungsi->id }}')">
                            {{ $fungsi->nama }}
                        </li>
                    @endforeach
                </ul>

                @error('fungsi_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nama Potongan -->
            <div class="form-group mb-4">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama Potongan</label>
                <input type="text" id="nama" wire:model="nama" class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nama')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="form-group mb-4">
                <label for="nominal" class="block text-sm font-medium text-green-900">Nominal</label>
                <input type="number" id="nominal" wire:model="nominal" class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nominal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="form-group mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-green-900">Deskripsi</label>
                <textarea id="deskripsi" wire:model="deskripsi" class="form-control mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('deskripsi')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="form-group flex justify-end mt-6">
                <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan
                </button>
            </div>
        </div>
    </form>

    <!-- Notifikasi -->
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
        const input = document.getElementById(id).value.toLowerCase();
        const dropdown = document.getElementById(id + 'Dropdown');
        const items = dropdown.getElementsByTagName('li');

        dropdown.classList.remove('hidden');

        for (let item of items) {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(input) ? '' : 'none';
        }
    }

    function selectItem(field, name, id) {
        document.getElementById(field).value = name;
        
        if (field === 'fungsi_nama') {
            @this.set('fungsi_id', id);
        }
        
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