<x-body>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-success-900">Ubah Data Tunjangan Tidak Tetap</h1>
        <a href="{{ route('trans.index') }}"
            class="flex items-center bg-success-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-success-800 focus:ring-4 focus:outline-none focus:ring-success-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <livewire:edit-trans :transId="$trans->id" />
</x-body>
