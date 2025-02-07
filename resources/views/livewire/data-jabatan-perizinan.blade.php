<div>


    <x-card title="Hak Akses & Perizinan">
        <div class="flex justify-between items-center gap-4 mb-3">
            <p>Pengaturan untuk Hak Akses dan Perizinan yang dapat diakses.</p>

            <a href="#"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Buat Hak Akses
            </a>
        </div>

        <div class="flex-1" style="margin-bottom: 20px;">
            <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Hak Akses..."
                class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
        </div>

        @forelse ($jabatanperizinan as $item)
            <x-card-tanpa-title class="mb-4">
                <div class="flex justify-between items-center ">
                    <p>{{ $item['name'] }}</p>

                    <div class="flex justify-between items-center gap-2">
                        <button type="button" class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-item-{{ $item['id'] }}"
                            @click="$dispatch('open-modal', 'edit-modal', '{{ $item['id'] }}')">

                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <a href="{{ route('detail.show', ['detail' => $item['id']]) }}" class="btn btn-outline-success">
                            <i class="fa-solid fa-eye" style="color: #000000;"></i>
                        </a>

                    </div>
                </div>
            </x-card-tanpa-title>
        @empty
            <tr>

                <td colspan="3" class="text-center px-6 py-4">Tidak ada data Jabatan.</td>

            </tr>
        @endforelse

    </x-card>



    <!-- Modal -->

    <x-modal name="edit-modal" maxWidth="lg" :show="false">

        <form style="margin: 10px; 20px; 30px; 40px;">

            <h2 class="text-lg font-semibold mb-4">Edit Jabatan</h2>

            <div class="mb-4">

                <label for="jabatan_nama" class="block text-sm font-medium text-gray-700">Nama Jabatan</label>

                <input type="text" id="jabatan_nama" name="jabatan_nama"
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600"
                    placeholder="Nama Jabatan">

            </div>

            <div class="flex justify-end space-x-4">

                <button type="button" x-on:click="$dispatch('close-modal', 'edit-modal')"
                    class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>

                <button type="submit" class="px-4 py-2 bg-success-600 text-white rounded-lg">Simpan</button>

            </div>

        </form>

    </x-modal>


</div>
