<x-body>
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold text-green-900">Ubah Data Tunjangan Fungsional</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-700 text-white font-medium rounded-lg px-4 py-2 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <livewire:edit-fungsi :fungsiId="$fungsi->id" />
</x-body>
