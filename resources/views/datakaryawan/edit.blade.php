<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-success-900 ">KARYAWAN EDIT</h1>
        <div>
            <a href="{{ route('detailkaryawan.show', ['detailkaryawan' => $user->id]) }}"
                class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <livewire:karyawan-form :user="$user" />
</x-body>
