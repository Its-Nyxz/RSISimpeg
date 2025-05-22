<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-green-900">
            {{ $tipe === 'edit' ? 'Edit Gaji Pokok Kontrak' : 'Tambah Gaji Pokok Kontrak' }}
        </h1>

        <div>
            <a href="{{ route('gapokkontrak.index') }}"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                Kembali</a>
        </div>
    </div>
    <div>
        <livewire:add-gapok-kontrak :tipe="$tipe" :id="$kontrak" />
    </div>

</x-body>
