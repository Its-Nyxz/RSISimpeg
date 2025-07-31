<div>
    <form wire:submit.prevent="store">
        <div class="grid grid-cols-2 gap-4 bg-green-100 border border-green-200 rounded-lg shadow-lg p-6">

            <!-- Nama (User) -->
            <div class="form-group col-span-2 relative">
                <label for="nama" class="block text-sm font-medium text-green-900">Nama</label>
                <input type="text" id="nama" wire:model.lazy="nama"
                    wire:focus="fetchSuggestions('user', $event.target.value)"
                    wire:input="fetchSuggestions('user', $event.target.value)" placeholder="Cari Nama..."
                    class="form-control mt-1 block w-full rounded-lg bg-white focus:ring-green-500 p-2.5"
                    autocomplete="off" />

                <ul id="namaDropdown" class="dropdown absolute w-full bg-white rounded-lg shadow-lg mt-1 z-10"
                    wire:loading.remove>
                    @foreach ($users as $suggestion)
                        <li class="dropdown-item p-2 hover:bg-green-200 cursor-pointer"
                            wire:click="selectUser('{{ $suggestion['id'] }}', '{{ $suggestion['name'] }}')">
                            {{ $suggestion['name'] }}
                        </li>
                    @endforeach
                </ul>

                @error('user_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Shift -->
            <div class="form-group col-span-2 relative">
                <label for="shift_id" class="block text-sm font-medium text-green-900">Shift</label>
                <input type="text" id="shift_nama" wire:model.lazy="shift_nama"
                    wire:focus="fetchSuggestions('shift', $event.target.value)"
                    wire:input="fetchSuggestions('shift', $event.target.value)" placeholder="Cari Shift..."
                    class="form-control mt-1 block w-full rounded-lg bg-white focus:ring-green-500 p-2.5"
                    autocomplete="off" />

                <ul id="shiftDropdown" class="dropdown absolute w-full bg-white rounded-lg shadow-lg mt-1 z-10"
                    wire:loading.remove>
                    @foreach ($shifts as $suggestion)
                        <li class="dropdown-item p-2 hover:bg-green-200 cursor-pointer"
                            wire:click="selectShift('{{ $suggestion['id'] }}', '{{ $suggestion['nama_shift'] }}')">
                            {{ $suggestion['nama_shift'] }}
                        </li>
                    @endforeach
                </ul>

                @error('shift_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Opsi Absensi -->
            {{-- <div class="form-group col-span-2 relative">
                <label for="opsi_id" class="block text-sm font-medium text-green-900">Opsi Absensi</label>
                <input type="text" id="opsi_nama" wire:model.lazy="opsi_nama"
                    wire:focus="fetchSuggestions('opsi', $event.target.value)"
                    wire:input="fetchSuggestions('opsi', $event.target.value)" placeholder="Cari opsi absensi..."
                    class="form-control mt-1 block w-full rounded-lg bg-white focus:ring-green-500 p-2.5"
                    autocomplete="off" />

                <ul id="opsiDropdown" class="dropdown absolute w-full bg-white rounded-lg shadow-lg mt-1 z-10"
                    wire:loading.remove>
                    @foreach ($opsis as $suggestion)
                        <li class="dropdown-item p-2 hover:bg-green-200 cursor-pointer"
                            wire:click="selectOpsi('{{ $suggestion['id'] }}', '{{ $suggestion['name'] }}')">
                            {{ $suggestion['name'] }}
                        </li>
                    @endforeach
                </ul>

                @error('opsi_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

            <!-- Tanggal Jadwal -->
            <div class="form-group col-span-2">
                <label for="tanggal" class="block text-sm font-medium text-green-900">Tanggal Jadwal</label>
                <input type="date" id="tanggal" wire:model="tanggal"
                    class="form-control mt-1 block w-full rounded-lg bg-white focus:ring-green-500 p-2.5" />
                @error('tanggal')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Keterangan Absensi -->
            {{-- <div class="form-group col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-green-900">Keterangan Absensi</label>
                <select id="keterangan" wire:model="keterangan"
                     class="form-control mt-1 block w-full rounded-lg bg-white focus:ring-green-500 p-2.5">
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
            </div> --}}

            @if ($id === 'edit')
                <div class="col-span-2">
                    <label for="pergantian" class="block text-sm font-medium text-green-900">Pergantian Jadwal</label>
                    <select id="pergantian" wire:model="isPergantianJadwal"
                        class="form-control mt-1 block w-full rounded-lg border border-gray-300 bg-white focus:ring-green-500 focus:border-green-500 p-2.5">
                        <option value="">Pilih Opsi</option>
                        <option value="0">Bukan Pergantian Jadwal</option>
                        <option value="1">Pergantian Jadwal</option>
                    </select>
                </div>
            @endif
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end mt-4">
            <button type="submit"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                <i class="fa-solid fa-paper-plane mr-2"></i> Save
            </button>
        </div>
    </form>

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

            // Set the correct property name dynamically
            if (field === 'nama') {
                @this.set('user_id', id); // Set the user_id
            } else if (field === 'shift') {
                @this.set('shift_id', id); // Set the shift_id
            }

            document.getElementById(field).value = name;

            // Set the correct property name dynamically
            if (field === 'nama') {
                @this.set('user_id', id); // Set the user_id
            } else if (field === 'shift') {
                @this.set('shift_id', id); // Set the shift_id
            }

            document.getElementById(field + 'Dropdown').classList.add('hidden');
        }


        document.addEventListener('click', function(e) {
            const fields = ['nama', 'shift'];
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
            /* Menghapus border dari dropdown */
            border: none;
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
