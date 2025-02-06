<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Tambah Data Pendidikan</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form wire:submit.prevent="store">
        <!-- Card Container -->
        <div class="bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">

            <!-- Nama Pendidikan -->
            <div class="form-group mb-4">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama Pendidikan</label>
                <input type="text" id="nama" wire:model="nama" class="form-control @error('nama') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5" required>
                @error('nama') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi Pendidikan -->
            <div class="form-group mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-green-900">Deskripsi Pendidikan</label>
                <textarea id="deskripsi" wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"></textarea>
                @error('deskripsi') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Golongan Minimal -->
            <div class="form-group mb-4">
                <label for="minim_gol" class="block text-sm font-medium text-green-900">Golongan Minimal</label>
                <div class="relative">
                    <input type="text" id="minim_gol" wire:model="minim_gol_nama" placeholder="Cari Golongan Minimal..." autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                        oninput="filterDropdown('minim_gol')" onclick="toggleDropdown('minim_gol')" />
                    
                    <ul id="minim_golDropdown" class="dropdown hidden">
                        @foreach($golongans as $golongan)
                            <li class="dropdown-item" onclick="selectItem('minim_gol', '{{ $golongan->nama }}', '{{ $golongan->id }}')">
                                {{ $golongan->nama }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('minim_gol') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Golongan Maksimal -->
            <div class="form-group mb-4">
                <label for="maxim_gol" class="block text-sm font-medium text-green-900">Golongan Maksimal</label>
                <div class="relative">
                    <input type="text" id="maxim_gol" wire:model="maxim_gol_nama" placeholder="Cari Golongan Maksimal..." autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                        oninput="filterDropdown('maxim_gol')" onclick="toggleDropdown('maxim_gol')" />
                    
                    <ul id="maxim_golDropdown" class="dropdown hidden">
                        @foreach($golongans as $golongan)
                            <li class="dropdown-item" onclick="selectItem('maxim_gol', '{{ $golongan->nama }}', '{{ $golongan->id }}')">
                                {{ $golongan->nama }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('maxim_gol') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="form-group flex justify-end space-x-2">
                <button type="submit" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan Pendidikan
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

        if (field === 'minim_gol') {
            @this.set('minim_gol', id);
        } else if (field === 'maxim_gol') {
            @this.set('maxim_gol', id);
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