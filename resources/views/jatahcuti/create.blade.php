<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-green-900">
            {{ $tipe === 'edit' ? 'Edit Jatah Cuti Tahunan' : 'Tambah Jatah Cuti Tahunan' }}
        </h1>

        <div>
            <a href="{{ route('jatahcuti.index') }}"
                class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
                Kembali</a>
        </div>
    </div>
    <div>
        <livewire:add-jatah-cuti :tipe="$tipe" :id="$cuti" />
    </div>

</x-body>
