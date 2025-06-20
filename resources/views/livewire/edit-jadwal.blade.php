<div>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Edit Jadwal Absensi</h1>
        <a href="{{ route('jadwalAbsensi.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <form wire:submit.prevent="updateJadwal">
        <!-- Form Edit Jadwal -->
        <div class="grid grid-cols-1 gap-4 bg-success-100 border border-success-200 rounded-lg shadow-lg p-6">
            <!-- Nama (User) -->
            <div class="col-span-2">
                <label for="nama" class="block text-sm font-medium text-success-900">Nama</label>
                <div class="relative">
                    <input type="text" id="nama" wire:model.lazy="user_nama" placeholder="Cari nama..."
                        autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                        oninput="filterDropdown('nama')" onclick="toggleDropdown('nama')" />
                    <ul id="namaDropdown" class="dropdown hidden">
                        @foreach ($users as $user)
                            <li class="dropdown-item"
                                onclick="selectItem('nama', '{{ $user->name }}', '{{ $user->id }}')">
                                {{ $user->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('user_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Shift -->
            <div class="col-span-2">
                <label for="shift" class="block text-sm font-medium text-success-900">Shift</label>
                <div class="relative">
                    <input type="text" id="shift" wire:model.lazy="shift_nama" placeholder="Cari shift..."
                        autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                        oninput="filterDropdown('shift')" onclick="toggleDropdown('shift')" />
                    <ul id="shiftDropdown" class="dropdown hidden">
                        @foreach ($shifts as $shift)
                            <li class="dropdown-item"
                                onclick="selectItem('shift', '{{ $shift->nama_shift }}', '{{ $shift->id }}')">
                                {{ $shift->nama_shift }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('shift_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Opsi Absensi -->
            <div class="col-span-2">
                <label for="opsi" class="block text-sm font-medium text-success-900">Opsi Absensi</label>
                <div class="relative">
                    <input type="text" id="opsi" wire:model.lazy="opsi_nama" placeholder="Cari opsi absensi..."
                        autocomplete="off"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5"
                        oninput="filterDropdown('opsi')" onclick="toggleDropdown('opsi')" />
                    <ul id="opsiDropdown" class="dropdown hidden">
                        @foreach ($opsis as $opsi)
                            <li class="dropdown-item"
                                onclick="selectItem('opsi', '{{ $opsi->name }}', '{{ $opsi->id }}')">
                                {{ $opsi->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @error('opsi_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Jadwal -->
            <div class="col-span-2">
                <label for="tanggal" class="block text-sm font-medium text-success-900">Tanggal Jadwal</label>
                <input type="date" id="tanggal" wire:model.lazy="tanggal_jadwal"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5" />
                @error('tanggal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Keterangan Absensi -->
            <div class="col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-success-900">Keterangan Absensi</label>
                <select id="keterangan" wire:model.lazy="keterangan_absen"
                    class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-success-500 focus:border-success-500 p-2.5">
                    <option value="">Pilih Keterangan Absensi</option>
                    <option value="Cuti">Cuti</option>
                    <option value="Libur">Libur</option>
                    <option value="Tugas">Tugas</option>
                    <option value="Ijin">Ijin</option>
                    <option value="Sakit">Sakit</option>
                </select>
                @error('keterangan')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>


        <!-- Tombol Submit -->
        <div class="flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
            </button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3 p-4 bg-success-200 text-success-800 rounded-lg">
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
            if (field === 'nama') {
                @this.set('user_id', id);
            } else if (field === 'shift') {
                @this.set('shift_id', id);
            } else if (field === 'opsi') {
                @this.set('opsi_id', id);
            }
            document.getElementById(field + 'Dropdown').classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            const fields = ['nama', 'shift', 'opsi'];
            fields.forEach(field => {
                const input = document.getElementById(field);
                const dropdown = document.getElementById(field + 'Dropdown');
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
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
