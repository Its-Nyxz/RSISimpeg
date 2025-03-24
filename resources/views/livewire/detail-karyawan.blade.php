<div>
    <!-- Header -->
    <div class="flex justify-end mb-6">
        @can('tambah-history')
            <button x-on:click="$dispatch('open-modal', 'modal-History')"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 mr-2 transition duration-200">
                + Tambah History
            </button>
        @endcan
        <a href="{{ route('datakaryawan.index') }}"
            class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="w-full">
            <x-card title="No KTP : {{ $user->no_ktp ?? '-' }}" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div class="mb-4"><strong>Nama</strong> : {{ $user->name ?? '-' }}</div>
                    <div class="mb-4"><strong>Jabatan</strong> : {{ $user->kategorijabatan->nama ?? '-' }}</div>
                    <div class="mb-4">
                        <strong>Tempat, Tanggal Lahir</strong> : {{ $user->tempat ?? '-' }},
                        {{ $user->tanggal_lahir ? formatDate($user->tanggal_lahir) : '-' }}
                    </div>
                    <div class="mb-4"><strong>No Rek</strong> : {{ $user->no_rek ?? '-' }}</div>
                    <div class="mb-4"><strong>Pendidikan</strong> : {{ $user->pendidikanUser->deskripsi ?? '-' }}
                    </div>
                    <div class="mb-4"><strong>Institusi</strong> : {{ $user->institusi ?? '-' }}</div>
                    <div class="mb-4">
                        <strong>Jenis Kelamin</strong> :
                        {{ $user->jk === null ? '-' : ($user->jk == 1 ? 'Laki-Laki' : 'Perempuan') }}
                    </div>
                    <div class="mb-4"><strong>Alamat</strong> : {{ $user->alamat ?? '-' }}</div>
                    {{-- <div class="mb-4"><strong>Hak Akses</strong> : {{ $user->roles->first()->name ?? '-' }}</div> --}}
                </div>

                <!-- Tombol Edit Karyawan -->
                @can('edit-data-karyawan')
                    <div class="mt-4">
                        <a href="{{ route('editKaryawan.edit', ['id' => $user->id]) }}"
                            class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 mr-2 transition duration-200">
                            Edit Karyawan
                        </a>
                        @can('resign-kerja')
                            <button x-on:click="$dispatch('open-modal', 'modal-resign')"
                                class="text-{{ $statusKaryawan == 1 ? 'success-900' : 'black' }} bg-{{ $statusKaryawan == 1 ? 'success' : 'red' }}-100 hover:bg-{{ $statusKaryawan == 1 ? 'success' : 'red' }}-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                {{ $statusKaryawan == 1 ? 'Resign Kerja' : 'Kembali Kerja' }}
                            </button>
                        @endcan
                    </div>
                @endcan
            </x-card>
        </div>

        <div class="w-full">
            <x-card title="No recent" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div class="mb-4">
                        <strong>Nama</strong>: {{ $user->name ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Jabatan</strong>: {{ $user->kategorijabatan->nama ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tempat Tanggal Lahir</strong>:
                        {{ $user->tanggal_lahir ? formatDate($user->tanggal_lahir) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Tetap</strong>:  {{ $user->tmt ? formatDate($user->tmt) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Awal</strong>: {{ $viewPendAwal->pendidikanAwal->nama ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Penyesuaian</strong>:
                        {{ $viewPendAwal->pendidikanPenyesuaian->nama ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Penyesuaian</strong>:
                        {{ $viewPendAwal?->tanggal_penyesuaian ? formatDate($viewPendAwal?->tanggal_penyesuaian) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Naik Golongan</strong> : -
                    </div>
                    <div class="mb-4">
                        {{-- <strong>Hak akses</strong> : {{ implode(', ', $roles) }} --}}
                    </div>
                </div>
            </x-card>
        </div>


        <x-modal name="modal-resign" maxWidth="lg" :show="false">
            <form class="mx-5 py-5" wire:submit.prevent="{{ $statusKaryawan == 1 ? 'resignKerja' : 'kembaliKerja' }}">
                <h2 class="text-lg font-semibold mb-4">Resign Kerja</h2>
                <div class="mb-4">
                    <label for="alasan_resign" class="block text-sm font-medium text-gray-700 mb-2">Alasan
                        Resign</label>
                    <textarea id="alasanResign" wire:model.live="alasanResign"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 "
                        placeholder="Masukkan Alasan Resign" rows="3"></textarea>
                    @error('alasanResign')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    @if ($statusKaryawan == 1)
                        <button type="button" x-on:click="$dispatch('close-modal', 'modal-resign')"
                            class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                        <button type="submit" wire:click="resignKerja()"
                            class="px-4 py-2 bg-success-600 text-white rounded-lg">Kirim</button>
                    @else
                        <button type="submit" wire:click="kembaliKerja()"
                            class="px-4 py-2 bg-success-600 text-white rounded-lg">Kembali Bekerja</button>
                    @endif
                </div>
            </form>
        </x-modal>

        <x-modal name="modal-History" :show="false">
            <form class="mx-5 py-5" wire:submit.prevent="tambahHistory">
                <h2 class="text-lg font-semibold mb-4">Tambah History</h2>
                <div class="mb-4 flex space-x-4">
                    <div class="w-1/2">
                        <label for="tmt" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tetap /
                            Tanggal Mulai Kerja</label>
                        <input type="text" id="tmt" wire:model.live="tmt"
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                            placeholder="Belum Di Isi Silahkan isi Di Edit Karyawan" readonly>
                        @error('tmt')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <label for="pend_awal" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan
                            Awal</label>
                        <input type="text" id="pend_awal" wire:model.live="pend_awal"
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"
                            placeholder="Belum Di Isi Silahkan isi Di Edit Karyawan" readonly>
                        @error('pend_awal')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="pend_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan
                        Penyesuaian</label>
                    <select id="pend_penyesuaian" wire:model.live="pend_penyesuaian" wire:input='pend_penyesuaian'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                        <option value="">Pilih Pendidikan</option>
                        @foreach ($pendidikans as $pendidikan)
                            <option value="{{ $pendidikan->id }}">
                                {{ $pendidikan->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('pend_penyesuaian')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="tanggal_penyesuaian" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                        Penyesuaian</label>
                    <input type="date" id="tanggal_penyesuaian" wire:model.live="tanggal_penyesuaian"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 "
                        placeholder="Tanggal Penyesuaian">
                    @error('tanggal_penyesuaian')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-History')"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-success-600 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </x-modal>
    </div>
    <div class="w-full">
        <x-card title="History Cuti Tahunan" class="mb-6 text-success-900">
            <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 18px;">
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-1 justify-items-end">
                    <strong></strong>
                    <strong class="mb-3">Stok Cuti : <span
                            class=" bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                            {{ $user->jatah_cuti_tahunan ?? '-' }} </span></strong>
                    <strong class="mt-3">Cuti Digunakan : <span
                            class=" bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                            {{ $user->sisa_cuti_tahunan ?? '-' }} </span></strong>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-center text-gray-700">
                    <thead class="text-sm uppercase bg-success-400 text-success-900">
                        <tr>
                            <th scope="col" class="px-6 py-3">No.</th>
                            <th scope="col" class="px-6 py-3">Tanggal Mulai Cuti</th>
                            <th scope="col" class="px-6 py-3">Tanggal Berakhir Cuti</th>
                            <th scope="col" class="px-6 py-3">Jenis Cuti</th>
                            <th scope="col" class="px-6 py-3">Keterangan</th>
                            <th scope="col" class="px-6 py-3">Status Cuti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="odd:bg-success-50 even:bg-success-100 border-b border-success-300 hover:bg-success-300">
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">1</td>
                            <td class="px-6 py-4">Nikahan</td>
                            <td class="px-6 py-4">-</td>
                            <td class="px-6 py-4">ACC</td>
                        </tr>
                        {{-- <tr>
                            <td colspan="7" class="text-center px-6 py-4">Tidak ada data Cuti Karyawan.</td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>


</div>
