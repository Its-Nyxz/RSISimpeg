<div class="px-2 sm:px-0">
    <x-card title="Hak Akses & Perizinan">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
            <p class="text-sm text-gray-600">Pengaturan untuk Hak Akses dan Perizinan yang dapat diakses.</p>

            <a href="#" wire:click="openCreateModal"
                class="w-full sm:w-auto text-center text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Buat Hak Akses
            </a>
        </div>

        <div class="flex-1" style="margin-bottom: 20px;">
            <div class="relative">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Hak Akses..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-success-600" />
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($jabatanperizinan as $item)
                <x-card-tanpa-title class="mb-0">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <p class="font-medium text-gray-700 break-all">{{ $item['name'] }}</p>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                            <button type="button" 
                                class="flex-1 sm:flex-none text-success-900 px-3 py-2 rounded-md border border-gray-200 hover:bg-slate-100 transition"
                                wire:click="editJabatan({{ $item['id'] }})"
                                @click="$dispatch('open-modal', 'edit-modal', '{{ $item['id'] }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <a href="{{ route('detail.show', ['detail' => $item['id']]) }}"
                                class="flex-1 sm:flex-none text-center text-success-900 px-3 py-2 rounded-md border border-gray-200 hover:bg-slate-100 transition">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if(auth()->user()->can('hak-akses') && $item['id'] > 15)
                                <button
                                    class="flex-1 sm:flex-none text-red-600 px-3 py-2 rounded-md border border-red-100 hover:bg-red-50 transition"
                                    @click="Swal.fire({
                                        title: 'Apakah Anda yakin?',
                                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Ya, hapus',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $wire.deleteJabatan({{ $item['id'] }});
                                        }
                                    })">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </x-card-tanpa-title>
            @empty
                <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-xl">
                    <p class="text-gray-500 text-sm">Tidak ada data Jabatan ditemukan.</p>
                </div>
            @endforelse
        </div>
    </x-card>

    <x-modal name="edit-modal" maxWidth="lg" :show="false">
        <form class="p-4 sm:p-6" wire:submit.prevent="updateJabatan">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Edit Jabatan</h2>
            <div class="mb-6">
                <label for="jabatan_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Jabatan</label>
                <input type="text" id="jabatan_nama" wire:model="jabatanNama"
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 outline-none"
                    placeholder="Nama Jabatan">
            </div>
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'edit-modal')"
                    class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-success-600 text-white rounded-lg hover:bg-success-700 transition">Simpan</button>
            </div>
        </form>
    </x-modal>

    <x-modal name="create-modal" maxWidth="lg" :show="false">
        <form class="p-4 sm:p-6" wire:submit.prevent="storeJabatan">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Tambah Hak Akses</h2>
            <div class="mb-6">
                <label for="nama_jabatan" class="block text-sm font-medium text-gray-700 mb-1">Nama Hak Akses</label>
                <input type="text" id="nama_jabatan" wire:model="newJabatanNama"
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:ring-2 focus:ring-success-600 outline-none"
                    placeholder="Nama Hak Akses">
            </div>
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'create-modal')"
                    class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-success-600 text-white rounded-lg hover:bg-success-700 transition">Simpan</button>
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