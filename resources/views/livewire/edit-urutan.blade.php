<div>
    <div class="flex justify-between py-2 mb-3">
        <div class="mb-4">
            <!-- Tulisan Keuangan -->
            <div class="py-2 mb-3">
                <h1 class="text-2xl font-bold text-success-900">Urutan karyawan Tetap</h1>
            </div>
        </div>
    </div>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mb-5 border border-gray-200">
    <table class="w-full text-sm text-center text-gray-700">
        <thead class="text-sm uppercase bg-success-400 text-success-900 sticky top-0">
            <tr>
                <th class="px-6 py-3">No (Urutan)</th>
                <th class="px-6 py-3 text-left">Nama Karyawan</th>
                <th class="px-6 py-3">Unit Kerja</th>
                <th class="px-6 py-3">Geser</th>
            </tr>
        </thead>
        <tbody wire:sortable="updateUrutan">
            @foreach ($users as $user)
                {{-- Ubah bagian class tr ini --}}
                <tr wire:sortable.item="{{ $user->id }}" wire:key="user-row-{{ $user->id }}"
                    class="odd:bg-success-50 even:bg-success-100 border-b border-success-200 hover:bg-success-300 transition">

                    <td class="px-6 py-4 font-bold text-success-700">
                        {{ $user->urutanKeuangan->urutan ?? $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 text-left font-medium">
                        {{ $user->nama_bersih }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $user->unitKerja->nama ?? '-' }}
                    </td>

                    <td class="px-6 py-4 flex justify-center items-center gap-3">
                        <div wire:sortable.handle
                            class="cursor-grab active:cursor-grabbing p-2 bg-white/50 rounded border border-success-400 hover:bg-success-400 hover:text-white transition">
                            <i class="fa-solid fa-bars"></i>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

        <script>
            // Notifikasi jika berhasil (opsional)
            window.addEventListener('notify', event => {
                alert(event.detail);
            });
        </script>

        <style>
            .draggable-mirror {
                display: none !important;
                /* Benar-benar disembunyikan */
                opacity: 0 !important;
                pointer-events: none;
            }

            /* 2. Memunculkan Baris Asli (Source) agar tetap jelas terlihat */
            /* Secara default library akan menyamarkan baris asli, kita paksa agar tetap muncul */
            .draggable-source--is-dragging {
                opacity: 1 !important;
                background-color: #f0fdf4 !important;
                /* Warna hijau sangat muda untuk penanda */
                border: 2px dashed #10b981 !important;
                /* Beri garis putus-putus sebagai tanda sedang dipilih */
            }
        </style>
    @endpush
   
</div>
