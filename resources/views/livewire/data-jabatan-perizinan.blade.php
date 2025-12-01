<div>
    <x-card title="Hak Akses & Perizinan">
        <div class="flex justify-between items-center gap-4 mb-3">
            <p>Pengaturan untuk Hak Akses dan Perizinan yang dapat diakses.</p>

            <a href="#" wire:click="openCreateModal"
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
                            wire:click="editJabatan({{ $item['id'] }})"
                            data-tooltip-target="tooltip-item-{{ $item['id'] }}"
                            @click="$dispatch('open-modal', 'edit-modal', '{{ $item['id'] }}')">

                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <a href="{{ route('detail.show', ['detail' => $item['id']]) }}"
                            class="text-success-900 px-3 py-2 rounded-md border hover:bg-slate-300">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Kepala Seksi Kepegawaian']))
                        <button
                        class="text-red-600 px-3 py-2 rounded-md border hover:bg-red-200"
                        @click="Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: 'Data yang dihapus tidak dapat dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, hapus',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.deleteJabatan({{ $item['id'] }});
                            }
                        })"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    @endif
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

        <form class="mx-5 py-5" wire:submit.prevent="updateJabatan">

            <h2 class="text-lg font-semibold mb-4">Edit Jabatan</h2>

            <div class="mb-4">

                <label for="jabatan_nama" class="block text-sm font-medium text-gray-700">Nama Jabatan</label>

                <input type="text" id="jabatan_nama" wire:model="jabatanNama"
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

    <x-modal name="create-modal" maxWidth="lg" :show="false">
        <form class="mx-5 py-5" wire:submit.prevent="storeJabatan">

            <h2 class="text-lg font-semibold mb-4">Tambah Hak Akses</h2>

            <div class="mb-4">
                <label for="nama_jabatan" class="block text-sm font-medium text-gray-700">Nama Hak Akses</label>
                <input type="text" id="nama_jabatan" wire:model="newJabatanNama"
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600"
                    placeholder="Nama Hak Akses">
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" x-on:click="$dispatch('close-modal', 'create-modal')"
                    class="px-4 py-2 bg-gray-200 rounded-lg">Batal</button>

                <button type="submit" class="px-4 py-2 bg-success-600 text-white rounded-lg">Simpan</button>
            </div>

        </form>
    </x-modal>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div x-data="{ swalData: @entangle('swalData') }" x-init="$watch('swalData', value => {
        if (value) {
            Swal.fire({
                icon: value.icon,
                title: value.title,
                text: value.text,
                timer: value.timer || 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });
            swalData = null;
        }
    })">
    </div>

</div>