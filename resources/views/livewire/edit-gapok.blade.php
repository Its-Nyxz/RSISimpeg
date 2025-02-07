<div>
    <form wire:submit.prevent="updateGapok">
        <!-- Pilihan Nama Golongan -->
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">
            <div class="form-group">
                <label for="golongan_id" class="block text-sm font-medium text-green-900">Nama Golongan</label>
                <div class="relative">
                    <input type="text" id="golongan_id" wire:model="golongan_id_nama" placeholder="Cari Golongan..." autocomplete="off"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5"
                    oninput="filterGolonganDropdown()" onclick="toggleGolonganDropdown()" />
                    
                    <ul id="golonganDropdown" class="dropdown hidden">
                        @foreach($golongans as $golongan)
                            <li class="dropdown-item" onclick="selectGolongan('{{ $golongan->nama }}', '{{ $golongan->id }}')">
                                {{ $golongan->nama }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('golongan_id') 
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Nominal Gaji Pokok -->
            <div class="form-group">
                <label for="nominal_gapok" class="block text-sm font-medium text-green-900">Nominal Gaji Pokok</label>
                <input type="number" id="nominal_gapok" wire:model="nominal_gapok" class="form-control @error('nominal_gapok') is-invalid @enderror mt-1 block w-full rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 p-2.5">
                @error('nominal_gapok')
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

    <script>
        function toggleGolonganDropdown() {
            document.getElementById('golonganDropdown').classList.toggle('hidden');
        }

        function filterGolonganDropdown() {
            const input = document.getElementById('golongan_id').value.toLowerCase();
            const dropdown = document.getElementById('golonganDropdown');
            const items = dropdown.getElementsByTagName('li');

            dropdown.classList.remove('hidden');

            for (let item of items) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(input) ? '' : 'none';
            }
        }

        function selectGolongan(name, id) {
            document.getElementById('golongan_id').value = name;
            @this.set('golongan_id', id);
            document.getElementById('golonganDropdown').classList.add('hidden');
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