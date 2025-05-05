<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-green-900">
            {{ $tipe === 'edit' ? 'Edit Penyesuaian Pendidikan' : 'Tambah Penyesuaian Pendidikan' }}
        </h1>

        <div>
            <a href="{{ route('penyesuaian.index') }}"
                class="text-green-900 bg-green-100 hover:bg-green-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali</a>
        </div>
    </div>
    <div>
        <livewire:add-penyesuaian :tipe="$tipe" :id="$penyesuaian" />
    </div>

</x-body>
