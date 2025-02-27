<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            {{-- Data Pegawai --}}
            <div>
                <x-card title="Data Pegawai" class="mb-3">
                    <table class="w-full border-separate border-spacing-y-4">
                        <tr>
                            <td style="width: 40%">
                                <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">
                                    Nama Pegawai *</label>
                            </td>
                            <td>
                                <input type="text" id="nama" wire:model.live="nama"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="Nama Pegawai" required />
                                @error('nama')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="nip" class="block mb-2 text-sm font-medium text-gray-900">
                                    NIP *</label>
                            </td>
                            <td>
                                <input type="text" id="nip" wire:model.live="nip"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="NIP" required />
                                @error('nip')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                                    Email *</label>
                            </td>
                            <td>
                                <input type="text" id="email" wire:model.live="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="Email Pegawai" required />
                                @error('email')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="no_ktp" class="block mb-2 text-sm font-medium text-gray-900">
                                    No KTP *</label>
                            </td>
                            <td>
                                <input type="text" id="no_ktp" wire:model.live="no_ktp"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="No KTP" required />
                                @error('no_ktp')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900">
                                    No Hp *</label>
                            </td>
                            <td>
                                <input type="text" id="no_hp" wire:model.live="no_hp"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="No HP" required />
                                @error('no_hp')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="no_ktp" class="block mb-2 text-sm font-medium text-gray-900">
                                    No Rek *</label>
                            </td>
                            <td>
                                <input type="text" id="no_rek" wire:model.live="no_rek"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="No Rek" required />
                                @error('no_rek')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900">
                                    Kategori Pendidikan *</label>
                            </td>
                            <td>
                                <select id="pendidikans" wire:model.live="selectedPendidikan"
                                    wire:input='selectGolongan'
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    required>
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach ($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->id }}">
                                            {{ $pendidikan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedPendidikan')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%">
                                <label for="namapendidikan" class="block mb-2 text-sm font-medium text-gray-900">
                                    Pendidikan *</label>
                            </td>
                            <td>
                                <input type="text" id="namapendidikan" wire:model.live="namapendidikan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="Pendidikan Pegawai" required />
                                @error('namapendidikan')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%">
                                <label for="institusi" class="block mb-2 text-sm font-medium text-gray-900">
                                    Institusi *</label>
                            </td>
                            <td>
                                <input type="text" id="institusi" wire:model.live="institusi"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="Institusi Pegawai" required />
                                @error('institusi')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="jk" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jenis Kelamin *</label>
                            </td>
                            <td>
                                <label class="flex items-center">
                                    <input type="radio" name="jk" id="laki" wire:model.live="jk"
                                        value="1"
                                        class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                    <span class="ml-2 text-gray-900">Laki-laki</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="jk" id="perempuan" wire:model.live="jk"
                                        value="0"
                                        class="form-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                    <span class="ml-2 text-gray-900">Perempuan</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="alamat"
                                    class="block mb-2 text-sm font-medium text-gray-900 ">Alamat</label>
                            </td>
                            <td>
                                <textarea id="alamat" wire:model.live="alamat"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 "
                                    placeholder="Masukkan alamat" rows="3"></textarea>
                                @error('alamat')
                                    <span class="text-sm text-red-500 font-semibold">{{ the_message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="tempat" class="block mb-2 text-sm font-medium text-gray-900">
                                    Tempat Lahir *</label>
                            </td>
                            <td>
                                <input type="text" id="tempat" wire:model.live="tempat"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="Tempat Lahir" required />
                                @error('tempat')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr wire:ignore>
                            <td class="w-1/3">
                                <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-900">
                                    Tanggal Lahir *
                                </label>
                            </td>
                            <td>
                                <input type="date" id="tanggal_lahir" wire:model.live="tanggal_lahir"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    required placeholder="Pilih tanggal">
                                @error('tanggal_lahir')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        @push('scripts')
                            <!-- Flatpickr CSS -->
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                            <!-- Flatpickr JS -->
                            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Select the Tanggal Lahir input element
                                    const dateInput = document.querySelector("#tanggal_lahir");

                                    // Initialize Flatpickr
                                    flatpickr("#tanggal_lahir", {
                                        altInput: true,
                                        altFormat: "F j, Y",
                                        dateFormat: "Y-m-d",
                                        // maxDate: "today", // Restrict future dates
                                        onChange: function(selectedDates, dateStr, instance) {
                                            // Notify Livewire about the Tanggal Lahir change
                                            const event = new Event('input', {
                                                bubbles: true
                                            });
                                            dateInput.dispatchEvent(event);
                                        }
                                    });

                                    // No defaultDate is set for Tanggal Lahir
                                });
                            </script>
                        @endpush
                    </table>
                </x-card>
            </div>
        </div>
        <div>
            <div>
                <x-card title="Detail Pegawai" class="mb-3">
                    <table class="w-full border-separate border-spacing-y-4">
                        <tr>
                            <td style="width: 40%">
                                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">
                                    Unit Kerja *</label>
                            </td>
                            <td class="relative">
                                <input type="text" wire:model.live="unit"
                                    wire:input="fetchSuggestions('unit', $event.target.value)"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-700 focus:border-blue-700 block w-full p-2.5"
                                    placeholder="Cari Unit Kerja..." wire:blur="hideSuggestions('unit')" required>

                                @if ($suggestions['unit'])
                                    <ul
                                        class="absolute z-20 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                        @foreach ($suggestions['unit'] as $suggestion)
                                            <li wire:click="selectSuggestion('unit', '{{ $suggestion }}')"
                                                class="px-4 py-2 hover:bg-blue-700 hover:text-white cursor-pointer transition duration-200">
                                                {{ $suggestion }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 40%">
                                <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jabatan *</label>
                            </td>
                            <td class="relative">
                                <input type="text" wire:model.live="jabatan"
                                    wire:focus="fetchSuggestions('jabatan', $event.target.value)"
                                    wire:input="fetchSuggestions('jabatan', $event.target.value)"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-700 focus:border-green-700 block w-full p-2.5"
                                    placeholder="Cari Jabatan..." wire:blur="hideSuggestions('jabatan')" required>

                                @if (!empty($suggestions['jabatan']))
                                    <ul
                                        class="absolute z-20 w-full bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                        @foreach ($suggestions['jabatan'] as $tunjangan => $list)
                                            <li class="bg-gray-100 px-4 py-2 font-bold text-gray-600 uppercase">
                                                {{ ucfirst($tunjangan) }}</li>
                                            @foreach ($list as $suggestion)
                                                <li wire:click="selectSuggestion('jabatan', '{{ $suggestion }}')"
                                                    class="px-4 py-2 hover:bg-green-700 hover:text-white cursor-pointer transition duration-200">
                                                    {{ $suggestion }}
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%">
                                <label for="jenis" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jenis *</label>
                            </td>
                            <td>
                                <select id="jeniskaryawan" wire:model.live="jeniskaryawan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    required>
                                    <option value="">Pilih Jenis Pegawai</option>
                                    @foreach ($jeniskaryawan as $jenis)
                                        <option value="{{ $jenis->id }}">
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jeniskaryawan')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr wire:ignore>
                            <td class="w-1/3">
                                <label for="tmt" class="block mb-2 text-sm font-medium text-gray-900">TMT
                                    *</label>
                            </td>
                            <td>
                                <input type="date" id="tmt" wire:model.live="tmt"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    required placeholder="Pilih tanggal">
                                @error('tmt')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="masakerja" class="block mb-2 text-sm font-medium text-gray-900">
                                    Masa Kerja *</label>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <input type="text" id="masakerja" wire:model.live="masakerja"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                        placeholder="Masa Kerja (dalam tahun)" readonly /> <label for="masakerja"
                                        class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm ">Tahun</label>
                                </div>
                                @error('masakerja')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="golongan" class="block mb-2 text-sm font-medium text-gray-900">
                                    Golongan *</label>
                            </td>
                            <td>
                                <input type="text" id="golongan" wire:model.live="selectedGolonganNama"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    placeholder="Golongan akan terisi otomatis" readonly />
                                @error('selectedGolonganNama')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                                {{-- <span for="golongan"
                                    class="block mb-2 text-sm font-semibold text-gray-900 uppercase ">
                                    {{ $selectedGolonganNama ?? '-' }} </span> --}}
                            </td>
                        </tr>
                        @push('scripts')
                            <!-- Flatpickr CSS -->
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

                            <!-- Flatpickr JS -->
                            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Select the TMT input element
                                    const dateInput = document.querySelector("#tmt");

                                    // Initialize Flatpickr
                                    flatpickr("#tmt", {
                                        altInput: true,
                                        altFormat: "F j, Y",
                                        dateFormat: "Y-m-d",
                                        maxDate: "today", // Restrict future dates
                                        defaultDate: "today", // Set default date to today
                                        onChange: function(selectedDates, dateStr, instance) {
                                            if (selectedDates.length > 0) {
                                                const selectedDate = new Date(selectedDates[0]);
                                                const today = new Date();

                                                let years = today.getFullYear() - selectedDate.getFullYear();
                                                const monthDiff = today.getMonth() - selectedDate.getMonth();

                                                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < selectedDate
                                                        .getDate())) {
                                                    years--;
                                                }

                                                // Update masa kerja input
                                                const masaKerjaInput = document.querySelector("#masakerja");
                                                masaKerjaInput.value = years;

                                                // Trigger Livewire updates
                                                masaKerjaInput.dispatchEvent(new Event('input', {
                                                    bubbles: true
                                                }));

                                                @this.call('selectGolongan')
                                            }
                                        }
                                    });

                                    // Dispatch initial input event to set the initial state
                                    dateInput.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    }));
                                });
                            </script>
                        @endpush
                    </table>
                </x-card>
            </div>
            <div>
                <x-card title="Lain-Lain" class="mb-3">
                    <table class="w-full border-separate border-spacing-y-4">
                        <tr>
                            <td>
                                <label for="khusus" class="block mb-2 text-sm font-medium text-gray-900">
                                    Tunjangan Khusus *</label>
                            </td>
                            <td>
                                <select id="khusus" wire:model.live="khusus"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                                    required>
                                    <option value="">Pilih Tunjangan Khusus</option>
                                    @foreach ($filteredKhusus as $khus)
                                        <option value="{{ $khus->id }}">
                                            {{ $khus->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('khusus')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%">
                                <label for="pph" class="block mb-2 text-sm font-medium text-gray-900">
                                    Kategori PPH *</label>
                            </td>
                            <td>
                                <select wire:model.live="selectedPph"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                                    <option value="">Pilih Kategori PPH</option>

                                    @foreach ($parentPphs as $parent)
                                        <optgroup label="{{ $parent->nama }}">
                                            @foreach ($childPphs->where('parent_id', $parent->id) as $child)
                                                <option value="{{ $child->id }}">{{ $child->nama }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach

                                </select>
                                @error('selectedPph')
                                    <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 40%">
                                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">
                                    Hak Akses *</label>
                            </td>
                            <td>
                                <div>
                                    <select wire:model.live="selectedRoles"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 ">
                                        <option value="">Pilih Hak Akses</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </table>
                </x-card>
            </div>
        </div>
    </div>
    <div class="flex justify-end">
        <button type="button" wire:click="save"
            class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
    @dump($errors->first())
</div>
