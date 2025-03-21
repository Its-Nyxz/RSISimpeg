<div>
    <!-- Header -->
    <div class="flex justify-end mb-6">
        @can('tambah-history')
            <a href="#"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 mr-2 transition duration-200">
                + Tambah History
            </a>
        @endcan
        <a href="{{ route('datakaryawan.index') }}"
            class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="w-full">
            <x-card title="{{ $user->no_ktp ?? '-' }}" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div class="mb-4">
                        <strong>Nama</strong> : {{ $user->name ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Jabatan</strong> : {{ $user->kategorijabatan->nama ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tempat, Tanggal Lahir</strong> : {{ $user->tempat ?? '-' }},
                        {{ $user->tanggal_lahir ? formatDate($user->tanggal_lahir) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Tetap</strong> :
                        {{ $user->tanggal_tetap ? formatDate($user->tanggal_tetap) : '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Awal</strong> : {{ $user->pendidikanUser->deskripsi ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Penyesuaian</strong> : {{ $user->pendidikan_penyesuaian ?? '-' }}
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Penyesuaian</strong> :
                        {{ $user->tgl_penyesuaian ? formatDate($user->tgl_penyesuaian) : '-' }}
                    </div>

                    <div class="mb-4">
                        <strong>Informasi Pensiun</strong> :
                        {{ $user->pensiun ? formatDate($user->pensiun) : '-' }}
                    </div>
                </div>
                <!-- Tombol Edit Karyawan -->
                @can('edit-data-karyawan')
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('editKaryawan.edit', ['id' => $user->id]) }}"
                            class="bg-green-700 text-white hover:bg-success-800 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                            Edit Karyawan
                        </a>
                        <button x-on:click="$dispatch('open-modal', 'modal-resign')"
                            class="text-{{ $statusKaryawan == 1 ? 'success-900' : 'black' }} 
                               bg-{{ $statusKaryawan == 1 ? 'success' : 'red' }}-100 
                               hover:bg-{{ $statusKaryawan == 1 ? 'success' : 'red' }}-600 
                               hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                            @if ($statusKaryawan == 1)
                                Resign Kerja
                            @else
                                Kembali Kerja
                            @endif
                        </button>
                    </div>

                @endcan
            </x-card>
        </div>

        <div class="w-full">
            <x-card title="No recent" class="mb-6 text-success-900">
                <div style="font-family: 'Gilroy-Regular', sans-serif; font-size: 14px;">
                    <div class="mb-4">
                        <strong>Nama</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Jabatan</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Tempat Tanggal Lahir</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Tetap</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Awal</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Pendidikan Penyesuaian</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Tanggal Penyesuaian</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Naik Berkala</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Naik Golongan</strong> : -
                    </div>
                    <div class="mb-4">
                        <strong>Hak akses</strong> : {{ implode(', ', $roles) }}
                    </div>
                </div>
            </x-card>
        </div>


        <x-modal name="modal-resign" maxWidth="lg" :show="false">
            <form style="margin: 10px; 20px; 30px; 40px;"
                wire:submit.prevent="{{ $statusKaryawan == 1 ? 'resignKerja' : 'kembali' }}">
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
                        <button type="submit" wire:click="kembali()"
                            class="px-4 py-2 bg-success-600 text-white rounded-lg">Kembali Bekerja</button>
                    @endif
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
